
<?php

use App\Enums\PermissionEnum;
use App\Http\Controllers\api\app\NgoController;
use Illuminate\Support\Facades\Route;

// ->middleware(["hasAddPermission:" . PermissionEnum::ngo->value]);
Route::prefix('v1')->middleware(['api.key', "auth:sanctum"])->group(function () {
  Route::get('/ngos', [NgoController::class, 'ngos'])->middleware(["hasViewPermission:" . PermissionEnum::ngo->value]);
  Route::post('/ngo/store', [NgoController::class, 'store'])->middleware(["hasAddPermission:" . PermissionEnum::ngo->value]);
});
