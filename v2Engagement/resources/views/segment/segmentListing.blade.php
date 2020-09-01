@extends('layouts.master')
@section('searchBar')
    <div class="tp_BreadCrumb_list_sec clearfix">
        <label class="sec_tp_title"> Segments </label>
        <div class="track_userdeta_right clearfix">
            <div class="tp_BreadCrumb_srch_inp">
                <input id="searchBar" type="search" name="" value="" placeholder="Search...">
            </div>
            <div class="uder_deta_dropdown">
                <div class=" inp_select">
                    <select id="campaigns_type2">
                        <option value="app_message">Actions</option>
                        <option value="email_html-2">Create Segment</option>
                    </select>
                </div>
            </div>

        </div>

    </div>
@stop

@section('content')
    @include('segment.left-scroll-bar')

    <style>
        /* datatable styling */
        table.dataTable tbody tr {
            background-color: #ffffff;
            height: 49px !important;
        }

        #segmentListing td:first-child {
            text-align: left;
            padding: 13px 20px !important;
        }

        #segmentListing_filter label {
            display: none !important;
        }

        table.dataTable thead th, table.dataTable thead td {
            border-bottom: 1px solid #c0c0c0 !important;
        }

        table.dataTable.no-footer {
            border-bottom: 1px solid white;
        }

        /*datepicker styling*/
        th {
            text-align: center;
        }

        tfoot {
            display: none;
        }

        .dropdown-menu {
            padding: 0px;
        }

        th.next, th.prev, td.day {
            cursor: pointer;
        }

        td.old, td.new {
            color: #ccc !important;
            background-color: #eee !important;
            cursor: not-allowed !important;
        }

        td.active {
            background: #ebedf2;
        }
    </style>
    <div class="db_list_right_sec">
        <input class="companyId" type="hidden" value="{{$companyId}}">
        <div class="list_table_body scrollbar_content mCustomScrollbar _mCS_1 segment_scroll">
            <div class="list_table_header">
                <table cellspacing="0" cellpadding="0" id="segmentListing">
                    <thead>
                    <th style="width:10%;"></th>
                    <th style="width:10%;"></th>
                    <th style="width:35%;" >Name</th>
                    <th style="width:15%;">Target</th>
                    <th style="width:20%;">Created By</th>
                    <th style="width:6%;"></th>
                    </thead>
                </table>
            </div>
        </div>
    </div>
@stop

@section('jsSection')
    <script src="{{asset('/assets/js/segment/segmentListing.js')}}"></script>
@stop
