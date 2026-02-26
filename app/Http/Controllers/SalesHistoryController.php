<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use Illuminate\Http\Request;
use Carbon\Carbon;

class SalesHistoryController extends Controller
{
    public function index(Request $request)
    {
        $query = Sale::with('user', 'saleItems');

        if ($request->has('date')) {
            $date = Carbon::parse($request->date);
            $query->whereDate('created_at', $date);
        }

        if ($request->has('start_date') && $request->has('end_date')) {
            $query->whereBetween('created_at', [
                $request->start_date,
                $request->end_date . ' 23:59:59'
            ]);
        }

        $sales = $query->latest()->paginate(20);
        $totalSales = $query->count();
        $totalRevenue = $query->sum('total_amount');

        return view('sales.index', compact('sales', 'totalSales', 'totalRevenue'));
    }

    public function show($id)
    {
        $sale = Sale::with('saleItems.product', 'user')->findOrFail($id);
        return view('sales.show', compact('sale'));
    }
}