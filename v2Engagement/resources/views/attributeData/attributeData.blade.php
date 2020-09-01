@extends('layouts.master')

@section('searchBar')

    <div class="tp_BreadCrumb_list_sec clearfix">
        <label class="sec_tp_title"> User Data </label>
        <div class="track_userdeta_right clearfix">
            <div class="tp_BreadCrumb_srch_inp user_attribute_search">
                <input id="searchBar" type="search" name="" value="" placeholder="Search...">
            </div>

            <div class="uder_deta_dropdown user_attribute_search">
                    <a class="btn btn-primary" href="{{ route('otherAttributeDataView') }}">Other Attribute Data</a>
                <button class="dt-button  buttons-html5" id="importData" tabindex="0" aria-controls="attributeDataListing" type="button"><span>Export</span></button>
            </div>
        </div>

    </div>
@stop

@section('create')
    <input class="companyId" type="hidden" value="{{$companyId}}">
@stop




@section('content')
    @include('partials.left-scroll-bar-attribute-data')

    <style>
        /* datatable styling */
        table.dataTable tbody tr {
            background-color: #ffffff;
            height: 49px !important;
        }

        #attributeDataListing td:first-child {
            text-align: left;
            padding: 13px 20px !important;
        }

        #attributeDataListing_filter label {
            display: none !important;
        }

        table.dataTable thead th, table.dataTable thead td {
            border-bottom: 1px solid #c0c0c0 !important;
        }

        table.dataTable.no-footer {
            border-bottom: 1px solid white;
        }

        table.dataTable tbody td:nth-child(2),
        table.dataTable tbody td:nth-child(4) {
            padding: 8px 10px;
            word-break: break-all;
            text-align: left;
        }


    </style>

    <div class="db_list_right_sec attribute_users">
        <div class="list_table_body scrollbar_content mCustomScrollbar _mCS_1">
            <div class="list_table_header">
                <table cellspacing="0" cellpadding="0" id="attributeDataListing">
                    <thead>
                    <th></th>
                    <th style="width: 100px;"></th>
                    <th style="width: 30%;">Name</th>
                    <th style="width: 10%">Device</th>
                    <th style="width: 25%">App Name</th>
                    <th>Last Login</th>
                    <th style="width: 50px;"></th>
                    </thead>
                </table>
            </div>
        </div>
        @stop

@section('jsSection')
<script src="https://cdn.datatables.net/buttons/1.5.2/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.5.2/js/buttons.flash.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.5.2/js/buttons.html5.min.js"></script>
<script src="{{asset('/assets/js/attributeData/attributeData.js')}}"></script>

@stop
