@extends('layouts.master')

@section('title', '| Gallery')

@section('searchBar')
    <div class="tp_BreadCrumb_list_sec clearfix">
        <label class="sec_tp_title"> Gallery </label>
        <div class="track_userdeta_right clearfix">
            <div class="tp_BreadCrumb_srch_inp">
                <input id="searchBar" type="search" name="search" value="" placeholder="Search...">
            </div>
            <div class="uder_deta_dropdown" style="height: 62px;">
                <div class="dropdown">
                    <a href="#" class="dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        Actions
                    </a>
                    <ul class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenu1">
                        <li>

                            <div class="file_select">
                                <input class="inputfile" id="i_file" type="file" name="userfile" size="20"
                                       style="width: 175px;">
                                <label for="i_file">Upload Image</label>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
@stop

@section('content')
{{--@include('partials.left-scroll-bar')--}}
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

        #gallery_filter label {
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
        tfoot{
            display: none;
        }
        .dropdown-menu{
            padding: 0px;
        }
        th.next, th.prev, td.day{
            cursor: pointer;
        }
        td.old, td.new{
            color: #ccc!important;
            background-color: #eee!important;
            cursor: not-allowed!important;
        }
        td.active{
            background: #ebedf2;
        }
        .db_list_right_sec table th, table td{
            text-align: left !important;
        }

        a.dropdown-toggle {
            border-radius: 6px;
            width: 100%;
            padding: 3px 22px 3px 10px;
            line-height: 30px;
            color: #666;
            font-size: 15px;
            font-weight: 700;
            background: 0 0;
            display: block;
            position: relative;
        }
        a.dropdown-toggle:after {
            margin-top: 0;
            position: absolute;
            content:'';
            right:11px;
            margin-left: 6px;
            top:50%;
            vertical-align: middle;
            border-top: 6px solid #2a8689;
            border-right: 6px solid transparent;
            border-left: 6px solid transparent;
        }
        .dropdown{ width:14%; }
    </style>

<div class="db_list_right_sec">
@if (Session::has('flash_message'))
<div class="alert alert-info">{{ Session::get('flash_message') }}</div>
@endif
<div id="MSG"></div>
    <div class="list_table_body list_table_header scrollbar_content mCustomScrollbar _mCS_1" >
     <table cellspacing="0" cellpadding="0" padding-right:10px; id="gallery">
     <thead class="">
       <tr>
                            <th style="width:30%;">Image</th>
                            <th style="width:20%;">Name</th>
                            <th style="width:20%;">Dimensions</th>
                            <th style="width:20%;">Size</th>
                            <th style="width:30%;">Date/Time Added</th>
                            <th style="width:10%;"> </th>
       </tr>
    </thead>

      </table>
 </div>

</div>
@endsection

@section('jsSection')
    <script src="{{asset('/assets/js/gallery/gallery.js')}}"></script>
<script>
$(document).ready( function () {

    $('#i_file').on('change', photoUpload);
    } );

function newsImage(id){



    swal({
        title: "Are you sure?",
        icon: "warning",
        buttons: true,
        dangerMode: true,
    }).then((willDelete) => {
        if (!willDelete) {
            return false;
        }else{

            $.ajax({
                type: "POST",
                data: 'id='+ id + '&_token={{ csrf_token() }}',
                url: "{{ route('gallery.destroy') }}",
                success: function (data) {
                    $("#"+id).addClass('hide');
                    $('#gallery').DataTable().draw();
                    return;
                },
                error: function (errorMsg) {


                }
            })
        }
    });


}


function photoUpload(event) {
           event.preventDefault()
             //get selected file
            files = event.target.files;
            if (files.length == 0) {
               $("#MSG").html("imageError");

                return;
            }
            //form data check the above bullet for what it is
            var data = new FormData();

             //file data is presented as an array
        for (var i = 0; i < files.length; i++) {
        var file = files[i];
        if(!file.type.match('image.*')) {
            //check file type
            $("#MSG").html('<div class="alert alert-warning">Please choose an images file.</div>');
        }else if(file.size > 1048576){
            //check file size (in bytes)
            $("#MSG").html('<div class="alert alert-warning">Sorry, your file is too large (>1 MB)</div>');
        }else{
            //append the uploadable file to FormData object
            data.append('file', file, file.name);
            data.append('_token', '{{ csrf_token() }}');
            //create a new XMLHttpRequest
            var xhr = new XMLHttpRequest();

                //post file data for upload
                xhr.open('POST', '{{ route("doUpload") }}', true);
                xhr.send(data);
                xhr.onload = function () {

                //get response and show the uploading status
                var response = JSON.parse(xhr.responseText);
                if(response.status == 200){
                    $("#MSG").html('<div class="alert alert-success">File has been uploaded successfully. Click to upload another.</div>');
                    location.reload();
                }else if(response.status == 'type_err'){
                    $("#MSG").html('<div class="alert alert-danger">Please choose an images file. Click to upload another.</div>');
                }else{
                    $("#MSG").html('<div class="alert alert-danger">Some problem occured, please try again.</div>');
                }
            };
        }
    }
        }
</script>
@stop
