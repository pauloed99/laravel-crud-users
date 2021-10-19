<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Person extends Model
{
    use HasFactory;

    protected $fillable = ["name", "cpf/cnpj", "password", "phone", "birthDate"];

    public $timestamps = false;

    function ageCalculator(string $birthDate) : int {
        $currentDate = explode("/", date("d/m/Y"));
        $birthDate = explode("/", $birthDate);

        $initialAge = intval($currentDate[2]) - intVal($birthDate[2]);

        if(intval($currentDate[1]) < intval($birthDate[1]))
            $initialAge--;
        if(intval($currentDate[1]) == intval($birthDate[1]) && intval($currentDate[0]) < intval($birthDate[0]))
            $initialAge--;
        
        return $initialAge;
    }
}
