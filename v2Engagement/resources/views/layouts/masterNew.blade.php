<!doctype html>
<html>
<head>
    <meta http-equiv="Cache-control" content="public">
    <meta charset="utf-8">
    <meta name="viewport" content=" width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Engagement</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="{{asset('/html/css/font-awesome.css')}}" media="all" rel="stylesheet">
    <link href="{{asset('/html/css/all.css')}}" rel="stylesheet" type="text/css">
    <link href="{{asset('/html/css/jquery-steps.css')}}" rel="stylesheet" type="text/css">
    <!-- custom scrollbar stylesheet -->
    <link rel="stylesheet" href="{{asset('html/css/jquery.mCustomScrollbar.css')}}">

    <script>
        window.onload = function () {

            //Better to construct options first and then pass it as a parameter
            var options = {
                exportEnabled: true,
                animationEnabled: true,
                title: {
                    text: ""
                },
                axisX: {
                    tickColor: "transparent",
                    gridColor: "transparent",
                    labelFontColor: "transparent",
                    lineColor: "transparent"
                },
                axisY: {
                    tickColor: "transparent",
                    gridColor: "transparent",
                    labelFontColor: "transparent",
                    lineColor: "transparent"
                },
                data: [
                    {
                        color: "#eef6f6",
                        lineColor: "#2a8689",
                        markerColor: "transparent",
                        // lineThickness: 5,
                        type: "splineArea", //change it to line, area, bar, pie, etc
                        dataPoints: [
                            {y: 10},
                            {y: 6},
                            {y: 14},
                            {y: 12},
                            {y: 19},
                            {y: 14},
                            {y: 26},
                            {y: 10},
                            {y: 22}
                        ]
                    }
                ]
            };
            $("#chart1").CanvasJSChart(options);
            $("#chart2").CanvasJSChart(options);
            $("#chart3").CanvasJSChart(options);
            $("#chart4").CanvasJSChart(options);
            $("#chart5").CanvasJSChart(options);
            $("#chart6").CanvasJSChart(options);

            var options2 = {
                title: {
                    text: ""
                },
                animationEnabled: true,
                exportEnabled: true,
                toolTip: {
                    enabled: true,
                    borderColor: "transparent",
                    fontSize: "15",
                    fontWeight: "bold",
                    showToolTipShadow: "2",
                    backgroundColor: "#303030",
                    content: "<span style='\"'color:#fff; font-style:normal;'\"'>{x}</span> <br/><span style='\"'color:#dbdbdb; font-style:normal; '\"'>Sessions by Units</em> <span style='\"'font-weight: bold; color:#fff; '\"'>{y}</span>",
                },
                axisX: {
                    tickColor: "transparent",
                    gridColor: "transparent",
                    labelFontColor: "transparent",
                    lineColor: "transparent"
                },
                axisY: {
                    tickColor: "transparent",
                    gridColor: "#e1e1e1",
                    labelFontColor: "#e1e1e1",
                    lineColor: "transparent"
                },
                data: [
                    {
                        lineColor: "#b2b2b2",
                        lineThickness: 2,
                        markerColor: "#7cb5ec",
                        // markerBorderThickness: 3,
                        // markerBorderColor: "#9bf014",
                        markerType: "circle",
                        markerSize: 11,
                        indexLabelFontSize: 50,
                        type: "spline", //change it to spline, line, area, column, pie, etc
                        dataPoints: [
                            {x: new Date(2010, 0, 3), y: 50},
                            {x: new Date(2010, 0, 5), y: 500, markerColor: "#434348"},
                            {x: new Date(2010, 0, 7), y: 200},
                            {x: new Date(2010, 0, 9), y: 100, markerColor: "#434348"},
                            {x: new Date(2010, 0, 11), y: 896, markerColor: "#434348"},
                            {x: new Date(2010, 0, 13), y: 354},
                            {x: new Date(2010, 0, 15), y: 400, markerColor: "#434348"},
                            {x: new Date(2010, 0, 17), y: 569},
                            {x: new Date(2010, 0, 19), y: 312},
                            {x: new Date(2010, 0, 21), y: 100, markerColor: "#434348"},
                            {x: new Date(2010, 0, 23), y: 970}
                        ]
                    }
                ]
            };
            $("#chartContainer").CanvasJSChart(options2);
            $("#chartContainer2").CanvasJSChart(options2);
            $("#chartContainer3").CanvasJSChart(options2);
        }
    </script>
