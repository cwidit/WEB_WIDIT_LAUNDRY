<?php

namespace Database\Seeders;

use App\Models\Customer;
use App\Models\TransOrder;
use App\Models\TransOrderDetail;
use App\Models\TypeOfService;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class DummyDashboardSeeder extends Seeder
{
    public function run(): void
    {
        // Pastikan data layanan tersedia
        $this->call(TypeOfServiceSeeder::class);

        $customersData = [
            ['customer_name' => 'Andita', 'phone' => '081234567890', 'address' => 'Jl. Mawar No. 12'],
            ['customer_name' => 'Budi', 'phone' => '081298765432', 'address' => 'Jl. Kenanga No. 45'],
            ['customer_name' => 'Citra', 'phone' => '081234098765', 'address' => 'Jl. Melati No. 9'],
        ];

        $customers = [];
        foreach ($customersData as $data) {
            $customers[$data['customer_name']] = Customer::updateOrCreate(
                ['customer_name' => $data['customer_name']],
                $data
            );
        }

        $services = TypeOfService::all()->keyBy('service_name');

        $ordersData = [
            [
                'order_code' => 'ORD-AND123',
                'customer' => 'Andita',
                'order_date' => Carbon::now()->subDays(4)->format('Y-m-d'),
                'order_status' => 2,
                'details' => [
                    ['service' => 'Cuci & Gosok', 'qty_kg' => 3.0, 'notes' => 'Selimut putih'],
                ],
                'order_pay' => 50000,
            ],
            [
                'order_code' => 'ORD-BUD456',
                'customer' => 'Budi',
                'order_date' => Carbon::now()->subDays(2)->format('Y-m-d'),
                'order_status' => 1,
                'details' => [
                    ['service' => 'Hanya Cuci', 'qty_kg' => 2.2, 'notes' => 'Kaos 5 buah'],
                ],
                'order_pay' => 25000,
            ],
            [
                'order_code' => 'ORD-CIT789',
                'customer' => 'Citra',
                'order_date' => Carbon::now()->subDays(1)->format('Y-m-d'),
                'order_status' => 0,
                'details' => [
                    ['service' => 'Laundry Besar', 'qty_kg' => 1.0, 'notes' => 'Sprei queen size'],
                ],
                'order_pay' => 20000,
            ],
        ];

        foreach ($ordersData as $orderData) {
            $customer = $customers[$orderData['customer']];
            $orderEndDate = Carbon::parse($orderData['order_date'])->addDays(3)->format('Y-m-d');
            $subtotal = 0;

            foreach ($orderData['details'] as $detail) {
                $service = $services[$detail['service']];
                $subtotal += $service->price * $detail['qty_kg'];
            }

            $order = TransOrder::updateOrCreate(
                ['order_code' => $orderData['order_code']],
                [
                    'id_customer' => $customer->id,
                    'order_date' => $orderData['order_date'],
                    'order_end_date' => $orderEndDate,
                    'order_status' => $orderData['order_status'],
                    'total' => $subtotal,
                    'order_pay' => $orderData['order_pay'],
                    'order_change' => max(0, $orderData['order_pay'] - $subtotal),
                ]
            );

            foreach ($orderData['details'] as $detail) {
                $service = $services[$detail['service']];
                TransOrderDetail::updateOrCreate(
                    ['id_order' => $order->id, 'id_service' => $service->id],
                    [
                        'qty' => (int) ($detail['qty_kg'] * 1000),
                        'subtotal' => $service->price * $detail['qty_kg'],
                        'notes' => $detail['notes'],
                    ]
                );
            }
        }
    }
}
