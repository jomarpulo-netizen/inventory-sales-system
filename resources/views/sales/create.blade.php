@extends('layouts.app')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card shadow-sm">
            <div class="card-header bg-success text-white">
                <h4 class="mb-0">New Sale Transaction</h4>
            </div>
            <div class="card-body">
                @if($errors->any())
                    <div class="alert alert-danger">
                        {{ $errors->first() }}
                    </div>
                @endif

                <form action="{{ route('sales.store') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">Select Product</label>
                        <select name="product_id" class="form-control" required>
                            <option value="">-- Choose Product --</option>
                            @foreach($products as $product)
                                <option value="{{ $product->id }}">
                                    {{ $product->name }} - Stock: {{ $product->stock_quantity }} (₱{{ number_format($product->price, 2) }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Quantity</label>
                        <input type="number" name="quantity" class="form-control" min="1" value="1" required>
                    </div>

                    <button type="submit" class="btn btn-success w-100">Complete Sale</button>
                    <a href="{{ route('sales.index') }}" class="btn btn-secondary w-100 mt-2">Cancel</a>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection