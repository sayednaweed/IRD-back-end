<?php

namespace App\Http\Controllers;

use App\Models\NgoType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Redis;

use function Laravel\Prompts\select;

class TestController extends Controller
{
    public function index(Request $request)
    {

        // Redis::set('name', 'Taylor');

        return Redis::get("name");
        return NgoType::join('ngo_type_trans', 'ngo_types.id', '=', 'ngo_type_trans.ngo_type_id')
            ->where('ngo_type_trans.lang', "en")
            ->select('ngo_type_trans.value as name', "ngo_types.id")
            ->orderBy('ngo_types.id', 'desc')
            ->get();
    }
}
