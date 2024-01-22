<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProductRequest;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $role = auth('sanctum')->user();

        if ($role->role_id != 1) {
            return response()->json([
                'success' => false,
                'message' => 'Not Authorized',
                'code' => 201,
            ], 201);
        }
        
        $products = Product::with('category')->orderBy('date_input', 'DESC')->paginate(8);
        
        return response()->json([
            'success' => true,
            'message' => 'List Data products',
            'data' => $products
        ], 200);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ProductRequest $request)
    {
        $role = auth('sanctum')->user();

        if ($role->role_id != 1) {
            return response()->json([
                'success' => false,
                'message' => 'Not Authorized',
                'code' => 201,
            ], 201);
        }

        $product = new Product;
        $product->product_name = $request->product_name;
        $product->brand = $request->brand;
        $product->date_input = now();
        $product->qty = $request->qty;
        $product->price = $request->price;
        $product->selling_price = $request->selling_price;
        $product->category_id = $request->category_id;

        if ($request->hasFile('image')) {
            $image_name = time() . '.' . $request->file('image')->extension();

            $request->file('image')->move(public_path('images'), $image_name);

            $product->image = $image_name;
        }

        $product->save();

        return response()->json([
            'success' => true,
            'message' => 'produk berhasil simpan',
            'data' => $product
        ], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(ProductRequest $request, $id)
    {
        $role = auth('sanctum')->user();

        if ($role->role_id != 1) {
            return response()->json([
                'success' => false,
                'message' => 'Not Authorized',
                'code' => 201,
            ], 201);
        }

        $product = Product::find($id);
        $product->product_name = $request->product_name;
        $product->brand = $request->brand;
        $product->date_input = now();
        $product->qty = $request->qty;
        $product->price = $request->price;
        $product->selling_price = $request->selling_price;
        $product->category_id = $request->category_id;

        if ($request->hasFile('image')) {
            $image_name = time() . '.' . $request->file('image')->extension();

            $request->file('image')->move(public_path('images'), $image_name);

            $product->image = $image_name;
        }

        $product->save();

        return response()->json([
            'success' => true,
            'message' => 'produk berhasil diupdate',
            'data' => $product
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $role = auth('sanctum')->user();

        if ($role->role_id != 1) {
            return response()->json([
                'success' => false,
                'message' => 'Not Authorized',
                'code' => 201,
            ], 201);
        }

        $product = Product::find($id);
        
        if ($product->image != null) {
            unlink(public_path('images/' . $product->image));
        }

        $product->delete();

        return response()->json([
            'success' => true,
            'message' => 'produk berhasil dihapus',
            'data' => $product
        ], 200);
    }
}
