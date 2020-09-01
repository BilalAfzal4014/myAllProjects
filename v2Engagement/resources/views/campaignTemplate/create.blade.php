@extends('layouts.master')
@section('title', '| Add User')
@section('content')
    <div class='col-lg-8 col-lg-offset-2'>
        @if (Session::has('flash_message'))
            <div class="alert alert-info">{{ Session::get('flash_message') }}</div>
        @endif
        <br>
        @if($newsTemplate->id!='')
            <h1><i class='fa fa-user-plus'></i> Edit News Campaign Template</h1>
        @else
            <h1><i class='fa fa-user-plus'></i> Add News Campaign Template</h1>
        @endif
        <hr>
        {{ Form::open(array('url' => 'saveCampaignTemplate','enctype'=>"multipart/form-data")) }}
        <input type="hidden" id="userid" name="userid" value="{{$newsTemplate->id}}"/>
        <div class="form-group">
            @include ('errors.list')
        </div>
        <div class="form-group">
            {{ Form::label('title', 'Title') }}
            {{ Form::text('title', $newsTemplate->title, array('class' => 'form-control','required')) }}
        </div>
        <div class="form-group">
            {{ Form::label('type', 'Type') }}
            {{ Form::select('type',$type,$newsTemplate->type,array('class'=>'form-control','required')) }}
        </div>
        <div class="form-group">
            {{ Form::label('content', 'Content') }}
            {{ Form::textarea('content',$newsTemplate->content, array('class' => 'form-control','required','id'=>'content')) }}
        </div>
        <div class="form-group">
            {{ Form::label('thumbnail', 'Thumbnail') }}
            {{ Form::file('image') }}
        </div>
        {{ Form::submit('Save', array('class' => 'btn btn-primary','style'=>'background: #2a8689')) }}
        {{ Form::close() }}
    </div>
    <style>
        input[type="checkbox"] {
            -webkit-appearance: checkbox !important;
        }
    </style>
@endsection
@section('jsSection')
    <script src="//cdn.ckeditor.com/4.10.0/full/ckeditor.js"></script>
    <script type="text/javascript">
        $('#demo').addClass('auto_height');
        // instance, using default configuration.
        CKEDITOR.editorConfig = function (config) {
            config.language = 'es';
            config.uiColor = '#F7B42C';
            config.height = 300;
            config.toolbarCanCollapse = true;

        };
        CKEDITOR.replace('content');
    </script>

@stop