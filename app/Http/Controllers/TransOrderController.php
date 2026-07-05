<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Customer;
use App\Models\TypeOfService;
use App\Models\TransOrder;
use App\Models\TransOrderDetail;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class TransOrderController extends Controller
{
    public function index()
    {
        $orders = TransOrder::with('customer')->latest()->get();
        return view('transaction.index', compact('orders'));
    }

    public function create()
    {
        $customers = Customer::orderBy('customer_name', 'asc')->get();
        $services = TypeOfService::orderBy('service_name', 'asc')->get();
        
        return view('transaction.create', compact('customers', 'services'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_customer' => 'required',
            'order_date' => 'required|date',
            'order_end_date' => 'required|date',
            'order_pay' => 'required|numeric|min:0',
            'items' => 'required|array|min:1',
            'items.*.id_service' => 'required',
            'items.*.qty' => 'required|numeric|min:1',
        ]);

        DB::beginTransaction();
        try {
            $subtotal_laundry = 0;
            $calculated_items = [];

            foreach ($request->items as $item) {
                $service = TypeOfService::findOrFail($item['id_service']);
                $subtotal_item = $service->price * $item['qty'];
                $subtotal_laundry += $subtotal_item;

                $calculated_items[] = [
                    'id_service' => $item['id_service'],
                    'qty' => $item['qty'],
                    'subtotal' => $subtotal_item,
                    'notes' => $item['notes'] ?? null
                ];
            }

            // Hitung Pajak 5%
            $tax = $subtotal_laundry * 0.05;
            $grand_total = $subtotal_laundry + $tax;

            // Hitung Kembalian
            $order_change = $request->order_pay - $grand_total;
            if ($order_change < 0) {
                return back()->withInput()->with('error', 'Transaksi gagal: Uang pembayaran kurang dari total tagihan.');
            }

            // Generate Order Code 
            $order_code = 'TRX-' . date('Ymd') . '-' . strtoupper(Str::random(5));

            // Simpan transaction Utama
            $order = TransOrder::create([
                'id_customer' => $request->id_customer,
                'order_code' => $order_code,
                'order_date' => $request->order_date,
                'order_end_date' => $request->order_end_date,
                'order_status' => 0, 
                'order_pay' => $request->order_pay,
                'order_change' => $order_change,
                'total' => $grand_total,
            ]);

            // Simpan Detail Item
            foreach ($calculated_items as $c_item) {
                TransOrderDetail::create([
                    'id_order' => $order->id,
                    'id_service' => $c_item['id_service'],
                    'qty' => $c_item['qty'],
                    'subtotal' => $c_item['subtotal'],
                    'notes' => $c_item['notes'],
                ]);
            }

            DB::commit();
            return redirect()->route('transaction.index')->with('success', 'Data transaksi berhasil disimpan.');

        } catch (\Exception $e) {
            DB::rollback();
            return back()->withInput()->with('error', 'Terjadi kesalahan sistem: ' . $e->getMessage());
        }
    }

    
    public function show($id)
    {
        // Cari data transaksi induknya
        $order = TransOrder::with('customer')->findOrFail($id);

        // Ambil data detail cuciannya, di-join sekalian sama tabel layanannya biar dapet nama & harga
        $details = DB::table('trans_order_details')
            ->join('type_of_services', 'trans_order_details.id_service', '=', 'type_of_services.id')
            ->where('trans_order_details.id_order', $id)
            ->select('trans_order_details.*', 'type_of_services.service_name', 'type_of_services.price')
            ->get();

        // Hitung subtotal kotor (sebelum pajak) buat ditampilin di nota
        $subtotal_kotor = $details->sum('subtotal');

        return view('transaction.show', compact('order', 'details', 'subtotal_kotor'));
    }

    // TAMBAHAN BUAT UBAH STATUS
    public function updateStatus(Request $request, $id)
    {
        $order = TransOrder::findOrFail($id);
        $order->update([
            'order_status' => $request->order_status
        ]);

        return back()->with('success', 'Status cucian berhasil diupdate.');
    }
}