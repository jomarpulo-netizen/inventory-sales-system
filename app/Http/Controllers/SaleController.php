<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Sale;
use App\Models\SaleItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SaleController extends Controller
{
    public function index()
    {
        $sales = Sale::with('items.product')->latest()->paginate(10);
        return view('sales.index', compact('sales'));
    }

    public function create()
    {
        $products = Product::where('stock_quantity', '>', 0)->get();
        return view('sales.create', compact('products'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
        ]);

        $product = Product::findOrFail($request->product_id);

        if ($request->quantity > $product->stock_quantity) {
            return back()->withErrors(['quantity' => 'Not enough stock available.'])->withInput();
        }

        $subtotal = $product->price * $request->quantity;

        DB::transaction(function () use ($product, $request, $subtotal) {
            $sale = Sale::create([
                'total_amount' => $subtotal,
            ]);

            SaleItem::create([
                'sale_id' => $sale->id,
                'product_id' => $product->id,
                'quantity' => $request->quantity,
                'subtotal' => $subtotal,
            ]);

            $product->decrement('stock_quantity', $request->quantity);
        });

        return redirect()->route('sales.index')->with('success', 'Sale recorded successfully!');
    }
}