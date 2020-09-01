@extends('layouts.master')

@section('searchBar')
    <div class="tp_BreadCrumb_list_sec clearfix">
        <label class="sec_tp_title"> Campaign </label>
        <div class="track_userdeta_right clearfix">
            <div class="tp_BreadCrumb_srch_inp">
                <input id="searchBar" type="search" name="" value="" placeholder="Search...">
            </div>

            <div class="uder_deta_dropdown">
                <div class=" inp_select">
                    <select id="campaigns_type2">
                        <option value="app_message"> Actions</option>
                        <option value="email_html-2"><a href="google.com" >Create Campaign</a></option>
                    </select>
                </div>


            </div>

        </div>

    </div>
@stop

@section('content')
    @include('campaign.left-scroll-bar')
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

        #campaignListing_filter label {
            display: none !important;
        }

        table.dataTable thead th, table.dataTable thead td {
            border-bottom: 1px solid #c0c0c0 !important;
        }

        table.dataTable.no-footer {
            border-bottom: 1px solid white;
        }

        .launch{
            border-left: 5px solid greenyellow;
        }

        .draft{
            border-left: 5px solid blue;
        }

        .suspend{
            border-left: 5px solid red;
        }

        .expired{
            border-left: 5px solid black;
        }

        .nf_seg_name_detail {
            top: 48px !important;
        }

    </style>
    <div class="db_list_right_sec">
        <input class="companyId" type="hidden" value="{{$companyId}}">
        <div class="list_table_body new_content_scroll scrollbar_content mCustomScrollbar _mCS_1">
            <div class="list_table_header">
                <table cellspacing="0" cellpadding="0" id="campaignListing" style="border-collapse: collapse;">
                    <thead>
                    <th></th>
                    <th style="width:25%;">Name</th>
                    <th style="width:10%;">Type</th>
                    <th style="width:15%;">Target Users</th>
                    <th style="width:10%;">Total</th>
                    <th style="width:10%;">Sent</th>
                    <th style="width:10%;">Failed</th>
                    <th style="width:10%;">View</th>
                    <th style="width:20%;">Last Edited</th>
                    <th style="width:8%;"></th>
                    </thead>
                </table>
            </div>
        </div>
    </div>
@stop


@section('jsSection')
    <script src="{{asset('/assets/js/campaign/campaignListing.js')}}"></script>
@stop
