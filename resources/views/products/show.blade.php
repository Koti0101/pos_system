@extends('layouts.app')

@section('content')
<div class="container">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Product Details</h2>

        <a href="{{ route('products.index') }}" class="btn btn-secondary">
            Back to Products
        </a>
    </div>

    <div class="card">
        <div class="card-body">

            <table class="table table-bordered">
                <tr>
                    <th style="width:200px;">Product Name</th>
                    <td>{{ $product->name }}</td>
                </tr>

                <tr>
                    <th>SKU</th>
                    <td>{{ $product->sku ?? '-' }}</td>
                </tr>

                <tr>
                    <th>Barcode</th>
                    <td>{{ $product->barcode ?? '-' }}</td>
                </tr>

                <tr>
                    <th>Category</th>
                    <td>{{ $product->category->name ?? '-' }}</td>
                </tr>

                <tr>
                    <th>Price</th>
                    <td>FCFA {{ number_format($product->price, 0, ',', ' ') }}</td>
                </tr>

                <tr>
                    <th>Stock Quantity</th>
                    <td>
                        <span class="badge {{ $product->quantity < 10 ? 'bg-danger' : ($product->quantity < 30 ? 'bg-warning' : 'bg-success') }}">
                            {{ $product->quantity }}
                        </span>
                    </td>
                </tr>

                <tr>
                    <th>Description</th>
                    <td>{{ $product->description ?? 'No description available' }}</td>
                </tr>

                <tr>
                    <th>Created At</th>
                    <td>{{ $product->created_at->format('d M Y H:i') }}</td>
                </tr>

                <tr>
                    <th>Last Updated</th>
                    <td>{{ $product->updated_at->format('d M Y H:i') }}</td>
                </tr>
            </table>

        </div>
    </div>

</div>
@endsection