<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class AreaMetric extends Model {
    protected $fillable = ['district_id','month','year','idl_pct','k1_pct','k4_pct','dbd_cases','visits'];
    public function district(){ return $this->belongsTo(District::class); }
}
