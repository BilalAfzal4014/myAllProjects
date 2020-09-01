@extends('layouts.master')

@section('create')
    <style>
        .btn-emails {
            float: right;
            background: none;
            color: #666;
            border-radius: 6px;
            padding: 1px 22px 3px 10px;
            line-height: 30px;
            font-size: 14px;
            border: 2px solid #ccc;
            font-weight: 700;
            margin-left: 10px;
        }

        .chart_columns .chart_column {
            border: 1px solid #d0d0d0;
        }

        .chart_columns .row:first-child .chart_column {
            border-bottom: 0;
        }

        .chart_columns .row .chart_column:first-child {
            float: left;
        }

        .chart_columns .row .chart_column:last-child {
            float: right;
            border-left: 0;
        }

        .chart_columns {
            overflow: hidden;
            background: #fff;
        }

        .chart_columns .rows {
            overflow: hidden;
            border-left: 1px solid #d0d0d0;
            border-right: 1px solid #d0d0d0;
        }

        .rt_content_outer {
            padding: 65px 0 36px;
        }

        .totals {
            border-bottom: 10px solid #f0f0f0;
        }

        .dash_chart_head {
            padding: 0 0 20px;
        }

        .inner_content_area {
            background: #fff;
        }

        .inner_content_area {
            padding: 10px 0 0;
        }

        .highcharts-container,
        #newsfeedChart {
            /*height: 400px !important;*/
        }

        .dashboar_charts_outer {
            padding: 0 10px 10px;
            border-bottom: 10px solid #f0f0f0;
        }

        .act_list {
            width: 286px;
            height: 863px;
            float: right;
            margin-left: 20px;
            background: #f6f6f6;
            overflow-y: auto;
        }

        .act_list h2 {
            font-size: 19px;
            font-weight: 500;
            background: #2a8689;
            color: #fff;
            text-align: center;
            padding: 10px 0;
        }

        .act_list ul {
            overflow: hidden;
            font-size: 12px;
            padding: 10px 10px 0;

        }

        .act_list ul li {
            margin: 0 0 6px;
        }

        .heading_stats {
            font-size: 20px;
            letter-spacing: 1px;
            font-weight: 700;
            line-height: 40px;
            color: #2a8689;
            padding: 9px 0 0;
        }

        .fluid_cols {
            overflow: hidden;
        }

        /*.highchartscss{*/
        /*height:100%;*/
        /*width:100%;*/
        /*position:absolute;*/
        /*}*/
        .highcharts-title tspan {
            width: 10px !important;
        }

    </style>
    @php
        $roleArr = Auth::user()->roles()->pluck('name')->toArray();

    @endphp
    @if( !in_array('SUPER-ADMIN', $roleArr))

    <div class="totals">
        <div class="total_col">
            <strong>Users</strong>
            <span>{{ $data['users']['total'] }}</span>
        </div>
        <div class="total_col">
            <strong>Campaign Total Sent <i class="fa fa-caret-down" aria-hidden="true"></i></strong>
            <span class="count">{{ $data['campaign']['sent']['total'] }}</span>
            <ul class="total_drop">
                <li>
                    <a href="#">
                        <span>{{ $data['campaign']['sent']['email'] }}</span>
                        <em><i class="fa fa-envelope" aria-hidden="true"></i> Email</em>
                    </a>
                </li>
                <li>
                    <a href="#">
                        <span>{{ $data['campaign']['sent']['push'] }}</span>
                        <em><i class="fa fa-paper-plane" aria-hidden="true"></i> Push</em>
                    </a>
                </li>
                <li>
                    <a href="#">
                        <span>{{ $data['campaign']['sent']['inapp'] }} </span>
                        <em><i class="fa fa-archive" aria-hidden="true"></i> In App</em>
                    </a>
                </li>
            </ul>
        </div>
        <div class="total_col">
            <strong>Campaign Total Fail <i class="fa fa-caret-down" aria-hidden="true"></i></strong>
            <span class="count">{{ $data['campaign']['failed']['total'] }}</span>
            <ul class="total_drop">
                <li>
                    <a href="#">
                        <span>{{ $data['campaign']['failed']['email'] }}</span>
                        <em><i class="fa fa-envelope" aria-hidden="true"></i> Email</em>
                    </a>
                </li>
                <li>
                    <a href="#">
                        <span>{{ $data['campaign']['failed']['push'] }} </span>
                        <em><i class="fa fa-paper-plane" aria-hidden="true"></i> Push</em>
                    </a>
                </li>
                <li>
                    <a href="#">
                        <span>{{ $data['campaign']['failed']['inapp'] }}</span>
                        <em><i class="fa fa-archive" aria-hidden="true"></i> In App</em>
                    </a>
                </li>
            </ul>
        </div>
        <div class="total_col">
            <strong>Campaign In Queue <i class="fa fa-caret-down" aria-hidden="true"></i></strong>
            <span class="count">{{ $data['campaign']['queued']['total'] }}</span>
            <ul class="total_drop">
                <li>
                    <a href="#">
                        <span>{{ $data['campaign']['queued']['email'] }}</span>
                        <em><i class="fa fa-envelope" aria-hidden="true"></i> Email</em>
                    </a>
                </li>
                <li>
                    <a href="#">
                        <span>{{ $data['campaign']['queued']['push'] }}</span>
                        <em><i class="fa fa-paper-plane" aria-hidden="true"></i> Push</em>
                    </a>
                </li>
                <li>
                    <a href="#">
                        <span>{{ $data['campaign']['queued']['inapp'] }}</span>
                        <em><i class="fa fa-archive" aria-hidden="true"></i> In App</em>
                    </a>
                </li>
            </ul>
        </div>
        <div class="total_col">
            <strong>Newsfeed Clicks <i class="fa fa-caret-down" aria-hidden="true"></i></strong>
            <span class="count">{{ $data['newsfeed']['clicks']['total'] }}</span>
            <ul class="total_drop">
                <li>
                    <a href="#">
                        <span>{{ $data['newsfeed']['clicks']['ios'] }} </span>
                        <em><i class="fa fa-windows" aria-hidden="true"></i> IOS</em>
                    </a>
                </li>
                <li>
                    <a href="#">
                        <span>{{ $data['newsfeed']['clicks']['android'] }}</span>
                        <em><i class="fa fa-android" aria-hidden="true"></i> Android</em>
                    </a>
                </li>
                <li>
                    <a href="#">
                        <span>{{ $data['newsfeed']['clicks']['web'] }}</span>
                        <em><i class="fa fa-apple" aria-hidden="true"></i> Web</em>
                    </a>
                </li>
            </ul>
        </div>
        <div class="total_col">
            <strong>Newsfeed Views <i class="fa fa-caret-down" aria-hidden="true"></i></strong>
            <span class="count">{{ $data['newsfeed']['views']['total'] }}</span>
            <ul class="total_drop">
                <li>
                    <a href="#">
                        <span>{{ $data['newsfeed']['views']['ios'] }}</span>
                        <em><i class="fa fa-envelope" aria-hidden="true"></i> IOS</em>
                    </a>
                </li>
                <li>
                    <a href="#">
                        <span>{{ $data['newsfeed']['views']['android'] }}</span>
                        <em><i class="fa fa-paper-plane" aria-hidden="true"></i> Android</em>
                    </a>
                </li>
                <li>
                    <a href="#">
                        <span>{{ $data['newsfeed']['views']['web'] }}</span>
                        <em><i class="fa fa-archive" aria-hidden="true"></i> Web</em>
                    </a>
                </li>
            </ul>
        </div>
        <div class="total_col">
            @if( !in_array('SUPER-ADMIN', $roleArr))
                <strong><a href="#" class="quick_acts"><i class="fa fa-plus" aria-hidden="true"></i> Quick
                        Actions</a></strong>
                <ul class="total_drop">
                    <li>
                        <a href="{{ route('campaignCreate') }}?select=email">
                            <em><i class="fa fa-plus" aria-hidden="true"></i> Create Email</em>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('campaignCreate') }}?select=inApp">
                            <em><i class="fa fa-rocket" aria-hidden="true"></i> Create in App</em>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('campaignCreate') }}?select=push">
                            <em><i class="fa fa-plus" aria-hidden="true"></i> Create Push</em>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('newsfeedcreate') }}">
                            <em><i class="fa fa-rss" aria-hidden="true"></i> Newsfeed</em>
                        </a>
                    </li>
                </ul>
            @endif

        </div>

    </div>

    <div class="inner_content_area">
        <div class="dashboar_charts_sec">
            <div class="dashboar_charts_outer">

                <div class="fluid_cols">
                    <div class="act_list">
                        <h2>Recent Users</h2>
                        <ul>
                            @for($val=0;$val<count($latestUsers);$val++)
                                <li style="overflow:hidden;">
                                    <a style="cursor: pointer;"
                                       href="{{url('backend/attribute/user/Stat/'.$latestUsers[$val]['row_id'])}}">
                                        <img src="{{url('assets/images/profile_placeholder.png')}}"
                                             style="height: 40px; width: 40px; float:left;">
                                        <div style="font-size: 12px;overflow:hidden; padding-left:10px; word-break: break-all;">
                                            <b>User id </b>:{{$latestUsers[$val]['user_id']}}
                                            <br>
                                            <b>Email</b> : {{$latestUsers[$val]['email']}}
                                        </div>
                                    </a>
                                </li>
                            @endfor
                        </ul>
                    </div>
                    <div class="chart_columns">
                        <div class="dash_chart_head clearfix">
                            <div class="dash_chart_right clearfix">
                                @if( in_array('SUPER-ADMIN', $roleArr))
                                    <a href="{{url('/emailListing')}}" class="btn btn-primary btn-emails">Email List</a>
                                @endif
                                <div class="chart_select_seq inp_select  b_r">
                                    {{ \Form::select('campaign_graph',
                                         [
                                            'today'             => 'Today',
                                            'yesterday'         => 'Yesterday',                                           
                                            'last-30-days'      => 'Last 30 days', 
                                            'last-6-months'     => 'Last 6 Months',                                            
                                         ],
                                         'today',
                                         [
                                            'id'            => 'campaign_graph_intervals',
                                            'class'         => 'form-control pb-1',
                                            'placeholder'   => 'Select Duration ...'
                                         ]
                                    )}}
                                </div>
                            </div>
                            <div class="dash_chart_left">
                                <label for=""> Statistics </label>
                            </div>

                        </div>
                        <div class="rows">
                            <div class="col-sm-12">
                                <div class="row">
                                    <div class="campaignChart emailChart col-sm-6 pt-1 chart_column">
                                        <div id="emailChart"></div>
                                    </div>
                                    <div class="campaignChart pushChart col-sm-6 pt-1 chart_column">
                                        <div id="pushChart"></div>
                                    </div>
                                </div>
                                <div class="row">

                                    <div class="campaignChart inappChart col-sm-6 pt-1 chart_column">
                                        <div id="inappChart"></div>
                                    </div>
                                    <div class="campaignChart newsfeedChart col-sm-6 pt-1 chart_column">
                                        <div id="newsfeedChart"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="dashboar_charts_outer" style="padding-top: 50px;">
                <div class="dash_chart_head clearfix">
                    <div class="dash_chart_left">
                        <label for=""> Conversion </label>
                    </div>
                    <div class="dash_chart_right clearfix">
                        <div class="chart_select_seq inp_select  b_r">
                            {{--{{ \Form::select('conversion_graph',--}}
                            {{--[--}}
                            {{--'Ios'=> 'Ios',--}}
                            {{--'Android'=> 'Android',--}}
                            {{--'Web'=> 'Web',--}}
                            {{--],--}}
                            {{--'',--}}
                            {{--[--}}
                            {{--'id'            => 'conversion_graph_intervalsByAppName',--}}
                            {{--'class'         => 'form-control pb-1',--}}
                            {{--'placeholder'   => 'Select App Name ...'--}}
                            {{--]--}}
                            {{--)}}--}}
                            <select class="form-control pb-1" id="conversion_graph_intervalsByAppName">
                                {{--//<option value="">Select App Name</option>--}}
                                @for($val=0;$val<count($userlist);$val++)
                                    <option value="{{$userlist[$val]->name}}">{{$userlist[$val]->name}}</option>
                                @endfor
                            </select>
                        </div>
                        <span style="float: right;/* margin:0 5px; */width: 10px;height: 1px;"></span>
                        <div class="chart_select_seq inp_select  b_r">
                            {{ \Form::select('conversion_graph',
                                 [
                                    'today'             => 'Today',
                                    'yesterday'         => 'Yesterday',                                    
                                    'last-30-days'      => 'Last 30 days',                                   
                                    'last-6-months'     => 'Last 6 Months',
                                   
                                 ],
                                 'today',
                                 [
                                    'id'            => 'conversion_graph_intervals',
                                    'class'         => 'form-control pb-1',
                                    'placeholder'   => 'Select Duration ...'
                                 ]
                            )}}
                        </div>
                    </div>
                </div>
                <div id="conversionChart"></div>
            </div>
        </div>
    </div>
    @endif
@stop
@section('jsSection')
    @if( !in_array('SUPER-ADMIN', $roleArr))
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
    <script src="https://code.highcharts.com/highcharts.js"></script>
    <script src="https://code.highcharts.com/modules/series-label.js"></script>
    <script src="https://code.highcharts.com/modules/exporting.js"></script>
    <script src="{{ asset('js/newDashboard.js') }}"></script>
    <script>
        $(".db_content_listing_holder").css({'display': 'none'});
    </script>
    @endif
@stop
