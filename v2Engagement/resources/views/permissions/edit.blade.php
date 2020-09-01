@extends('layouts.master')
@section('title', '| Edit Permission')
@section('content')

<div class='col-lg-8 col-lg-offset-2'>
    <h1><i class='fa fa-key'></i> Edit {{$permission->name}}</h1>
    <br>
    {{ Form::model($permission, array('route' => array('permissions.update', $permission->id), 'method' => 'PUT')) }}{{-- Form model binding to automatically populate our fields with permission data --}}
    <div class="form-group">
        @include ('errors.list')
    </div>
    <div class="form-group">
        {{ Form::label('name', 'Permission Name') }}
        {{ Form::text('name', null, array('class' => 'form-control')) }}
    </div>
    <br>
    {{ Form::submit('Edit', array('class' => 'btn btn-primary')) }}
    {{ Form::close() }}
</div>

<style>
    input[type="checkbox"]{
      -webkit-appearance: checkbox !important;
    }
</style>
@endsection
