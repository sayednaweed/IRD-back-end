<?php

namespace App\Http\Controllers;

use App\Models\Ngo;
use App\Models\NgoType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;

use function Laravel\Prompts\select;
use Illuminate\Support\Facades\Redis;

class TestController extends Controller
{
    public function index(Request $request)
    {
        $locale = "en";
        $query = Ngo::with([
            'ngoTrans' => function ($query) use ($locale) {
                $query->where('language_name', $locale)->select('id', 'ngo_id', 'name');
            },
            'ngoType' => function ($query) use ($locale) {
                $query->with(['ngoTypeTrans' => function ($query) use ($locale) {
                    $query->where('lang', $locale)->select('ngo_type_id', 'value as name');
                }]);
            },
            'ngoStatus' => function ($query) {
                $query->select('ngo_id', 'operation');
            },
            'agreement' => function ($query) {
                $query->select('ngo_id', 'end_date');
            },
        ])
            ->select([
                'id',
                'registration_no',
                'date_of_establishment',
                'ngo_type_id',
            ]);

        return $query;
        // Redis::set('name', 'Taylor');

        return Redis::get("name");
        return NgoType::join('ngo_type_trans', 'ngo_types.id', '=', 'ngo_type_trans.ngo_type_id')
            ->where('ngo_type_trans.lang', "en")
            ->select('ngo_type_trans.value as name', "ngo_types.id")
            ->orderBy('ngo_types.id', 'desc')
            ->get();
    }
}
