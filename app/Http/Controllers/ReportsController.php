<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Sale;
use App\Models\Product;
use App\Exports\SalesExport;        // <-- ADD THIS
use Maatwebsite\Excel\Facades\Excel; // <-- ADD THIS
use DB;

class ReportsController extends Controller
{
    public function index(Request $request)
    {
        // Default date range: last 30 days
        $startDate = $request->get('start_date', now()->subDays(30)->format('Y-m-d'));
        $endDate = $request->get('end_date', now()->format('Y-m-d'));

        // Daily sales summary
        $salesData = Sale::whereBetween('created_at', [$startDate, $endDate])
            ->select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('COUNT(*) as total_transactions'),
                DB::raw('SUM(total_amount) as total_sales')
            )
            ->groupBy('date')
            ->orderBy('date', 'desc')
            ->get();

        // Overall totals
        $totalSales = $salesData->sum('total_sales');
        $totalTransactions = $salesData->sum('total_transactions');

        // Top 5 products sold in the period
        $topProducts = DB::table('sale_items')
            ->join('products', 'sale_items.product_id', '=', 'products.id')
            ->join('sales', 'sale_items.sale_id', '=', 'sales.id')
            ->whereBetween('sales.created_at', [$startDate, $endDate])
            ->select(
                'products.name',
                DB::raw('SUM(sale_items.quantity) as total_quantity'),
                DB::raw('SUM(sale_items.subtotal) as total_revenue')
            )
            ->groupBy('products.id', 'products.name')
            ->orderBy('total_quantity', 'desc')
            ->limit(5)
            ->get();

        return view('reports.index', compact(
            'salesData',
            'totalSales',
            'totalTransactions',
            'topProducts',
            'startDate',
            'endDate'
        ));
    }

    /**
     * Export sales report to Excel
     */
    public function export(Request $request)
    {
        $startDate = $request->get('start_date', now()->subDays(30)->format('Y-m-d'));
        $endDate = $request->get('end_date', now()->format('Y-m-d'));

        return Excel::download(
            new SalesExport($startDate, $endDate),
            'sales_report_' . $startDate . '_to_' . $endDate . '.xlsx'
        );
    }
}