</head>
<!-- email_html-2,  -->
<body class=@yield('create-class')>

<div class="wrapper">
{{ csrf_field() }}
<!-- left_menu_expand => Add for left Bar Expand -->
    <div class="wpr_content_holder  clearfix">

        <div class="left_menu_list ">
            <div class="menu_left_logo">
                <a href="{{route('dashboard')}}"> <img src="{{asset('html/images/ureka_logo2.png')}}" alt=""> </a>
            </div>
            @php
                $roleArr = Auth::user()->roles()->pluck('name')->toArray()
            @endphp
            <ul>
                {{--<li>
      <a href="{{route('dashboard')}}" class="{{ Request::path() == '/' ? 'active' : '' }}">
          <img src="{{asset('/assets/images/hm_home_icon.png')}}" alt="#">
          <b class="link_inline"> Home</b>
          <b class="link_block"> Home</b>
      </a>
  </li>--}}
                @if( !in_array('SUPER-ADMIN', $roleArr))
                    <li>
                        <a href="{{asset('/backend/campaign/campaigns')}}"
                           class="{{ Request::path() == 'backend/campaign/campaigns' ? 'active' : '' }}">
                            <img src="{{asset('/assets/images/hm_campaign_icon.png')}}" alt="#">
                            <b class="link_inline"> Campaign</b>
                            <b class="link_block"> Campaign</b>
                        </a>
                    </li>

                    <li>
                        <a href="{{asset('/backend/segment/segments')}}"
                           class="{{ Request::path() == 'backend/segment/segments' ? 'active' : '' }}">
                            <img src="{{asset('/assets/images/hm_segment_icon.png')}}" alt="#">
                            <b class="link_inline"> Segments</b>
                            <b class="link_block"> Segments</b>
                        </a>
                    </li>
                    <li>
                        <a href="{{asset('/backend/newsfeed/newsfeeds')}}"
                           class="{{ Request::path() == 'backend/newsfeed/newsfeeds' ? 'active' : '' }}">
                            <img src="{{asset('/assets/images/hm_newsfeed_icon.png')}}" alt="#">
                            <b class="link_inline"> Newsfeed</b>
                            <b class="link_block"> Newsfeed</b>
                        </a>
                    </li>
                    <li>
                        <a href="{{route('gallery')}}" class="{{ Request::path() == 'gallery' ? 'active' : '' }}">
                            <img src="{{asset('/assets/images/hm_usr_icon.png')}}" alt="#">
                            <b class="link_inline">Gallery</b>
                            <b class="link_block">Gallery</b>
                        </a>
                    </li>
                @endif
                @if( in_array('SUPER-ADMIN', $roleArr))
                    <li>
                        <a href="{{route('users.index')}}" class="{{ Request::path() == 'users' ? 'active' : '' }}">
                            <img src="{{asset('/assets/images/hm_usr_icon.png')}}" alt="#">
                            <b class="link_inline"> Users</b>
                            <b class="link_block"> Users</b>
                        </a>
                    </li>
                    <li>
                        <a href="{{route('setting')}}">
                            <img src="{{asset('/assets/images/hm_setting_icon.png')}}" alt="#">
                            <b class="link_inline"> Settings</b>
                            <b class="link_block"> Settings</b>
                        </a>
                    </li>
                    <li>
                        <a href="{{route('lookup.index')}}">
                            <img src="{{asset('/assets/images/lookup.png')}}" alt="#">
                            <b class="link_inline"> Lookup</b>
                            <b class="link_block"> Lookup</b>
                        </a>
                    </li>
                    <li>
                        <a href="{{route('location.index')}}">
                            <img src="{{asset('/assets/images/Location.png')}}" alt="#">
                            <b class="link_inline"> Location</b>
                            <b class="link_block"> Location</b>
                        </a>
                    </li>
                    <li>
                        <a href="{{url('/company/cache')}}">
                            <img src="{{asset('/assets/images/hm_usr_icon.png')}}" alt="#">
                            <b class="link_inline"> Sync Data</b>
                            <b class="link_block"> Sync Data</b>
                        </a>
                    </li>
                    <li>
                        <a href="{{url('/jobs')}}">
                            <img src="{{asset('/assets/images/hm_usr_icon.png')}}" alt="#">
                            <b class="link_inline">Queue Data</b>
                            <b class="link_block">Queue Data</b>
                        </a>
                    </li>
                    <li>
                        <a href="{{route('duplicates.attribute')}}">
                            <img src="{{asset('/assets/images/Location.png')}}" alt="#">
                            <b class="link_inline">Attributes List</b>
                            <b class="link_block">Attributes List</b>
                        </a>
                    </li>
                @endif
                @if( !in_array('SUPER-ADMIN', $roleArr))
                    <li>
                        <a href="{{route('importFileView')}}"
                           class="{{ Request::path() == 'import-data/import-file-view' ? 'active' : '' }}">
                            <img src="{{asset('/assets/images/hm_d_imp_icon.png')}}" alt="#" style="width: 20px;">
                            <b class="link_inline">Import</b>
                            <b class="link_block">Import</b>
                        </a>
                    </li>
                    <li>
                        <a href="{{asset('/backend/attribute/attributeData')}}"
                           class="{{ Request::path() == 'backend/attribute/attributeData' ? 'active' : '' }}">
                            <img src="{{asset('/assets/images/attributes-icon.png')}}" alt="#">
                            <b class="link_inline">User</b>
                            <b class="link_block">User</b>
                        </a>
                    </li>
                    <li>
                        <a href="{{asset('/backend/app/listing')}}"
                           class="{{ Request::path() == 'backend/app/listing' ? 'active' : '' }}">
                            <img src="{{asset('/assets/images/App-Information.png')}}" alt="#">
                            <b class="link_inline">App</b>
                            <b class="link_block">App</b>
                        </a>
                    </li>
                    <li>
                        <a href="{{route('location.index')}}">
                            <img src="{{asset('/assets/images/Location.png')}}" alt="#">
                            <b class="link_inline"> Location</b>
                            <b class="link_block"> Location</b>
                        </a>
                    </li>
                {{--<li>--}}
                {{--<a href="{{ route('otherAttributeDataView') }}"--}}
                {{--class="{{ Request::path() == 'other-attribute-data-view' ? 'active' : '' }}">--}}
                {{--<img src="{{asset('/assets/images/attributes-icon.png')}}" alt="#">--}}
                {{--<b class="link_inline">Other Attribute</b>--}}
                {{--<b class="link_block">Other Attribute</b>--}}
                {{--</a>--}}
                {{--</li>--}}
                {{--<li>
                    <a href="{{ route('emailListView') }}"
                       class="{{ Request::path() == 'email-list-view' ? 'active' : '' }}">
                        <img src="{{asset('/assets/images/attributes-icon.png')}}" alt="#">
                        <b class="link_inline">Email List</b>
                        <b class="link_block">Email List</b>
                    </a>
                </li>--}}
                {{--<li>--}}
                {{--<a href="{{asset('/backend/company/companyStats')}}"--}}
                {{--class="{{ Request::path() == 'backend/company/companyStats' ? 'active' : '' }}">--}}
                {{--<img src="{{asset('/assets/images/hm_usr_icon.png')}}" alt="#">--}}
                {{--<b class="link_inline">Stats</b>--}}
                {{--<b class="link_block"> Stats</b>--}}
                {{--</a>--}}
                {{--</li>--}}

            @endif
            <!-- <li> <a href="#"> <img src="images/hm_quires_icon.png" alt="#"> <b> Quires</b> </a> </li> -->
            </ul>
        </div>

        <div class="right_sec_outer">

            <div class="rt_tp_hdr_outer clearfix">

                <div class="hdr_profile_sec">

                    <a class="hdr_menu_btn" href="#"> <img src="{{asset('/html/images/hdr_menu_btn2-new.png')}}" alt="#"> </a>
                    <p>
                        <span>Beats by dr.dre</span>
                    </p>

                </div>

                <div class="hdr_lft_list tp_hdr_list">

                    <ul>

                        <li><a href="#"> <img src="{{asset('html/images/hdr_folder_icon-new.png')}}" alt="#"> </a></li>
                        <li><a href="#"> <img src="{{asset('html/images/hdr_filter_icon-new.png')}}" alt="#"> </a></li>
                        <li><a href="#"> <img src="{{asset('html/images/hdr_inbox_icon-new.png')}}" alt=""> </a></li>
                        <li class="hdr_alert"><a href="#"> <img src="{{asset('html/images/hdr_alert_icon-new.png')}}" alt=""> </a> <i></i>
                        </li>
                    </ul>
                </div>

                <div class="hdr_rit_search_sec clearfix">

                    <div class="tp_hdr_search_sec">
                        <form id="ent-serach-top" action="/search">
                            <div class="inputWrap">
                                <input class="top-search" type="search" placeholder="Search Anything Here....">
                                <input class="search_btn" type="submit">
                            </div>
                        </form>
                    </div>
                    <a href="#"> <img src="{{asset('html/images/hdr_profile_menu_icon-new.png')}}" alt="#"> </a>
                    <div class="hdr_rt_drop_down">
                        <ul>
                            <li><a href="#"> <i class="fa fa-pencil-square-o" aria-hidden="true"></i> Edit Profile </a>
                            </li>
                            <li><a href="#"> <i class="fa fa-sign-out" aria-hidden="true"></i> Log Out </a></li>
                        </ul>
                    </div>
                </div>
            </div>
            <!--   -->
            <div class="rt_content_outer add">
                @yield('content')
            </div>

        </div>
    </div>
    <div class="footer_outer">
        <p> &copy; Engagement Platform 2018 </p>
    </div>
