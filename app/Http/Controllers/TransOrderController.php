<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TransOrder;
use App\Models\TransOrderDetail;
use App\Models\Customer;
use App\Models\TypeOfService;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;


class TransOrderController extends Controller
{
    public function index()
    {
        return redirect()->route('transaction.pickup.index');
    }

    public function create()
    {
        $customers = Customer::orderBy('customer_name', 'asc')->get();
        $services = TypeOfService::all();

        // 1. Ubah bagian ini dari 'LAU-' menjadi 'LAUNDRY-'
        $datePrefix = 'LAUNDRY-' . date('ymd') . '-';

        // 2. Sistem mencari order terakhir yang menggunakan awalan baru ini
        $lastOrder = \App\Models\TransOrder::where('order_code', 'LIKE', $datePrefix . '%')
                        ->orderBy('id', 'desc')
                        ->first();

        if ($lastOrder) {
            // Karena bagian ujungnya tetap 4 digit angka, potongan teks (substr) tetap -4
            $lastNumber = (int) substr($lastOrder->order_code, -4);
            $nextNumber = $lastNumber + 1;
        } else {
            $nextNumber = 1;
        }

        // 3. Gabungkan awalan LAUNDRY-yymmdd- dengan urutan 4 digit (0001, 0002, dst)
        $order_code = $datePrefix . str_pad($nextNumber, 4, '0', STR_PAD_LEFT);

        return view('transaction.create', compact('customers', 'services', 'order_code'));
    }

    public function store(Request $request)
    {
        // 1. Validasi masukan dasar
        $request->validate([
            'id_customer' => 'required|string',
            'order_code' => 'required|string|unique:trans_order,order_code',
            'order_date' => 'required|date',
            'id_service' => 'required|array',
            'id_service.*' => 'required|exists:type_of_service,id',
            'qty' => 'required|array',
            'qty.*' => 'required|numeric|min:0.001',
            'total' => 'required|numeric',
            'payment_option' => 'required|in:upfront,pickup',
            'order_pay' => 'nullable|numeric'
        ]);

        // Validasi conditional jika customer baru didaftarkan
        if ($request->id_customer === 'new') {
            $request->validate([
                'new_customer_name' => 'required|string|max:255',
                'new_customer_phone' => 'required|string|max:15',
                'new_customer_address' => 'required|string',
            ], [
                'new_customer_name.required' => 'Nama pelanggan baru wajib diisi.',
                'new_customer_phone.required' => 'Nomor HP pelanggan baru wajib diisi.',
                'new_customer_address.required' => 'Alamat pelanggan baru wajib diisi.',
            ]);
        } else {
            $request->validate([
                'id_customer' => 'exists:customer,id',
            ]);
        }

        // Menggunakan Database Transaction demi keamanan data
        \Illuminate\Support\Facades\DB::beginTransaction();

        try {
            // Jika customer baru, buat datanya terlebih dahulu
            $customerId = $request->id_customer;
            if ($customerId === 'new') {
                $newCustomer = \App\Models\Customer::create([
                    'customer_name' => $request->new_customer_name,
                    'phone' => $request->new_customer_phone,
                    'address' => $request->new_customer_address,
                ]);
                $customerId = $newCustomer->id;
            }

            // 2. Kalkulasi Data Tambahan
            $payment_option = $request->payment_option;
            $total = $request->total;

            if ($payment_option === 'pickup') {
                $order_pay = 0;
                $order_change = 0;
                $payment_status = 1; // 1 = Hutang / Belum Lunas
            } else {
                $order_pay = $request->order_pay ?? 0;
                // Hitung uang kembalian
                $order_change = ($order_pay > $total) ? ($order_pay - $total) : 0;
                $payment_status = ($order_pay >= $total) ? 0 : 1; // 0 = Lunas, 1 = Hutang
            }

            // Set estimasi tanggal selesai
            $order_end_date = date('Y-m-d', strtotime($request->order_date . ' + 3 days'));

            // 3. Simpan ke tabel induk: trans_order
            $order = \App\Models\TransOrder::create([
                'order_code' => $request->order_code,
                'order_date' => $request->order_date,
                'order_end_date' => $order_end_date,
                'order_status' => 0,
                'id_customer' => $customerId,
                'total' => $total,
                'order_pay' => $order_pay,
                'order_change' => $order_change,
                'payment_status' => $payment_status,
                'paid_amount' => $order_pay,
            ]);

            // 4. Iterasi untuk menyimpan setiap baris layanan ke tabel anak
            foreach ($request->id_service as $key => $service_id) {
                $service = \App\Models\TypeOfService::findOrFail($service_id);
                $subtotal_item = $service->price * $request->qty[$key];

                $qty_gram = (int) ($request->qty[$key] * 1000);

                \App\Models\TransOrderDetail::create([
                    'id_order' => $order->id,
                    'id_service' => $service_id,
                    'qty' => $qty_gram,
                    'subtotal' => $subtotal_item,
                    'notes' => $request->notes[$key] ?? null,
                ]);
            }

            \Illuminate\Support\Facades\DB::commit();
            return redirect()->route('transaction.pickup.index')->with('success', 'Transaksi berhasil disimpan!');

        } catch (\Exception $e) {
            \Illuminate\Support\Facades\DB::rollback();
            return redirect()->back()->withErrors('Terjadi kesalahan sistem: ' . $e->getMessage())->withInput();
        }
    }

