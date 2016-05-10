<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Permission;
use App\Role;
use Redirect;

class PermissionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $permissions = Permission::all();
        return view('permission.index', ['permissions' => $permissions]);
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
            'alias' => 'required|alpha|unique:permissions'
        ]);
        $permission = new Permission;
        $permission->name = $request->input('name');
        $permission->alias = $request->input('alias');
        if($permission->save()) \Flash::success('New Permission Added!');
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
        $permission = Permission::with(['roles'])->findOrFail($id);
        $other_roles = Role::all()->diff($permission->roles);
        return view('permission.show', ['permission' => $permission, 'other_roles' => $other_roles]);
    }

    /**
     * Assign a permission to a role
     *
     * @param int $id
     * @param string $role_alias
     * @return \Illuminate\Http\RedirectResponse
     */
    public function assignRole($id, $role_alias)
    {
        Permission::findOrFail($id)->assignRole($role_alias);
        \Flash::success('Role assigned!');
        return Redirect::back();
    }

    /**
     * Revoke a permission from a role
     *
     * @param int $id
     * @param string $role_alias
     * @return \Illuminate\Http\RedirectResponse
     */
    public function revokeRole($id, $role_alias)
    {
        Permission::findOrFail($id)->revokeRole($role_alias);
        \Flash::success('Role revoked');
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
        $this->validate($request,[
            'name' => 'required'
        ]);
        $permission = Permission::findOrFail($id);
        $permission->name = $request->input('name');
        $permission->save();
        \Flash::success('Permission name updated');
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
        //
    }
}
