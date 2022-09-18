<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Country;
use App\Models\State;
use App\Models\City;


class AddressController extends Controller
{
    public function getAllCountries()
    {
        return Country::where('status','=',1)->get();

    }

    public function getAllStates()
    {
        return State::where('country_id','=',65)->get();
    }


    public function getAllCities($id)
    {
        return City::where('state_id','=',$id)->get();

    }

}
