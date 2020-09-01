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
@extends('layouts.master')
@section('searchBar')
    <div class="tp_BreadCrumb_list_sec clearfix">
        <label class="sec_tp_title"> Attribute</label>
        <div class="track_userdeta_right clearfix">
            <div class="tp_BreadCrumb_srch_inp">
                <input id="searchBar" type="search" name="" value="" placeholder="Search...">
            </div>
            <div class="uder_deta_dropdown">
                <div class="inp_select">
                    <select id="dashboard_quick_actionTwo">
                        <option value=""> Actions</option>
                        <option value="1">Create Attribute</option>
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
{{--    @include('partials.left-scroll-bar-attributes')--}}
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
                    <th width="10%">Sr#</th>
                    <th width="20%">Code</th>
                    <th width="20%">Name</th>
                    <th width="20%">Data Type</th>
                    <th width="20%">Length</th>
                    <th width="20%">Type</th>
                    <th width="10%">Action</th>
                    </thead>
                </table>
            </div>
        </div>
        <div id="campaignOtherDetail">

        </div>
    </div>
@stop
@section('jsSection')
    <script type="text/javascript">
        console.log('baseurl', baseUrl);
        var filter = null;
        var filterType = null;
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
                    url: "{{url('/create/attribute')}}",
                    success: function (response) {
                        console.log('resp', response);
                        $('#campaignOtherDetail').html(response);
                        $('#campaignOtherAttributedata').modal('show');
                    }, error: function (e) {
                        toastr.error(e);
                    }
                });

            }

            var oTable;


            $(".filter").on("click", function (e) {
                filter = $(this).attr("data-action");
                filterType = $(this).attr("data-type");
                console.log('filter', filter);
                console.log('filterType', filterType);
                $("#otherAttributeDataListing").DataTable().draw();
            });

            function otherAttributeDataRequest() {

                oTable = $('#otherAttributeDataListing').DataTable({
                    "processing": true,
                    "serverSide": true,
                    "searching": true,
                    "bLengthChange": false,
                    "iDisplayLength": 25,
                    "ajax": {
                        "url": baseUrl + '/attribute/listing',
                        "dataType": "json",
                        "type": "GET",
                        "data": function (data) {
                            data.filter = filter;
                            data.filterType = filterType;
                        },
                    },
                    "columns": [
                        {
                            "data": "id"
                        },
                        {
                            "data": "code",
                            "render": $.fn.dataTable.render.text()
                        },
                        {
                            "data": "name",
                            "render": $.fn.dataTable.render.text()
                        },
                        {
                            "data": "data_type",
                            "render": $.fn.dataTable.render.text()
                        },
                        {
                            "data": "length",
                            "render": $.fn.dataTable.render.text()
                        },
                        {
                            "data": "type",
                            "render": $.fn.dataTable.render.text()
                        },
                        {"data": "action"}
                    ],
                    "aoColumnDefs": [
                        {
                            "aTargets": [6],
                            "mData": "action",
                            "mRender": function (data, type, full) {
                                console.log('full', full);
                                return '<div class="lst_tbl_drop_outer">' +
                                    '<span class=""> ' +
                                    '<img src="' + baseUrl + '/assets/images/sett_icon.png' + '"  alt="#"> </span>' +
                                    ' <ul>' +
                                    ' <li id="' + full.id + '"  data-action="edit" ><a href="#"> <img src="' + baseUrl + '/assets/images/edit_icon.png' + '" alt="#">Edit</a></li> ' +
                                    ' <li id="' + full.id + '" data-action="delete"><a  href="#" ><img src="' + baseUrl + '/assets/images/del_icon.png' + '" alt="#">  Delete</a></li></ul></div></td>';
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
                        // //call after every event cause change in datatable
                        // var description = $("#attributeDataListing_info").text();
                        // $("#attributeDataListing_info").text("");
                        // $(".listing_sec_ftr_detail p").text(description);
                    }
                });
            }


            $('#searchBar').on("change", function () {
                oTable.search($(this).val()).draw();
            });

            otherAttributeDataRequest();

            $(document).on('click', '.lst_tbl_drop_outer ul li', function () {
                var dataAction = $(this).attr('data-action');
                var id = $(this).attr('id');
                console.log('dataAction', dataAction);
                console.log('id', id);
                if (dataAction == 'delete') {
                    swal({
                        title: "Are you sure want to delete this?",
                        icon: "warning",
                        buttons: true,
                        dangerMode: true,
                    }).then((willDelete) => {
                        if (!willDelete) {
                            return false;
                        } else {
                            $.ajax({
                                type: 'GET',
                                url: baseUrl + "/delete/attributes/" + id,
                                success: function (response) {
                                    if (response.status == 200) {
                                        oTable.ajax.reload()
                                        toastr.success(response.message);
                                    } else {
                                        toastr.error(response.message);
                                    }

                                }, error: function (e) {
                                    toastr.error(e);
                                }
                            });
                        }
                    });
                } else {
                    $.ajax({
                        type: 'GET',
                        url: baseUrl + "/edit/attributes/" + id,
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
        });
    </script>

@stop
