<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LocationController extends Controller
{
    public function getStates($country_id)
    {
        $states = \App\Models\State::where('country_id', $country_id)->get();
        return response()->json($states);
    }

    public function getCities($state_id)
    {
        $cities = \App\Models\City::where('state_id', $state_id)->get();
        return response()->json($cities);
    }
}
