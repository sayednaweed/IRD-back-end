
<?php

use App\Enums\PermissionEnum;
use App\Http\Controllers\api\app\DonorController;
use Illuminate\Support\Facades\Route;



  Route::POST('donor/store', [DonorController::class, 'store']);
  // ->middleware(["hasAddPermission:" . PermissionEnum::donor->value]);

  Route::get('/donors', [DonorController::class, 'donors']);

Route::prefix('v1')->middleware(['api.key', "auth:sanctum"])->group(function () {



});
