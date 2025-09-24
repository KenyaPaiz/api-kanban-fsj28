<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    //especificamos los campos de tu tabla que se van asignar de manera masiva
    protected $fillable = [
        'title',
        'description',
        'status',
        'priority',
        'due_date',
        'user_id'
    ];
}