    public function show($id)
    {
    $order = TransOrder::with(['customer', 'details.typeOfService'])->findOrFail($id);

    // Hitung subtotal sebelum pajak
    $subtotal_kotor = $order->details->sum('subtotal');

    return view('transaction.show', compact(
        'order',
        'subtotal_kotor'
    ));
    }

    public function printInvoice($id)
    {
        $order = \App\Models\TransOrder::with(['customer', 'details.typeOfService'])->findOrFail($id);
        return view('transaction.print', compact('order'));
    }

    public function updateStatus(Request $request, $id)
    {
        $order = TransOrder::findOrFail($id);

        $rules = [
            'order_status' => 'required|in:0,1,2,3',
            'payment_status' => 'required|in:0,1',
        ];

        // Jika status pembayaran diubah dari Belum Lunas (1) menjadi Lunas (0)
        if ($request->payment_status == 0 && $order->payment_status == 1) {
            $rules['order_pay'] = 'required|numeric|min:' . $order->total;
        }

        $request->validate($rules, [
            'order_pay.required' => 'Nominal uang yang diterima wajib diisi.',
            'order_pay.min' => 'Uang yang diterima kurang dari total tagihan (Total: Rp ' . number_format($order->total, 0, ',', '.') . ').',
        ]);

        $data = [
            'order_status' => $request->order_status,
            'payment_status' => $request->payment_status,
        ];

        if ($request->payment_status == 0 && $order->payment_status == 1) {
            $order_pay = $request->order_pay;
            $order_change = $order_pay - $order->total;

            $data['order_pay'] = $order_pay;
            $data['paid_amount'] = $order->total;
            $data['order_change'] = $order_change;
        }

        $order->update($data);

        // Jika status diubah menjadi Diambil (3), pastikan log pengambilan dibuat
        if ($request->order_status == 3 && $order->order_status != 3) {
            $exists = \App\Models\TransLaundryPickup::where('id_order', $order->id)->exists();
            if (!$exists) {
                \App\Models\TransLaundryPickup::create([
                    'id_order' => $order->id,
                    'id_customer' => $order->id_customer,
                    'pickup_date' => now(),
                    'notes' => 'Diambil via pembaruan status',
                ]);
            }
        }

        return redirect()->route('transaction.show', $id)->with('success', 'Status transaksi berhasil diperbarui.');
    }

    public function pickupIndex()
    {
        // Cucian Aktif / Berjalan (status 0: Baru, 1: Proses)
        $ready_orders = TransOrder::with(['customer', 'details'])
            ->whereIn('order_status', [0, 1])
            ->orderBy('id', 'desc')
            ->get();

        // Riwayat Pengambilan (status 2: Selesai, 3: Diambil)
        $pickup_history = TransOrder::with(['customer', 'details', 'pickupLog'])
            ->whereIn('order_status', [2, 3])
            ->orderBy('id', 'desc')
            ->get();

        return view('transaction.pickup_index', compact('ready_orders', 'pickup_history'));
    }

    public function pickupShow($id)
    {
        $order = TransOrder::with(['customer', 'details.typeOfService'])->findOrFail($id);

        // Hanya izinkan order berstatus 2 (Selesai) untuk dipickup
        if ($order->order_status != 2) {
            return redirect()->route('transaction.pickup.index')->withErrors('Order belum selesai atau sudah diambil.');
        }

        return view('transaction.pickup_show', compact('order'));
    }

    public function pickupProcess(Request $request, $id)
    {
        $order = TransOrder::findOrFail($id);

        if ($order->order_status != 2) {
            return redirect()->route('transaction.pickup.index')->withErrors('Order belum selesai atau sudah diambil.');
        }

        $rules = [
            'pickup_date' => 'required|date',
            'notes' => 'nullable|string',
        ];

        // Jika belum lunas, wajib mengisikan nominal uang pembayaran
        if ($order->payment_status == 1) {
            $rules['order_pay'] = 'required|numeric|min:' . $order->total;
        }

        $request->validate($rules, [
            'order_pay.required' => 'Nominal pembayaran wajib diisi untuk pelunasan.',
            'order_pay.min' => 'Nominal pembayaran kurang dari total tagihan (Total: Rp ' . number_format($order->total, 0, ',', '.') . ').',
        ]);

        \Illuminate\Support\Facades\DB::beginTransaction();
        try {
            // Update order data
            $orderData = [
                'order_status' => 3, // Diambil
            ];

            if ($order->payment_status == 1) {
                $order_pay = $request->order_pay;
                $order_change = $order_pay - $order->total;

                $orderData['payment_status'] = 0; // Lunas
                $orderData['order_pay'] = $order_pay;
                $orderData['paid_amount'] = $order->total;
                $orderData['order_change'] = $order_change;
            }

            $order->update($orderData);

            // Simpan log ke tabel trans_laundry_pickup
            \App\Models\TransLaundryPickup::create([
                'id_order' => $order->id,
                'id_customer' => $order->id_customer,
                'pickup_date' => $request->pickup_date,
                'notes' => $request->notes,
            ]);

            \Illuminate\Support\Facades\DB::commit();
            return redirect()->route('transaction.pickup.index')->with('success', 'Cucian berhasil diambil dan transaksi telah selesai!');

        } catch (\Exception $e) {
            \Illuminate\Support\Facades\DB::rollback();
            return redirect()->back()->withErrors('Terjadi kesalahan: ' . $e->getMessage())->withInput();
        }
    }
}
