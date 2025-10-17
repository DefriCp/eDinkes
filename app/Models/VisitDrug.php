<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VisitDrug extends Model
{
    protected $fillable = ['visit_id','drug_name','quantity','unit'];

    public function visit()
    {
        return $this->belongsTo(Visit::class);
    }
}
