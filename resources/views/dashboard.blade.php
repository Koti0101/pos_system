@extends('layouts.app')

@section('page-title', 'Admin Dashboard')

@section('content')

{{-- STAT CARDS --}}
<div class="row g-3 mb-3">
    <div class="col-md-4">
        <div class="sc b">
            <div class="sc-ico b"><i class="bi bi-box-seam"></i></div>
            <div class="sc-lbl">Total Products</div>
            <div class="sc-val">{{ $totalProducts }}</div>
            <div class="sc-sub">Items in inventory</div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="sc g">
            <div class="sc-ico g"><i class="bi bi-bag-check"></i></div>
            <div class="sc-lbl">Sales Today</div>
            <div class="sc-val">{{ $salesToday }}</div>
            <div class="sc-sub">Transactions this day</div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="sc o">
            <div class="sc-ico o"><i class="bi bi-cash-stack"></i></div>
            <div class="sc-lbl">Revenue Today</div>
            <div class="sc-val" style="font-size:20px;">FCFA {{ number_format($revenueToday) }}</div>
            <div class="sc-sub">Total earnings today</div>
        </div>
    </div>
</div>

<div class="row g-3">

    {{-- Recent Transactions --}}
    <div class="col-xl-8">
        <div class="cc">
            <div class="cc-h">
                <div class="cc-t">
                    <i class="bi bi-receipt me-2" style="color:var(--blue)"></i>Recent Transactions
                </div>
                <a href="{{ route('sales.index') }}" class="btn-b">
                    View All <i class="bi bi-arrow-right"></i>
                </a>
            </div>
            <table class="pt">
                <thead>
                    <tr>
                        <th>ID</th><th>Date & Time</th><th>Cashier</th>
                        <th>Items</th><th>Total</th><th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($recentTransactions as $sale)
                    <tr>
                        <td style="color:var(--blue);font-weight:600;">#{{ str_pad($sale->id,4,'0',STR_PAD_LEFT) }}</td>
                        <td>
                            {{ $sale->created_at->format('M d, Y') }}
                            <span style="display:block;font-size:11px;color:var(--t3)">{{ $sale->created_at->format('H:i') }}</span>
                        </td>
                        <td>
                            <div style="display:flex;align-items:center;gap:7px;">
                                <div class="av">{{ strtoupper(substr($sale->user->name,0,2)) }}</div>
                                {{ $sale->user->name }}
                            </div>
                        </td>
