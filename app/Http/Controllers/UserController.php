<?php

namespace App\Http\Controllers;

use App\Role;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\User;
use Illuminate\Support\Facades\Input;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $users = User::all();
        return view('user.index', ['users' => $users]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
            'new-email' => 'email|required|unique:users,email',
            'new-pass' => 'required|min:6'
        ], [
            'name.required' => 'You must enter a name',
            'new-email.email' => 'You must enter a valid email address',
            'new-email.required' => 'You must enter a valid email address',
            'new-email.unique' => 'This email is already registered',
            'new-pass.required' => 'Password is required and should be at least 6 characters long',
            'new-pass.min' => 'Password should be at least 6 characters long'
        ]);

        User::create([
            'name' => Input::get('name'),
            'email' => Input::get('new-email'),
            'password' => bcrypt(Input::get('new-pass'))
        ]);

        \Flash::success('New user created successfully!');

        return \Redirect::route('super.user.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $user = User::find($id);
        $other_roles = Role::all()->diff($user->roles);
        return view('user.show', compact('user','other_roles'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $user = User::find($id);
        $this->validate($request, [
            'name' => 'required',
            'new-email' => 'email|required|unique:users,email,' . $user->id,
            'new-pass' => 'min:6'
        ], [
            'name.required' => 'You must enter a name',
            'new-email.email' => 'You must enter a valid email address',
            'new-email.required' => 'You must enter a valid email address',
            'new-email.unique' => 'This email is already registered',
            'new-pass.min' => 'Password should be at least 6 characters long'
        ]);
        $user->name = $request->get('name');
        $user->email = $request->get('new-email');
        if (trim($request->get('new-pass')) != '') {
            $user->password = bcrypt($request->get('new-pass'));
        }
        $user->save();
        \Flash::success('Updated!');
        return \Redirect::back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if($id == 1){
            \Flash::error('Super Administrator (first user) cannot be deleted');
            return \Redirect::back();
        }
        User::destroy($id);
        \Flash::success('User Deleted!');
        return \Redirect::route('super.user.index');
    }

    public function assignRole($id,$role_alias){
        User::find($id)->assignRole($role_alias);
        \Flash::success('Role assigned!');
        return \Redirect::back();
    }

    public function revokeRole($id,$role_alias){
        User::find($id)->revokeRole($role_alias);
        \Flash::success('Role revoked!');
        return \Redirect::back();
    }
}
