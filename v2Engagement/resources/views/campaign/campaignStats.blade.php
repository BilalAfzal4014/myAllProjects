@extends('layouts.master')

@section('searchBar')
@stop

@section('create')
@stop

@section('content')
    <link href="{{asset('/assets/css/inAppStyles.css')}}" rel="stylesheet" type="text/css">
    <link href="{{ asset('build/css/custom.css') }}" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="{{asset('css/devices.min.css')}}"/>
    <style>
        .trackingAction {
            cursor: pointer;
        }

        #importData {
            width: auto;
        }

        body {
            background: #fff !important;
        }

        .wpr_content_holder {
            overflow: hidden;
        }

        .db_content_listing_holder {
            max-height: none !important;
        }

        .list_table_header table.dataTable thead th {
            font-size: 15px;
        }

        .file_field {
            position: relative;
        }

        .file_field input {
            padding: 0;
        }

        .file_field span {
            position: absolute;
            top: 0;
            left: 0;
            padding: 6px 22px;
            pointer-events: none;
        }

        .file_field:hover span {
            background-color: #286090;
            border-color: #286090;
        }

        .loader_image {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.4);
            font-size: 40px;
            color: #fff;
            z-index: 999;
            border-radius: 3px;
        }

        .loader_image i {
            position: absolute;
            top: 50%;
            left: 50%;
        }

        label.error {
            color: #ff0000;
            font-weight: 400;
        }

        input.error {
            border-color: #ff0000 !important;
        }

        .dl-info {
            background: #f1f1f1;
            overflow: hidden;
            padding: 7px 10px;
            margin: 0;
        }

        .dl-info dt {
            background: #fff;
            font-weight: 700;
            float: left;
            clear: left;
            width: 180px;
            padding: 7px;
            margin: 0 0 7px;
        }

        .dl-info dd {
            float: left;
            clear: right;
            padding: 7px 10px;
        }

        #campaignTrackingTable td:first-child {
            text-align: left;
            padding: 13px 20px !important;
        }

        table.dataTable tbody tr {
            background-color: #ffffff;
            height: 49px !important;
        }

        table.dataTable thead th, table.dataTable thead td {
            border-bottom: 1px solid #c0c0c0 !important;
        }

        table.dataTable.no-footer {
            border-bottom: 1px solid white;
        }

        #campaignTrackingTable td:hover .nf_seg_name_detail {
            display: block;
        }

        .nf_seg_name_detail hr {
            margin: 5px 0;
        }

        .nf_seg_name_detail {
            width: 480px;
            padding: 10px 0 0;
        }

        #campaignTrackingTable_filter {
            width: 100%;
            padding-right: 14px;
        }

        #campaignTrackingTable_filter label,
        #campaignActionTrigger_filter label {
            display: block;
            font-size: 0;
        }

        #campaignTrackingTable_filter input,
        #campaignActionTrigger_filter input {
            width: 100%;
            background: #f0f0f0;
            display: block;
            float: none;
            height: 31px;
            padding: 0 15px;
            font-size: 16px;
            border-radius: 30px;
            color: #b2b2b2;
        }

        #campaignActionTrigger_filter input::-webkit-input-placeholder,
        #campaignTrackingTable_filter input::-webkit-input-placeholder { /* Chrome/Opera/Safari */
            color: #b2b2b2;
        }

        #campaignActionTrigger_filter input::-moz-placeholder,
        #campaignTrackingTable_filter input::-moz-placeholder { /* Firefox 19+ */
            color: #b2b2b2;
        }

        #campaignActionTrigger_filter input:-ms-input-placeholder,
        #campaignTrackingTable_filter input:-ms-input-placeholder { /* IE 10+ */
            color: #b2b2b2;
        }

        #campaignActionTrigger_filter input:-moz-placeholder,
        #campaignTrackingTable_filter input:-moz-placeholder { /* Firefox 18- */
            color: #b2b2b2;
        }

        #campaignActionTrigger_filter {
            width: 100%;
            float: none;
        }

        #campaignTrackingTable td {
            position: relative;
        }

        .nf_seg_name_detail {
            left: 100%;
            top: 10px !important;
        }

        .nf_seg_name_detail span {
            float: left;
            width: 70%;
            vertical-align: top;
            margin: -3px 0 0 10px;
        }

        .nf_seg_name_detail label {
            float: left;
        }

        .lst_tbl_drop_outer_Tracking ul {
            width: 115px;
            position: absolute;
            top: 39px;
            left: -33px;
            background: #eaeaea;
            box-shadow: 0 0 6px -2px #000;
            z-index: 99999;
            display: none;
        }

        .lst_tbl_drop_outer_Tracking ul a {
            padding: 3px 0;
        }

        .lst_tbl_drop_outer_Tracking span {
            display: block;
        }

        .lst_tbl_drop_outer_Tracking span:after {
            margin-top: -3px;
            position: absolute;
            content: '';
            top: 50%;
            right: 5px;
            vertical-align: middle;
            border-top: 5px solid #2a8689;
            border-right: 5px solid transparent;
            border-left: 5px solid transparent;
        }

        .bordered_div {
            background: #fff;
            padding: 0 20px;
        }

        .bordered_div.last {
            background: #fff;
            padding-bottom: 60px;
        }

        .bordered_div_holder {
            overflow: hidden;
        }

        .container.custom_container h3.heading {
            margin: 0;
            padding: 10px 20px !important;
            background: #fff;
        }

        .custom_container .row {
            margin-bottom: 10px;
        }

        #campaignTrackingTable_filter label,
        #campaignActionTrigger_filter label {
            margin: 0 0 13px;
        }

        .custom_container table th {
            border-top: 1px solid #e4e4e4;
        }

        .custom_container .dataTables_info {
            padding-left: 21px;
        }

        .custom_container .db_list_left_btm,
        .custom_container .db_list_right_sec,
        .custom_container .list_table_body {
            height: auto;
            max-height: calc(100vh - 200px);
        }
        .db_list_right_sec.fluid .db_list_left_btm,
        .db_list_right_sec.fluid .db_list_right_sec,
        .db_list_right_sec.fluid .list_table_body {
            height: 340px;
        }

        .custom_container .mCSB_scrollTools .mCSB_dragger {
            right: -10px !important;
        }

        .ajax_call_loader {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            z-index: 5;
            text-align: center;
        }

        .ajax_call_loader img {
            width: 40px;
            position: absolute;
            top: 50%;
            left: 50%;
            margin: -25px 0 0 -40px;
        }

        .list_table_body {
            position: relative;
        }

        .modal.appended hr {
            margin: 0;
        }

        .modal.appended .row {
            margin-bottom: 0;
            padding: 3px 0;
        }

        .modal.appended .row label {
            margin: 4px 7px 0 0px !important;
        }

        .modal.appended .modal-title {
            text-align: center;
        }
        .db_list_right_sec.fluid{
            width:100%;
            float:none;
            clear:both;
            border-left: none;
        }
        .col-sm-12.bordered_div.no_padd{ padding-left: 0; }
        .date_label{
            font-weight: 600;
            font-size: 14px;
        }
        .dates_row {
            padding: 20px 21px 0;
        }
        .date_label, .date_input {
            display: inline-block;
            margin-right: 10px;
            vertical-align: middle;
        }
        .date_input select{
            height:35px;
            box-shadow: inset 0 1px 1px rgba(0,0,0,0.075);
            border: 1px solid #ccc;
            background: none;
            width: 181px;
            padding: 0 12px;
            font-weight: 600;
            color:#555;
        }
        .date_input input{ height:35px; }
        .b_r.no_radius{ border-radius: 0; }
        .custom_search_input{
            width: 100%;
            background: #f0f0f0;
            display: block;
            float: none;
            height: 31px;
            padding: 0 15px;
            font-size: 16px;
            border-radius: 30px;
            color: #b2b2b2;
        }
        .dataTables_filter{ display:none; }

        .date_input input, .dates_row input, .dates_row select {
            height: 35px;
            border-color: #b2b2b2;
            box-shadow: none;
        }

        .dates_row select{ height:37px; }
    </style>
    <div class="page_wrapper">
        <input id="campaignId" type="hidden" value="{{$campaignId}}">
        <div class="row hide">
            <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel">
                    <div class="loader_image" style="display: none"><i class="fa fa-spinner fa-spin"></i></div>

                    <div class="x_title">
                        <h2></h2>
                    </div>
                    <div class="x_content">
                        <div class="row hide_ajax">
                            <div class="col-xs-12">
                                <div class="col-xs-12">
                                    <form id="chart_form" class="formee form-horizontal" onsubmit="return false">
                                        <div class="row">
                                            <label class="control-label col-xs-5">Start Date</label>
                                            <div class="col-xs-3">
                                                <div class="form-group">
                                                    <input type="text" name="m_sdate"
                                                           value=""
                                                           id="start_date" class="form-control"
                                                           placeholder="Start Date"/>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <label class="control-label col-xs-5">End Date</label>
                                            <div class="col-xs-3">
                                                <div class="form-group">
                                                    <input type="text" name="m_edate" value=""
                                                           id="end_date" class="form-control" placeholder="Start Date"/>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-xs-5"></div>
                                            <div class="col-xs-6">
                                                <div class="form-group">
                                                    <input type="submit" id="submit_call" name="submit"
                                                           class="btn btn-success" value="Apply"/>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                            <!-- graph area -->
                            <div class="col-md-4 col-xs-12">
                                <div class="x_panel">
                                    <div class="x_title">
                                        <h2>Total Clicks <strong id="total_click_count">0</strong></h2>
                                        <div class="clearfix"></div>
                                    </div>
                                    <div class="x_content2">
                                        <div id="graph_area_clicks" style="width:100%; height:300px;"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4 col-xs-12">
                                <div class="x_panel">
                                    <div class="x_title">
                                        <h2>Total Views <strong id="total_view_count">0</strong></h2>
                                        <div class="clearfix"></div>
                                    </div>
                                    <div class="x_content2">
                                        <div id="graph_area_visits" style="width:100%; height:300px;"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4 col-xs-12">
                                <div class="x_panel">
                                    <div class="x_title">
                                        <h2>CLICKTHROUGH RATE <strong id="ctr_percentage">0%</strong></h2>
                                        <div class="clearfix"></div>
                                    </div>
                                    <div class="x_content2">
                                        <div id="graph_area_rate" style="width:100%; height:300px;"></div>
                                    </div>
                                </div>
                            </div>
                            <!-- /graph area -->
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row hide">
            <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel">
                    <div class="loader_image" style="display: none"><i class="fa fa-spinner fa-spin"></i></div>
                    <div class="x_title">
                        <h2>Mobile Platforms</h2>
                    </div>
                    <div class="x_content">
                        <div class="row">

                            <!-- graph area -->
                            <div class="col-md-4 col-sm-4 col-xs-12">
                                <div class="x_panel">
                                    <div class="x_title">
                                        <h2>Total Clicks</h2>
                                        <div class="clearfix"></div>
                                    </div>
                                    <div class="x_content2">
                                        <div id="graph_area_clicks_pie" style="width:100%; height:300px;"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4 col-sm-4 col-xs-12">
                                <div class="x_panel">
                                    <div class="x_title">
                                        <h2>Total Views</h2>
                                        <div class="clearfix"></div>
                                    </div>
                                    <div class="x_content2">
                                        <div id="graph_area_visits_pie" style="width:100%; height:300px;"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4 col-sm-4 col-xs-12">
                                <div class="x_panel">
                                    <div class="x_title">
                                        <h2>CLICKTHROUGH RATE</h2>
                                        <div class="clearfix"></div>
                                    </div>
                                    <div class="x_content2">
                                        <div id="graph_area_rate_pie" style="width:100%; height:300px;"></div>
                                    </div>
                                </div>
                            </div>
                            <!-- /graph area -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">

        @section('newFeed_static')
            <!-- Newsfeed Graph Sec -->
                <div class="newFeed_static">
                    <div class="newsfeed_stat_sec mobile_platform_outer clearfix">
                        {{--<h3 class="nf_stat_heading"> Mobile Platform </h3>--}}
                        <h3 id="campaignNameMain" class="nf_stat_heading"></h3>
                        <div class="newsfeed_stat_list_outer clearfix">
                            <div class=" newsfeed_stat_list">
                                <h2> IOS </h2>
                                <ul>
                                    <li><em class="clickIphoneCount"></em> Clicks</li>
                                    <li><em class="viewIphoneCount"></em> Views</li>
                                    <li><em class="iphoneClickThrough"></em>%</li>
                                </ul>
                            </div>
                            <div class=" newsfeed_stat_list">
                                <h2> Android </h2>
                                <ul>
                                    <li><em class="clickAndroidCount"></em> Click</li>
                                    <li><em class="viewAndroidCount"></em> Views</li>
                                    <li><em class="androidClickThrough"></em>%</li>
                                </ul>
                            </div>
                            <div class="newsfeed_stat_list camp_Dur_detail ">
                                <form class="mobilePlateFormForm">
                                    <input type="hidden" name="newsFeedId" value="">
                                    <ul>
                                        <li>
                                            <div class="camp_Dur_timing clearfix">
                                                <label for="start_tm"> Start Time</label>
                                                <div class=" inp_dat_picker b_r">
                                                    <label>
                                                        <input id="rangeStartDate" type="date" name="fromDate">
                                                    </label>
                                                </div>
                                            </div>
                                        </li>
                                        <li>
                                            <div class="camp_Dur_timing clearfix">
                                                <label for="start_tm"> End Time</label>
                                                <div class=" inp_dat_picker b_r">
                                                    <label>
                                                        <input id="rangeEndDate" type="date" name="toDate">
                                                    </label>
                                                </div>
                                            </div>
                                        </li>
                                        <li>
                                            <div class="camp_Dur_timing clearfix">

                                                <div class="" style="text-align:right; padding-right:10px; ">
                                                    <button id="applyDateRange" type="button" class="btn btn-success" name="button">
                                                        APPLY
                                                    </button>
                                                </div>
                                            </div>
                                        </li>
                                    </ul>
                                    <span id="dateRangeError" style="color: #F99 ; float: left"></span>
                                </form>
                            </div>
                        </div>

                    </div>
                    <div class="newsfeed_stat_sec mobile_platform_outer clearfix">
                        <div class="dashboar_charts_outer">
                            <div class="dash_chart_head clearfix">
                                <div class="dash_chart_left">
                                    <label for="">Chart</label>
                                    <ul id="chartTypes" class="nav nav-tabs">
                                        <li class="active" onclick="javascript: showChartTab('chartView');">
                                            <a data-toggle="tab" href="#chart-view" class="font-size18px">View</a>
                                        </li>
                                        <li onclick="javascript: showChartTab('chartClick');">
                                            <a data-toggle="tab" href="#chart-click" class="font-size18px">Click</a>
                                        </li>
                                        <li onclick="javascript: showChartTab('chartClickThrough');">
                                            <a data-toggle="tab" href="#chart-clickThrough" class="font-size18px">Click
                                                Through</a>
                                        </li>
                                    </ul>
                                </div>
                                <div class="generalChart chartView col-md-12 pt-1">
                                    <div id="total_views"></div>
                                </div>
                                <div class="generalChart chartClick col-md-12 pt-1 hide">
                                    <div id="total_click"></div>
                                </div>
                                <div class="generalChart chartClickThrough col-md-12 pt-1 hide">
                                    <div id="click_through"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endsection

            <div class="col-xs-12">
                <div class="x_panel">
                    <div class="x_title">
                        <h2>Campaign Details</h2>
                    </div>
                    <div class="row">
                        <div class="col-sm-6 col-xs-12">
                            <div class="x_panel">
                                <div class="x_title">
                                    <h2 id="campaignNamePreview"></h2>
                                </div>
                                <div class="x_content">
                                    <div id="preview_div">
                                    </div>
                                    <iframe style="width: 100%; height: 500px;" id="preview_iframe" srcdoc=""></iframe>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6 col-xs-12">
                            <div class="x_panel">
                                <div class="x_title">
                                    <h2>Delivery Window</h2>
                                    {{--<a style="float: right" href="{{ route('editNewsfeed',  $news->id)}}"><i class="fa fa-pencil"></i></a>--}}
                                </div>
                                <div class="x_content">
                                    <div class="x_content2">
                                        <div id="" style="width:100%; max-height:100px;">
                                            <dl class="dl-info">
                                                <dt>START TIME:</dt>
                                                <dd id="startTime"></dd>
                                                <dt>END TIME:</dt>
                                                <dd id="endTime"></dd>
                                            </dl>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="x_panel">
                                <div class="x_title">
                                    <h2>Targeted Audience</h2>
                                </div>
                                <div class="x_content">
                                    <div class="x_content2">
                                        <div id="" style="width:100%; max-height:200px;">
                                            <dl class="dl-info">
                                                <dt>Segment:</dt>
                                                <dd id="segementsInfo"></dd>
                                                <dt>No. OF USERS:</dt>
                                                <dd id="totalUsers"></dd>
                                            </dl>

                                            <strong></strong>


                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="x_panel">
                                <div class="loader_image" style="display: none;"><i class="fa fa-spinner fa-spin"></i>
                                </div>
                                <div class="x_title">
                                    <h2>Performance Statistics</h2>
                                    <span style="float: right;color: #0a0a0a"
                                          id="selected_time">{{--Jul 05, 2018 - Jul 06, 2018--}}</span>

                                </div>
                                <div class="x_content">
                                    <div class="x_content2">
                                        <div id="" style="width:100%; height:200px;">

                                            <table class="table">
                                                <thead>
                                                <tr>
                                                    <th></th>
                                                    <th>Best Day</th>
                                                    <th>Worst Day</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                <tr>
                                                    <th scope="row">Views</th>
                                                    <td id="views_h"><b></b>
                                                    </td>
                                                    <td id="views_l"><b></b>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <th scope="row">Clicks</th>
                                                    <td id="clicks_h"><b></b>
                                                    </td>
                                                    <td id="clicks_l"><b></b>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <th scope="row">CLICKTHROUGHS</th>
                                                    <td id="ctr_h">
                                                        <b></b></td>
                                                    <td id="ctr_l">
                                                        <b></b></td>
                                                </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <br>
                </div>
                <div class="container custom_container">

                    <div class="row">
                        <h3 class="heading">Campaign Tracking</h3>
                        <div class="col-sm-12 bordered_div no_padd">
                            <div class="bordered_div_holder">
                                {{--@include('partials.left -scroll-bar-trackingListing')--}}
                                <div class="db_list_right_sec fluid">
                                    @if (Session::has('flash_message'))
                                        <div class="alert alert-info">{{ Session::get('flash_message') }}</div>
                                    @endif
                                        <div class="row dates_row">
                                            <div class="col-sm-8">
                                                <div class="date_label">From: </div>
                                                <div class="date_input">
                                                        <div class="inp_dat_picker b_r no_radius">
                                                        <label>
                                                            <input id="date_start" type="date" name="fromDate">
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="date_label">To: </div>
                                                <div class="date_label">
                                                    <div class="inp_dat_picker b_r no_radius">
                                                        <label>
                                                            <input id="date_end" type="date" name="fromDate">
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="date_input">
                                                    <select class="filter">
                                                        <option value="">Select Status</option>
                                                        <option value="added">Added</option>
                                                        <option value="executing">Executing</option>
                                                        <option value="completed">Completed</option>
                                                        <option value="failed">Failed</option>
                                                    </select>
                                                </div>
                                                <div class="date_label">
                                                    <input type="button" id="campaignTracking_date_filter" name="submit" class="btn btn-success" value="Apply">
                                                </div>
                                            </div>
                                            <div class="col-sm-4">
                                                <div class="data_input">
                                                    <input id="campaignTrackingsearchBar" class="custom_search_input" type="search" name="" value="" placeholder="Search...">
                                                </div>
                                            </div>

                                        </div>
                                        <span id="campaigndateRangeError" style="color: #F99 ; display: block; padding: 3px 14px 11px;"></span>
                                    <div id="MSG"></div>
                                    <div>
                                        <div class="list_table_body scrollbar_content mCustomScrollbar _mCS_1">
                                            <div class="ajax_call_loader" style="display: none;">
                                                <img src="{{asset('assets/images/loader_ajax.gif')}}">
                                            </div>
                                            <table cellspacing="0" cellpadding="0"
                                                   id="campaignTrackingTable">
                                                <thead>
                                                <tr>
                                                    <th style="width:20%;">Track Key</th>
                                                    <th style="width:20%;">Email</th>
                                                    <th style="width:20%;">Sent At</th>
                                                    <th style="width:20%;">Status</th>
                                                    <th style="width:20%;">Viewed At</th>
                                                    <th style="width:30%;">Message</th>
                                                    <th style="width:10%;">Action</th>
                                                </tr>
                                                </thead>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
                <div class="container custom_container">
                    <div class="row">
                        <h3 class="heading">Action Trigger</h3>
                        <div class="col-sm-12 bordered_div last no_padd">
                            <div class="bordered_div_holder">
                                {{--@include('partials.left-scroll-bar-actiontrackinglisting')--}}
                                <div class="db_list_right_sec fluid">
                                    @if (Session::has('flash_message'))
                                        <div class="alert alert-info">{{ Session::get('flash_message') }}</div>
                                    @endif
                                        <div class="row dates_row">
                                            <div class="col-sm-8">
                                            <div class="date_label">From: </div>
                                            <div class="date_input">
                                                <div class="inp_dat_picker b_r no_radius">
                                                    <label>
                                                        <input id="action_start_date" type="date" name="fromDate">
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="date_label">To: </div>
                                            <div class="date_label">
                                                <div class="inp_dat_picker b_r no_radius">
                                                    <label>
                                                        <input id="action_date_end" type="date" name="fromDate">
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="date_label">
                                                <input type="button" id="action_date_filter" name="submit" class="btn btn-success" value="Apply">
                                            </div>
                                            </div>
                                            <div class="col-sm-4">
                                                <div class="data_input">
                                                    <input id="campaignActionTriggerSearch" class="custom_search_input" type="search" name="" value="" placeholder="Search...">
                                                </div>
                                            </div>

                                        </div>
                                        <span id="actiondateRangeError" style="color: #F99 ; display: block; padding: 3px 14px 11px;"></span>
                                    <div id="MSG"></div>
                                    <div>
                                        <div class="list_table_body scrollbar_content mCustomScrollbar _mCS_1">
                                            <table cellspacing="0" cellpadding="0"
                                                   id="campaignActionTrigger">
                                                <thead>
                                                <tr>
                                                    <th width="10%">Sr#</th>
                                                    <th width="30%">Email</th>
                                                    <th width="20%">App Name</th>
                                                    <th width="20%">Event Name</th>
                                                    <th width="20%">Event Value</th>
                                                    <th width="20%">Device Type</th>
                                                    <th width="20%">Bulid</th>
                                                    <th width="20%">Version</th>
                                                    <th width="20%">Created at</th>
                                                </tr>
                                                </thead>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
        <div id="campaignDetail">
        </div>
    </div>
