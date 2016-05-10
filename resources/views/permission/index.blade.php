@extends('layouts.lte')
@section('content')
    <div class="row">
        <div class="col-md-6">
            @include('parts.box.start',['title'=>'Existing Permissions'])
            <ul class="permission-list">
                @foreach($permissions as $permission)
                    <li>
                        <a href="{{route('super.permission.show',$permission->id)}}"><strong>{{$permission->name}}</strong></a>
                        -
                        <small><var>{{$permission->alias}}</var></small>
                    </li>
                @endforeach
            </ul>
            <p><em>Deletion is disabled as the alias is used in code</em></p>
            @include('parts.box.end')
        </div>
        <div class="col-md-6">
            @include('parts.box.start',['title'=>'Create New Permission','type'=>'success'])

            {!! Form::open(['route'=>'super.permission.store']) !!}
            <div class="form-group">
                <input type="text" name="name" id="" class="form-control" placeholder="Name"
                       value="{{old('name')}}">
            </div>
            <div class="form-group">
                <input type="text" name="alias" id="" class="form-control" placeholder="Alias (unique)"
                       value="{{old('alias')}}">
            </div>
            <div class="form-group">
                <input type="submit" class="btn btn-primary" value="Add Permission">
            </div>
            {!! Form::close() !!}
            @include('parts.box.end')
        </div>
    </div>
@stop
@section('header')
    <h1 class="title"><i class="fa fa-key"></i>Manage Permissions</h1>
@stop
