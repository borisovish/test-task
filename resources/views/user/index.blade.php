@extends('layouts.lte')
@section('content')
    <div class="row">
        <div class="col-md-6">
            <div class="box box-info">
                <div class="box-header with-border"><h3 class="box-title">Existing Users</h3></div>
                <div class="box-body">
                    <ul class="user-list">
                        @foreach($users as $user)
                            <li>
                                <a href="{{route('super.user.show',$user->id)}}"><strong>{{$user->name}}</strong></a>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="box box-success">
                <div class="box-header with-border"><h3 class="box-title">Create New User</h3></div>
                <div class="box-body">
                    {!! Form::open(['route'=>'super.user.store']) !!}
                    <div class="form-group">
                        <input type="text" name="name" id="" class="form-control" placeholder="Name"
                               value="{{old('name')}}"><br>
                        <input type="text" name="new-email" id="" class="form-control" placeholder="Email"
                               value="{{old('new-email')}}"><br>
                        <input type="text" name="new-pass" id="" class="form-control" placeholder="Password"
                               value="{{old('new-pass')}}"><br>
                        <input type="submit" class="btn btn-primary" value="Add User">
                    </div>
                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>
@stop
@section('header')
    <h1 class="title"><i class="fa fa-users"></i>Manage Users</h1>
@stop