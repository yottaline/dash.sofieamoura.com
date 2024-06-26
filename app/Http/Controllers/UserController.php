<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index()
    {
        return view('contents.users.index');
    }

    function load(Request $request)
    {
        $param = $request->q ? ['q' => $request->q] : [];
        $limit = $request->limit;
        $lastId = $request->last_id;

        echo json_encode(User::fetch(0, $param, null, $limit, $lastId));
    }

    function submit(Request $request)
    {
        $id = intval($request->id);

        // $emailCheck = User::fetch(0, [['id', '!=', $id], ['user_email', '=', $request->email]]);
        // if ($emailCheck) {
        //     echo json_encode(['status' => false, 'message' => __('The email address is already registered'),]);
        //     return;
        // }

        $param = [
            'user_name'    => $request->name,
            'user_email'   => $request->email,
            'user_password' => Hash::make($request->password)
        ];

        if (!$id) {
            $param['user_code'] = uniqidReal(8);
            $param['user_created'] = Carbon::now();
        } else {
            $param['user_modified'] = Carbon::now();
        }

        $result = User::submit($param, $id);
        echo json_encode([
            'status' => boolval($result),
            'data' => $result ? User::fetch($id) : [],
        ]);
    }
}
