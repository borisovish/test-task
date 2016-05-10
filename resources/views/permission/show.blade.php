@extends('layouts.lte')
@section('content')
    <div class="row">
        <div class="col-md-8">
            <div class="box box-success">
                <div class="box-header with-border"><i class="fa fa-green fa-suitcase"></i>

                    <h3 class="box-title">Roles with
                        <strong>{{$permission->name}}</strong> Permission</h3>
                </div>
                <div class="box-body">
                    <ul class="role-list">
                        @foreach($permission->roles as $role)
                            <li>
                                <a href="{{route('super.role.show',$role->id)}}"><strong>{{$role->name}}</strong></a>
                                - -
                                <a href="{{route('permission.role.revoke',[$permission->id,$role->alias])}}"
                                   data-method="put"
                                   data-token="{{csrf_token()}}">Revoke {{$role->name}} from this Permission</a>

                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
            <div class="box box-danger">
                <div class="box-header with-border"><i class="fa fa-red fa-suitcase"></i>

                    <h3 class="box-title">Roles <em>Without</em>
                        <strong>{{$permission->name}}</strong> Permission</h3>
                </div>
                <div class="box-body">
                    <ul class="role-list">
                        @foreach($other_roles as $role)
                            <li>
                                <a href="{{route('super.role.show',$role->id)}}"><strong>{{$role->name}}</strong></a>
                                - -
                                <a href="{{route('permission.role.assign',[$permission->id,$role->alias])}}"
                                   data-method="put"
                                   data-token="{{csrf_token()}}">Assign {{$role->name}} to this Permission</a>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <form id="edit-permission-name" class="white-popup-block mfp-hide"
          action="{{route('super.permission.update',$permission->id)}}"
          method="post">
        {{csrf_field()}}
        {{method_field("PUT")}}
        <h4>Edit Permission Name</h4>

        <div class="form-group">
            <input class="first-input form-control" type="text" name="name" id="" value="{{$permission->name}}">
        </div>
        <input type="submit" class="btn btn-primary" value="Update">
    </form>

@stop

@section('header')

    <h1 class="title"><i class="fa fa-btn fa-key"></i>Permission: {{$permission->name}}
        <small><em>Alias: {{$permission->alias}}</em> <a class="popup-with-form" href="#edit-permission-name"><i class="fa fa-btn fa-pencil"></i>Edit
                Permission Name</a></small>
    </h1>

@stop