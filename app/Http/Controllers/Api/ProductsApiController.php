<?php

namespace App\Http\Controllers\Api;

use App\Models\products;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class ProductsApiController extends BaseApiController
{
    public function index()
    {
        $products = products::all();
        return $this->responseHasil(200, true, $products);
    }

    public function show($id)
    {
        $product = products::find($id);

        if (!$product) {
            return $this->responseHasil(404, false, "Product tidak ditemukan");
        }

        return $this->responseHasil(200, true, $product);
    }

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'code'    => 'required|unique:products,code',
                'name'    => 'required',
                'category' => 'required',
            ]);
        } catch (ValidationException $e) {
            return $this->responseHasil(400, false, $e->errors());
        }

        $product = products::create([
            'code'      => $validated['code'],
            'name'      => $validated['name'],
            'category'   => $validated['category'],
            'is_active' => $request->boolean('is_active', true),
        ]);

        return $this->responseHasil(200, true, $product);
    }

    public function update(Request $request, $id)
    {
        $product = products::find($id);

        if (!$product) {
            return $this->responseHasil(404, false, "Product tidak ditemukan");
        }

        try {
            $validated = $request->validate([
                'code'    => 'required|unique:products,code,' . $id,
                'name'    => 'required',
                'category' => 'required',
            ]);
        } catch (ValidationException $e) {
            return $this->responseHasil(400, false, $e->errors());
        }

        $product->update([
            'code'      => $validated['code'],
            'name'      => $validated['name'],
            'category'   => $validated['category'],
            'is_active' => $request->boolean('is_active', $product->is_active),
        ]);

        return $this->responseHasil(200, true, $product);
    }

    public function destroy($id)
    {
        $product = products::find($id);

        if (!$product) {
            return $this->responseHasil(404, false, "Product tidak ditemukan");
        }

        $product->delete();
        return $this->responseHasil(200, true, "Product berhasil dihapus");
    }
}