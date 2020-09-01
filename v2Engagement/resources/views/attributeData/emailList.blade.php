@extends('layouts.master')

@section('searchBar')
    <div class="tp_BreadCrumb_list_sec clearfix">
        <label class="sec_tp_title"> Email List </label>
        <div class="track_userdeta_right clearfix">
            <div class="tp_BreadCrumb_srch_inp">
                <input id="searchBar" type="search" name="" value="" placeholder="Search...">
            </div>

            {{--<div class="uder_deta_dropdown">--}}
            {{--<div class=" inp_select">--}}
            {{--<select class="uploadPage" onchange="uploadPage()">--}}
            {{--value="app_message"--}}
            {{--<option value="" >Actions</option>--}}
            {{--<!-- <option value="UPLOAD">Upload</option>-->--}}
            {{--</select>--}}
            {{--</div>--}}
            {{--</div>--}}

        </div>
    </div>
@stop




@section('content')
    <div class="db_list_left_sec ">
        <div class="db_list_left_tp">
            <label> Filters : </label>
        </div>
        <div class="db_list_left_btm scrollbar_content mCustomScrollbar _mCS_1">
            <ul class="userType">
                <li><a class="blacklist" href="javascript:void(0);" data-action="blacklist">Blacklist</a></li>
                <li><a class="whitelist" href="javascript:void(0);" data-action="whitelist">Whitelist</a></li>
            </ul>
        </div>
    </div>


    <style>
        /* datatable styling */
        table.dataTable tbody tr {
            background-color: #ffffff;
            height: 49px !important;
        }

        #emailListing td:first-child {
            text-align: left;
            padding: 13px 20px !important;
        }

        #emailListing_filter label {
            display: none !important;
        }

        table.dataTable thead th, table.dataTable thead td {
            border-bottom: 1px solid #c0c0c0 !important;
        }

        table.dataTable.no-footer {
            border-bottom: 1px solid white;
        }

        /*table.dataTable tbody td:nth-child(2),*/
        /*table.dataTable tbody td:nth-child(4) {*/
        /*padding: 8px 10px;*/
        /*word-break: break-all;*/
        /*}*/
        table.dataTable th:nth-child(1) {
            width: 40% !important;
        }

        table.dataTable th:nth-child(2) {
            width: 20% !important;
        }

        table.dataTable th:nth-child(3) {
            width: 47% !important;
        }

        table.dataTable th:nth-child(4) {
            width: 7% !important;
        }

        .dataTables_filter {
            display: none;
        }
    </style>

    <div class="db_list_right_sec">
        <div class="MSG"></div>
        <div class="list_table_body scrollbar_content mCustomScrollbar _mCS_1">
            <div class="list_table_header">
                <table cellspacing="0" cellpadding="0" id="emailListing">
                    <thead>
                    <th>Email</th>
                    <th>type</th>
                    <th>Created Date</th>
                    <th></th>
                    </thead>
                </table>
            </div>
        </div>
        @stop

        @section('jsSection')
            <script src="{{asset('/assets/js/attributeData/emailList.js')}}"></script>
            <script>
                var emailListDT = "{{ route('emailListDT') }}";
                var emailListView = "{{ route('emailListView') }}";
                var deleteEmailList = "{{ route('deleteEmailList') }}";
            </script>
@stop
