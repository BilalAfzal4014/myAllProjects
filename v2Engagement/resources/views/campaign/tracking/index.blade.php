@extends('layouts.master')

@section('title', '| Gallery')

@section('searchBar')
    <div class="tp_BreadCrumb_list_sec clearfix">
        <label class="sec_tp_title"><i class="fa fa-users"></i> Sync Data </label>
        <div class="track_userdeta_right clearfix">
            <div class="tp_BreadCrumb_srch_inp">
                <input id="searchBar" type="search" name="search" value="" placeholder="Search...">
            </div>
            {{--<div class="uder_deta_dropdown">--}}

            {{--<div class=" inp_select">--}}
            {{--<select id="dashboard_quick_action">--}}
            {{--<option value=""> Actions</option>--}}
            {{--<option value="{{ route('users.create') }}">Add New User</option>--}}
            {{--</select>--}}
            {{--</div>--}}
            {{--</div>--}}
        </div>
    </div>
@stop
@section('content')
    @include('partials.left-scroll-bar-cache')
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

        .lst_tbl_drop_outer ul {
            width: 125px;
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
                        <th>Row ID</th>
                        <th>Synced (Cache)</th>
                        <th>Action</th>
                    </tr>
                    </thead>
                    @foreach($rows as $row)
                        <tr>
                            <td>{{$row['row_id'] }}</td>
                            <td>Synced (Cache)</td>
                            <td style="width:6%;">
                                <div class="lst_tbl_drop_outer">
                              <span class="">
                                 <img src="{{asset('/assets/images/sett_icon.png')}}" alt="#">
                              </span>
                                    <ul>
                                        <li>
                                            <a onclick="proJob(this, {{ $row['row_id'] }});">
                                                <i class="fa fa-play"></i> Sync
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            </td>
                        </tr>
                    @endforeach
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
@endsection
@section('jsSection')
    <script type="text/javascript">
        var oTable;
        var userTable;
        var finalArray = [];
        {{--var baseUrl = "{{url('/')}}";--}}
        console.log('baseurl', baseUrl);
        $(document).ready(function () {
            userTable = $('#userTable').DataTable({
                "order": [[2, "desc"]],
                "bLengthChange": false,
                "bFilter": true,
                "bInfo": true,
                "bAutoWidth": false
            });
            oTable = $('#userTable').DataTable();
            $('#searchBar').keyup(function () {
                oTable.search($(this).val()).draw();
            });

            $('#company_id').on("change", function () {
                window.location = baseUrl + '/company/cache?company_id=' + $(this).val();
            });
        });

        function proJob(element, id) {
        //    alert('call');
            $(element).attr('disabled', 'disabled').html('<i class="fa fa-2x fa-spin fa-spinner"></i>');
            $.ajax({
                type: "POST",
                url: baseUrl + "/company/cache/store",
                cache: false,
                data: {"company_id": "<?php echo $company_id ?>", "row_id": id},
                headers: {'X-CSRF-TOKEN': "<?php echo csrf_token(); ?>"},
                dataType: 'json',
                success: function (response) {
                    console.log('response', response);
                    if (response.type == 'success') {
                        toastr.success(response.message);
                        window.location.reload();
                    }else{
                        toastr.error(response.message);
                    }
                }, error(e) {
                    console.log(e);
                }
            });
        }
    </script>
@stop
