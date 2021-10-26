<?php

namespace App\Http\Controllers;

use App\Models\Person;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
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
        $cpfCnpj = $request->query('cpfCnpj');
        $maxAge = $request->query('maxAge');

        if(!$maxAge && !$cpfCnpj) {
            $persons = Person::all();
            return response()->json($persons);
        }

        if($cpfCnpj && $maxAge) {
            $person = DB::table('people')->select([
                'id','name','birthDate','phone'
            ])->where('cpfCnpj', $cpfCnpj)->get();
            if(count($person) != 0 && $this->person->ageCalculator($person[0]->birthDate) <= $maxAge)
                return response()->json($person);
            return response()->json([]);
        }
        
        if($cpfCnpj) {
            $person = DB::table('people')->select(
                ['id','name','birthDate','phone']
            )->where('cpfCnpj', $cpfCnpj)->get();
            return response()->json($person);
        } 

        if($request->query('maxAge')) {
            $persons = DB::table('people')->select(
                ['id','name','birthDate','phone']
            )->get();
            $personsByAge = [];
            foreach ($persons as $value) {
                if($this->person->ageCalculator($value->birthDate) <= $maxAge) 
                    array_push($personsByAge, $value);
            }
            return response()->json($personsByAge);
        }
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
