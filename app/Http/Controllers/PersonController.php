<?php

namespace App\Http\Controllers;

use App\Models\Person;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class PersonController extends Controller
{
    public function __construct(private Person $person)
    {
        
    }    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if(!$request->query('maxAge') && !$request->query('cpfCnpj')) {
            $persons = Person::all();
            return response()->json($persons);
        }
        
        
        return response()->json($request->query('maxAge'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = $request->all();
        $data["password"] = Hash::make($request->password);
        $person = Person::create($data);
        return response()->json($person, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $person = Person::find($id);

        if(!$person) 
            return response()->json(['message' => 'Registro não encontrado'], 404);
        else
            return response()->json($person); 
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
        $person = Person::find($id);
        if(!$person) 
            return response()->json(['message' => 'Registro não encontrado'], 404);
        
        $data = $request->all();

        if($request->password) 
            $data["password"] = Hash::make($request->password);
        
        $person->update($data);
        return response()->json(['message' => 'Registro atualizado']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $person = Person::find($id);
        if(!$person)
            return response()->json(['message' => 'Registro não encontrado'], 404);
        
        $person->delete();

        return response()->json(['message' => 'Registro deletado']);
    }
}
