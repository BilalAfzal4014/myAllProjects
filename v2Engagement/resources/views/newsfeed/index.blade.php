@extends('layouts.master')

@section('searchBar')
    <div class="tp_BreadCrumb_list_sec clearfix">
        <label class="sec_tp_title"> News Feed </label>
        <div class="track_userdeta_right clearfix">
            <div class="tp_BreadCrumb_srch_inp">
                <input id="searchBar" type="search" name="search" value="" placeholder="Search...">
            </div>
            <div class="uder_deta_dropdown">

                <div class=" inp_select">
                    <select id="dashboard_quick_action">
                        <option value=""> Actions</option>
                        <option value="{{ route('newsfeedcreate') }}">Add Newsfeed</option>
                    </select>
                </div>
            </div>
        </div>
    </div>
@stop


@section('newFeed_static')

@endsection



@section('create')

@stop

@section('content')



    @include('newsfeed.left-scroll-bar')

    <style>
        .launch {
            border-left: 5px solid greenyellow;
        }

        .draft {
            border-left: 5px solid blue;
        }

        .suspend {
            border-left: 5px solid red;
        }

        .expired {
            border-left: 5px solid black;
        }
    </style>
    <div class="db_list_right_sec">

        <div class="modal language_newsfeed" id="language_newsfeed" tabindex="-1" role="dialog" >
            <div class="modal-dialog" role="document" style="width: 88%">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Edit Arabic</h5>
                    </div>
                    <div class="modal-body">

                        <form  id="formArabic" action="{{ route('updateLanguageNewsfeed') }}" method="post">
                            <input type="hidden" name="_token" id="tokenForUpdatingLanguage" value="{{ csrf_token() }}"/>
                            <input type="hidden" id="neesfeedId" name="neesfeedId" value=""/>
                            <input type="hidden" id="newsfeedTraslationId" name="newsfeedTraslationId" value=""/>
                            <div class="form-group">
                                <label for="title">Title</label>
                                <input style="direction: rtl;" name="title" type="text" required class="form-control" id=title"   placeholder="أدخل العنوان">
                            </div>
                            <div class="form-group">
                                <label for="description">Description</label>
                                <textarea style="direction: rtl;" name="description"  class="form-control" required id="description" rows="5" placeholder="أدخل الوصف"></textarea>
                            </div>
                            <div class="form-group">
                                <button type="submit" id="submitForm" class="btn btn-primary">Submit</button>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">

                        <button type="button" class="btn btn-secondary" data-dismiss="modal"><i class="fa fa-times" aria-hidden="true"></i></button>
                    </div>
                </div>
            </div>
        </div>
        <input type="hidden" name="activeFilter" id="activeFilter" value="2">

        <div class="list_table_body new_content_scroll scrollbar_content mCustomScrollbar _mCS_1">
            <div class="list_table_header">
                <table cellspacing="0" cellpadding="0" id="newsListing" style="border-collapse: collapse;">
                    <thead>
                    <th>Id</th>
                    <th style="width:35%;">Name</th>
                    <th style="width:25%;">Location</th>
                    <th style="width:25%;">Segment</th>
                    <th style="width:15%;">Start Date</th>
                    <th style="width:15%;">End Date</th>
                    <th style="width:10%;">Status</th>
                    <th style="width:7%;"></th>
                    </thead>
                </table>
            </div>
        </div>


        <div class="db_list_right_sec hide">
            <div class="list_table_header">

            </div>
        </div>
        @stop

        @section('jsSection')
            <script src="{{asset('/assets/js/newsFeed/newsFeed.js')}}"></script>

            <script>


                function handleEndTime(checkbox) {
                    if (checkbox.checked == true) {
                        $('#end_time_dev').removeClass('hide');

                    } else {
                        $('#end_time_dev').addClass('hide');
                    }
                }

                function newsDelete(id) {

                    swal({
                        title: "Are you sure?",
                        icon: "warning",
                        buttons: true,
                        dangerMode: true,
                    }).then((willDelete) => {
                        if (!willDelete) {
                            return false;
                        } else {

                            $.ajax({
                                type: "POST",
                                async: true,
                                data: 'id=' + id + '&_token={{ csrf_token() }}',
                                url: "{{ route('deleteNewsfeed') }}",
                                success: function (data) {
                                    $('#MSG').html('<div class="alert alert-success">\n\
                        <strong>Success! </strong>NewsFeed has been Deleted \n\
                    </div>');
                                    $("html, body").animate({scrollTop: 0}, "slow");
                                    $("#" + id).addClass('hide');
                                    $("#newsFeedTags").html(data.data);
                                    hideLoader();
                                    $('#newsListing').DataTable().draw();
                                },
                                error: function (errorMsg) {


                                }
                            })
                        }
                    });

                }


                function launchNewsfeed() {
                    $('#is_active').val('active');
                    $('.inputError').html('');
                    if (newsfeed.newsfeedStep.value == "COMPOSE") {
                        if (newsfeed.m_name.value == "") {
                            $('#nameError').html('Please Enter the Name');
                            $("#m_name").focus();
                            return;
                        }

                        // if(newsfeed.newstags.value == ""){
                        //     $('#newstagsError').html('Please Enter the Tag');
                        //     $("#newstags" ).focus();
                        //     return;
                        //  }

                        if (newsfeed.type_id.value == "") {
                            $('#typeError').html('Please select the template');
                            $("#select_card_type").focus();
                            return;
                        }

                        if (newsfeed.m_title.value == "") {
                            $('#titleError').html('Please select the title');
                            $("#m_title").focus();
                            return;
                        }

                    }

                    if (newsfeed.newsfeedStep.value == "DELIVERY") {
                        if (newsfeed.seg_id.value == "") {
                            $('#segError').html('Please select the Segment');
                            $("#seg_id").focus();
                            return;
                        }

                        if (newsfeed.loc_id.value == "") {
                            $('#locError').html('Please select the location');
                            $("#loc_id").focus();
                            return;
                        }

                    }
                    newsfeedSave();

                    location.reload();


                }

                function selectImage(image) {
                    var image_url = baseUrl + '/uploads/gallery/' + image;
                    $(".col-xs-2 img").fadeIn("slow").attr('src', image_url);
                    $('.content_holder img').fadeIn("slow").attr('src', image_url);
                    $('#image_url').val(image_url)
                    $('#exampleModalCenter').modal('hide');
                }

                function draftNewsfeed() {
                    $('.inputError').html('');

                    if (newsfeed.m_name.value == "") {
                        $('#nameError').html('Please Enter the Name');
                        $("#m_name").focus();
                        return;
                    }

                    // if(newsfeed.newstags.value == ""){
                    //     $('#newstagsError').html('Please Enter the Tag');
                    //     $("#newstags" ).focus();
                    //     return;
                    //  }

                    if (newsfeed.type_id.value == "") {
                        $('#typeError').html('Please select the template');
                        $("#select_card_type").focus();
                        return;
                    }

                    if (newsfeed.m_title.value == "") {
                        $('#titleError').html('Please select the title');
                        $("#m_title").focus();
                        return;
                    }

                    newsfeedSave();
                    location.reload();
                }


                function newsfeedStepSave() {
                    var content = $('#preview_div').html();
                    $.ajax({
                        type: "POST",
                        data: $('#form_id1').serialize() + '&content=' + content + '&_token={{ csrf_token() }}&',
                        url: "{{ route('saveNewsfeed') }}",
                        dataType: "json",
                        success: function (data) {
                            if (data.error == false) {
                                $('#newsfeedID').val(data.result)
                                $('#preview_final').html(content);
                                return;
                            }

                            if (data.error == true) {
                                $('#MSG').html('<div class="alert alert-warning">\n\
                        <strong>Warning! </strong> something wrong\n\
                        </div>');
                                $("html, body").animate({scrollTop: 0}, "slow");
                                return false;
                            }

                            console.log(data.result['id']);
                        },
                        error: function (errorMsg) {


                        }
                    })
                }

                function newsfeedSave() {

                    var content = $('#preview_div').html();
                    $.ajax({
                        type: "POST",
                        data: $('#form_id1').serialize() + '&content=' + content + '&_token={{ csrf_token() }}&',
                        url: "{{ route('saveNewsfeed') }}",
                        dataType: "json",
                        success: function (data) {
                            if (data.error == false) {
                                $('#newsfeedID').val(data.result)
                                $('#preview_final').html(content);
                                $('#MSG').html('<div class="alert alert-success">\n\
                        <strong>Success! </strong>Newsfeed has been created  \n\
                    </div>');
                                $("html, body").animate({scrollTop: 0}, "slow");
                                return;
                            }

                            if (data.error == true) {
                                $('#MSG').html('<div class="alert alert-warning">\n\
                        <strong>Warning! </strong> something wrong\n\
                        </div>');
                                $("html, body").animate({scrollTop: 0}, "slow");
                                return false;
                            }

                            console.log(data.result['id']);
                        },
                        error: function (errorMsg) {


                        }
                    })
                }


            </script>
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

                #newsListing_filter label {
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
            </style>

@stop
