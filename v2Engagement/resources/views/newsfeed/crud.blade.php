@extends('layouts.master')

@section('searchBar')

@stop

@section('create')

    {{--hidden field start--}}
    <input type="hidden" name="newsfeedId" id="newsfeedId" value="@if(isset($news)){{$news->id}}@endif">
    <input class="form-control" value="{{ csrf_token() }}" id="csr_token" type="hidden" name="csr_token" size="20">
    <input class="form-control" value="{{ route('saveNewsfeed') }}" id="save_url" type="hidden" name="" size="20">
    <input class="form-control" value="{{ asset('assets/images/ureka_logo2.png') }}" id="actualImg" type="hidden"
           name="" size="20">
    {{--end hidden fields--}}
    <div class="db_content_holder step-app">

        <div class="tp_BreadCrumb_list_sec clearfix">

            <label class="sec_tp_title"> News Feed &gt; <span id="newsNameId"> Card Name <span></label>

        </div>

        <div class="breadcrumb_steps_outer">
            <ul class="step-steps">
                <li class="active"><a href="#COMPOSE"> 1. Compose &amp; Schedule </a></li>
                <li><a href="#DELIVERY"> 2. Delivery </a></li>
                <li><a href="#CONFIRM"> 3. Confirm </a></li>
            </ul>
        </div>

        <div class="breadcrumb_step_det_outer step-content">
            @include('newsfeed.newsfeedStep1')
            @include('newsfeed.newsfeesStep2')
            @include('newsfeed.newsfeedStep3')
            <div class="step-footer save_next_sec ">
                <button data-direction="prev" class="step-btn" style="display: none;">Back</button>
                <button data-direction="next" class="step-btn">Next</button>
                <button data-direction="launch_btn" class="step-btn launch_btn">Launch Newsfeed</button>
                <button @if(isset($news) and $news->status == 'active')style="display: none"
                        @endif data-direction="draft" class="step-btn save_as_draft">Save As Draft
                </button>


                <div class="reachable_usr_app_message_otr">
                    <div class="reachable_user">
                        <p> Reachable Users
                            <span>
                                        <i class="noticification_icon"></i><br><span id="segemntCount">All</span>
                                    </span>
                        </p>
                    </div>
                    @if(isset($news))
                        <div class="app_message">
                            <p style="margin: 0 0 0px;">Total Clicks</p>
                            <span>{{ $totalClicks }}</span>
                        </div>

                        <div class="app_message">
                            <p style="margin: 0 0 0px;">Total Views</p>
                            <span>{{ $totalViews }}</span>
                        </div>

                        <div class="app_message hide">
                            <p style="margin: 0 0 0px;">Clickthrough Rate </p>
                            <span>{{ $clickthroughRate }} %</span>
                        </div>
                    @endif
                </div>

            </div>

            <div class="" style="padding:5px; background:#fff;">
            </div>

        </div>

    </div>
    <!-- Button trigger modal -->
    <style>
        #galleryUpload {
            width: 82.7%;
            line-height: 30px;
            color: #b7b7b7;
            padding: 6px 10px;
            display: inline;
            font-size: 16px;
            font-weight: 500;
            letter-spacing: 1px;
            border: 1px solid #b7b7b7;
            border-radius: 4px;
        }

        .modal-body {
            padding: 0px !important;
        }

        /*gallery datatable*/

        #gallery_filter {
            background: #2a8689;
            border-radius: 7px;
            color: #ffffff;
            font-size: 13px;
            font-weight: 400;
            line-height: 24px;
            padding: 10px 10px 7px 8px;
            margin: 1px 0px;
            letter-spacing: 0.5px;
        }

        #gallery_filter input {
            color: #000 !important;
            padding: 1px 5px;
            border-radius: 5px;
        }

        #gallery_length label {
            position: relative;
            bottom: -10px;
            left: 1px;
        }

        #gallery_length label select {
            border: 1px solid #b7b7b7;
            padding: 0px 10px 0px 7px;
            border-radius: 6px;
        }

        .table-bordered > tbody > tr > td {
            vertical-align: middle;
        }

        .sorting_disabled, .sorting {
            text-align: center;
        }

        .step-footer .reachable_usr_app_message_otr div {

            vertical-align: top;
        }
    </style>

    @include('galleryPopup')
@stop

@section('jsSection')

    <script src="{{asset('/assets/js/newsFeed/newsFeed_Crud.js')}}"></script>
    {{--    <script src="{{asset('/assets/js/newsFeed/newsFeed.js')}}"></script>--}}
@stop