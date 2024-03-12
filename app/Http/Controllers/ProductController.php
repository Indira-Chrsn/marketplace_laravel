<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Pagination\CursorPaginator;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $product_query = Product::with(['category', 'brand']);

        // search by product name
        if ($request->keyword) {
            $product_query->where('name', 'LIKE', '%'.$request->keyword.'%');
        }

        // search by price
        if ($request->min_price && $request->max_price) {
            $minPrice = $request->get('min_price', null);
            $maxPrice = $request->get('max_price', null);

            $product_query->where(function ($query) use ($minPrice, $maxPrice) {
                if (!is_null($minPrice)) {
                    $query->where('price', '>=', $minPrice);
                }
                if (!is_null($maxPrice)) {
                    $query->where('price', '<=', $maxPrice);
                }
            });
        }

        // filter by category
        if ($request->category) {
            $product_query->whereHas('category', function($query) use($request){
                $query->where('name', $request->category);
            });
        }

        // filter by brands
        if ($request->brand) {
            $product_query->whereHas('brand', function($query) use($request) {
                $query->where('name', $request->brand);
            });
        }

        // sort by
        if ($request->sortBy && in_array($request->sortBy,['id', 'name', 'price'])) {
            $sortBy = $request->sortBy;
        } else {
            $sortBy = 'id';
        }

        // sort order
        if ($request->sortOrder && in_array($request->sortOrder,['asc', 'desc'])) {
            $sortOrder = $request->sortOrder;
        } else {
            $sortOrder = 'asc';
        }

        $products = $product_query->orderBy($sortBy, $sortOrder)->get();
        return response()->json(['products' => $products]);
        // 
        // $products = Product::where('category_id', '=', 1)->paginate(3);
        // $products = Product::where('name', 'like', "I")->with('category')->get();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required',
            'price' => 'required|numeric|no_negative_value',
            'stock' => 'required|integer|no_negative_value',
            'category_id' => 'integer|no_negative_value'
        ]);
        // $validated = $request->validated();

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
        $product = Product::find($id);

        if ($product) {
            return response()->json(['product' => $product]);
        } else {
            return response()->json(['message' => 'Produk tidak ditemukan'], 404);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(StorePostRequest $request, string $id)
    {
        $validatedData = $request->validated();

        $product = Product::find($id);
        if (!$product) {
            return response()->json(['message' => 'Produk tidak ditemukan'], 404);
        }

        $product->update($validatedData);
        return response()->json(['message' => 'produk berhasil diupdate', 'product' => $product]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $product = Product::find($id);

        if (!$product) {
            return response()->json(['message' => 'produk tidak ditemukan'], 404);
        }

        $product->delete();
        return response()->json(['message' => 'Produk berhasil dihapus']);
    }

    /**
     * show the form to create new data
     */
    public function insert()
    {
        return view('products.create');
    }

    /**
     * add current timestamp to deleted product without actually deleted the data
     */
    public function soft_delete(string $id) {
        $product = Product::find($id);

        if (!$product) {
            return response()->json(['message' => 'produk tidak ditemukan'], 404);
        }

        $product->delete();
        if (!$product->trashed()) {
            return response()->json(['message' => 'produk gagal dihapus'], 500);
        }

        return response()->json(['message' => 'produk berhasil dihapus']);
    }

    /**
     * Restore soft-deleted product
     */
    public function restore(string $id) {
        $product = Product::find($id);

        if (!$product) {
            return response()->json(['message' => 'produk tidak ditemukan']);
        }

        $product->restore();
        return response()->json(['message' => 'produk berhasil ditambahkan kembali']);
    }



    /**
     * add soft delete [done]
     * add restore [done]
     * buat store + validasinya [done]
     * insert untuk memunculkan form [done]
     * routing
     * 
     */


    // search by name [done] & price [done]
    // sorting asc & desc (name, price) [done]
    // filter by category [done]
    // range harga 
}



/**
 * kalau getAll:
 * Data: Product A
 * Data: Product B
 * 
 * kalau detail per product:
 * name:
 * 
 * 
 * productResource extends JsonResource untuk mengatur bentuk response
 * 
 * data [
 *      "id": x
 *      "name": aa
 *      dsb
 * 
 * ]
 * 
 * array of object
 * 
 * tambahkan tabel brand [done] + filter by [done] + relationship w product [done] , > 2 brand [done]
 */