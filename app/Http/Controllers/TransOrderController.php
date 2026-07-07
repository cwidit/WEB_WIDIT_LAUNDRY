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
        $transactions = TransOrder::with(['customer', 'details'])->orderBy('id', 'desc')->get();
        return view('transaction.index', compact('transactions'));
    }

    public function create()
    {
        $customers = Customer::orderBy('customer_name', 'asc')->get();
        $services = TypeOfService::all();
        $order_code = 'ORD-' . strtoupper(Str::random(6));

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
            return redirect()->route('transaction.index')->with('success', 'Transaksi berhasil disimpan!');

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
        $request->validate([
            'order_status' => 'required|in:0,1,2,3',
            'payment_status' => 'required|in:0,1',
        ]);

        $order = TransOrder::findOrFail($id);
        
        $data = [
            'order_status' => $request->order_status,
            'payment_status' => $request->payment_status,
        ];

        // Jika status pembayaran diubah jadi Lunas (0) dan sebelumnya belum lunas (1),
        // set uang bayar jadi total harga dan hilangkan hutang.
        if ($request->payment_status == 0 && $order->payment_status == 1) {
            $data['order_pay'] = $order->total;
            $data['paid_amount'] = $order->total;
            $data['order_change'] = 0;
        }

        $order->update($data);

        return redirect()->route('transaction.show', $id)->with('success', 'Status transaksi berhasil diperbarui.');
    }
}