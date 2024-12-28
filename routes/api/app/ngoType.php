
<?php

use App\Enums\PermissionEnum;
use App\Http\Controllers\api\app\NgoTypeController;
use Illuminate\Support\Facades\Route;


Route::prefix('v1')->middleware(['api.key', "auth:sanctum"])->group(function () {
  Route::get('/ngo/types', [NgoTypeController::class, 'types'])->middleware(["hasAddPermission:" . PermissionEnum::settings->value]);
});
