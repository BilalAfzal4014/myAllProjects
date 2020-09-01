@extends('layouts.master')

@section('searchBar')
@stop



@section("create")
    <input id="row_id" value="{{$data->row_id}}" type="hidden">
    <input id="email" value="@if(isset($data->email)){{$data->email}}@endif" type="hidden">
@endsection

@section('content')
    <style>
        .notification_btns_div {
            overflow: hidden;
        }

        .notification_btns_div div {
            float: left !important;
            border: none !important;
            padding-left: 0px;
            width: 100% !important;
        }
    </style>
    {{--    @include('partials.left-scroll-bar')--}}

    <div class="dash_chart_head clearfix">
        <div class=" usr_profile_top_outer clearfix" style="padding: 22px 22px;">
            <div class="usr_profile_pic_detail clearfix">
                <div class="usr_profile_pic">
                    <span> <img src="@if(!empty($data->profile_image)){{$data->profile_image}}@else{{asset('assets/images/profile_placeholder.png')}}@endif"
                                alt="#"> </span>
                </div>
                <div class="usr_profile_det">
                    <h3>@if(isset($data->firstname)){{$data->firstname}}@endif @if(isset($data->lastname)){{$data->lastname}}@endif</h3>
                    <ul>
                        <li><a href="#" class="usr_profile_name"></a></li>

                        <li>
                            <a href="#">
                                <img src="{{asset('assets/images/email_icon.png')}}"
                                     alt="#">@if(isset($data->email)){{$data->email}}@endif
                            </a>
                        </li>
                        <li>
                            <label>User Id: @if(isset($data->user_id)){{$data->user_id}} @endif</label>
                        </li>
                        <li>
                            <label>Device Type: @if(isset($data->device_type)){{$data->device_type}}@endif</label>

                        </li>
                    </ul>
                    <div class="notification_btns_div">
                        <div class="track_userdeta_right clearfix">
                            <div class="Ana_track_switch">
                                <strong>
                                    App Notifications
                                    <label class="switch">

                                        @foreach ($data as $keys=>$value)
                                            @if($keys == 'enable_notification')
                                                <input data-key="{{$keys}}"
                                                       type="checkbox" {{$value ==  1 ? 'checked': ''}}>
                                            @endif
                                        @endforeach

                                        <span class="slider round"></span>
                                    </label>
                                </strong>
                            </div>
                        </div>
                    </div>
                    <div class="notification_btns_div">
                        <div class="track_userdeta_right clearfix">
                            <div class="Ana_track_switch">
                                <strong>
                                    Email Notifications
                                    <label class="switch">
                                        @foreach ($data as $keys=>$value)
                                            @if($keys == 'email_notification')
                                                <input data-key="{{$keys}}"
                                                       type="checkbox" {{$value ==  1 ? 'checked': ''}} >
                                            @endif
                                        @endforeach
                                        <span class="slider round"></span>
                                    </label>
                                </strong>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="usr_profile_with_map">
                <div class="usr_profile_phone_sec ">
                    <a href="#" class="phon_no">
                        <img src="{{asset('assets/images/phone_icon.png')}}"
                             alt="#">@if(isset($data->phone_number)){{$data->phone_number}}@endif
                    </a><br/>
                    <a href="javascript:void(0);" class="phon_no">
                        language: @if(isset($data->lang)){{$data->lang}}@endif
                    </a><br/>
                    <a href="javascript:void(0);" class="phon_no">
                        App Name: @if(isset($data->app_name)){{$data->app_name}}@endif
                    </a><br/>
                    <a href="javascript:void(0);" class="phon_no">
                        App Id: @if(isset($data->app_id)){{$data->app_id}}@endif
                    </a>
                </div>
                <div class="usr_profile_map">
                    <label> Last Login: @if(isset($data->last_login)){{$data->last_login}}@endif</label>
                    <label> Most Recent Location </label>
                    <span> <div style="height: 150px;width: 210px" id="userMAp"></div> </span>
                </div>
            </div>

        </div>
        @if (Session::get('MSG'))
            <div class="alert {{ Session::get('MSG')['CLASS'] }}">
                {{Session::get('MSG')['MSG']}}
            </div>
        @endif
        <div class="usr_prof_btm_tab_outer">
            <div class="usr_prof_tab_btns clearfix">
                <ul>
                    <li><a href="#usr_prof_overview" class="active"> Overview </a></li>
                    <li><a href="#usr_prof_engagement"> Engagement </a></li>
                    {{--<li><a href="#import_targeted_clients"> Import Targeted Clients </a></li>--}}
                    <li><a href="#usr_prof_feedback"> User Attribute Data </a></li>
                    <li><a href="#usr_prof_social" style="display: none;"> Social </a></li>
                </ul>
            </div>
            <div class="usr_prof_tab_detail_outer">
                <div class="usr_prof_tab_det usr_prof_overview" id="usr_prof_overview" style="display:block;">
                    <div class="usr_prof_tab_box">
                        <label class="usr_prof_tab_box_title"> <img src="{{asset('assets/images/profile_icon.png')}}"
                                                                    alt="#"> Campaign Coversion Push
                        </label>
                        <div class="usr_prof_tab_box_detlist mCustomScrollbar _mCS_1">
                            <table id="profileInfo">

                            </table>
                        </div>
                    </div>
                    <div class="usr_prof_tab_box">
                        <label class="usr_prof_tab_box_title"> <img src="{{asset('assets/images/app_usage_icon.png')}}"
                                                                    alt="#"> Campaign Coversion Inapp
                        </label>
                        <div class="usr_prof_tab_box_detlist mCustomScrollbar _mCS_1">
                            <table id="appUsage">

                            </table>
                        </div>
                    </div>
                    <div class="usr_prof_tab_box">
                        <label class="usr_prof_tab_box_title"> <img
                                    src="{{asset('assets/images/custome_att_icon.png')}}" alt="#"> User Login</label>
                        <div class="usr_prof_tab_box_detlist mCustomScrollbar _mCS_1">
                            <table id="customAttr">

                            </table>
                        </div>
                    </div>
                    <div class="usr_prof_tab_box">
                        <label class="usr_prof_tab_box_title"> <img src="{{asset('assets/images/app_icon.png')}}"
                                                                    alt="#"> User List
                        </label>
                        <div class="usr_prof_tab_box_detlist mCustomScrollbar _mCS_1">
                            <table id="deviceList">

                            </table>
                        </div>
                    </div>


                    <!--  -->
                </div>
                <div class="usr_prof_tab_det usr_prof_engagement" id="usr_prof_engagement">
                    <div class="usr_prof_tab_box">
                        <label class="usr_prof_tab_box_title"> <img
                                    src="{{asset('assets/images/contact_set_icon.png')}}" alt="#"> Campaign Email Sent
                        </label>
                        <div class="usr_prof_tab_box_detlist mCustomScrollbar _mCS_1">

                            <table id="emailClicks">


                            </table>
                        </div>
                    </div>
                    <div class="usr_prof_tab_box">
                        <label class="usr_prof_tab_box_title"> <img src="{{asset('assets/images/app_icon.png')}}"
                                                                    alt="#"> Campaign Push Sent</label>
                        <div class="usr_prof_tab_box_detlist mCustomScrollbar _mCS_1">
                            <table id="campaignPushClick">

                            </table>
                        </div>
                    </div>
                    <div class="usr_prof_tab_box">
                        <label class="usr_prof_tab_box_title"> <img src="{{asset('assets/images/app_usage_icon.png')}}"
                                                                    alt="#">Campaign Inapp Sent
                        </label>
                        <div class="usr_prof_tab_box_detlist mCustomScrollbar _mCS_1">
                            <table id="inAppStat">

                            </table>
                        </div>
                    </div>

                    <div class="usr_prof_tab_box">
                        <label class="usr_prof_tab_box_title"> <img src="{{asset('assets/images/app_icon.png')}}"
                                                                    alt="#">News Feed Cards
                            Clicked </label>
                        <div class="usr_prof_tab_box_detlist mCustomScrollbar _mCS_1">

                            <table id="newsFeedClicks">

                            </table>
                        </div>
                    </div>

                </div>

                <div class="usr_prof_tab_det usr_prof_feedback" id="usr_prof_feedback">

                    <div class="user_attribute_data_outer" style="width: 100%">
                        <label>User Attribute</label>
                        <div class="usr_prof_tab_box">

                            <table id="userAttributeData" style="width: 100%;">
                                @foreach ($data as $keys=>$value)
                                    <tr>
                                        <td>
                                            {{ $keys }}
                                        </td>
                                        <td>
                                            {{$value}}
                                        </td>
                                    </tr>
                                @endforeach
                            </table>
                        </div>
                    </div>
                    <div>
                        <div class="user_attribute_data_outer" style="width: 33%">
                            <label>User Conversion</label>
                            <div class="usr_prof_tab_box">

                                <table id="userConversionData" style="width: 100%;">

                                </table>
                            </div>
                        </div>
                        <div class="user_attribute_data_outer" style="width: 33%">
                            <label>User Action</label>
                            <div class="usr_prof_tab_box">

                                <table id="userActionData" style="width: 100%;">

                                </table>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
@stop

@section('jsSection')
    <script>
        function initMap() {


            map = new google.maps.Map(document.getElementById('userMAp'), {
                center: {
                    lat: @if(isset($data->latitude)){{$data->latitude}}@else -34.397 @endif,
                    lng: @if(isset($data->longitude)){{$data->longitude}}@else 150.397 @endif},
                zoom: 5
            });
            var geocoder = new google.maps.Geocoder();
            geoMarker = new google.maps.Marker();
            geoMarker.setPosition(map.getCenter());
            geoMarker.setMap(map);
        }
    </script>
    <script src="{{asset('/assets/js/user/userStats.js')}}"></script>
    <script src="https://maps.googleapis.com/maps/api/js?key={{config('engagement.google_map_key')}}&callback=initMap"
            async defer></script>
@stop
