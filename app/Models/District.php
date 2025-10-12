<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class District extends Model {
    protected $fillable = ['code_bps','name','geojson_prop_key'];
    public function metrics(){ return $this->hasMany(AreaMetric::class); }
    public function facilities(){ return $this->hasMany(HealthFacility::class); }
}
