<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MonthlyDrugUsage extends Model
{
    protected $table = 'monthly_drug_usage';
    protected $fillable = ['drug_name','month','year','total_usage'];
}
