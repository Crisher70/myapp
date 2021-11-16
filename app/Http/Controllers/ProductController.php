<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Product::all();
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
            'name' => 'required|string',
            'price' => 'required|integer',
            'quantity' => 'required|integer'
        ]);

        if($validator->fails())
            return response()->json($validator->errors(), '400');

        try{
            $product = new Product();
            $product->name = $request->get('name');
            $product->price = $request->get('price');
            $product->quantity = $request->get('quantity');
            $product->save();

            return response()->json('Realizado '  . $product);
        }catch(\Exception $ex){
            return response()->json('Error al agregar ' . $ex, 400);
        }
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
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $validator = validator::make($request->all(),[
            'name' => 'required|string',
            'price' => 'required|integer',
            'quantity' => 'required|integer'
        ]);

        if($validator->fails())
            return response()->json($validator->errors(), '400');

        try{
            $product = Product::find($id);
            $product->name = $request->get('name');
            $product->price = $request->get('price');
            $product->quantity = $request->get('quantity');
            $product->save();

            return response()->json('Operación realizada');
        }catch(\Exception $ex){
            return response()->json('Error al modificar ' . $ex, 400);
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
            $product = Product::find($id);
            $product->delete();
            return response()->json('Eliminado con éxito');
        }catch(\Exception $ex){
            return response()->json('Error al eliminar ' . $ex, 400);
        }
    }
}
