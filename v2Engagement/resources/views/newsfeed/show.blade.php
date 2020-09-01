@extends('layouts.master')

@section('searchBar')
@stop

@section('create')
@stop

@section('content')
    <link href="{{asset('build/css/custom.css')}}" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="{{asset('css/devices.min.css')}}"/>

    <style>
        .file_field {
            position: relative;
        }

        .file_field input {
            padding: 0;
        }

        .file_field span {
            position: absolute;
            top: 0;
            left: 0;
            padding: 6px 22px;
            pointer-events: none;
        }

        .file_field:hover span {
            background-color: #286090;
            border-color: #286090;
        }

        .loader_image {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.4);
            font-size: 40px;
            color: #fff;
            z-index: 999;
            border-radius: 3px;
        }

        .loader_image i {
            position: absolute;
            top: 50%;
            left: 50%;
        }

        label.error {
            color: #ff0000;
            font-weight: 400;
        }

        input.error {
            border-color: #ff0000 !important;
        }

        #preview_div {
            pointer-events: none;
        }

        .dl-info {
            background: #f1f1f1;
            overflow: hidden;
            padding: 7px 10px;
            margin: 0;
        }

        .dl-info dt {
            background: #fff;
            font-weight: 700;
            float: left;
            clear: left;
            width: 180px;
            padding: 7px;
            margin: 0 0 7px;
        }

        .dl-info dd {
            float: left;
            clear: right;
            padding: 7px 10px;
        }
    </style>

    <div class="row hide">
        <div class="col-md-12 col-sm-12 col-xs-12">
            <div class="x_panel">
                <div class="loader_image" style="display: none"><i class="fa fa-spinner fa-spin"></i></div>

                <div class="x_title">
                    <h2><?php echo $news->name; ?></h2>
                </div>
                <div class="x_content">
                    <div class="row hide_ajax">
                        <div class="col-xs-12">
                            <div class="col-xs-12">
                                <form id="chart_form" class="formee form-horizontal" onsubmit="return false">
                                    <div class="row">
                                        <label class="control-label col-xs-5">Start Date</label>
                                        <div class="col-xs-3">
                                            <div class="form-group">
                                                <input type="text" name="m_sdate"
                                                       value="<?php echo date('M d, Y', time() - 60 * 60 * 24); ?>"
                                                       id="start_date" class="form-control" placeholder="Start Date"/>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <label class="control-label col-xs-5">End Date</label>
                                        <div class="col-xs-3">
                                            <div class="form-group">
                                                <input type="text" name="m_edate" value="<?php echo date('M d, Y'); ?>"
                                                       id="end_date" class="form-control" placeholder="Start Date"/>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-xs-5"></div>
                                        <div class="col-xs-6">
                                            <div class="form-group">
                                                <input type="submit" id="submit_call" name="submit"
                                                       class="btn btn-success" value="Apply"/>
                                            </div>
                                        </div>
                                    </div>
                            </div>
                            </form>
                        </div>
                        <!-- graph area -->
                        <div class="col-md-4 col-xs-12">
                            <div class="x_panel">
                                <div class="x_title">
                                    <h2>Total Clicks <strong id="total_click_count">0</strong></h2>
                                    <div class="clearfix"></div>
                                </div>
                                <div class="x_content2">
                                    <div id="graph_area_clicks" style="width:100%; height:300px;"></div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 col-xs-12">
                            <div class="x_panel">
                                <div class="x_title">
                                    <h2>Total Views <strong id="total_view_count">0</strong></h2>
                                    <div class="clearfix"></div>
                                </div>
                                <div class="x_content2">
                                    <div id="graph_area_visits" style="width:100%; height:300px;"></div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 col-xs-12">
                            <div class="x_panel">
                                <div class="x_title">
                                    <h2>CLICKTHROUGH RATE <strong id="ctr_percentage">0%</strong></h2>
                                    <div class="clearfix"></div>
                                </div>
                                <div class="x_content2">
                                    <div id="graph_area_rate" style="width:100%; height:300px;"></div>
                                </div>
                            </div>
                        </div>
                        <!-- /graph area -->
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row hide">
        <div class="col-md-12 col-sm-12 col-xs-12">
            <div class="x_panel">
                <div class="loader_image" style="display: none"><i class="fa fa-spinner fa-spin"></i></div>
                <div class="x_title">
                    <h2>Mobile Platforms</h2>
                </div>
                <div class="x_content">
                    <div class="row">

                        <!-- graph area -->
                        <div class="col-md-4 col-sm-4 col-xs-12">
                            <div class="x_panel">
                                <div class="x_title">
                                    <h2>Total Clicks</h2>
                                    <div class="clearfix"></div>
                                </div>
                                <div class="x_content2">
                                    <div id="graph_area_clicks_pie" style="width:100%; height:300px;"></div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 col-sm-4 col-xs-12">
                            <div class="x_panel">
                                <div class="x_title">
                                    <h2>Total Views</h2>
                                    <div class="clearfix"></div>
                                </div>
                                <div class="x_content2">
                                    <div id="graph_area_visits_pie" style="width:100%; height:300px;"></div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 col-sm-4 col-xs-12">
                            <div class="x_panel">
                                <div class="x_title">
                                    <h2>CLICKTHROUGH RATE</h2>
                                    <div class="clearfix"></div>
                                </div>
                                <div class="x_content2">
                                    <div id="graph_area_rate_pie" style="width:100%; height:300px;"></div>
                                </div>
                            </div>
                        </div>
                        <!-- /graph area -->
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">

    @section('newFeed_static')
        <!-- Newsfeed Graph Sec -->
            <div class="newFeed_static">
                <div class="newsfeed_stat_sec mobile_platform_outer clearfix">
                    {{--<h3 class="nf_stat_heading"> Mobile Platform </h3>--}}
                    <h3 class="nf_stat_heading">{{ $news->name }}</h3>
                    <div class="newsfeed_stat_list_outer clearfix">
                        <div class=" newsfeed_stat_list">
                            <h2> IOS </h2>
                            <ul>
                                <li style="cursor: pointer"> Clicks</li>
                                <li style="cursor: pointer"> Views</li>
                                <li style="cursor: pointer"> %</li>
                            </ul>
                        </div>
                        <div class=" newsfeed_stat_list">
                            <h2> Android </h2>
                            <ul>
                                <li style="cursor: pointer"> Clicks</li>
                                <li style="cursor: pointer"> Views</li>
                                <li style="cursor: pointer"> %</li>
                            </ul>
                        </div>
                        <div class="newsfeed_stat_list camp_Dur_detail ">
                            <form class="mobilePlateFormForm">
                                <input type="hidden" name="newsFeedId" value="{{ $newsFeedId }}">
                                <ul>
                                    <li>
                                        <div class="camp_Dur_timing clearfix">
                                            <label for="start_tm"> Start Time</label>
                                            <div class=" inp_dat_picker b_r">
                                                <label>
                                                    <input id="rangeStartDate" type="date" name="fromDate">
                                                </label>
                                            </div>
                                        </div>
                                    </li>
                                    <li>
                                        <div class="camp_Dur_timing clearfix">
                                            <label for="start_tm"> End Time</label>
                                            <div class=" inp_dat_picker b_r">
                                                <label>
                                                    <input id="rangeEndDate" type="date" name="toDate">
                                                </label>
                                            </div>
                                        </div>
                                    </li>
                                    <li>
                                        <div class="camp_Dur_timing clearfix">

                                            <div class="" style="text-align:right; padding-right:10px; ">
                                                <button type="submit" name="button"> APPLY</button>
                                            </div>
                                        </div>
                                    </li>
                                </ul>
                            </form>
                        </div>
                    </div>

                </div>
                <div class="newsfeed_stat_sec mobile_platform_outer clearfix">
                    <h3 class="nf_stat_heading"> Chart</h3>
                    <div class="newsfeed_stat_list_outer clearfix">
                        <div class="col-md-4 col-lg-4">
                            <div id="total_views" style="min-width: 310px; height: 400px; margin: 0 auto"></div>
                        </div>
                        <div class="col-md-4 col-lg-4">
                            <div id="total_click" style="min-width: 310px; height: 400px; margin: 0 auto"></div>
                        </div>
                        <div class="col-md-4 col-lg-4">
                            <div id="click_through" style="min-width: 310px; height: 400px; margin: 0 auto"></div>
                        </div>
                    </div>
                </div>
            </div>
        @endsection

        <div class="col-xs-12">
            <div class="x_panel">
                <div class="x_title">
                    <h2>Newsfeed Details</h2>
                </div>
                <div class="row">
                    <div class="col-sm-6 col-xs-12">
                        <div class="x_panel">
                            <div class="x_title">
                                <h2>{{ $news->name }}</h2>
                            </div>
                            <div class="x_content">
                                <div id="preview_div" style="width:100%; max-height:600px;">
                                    @php echo $news->content @endphp
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6 col-xs-12">
                        <div class="x_panel">
                            <div class="x_title">
                                <h2>Delivery Window</h2>
                                {{--<a style="float: right" href="{{ route('editNewsfeed',  $news->id)}}"><i class="fa fa-pencil"></i></a>--}}
                            </div>
                            <div class="x_content">
                                <div class="x_content2">
                                    <div id="" style="width:100%; max-height:100px;">
                                        <dl class="dl-info">
                                            <dt>START TIME:</dt>
                                            <dd>{{ date('M d, Y h:i:s a', strtotime($news->start_time)) }}</dd>
                                            <dt>END TIME:</dt>
                                            @if($news->enable_end_time==1)
                                                <dd>{{ date('M d, Y h:i:s a', strtotime($news->end_time)) }}</dd>
                                            @else
                                                <dd> Never</dd>
                                            @endif
                                        </dl>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="x_panel">
                            <div class="x_title">
                                <h2>Targeted Audience</h2>
                                {{--                                <a style="float: right" href="{{ route('editNewsfeed',  $news->id)}}"><i class="fa fa-pencil"></i></a>--}}

                            </div>
                            <div class="x_content">
                                <div class="x_content2">
                                    <div id="" style="width:100%; max-height:200px;">
                                        <?php if (!empty($segments)) { ?>
                                        <dl class="dl-info">
                                            <dt>Segment:</dt>
                                            <dd><?php echo $segments->name; ?></dd>

                                        <!-- <dt>TOTAL AUDIENCE COUNT:</dt>
                                                <dd><?php //echo $targeted_users; ?></dd> -->
                                            <dt>No. OF USERS:</dt>
                                            <dd> <?php echo $reachableUsers; ?></dd>
                                        </dl>
                                        <?php } else { ?>
                                        <strong>ALL AUDIENCE -- Not any filter applied.</strong>
                                        <?php } ?>


                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="x_panel">
                            <div class="loader_image" style="display: none;"><i class="fa fa-spinner fa-spin"></i></div>
                            <div class="x_title">
                                <h2>Performance Statistics</h2>
                                <span style="float: right;color: #0a0a0a"
                                      id="selected_time">{{--Jul 05, 2018 - Jul 06, 2018--}}</span>

                            </div>
                            <div class="x_content">
                                <div class="x_content2">
                                    <div id="" style="width:100%; height:200px;">

                                        <table class="table">
                                            <thead>
                                            <tr>
                                                <th></th>
                                                <th>Best Day</th>
                                                <th>Worst Day</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            <tr>
                                                <th scope="row">Views</th>
                                                <td id="views_h"><b>{{ $maxViewByDay[0]->maxViewed }}</b>&nbsp;&nbsp;&nbsp;{{ $maxViewByDay[0]->createdDate }}
                                                </td>
                                                <td id="views_l"><b>{{ $minViewByDay[0]->minViewed }}</b>&nbsp;&nbsp;&nbsp;{{ $minViewByDay[0]->createdDate }}
                                                </td>
                                            </tr>
                                            <tr>
                                                <th scope="row">Clicks</th>
                                                <td id="clicks_h"><b>{{ $maxClickByDay[0]->maxCount }}</b>&nbsp;&nbsp;&nbsp;{{ $maxClickByDay[0]->createdDate }}
                                                </td>
                                                <td id="clicks_l"><b>{{ $minClickByDay[0]->minCount }}</b>&nbsp;&nbsp;&nbsp;{{ $minClickByDay[0]->createdDate }}
                                                </td>
                                            </tr>
                                            <tr>
                                                <th scope="row">CLICKTHROUGHS</th>
                                                <td id="ctr_h">
                                                    <b>{{ ($maxViewByDay[0]->maxViewed)?number_format((($maxClickByDay[0]->maxCount/$maxViewByDay[0]->maxViewed)*100),2):0 }}
                                                        %</b></td>
                                                <td id="ctr_l">
                                                    <b>{{ ($minViewByDay[0]->minViewed)?number_format((($minClickByDay[0]->minCount/$minViewByDay[0]->minViewed)*100),2):0 }}
                                                        %</b></td>
                                            </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@stop

