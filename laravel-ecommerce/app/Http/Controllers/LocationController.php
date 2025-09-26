<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LocationController extends Controller
{
    public function getCities($countryCode)
    {
        $cities = get_cities_for_country($countryCode);
        
        return response()->json([
            'success' => true,
            'cities' => $cities
        ]);
    }

    public function detectLocation()
    {
        $location = get_user_country_from_ip();
        $cities = get_cities_for_country($location['country_code']);
        
        return response()->json([
            'success' => true,
            'location' => $location,
            'cities' => $cities
        ]);
    }
}