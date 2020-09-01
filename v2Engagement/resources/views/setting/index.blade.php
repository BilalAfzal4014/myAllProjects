@extends('layouts.master')

@section('title', '| Users')

@section('content')
    <!-- @include('partials.left-scroll-bar') -->

    <style>
        th {
            text-align: center;
        }
    </style>
      @if (Session::has('message'))
                            <div class="alert alert-success">{{ Session::get('message') }}</div>
      @endif

    <div class="col-lg-10 col-lg-offset-1">
        <h1>
         Setting
        </h1>
        <hr>
        <div class="table-responsive">
            <table class="table table-bordered table-striped">
                <thead>
                <tr>
                    <th>Name</th>
                    <th>Operations</th>
                </tr>
                </thead>
                <tbody>
                @foreach ($settings as $setting)
                    <tr>
                        <td>{{ $setting->setting_type }}</td>
                        <td>
                            <a href="{{ route('setting.edit', $setting->id) }}" class="btn btn-info">Edit</a>
                       </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
