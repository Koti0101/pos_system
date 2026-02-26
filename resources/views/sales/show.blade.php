@extends('layouts.app')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Sale Details #{{ $sale->id }}</h2>
        <a href="{{ route('sales.index') }}" class="btn btn-secondary">Back</a>
    </div>

    <div class="row">
        <div class="col-md-6">
            <div class="card mb-3">
                <div class="card-header">
                    <h5>Sale Information</h5>
                </div>
                <div class="card-body">
                    <p><strong>Date:</strong> {{ $sale->created_at->format('M d, Y H:i A') }}</p>
                    <p><strong>Cashier:</strong> {{ $sale->user->name }}</p>
                    <p><strong>Payment Method:</strong> {{ ucfirst($sale->payment_method) }}</p>
                    <p><strong>Total Amount:</strong> FCFA{{ number_format($sale->total_amount, 2) }}</p>
                    <p><strong>Amount Paid:</strong> FCFA{{ number_format($sale->amount_paid, 2) }}</p>
                    <p><strong>Change:</strong> FCFA{{ number_format($sale->balance, 2) }}</p>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card mb-3">
                <div class="card-header">
                    <h5>Actions</h5>
                </div>
                <div class="card-body">
                    <a href="{{ route('pos.receipt', $sale->id) }}" class="btn btn-primary btn-block mb-2">View Receipt</a>
                    <button onclick="window.print()" class="btn btn-secondary btn-block">Print</button>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h5>Items Sold</h5>
        </div>
        <div class="card-body">
            <table class="table">
                <thead>
                    <tr>
                        <th>Product</th>
                        <th>Category</th>
                        <th class="text-center">Quantity</th>
                        <th class="text-end">Price</th>
                        <th class="text-end">Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($sale->saleItems as $item)
                    <tr>
                        <td>{{ $item->product->name }}</td>
                        <td>{{ $item->product->category->name }}</td>
                        <td class="text-center">{{ $item->quantity }}</td>
                        <td class="text-end">FCFA{{ number_format($item->price, 2) }}</td>
                        <td class="text-end">FCFA{{ number_format($item->subtotal, 2) }}</td>
                    </tr>
                    @endforeach
                    <tr>
                        <td colspan="4" class="text-end"><strong>Total:</strong></td>
                        <td class="text-end"><strong>FCFA{{ number_format($sale->total_amount, 2) }}</strong></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection