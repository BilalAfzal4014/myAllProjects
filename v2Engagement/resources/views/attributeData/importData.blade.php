@extends('layouts.master')

@section('searchBar')



    <div class="tp_BreadCrumb_list_sec clearfix">
        <label class="sec_tp_title"> Data Import </label>
        <div class="track_userdeta_right clearfix">
            <div class="tp_BreadCrumb_srch_inp">
                <input id="searchBar" type="search" name="" value="" placeholder="Search...">
            </div>

            <div class="uder_deta_dropdown">
                <div class=" inp_select">
                    <select class="uploadPage" onchange="uploadPage()">
                        {{--value="app_message"--}}
                        <option value="" >Actions</option>
                        <option value="UPLOAD">Upload</option>
                    </select>
                </div>
            </div>

        </div>

    </div>
@stop


@section('content')
    @include('partials.left-scroll-bar-data-import')

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

        table.dataTable tbody td:nth-child(3),
        table.dataTable tbody td:nth-child(5) {
            padding: 8px 10px;
            word-break: break-all;
        }
        .lst_tbl_drop_outer ul{
            width: 109px;
        }

    </style>

    <div class="db_list_right_sec">
        <div class="MSG">
            {!! Session::get('MSG') !!}
        </div>
        <div class="list_table_body scrollbar_content mCustomScrollbar _mCS_1 new_content_scroll">
            <div class="list_table_header">
                <table cellspacing="0" cellpadding="0" id="importDataListing">
                    <thead>
                        <th>Id</th>
                        <th>Name</th>
                        <th>File Size</th>
                        <th>Upload Date</th>
                        <th>Status</th>
                        <th>Remaining Files</th>
                        <th>process Date</th>
                        <th style="width: 50px;"></th>
                    </thead>
                </table>
            </div>
        </div>
        @stop

@section('jsSection')
    <script>
        route = '{{route("importFileListing")}}'
    </script>
<script src="{{asset('/assets/js/attributeData/importData.js')}}"></script>
<script>
var table

function downloadADFile(){
    $uploadPage = $('.uploadPage').val();
    if( $uploadPage == 'UPLOAD' ){

        window.location.href="{{route('downloadADFile')}}";
    }
}

$('.db_list_right_sec').on('click','li[data-action="download"]', function(){
    $id = $(this).attr('id');
    window.location.href="{{route('downloadADFile')}}/"+$id;
})


function uploadPage(){
    $uploadPage = $('.uploadPage').val();
    if( $uploadPage == 'UPLOAD' ){

        window.location.href='{{route('importAttributeDataView')}}';
    }
}

$('.db_list_right_sec').on('click','li[data-action="delete"]', function(){

    swal({
        title: "Are you sure?",
        icon: "warning",
        buttons: true,
        dangerMode: true,
    }).then((willDelete) => {
        if (!willDelete) {
            return false;
        }else{

            $id = $(this).attr('id');
            $.ajax({
                url: "{{ route('importFileDelete') }}",
                type: "POST",
                data: 'id=' + $id,
                success: function (res) {
                    window.location.href = '{{route("importFileView")}}';
                },
            });
        }
    });

})


$('.db_list_right_sec').on('click','li[data-action="importFile"]', function(){
    $id = $(this).attr('id');
    $(".loading_popup_outer_ajax").fadeIn('slow');
    var popUpCheck = 1;
    $.ajax({
        url: "{{ route('importTargetedUsers') }}",
        type: "POST",
        async:true,
        global:false,
        data: 'id='+$id,
        success: function (res) {

            toastr.success(res);
            table.draw();
            $('.attributeDataExist').hide();
            $(".loading_popup_outer_ajax").fadeOut('slow');
            popUpCheck = 0;
        },
        error: function(res){

            toastr.error(res.responseText);
            popUpCheck = 0;
            $(".loading_popup_outer_ajax").fadeOut('slow');
            // HTML = '<div class="alert alert-danger">\n' +
            //     '<strong>Warning!</strong>'+res.responseText+'</div>'
            // $(".MSG").html(HTML);
        }
    });

    if(popUpCheck === 1) {
        toastr.success('Please wait your import request is under process');
    }
});
$(document).ready(function (e) {
    function getDataImportListingAjax(filter,filterType) {
        var url = baseUrl + '/import-data/import-file-view-filter?operation='+filter;
        table = $("#importDataListing").DataTable({
            "order": [[2, "desc"]],
            "bLengthChange": false,
            "bFilter": true,
            "bInfo": false,
            "searching": false,
            "bAutoWidth": false,
            "ajax": url,
            "aoColumnDefs": [
                {
                    "targets": [0],
                    "visible": false,
                    "searchable": false,
                    "orderable": false
                }
            ],
        });


    }
    function filterDataImportListing(filter, filterType, otable=table)
    {
        otable.destroy();
        getDataImportListingAjax(filter, filterType)
    }

    $(".filter-status").on("click",function (e) {

        var operation = $(this).attr("data-action");
        filterDataImportListing(operation,'status');
    });
})
</script>
@stop