@stop

@section('jsSection')

    <script src="https://code.highcharts.com/highcharts.js"></script>
    <script src="https://code.highcharts.com/modules/series-label.js"></script>
    <script src="https://code.highcharts.com/modules/exporting.js"></script>
    <script src="{{asset('/assets/js/campaign/campaignStats.js')}}"></script>
    <script src="https://cdn.datatables.net/buttons/1.5.2/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.5.2/js/buttons.flash.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.5.2/js/buttons.html5.min.js"></script>
    <script type="text/javascript">
        var startDate=null;
        var endDate=null;
        var actionStartDate=null;
        var actionENdDate=null;
        $(document).ready(function () {

            $(document).on('click', '.lst_tbl_drop_outer_Tracking', function (e) {

                $(this).find('ul').slideToggle();
                $(this).closest('tr').nextAll('tr').find('.lst_tbl_drop_outer_Tracking').find('ul').slideUp();
                $(this).closest('tr').prevAll('tr').find('.lst_tbl_drop_outer_Tracking').find('ul').slideUp();
            });


            var id = $("#campaignId").val();
            console.log('id', id);
            var TrackingTable;
            var ActionTrackingTable;
            var campaginStatus;
            var filter = null;
            var filterType = null;
            var actionFilter = null;
            var actionfilterType = null;
            getSegmentListing();
            events();
            getActionTrigger();
            /***********************************************Campaign Tracking Method************************************************/
            /***********************************************Campaign Tracking Method************************************************/
            $(".filter").on("change", function (e) {
                filter = $(this).val();
                filterType='app_name';
                console.log('filter',filter);
                console.log('filterType',filterType);
                $("#campaignTrackingTable").DataTable().draw();
            });

            $("#campaignTracking_date_filter").on("click",function(e) {
                if ($("#date_start").val() > $("#date_end").val()) {
                    $("#campaigndateRangeError").text("End Time should be greater than or equals to Start Time");
                    return;
                }else{
                    $("#campaigndateRangeError").text("");
                    startDate = $("#date_start").val();
                    endDate = $("#date_end").val();
                    console.log('startDate',startDate);
                    console.log('endDate',endDate);
                    $("#campaignTrackingTable").DataTable().draw();
                }
            });


            function getSegmentListing() {
                startDate = $("#date_start").val();
                endDate = $("#date_end").val();
                console.log('startDate',startDate);
                console.log('endDate',endDate);
                var returnlink;
                var url = baseUrl + '/backend/campaign/campaignTrackingListing/' + id;
                $('#campaignTrackingTable').DataTable({
                    "dom": 'Bfrtip',
                    "buttons": [
                        'csv'
                    ],
                    "processing": true,
                    "serverSide": true,
                    "searching": true,
                    "searchPlaceholder": "search..",
                    "bLengthChange": false,
                    "iDisplayLength": listingSize,
                    "defaultContent": "",
                    "ajax": {
                        "url": url,
                        "dataType": "json",
                        "type": "GET",
                        "data": function (data) {
                            data.filter = filter;
                            data.filterType = filterType;
                            data.start_date = startDate;
                            data.end_Date = endDate;
                        },
                    },
                    "columns": [
                        {
                            "data": "Trackkey",
                            "className": "nf_seg_name",
                            "mRender": function (data, type, full) {
                                if (full.uid > 0) {
                                    returnlink = '<a href="#" data-toggle="modal" data-target="#conversation' + full.id + '"><i class="fas fa-edit"></i>' + full.Trackkey + '</a>' +
                                        '<div id="conversation' + full.id + '" class="modal fade appended" role="dialog">' +
                                        '<div class="modal-dialog">' +
                                        '<div class="modal-content">' +
                                        '<div class="modal-header">' +
                                        '<button type="button" class="close" data-dismiss="modal">' + 'x' + '</button>' +
                                        '<h4 class="modal-title">Conversation</h4>' +
                                        '<div class="modal-body">' +
                                        '<div class="row">' +
                                        '<div class="col-sm-12">' +
                                        '<label style="margin-left: 15px">Key:</label>' +
                                        '<span>"' + full.Trackkey + '"</span>' +
                                        '</div>' +
                                        '</div><hr>' +
                                        '<div class="row">' +
                                        '<div class="col-sm-12">' +
                                        '<label style="margin-left: 15px">Firebase Key:</label>' +
                                        '<span>' + full.firebase_key + '</span>' +
                                        '</div>' +
                                        '</div><hr>' +
                                        '<div class="row">' +
                                        '<div class="col-sm-12">' +
                                        '<label style="margin-left: 15px">Device Key:</label>' +
                                        '<span>' + full.device_key + '</span>' +
                                        '</div>' +
                                        '</div><hr>' +
                                        '<div class="row">' +
                                        '<div class="col-sm-12">' +
                                        '<div class="col-sm-4">' +
                                        '<label>Event Id:</label>' +
                                        '</div>' +
                                        '<div class="col-sm-8">' +
                                        '<span>' + full.event_id + '</span>' +
                                        '</div>' +
                                        '</div>' +
                                        '</div><hr>' +
                                        '<div class="row">' +
                                        '<div class="col-sm-12">' +
                                        '<div class="col-sm-4">' +
                                        '<label>Email:</label>' +
                                        '</div>' +
                                        '<div class="col-sm-8">' +
                                        '<span>' + full.email + '</span>' +
                                        '</div>' +
                                        '</div>' +
                                        '</div><hr>' +
                                        '<div class="row">' +
                                        '<div class="col-sm-12">' +
                                        '<div class="col-sm-4">' +
                                        '<label>Event Name:</label>' +
                                        '</div>' +
                                        '<div class="col-sm-8">' +
                                        '<span>' + full.event_name + '</span>' +
                                        '</div>' +
                                        '</div>' +
                                        '</div><hr>' +
                                        '<div class="row">' +
                                        '<div class="col-sm-12">' +
                                        '<div class="col-sm-4">' +
                                        '<label>Event Value:</label>' +
                                        '</div>' +
                                        '<div class="col-sm-8">' +
                                        '<span>' + full.event_value + '</span>' +
                                        '</div>' +
                                        '</div>' +
                                        '</div><hr>' +
                                        '<div class="row">' +
                                        '<div class="col-sm-12">' +
                                        '<div class="col-sm-4">' +
                                        '<label>Version:</label>' +
                                        '</div>' +
                                        '<div class="col-sm-8">' +
                                        '<span>' + full.version + '</span>' +
                                        '</div>' +
                                        '</div>' +
                                        '</div><hr>' +
                                        '<div class="row">' +
                                        '<div class="col-sm-12">' +
                                        '<div class="col-sm-4">' +
                                        '<label>Build:</label>' +
                                        '</div>' +
                                        '<div class="col-sm-8">' +
                                        '<span>' + full.build + '</span>' +
                                        '</div>' +
                                        '</div>' +
                                        '</div><hr>' +
                                        '<div class="row">' +
                                        '<div class="col-sm-12">' +
                                        '<div class="col-sm-4">' +
                                        '<label>Device Type:</label>' +
                                        '</div>' +
                                        '<div class="col-sm-8">' +
                                        '<span>' + full.device_type + '</span>' +
                                        '</div>' +
                                        '</div>' +
                                        '</div><hr>' +
                                        '<div class="row">' +
                                        '<div class="col-sm-12">' +
                                        '<div class="col-sm-4">' +
                                        '<label>Conversion Date:</label>' +
                                        '</div>' +
                                        '<div class="col-sm-8">' +
                                        '<span>' + full.created_at + '</span>' +
                                        '</div>' +
                                        '</div>' +
                                        '</div>' +
                                        '</div>' +
                                        '</div>' +
                                        '</div>' +
                                        '</div>' +
                                        '</div>' +
                                        '</div>';
                                } else {
                                    returnlink = '<a title="' + full.Trackkey + '"><i class="fas fa-edit"></i>' + full.Trackkey + '</a>'
                                }
                                return returnlink;
                            },
                            "visible": true,
                            "render": $.fn.dataTable.render.text()
                        },
                        {
                            "data": "UserEmail",
                            "mRender": function (data, type, full) {
                                return '<a target="_blank" href="' + baseUrl + '/backend/attribute/user/Stat/' + full.rowId + '">' + full.UserEmail + '</a>';

                            },
                            "visible": true,
                            "render": $.fn.dataTable.render.text()
                        },
                        {
                            "data": "sent_at",
                            "render": $.fn.dataTable.render.text()
                        },
                        {
                            "data": "completestatus",
                            "render": $.fn.dataTable.render.text()
                        },
                        {
                            "data": "viewed_at",
                            "render": $.fn.dataTable.render.text()
                        },
                        {
                            "data": "message",
                            "mRender": function (data, type, full) {
                                return '<a href="#" data-toggle="modal" data-target="#messages' + full.id + '">' + full.message + '</a>' +
                                    '<div id="messages' + full.id + '" class="modal fade appended" role="dialog">' +
                                    '<div class="modal-dialog">' +
                                    '<div class="modal-content">' +
                                    '<div class="modal-header">' +
                                    '<button type="button" class="close" data-dismiss="modal">' + 'x' + '</button>' +
                                    '<h4 class="modal-title">Campaign Messages</h4>' +
                                    '<div class="modal-body">' +
                                    '<div class="row">' +
                                    '<div class="col-sm-12">' +
                                    '<span>' + full.message + '</span>' +
                                    '</div>' +
                                    '</div>' +
                                    '</div>' +
                                    '</div>' +
                                    '</div>' +
                                    '</div>' +
                                    '</div>';

                            },
                            "visible": true,
                            "render": $.fn.dataTable.render.text()

                        },
                        {"data": "action"}
                    ],
                    "aoColumnDefs": [
                        {
                            "aTargets": [6],
                            "mData": "action",
                            "mRender": function (data, type, full) {
                                if (full.completestatus == 'failed') {
                                    console.log('track', full);
                                    return '<div class="lst_tbl_drop_outer_Tracking">' +
                                        '<span class=""> <img src="' + baseUrl + '/assets/images/sett_icon.png' + '"  alt="#"> </span>' +
                                        '<ul>' +
                                        '<li id="' + full.id + '" ><a class="trackingAction">' +
                                        '<img src="' + baseUrl + '/assets/images/view_icon.png' + '" alt="#"> Resend</a>' +
                                        '</li></ul></div></td>';
                                }
                                return '';

                            },
                            "visible": true,
                            "searchable": false,
                            "orderable": false
                        }
                    ],
                    "order": [[0, "desc"]],
                    "initComplete": function (settings, json) {
                        // call after loaded only first time
                    },
                    "drawCallback": function (settings, json) {
                        //call after every event cause change in datatable
                        // var description = $("#campaignTrackingTable_info").text();
                        // $("#campaignTrackingTable_info").text("");
                        // $(".listing_sec_ftr_detail p").text(description);
                    }
                });
                TrackingTable = $('#campaignTrackingTable').DataTable();
               // console.log(TrackingTable, 'TrackingTable');
                $('#campaignTrackingsearchBar').on("change", function () {
                    TrackingTable.search($(this).val()).draw();
                });
            }


            /***********************************************Event Method************************************************/
            function events() {
                $(document).on('click', '.lst_tbl_drop_outer_Tracking ul li', function () {
                    campaignResenNotificaition($(this).attr('id'));

                });
            }

            /***********************************************Resend notificaition Method************************************************/
            function campaignResenNotificaition(id) {
                var compaignid = $("#campaignId").val();
                console.log('detailCampaign', compaignid);
                var url = baseUrl + '/backend/campaign/resendNotification/' + compaignid + '/' + id;
                $.ajax({
                    url: url,
                    type: "GET",
                    beforeSend: function () {
                        $(".ajax_call_loader").css('display', 'block');
                    },
                    complete: function () {
                        $(".ajax_call_loader").css('display', 'none');
                    },
                    success: function (response) {
                        console.log('res', response);
                        if (response.status == 200) {
                            TrackingTable.ajax.reload();
                            toastr.success(response.message);
                        } else if (response.status == 402) {
                            for (var val = 0; val < response.data.length; val++) {
                                toastr.error(response.data[val].message);
                            }
                            TrackingTable.ajax.reload();
                        } else {
                            toastr.error(response.message);
                        }
                        console.log('resp', response);
                    }, error: function (error) {
                        console.log('error', error);
                    }
                });
            }

            /***********************************************Action trigger Method************************************************/
            /***********************************************Action trigger Method************************************************/
            $(".actionfilter").on("click", function (e) {
                // alert('call');
                actionFilter = $(this).attr("data-action");
                actionfilterType = $(this).attr("data-type");
                console.log('actionFilter', actionFilter);
                console.log('actionfilterType', actionfilterType);
                $("#campaignActionTrigger").DataTable().draw();
            });


            $("#action_date_filter").on("click",function(e) {
             //   alert('call');
                if ($("#action_start_date").val() > $("#action_date_end").val()) {
                    $("#actiondateRangeError").text("End Time should be greater than or equals to Start Time");
                    return;
                }else{
                    $("#actiondateRangeError").text("");
                    actionStartDate = $("#action_start_date").val();
                    actionENdDate = $("#action_date_end").val();
                    console.log('actionStartDate',actionStartDate);
                    console.log('actionENdDate',actionENdDate);
                    $("#campaignActionTrigger").DataTable().draw();
                }

            });



            function getActionTrigger() {
             //   alert('call');
                actionStartDate = $("#action_start_date").val();
                actionENdDate = $("#action_date_end").val();
                console.log('actionStartDate',actionStartDate);
                console.log('actionENdDate',actionENdDate);
                var url = baseUrl + '/backend/campaign/campaignActionTrigger/' + id;
                $('#campaignActionTrigger').DataTable({
                    "dom": 'Bfrtip',
                    "buttons": [
                        'csv'
                    ],
                    "processing": true,
                    "serverSide": true,
                    "searching": true,
                    "searchPlaceholder": "search..",
                    "bLengthChange": false,
                    "iDisplayLength": listingSize,
                    "defaultContent": "",
                    "ajax": {
                        "url": url,
                        "dataType": "json",
                        "type": "GET",
                        "data": function (data) {
                            data.actionFilter = actionFilter;
                            data.actionfilterType = actionfilterType;
                            data.actionStartDate=actionStartDate;
                            data.actionENdDate=actionENdDate;
                        },
                    },
                    "columns": [
                        {
                            "data": "id",
                        },
                        {
                            "data": "email",
                            "mRender": function (data, type, full) {
                                return '<a target="_blank" href="' + baseUrl + '/backend/attribute/user/Stat/' + full.row_id + '">' + full.email + '</a>';

                            },
                            "visible": true,
                            "render": $.fn.dataTable.render.text()
                        },
                        {
                            "data": "app_name",
                            "render": $.fn.dataTable.render.text()
                        },
                        {
                            "data": "event_name",
                            "render": $.fn.dataTable.render.text()
                        },
                        {
                            "data": "event_value",
                            "render": $.fn.dataTable.render.text()
                        },
                        {
                            "data": "device_type",
                            "render": $.fn.dataTable.render.text()
                        },
                        {
                            "data": "build",
                            "render": $.fn.dataTable.render.text()
                        },
                        {
                            "data": "version",
                            "render": $.fn.dataTable.render.text()
                        },
                        {
                            "data": "created_at",
                            "render": $.fn.dataTable.render.text()
                        }
                    ],
                    "order": [[0, "desc"]],
                    "initComplete": function (settings, json) {

                        // call after loaded only first time
                    },
                    "drawCallback": function (settings, json) {
                        // // call after every event cause change in datatable
                        // var description = $("#campaignActionTrigger").text();
                        // $("#campaignActionTrigger").text("");
                        // $(".listing_sec_ftr_detail p").text(description);
                    }
                });
                ActionTrackingTable = $('#campaignActionTrigger').DataTable();
                //console.log(ActionTrackingTable, 'TrackingTable');

                $('#campaignActionTriggerSearch').on("change", function () {
                    ActionTrackingTable.search($(this).val()).draw();
                });
            }

            $('.bordered_div_holder input[type="search"]').attr('placeholder', "Search ...");
        });

    </script>
@stop
