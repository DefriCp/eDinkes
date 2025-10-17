<?php

namespace App\Http\Controllers;

use App\Models\HealthFacility;
use Illuminate\Http\Request;

class FacilityApiController extends Controller
{
    public function list(Request $request)
    {
        $q = HealthFacility::query()->select('id','name','type','address','lat','lng');

        if ($t = $request->query('type')) {
            $q->where('type', $t);
        }

        return $q->orderBy('name')->get();
    }
}
