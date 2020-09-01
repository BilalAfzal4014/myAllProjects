@extends('layouts.master')

@section('title', '| Gallery')

@section('searchBar')
    <div class="tp_BreadCrumb_list_sec clearfix">
        <label class="sec_tp_title">Campaign Queue </label>
        <div class="track_userdeta_right clearfix">
            <div class="tp_BreadCrumb_srch_inp">
                <input id="searchBar" type="search" name="search" value="" placeholder="Search...">
            </div>
            {{--<div class="uder_deta_dropdown">--}}
                {{--<div class=" inp_select">--}}
                    {{--<select id="dashboard_quick_action">--}}
                        {{--<option value=""> Actions</option>--}}
                        {{--<option value="{{url('/jobs')}}">Queue List</option>--}}
                    {{--</select>--}}
                {{--</div>--}}
                {{--<div>--}}
                    {{--<a href="{{url('/jobs')}}" class="btn btn-primary" style="width: 100px;display: block;margin:0 auto;">Queue</a>--}}
                {{--</div>--}}
            {{--</div>--}}
        </div>
    </div>
@stop
@section('content')
    @include('partials.left-scroll-bar-campaignQueue')
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

        .dataTables_wrapper {
            padding-bottom: 50px !important;
        }
    </style>
    <div class="db_list_right_sec">
        @if (Session::has('flash_message'))
            <div class="alert alert-info">{{ Session::get('flash_message') }}</div>
        @endif
        <div id="MSG"></div>
        <div>
            <div class="list_table_body scrollbar_content mCustomScrollbar _mCS_1">
                <table cellspacing="0" cellpadding="0" padding-right:10px; id="segmentListing">
                    <thead>
                    <tr>
                        <th style="width:10%;">ID</th>
                        <th style="width:20%;">Campaign ID</th>
                        <th style="width:20%;">Company Name</th>
                        <th style="width:20%;">Status</th>
                        <th style="width:50%;">Detail</th>
                        <th style="width:20%;">Error Message</th>
                        <th style="width:20%;">Created At</th>
                        <th style="width:20%;">Action</th>
                    </tr>
                </table>
            </div>
        </div>
    </div>
    <div id="jobModal" class="modal fade" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-body">
                    <p>One fine body&hellip;</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
    <div id="campaignDetail">

    </div>
@endsection

@section('jsSection')
    <script src="https://cdn.datatables.net/buttons/1.5.2/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.5.2/js/buttons.flash.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.5.2/js/buttons.html5.min.js"></script>
    <script type="text/javascript">
        $(document).ready(function () {
            var oTable;
            var campaginStatus;
            var filter = null;
            var filterType = null;
            getSegmentListing();
            events();
            $(".filter").on("click", function (e) {

                filter = $(this).attr("data-action");
                filterType = $(this).attr("data-type");
                $("#segmentListing").DataTable().draw();
            });

            function getSegmentListing() {
                var url = baseUrl + '/campaignQueueFilter';
                $('#segmentListing').DataTable({
                    "processing": true,
                    "serverSide": true,
                    "searching": true,
                    "bLengthChange": false,
                    "iDisplayLength": listingSize,
                    "ajax": {
                        "url": url,
                        "dataType": "json",
                        "type": "GET",
                        "data": function (data) {
                            data.filter = filter;
                            data.filterType = filterType;
                         //   console.log('data', data);
                        },
                    },
                    "columns": [
                        {
                            "data": "id",
                            "render": $.fn.dataTable.render.text()
                        },
                        {
                            "data": "campaign_id",
                            "render": $.fn.dataTable.render.text()
                        },
                        {
                            "data": "name"
                        },
                        {
                            "data": "status",
                            "render": $.fn.dataTable.render.text()
                        },
                        {
                            "data": "details",
                            "render": $.fn.dataTable.render.text()
                        },
                        {
                            "data": "error_message",
                            "render": $.fn.dataTable.render.text()
                        },
                        {
                            "data": "created_at",
                            "render": $.fn.dataTable.render.text()
                        },
                        {"data": "action"},
                    ],
                    "aoColumnDefs": [
                        {
                            "aTargets": [7],
                            "mData": "action",
                            "mRender": function (data, type, full) {
                                if (full.status == 'Available' || full.status == 'Processing') {
                                    campaginStatus = '<i class="fa fa-play"></i>Execute';
                                } else {
                                    campaginStatus = '<i class="fa fa-bookmark"></i>Set to Available';
                                }
                           //     console.log('data', full);
                                return '<div class="lst_tbl_drop_outer">' +
                                    '<span class=""> <img src="' + baseUrl + '/assets/images/sett_icon.png' + '"  alt="#"> </span>' +
                                    '<ul>' +
                                    '<li id="' + full.id + '"  data-action="updateCampaignStatus" >' +
                                    '<a href="#">' + campaginStatus + '</a>' +
                                    '</li>' +
                                    '<li id="' + full.id + '"  data-action="deleteCampaignQueue">' +
                                    '<a href="#">' +
                                    '<img src="' + baseUrl + '/assets/images/del_icon.png' + '" alt="#"> Remove</a>' +
                                    '</li>' +
                                    '<li id="' + full.campaign_id + '"  data-action="campaign/trackinglist"><a href="#">' +
                                    '<img src="' + baseUrl + '/assets/images/view_icon.png' + '" alt="#"> Tracking</a>' +
                                    '</li></ul></div></td>';
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
                        var description = $("#segmentListing_info").text();
                        $("#segmentListing_info").text("");
                        $(".listing_sec_ftr_detail p").text(description);
                    }

                });
                oTable = $('#segmentListing').DataTable();
                $('#searchBar').on("change", function () {
                    oTable.search($(this).val()).draw();
                });
            }

            function events() {
                $(document).on('click', '.lst_tbl_drop_outer ul li', function () {
                    mode = $(this).attr('data-action');
                    id = $(this).attr('id');
                    console.log('id', id);
                    console.log('node', mode);
                    // if(mode=='updateCampaignStatus')
                    if (mode == 'updateCampaignStatus') {
                        $.ajax({
                            type: "get",
                            url: baseUrl + '/' + mode + '/' + id,
                            cache: false,
                            dataType: 'json',
                            success: function (response) {
                                console.log('response', response);
                                if (response.status) {
                                    toastr.success(response.log);
                                    oTable.ajax.reload();
                                } else {
                                    toastr.error(response.log);
                                }
                            }
                        });
                    } else if (mode == 'deleteCampaignQueue') {
                        $.ajax({
                            type: "get",
                            url: baseUrl + '/' + mode + '/' + id,
                            cache: false,
                            dataType: 'json',
                            success: function (response) {
                                console.log('response', response);
                                if (response.status == "success") {
                                    toastr.success(response.data);
                                    oTable.ajax.reload();
                                } else {
                                    toastr.error(response.data);
                                }

                            }
                        });
                    } else {
                        $.ajax({
                            url: baseUrl + '/' + mode + '/' + id,
                            type: "GET",
                            success: function (response) {
                                console.log('resp', response);
                                $('#campaignDetail').html(response);
                                $('#campaignModal').modal('show');
                            }, error: function (error) {
                                console.log('error', error);
                            }
                        });

                    }
                });
            }
        });

    </script>
@stop