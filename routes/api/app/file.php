
<?php

use App\Enums\PermissionEnum;
use App\Http\Controllers\api\app\file\FileController;
use Illuminate\Support\Facades\Route;



Route::prefix('v1')->middleware(['api.key', "auth:sanctum"])->group(function () {
  Route::post('file/upload', [FileController::class, 'fileUpload']);

});
