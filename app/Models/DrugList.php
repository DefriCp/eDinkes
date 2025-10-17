<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DrugList extends Model
{
    protected $table = 'drug_lists'; 
    protected $fillable = ['name', 'code'];
}
