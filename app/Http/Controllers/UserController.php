<?php

namespace App\Http\Controllers;

use App\User as User;
use Illuminate\Http\Request as Request;
use Illuminate\Http\Response as Response;

class UserController extends Controller
{
    /**
     * get all users
     */
    public function index()
    {
        return response()->json(\App\User::all());
    }

    /** create new user */
    public function create(Request $request)
    {
        $request->validate(
            [
                'name' => 'required',
                'email' => 'required|unique:users',
                'password' => 'required|min:7'
            ]
        );
        $user = new \App\User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = $request->password; #TODO: encrypt password
        if (count(\App\User::where('email', '=', $request->email)->get())) {
            return response()->json(['error' => 'email already being used by another user']);
        }
        if ($user->save()) {
            return response()->json(['message' => 'User account created successfully']);
        }
    }

    /** delete user account */
    public function destroy($id)
    {
        $post = \App\User::find($id);
        if (!$post) {
            return response()->json(['error' => 'cannot find user with specified id'], 400);
        }
        $post->delete();
        return response()->json(['message' => 'User deleted'], 200);
    }

    /** update user information */
    public function update(Request $request, $id)
    {
        $post = \App\User::find($id);
        if (!$post) {
            return response()->json(['error' => 'specified user account not found']);
        } 
        if (!$post->fill($request->all())->save()) {
            return response()->json(['error' => 'Could not update field as required']);
        }
        return response()->json(['message', 'field successfully updated']);
    }

    /** get user details by ID */
    public function show($id)
    {
        $user = \App\User::find($id);
        if (!$user) {
            return response()->json(['error' => 'cannot find user with specified id'], 400);
        }
        return response()->json($user);
    }
}
