<?php

namespace App\Http\Controllers;

use App\Http\Requests\CategoryRequest;
use App\Models\Category;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $role = auth('sanctum')->user();

        if ($role->role_id != 1) {
            return response()->json([
                'success' => false,
                'message' => 'Not Authorized',
                'code' => 201,
            ], 201);
        }

        $categories = Category::all();

        return response()->json([
            'success' => true,
            'message' => 'List Data Categories',
            'data' => $categories
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
    public function store(CategoryRequest $request)
    {
        $role = auth('sanctum')->user();

        if ($role->role_id != 1) {
            return response()->json([
                'success' => false,
                'message' => 'Not Authorized',
                'code' => 201,
            ], 201);
        }

       $categories = Category::create($request->all());
        
        return response()->json([
            'success' => true,
            'message' => 'Stok berhasil simpan',
            'data' => $categories
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
    public function update(CategoryRequest $request, $id)
    {
        $role = auth('sanctum')->user();

        if ($role->role_id != 1) {
            return response()->json([
                'success' => false,
                'message' => 'Not Authorized',
                'code' => 201,
            ], 201);
        }

      $categories = Category::find($id)->update($request->all());

        return response()->json([
            'success' => true,
            'message' => 'Categories berhasil diupdate',
            'data' => $categories
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
        
       $data = Category::find($id);
        $data->delete();

        return response()->json([
            'success' => true,
            'message' => 'Categories berhasil hapus',
            'data' => $data
        ], 200);
    }
}
