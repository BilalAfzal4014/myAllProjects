@extends('layouts.master')

@section('searchBar')
    <div class="tp_BreadCrumb_list_sec clearfix">
        <label class="sec_tp_title"> Location </label>
        <div class="track_userdeta_right clearfix">
            <div class="tp_BreadCrumb_srch_inp">
                <input id="searchBar" type="search" name="" value="" placeholder="Search...">
            </div>

            <div class="uder_deta_dropdown">
                <div class=" inp_select">
                    <select id="dashboard_quick_action">
                        <option value=""> Actions</option>
                        <option value="{{ route('location.create') }}">Add Location</option>
                    </select>
                </div>


            </div>

        </div>

    </div>
@stop

@section('content')
    <style>
        /*tags styling*/
        .bootstrap-tagsinput {
            margin-bottom: 10px !important;
        }

        /* datatable styling */
        table.dataTable tbody tr {
            background-color: #ffffff;
            height: 49px !important;
        }

        #campaignListing td:first-child {
            text-align: left;
            padding: 13px 20px !important;
        }

        #locationListing_filter label {
            display: none !important;
        }

        table.dataTable thead th, table.dataTable thead td {
            border-bottom: 1px solid #c0c0c0 !important;
        }

        table.dataTable.no-footer {
            border-bottom: 1px solid white;
        }

    </style>
    <div class="db_list_right_sec">
        <div class="list_table_body scrollbar_content mCustomScrollbar _mCS_1">
            <div class="list_table_header">
                <table cellspacing="0" cellpadding="0" id="locationListing">
                    <thead>
                    <th style="width:15%;">Country Name</th>
                    <th style="width:10%;">Address</th>
                    <th style="width:15%;">Latitude</th>
                    <th style="width:15%;">Longitude</th>
                    <th style="width:8%;">Currency</th>
                    <th style="width:8%;">Radius</th>
                    <th style="width:10%;">Created At</th>
                    <th style="width:6%;"></th>
                    </thead>
                </table>
            </div>
        </div>
    </div>
@stop


@section('jsSection')
    <script src="{{asset('/assets/js/location/locationListing.js')}}"></script>
@stop
