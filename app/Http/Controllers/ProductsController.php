<?php

namespace App\Http\Controllers;

use App\Models\products;
use Illuminate\Http\Request;

class ProductsController extends Controller
{
    public function index()
    {
        $products = products::all();
        return view('products.index', compact('products'));
    }

    public function create()
    {
        return view('products.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'code'    => 'required|unique:products,code',
            'name'    => 'required',
            'category' => 'required',
        ]);

        try {
            $product = new products();
            $product->code       = $request->code;
            $product->name       = $request->name;
            $product->category    = $request->category;
            $product->is_active  = $request->boolean('is_active'); // checkbox: default false kalau gak dicentang
            $product->save();

            return redirect()->route('products.index')->with('success', 'Product berhasil ditambahkan.');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Terjadi kesalahan saat menambahkan Product. Silakan coba lagi.']);
        }
    }

    public function edit($id)
    {
        $product = products::findOrFail($id);
        return view('products.edit', compact('product'));
    }

    public function update(Request $request, $id)
    {
        $product = products::findOrFail($id);

        $request->validate([
            'code'    => 'required|unique:products,code,' . $id,
            'name'    => 'required',
            'category' => 'required',
        ]);

        try {
            $product->code      = $request->code;
            $product->name      = $request->name;
            $product->category   = $request->category;
            $product->is_active = $request->boolean('is_active');
            $product->save();

            return redirect()->route('products.index')->with('success', 'Product berhasil diperbarui.');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Terjadi kesalahan saat memperbarui Product. Silakan coba lagi.']);
        }
    }

    public function destroy($id)
    {
        try {
            $product = products::findOrFail($id);
            $product->delete();
            return redirect()->route('products.index')->with('success', 'Product berhasil dihapus.');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Terjadi kesalahan saat menghapus Product. Silakan coba lagi.']);
        }
    }
}