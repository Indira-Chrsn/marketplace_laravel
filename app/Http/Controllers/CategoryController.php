<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Contracts\Database\Eloquent\Builder;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // case when there's condition to be met
        // $categories = Category::with(['products' => function (Builder $query) {
        //     $query->where('name', 'like', '%I%');
        // }])->get();

        // case when only need to retrieve all
        $categories = Category::with('products')->paginate(2);

        return response()->json(['categories' => $categories]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|max:25'
        ]);

       if($validator->fails()) {
        return response()->json(['message' => "Gagal menambahkan kategori"], 500);
       }

        $validated = $validator->validated();

        $category = Category::create($validated);

        if ($category) {
            return response()->json(['category' => $category]);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $category = Category::find($id);

        if ($category) {
            return response()->json(['category' => $category]);
        } else {
            return response()->json(['message' => 'category tidak ditemukan'], 404);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|max:25'
        ]);

       if($validator->fails()) {
        return response()->json(['message' => "Gagal memperbarui info kategori"], 500);
       }

        $validated = $validator->validated();

        $category = Category::find($id);

        if (!$category) {
            return response()->json(['message' => 'Kategori tidak ditemukan'], 404);
        }

        $category->update($validated);
        return response()->json(['message' => 'Kategori berhasil diupdate', 'category' => $category]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $category = Category::find($id);

        if (!$category) {
            return response()->json(['message' => 'category tidak ditemukan'], 404);
        }

        $category->delete();
        $category->products()->update(['deleted_at' => now()]);

        return response()->json(['message' => 'Category berhasil dihapus.']);
    }

    public function restore(string $id) {
        $category = Category::withTrashed()->find($id);

        if (!$category) {
            return response()->json(['message' => 'Category tidak ditemukan']);
        }

        $category->restore();
        $category->products()->withTrashed()->restore();

        return response()->json(['message' => 'Category berhasil ditambahkan kembali.']);
    }
}
