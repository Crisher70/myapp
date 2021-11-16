<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Sale;
use App\Models\Detail;
use App\Models\Product;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

class SaleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $sales = Sale::query()
                ->with('details.product', 'client')
                ->get();
        
        return $sales;
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = validator::make($request->all(),[
            'client_id' => 'required|integer',
            'iva' => 'required|integer',
            'discount' => 'required|integer',
            'total' => 'required|integer',
        ]);

        if($validator->fails())
            return response()->json($validator->errors(), '400');

        $validatorProduct = validator::make($request->products,[
                '*.product_id' => 'required|integer',
                '*.quantity' => 'required|integer',
                '*.sub_total' => 'required|integer'
        ]);

        if($validatorProduct->fails())
            return response()->json($validatorProduct->errors(), '400');

        try{
            $sale = new Sale();
            $sale->client_id = $request->get('client_id');
            $sale->iva =  $request->get('iva');
            $sale->discount =  $request->get('discount');
            $sale->total =  $request->get('total');
            $sale->sold_at = Carbon::now();
            $sale->save();

            foreach ($request->products as $product) {
                $detail = new Detail();
                $detail->sale_id = $sale->id;
                $detail->product_id = $product['product_id'];
                $detail->quantity= $product['quantity'];
                $detail->sub_total = $product['sub_total'];
                $detail->save();

                $product = Product::find($product['product_id']);
                $product->quantity = ($product->quantity - $detail->quantity);
                $product->save();
            }
        }catch(\Exception $ex){
            return response()->json('Error al agregar '  . $ex, 400);
        }
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
        $validator = validator::make($request->all(),[
            'client_id' => 'required|integer',
            'iva' => 'required|integer',
            'discount' => 'required|integer',
            'total' => 'required|integer',
        ]);

        if($validator->fails())
            return response()->json($validator->errors(), '400');

        $validatorProduct = validator::make($request->products,[
                '*.id' => 'nullable|integer',
                '*.quantity' => 'required|integer',
                '*.sub_total' => 'required|integer'
        ]);

        if($validatorProduct->fails())
            return response()->json($validatorProduct->errors(), '400');

        try{
            $sale = Sale::find($request->get('id'));
            $sale->client_id = $request->get('client_id');
            $sale->iva =  $request->get('iva');
            $sale->discount =  $request->get('discount');
            $sale->total =  $request->get('total');
            $sale->save();

            $detailsExists = $sale->details->map(function($detail){
                return $detail->product_id;
            })->toArray();

            foreach ($request->products as $product) {
                if(in_array($product['product_id'], $detailsExists)){
                    $key = array_search($product['product_id'],$detailsExists);
                    array_splice($detailsExists, $key, 1);
                }

                $detail = Detail::updateOrCreate(
                    ['product_id' => $product['product_id'],  'sale_id' => $sale->id],
                    [
                    'quantity' => $product['quantity'],
                    'sub_total' => $product['sub_total']
                    ]
                    );
             }

            foreach ( $detailsExists as $value) {
                $detailFound = Detail::where('product_id', $value)
                                ->where('sale_id',$sale->id)
                                ->first();
                $delete = Detail::find($detailFound->id);
                $delete->delete();
            }


        }catch(\Exception $ex){
            return response()->json('Error al agregar '  . $ex, 400);
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
        try{
            $sale = Sale::find($id);
            $sale->delete();
            return response()->json('Eliminado con éxito');
        }catch(\Exception $ex){
            return response()->json('Error al eliminar ' . $ex, 400);
        }
    }
}
