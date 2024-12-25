
<?php

use App\Enums\PermissionEnum;
use App\Http\Controllers\api\app\NgoController;
use Illuminate\Support\Facades\Route;

  Route::post('/ngo/store', [NgoController::class, 'store']);
  // ->middleware(["hasAddPermission:" . PermissionEnum::ngo->value]);

  Route::get('/ngos', [NgoController::class, 'ngos']);

Route::prefix('v1')->middleware(['api.key', "auth:sanctum"])->group(function () {


      // Route::post('/ngo/store', [NgoController::class, 'store'])->middleware(["hasAddPermission:" . PermissionEnum::ngo->value]);

});
