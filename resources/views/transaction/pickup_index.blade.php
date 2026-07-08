<div class="card">
    <div class="card-header">
        <h4>Pickup Laundry</h4>
    </div>
    <div class="card-body">

        <ul class="nav nav-tabs" id="pickupTab" role="tablist">
            <li class="nav-item">
                <a class="nav-link active" id="ready-tab" data-toggle="tab" href="#ready" role="tab">
                    Siap Diambil
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="history-tab" data-toggle="tab" href="#history" role="tab">
                    History Pengambilan
                </a>
            </li>
        </ul>

        <div class="tab-content mt-3" id="pickupTabContent">

            <div class="tab-pane fade show active" id="ready" role="tabpanel">
                <div class="table-responsive">
                    <table class="table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th>No Order</th>
                                <th>Pelanggan</th>
                                <th>Total Tagihan</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($ready_orders as $order)
                            <tr>
                                <td>{{ $order->order_code }}</td>
                                <td>{{ $order->customer->customer_name }}</td>
                                <td>Rp {{ number_format($order->total, 0, ',', '.') }}</td>
                                <td>
                                    <a href="{{ route('transaction.pickup.show', $order->id) }}" class="btn btn-success btn-sm">
                                        Proses Pickup
                                    </a>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="text-center">Belum ada cucian yang siap diambil.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="tab-pane fade" id="history" role="tabpanel">
                <div class="table-responsive">
                    <table class="table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th>No Order</th>
                                <th>Pelanggan</th>
                                <th>Tanggal Diambil</th>
                                <th>Catatan</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($pickup_history as $history)
                            <tr>
                                <td>{{ $history->order->order_code ?? '-' }}</td>
                                <td>{{ $history->customer->customer_name ?? '-' }}</td>
                                <td>{{ date('d-m-Y', strtotime($history->pickup_date)) }}</td>
                                <td>{{ $history->notes ?? '-' }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="text-center">Belum ada riwayat pengambilan.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </div> </div>
</div>
