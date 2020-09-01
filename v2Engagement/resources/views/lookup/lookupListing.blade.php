@extends('layouts.master')

@section('searchBar')
    <div class="tp_BreadCrumb_list_sec clearfix">
        <label class="sec_tp_title"> Lookups </label>
        <div class="track_userdeta_right clearfix">
            <div class="tp_BreadCrumb_srch_inp">
                <input id="searchBar" type="search" name="" value="" placeholder="Search...">
            </div>

            <div class="uder_deta_dropdown">
                <div class=" inp_select">
                    <select id="dashboard_quick_actionone">
                        <option value=""> Actions</option>
                        <option value="1">Add Lookup</option>
                    </select>
                </div>


            </div>

        </div>

    </div>
@stop

@section('content')
    @include('lookup.left-scroll-bar')
    <style>
        /*tags styling*/
        .bootstrap-tagsinput {
            margin-bottom: 10px !important;
        }
        .dataTables_wrapper {
            padding-bottom: 50px !important;
        }

        /* datatable styling */
        table.dataTable tbody tr {
            background-color: #ffffff;
            height: 49px !important;
        }

        #lookupListing td:first-child {
            text-align: left;
            padding: 13px 20px !important;
        }

        #lookupListing_filter label {
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
        @if($error)
            <div class="alert alert-warning">
                <strong>Warning!</strong> {{$error}}
            </div>@endif
        <input class="companyId" type="hidden" value="{{$companyId}}">
        <div class="list_table_body scrollbar_content mCustomScrollbar _mCS_1">
            <div class="list_table_header">
                <table cellspacing="0" cellpadding="0" id="lookupListing">
                    <thead>
                    <th style="width:20%;">Code</th>
                    <th style="width:25%;">Name</th>
                    <th style="width:30%;">Parent Code</th>
                    <th style="width:30%;">Created At</th>
                    <th style="width:7%;"></th>
                    </thead>
                </table>
            </div>
        </div>
    </div>
    <div id="campaignOtherDetail">

    </div>
@stop


@section('jsSection')
    <script type="text/javascript">
        var userid="{{Auth::user()->name}}";
    </script>
    <script type="text/javascript">
        console.log('baseurl',baseUrl);
        $(document).ready(function () {
            $("#dashboard_quick_actionone").on('change', function () {

                if ($(this).val() == 1) {
                    window.location = "{{url('/lookup/create')}}";
                    console.log('val', $(this).val());
                } else {
                   // getAttributeDataView();
                }
            });

            function getAttributeDataView() {
                $.ajax({
                    type: 'GET',
                    url: "/otherAttributedata",
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
     <script src="{{asset('/assets/js/lookup/lookupListing.js')}}"></script>
@stop
