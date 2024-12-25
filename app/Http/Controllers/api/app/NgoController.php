<?php

namespace App\Http\Controllers\api\app;

use App\Enums\LanguageEnum;
use App\Http\Controllers\Controller;
use App\Http\Requests\app\NgoRegisterRequest;
use App\Models\Address;
use App\Models\Email;
use App\Models\Ngo;
use App\Models\NgoTran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Hash;

class NgoController extends Controller
{
    //
public function ngos(Request $request)
{
    $locale = App::getLocale();
    $locale = 'ps'; // Setting the default locale
    $perPage = $request->input('per_page', 10);
    $page = $request->input('page', 1);

    // Eager loading relationships
    $query = Ngo::with([
        'ngoTran' => function ($query) use ($locale) {
            $query->where('language_name', $locale)->select('ngo_id', 'name as ngo_name');
        },
        'ngoType:id,name as type_name',
        'ngoStatus' => function ($query) {
            $query->select('ngo_id', 'operation');
        },
        'agreement' => function ($query) {
            $query->select('ngo_id', 'end_date');
        },
    ])->select([
        'id',
        'registration_no',
        'date_of_establishment',
        'ngo_type_id'
    ]);

    // Apply filters
    $this->applyDateFilters($query, $request->input('filters.date.startDate'), $request->input('filters.date.endDate'));
    $this->applySearchFilter($query, $request->input('filters.search'));

    // Apply sorting
    $sort = $request->input('filters.sort', 'registration_no');
    $order = $request->input('filters.order', 'asc');
    $query->orderBy($sort, $order);

    // Paginate results
    $result = $query->paginate($perPage, ['*'], 'page', $page);

    // Return JSON response
    return response()->json(
        ["ngos" => $result],
        200,
        [],
        JSON_UNESCAPED_UNICODE
    );
}

private function applySearchFilter($query, $search)
{
    if (!empty($search['column']) && !empty($search['value'])) {
        $allowedColumns = ['registration_no', 'id', 'ngoType.name', 'ngoTran.name'];
        
        if (in_array($search['column'], $allowedColumns)) {
            if ($search['column'] == 'ngoType.name') {
                // Search in ngoType's name (aliased as type_name)
                $query->whereHas('ngoType', function ($q) use ($search) {
                    $q->where('name', 'like', '%' . $search['value'] . '%');
                });
            } elseif ($search['column'] == 'ngoTran.name') {
                // Search in ngoTran's name (aliased as ngo_name)
                $query->whereHas('ngoTran', function ($q) use ($search) {
                    $q->where('name', 'like', '%' . $search['value'] . '%');
                });
            } else {
                // Default search for registration_no or id
                $query->where($search['column'], 'like', '%' . $search['value'] . '%');
            }
        }
    }
}



private function applyDateFilters($query, $startDate, $endDate)
{
    if ($startDate || $endDate) {
        if ($startDate && $endDate) {
            $query->whereBetween('ngos.date_of_establishment', [$startDate, $endDate]);
        } elseif ($startDate) {
            $query->where('ngos.date_of_establishment', '>=', $startDate);
        } elseif ($endDate) {
            $query->where('ngos.date_of_establishment', '<=', $endDate);
        }
    }
}





 public function store(NgoRegisterRequest $request)
{
    $validatedData = $request->validated();

    // Create email
    $email = Email::create(['value' => $validatedData['email']]);

    // Create address
    $address = Address::create([
        'district_id' => $validatedData['district_id'],
        'area' => $validatedData['area'],
    ]);

    // Create NGO
    $newNgo = Ngo::create([
        'abbr' => $validatedData['abbr'],
        'registration_no' => $validatedData['registration_no'],
        'date_of_establishment' => $validatedData['date_of_establishment'],
        'ngo_type_id' => $validatedData['ngo_type_id'],
        'address_id' => $address->id,
        'moe_registration_no' => $request->moe_registration_no,
        'place_of_establishment' => $validatedData['country_id'],
        'email_id' => $email->id,
        "password" => Hash::make($validatedData['password']),
    ]);


    
        NgoTran::create([
            'ngo_id' => $newNgo->id,
            'language_name' =>  LanguageEnum::default->value,
            'name' => $validatedData['name_en'],
            'vision'  =>'',
            'mission' => '',
            'general_objective' =>'',
            'profile' => '',
            'objective' =>'',
            'introduction' =>''
        ]);


   

    return response()->json(['message' => __('app_translation.success')], 200, [], JSON_UNESCAPED_UNICODE);
}

    



}
