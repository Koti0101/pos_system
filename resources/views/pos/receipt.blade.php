@extends('layouts.app')

@section('page-title', 'Receipt')

@section('content')

<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Receipt #{{ $sale->id }}</h2>
        <div>
            <button onclick="window.print()" class="btn btn-primary">
                <i class="bi bi-printer"></i> Print Receipt
            </button>
            <a href="{{ route('pos.index') }}" class="btn btn-success">
                <i class="bi bi-plus-lg"></i> New Sale
            </a>
        </div>
    </div>

    <div class="card">
        <div class="card-body" id="receiptContent">
            {{-- Store Info --}}
            <div class="text-center mb-4">
                <h3>GRACIOUS STORE</h3>
                <p class="mb-0">Point of Sale System</p>
                <p class="mb-0">Receipt #{{ str_pad($sale->id, 4, '0', STR_PAD_LEFT) }}</p>
            </div>

            <hr>

            {{-- Sale Info --}}
            <div class="row mb-3">
                <div class="col-6">
                    <strong>Date:</strong> {{ $sale->created_at->format('M d, Y') }}<br>
                    <strong>Time:</strong> {{ $sale->created_at->format('H:i') }}
                </div>
                <div class="col-6 text-end">
                    <strong>Cashier:</strong> {{ $sale->user->name }}
                </div>
            </div>

            <hr>

            {{-- Items --}}
            <table class="table table-borderless">
                <thead>
                    <tr>
                        <th>Item</th>
                        <th class="text-center">Qty</th>
                        <th class="text-end">Price</th>
                        <th class="text-end">Total</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($sale->saleItems as $item)
                    <tr>
                        <td>{{ $item->product->name }}</td>
                        <td class="text-center">{{ $item->quantity }}</td>
                        <td class="text-end">FCFA {{ number_format($item->price) }}</td>
                        <td class="text-end">FCFA {{ number_format($item->subtotal) }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>

            <hr>

            {{-- Totals --}}
            <div class="row">
                <div class="col-6"></div>
                <div class="col-6">
                    <table class="table table-borderless">
                        <tr>
                            <td><strong>Subtotal:</strong></td>
                            <td class="text-end">FCFA {{ number_format($sale->total_amount) }}</td>
                        </tr>
                        <tr>
                            <td><strong>Amount Paid:</strong></td>
                            <td class="text-end">FCFA {{ number_format($sale->amount_paid) }}</td>
                        </tr>
                        <tr>
                            <td><strong>Change:</strong></td>
                            <td class="text-end">FCFA {{ number_format($sale->balance) }}</td>
                        </tr>
                    </table>
                </div>
            </div>

            <hr>

            <div class="text-center">
                <p class="mb-0">Thank you for shopping with us!</p>
                <small class="text-muted">Powered by POS System</small>
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<style>
    @media print {
        /* Hide everything except receipt */
        body * { visibility: hidden; }
        #receiptContent, #receiptContent * { visibility: visible; }
        #receiptContent { 
            position: absolute;
            left: 0;
            top: 0;
            width: 100%;
        }
        .btn, .top-navbar, .sidebar, .pos-sb, .pos-top { display: none !important; }
    }
</style>
@endsection