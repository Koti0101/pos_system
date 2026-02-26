<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Sale;
use App\Models\SaleItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class POSController extends Controller
{
    public function index()
    {
        $products = Product::with('category')->where('quantity', '>', 0)->get();
        return view('pos.index', compact('products'));
    }

    public function searchProduct(Request $request)
    {
        $search = $request->search;
        
        $products = Product::with('category')
            ->where('quantity', '>', 0)
            ->where(function($query) use ($search) {
                $query->where('name', 'like', "%{$search}%")
                      ->orWhere('sku', 'like', "%{$search}%");
            })
            ->get();

        return response()->json($products);
    }

    public function checkout(Request $request)
    {
        $validated = $request->validate([
            'cart' => 'required|array|min:1',
            'cart.*.product_id' => 'required|exists:products,id',
            'cart.*.quantity' => 'required|integer|min:1',
            'cart.*.price' => 'required|numeric|min:0',
            'amount_paid' => 'required|numeric|min:0',
        ]);

        DB::beginTransaction();

        try {
            $totalAmount = 0;
            foreach ($validated['cart'] as $item) {
                $totalAmount += $item['price'] * $item['quantity'];
            }

            if ($validated['amount_paid'] < $totalAmount) {
                return back()->with('error', 'Insufficient payment amount.');
            }

            $balance = $validated['amount_paid'] - $totalAmount;

            $sale = Sale::create([
                'user_id' => auth()->id(),
                'total_amount' => $totalAmount,
                'amount_paid' => $validated['amount_paid'],
                'balance' => $balance,
                'payment_method' => 'cash',
            ]);

            foreach ($validated['cart'] as $item) {
                $product = Product::findOrFail($item['product_id']);

                if (!$product->isInStock($item['quantity'])) {
                    DB::rollBack();
                    return back()->with('error', "Insufficient stock for {$product->name}");
                }

                SaleItem::create([
                    'sale_id' => $sale->id,
                    'product_id' => $product->id,
                    'quantity' => $item['quantity'],
                    'price' => $item['price'],
                    'subtotal' => $item['price'] * $item['quantity'],
                ]);

                $product->reduceStock($item['quantity']);
            }

            DB::commit();

            return redirect()->route('pos.receipt', $sale->id)
                ->with('success', 'Sale completed successfully!');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'An error occurred: ' . $e->getMessage());
        }
    }

    public function receipt($id)
    {
        $sale = Sale::with('saleItems.product', 'user')->findOrFail($id);
        return view('pos.receipt', compact('sale'));
    }


public function searchByBarcode(Request $request)
{
    $barcode = $request->get('barcode');
    
    $product = Product::where('barcode', $barcode)
                      ->where('quantity', '>', 0)
                      ->with('category')
                      ->first();
    
    if (!$product) {
        return response()->json([
            'success' => false,
            'message' => 'Product not found or out of stock'
        ]);
    }
    
    return response()->json([
        'success' => true,
        'product' => $product
    ]);
}
}