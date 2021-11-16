<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Client;
use Illuminate\Support\Facades\Validator;

class ClientController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Client::all();
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
            'last_name' => 'required|string',
            'status' => 'required|boolean'
        ]);

        if($validator->fails())
            return response()->json($validator->errors(), '400');

        try{
            $client = new Client();
            $client->name = $request->get('name');
            $client->last_name = $request->get('last_name');
            $client->status = $request->get('status');
            $client->save();

            return response()->json('Realizado '  . $client);
        }catch(\Exception $ex){
            return response()->json('Error al eliminar '  . $ex, 400);
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
            'last_name' => 'required|string',
            'status' => 'required|boolean'
        ]);

        if($validator->fails())
            return response()->json($validator->errors(), '400');

        try{
            $client = Client::find($id);
            $client->name = $request->get('name');
            $client->last_name = $request->get('last_name');
            $client->status = $request->get('status');
            $client->save();

            return response()->json('Operación realizada');
        }catch(\Exception $ex){
            return response()->json('Error al modificar', 400);
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
            $client = Client::find($id);
            $client->delete();
            return response()->json('Eliminado con éxito');
        }catch(\Exception $ex){
            return response()->json('Error al eliminar ' . $ex, 400);
        }
       
    }
}
