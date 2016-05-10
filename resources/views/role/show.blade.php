@extends('layouts.lte')
@section('content')
    <div class="row">
        <div class="col-md-6">
            <div class="box box-success">
                <div class="box-header with-border"><i class="fa fa-green fa-users"></i>

                    <h3 class="box-title">Users with
                        <strong>{{$role->name}}</strong> role
                    </h3>
                </div>
                <div class="box-body">
                    <ul class="user-list">
                        @foreach($role->users as $user)
                            <li>
                                <a href="{{route('super.user.show',$user->id)}}"><strong>{{$user->name}}</strong></a>
                                - -
                                <a href="{{route('role.user.revoke',[$role->id,$user->id])}}" data-method="put"
                                   data-token="{{csrf_token()}}">Revoke from {{$role->name}}</a>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
            <div class="box box-danger">
                <div class="box-header with-border"><i class="fa fa-red fa-users"></i>

                    <h3 class="box-title">Users <em>without</em>
                        <strong>{{$role->name}}</strong> role</h3>
                </div>
                <div class="box-body">
                    <ul class="user-list">
                        @foreach($other_users as $user)
                            <li>
                                <a href="{{route('super.user.show',$user->id)}}"><strong>{{$user->name}}</strong></a>
                                - -
                                <a href="{{route('role.user.assign',[$role->id,$user->id])}}" data-method="put"
                                   data-token="{{csrf_token()}}">Assign to {{$role->name}}</a>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="box box-success">
                <div class="box-header with-border"><i class="fa fa-green fa-key"></i><h3 class="box-title">Permissions assigned to
                        <strong>{{$role->name}}</strong></h3></div>
                <div class="box-body">
                    <ul class="permission-list">
                        @foreach($role->permissions as $permission)
                            <li>
                                <a href="{{route('super.permission.show',$permission->id)}}"><strong>{{$permission->name}}</strong></a>
                                - -
                                <a href="{{route('role.permission.revoke',[$role->id,$permission->alias])}}"
                                   data-method="put"
                                   data-token="{{csrf_token()}}">Revoke from {{$role->name}}</a>

                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
            <div class="box box-danger">
                <div class="box-header with-border"><i class="fa fa-red fa-key"></i><h3 class="box-title">Permissions
                        <em>Not</em>
                        assigned to
                        <strong>{{$role->name}}</strong></h3></div>
                <div class="box-body">
                    <ul class="permission-list">
                        @foreach($other_permissions as $permission)
                            <li>
                                <a href="{{route('super.permission.show',$permission->id)}}"><strong>{{$permission->name}}</strong></a>
                                - -
                                <a href="{{route('role.permission.assign',[$role->id,$permission->alias])}}"
                                   data-method="put"
                                   data-token="{{csrf_token()}}">Assign to {{$role->name}}</a>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </div>


    <form id="edit-role-name" class="white-popup-block mfp-hide" action="{{route('super.role.update',$role->id)}}"
          method="post">
        {{csrf_field()}}
        {{method_field("PUT")}}
        <h4>Edit Role Name</h4>

        <div class="form-group">
            <input class="first-input form-control" type="text" name="name" id="" value="{{$role->name}}">
        </div>
        <input type="submit" class="btn btn-primary" value="Update">
    </form>

@stop
@section('header')

    <h1 class="title"><i class="fa fa-suitcase"></i>Role: {{$role->name}}
        <small><a class="popup-with-form" href="#edit-role-name"><i class="fa fa-pencil"></i>Edit
                Role Name</a> <a
                    href="{{route('super.role.destroy',$role->id)}}" data-token="{{csrf_token()}}"
                    data-method="DELETE" data-confirm="Are You Sure?"><i class="fa fa-trash"></i>Delete
                Role</a></small>
    </h1>

@stop