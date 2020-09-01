@extends('layouts.master')

@section('title', '| Gallery')

@section('searchBar')
    <div class="tp_BreadCrumb_list_sec clearfix">
        <label class="sec_tp_title"> Duplicate Attribute List</label>
        <div class="track_userdeta_right clearfix">
            <div class="tp_BreadCrumb_srch_inp">
                <input id="searchBar" type="search" name="search" value="" placeholder="Search...">
            </div>
            <div class="uder_deta_dropdown">
                <div class=" inp_select">
                    <select id="dashboard_quick_action">
                        <option value=""> Actions</option>
                        <option value="{{ route('duplicates.fix') }}">Fix Duplication</option>
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
                        <th>Row Id</th>
                        <th>Count</th>
                        <th>Created at</th>
                    </tr>
                    </thead>
                    @for($val=0;$val<count($attributeList);$val++)
                        <tr>
                            <td>{{$attributeList[$val]->row_id}}</td>
                            <td>{{$attributeList[$val]->cnt }}</td>
                            <td>{{$attributeList[$val]->created_at }}</td>
                        </tr>
                    @endfor
                </table>
            </div>
        </div>
    </div>
@endsection
@section('jsSection')
    <script type="text/javascript">
        var oTable;
        var userTable;
        var finalArray = [];
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
        });
    </script>
@stop
