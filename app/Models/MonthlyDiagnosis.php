<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MonthlyDiagnosis extends Model
{
    protected $table = 'monthly_diagnoses';
    protected $fillable = ['code','name','month','year','total_cases'];
}
