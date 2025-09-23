<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Test extends Model
{
    use HasFactory;
    //indicamos que el modelo le pertenece a la tabla "test" de la base de datos
    protected $table = 'test';
}