</div>

<script src="{{asset('/html/js/my_script.js')}}"></script>
<script src="{{asset('/html/js/jquery-1.10.2.js')}}"></script>
<script type="text/javascript" src="{{asset('/html/js/jquery-1.10.2.js')}}"></script>
<script type="text/javascript" src="{{asset('/html/js/jquery-resizable.js')}}"></script>
<script type="text/javascript" src="{{asset('/html/js/jquery-steps.js')}}"></script>
<script src="{{asset('/html/js/jquery.mCustomScrollbar.concat.min.js')}}"></script>
<script type="text/javascript">

    $(document).ready(function () {
        // var has_drop = $('.total_col');
        // $(has_drop).hover(function(e) {

        //   e.preventDefault();
        //     if( $(this).hasClass('active') ){
        //         $(this).removeClass('active');
        //     }
        //     else{
        //         $(this).toggleClass('active');
        //     }

        //     var $this = $(this);
        //     if (
        //         $this.closest('.total_col').find('.total_drop').hasClass('active')) {
        //         $this.closest('.total_col').find('.total_drop').removeClass('active');
        //         $this.closest('.total_col').find('.total_drop').slideUp(350);
        //     }
        //     else {
        //         $this.parent().parent().find('li .inner').removeClass('active');
        //         $this.parent().parent().find('li .inner').slideUp(350);
        //         $this.closest('.total_col').find('.total_drop').toggleClass('active');
        //         $this.closest('.total_col').find('.total_drop').slideToggle(400);
        //     }
        // });
        //TABS

        $('.custom_tabs a').click(function (event) {
            event.preventDefault();
            $('.custom_tabs li').removeClass('active');
            $(this).closest('li').addClass('active');
            var currentTab = $(this).attr('href');
            $('.charts .tab').removeClass('active');
            $(currentTab).addClass('active');
        });


        $('.count').each(function () {
            $(this).prop('Counter', 0).animate({
                Counter: $(this).text()
            }, {
                duration: 4000,
                easing: 'swing',
                step: function (now) {
                    $(this).text(Math.ceil(now));
                }
            });
        });

        // Class Add/ Remove On Wrapper
        $(".hdr_menu_btn").click(function () {
            $(".wpr_content_holder ").toggleClass("left_menu_expand");
        });
        // Left Header button active on click Function
        $(".left_menu_list ul li a").click(function () {
            $(".left_menu_list ul li a").removeClass("active");
            $(this).addClass("active");
        });

        //  campaigns_type Section add/Remove class on parent
        $("#campaigns_type").change(function () {

            var lang_var = $("#campaigns_type option:selected").val();

            $("body").attr("class", lang_var);

        });

        // Compose In-App Messages Tab Function
        $(".pre_comp_title_icons ul li a").click(function (e) {
            $(".pre_comp_title_icons ul li a").removeClass("active");
            $(this).addClass("active");

            var this_id = $(this).attr("href");
            $(".comp_det_sec").hide();
            $(this_id).show();
            return false;

        });
        // Top Header-right Drop Down Function
        $(".hdr_rit_search_sec a").click(function () {
            $(".hdr_rt_drop_down").slideToggle();

        });
        $(".hdr_rt_drop_down ul li a").click(function () {
            $(this).parents(".hdr_rt_drop_down").slideUp();

        });

        // Step function
        $('#demo').steps({
            startAt: 0,
            showBackButton: true,
            showFooterButtons: true,
        });
        //////////////////

        $(".con_event_dropdown span ").click(function (e) {
            $(".con_event_dropdown ul").slideToggle();

        });

        $(".con_event_dropdown a").click(function (e) {
            $(".con_event_dropdown ul ").slideUp();

        });

        //    Step-5 Tab with Dropdown
        $(".con_event_dropdown li a").click(function () {

            var test = $(this).text();
            $('.con_event_dropdown span').text('');
            $('.con_event_dropdown span').text(test);

            var this_id = $(this).attr("tab");
            $('.conversion_event_step').removeClass('active');
            $('#' + this_id).addClass('active');
        });


        $(".rt_aligning_btn ul li a").click(function (e) {
            $(".rt_aligning_btn ul li a").removeClass("active");
            $(this).addClass("active");
        });


        // And Or Toggle Btns Effects

        $(".and_or_toggle_btn ").click(function () {
            $(this).toggleClass('active');


        });


        //  Tab Step-3
        // tab Function
        $(".select_button a").click(function (e) {
            $(".select_button a").removeClass("active");
            $(this).addClass("active");

            var this_id = $(this).attr("href");
            $(".sel_btn_det").fadeOut('fast');
            $(this_id).fadeIn('slow');
            return false;
        });

        // Sec-2 - Email

        //  step-1 temp_Add Remove class

        $(".tamp_list_outer ul li a").click(function (e) {
            $(".tamp_list_outer ul li a").removeClass("active");
            $(this).addClass("active");
        });

        //
        //  Compose In-App Messages Tab Function
        $(".pre_comp_title_icons2 ul li a").click(function (e) {
            $(".pre_comp_title_icons2 ul li a").removeClass("active");
            $(this).addClass("active");

            var this_id = $(this).attr("href");
            $(".comp_det_sec").fadeOut('fast');
            $(this_id).fadeIn('slow');
            return false;

        });


        $(".lst_tbl_drop_outer span").click(function (e) {
            $(this).parent(".lst_tbl_drop_outer").find("ul").slideToggle('slow');
            return false;

        });
        $(".lst_tbl_drop_outer ul li").click(function (e) {
            $(this).parent(".lst_tbl_drop_outer ul").slideUp('slow');
            return false;

        });


        // Splitter function
        $(".panel-left").resizable({
            handleSelector: ".splitter",
            resizeHeight: false,
            resizeWidth: true
        });

        //    Newsfeed title Detail

        // $(".nf_seg_name a").click(function(){
        //   $(this).parent().find('.nf_seg_name_detail').slideToggle(500);
        // });

        //   Listing-left-bottom- Sub-Menu

        $(".db_list_left_sublist h3").click(function () {
            $(this).toggleClass('list_left_sublist_active');
            $(this).parent().find('ul').slideToggle(500);
        });


    });

    //   listing to newsfeed show ///////////
    $("#campaigns_type2").change(function () {

        var lang_var = $("#campaigns_type2 option:selected").val();

        $("body").attr("class", lang_var);
        $(".db_content_holder").show();
        $(".db_content_listing_holder").hide();
    });
    ////////////////////////

</script>
<script type="text/javascript" src="{{asset('/html/js/jquery.canvasjs.min.js')}}"></script>
</body>
@yield('jsSection')
</html>