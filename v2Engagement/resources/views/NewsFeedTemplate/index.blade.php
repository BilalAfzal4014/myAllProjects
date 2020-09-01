@extends('layouts.master')

@section('title', '| Gallery')

@section('searchBar')
    <div class="tp_BreadCrumb_list_sec clearfix">
        <label class="sec_tp_title"> NewsFeed </label>
        <div class="track_userdeta_right clearfix">
            <div class="tp_BreadCrumb_srch_inp">
                <input id="searchBar" type="search" name="search" value="" placeholder="Search...">
            </div>
            <div class="uder_deta_dropdown">
                <div class=" inp_select">
                    <select id="dashboard_quick_action">
                        <option value=""> Actions</option>
                        <option value="{{ route('newsFeedTemplates.create') }}">Add New User</option>
                    </select>
                </div>
            </div>
        </div>
    </div>
@stop

@section('content')
    @include('partials.left-scroll-bar')
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
                        <th>Sr#</th>
                        <th>Name</th>
                        <th>Created date</th>
                        <th style="width:6%;"></th>
                    </tr>
                    </thead>
                    @foreach ($newFeedTemplate as $newFeedTemplates)
                        <tr>
                            <td>{{ $newFeedTemplates->id }}</td>
                            <td>{{ $newFeedTemplates->name }}</td>
                            <td>{{ $newFeedTemplates->created_at->format('F d, Y h:ia') }}</td>
                            <td style="width:6%;">
                                <div class="lst_tbl_drop_outer">
                              <span class="">
                                 <img src="{{asset('/assets/images/sett_icon.png')}}" alt="#">
                              </span>
                                    <ul>
                                        <li onclick="window.location.href = '{{ url('newFeedTemplates/edit', $newFeedTemplates->id) }}';">
                                            <a href="{{ url('newFeedTemplates/edit/', $newFeedTemplates->id) }}"> <img
                                                        src="{{asset('/assets/images/edit_icon.png')}}" alt=""> Edit
                                            </a>
                                        </li>
                                        <li onclick="window.location.href = '{{ url('newFeedTemplatesStatus', ['id'=>$newFeedTemplates->id,'is_active'=>$newFeedTemplates->is_active]) }}';">
                                            <a href="{{ url('newFeedTemplatesStatus', ['id'=>$newFeedTemplates->id,'is_active'=>$newFeedTemplates->is_active]) }}">
                                                <img src="{{asset('/assets/images/del_icon.png')}}"
                                                     alt=""> {{ ($newFeedTemplates->is_active ==1 ) ? 'Inactive':'Active' }}
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
@endsection
@section('jsSection')
    <script>
        $(document).ready(function () {
            $(".lst_tbl_drop_outer ul li a").on("click", function () {

                swal({
                    title: "Are you sure?",
                    icon: "warning",
                    buttons: true,
                    dangerMode: true,
                }).then((willDelete) => {
                    if (!willDelete) {
                        return false;
                    } else {
                        window.location = $(this).attr("href");
                        // $(this).
                    }
                });
            });
            var userTable = $('#userTable').DataTable({
                "order": [[0, "desc"]],
                "bLengthChange": false,
                "bFilter": true,
                "bInfo": true,
                "bAutoWidth": false,
                columnDefs: [
                    {"width": "10px", "targets": [0]},
                    {"width": "50px", "targets": [1]},
                    {"width": "50px", "targets": [2]},
                    {"width": "20px", "targets": [3]},
                ]
            });


            var oTable = $('#userTable').DataTable();
            $('#searchBar').keyup(function () {
                oTable.search($(this).val()).draw();
            });

            function getUserListing(filter, filterType) {
                var url = baseUrl + '/newsFeedTemplates/listing/' + filter;
                console.log('url', url);
                oTable = $("#userTable").DataTable({
                    "order": [[0, "desc"]],
                    "bLengthChange": false,
                    "bFilter": true,
                    "bInfo": false,
                    "bAutoWidth": false,
                    "ajax": url
                });


            }

            function filterUserListing(filter, filterType, otable = oTable) {
                otable.destroy();
                getUserListing(filter, filterType)
            }

            $(".filter-status").on("click", function (e) {
                // alert('call');
                var operation = $(this).attr("data-action");
                filterUserListing(operation, 'is_active');
            });


        });
    </script>
@stop