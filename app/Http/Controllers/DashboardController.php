<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\HistoryTransaksion;
use App\Models\Stock;
use App\Models\Product;

class DashboardController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $transaksi_keluar = 0;
        $transaksi_masuk = 0;
        $total_keluar = 0;

        if(auth('sanctum')->user()->role_id != 1){
            $stock = HistoryTransaksion::where('type', 'out')->where('user_id',auth('sanctum')->user()->id)->sum('qty');
            
        }else{
            $stock = Product::sum('qty');

            $transaksi_keluar = Stock::where('type','out')->sum('qty');
            
            $transaksi_masuk = Stock::where('type','in')->sum('qty');

            $total_product = Product::count('product_name');
            
        }
        
        return response()->json([
            'success' => true,
            'message' => 'Detail Data Stok',
            'data' => [
                'sisa_stock' => $stock,
                'transaksi_keluar'=>$transaksi_keluar,
                'transaksi_masuk'=>$transaksi_masuk,
                'total_product'=>$total_product
            ]
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
    public function store(Request $request)
    {
        //
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
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
