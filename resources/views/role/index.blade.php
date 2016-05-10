@extends('layouts.lte')
@section('content')
    <div class="row">
        <div class="col-md-6">
            <div class="box box-info">
                <div class="box-header with-border"><h3 class="box-title">Existing Roles</h3></div>
                <div class="box-body">
                    <ul class="role-list">
                        @foreach($roles as $role)
                            <li>
                                <a href="{{route('super.role.show',$role->id)}}"><strong>{{$role->name}}</strong></a>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="box box-success">
                <div class="box-header with-border"><h3 class="box-title">Create New Role</h3></div>
                <div class="box-body">
                    {!! Form::open(['route'=>'super.role.store']) !!}
                    <div class="form-group">
                        <input type="text" name="name" id="" class="form-control" placeholder="Name" value="{{old('name')}}"><br>
                        <input type="text" name="alias" id="" class="form-control" placeholder="Alias (unique)" value="{{old('alias')}}"><br>
                        <input type="submit" class="btn btn-primary" value="Add Role">
                    </div>
                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>
@stop
@section('header')
    <h1 class="title"><i class="fa fa-suitcase"></i>Manage Roles</h1>
@stop
