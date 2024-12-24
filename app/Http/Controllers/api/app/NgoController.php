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

       public function ngos(Request $request, $page)
    {
        $locale = App::getLocale();
        $tr = [];
        $perPage = $request->input('per_page', 10); // Number of records per page
        $page = $request->input('page', 1); // Current page

        



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
        "password" => Hash::make($request->password),
    ]);



   

    return response()->json(['message' => __('app_translation.success')], 200, [], JSON_UNESCAPED_UNICODE);
}

    



}
