@extends('layouts.master')

@section('searchBar')
    <div class="tp_BreadCrumb_list_sec clearfix">
        <label class="sec_tp_title"> Attribute Data </label>
        <div class="track_userdeta_right clearfix">
            <div class="tp_BreadCrumb_srch_inp">
                <input id="searchBar" type="search" name="" value="" placeholder="Search...">
            </div>
            <div class="uder_deta_dropdown">
                <div class="inp_select">
                    <select id="dashboard_quick_actionTwo">
                        <option value=""> Actions</option>
                        <option value="1">Create Attribute Data</option>
                    </select>
                </div>
            </div>
        </div>
    </div>
@stop

@section('create')
    <input class="companyId" type="hidden" value="{{$companyId}}">
@stop




@section('content')
    <div class="db_list_left_sec ">
        <div class="db_list_left_tp">
            <label> Filters : </label>
        </div>
        <div class="db_list_left_btm scrollbar_content mCustomScrollbar _mCS_1">
            <div class="row ">
                {{--<div class="col-sm-12">--}}
                    {{--<a class="btn btn-primary" href="{{url('/attributes/list')}}">Attributes</a>--}}
                {{--</div>--}}
            </div>
            <ul>
                <li>
                    <div class="db_list_left_sublist">
                        <h3>Data Type</h3>
                        <ul class="dataType">
                            <li><a class="filter-status" href="javascript:void(0);"
                                   data-action="conversion">Conversion</a></li>
                            <li><a class="filter-status" href="javascript:void(0);" data-action="action">Action</a></li>
                            <li><a class="filter-status" href="javascript:void(0);" data-action="app">App</a></li>
                            <li><a class="filter-status" href="javascript:void(0);" data-action="gamification">Gamification</a>
                            </li>
                        </ul>
                    </div>
                </li>
            </ul>
        </div>
    </div>


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
        }

        .dataTables_filter {
            display: none;
        }

        table.dataTable th:nth-child(1) {
            width: 22% !important;
        }

        table.dataTable th:nth-child(2) {
            width: 25% !important;
        }

        table.dataTable th:nth-child(3) {
            width: 25% !important;
        }

        table.dataTable th:nth-child(4) {
            width: 28% !important;
        }
    </style>

    <div class="db_list_right_sec">
        <div id="MSG">
            @if (Session::has('flash_message'))
                <div class="alert alert-info">{{ Session::get('flash_message') }}</div>
            @endif
        </div>
        <div class="list_table_body scrollbar_content mCustomScrollbar _mCS_1">
            <div class="list_table_header">
                <table cellspacing="0" cellpadding="0" id="otherAttributeDataListing">
                    <thead>
                    <th width="20%">Code</th>
                    <th width="20%">Value</th>
                    <th width="20%">Created Date</th>
                    <th width="20%">Type</th>
                    <th width="10%">Action</th>
                    </thead>
                </table>
            </div>
        </div>
        <div id="campaignOtherDetail">

        </div>
        @stop

        @section('jsSection')
            <script type="text/javascript">
                console.log('baseurl', baseUrl);
                $(document).ready(function () {
                    setTimeout(function () {
                        $('.alert-info').slideUp();
                    }, 4000);
                    $("#dashboard_quick_actionTwo").on('change', function () {
                        var attributes = $(this).val();
                        if (attributes) {
                            getAttributeDataView();
                        }

                    });

                    function getAttributeDataView() {
                        $.ajax({
                            type: 'GET',
                            url: "{{url('/otherAttributedata')}}",
                            success: function (response) {
                                console.log('resp', response);
                                $('#campaignOtherDetail').html(response);
                                $('#campaignOtherAttributedata').modal('show');
                            }, error: function (e) {
                                toastr.error(e);
                            }
                        });

                    }
                });

            </script>
            <script src="{{asset('/assets/js/attributeData/otherAttributeData.js')}}"></script>
@stop
