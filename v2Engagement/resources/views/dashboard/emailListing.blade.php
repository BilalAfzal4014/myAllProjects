@extends('layouts.master')

@section('title', '| Gallery')

@section('searchBar')
    <div class="tp_BreadCrumb_list_sec clearfix">
        <label class="sec_tp_title"><i class="fa fa-users"></i> Email Listing </label>
        <div class="track_userdeta_right clearfix">
            <div class="tp_BreadCrumb_srch_inp">
                <input id="searchBar" type="search" name="search" value="" placeholder="Search...">
            </div>
            <div class="uder_deta_dropdown">
                <div class="inp_select">
                    <select id="company">
                        <option value=""> Company</option>
                        @for($val=0;$val<count($users);$val++)
                            <option value="{{$users[$val]['id']}}">{{$users[$val]['name']}}</option>
                        @endfor
                    </select>
                </div>
            </div>
        </div>
    </div>
@stop

@section('content')
    @include('partials.left-scroll-bar-emailListing')
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

        #emailListing_filter {
            display: none;
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

        .lst_tbl_drop_outer ul {
            width: 125px;
        }
        .dataTables_wrapper .dataTables_paginate {
            padding-bottom: 60px !important;
        }
    </style>
    <div class="db_list_right_sec">
        <div id="MSG">
            @if (Session::has('flash_message'))
                <div class="alert alert-info">{{ Session::get('flash_message') }}</div>
            @endif
        </div>

        <div>
            <div class="list_table_body scrollbar_content mCustomScrollbar _mCS_1">
                <table cellspacing="0" cellpadding="0" id="emailListing">
                    <thead>
                    <th style="width:10%;">Sr#</th>
                    <th style="width:20%;">Company</th>
                    <th style="width:25%;">Name</th>
                    <th style="width:20%;">Record Type</th>
                    <th style="width:20%;">Created Date</th>
                    <th style="width:10%;">Action</th>
                    </thead>
                </table>

            </div>
        </div>
    </div>
@endsection
@section('jsSection')
    <script type="text/javascript">
        $(document).ready(function () {
            var oTable;
            var campaginStatus;
            var filter = null;
            var filterType = null;
            var companyId=0;
            /*** listing Method Call ***/
            getEmailListing();
            /*** Filter Method Call ***/
            $(".filter-status").on("click", function (e) {

                filter = $(this).attr("data-action");
                filterType = $(this).attr("data-type");
                console.log('filter', filter);
                console.log('companyId', companyId);
                console.log('filterType', filterType);
                $("#emailListing").DataTable().draw();
            });
            $("#company").change(function () {
                filter = $(this).val();
                companyId=filter;
                filterType = 'company_name';
                console.log('filter', filter);
                console.log('companyId', companyId);
                console.log('filterType', filterType);
                $("#emailListing").DataTable().draw();
            });
            /*** Event Method Call***/
            events();

            /*** Filter Method Call ***/
            function getEmailListing() {
                var url = baseUrl + '/email/listing/fetch';
                $('#emailListing').DataTable({
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
                            data.companyId = companyId;
                            //        console.log('filterData', data);
                        },
                    },
                    "columns": [
                        {
                            "data": "id",
                            "render": $.fn.dataTable.render.text()
                        },
                        {
                            "data": "username",
                            "render": $.fn.dataTable.render.text()
                        },
                        {
                            "data": "email",
                            "render": $.fn.dataTable.render.text()
                        },
                        {
                            "data": "rec_type",
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
                            "aTargets": [5],
                            "mData": "action",
                            "mRender": function (data, type, full) {
                                //   console.log('data', full);
                                if (full.rec_type == 'blacklist') {
                                    campaginStatus = '<a href="#"><img src="' + baseUrl + '/assets/images/tick.PNG' + '" alt="#">Whitelist</a>';
                                } else {
                                    campaginStatus = '<a href="#"><img src="' + baseUrl + '/assets/images/Cross.PNG' + '" alt="#">Blacklist</a>';
                                }
                                return '<div class="lst_tbl_drop_outer">' +
                                    '<span class=""> <img src="' + baseUrl + '/assets/images/sett_icon.png' + '"  alt="#"> </span>' +
                                    '<ul>' +
                                    '<li id="' + full.rec_type + '"  data-action="' + full.id + '" >' + campaginStatus +
                                    '</li>' +
                                    '</ul>' +
                                    '</div>' +
                                    '</td>';
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
                        var description = $("#emailListing_info").text();
                        $("#emailListing_info").text("");
                        $(".listing_sec_ftr_detail p").text(description);
                    }

                });
                oTable = $('#emailListing').DataTable();
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
                    $.ajax({
                        type: "get",
                        url: baseUrl + '/email/change/' + id + '/' + mode,
                        cache: false,
                        dataType: 'json',
                        success: function (response) {
                            //  console.log('response', response);
                            toastr.success(response.message);
                            oTable.ajax.reload()

                        }
                    });

                });
            }

        });
    </script>
@stop