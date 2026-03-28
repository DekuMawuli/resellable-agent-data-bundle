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
                                <button
                                    type="button"
                                    wire:click="confirmPurchase('{{$order->code}}')"
                                    wire:loading.attr="disabled"
                                    wire:target="confirmPurchase('{{$order->code}}')"
                                    class="btn btn-info btn-sm"
                                >
                                    <span wire:loading wire:target="confirmPurchase('{{$order->code}}')" class="spinner-border spinner-border-sm me-1" role="status" aria-hidden="true"></span>
                                    <span wire:loading.remove wire:target="confirmPurchase('{{$order->code}}')">Confirm Payment</span>
                                    <span wire:loading wire:target="confirmPurchase('{{$order->code}}')">Confirming...</span>
                                </button>
                            @elseif($order->status == "processing" && blank($order->provider_reference))
                                <button
                                    type="button"
                                    wire:click="approvePurchase('{{$order->code}}')"
                                    wire:loading.attr="disabled"
                                    wire:target="approvePurchase('{{$order->code}}')"
                                    class="btn btn-success btn-sm"
                                >
                                    <span wire:loading wire:target="approvePurchase('{{$order->code}}')" class="spinner-border spinner-border-sm me-1" role="status" aria-hidden="true"></span>
                                    <span wire:loading.remove wire:target="approvePurchase('{{$order->code}}')">Forward Order</span>
                                    <span wire:loading wire:target="approvePurchase('{{$order->code}}')">Forwarding...</span>
                                </button>
                            @elseif($order->status == "processing")
                                <span class="badge badge-primary">Forwarded</span>
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