@section('jsSection')
    <script src="{{asset('/assets/js/newsFeed/newsFeed.js')}}"></script>
    <script src="https://code.highcharts.com/highcharts.js"></script>
    <script src="https://code.highcharts.com/modules/series-label.js"></script>
    <script src="https://code.highcharts.com/modules/exporting.js"></script>
    <script>


        $(document).keypress(
            function (event) {
                if (event.which == '13') {
                    event.preventDefault();
                }


            });

        setlast30thDate();

        function setlast30thDate() {
            var currDate = new Date();
            var last30thDate = new Date().setDate(currDate.getDate() - 7);
            last30thDate = new Date(last30thDate).toISOString().split('T')[0];
            var prevDate = new Date().setDate(new Date().getDate() );
            var prevDate = new Date(prevDate).toISOString().split('T')[0];
            $("#rangeStartDate").val(last30thDate);
            $("#rangeEndDate").val(prevDate);
        }

        function handleEndTime(checkbox) {
            if (checkbox.checked == true) {
                $('#end_time_dev').removeClass('hide');

            } else {
                $('#end_time_dev').addClass('hide');
            }
        }

        function getNewsfeedBySearch() {
            $_token = "{{ csrf_token() }}";
            var searchKeyWord = $('#search').val();
            $.ajax({
                headers: {'X-CSRF-Token': $('meta[name=_token]').attr('content')},
                url: "{{ url('/newsfeed/search-table') }}",
                type: 'POST',
                cache: false,
                data: {'searchKeyWord': searchKeyWord, '_token': $_token}, //see the $_token
                datatype: 'html',
                beforeSend: function () {
                    //something before send
                },
                success: function (data) {
                    //success
                    //var data = $.parseJSON(data);
                    if (data.success == true) {
                        //user_jobs div defined on page
                        $('#newsfeedTable').html(data.html);
                    } else {
                        $('#newsfeedTable').html(data.html + '1');

                    }


                    $(".scrollbar_content").mCustomScrollbar();

                    $(".disable-destroy a").click(function (e) {
                        e.preventDefault();
                        var $this = $(this),
                            rel = $this.attr("rel"),
                            el = $(".scrollbar_content"),
                            output = $("#info > p code");
                        switch (rel) {
                            case "toggle-disable":
                            case "toggle-disable-no-reset":
                                if (el.hasClass("mCS_disabled")) {
                                    el.mCustomScrollbar("update");
                                    output.text("$(\".scrollbar_content\").mCustomScrollbar(\"update\");");
                                } else {
                                    var reset = rel === "toggle-disable-no-reset" ? false : true;
                                    el.mCustomScrollbar("disable", reset);
                                    if (reset) {
                                        output.text("$(\".scrollbar_content\").mCustomScrollbar(\"disable\",true);");
                                    } else {
                                        output.text("$(\".scrollbar_content\").mCustomScrollbar(\"disable\");");
                                    }
                                }
                                break;
                            case "toggle-destroy":
                                if (el.hasClass("mCS_destroyed")) {
                                    el.mCustomScrollbar();
                                    output.text("$(\".scrollbar_content\").mCustomScrollbar();");
                                } else {
                                    el.mCustomScrollbar("destroy");
                                    output.text("$(\".scrollbar_content\").mCustomScrollbar(\"destroy\");");
                                }
                                break;
                        }
                    });
                },
                error: function (xhr, textStatus, thrownError) {
                    alert(xhr + "\n" + textStatus + "\n" + thrownError);
                }
            });
        }


        $(document).ready(function () {
            var gallery = $('#gallery').DataTable();
        });


        function newsDelete(id) {
            var r = confirm("Are you sure!");
            if (r == false) {
                return false;
            }
            $.ajax({
                type: "POST",
                data: 'id=' + id + '&_token={{ csrf_token() }}',
                url: "{{ route('deleteNewsfeed') }}",
                success: function (data) {
                    $('#MSG').html('<div class="alert alert-success">\n\
							 <strong>Success! </strong>NewsFeed has been Deleted \n\
							</div>');
                    $("html, body").animate({scrollTop: 0}, "slow");
                    $("#" + id).addClass('hide');
                    return;
                },
                error: function (errorMsg) {


                }
            })
        }


        function launchNewsfeed() {
            $('#is_active').val(1);
            $('.inputError').html('');
            if (newsfeed.newsfeedStep.value == "COMPOSE") {
                if (newsfeed.m_name.value == "") {
                    $('#nameError').html('Please Enter the Name');
                    $("#m_name").focus();
                    return;
                }

                if (newsfeed.newstags.value == "") {
                    $('#newstagsError').html('Please Enter the Tag');
                    $("#newstags").focus();
                    return;
                }

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

            window.location = baseUrl + "/backend/newsfeed/newsfeeds";


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

            if (newsfeed.newstags.value == "") {
                $('#newstagsError').html('Please Enter the Tag');
                $("#newstags").focus();
                return;
            }

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
            window.location = baseUrl + "/backend/newsfeed/newsfeeds";
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


        $('.mobilePlateFormForm').bind('submit', function (event) {
            event.preventDefault();
            formData = $(this).serialize();
            $.ajax({
                'type': 'post',
                'url': '{{ route('mobilePlateformStatics') }}',
                'data': formData + '&screen=VIEW&_token={{csrf_token()}}',
                'success': function (data) {
                    $('.clickIphoneCount').text(data.clickIphoneCount);
                    $('.viewIphoneCount').text(data.viewIphoneCount);
                    $('.iphoneClickThrough').text(data.iphoneClickThrough);
                    $('.clickAndroidCount').text(data.clickAndroidCount);
                    $('.viewAndroidCount').text(data.viewAndroidCount);
                    $('.androidClickThrough').text(data.androidClickThrough);
                    intervalArr = data.intervalArr;
                    totalClickChart(data.clickAndroidByInterval, data.clickIphoneByInterval);
                    totalViewChart(data.viewAndroidByInterval, data.viewIphoneByInterval)
                    clickThroughChart(data.androidClickThroughCount, data.iphoneClickThroughCount)
                }
            })
        })

        $('.mobilePlateFormForm').trigger('submit');


        // catArr = [
        //     'Jan',
        //     'Feb',
        //     'Mar',
        //     'Apr',
        //     'May',
        //     'Jun',
        //     'Jul',
        //     'Aug',
        //     'Sep',
        //     'Oct',
        //     'Nov',
        //     'Dec'
        // ];

        // totalClickArr = [49.9, 71.5, 106.4, 129.2, 144.0, 176.0, 135.6, 148.5, 216.4, 194.1, 95.6, 54.4];
        function totalClickChart(androidData, iphoneData) {
            Highcharts.chart('total_click', {
                chart: {
                    type: 'column'
                },
                title: {
                    text: 'Total Clicks'
                },
                subtitle: {
                    text: ''
                },
                xAxis: {
                    categories: intervalArr,
                    crosshair: true
                },
                yAxis: {
                    min: 0,
                    title: {
                        text: ''
                    }
                },
                tooltip: {
                    headerFormat: '<span style="font-size:10px">{point.key}</span><table>',
                    pointFormat: '<tr><td style="color:{series.color};padding:0">{series.name}: </td>' +
                        '<td style="padding:0"><b>{point.y:.0f}</b></td></tr>',
                    footerFormat: '</table>',
                    shared: true,
                    useHTML: true
                },
                plotOptions: {
                    column: {
                        pointPadding: 0.2,
                        borderWidth: 0
                    }
                },
                series: [{
                    name: 'Android',
                    data: androidData
                }, {
                    name: 'IOS',
                    data: iphoneData
                }]
            });

        }

        // totalViewsData = [49.9, 71.5, 106.4, 129.2, 144.0, 176.0, 135.6, 148.5, 216.4, 194.1, 95.6, 54.4];
        function totalViewChart(androidData, iphoneData) {

            Highcharts.chart('total_views', {
                chart: {
                    type: 'column'
                },
                title: {
                    text: 'Total Views'
                },
                subtitle: {
                    text: ''
                },
                xAxis: {
                    categories: intervalArr,
                    crosshair: true
                },
                yAxis: {
                    min: 0,
                    title: {
                        text: ''
                    }
                },
                tooltip: {
                    headerFormat: '<span style="font-size:10px">{point.key}</span><table>',
                    pointFormat: '<tr><td style="color:{series.color};padding:0">{series.name}: </td>' +
                        '<td style="padding:0"><b>{point.y:.0f}</b></td></tr>',
                    footerFormat: '</table>',
                    shared: true,
                    useHTML: true
                },
                plotOptions: {
                    column: {
                        pointPadding: 0.2,
                        borderWidth: 0
                    }
                },
                series: [{
                    name: 'Android',
                    data: androidData
                }, {
                    name: 'IOS',
                    data: iphoneData
                }]
            });
        }

        // clickThroughData = [49.9, 71.5, 106.4, 129.2, 144.0, 176.0, 135.6, 148.5, 216.4, 194.1, 95.6, 54.4];
        function clickThroughChart(androidData, iphoneData) {

            Highcharts.chart('click_through', {
                chart: {
                    type: 'column'
                },
                title: {
                    text: 'Click Through Rate'
                },
                subtitle: {
                    text: ''
                },
                xAxis: {
                    categories: intervalArr,
                    crosshair: true
                },
                yAxis: {
                    min: 0,
                    title: {
                        text: ''
                    }
                },
                tooltip: {
                    headerFormat: '<span style="font-size:10px">{point.key}</span><table>',
                    pointFormat: '<tr><td style="color:{series.color};padding:0">{series.name}: </td>' +
                        '<td style="padding:0"><b>{point.y:.1f} %</b></td></tr>',
                    footerFormat: '</table>',
                    shared: true,
                    useHTML: true
                },
                plotOptions: {
                    column: {
                        pointPadding: 0.2,
                        borderWidth: 0
                    }
                },
                series: [{
                    name: 'Android',
                    data: androidData
                }, {
                    name: 'IOS',
                    data: iphoneData
                }]
            });
        }
    </script>
@stop