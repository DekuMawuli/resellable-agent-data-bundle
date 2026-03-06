<div class="crud-shell">
    <div class="table-responsive">
        <table class="table table-striped table-inverse table-responsive-sm">
            <thead class="thead-inverse">
            <tr>
                <th>Customer Name</th>
                <th>Package</th>
                <th>Amount</th>
                <th>Recipient</th>
                <th>Ordered At</th>
                <th>Action</th>
            </tr>
            </thead>
            <tbody>
            @foreach($orders as $order)
                <tr>
                    <td>{{ $order->customer->name }}</td>
                    <td>{{ $order->product->category->name }} {{ $order->product->name }} GB</td>
                    <td>{{ $order->product->agent_price }}</td>
                    <td>{{ $order->phone_number }}</td>
                    <td>{{ $order->created_at }}</td>
                    <td>
                        <div class="action-group">
                            @if($order->status == "pending")
                                <button wire:click="confirmPurchase('{{$order->code}}')" class="btn btn-info btn-sm">Confirm Payment</button>
                            @elseif($order->status == "processing")
                                <button wire:click="approvePurchase('{{$order->code}}')" class="btn btn-success btn-sm">Forward Order</button>
                            @else
                                <span class="badge badge-success">Completed</span>
                            @endif
                        </div>
                    </td>
                </tr>
            @endforeach

            </tbody>
        </table>
    </div>
    {{ $orders->links() }}
</div>
