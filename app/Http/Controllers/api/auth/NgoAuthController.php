<?php

namespace App\Http\Controllers\api\auth;

use App\Enums\StatusTypeEnum;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Models\Email;
use App\Models\Ngo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class NgoAuthController extends Controller
{
    //



       public function login(LoginRequest $request)
    {
      
        $locale = App::getLocale();
        $credentials = $request->validated();
        $email = Email::where('value', '=', $credentials['email'])->first();
        if (!$email) {
            return response()->json([
                'message' => __('app_translation.email_not_found'),
            ], 403, [], JSON_UNESCAPED_UNICODE);
        }
         $ngo = Ngo::where('email_id', '=', $email->id)->first();
       if ($ngo) {
                $ngoStatus = $ngo->ngoStatus; // Assuming ngoStatus is a relationship
                if ($ngoStatus && $ngoStatus->status_type_id === StatusTypeEnum::blocked) {
                    return response()->json([
                        'message' => __('app_translation.account_is_lock'),
                    ], 403, [], JSON_UNESCAPED_UNICODE);
                }
            
            // Check password
            if (!Hash::check($credentials['password'], $ngo->password)) {
                return response()->json([
                    'message' => __('app_translation.incorrect_credentials'),
                ], 422, [], JSON_UNESCAPED_UNICODE);
            }
            $token = $ngo->createToken("ngos")->plainTextToken;
            $ngoPermissions = $this->ngoWithPermission($ngo);
            $ngo = $ngo->load([
                'contact:id,value',
                'email:id,value',
                'ngoStatus,id,status_type_id',
                'ngoTrans' => function ($query) use ($locale) {
                $query->where('language_name', $locale)->select('id', 'ngo_id', 'name');
            },
            
            ]);
            
        

            return response()->json(
                array_merge([
                    "ngo" => [
                        "id" => $ngo->id,
                        "ngoname" => $ngo->name,
                        'email' => $ngo->email ? $ngo->email->value : "",
                        "profile" => $ngo->profile,
                        "status" => $ngo->status_type_id,
                        'contact' => $ngo->contact ? $ngo->contact->value : "",
                        
                        "createdAt" => $ngo->created_at,
                    ]
                ], [
                    "token" => $token,
                    "permissions" => $ngoPermissions["permissions"],
                ]),
                200,
                [],
                JSON_UNESCAPED_UNICODE
            );
        
        } else {
            return response()->json([
                'message' => __('app_translation.user_not_found')
            ], 404, [], JSON_UNESCAPED_UNICODE);
        }
    
    }


     protected function ngoWithPermission($ngo)
    {
        $ngoId = $ngo->id;
        $ngoPermissions = DB::table('ngo_permissions')
            ->join('permissions', function ($join) use ($ngoId) {
                $join->on('ngo_permissions.permission', '=', 'permissions.name')
                    ->where('ngo_permissions.ngo_id', '=', $ngoId);
            })
            ->select(
                "permissions.name as permission",
                "permissions.icon as icon",
                "permissions.priority as priority",
                "ngo_permissions.view",
                "ngo_permissions.add",
                "ngo_permissions.delete",
                "ngo_permissions.edit",
                "ngo_permissions.id",
            )
            ->orderBy("priority")
            ->get();
        return ["ngo" => $ngo->toArray(), "permissions" => $ngoPermissions];
    }
}
