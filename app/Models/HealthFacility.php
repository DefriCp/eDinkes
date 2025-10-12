<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class HealthFacility extends Model {
    protected $fillable = ['district_id','name','type','address','lat','lng'];
    public function district(){ return $this->belongsTo(District::class); }
}
