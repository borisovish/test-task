@extends('layouts.lte')
@section('content')
    <div class="row">
        <div class="col-md-6">
            <div class="box box-success">
                <div class="box-header with-border"><i class="fa fa-green fa-suitcase"></i>

                    <h3 class="box-title">Roles assigned to
                        <strong>{{$user->name}}</strong></h3>
                </div>
                <div class="box-body">
                    <ul class="role-list">
                        @foreach($user->roles as $role)
                            <li>
                                <a href="{{route('super.role.show',$role->id)}}"><strong>{{$role->name}}</strong></a>
                                - -
                                <a href="{{route('user.role.revoke',[$user->id,$role->alias])}}"
                                   data-method="put"
                                   data-token="{{csrf_token()}}">Revoke {{$role->name}} from this user</a>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
            <div class="box box-danger">
                <div class="box-header with-border"><i class="fa fa-green fa-suitcase"></i>

                    <h3 class="box-title">Roles <em>not</em> assigned to
                        <strong>{{$user->name}}</strong></h3>
                </div>
                <div class="box-body">
                    <ul class="role-list">
                        @foreach($other_roles as $role)
                            <li>
                                <a href="{{route('super.role.show',$role->id)}}"><strong>{{$role->name}}</strong></a>
                                - -
                                <a href="{{route('user.role.assign',[$user->id,$role->alias])}}"
                                   data-method="put"
                                   data-token="{{csrf_token()}}">Assign {{$role->name}} to this user</a>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="box box-success">
                <div class="box-header with-border"><i class="fa fa-green fa-suitcase"></i>

                    <h3 class="box-title">Permissions assigned to
                        <strong>{{$user->name}}</strong></h3>
                </div>
                <div class="box-body">
                    <ul class="role-list">
                        @foreach($user->permissions() as $permission)
                            <li>
                                <a href="{{route('super.permission.show',$permission->id)}}"><strong>{{$permission->name}}</strong></a>
                            </li>
                        @endforeach
                    </ul>
                    <em>Permissions can't be assigned directly to a user. In order to change a user's permission. Change user's roles and/or change permissions assosicated with different roles</em>
                </div>
            </div>
        </div>
    </div>

    <form id="edit-user-info" class="white-popup-block mfp-hide" action="{{route('super.user.update',$user->id)}}"
          method="post">
        {{csrf_field()}}
        {{method_field("PUT")}}
        <h4>Edit User Info</h4>

        <div class="form-group">
            <label for="name">Name</label>
            <input class="first-input form-control" type="text" name="name" id="" value="{{$user->name}}">
        </div>
        <div class="form-group">
            <label for="name">Email</label>
            <input class="first-input form-control" type="text" name="new-email" id="" value="{{$user->email}}">
        </div>
        <div class="form-group">
            <label for="name">Password <small>(Leave it blank to keep previous password)</small></label>
            <input class="first-input form-control" type="text" name="new-pass" id="" value="">
        </div>
        <input type="submit" class="btn btn-primary" value="Update">
    </form>
@stop
@section('header')
    <h1 class="title">Manage: <strong>{{$user->name}}</strong>
        <small>
            <a class="popup-with-form" href="#edit-user-info"><i class="fa fa-pencil"></i>Edit Info</a> <a
                    href="{{route('super.user.destroy',$user->id)}}" data-token="{{csrf_token()}}"
                    data-method="DELETE" data-confirm="Are You Sure?"><i class="fa fa-btn fa-trash"></i>Delete
                {{$user->name}}</a>
        </small>
    </h1>
@stop