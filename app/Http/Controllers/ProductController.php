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
        $type = $request->get('type');

        switch ($type) {
            case 'search':
                search($request);
            case 'sort':
                sort($request);
        }
        // $products = Product::all();
        // $products = Product::where('name', 'like', "I")->with('category')->get();
        $products = Product::where('category_id', '=', 1)->paginate(3);
        return response()->json(['products' => $products]);
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

    public function search(Request $request)
    {
        dd($request->all());
        // $validatedData = $request->validate(['keyword' => 'required']);

        $keyword = $request->get('keyword');

        $products = Product::where('name', 'like', "%{keyword}%")->paginate(5);

        // if ($request->has('price')) {
        //     $query->where('price', $request->get('price'));
        // }

        // $products = $query->paginate(5);
        return response()->json([
            'products' => $products
        ]);
    }

    public function sort(Request $request)
    {
        $validOrderFields = ['name', 'price'];
        $validOrder = ['asc', 'desc'];

        if (!in_array($request->get('orderField'), $validOrderFields) ||
            !in_array($request->get('order'), $validOrder)) {
                return response()->json(['error' => 'Invalid sort parameter'], 400);
        }

        $orderField = $request->get('orderField');
        $order = $request->get('order');

        // if ($order == "desc") {
        //     $products = Product::orderByDesc('{$orderField}')
        //         ->get();
        // } else {
        //     $products = Product::orderBy('{$orderField}')
        //         ->get();
        // }

        $products = Product::orderBy($orderField, $order)->get();
        
        return response()->json(['products' => $products]);
    }

    public function filter(Request $request)
    {
        $validFilter = ['name', 'Category_id', 'price'];

        if (!in_array($request->get('filter'), $validFilter)) {
            return response()->json(['error' => 'invalid parameter'], 400);
        }

        $filter = $request->get('filter');
        $filterValue = $request->get('filterValue');

        $products = Product::where($filter, $filterValue)->get();

        // if ($filter == "price") {
        //     if ($request->has('bottomRange') && $request->has('upperRange')) {
        //         $bottomRange = $request->get('bottomRange');
        //         $upperRange = $request->get('upperRange');

        //         $products = Product::where($filter, '>=', '{$bottomRange}', 'and', $filter, )
        //     }
        // }

        return response()->json(['products' => $products]);
    }





    /**
     * add soft delete [done]
     * add restore [done]
     * buat store + validasinya [done]
     * insert untuk memunculkan form [done]
     * routing
     * 
     */


    // search by name & price
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
 * tambahkan tabel brand + filter by + relationship w product, > 2 brand
 */