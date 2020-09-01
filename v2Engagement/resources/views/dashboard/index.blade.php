@extends('layouts.master')

@section('create')

    @php
        $roleArr = Auth::user()->roles()->pluck('name')->toArray()
    @endphp
    <div class="db_content_holder dashboard_content step-app" style="display:block;">
        <div class="dashboard_stats_outer clearfix">
            <div class="reachable_usr_app_message_otr dashboard_stats clearfix">
                <div class=" dash_stats_list">
                    <p>Users</p>
                    <span>{{ $data['users']['total'] }}</span>
                </div>
                <div class=" dash_stats_list">
                    <p>Campaign Total Sent</p>
                    <span>{{ $data['campaign']['sent']['total'] }}</span>
                    <div class="more_detail">
                        <ul>
                            <li><p> Email <span> {{ $data['campaign']['sent']['email'] }} </span></p></li>
                            <li><p> Push <span> {{ $data['campaign']['sent']['push'] }} </span></p></li>
                            <li><p> In App <span> {{ $data['campaign']['sent']['inapp'] }} </span></p></li>
                        </ul>
                    </div>
                </div>
                <div class=" dash_stats_list">
                    <p>Campaign Total Fail</p>
                    <span>{{ $data['campaign']['failed']['total'] }}</span>
                    <div class="more_detail">
                        <ul>
                            <li><p> Email <span> {{ $data['campaign']['failed']['email'] }} </span></p></li>
                            <li><p> Push <span> {{ $data['campaign']['failed']['push'] }} </span></p></li>
                            <li><p> In App <span> {{ $data['campaign']['failed']['inapp'] }} </span></p></li>
                        </ul>
                    </div>
                </div>
                <div class=" dash_stats_list">
                    <p>Campaign In Queue</p>
                    <span>{{ $data['campaign']['queued']['total'] }}</span>
                    <div class="more_detail">
                        <ul>
                            <li><p> Email <span> {{ $data['campaign']['queued']['email'] }} </span></p></li>
                            <li><p> Push <span> {{ $data['campaign']['queued']['push'] }} </span></p></li>
                            <li><p> In App <span> {{ $data['campaign']['queued']['inapp'] }} </span></p></li>
                        </ul>
                    </div>
                </div>
                <div class=" dash_stats_list">
                    <p>Newsfeed Clicks</p>
                    <span>{{ $data['newsfeed']['clicks']['total'] }}</span>
                    <div class="more_detail">
                        <ul>
                            <li><p> IOS <span> {{ $data['newsfeed']['clicks']['ios'] }} </span></p></li>
                            <li><p> Android <span>  {{ $data['newsfeed']['clicks']['android'] }}</span></p></li>
                            <li><p> Web <span>  {{ $data['newsfeed']['clicks']['web'] }} </span></p></li>
                        </ul>
                    </div>
                </div>
                <div class=" dash_stats_list">
                    <p>Newsfeed Views</p>
                    <span>{{ $data['newsfeed']['views']['total'] }}</span>
                    <div class="more_detail">
                    <ul>
                        <li><p> IOS <span> {{ $data['newsfeed']['views']['ios'] }} </span></p></li>
                        <li><p> Android <span>  {{ $data['newsfeed']['views']['android'] }}</span></p></li>
                        <li><p> Web <span>  {{ $data['newsfeed']['views']['web'] }} </span></p></li>
                    </ul>
                    </div>
                </div>
            </div>
            @if( !in_array('SUPER-ADMIN', $roleArr))
            <div class=" dash_stats_right">
                <div class=" inp_select">
                    <select id="dashboard_quick_action">
                        <option value=""> Quick Actions</option>
                        <option value="{{ route('campaignCreate') }}?select=email">Create Email</option>
                        <option value="{{ route('campaignCreate') }}?select=inApp">Create In App</option>
                        <option value="{{ route('campaignCreate') }}?select=push">Create Push</option>
                        <option value="{{ route('newsfeedcreate') }}">Create Newsfeed</option>
                    </select>
                </div>

            </div>
            @endif
        </div>

        <div class="dashboar_charts_sec">
            <div class="dashboar_charts_outer">
                <div class="dash_chart_head clearfix">
                    <div class="dash_chart_left">
                        <label for="">Campaigns</label>
                        <ul id="campaigntypes" class="nav nav-tabs">
                            <li class="active" onclick="javascript: showCampaignGraph('emailChart');">
                                <a data-toggle="tab" href="#campaign-email" class="font-size18px">Email</a>
                            </li>
                            <li onclick="javascript: showCampaignGraph('pushChart');">
                                <a data-toggle="tab" href="#campaign-push" class="font-size18px">Push</a>
                            </li>
                            <li onclick="javascript: showCampaignGraph('inappChart');">
                                <a data-toggle="tab" href="#campaign-inapp" class="font-size18px">InApp</a>
                            </li>
                        </ul>
                    </div>
                    <div class="dash_chart_right clearfix">
                        <div class="chart_select_seq inp_select  b_r">
                            {{ \Form::select('campaign_graph',
                                 [
                                    'today'             => 'Today',
                                    'yesterday'         => 'Yesterday',
                                    'last-7-days'       => 'Last 7 days',
                                    'last-30-days'      => 'Last 30 days',
                                    'last-3-months'     => 'Last 3 Months',
                                    'last-6-months'     => 'Last 6 Months',
                                    'last-12-months'    => 'Last 12 Months'
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
                    <div class="tab-content" style="padding-bottom: 50px;">
                        <div id="campaign-email" class="tab-pane fade in active"></div>
                        <div id="campaign-push" class="tab-pane fade"></div>
                        <div id="campaign-inapp" class="tab-pane fade"></div>
                    </div>
                    <div class="campaignChart emailChart col-md-12 pt-1">
                        <div id="emailChart"></div>
                    </div>
                    <div class="campaignChart pushChart col-md-12 pt-1 hide">
                        <div id="pushChart"></div>
                    </div>
                    <div class="campaignChart inappChart col-md-12 pt-1 hide">
                        <div id="inappChart"></div>
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
                            {{ \Form::select('conversion_graph',
                                 [
                                    'today'             => 'Today',
                                    'yesterday'         => 'Yesterday',
                                    'last-7-days'       => 'Last 7 days',
                                    'last-30-days'      => 'Last 30 days',
                                    'last-3-months'     => 'Last 3 Months',
                                    'last-6-months'     => 'Last 6 Months',
                                    'last-12-months'    => 'Last 12 Months'
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
                <div id="conversionChart" style="min-width: 310px; height: 400px; margin: 0 auto"></div>
            </div>

            <div class="dashboar_charts_outer" style="padding-top: 50px;">
                <div class="dash_chart_head clearfix">
                    <div class="dash_chart_left">
                        <label for=""> News Feed </label>
                    </div>
                    <div class="dash_chart_right clearfix">
                        <div class="chart_select_seq inp_select  b_r">
                            {{ \Form::select('newsfeed_graph',
                                 [
                                    'today'             => 'Today',
                                    'yesterday'         => 'Yesterday',
                                    'last-7-days'       => 'Last 7 days',
                                    'last-30-days'      => 'Last 30 days',
                                    'last-3-months'     => 'Last 3 Months',
                                    'last-6-months'     => 'Last 6 Months',
                                    'last-12-months'    => 'Last 12 Months'
                                 ],
                                 'today',
                                 [
                                    'id'            => 'newsfeed_graph_intervals',
                                    'class'         => 'form-control pb-1',
                                    'placeholder'   => 'Select Duration ...'
                                 ]
                            )}}
                        </div>
                    </div>
                </div>
                <div id="newsfeedChart"
                     style="min-width: 310px; height: 400px; margin: 0 auto; padding-bottom: 50px;"></div>
            </div>

        </div>
    </div>
    <script type="text/javascript">

        $(document).ready(function(){
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

            $('.custom_tabs a').click(function(event){
                event.preventDefault();
                $('.custom_tabs li').removeClass('active');
                $(this).closest('li').addClass('active');
                var currentTab = $(this).attr('href');
                $('.charts .tab').removeClass('active');
                $(currentTab).addClass('active');
            });


            $('.count').each(function () {
                $(this).prop('Counter',0).animate({
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
            $(".hdr_menu_btn").click(function(){
                $(".wpr_content_holder ").toggleClass("left_menu_expand");
            });
            // Left Header button active on click Function
            $(".left_menu_list ul li a").click(function(){
                $(".left_menu_list ul li a").removeClass("active");
                $(this).addClass("active");
            });

            //  campaigns_type Section add/Remove class on parent
            $("#campaigns_type").change(function(){

                var lang_var = $("#campaigns_type option:selected").val();

                $("body").attr("class",lang_var);

            });

            // Compose In-App Messages Tab Function
            $(".pre_comp_title_icons ul li a").click(function(e){
                $(".pre_comp_title_icons ul li a").removeClass("active");
                $(this).addClass("active");

                var this_id = $(this).attr("href");
                $(".comp_det_sec").hide();
                $(this_id).show( );
                return false;

            });


            // Step function
            $('#demo').steps({
                startAt: 0,
                showBackButton: true,
                showFooterButtons: true,
            });
            //////////////////

            $(".con_event_dropdown span ").click(function(e){
                $(".con_event_dropdown ul").slideToggle();

            });

            $(".con_event_dropdown a").click(function(e){
                $(".con_event_dropdown ul ").slideUp();

            });

            //    Step-5 Tab with Dropdown
            $(".con_event_dropdown li a").click(function() {

                var test = $(this).text();
                $('.con_event_dropdown span').text('');
                $('.con_event_dropdown span').text(test);

                var this_id = $(this).attr("tab");
                $('.conversion_event_step').removeClass('active');
                $('#' + this_id).addClass('active');
            });


            $(".rt_aligning_btn ul li a").click(function(e){
                $(".rt_aligning_btn ul li a").removeClass("active");
                $(this).addClass("active");
            });


            // And Or Toggle Btns Effects

            $(".and_or_toggle_btn ").click(function() {
                $(this).toggleClass('active');


            });


            //  Tab Step-3
            // tab Function
            $(".select_button a").click(function(e){
                $(".select_button a").removeClass("active");
                $(this).addClass("active");

                var this_id = $(this).attr("href");
                $(".sel_btn_det").fadeOut('fast');
                $(this_id).fadeIn('slow');
                return false;
            });

            // Sec-2 - Email

            //  step-1 temp_Add Remove class

            $(".tamp_list_outer ul li a").click(function(e){
                $(".tamp_list_outer ul li a").removeClass("active");
                $(this).addClass("active");
            });

            //
            //  Compose In-App Messages Tab Function
            $(".pre_comp_title_icons2 ul li a").click(function(e){
                $(".pre_comp_title_icons2 ul li a").removeClass("active");
                $(this).addClass("active");

                var this_id = $(this).attr("href");
                $(".comp_det_sec").fadeOut('fast');
                $(this_id).fadeIn('slow');
                return false;

            });


            $(".lst_tbl_drop_outer span").click(function(e){
                $(this).parent(".lst_tbl_drop_outer").find("ul").slideToggle('slow');
                return false;

            });
            $(".lst_tbl_drop_outer ul li").click(function(e){
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

            $(".db_list_left_sublist h3").click(function(){
                $(this).toggleClass('list_left_sublist_active');
                $(this).parent().find('ul').slideToggle(500);
            });




        });

        //   listing to newsfeed show ///////////
        $("#campaigns_type2").change(function(){

            var lang_var = $("#campaigns_type2 option:selected").val();

            $("body").attr("class",lang_var);
            $(".db_content_holder").show();
            $(".db_content_listing_holder").hide();
        });
        ////////////////////////

    </script>

@stop

@section('jsSection')
<script src="https://code.highcharts.com/highcharts.js"></script>
<script src="https://code.highcharts.com/modules/series-label.js"></script>
<script src="https://code.highcharts.com/modules/exporting.js"></script>
<script src="{{ asset('js/dashboard.js') }}"></script>
<script>
    $(".db_content_listing_holder").css({'display': 'none'});
</script>
@stop