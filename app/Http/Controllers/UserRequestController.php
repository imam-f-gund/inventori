<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\HistoryTransaksion;
use App\Models\Product;
use App\Models\Stock;
use Illuminate\Support\Facades\DB;

class UserRequestController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if(auth('sanctum')->user()->role_id != 1){
            return response()->json([
                'success' => false,
                'message' => 'Not Authorized',
                'code' => 201,
            ], 201);
        }

        if ($request->status == 'pending') {
            if(($request->start_date == null) && ($request->end_date == null)){
                $req = HistoryTransaksion::with('product')->with('user')->where('status','pending')->paginate(10);
            }else{
                $req = HistoryTransaksion::with('product')->with('user')->where('status','pending')->whereBetween('date', [$request->start_date, $request->end_date])->paginate(10);
            }
        }else if ($request->status == 'approved') {
            if(($request->start_date == null) && ($request->end_date == null)){
                $req = HistoryTransaksion::with('product')->with('user')->where('status','approved')->paginate(10);
            }else{
                $req = HistoryTransaksion::with('product')->with('user')->where('status','approved')->whereBetween('date', [$request->start_date, $request->end_date])->paginate(10);
            }
        }else if ($request->status == 'rejected') {
            if(($request->start_date == null) && ($request->end_date == null)){
                $req = HistoryTransaksion::with('product')->with('user')->where('status','rejected')->paginate(10);
            }else{
                $req = HistoryTransaksion::with('product')->with('user')->where('status','rejected')->whereBetween('date', [$request->start_date, $request->end_date])->paginate(10);
            }
            
        }else{
            if(($request->start_date == null) && ($request->end_date == null)){
                $req = HistoryTransaksion::with('product')->with('user')->paginate(10);
            }else{
                $req = HistoryTransaksion::with('product')->with('user')->whereBetween('date', [$request->start_date, $request->end_date])->paginate(10);
            }
            
        }

        return response()->json([
            'success' => true,
            'message' => 'Data request',
            'data' => $req,
            
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
        try {
            DB::beginTransaction();

            $history = HistoryTransaksion::find($id);

            if ($request->status == 'approved') {
                
                $product = Product::find($history->product_id);
                $stock = new Stock;
                
            
                if ($history->qty > $product->qty) {
                
                    return response()->json([
                        'success' => false,
                        'message' => 'Stok tidak mencukupi',
                        'data' => null
                    ], 200);
                }

                $stock->qty = $history->qty;
                $product->qty = $product->qty - $history->qty;
            
                $stock->product_id = $history->product_id;
                $stock->date = now();
                $stock->note = $history->note;
                $stock->type = $history->type;
                $stock->save(); 
                
                $history->status = 'approved';
                $history->save();
            
                $product->save();
            }else{
                $history->status = 'rejected';
                $history->save();
            }
            
            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Stok berhasil diupdate',
                'data' =>$history

           ], 200);

        } catch (\Throwable $th) {
            DB::rollback();

            return response()->json([
                'success' => false,
                'message' => 'Error',
            ], 401);
        }
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
