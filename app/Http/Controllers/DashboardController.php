<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Sale;
use Illuminate\Http\Request;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        if ($user->role === 'admin') {
            // Admin sees everything
            $totalProducts    = Product::count();
            $salesToday       = Sale::whereDate('created_at', Carbon::today())->count();
            $revenueToday     = Sale::whereDate('created_at', Carbon::today())->sum('total_amount');
            $recentTransactions = Sale::with('user', 'saleItems.product')
                ->latest()
                ->take(10)
                ->get();

            return view('dashboard', compact(
                'totalProducts',
                'salesToday',
                'revenueToday',
                'recentTransactions'
            ));

        } else {
            // Cashier sees limited info
            $salesToday    = Sale::whereDate('created_at', Carbon::today())->count();
            $mySalesToday  = Sale::where('user_id', $user->id)
                ->whereDate('created_at', Carbon::today())
                ->count();
            $recentTransactions = Sale::with('user', 'saleItems.product')
                ->latest()
                ->take(10)
                ->get();

            return view('dashboard-cashier', compact(
                'salesToday',
                'mySalesToday',
                'recentTransactions'
            ));
        }
    }
}
