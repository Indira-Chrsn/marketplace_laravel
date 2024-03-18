<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Pagination\CursorPaginator;
use App\Http\Resources\ProductResource;
use App\Http\Controllers\StorePostRequest;

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
        // return response()->json(['products' => $products]);
        
        return ProductResource::collection($products); 
        
        /**use collection because in index we gonna fetch a collection of data (many product model that is united in one collection)
         

        // $products = Product::where('category_id', '=', 1)->paginate(3);
        // $products = Product::where('name', 'like', "I")->with('category')->get();

        */
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'price' => 'required|numeric|no_negative_value',
            'stock' => 'required|integer|no_negative_value',
            'category_id' => 'required|integer|no_negative_value',
            'brand_id' => 'required|integer|no_negative_value'
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => "Gagal menambahkan produk"], 500);
        }

        $validated = $validator->validated();

        $product = Product::create($validated);

        if ($product) {
            return response()->json(['product' => $product]);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $product = Product::find($id);

        if ($product) {
            return ProductResource::make($product)->withDetails();
        } else {
            return response()->json(['message' => 'Produk tidak ditemukan'], 404);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        // $validatedData = $request->validated();

        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'price' => 'required|numeric|no_negative_value',
            'stock' => 'required|integer|no_negative_value',
            'category_id' => 'integer|no_negative_value',
            'brand_id' => 'integer|no_negative_value'
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => "Gagal memperbarui detail produk"], 500);
        }

        $validated = $validator->validated();

        $product = Product::find($id);
        if (!$product) {
            return response()->json(['message' => 'Produk tidak ditemukan'], 404);
        }

        $product->update($validated);
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
     * Restore soft-deleted product
     */
    public function restore(string $id) {
        $product = Product::withTrashed()->find($id);

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