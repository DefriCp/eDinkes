<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HealthFacility extends Model
{
    protected $fillable = ['district_id','name','type','address','lat','lng'];
    public function district(){ return $this->belongsTo(District::class); }
    public function scopeType($q, string $type)
    {
        return $q->where('type', $type);
    }

    public function visits()
    {
        return $this->hasMany(Visit::class, 'facility_id');
    }
}
