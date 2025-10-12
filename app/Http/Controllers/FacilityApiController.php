<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Puskesmas;
use App\Models\Posyandu;

class FacilityApiController extends Controller
{
    public function list(Request $request)
    {
        $type = $request->query('type');

        if ($type === 'puskesmas') {
            return Puskesmas::select('id','name')->orderBy('name')->get();
        }
        if ($type === 'posyandu') {
            return Posyandu::select('id','name')->orderBy('name')->get();
        }
        return response()->json([], 400);
    }
}
