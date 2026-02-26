@extends('layouts.app')

@section('page-title', 'Sales Reports')

@section('content')
<div class="container">
    <h2 class="mb-4">Sales Reports</h2>

    {{-- Date Range Filter --}}
    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('reports.index') }}" class="row g-3">
                <div class="col-md-4">
                    <label for="start_date" class="form-label">Start Date</label>
                    <input type="date" class="form-control" id="start_date" name="start_date" value="{{ $startDate }}">
                </div>
                <div class="col-md-4">
                    <label for="end_date" class="form-label">End Date</label>
                    <input type="date" class="form-control" id="end_date" name="end_date" value="{{ $endDate }}">
                </div>
                <div class="col-md-4 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary">Filter</button>
                    <a href="{{ route('reports.index') }}" class="btn btn-secondary ms-2">Reset</a>
                    
                    {{-- EXPORT BUTTON --}}
                    <a href="{{ route('reports.export', request()->query()) }}" class="btn btn-success ms-2">
                        <i class="bi bi-file-earmark-excel"></i> Export
                    </a>
                </div>
            </form>
        </div>
    </div>

    {{-- Summary Cards --}}
    <div class="row mb-4">
        <div class="col-md-6">
            <div class="sc b">
                <div class="sc-ico b"><i class="bi bi-cash-stack"></i></div>
                <div class="sc-lbl">Total Sales</div>
                <div class="sc-val">FCFA {{ number_format($totalSales, 0, ',', ' ') }}</div>
                <div class="sc-sub">{{ $startDate }} to {{ $endDate }}</div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="sc g">
                <div class="sc-ico g"><i class="bi bi-receipt"></i></div>
                <div class="sc-lbl">Transactions</div>
                <div class="sc-val">{{ $totalTransactions }}</div>
                <div class="sc-sub">{{ $startDate }} to {{ $endDate }}</div>
            </div>
        </div>
    </div>

    {{-- Top Products --}}
    <div class="card mb-4">
        <div class="card-header">
            <h5 class="mb-0">Top 5 Products</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Product</th>
                            <th>Quantity Sold</th>
                            <th>Revenue</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($topProducts as $product)
                        <tr>
                            <td>{{ $product->name }}</td>
                            <td>{{ $product->total_quantity }}</td>
                            <td>FCFA {{ number_format($product->total_revenue, 0, ',', ' ') }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="3" class="text-center">No sales in this period</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- Daily Sales Table --}}
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0">Daily Sales</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Transactions</th>
                            <th>Total Sales</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($salesData as $day)
                        <tr>
                            <td>{{ $day->date }}</td>
                            <td>{{ $day->total_transactions }}</td>
                            <td>FCFA {{ number_format($day->total_sales, 0, ',', ' ') }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="3" class="text-center">No data for this period</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection