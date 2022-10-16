<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\UserGroup;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public function login(Request $request) {
        $validator = Validator::make($request->all(), [
            "student_number" => 'required',
            "national_id" => 'required|numeric'
        ]);
        if ($validator->fails())
            return response()->json(['error' => $validator->errors()->first()], 400);

        $user_group = UserGroup::getGroupFromStudentNumber($request->student_number);
        if ($user_group) {
            $users = $user_group->users()->get();
            $to_check = $request->student_number . ' - ' . $request->national_id;
            foreach ($users as $user) {
                $pass = substr($user->password, 2, strlen($user->password) - 3);
                if (Hash::check($to_check, $pass)) {
                    $token = $user->createToken("name", [$user->role])->plainTextToken;
                    return response()->json([
                        'token' => $token,
                        'role' => $user->role
                    ]);
                }
            }
        }
        return response()->json(['error' => 'نام کاربری یا رمز عبور نادرست است']);
    }
}
