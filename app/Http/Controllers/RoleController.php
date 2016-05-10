<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Role;
use App\User;
use App\Permission;
use Redirect;
use Laracasts\Flash\Flash;

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $roles = Role::all();
        return view('role.index', ['roles' => $roles]);
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
        $this->validate($request,[
            'name' => 'required',
            'alias' => 'required|unique:roles'
        ]);
        $role = new Role;
        $role->name = $request->input('name');
        $role->alias = $request->input('alias');
        $role->save();
        \Flash::success('New role created');
        return Redirect::back();
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $role = Role::with(['users', 'permissions'])->findOrFail($id);
        $other_permissions = Permission::all()->diff($role->permissions);
        $other_users = User::all()->diff($role->users);
        return view('role.show', ['role' => $role, 'other_permissions' => $other_permissions, 'other_users' => $other_users]);
    }

    /**
     * Assign a permission to a role
     *
     * @param int $id
     * @param string $permission_alias
     * @return \Illuminate\Http\RedirectResponse
     */
    public function assignPermission($id, $permission_alias)
    {
        Role::findOrFail($id)->assignPermission($permission_alias);
        \Flash::message('Permission assigned to this role');
        return Redirect::back();
    }

    /**
     * Revoke a permission from a role
     *
     * @param int $id
     * @param string $permission_alias
     * @return \Illuminate\Http\RedirectResponse
     */
    public function revokePermission($id, $permission_alias)
    {
        Role::findOrFail($id)->revokePermission($permission_alias);
        \Flash::message('Permission revoked from this role');
        return Redirect::back();
    }

    /**
     * Assign a user to a role
     *
     * @param int $id
     * @param int $user_id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function assignUser($id, $user_id){
        Role::findOrFail($id)->assignUser($user_id);
        \Flash::success('User assigned to this role');
        return Redirect::back();
    }

    /**
     * Revoke a user from a role
     *
     * @param int $id
     * @param int $user_id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function revokeUser($id, $user_id){
        Role::findOrFail($id)->revokeUser($user_id);
        \Flash::success('User revoked from this role');
        return Redirect::back();
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
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
        $this->validate($request,[
            'name' => 'required'
        ]);
        $role = Role::findOrFail($id);
        $role->name = $request->input('name');
        if($role->save()) \Flash::success('Role name updated!');
        return Redirect::back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Role::destroy($id);
        \Flash::success('Role deleted');
        return Redirect::route('super.role.index');
    }
}
