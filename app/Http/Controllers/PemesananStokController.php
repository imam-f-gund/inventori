<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Stock;
use App\Models\HistoryTransaksion;
use RealRashid\SweetAlert\Facades\Alert;

use Illuminate\Support\Facades\DB;

class PemesananStokController extends Controller
{
    //

    public function index()
    {
        $product = Product::orderBy('date_input', 'DESC')->paginate(12);
        
        return response()->json([
            'success' => true,
            'message' => 'List Data Product',
            'data' => $product
        ], 200); 
    }

    public function store(Request $request)
    {
        try {
            DB::beginTransaction();
            $history = new HistoryTransaksion;
            $product = Product::find($request->product_id);
            if ($request->qty > $product->qty) {
            
                return response()->json([
                    'success' => false,
                    'message' => 'Stok tidak mencukupi',
                    'data' => null
                ], 200);
            }
            
            $history->qty = $request->qty;
            
            $history->product_id = $request->product_id;
            $history->user_id = auth('sanctum')->user()->id;
            $history->date = now();
            $history->note = $request->note;
            $history->type = $request->type;
            $history->status = 'pending';
            $history->save();
        
           
        DB::commit();

        return response()->json([
            'success' => true,
            'message' => 'Request berhasil',
            'data' => $history
        ], 200);

        } catch (\Throwable $th) {
            DB::rollback();

            return response()->json([
                'success' => false,
                'message' => $th,
            ], 401);
        }
        
    }

    public function show($id)
    {
        $stock = Stock::where('product_id', $id)->orderBy('date', 'DESC')->get();
        $product = Product::find($id);

        return response()->json([
            'success' => true,
            'message' => 'Detail Data Stok',
            'data' => [
                'product' => $product,
                'stock' => $stock
            ]
        ], 200);
    }

    public function laporan(Request $request)
    {
        if(($request->start_date == null) && ($request->end_date == null)){
            $stock = HistoryTransaksion::with('product')->OrderBy('id','desc')->where('history_transaksion.type', 'out')->where('user_id',auth('sanctum')->user()->id)->limit(50)->get();
            
        }else{
            $stock = HistoryTransaksion::with('product')->whereBetween('date', [$request->start_date, $request->end_date])->OrderBy('id','desc')->get();
            
        }
        
        return response()->json([
            'success' => true,
            'message' => 'Detail Data Stok',
            'data' => [
                'stock' => $stock,
            ]
        ], 200);
    }
}
