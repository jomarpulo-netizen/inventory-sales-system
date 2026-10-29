@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h2>Sales History</h2>
    <a href="{{ route('sales.create') }}" class="btn btn-success">+ New Sale Transaction</a>
</div>

<div class="card shadow-sm">
    <div class="card-body">
        <table class="table table-bordered table-striped align-middle">
            <thead class="table-dark">
                <tr>
                    <th>Sale ID</th>
                    <th>Product Sold</th>
                    <th>Quantity</th>
                    <th>Total Amount</th>
                    <th>Date</th>
                </tr>
            </thead>
            <tbody>
                @forelse($sales as $sale)
                    <tr>
                        <td>{{ $sale->id }}</td>
                        <td>
                            @foreach($sale->items as $item)
                                {{ $item->product->name ?? 'Product Deleted' }} (₱{{ number_format($item->subtotal, 2) }})
                            @endforeach
                        </td>
                        <td>
                            @foreach($sale->items as $item)
                                {{ $item->quantity }} pcs
                            @endforeach
                        </td>
                        <td><strong>₱{{ number_format($sale->total_amount, 2) }}</strong></td>
                        <td>{{ $sale->created_at->format('M d, Y h:i A') }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center text-muted">No sales recorded yet.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <div class="d-flex justify-content-center mt-3">
            {{ $sales->links() }}
        </div>
    </div>
</div>
@endsection