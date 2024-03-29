@extends('layouts.master')
@section('title', '| Create Permission')
@section('content')

<div class='col-lg-8 col-lg-offset-2'>
    <h1><i class='fa fa-key'></i> Add Permission</h1>
    <br>
    {{ Form::open(array('url' => 'permissions')) }}
    <div class="form-group">
        @include ('errors.list')
    </div>
    <div class="form-group">
        {{ Form::label('name', 'Name') }}
        {{ Form::text('name', '', array('class' => 'form-control')) }}
    </div>
    <br>
    @if(!$roles->isEmpty())
        <div class='form-group'>
            <h4>Assign Permission to Roles</h4>
            @foreach ($roles as $role)
                {{ Form::checkbox('roles[]',  $role->id ) }}
                {{ Form::label($role->name, ucfirst($role->name)) }}<br>
            @endforeach
        </div>
    @endif
    <br>
    {{ Form::submit('Add', array('class' => 'btn btn-primary')) }}
    {{ Form::close() }}
</div>

<style>
    input[type="checkbox"]{
      -webkit-appearance: checkbox !important;
    }
</style>
@endsection
