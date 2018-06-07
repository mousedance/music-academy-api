<?php

namespace App\Http\Controllers;

use App\User as User;
use Illuminate\Support\Facades\Auth as Auth;
use Illuminate\Http\Request as Request;
use Illuminate\Http\Response as Response;

class UserController extends Controller
{
    /** create user account in db, log them in */
    public function signUp()
    {
    }

    /** sign in user */
    public function signIn(Request $request)
    {
        if (Auth::attempt(array('email' => $request->email, 'password' => $request->password))) {
            # create token for user
        } else {
        }
    }

    public function signOut(Request $request)
    {
    }

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
            $request,
            [
                'name' => 'required|min:4',
                'email' => 'required|unique:users|email',
                'password' => 'required|min:7' # TODO: add regular expression to enforce some type of user password rules
            ]
        );
        $user = new \App\User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = bcrypt($request->password); #TODO: encrypt password
        if (count(\App\User::where('email', '=', $request->email)->get())) {
            return response()->json(['error' => 'email already being used by another user']);
        }
        if (!$user->save()) {
            return response()->json(['message' => 'Could not create account at the moment. Please try again later']);
        }
        return response()->json(['message' => 'User account created successfully']);
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
 