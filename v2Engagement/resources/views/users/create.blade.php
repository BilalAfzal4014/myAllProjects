@extends('layouts.master')
@section('title', '| Add User')
@section('content')
<div class='col-lg-8 col-lg-offset-2'>
    <h1> Add User</h1>
    <hr>
    {{ Form::open(array('url' => 'users')) }}
    <div class="form-group">
        @include ('errors.list')
    </div>
    <div class="form-group">
        {{ Form::label('name', 'Name') }}
        {{ Form::text('name', '', array('class' => 'form-control')) }}
    </div>
    <div class="form-group">
        {{ Form::label('email', 'Email') }}
        {{ Form::email('email', '', array('class' => 'form-control')) }}
    </div>
    <div class='form-group'>
        <h4><label>Assign Permissions</label></h4>
        @foreach ($roles as $role)
            {{ Form::checkbox('roles[]',  $role->id ) }}
            {{ Form::label($role->name, ucwords(strtolower(str_replace('-', ' ',$role->name)))) }}<br>
        @endforeach
    </div>
    <div class="form-group">
        {{ Form::label('password', 'Password') }}<br>
        {{ Form::password('password', array('class' => 'form-control')) }}
    </div>
    <div class="form-group">
        {{ Form::label('password', 'Confirm Password') }}<br>
        {{ Form::password('password_confirmation', array('class' => 'form-control')) }}
    </div>
    {{ Form::submit('Save', array('class' => 'btn btn-primary','style'=>'background: #2a8689')) }}
    {{ Form::close() }}
</div>

<style>
    input[type="checkbox"] {

        -webkit-appearance: checkbox !important;
        height: 16px;
        width: 16px;
        appearance: checkbox !important;
        -moz-appearance: checkbox;
        border-radius: 4px;

    }
</style>
@endsection
