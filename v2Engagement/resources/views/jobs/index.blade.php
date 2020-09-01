@extends('layouts.master')

@section('title', '| Gallery')

@section('searchBar')
    <div class="tp_BreadCrumb_list_sec clearfix">
        <label class="sec_tp_title"> Jobs </label>
        <div class="track_userdeta_right clearfix">
            <div class="tp_BreadCrumb_srch_inp">
                <input id="searchBar" type="search" name="search" value="" placeholder="Search...">
            </div>
            <div class="uder_deta_dropdown">
                <div class=" inp_select">
                    <select id="dashboard_quick_action">
                        <option value=""> Actions</option>
                        <option value="{{url('/campaignQueue')}}">Campaign Queue List</option>
                    </select>
                </div>
            </div>
        </div>
    </div>
@stop
@section('content')
    <style>
        /* datatable styling */
        table.dataTable tbody tr {
            background-color: #ffffff;
            height: 49px !important;
        }

        #userTable td:first-child {
            text-align: left;
            padding: 13px 20px !important;
        }

        #userTable_filter label {
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
                <table cellspacing="0" cellpadding="0" padding-right:10px; id="userTable">
                    <thead>
                    <tr>
                        <th style="width:20%;">ID</th>
                        <th style="width:20%;">Method</th>
                        <th style="width:50%;">Data</th>
                        <th style="width:20%;">Status</th>
                        <th style="width:50%;">Error Message</th>
                        <th style="width:20%;">Created At</th>
                        <th style="width:20%;">Action</th>
                    </tr>
                    </thead>

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
                $("#userTable").DataTable().draw();
            });

            function getSegmentListing() {
                var url = baseUrl + '/queueJobFilter';
                $('#userTable').DataTable({
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
                            console.log('data', data);
                        },
                    },
                    "columns": [
                        {
                            "data": "id",
                            "render": $.fn.dataTable.render.text()
                        },
                        {
                            "data": "method",
                            "render": $.fn.dataTable.render.text()
                        },
                        {
                            "data": "data"
                        },
                        {
                            "data": "status",
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
                            "aTargets": [6],
                            "mData": "action",
                            "mRender": function (data, type, full) {
                                if (full.status == 'Available' || full.status == 'Processing') {
                                    campaginStatus = '<i class="fa fa-play"></i>Execute';
                                } else {
                                    campaginStatus = '<i class="fa fa-bookmark"></i>Set to Available';
                                }
                                console.log('data', full);
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
                                    '<li id="' + full.campaignId + '"  data-action="campaign/trackinglist"><a href="#">' +
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
                        var description = $("#userTable_info").text();
                        $("#userTable_info").text("");
                        $(".listing_sec_ftr_detail p").text(description);
                    }

                });
                oTable = $('#userTable').DataTable();
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
                            type: "GET",
                            url: baseUrl + "/jobs/" + id,
                            cache: false,
                            dataType: 'json',
                            success: function (response) {
                                console.log('response', response);
                                if (response.status) {
                                    toastr.success(response.log);
                                    oTable.ajax.reload()
                                } else {
                                    toastr.error(response.log);
                                }
                            }
                        });
                    } else if (mode == 'deleteCampaignQueue') {
                        $.ajax({
                            type: "DELETE",
                            url: baseUrl + "/jobs/" + id,
                            cache: false,
                            dataType: 'json',
                            success: function (response) {
                                console.log('response', response);
                                if (response.status == "success") {
                                    toastr.success(response.data);
                                    oTable.ajax.reload()
                                } else {
                                    toastr.error(response.data);
                                }
                                //;
                                // if (response.status == 'success') {
                                //     window.location.reload();
                                // }
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


