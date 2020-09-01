@extends('layouts.master')
@section('title', '| Edit Role')
@section('content')

<div class='col-lg-8 col-lg-offset-2'>
    <h1><i class='fa fa-key'></i> Edit Role: {{$role->name}}</h1>
    <hr>
    {{ Form::model($role, array('route' => array('roles.update', $role->id), 'method' => 'PUT')) }}
    <div class="form-group">
        @include ('errors.list')
    </div>
    <div class="form-group">
        {{ Form::label('name', 'Role Name') }}
        {{ Form::text('name', null, array('class' => 'form-control')) }}
    </div>
    @if(!$permissions->isEmpty())
        <div class='form-group'>
            <h4><label>Assign Permissions</label></h4>
            @foreach ($permissions as $permission)
                {{Form::checkbox('permissions[]',  $permission->id, $role->permissions ) }}
                {{Form::label($permission->name, ucfirst($permission->name)) }}<br>
            @endforeach
        </div>
    @endif
    {{ Form::submit('Edit', array('class' => 'btn btn-primary')) }}
    {{ Form::close() }}
</div>

<style>
    input[type="checkbox"]{
      -webkit-appearance: checkbox !important;
    }
</style>
@endsection
