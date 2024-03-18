<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Brand;
use Illuminate\Support\Facades\Validator;

class BrandController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $brands = Brand::get();

        return response()->json(['brands' => $brands]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|max:100'
        ]);

       if($validator->fails()) {
        return response()->json(['message' => "Gagal menambahkan brand"], 500);
       }

        $validated = $validator->validated();

        $brand = Brand::create($validated);

        if ($brand) {
            return response()->json(['brand' => $brand]);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $brand = Brand::find($id);

        if ($brand) {
            return response()->json(['brand' => $brand]);
        } else {
            return response()->json(['message' => 'brand tidak ditemukan'], 404);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|max:100'
        ]);

       if($validator->fails()) {
        return response()->json(['message' => "Gagal memperbarui info brand"], 500);
       }

        $validated = $validator->validated();

        $brand = Brand::find($id);

        if (!$brand) {
            return response()->json(['message' => 'Kategori tidak ditemukan'], 404);
        }

        $brand->update($validated);
        return response()->json(['message' => 'Kategori berhasil diupdate', 'brand' => $brand]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $brand = Brand::find($id);

        if (!$brand) {
            return response()->json(['message' => 'brand tidak ditemukan'], 404);
        }

        $brand->delete();

        return response()->json(['message' => 'brand berhasil dihapus.']);
    }

    public function restore(string $id)
    {
        $brand = Brand::withTrashed()->find($id);

        if (!$brand) {
            return response()->json(['message' => 'brand tidak ditemukan']);
        }

        $brand->restore();

        return response()->json(['message' => 'brand berhasil ditambahkan kembali.']);
    }
}
