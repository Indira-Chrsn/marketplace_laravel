<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Contracts\Database\Eloquent\Builder;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // case when there's condition to be met
        $categories = Category::with(['products' => function (Builder $query) {
            $query->where('name', 'like', '%I%');
        }])->get();

        // case when only need to retrieve all
        // $categories = Category::with('products')->get();

        return response()->json(['categories' => $categories]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required',
        ]);

        $product = Product::create($validatedData);

        if ($product) {
            return response()->json(['product' => $product]);
        } else {
            return response()->json(['message' => "Gagal menambahkan produk"], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
