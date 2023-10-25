<?php

namespace App\Http\Controllers;

use App\Models\Country;
use Illuminate\Http\Request;

class CountryController extends Controller
{
    public function index() {
        try {
            $country = Country::all();

            return $this->success(
                'get',
                $country,
            );
        } catch (\Throwable $th) {
            return $this->errorServer($th->getMessage());
        }    
    }

    public function currency() {
        try {
            $country = Country::select('id', 'name', 'currency', 'currency_name', 'currency_symbol')->get();

            return $this->success(
                'get',
                $country,
            );
        } catch (\Throwable $th) {
            return $this->errorServer($th->getMessage());
        }    
    }
}
