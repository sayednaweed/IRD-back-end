
<?php

use App\Http\Controllers\api\template\ProvinceController;
use Illuminate\Support\Facades\Route;




  

Route::prefix('v1')->middleware(['api.key', "auth:sanctum"])->group(function () {
 


    Route::get('/provinces', [ProvinceController::class, "provinces"]);

    Route::get('/province/district/{id}',[ProvinceController::class, 'district']);
});
