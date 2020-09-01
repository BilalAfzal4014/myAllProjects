@extends('layouts.master')
@section('title', '| Add Role')
@section('content')

<div class='col-lg-8 col-lg-offset-2'>
    <h1><i class='fa fa-key'></i> Add Role</h1>
    <hr>
    {{ Form::open(array('url' => 'roles')) }}
    <div class="form-group">
        @include ('errors.list')
    </div>
    <div class="form-group">
        {{ Form::label('name', 'Name') }}
        {{ Form::text('name', null, array('class' => 'form-control')) }}
    </div>
    @if(!$permissions->isEmpty())
        <div class='form-group'>
            <h4><label>Assign Permissions</label></h4>
            @foreach ($permissions as $permission)
                {{ Form::checkbox('permissions[]',  $permission->id ) }}
                {{ Form::label($permission->name, ucfirst($permission->name)) }}<br>
            @endforeach
        </div>
    @endif
    {{ Form::submit('Add', array('class' => 'btn btn-primary')) }}
    {{ Form::close() }}
</div>

<style>
    input[type="checkbox"]{
      -webkit-appearance: checkbox !important;
    }
</style>
@endsection