<td><span class="bg-g">{{ $sale->saleItems->sum('quantity') }} items</span></td>
                        <td style="color:var(--green);font-weight:600;font-family:'Space Grotesk',sans-serif;">
                            FCFA {{ number_format($sale->total_amount) }}
                        </td>
                        <td>
                            <a href="{{ route('sales.show', $sale->id) }}" class="btn-b">
                                <i class="bi bi-eye"></i> View
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="6">
                        <div class="emp"><i class="bi bi-receipt"></i>No transactions yet</div>
                    </td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Quick Actions --}}
    <div class="col-xl-4">
        <div class="cc">
            <div class="cc-h">
                <div class="cc-t">
                    <i class="bi bi-lightning me-2" style="color:var(--orange)"></i>Quick Actions
                </div>
            </div>
            <div style="padding:12px;display:flex;flex-direction:column;gap:7px;">

                <a href="{{ route('pos.index') }}" class="qa-link qa-blue">
                    <div class="qa-ico" style="background:rgba(79,142,247,0.15);color:var(--blue)">
                        <i class="bi bi-bag-plus"></i>
                    </div>
                    <div class="qa-txt">
                        <div class="qa-name">New Sale</div>
                        <div class="qa-sub">Open POS screen</div>
                    </div>
                    <i class="bi bi-arrow-right ms-auto" style="color:var(--t3)"></i>
                </a>

                <a href="{{ route('products.create') }}" class="qa-link qa-green">
                    <div class="qa-ico" style="background:rgba(34,197,94,0.15);color:var(--green)">
                        <i class="bi bi-plus-circle"></i>
                    </div>
                    <div class="qa-txt">
                        <div class="qa-name">Add Product</div>
                        <div class="qa-sub">Add to inventory</div>
                    </div>
                    <i class="bi bi-arrow-right ms-auto" style="color:var(--t3)"></i>
                </a>

                <a href="{{ route('products.index') }}" class="qa-link qa-purple">
                    <div class="qa-ico" style="background:rgba(168,85,247,0.15);color:var(--purple)">
                        <i class="bi bi-box-seam"></i>
                    </div>
                    <div class="qa-txt">
                        <div class="qa-name">Manage Products</div>
                        <div class="qa-sub">Edit, delete products</div>
                    </div>
                    <i class="bi bi-arrow-right ms-auto" style="color:var(--t3)"></i>
                </a>

                <a href="{{ route('sales.index') }}" class="qa-link qa-orange">
                    <div class="qa-ico" style="background:rgba(245,158,11,0.15);color:var(--orange)">
                        <i class="bi bi-receipt"></i>
                    </div>
                    <div class="qa-txt">
                        <div class="qa-name">Sales History</div>
                        <div class="qa-sub">View all transactions</div>
                    </div>
                    <i class="bi bi-arrow-right ms-auto" style="color:var(--t3)"></i>
                </a>

                <a href="{{ route('categories.index') }}" class="qa-link qa-red">
                    <div class="qa-ico" style="background:rgba(239,68,68,0.15);color:var(--red)">
                        <i class="bi bi-tags"></i>
                    </div>
                    <div class="qa-txt">
                        <div class="qa-name">Categories</div>
                        <div class="qa-sub">Manage categories</div>
                    </div>
                    <i class="bi bi-arrow-right ms-auto" style="color:var(--t3)"></i>
                </a>

                <a href="{{ route('users.index') }}" class="qa-link qa-teal">
                    <div class="qa-ico" style="background:rgba(20,184,166,0.15);color:#14b8a6">
                        <i class="bi bi-people"></i>
                    </div>
                    <div class="qa-txt">
                        <div class="qa-name">Manage Users</div>
                        <div class="qa-sub">Add or remove cashiers</div>
                    </div>
                    <i class="bi bi-arrow-right ms-auto" style="color:var(--t3)"></i>
                </a>

                <a href="{{ route('reports.index') }}" class="qa-link qa-indigo">
                    <div class="qa-ico" style="background:rgba(99,102,241,0.15);color:#6366f1">
                        <i class="bi bi-bar-chart-line"></i>
                    </div>
                    <div class="qa-txt">
                        <div class="qa-name">Sales Report</div>
                        <div class="qa-sub">View & export reports</div>
                    </div>
                    <i class="bi bi-arrow-right ms-auto" style="color:var(--t3)"></i>
                </a>

            </div>
        </div>
    </div>

</div>

@endsection

@section('scripts')
<style>
    .qa-link {
        display:flex; align-items:center; gap:10px;
        padding:11px 12px; border-radius:8px;
        text-decoration:none; color:var(--t1);
        transition:all 0.15s; border:1px solid transparent;
    }
    .qa-blue   { background:rgba(79,142,247,0.08);  border-color:rgba(79,142,247,0.15);  }
    .qa-green  { background:rgba(34,197,94,0.08);   border-color:rgba(34,197,94,0.15);   }
    .qa-purple { background:rgba(168,85,247,0.08);  border-color:rgba(168,85,247,0.15);  }
    .qa-orange { background:rgba(245,158,11,0.08);  border-color:rgba(245,158,11,0.15);  }
    .qa-red    { background:rgba(239,68,68,0.08);   border-color:rgba(239,68,68,0.15);   }
    .qa-teal   { background:rgba(20,184,166,0.08);  border-color:rgba(20,184,166,0.15);  }
    .qa-indigo { background:rgba(99,102,241,0.08);  border-color:rgba(99,102,241,0.15);  }
    .qa-blue:hover   { background:rgba(79,142,247,0.18);  color:var(--t1); }
    .qa-green:hover  { background:rgba(34,197,94,0.18);   color:var(--t1); }
    .qa-purple:hover { background:rgba(168,85,247,0.18);  color:var(--t1); }
    .qa-orange:hover { background:rgba(245,158,11,0.18);  color:var(--t1); }
    .qa-red:hover    { background:rgba(239,68,68,0.18);   color:var(--t1); }
    .qa-teal:hover   { background:rgba(20,184,166,0.18);  color:var(--t1); }
    .qa-indigo:hover { background:rgba(99,102,241,0.18);  color:var(--t1); }
    .qa-ico { width:34px;height:34px;border-radius:8px;display:flex;align-items:center;justify-content:center;font-size:15px;flex-shrink:0; }
    .qa-name { font-size:13px;font-weight:600; }
    .qa-sub  { font-size:11px;color:var(--t2); }
</style>
@endsection
