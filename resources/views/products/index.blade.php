@extends('layouts.app')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Products</h2>

        {{-- Only Admin can add products --}}
        @if(auth()->user()->role === 'admin')
            <a href="{{ route('products.create') }}" class="btn btn-primary">Add Product</a>
        @endif
    </div>

    {{-- Success message --}}
    @if(session('success'))
        <div id="successAlert" class="alert alert-success">{{ session('success') }}</div>
    @endif

    {{-- Search --}}
    <div class="card mb-3">
        <div class="card-body">
            <form method="GET">
                <div class="input-group">
                    <input type="text" name="search" class="form-control"
                           placeholder="Search by name or SKU..."
                           value="{{ request('search') }}">
                    <button class="btn btn-primary" type="submit">Search</button>
                    <a href="{{ route('products.index') }}" class="btn btn-secondary">Clear</a>
                </div>
            </form>
        </div>
    </div>

    {{-- Table --}}
    <div class="card">
        <div class="card-body">

            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>SKU</th>
                            <th>Name</th>
                            <th class="d-none d-sm-table-cell">Barcode</th>
                            <th class="d-none d-md-table-cell">Category</th>
                            <th class="d-none d-sm-table-cell">Price</th>
                            <th class="d-none d-sm-table-cell">Stock</th>
                            <th class="d-none d-sm-table-cell">Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse($products as $product)
                        <tr>

                            {{-- SKU --}}
                            <td>{{ $product->id }}</td>

                            {{-- Name --}}
                            <td>{{ $product->name }}</td>

                            {{-- Barcode --}}
                            <td class="d-none d-sm-table-cell">
                                @if($product->barcode)
                                    <span class="badge bg-secondary" style="font-family:monospace;">
                                        {{ $product->barcode }}
                                    </span>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>

                            {{-- Category --}}
                            <td class="d-none d-md-table-cell">
                                {{ $product->category->name ?? '-' }}
                            </td>

                            {{-- Price --}}
                            <td class="d-none d-sm-table-cell">
                                FCFA {{ number_format($product->price, 0, ',', ' ') }}
                            </td>

                            {{-- Stock --}}
                            <td class="d-none d-sm-table-cell">
                                <span class="badge {{ $product->quantity < 10 ? 'bg-danger' : ($product->quantity < 30 ? 'bg-warning' : 'bg-success') }}">
                                    {{ $product->quantity }}
                                </span>
                            </td>

                            {{-- Status --}}
                            <td class="d-none d-sm-table-cell">
                                @php
                                    $maxStock = 100;
                                    $percentage = min(100, ($product->quantity / $maxStock) * 100);

                                    if ($product->quantity == 0) {
                                        $statusColor = 'danger';
                                        $statusText = 'Out of Stock';
                                    } elseif ($product->quantity < 10) {
                                        $statusColor = 'danger';
                                        $statusText = 'Critical';
                                    } elseif ($product->quantity < 30) {
                                        $statusColor = 'warning';
                                        $statusText = 'Low Stock';
                                    } else {
                                        $statusColor = 'success';
                                        $statusText = 'In Stock';
                                    }
                                @endphp

                                <div style="display:flex;align-items:center;gap:8px;">
                                    <div class="progress" style="flex:1;height:20px;">
                                        <div class="progress-bar bg-{{ $statusColor }}"
                                             role="progressbar"
                                             style="width: {{ $percentage }}%;">
                                            {{ round($percentage) }}%
                                        </div>
                                    </div>

                                    <span class="badge bg-{{ $statusColor }}">
                                        {{ $statusText }}
                                    </span>
                                </div>
                            </td>

                            {{-- Actions --}}
                            <td>
                                @if(auth()->user()->role === 'admin')

                                    <a href="{{ route('products.edit', $product) }}"
                                       class="btn btn-sm btn-warning">
                                        Edit
                                    </a>

                                    <form action="{{ route('products.destroy', $product) }}"
                                          method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                                class="btn btn-sm btn-danger"
                                                onclick="return confirm('Are you sure?')">
                                            Delete
                                        </button>
                                    </form>

                                @else
                                    {{-- CASHIER VIEW ONLY --}}
                                    <a href="{{ route('products.show', $product) }}"
                                       class="btn btn-sm btn-secondary">
                                        View
                                    </a>
                                @endif
                            </td>

                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="text-center">No products found</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{ $products->links() }}

        </div>
    </div>
</div>

{{-- Auto-hide success alert --}}
<script>
window.addEventListener('DOMContentLoaded', () => {
    const alert = document.getElementById('successAlert');
    if (alert) {
        setTimeout(() => alert.style.display = 'none', 3000);
    }
});
</script>
@endsection