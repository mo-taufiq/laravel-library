<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator as FacadesValidator;
use Throwable;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    public function ViewSignIn()
    {
        return view("page_login", [
            "title" => "Sign In"
        ]);
    }

    public function ViewSignUp()
    {
        return view("page_register", [
            "title" => "Sign Up"
        ]);
    }

    public function ViewAdminUsers()
    {
        $users = User::where('data_status', 'active')->get();
        return view("page_manage_users", [
            "title" => "Manage Users",
            "users" => $users,
        ]);
    }

    public function AuthSignUp(Request $request)
    {
        $validator = FacadesValidator::make($request->all(), [
            'username' => 'required_without:email|unique:users',
            'password' => 'required|confirmed|min:5',
        ]);

        if ($validator->fails()) {
            $data = $validator->errors()->messages();
            return response()->json([
                "data" => $data,
                "message" => "error validation",
                "success" => false,
            ], 422);
        }

        try {
            $password = $validator->validated()["password"];
            $user = User::create([
                "user_id" => uniqid("USER_", true),
                "username" => $validator->validated()["username"],
                "password" => Hash::make($password)
            ]);

            return response()->json([
                "data" => $user,
                "message" => "successfully created",
                "success" => true,
            ], 201);
        } catch (Throwable $error) {
            report($error);

            return response()->json([
                "error" => $error->getMessage(),
                "message" => "error",
                "success" => false,
            ], 500);
        }
    }

    public function AuthSignIn(Request $request)
    {
        $validator = FacadesValidator::make($request->all(), [
            'username' => 'required',
            'password' => 'required|min:5',
        ]);

        if ($validator->fails()) {
            $data = $validator->errors()->messages();
            return response()->json([
                "data" => $data,
                "message" => "error validation",
                "success" => false,
            ], 422);
        }

        $username = $validator->validated()["username"];

        try {
            $user = User::where('username', $username)->get();
            $user->makeVisible('password')->toArray();
            if (count($user) === 0) {
                return response()->json([
                    "message" => "authentication failed",
                    "success" => false,
                ], 400);
            }

            $inputPassword = $validator->validated()["password"];
            $hashedPassword = $user[0]["password"];
            if (Hash::check($inputPassword, $hashedPassword)) {
                $request->session()->put("user_id", $user[0]["user_id"]);
                $request->session()->put("username", $user[0]["username"]);
                $request->session()->put("role", $user[0]["role"]);
                $request->session()->put("image_user", $user[0]["image"]);
                return response()->json([
                    "message" => "authentication success",
                    "success" => true,
                ], 200);
            } else {
                return response()->json([
                    "message" => "authentication failed",
                    "success" => false,
                ], 400);
            }
        } catch (Throwable $error) {
            report($error);

            return response()->json([
                "error" => $error->getMessage(),
                "message" => "error",
                "success" => false,
            ], 500);
        }
    }

    public function SignOut(Request $request)
    {
        $request->session()->flush();
        return redirect("/");
    }

    public function Default(Request $request)
    {
        if (!$request->session()->get('username')) {
            return redirect()->route('login_page');
        } else {
            return redirect('page/books');
        }
    }

    public function ViewAdminAddEditUsers($user_id, Request $request)
    {
        $data = [
            "title" => "Add User",
            "method" => "post",
            "role" => $request->session()->get('role'),
            "user" => null
        ];

        if ($user_id !== "add") {
            $user = DB::table('users')
                ->where('users.user_id', '=', $user_id)
                ->get();

            $data["title"] = "Edit User";
            $data["method"] = "put";
            $data["user"] = json_encode($user[0]);
        }

        return view('page_add_edit_user', $data);
    }

    public function UserInsert(Request $request)
    {
        $validator = FacadesValidator::make($request->all(), [
            'username' => 'required_without:email|unique:users',
            'role' => 'required',
            'password' => 'required|confirmed|min:5',
        ]);

        if ($validator->fails()) {
            $data = $validator->errors()->messages();
            return response()->json([
                "data" => $data,
                "message" => "error validation",
                "success" => false,
            ], 422);
        }

        try {
            $password = $validator->validated()["password"];
            $user = User::create([
                "user_id" => uniqid("USER_", true),
                "username" => $validator->validated()["username"],
                "role" => $validator->validated()["role"],
                "password" => Hash::make($password),
            ]);

            return response()->json([
                "data" => $user,
                "message" => "successfully created",
                "success" => true,
            ], 201);
        } catch (Throwable $error) {
            report($error);

            return response()->json([
                "error" => $error->getMessage(),
                "message" => "error",
                "success" => false,
            ], 500);
        }
    }

    public function UserUpdate($user_id, Request $request)
    {
        $validator = FacadesValidator::make($request->all(), [
            'username' => 'required_without:email|unique:users,user_id,' . $user_id,
            'role' => 'required',
        ]);

        if ($validator->fails()) {
            $data = $validator->errors()->messages();
            return response()->json([
                "data" => $data,
                "message" => "error validation",
                "success" => false,
            ], 422);
        }

        try {
            $user = User::where('user_id', $user_id)->update([
                "username" => $validator->validated()["username"],
                "role" => $validator->validated()["role"],
            ]);

            return response()->json([
                "data" => $user,
                "message" => "successfully updated",
                "success" => true,
            ], 201);
        } catch (Throwable $error) {
            report($error);

            return response()->json([
                "error" => $error->getMessage(),
                "message" => "error",
                "success" => false,
            ], 500);
        }
    }

    public function UserDelete($user_id)
    {
        try {
            User::where('user_id', $user_id)->update([
                "data_status" => "deleted"
            ]);

            return response()->json([
                "message" => "successfully deleted",
                "success" => true,
            ], 201);
        } catch (Throwable $error) {
            report($error);

            return response()->json([
                "error" => $error->getMessage(),
                "message" => "error",
                "success" => false,
            ], 500);
        }
    }

    public function ViewUser(Request $request)
    {
        $user_id = $request->session()->get('user_id');
        $user = DB::table('users')
            ->where('users.user_id', '=', $user_id)
            ->where('users.data_status', '=', 'active')
            ->get();

        if (count($user) === 0) {
            return abort(404, 'Page not found');
        }

        return view('page_detail_profile', [
            "title" => "Profile",
            "user" => $user[0],
        ]);
    }

    public function ViewSettingUser(Request $request)
    {
        $user_id = $request->session()->get('user_id');
        $user = DB::table('users')
            ->where('users.user_id', '=', $user_id)
            ->where('users.data_status', '=', 'active')
            ->get();

        $user[0]->password = null;

        if (count($user) === 0) {
            return abort(404, 'Page not found');
        }

        return view('page_setting_profile', [
            "title" => "Setting Profile",
            "user" => json_encode($user[0]),
        ]);
    }

    public function UserUpdatePofile(Request $request)
    {
        $user_id = $request->session()->get('user_id');
        $validator = FacadesValidator::make($request->all(), [
            'username' => 'required_without:email|unique:users,user_id,' . $user_id,
            'new_password' => 'required|min:5|confirmed',
            'old_password' => 'required|min:5',
        ]);

        if ($validator->fails()) {
            $data = $validator->errors()->messages();
            return response()->json([
                "data" => $data,
                "message" => "error validation",
                "success" => false,
            ], 422);
        }

        try {
            $user = User::where('user_id', $user_id)->get();
            $user->makeVisible('password')->toArray();

            if (count($user) === 0) {
                return response()->json([
                    "message" => "old password wrong",
                    "success" => false,
                ], 400);
            }

            $oldPassword = $validator->validated()["old_password"];
            $hashedPassword = $user[0]["password"];
            if (Hash::check($oldPassword, $hashedPassword)) {
                $user = User::where('user_id', $user_id)->update([
                    "username" => $validator->validated()["username"],
                    "password" => Hash::make($validator->validated()["new_password"]),
                ]);

                return response()->json([
                    "data" => $user,
                    "message" => "successfully updated",
                    "success" => true,
                ], 201);
            } else {
                return response()->json([
                    "message" => "old password wrong",
                    "success" => false,
                ], 400);
            }
        } catch (Throwable $error) {
            report($error);

            return response()->json([
                "error" => $error->getMessage(),
                "message" => "error",
                "success" => false,
            ], 500);
        }
    }
}
