
<?php

use App\Enums\PermissionEnum;
use App\Http\Controllers\api\app\NgoController;
use Illuminate\Support\Facades\Route;



Route::prefix('v1')->middleware(['api.key', "auth:sanctum"])->group(function () {
  // Route::get('/ngos/{page}', [NgoController::class, 'ngos'])->middleware(["hasViewPermission:" . PermissionEnum::ngo->value]);
  Route::post('/ngo/store', [NgoController::class, 'store'])->middleware(["hasAddPermission:" . PermissionEnum::ngo->value]);
});
