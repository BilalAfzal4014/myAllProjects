@extends('layouts.master')

@section('create')
    <link href="{{asset('/assets/plugins/queryBuilder/css/query-builder.default.css')}}" rel="stylesheet"
          type="text/css">
    <input class="companyId" type="hidden" value="{{$companyId}}">
    <input id="segmentAction" type="hidden" value="{{$action or ''}}">
    <input id="segmentEditionId" type="hidden" value="{{$id or ''}}">
    <div class="db_content_holder step-app">

        <div class="tp_BreadCrumb_list_sec clearfix">

            <label id="segmentTitle" class="sec_tp_title"> Segment Details > </label>

            <div class="track_userdeta_right clearfix">
                <div class="Ana_track_switch">
                    <strong>
                        Analytic Tracking
                        <label class="switch">
                            <input type="checkbox">
                            <span class="slider round"></span>
                        </label>
                    </strong>
                </div>

                {{--<div class="uder_deta_dropdown">
                    <div class=" inp_select">
                        <select>
                            <option>Actions</option>
                            <option> User Data 1</option>
                            <option> User Data 2</option>
                        </select>
                    </div>


                </div>--}}

            </div>

        </div>
        <!-- Add these class for other Sections  segment_sec_1, segment_sec_2 , segment_sec_3 -->
        <div class="segment_content_outer segment_sec_1 clearfix">

            <div class="Campaigns_input b_r">
                <input maxlength="30" id="campaignTitle" type="text" name="campaignTitle" value=""
                       placeholder="Segment Title ">
            </div>
            <span id="campaignError" style="color: #F99; position: relative; top: -10px;"></span>

            <input type="text" value="" name="tags" class="tags" placeholder="Enter Tag(s)"/>
            <span id="tagsError" style="color: #F99"></span>

            {{--<div class="seg_app_usag_viewed clearfix">
                <ul>
                    <li>
                        <p>Apps Used</p>

                    </li>
                    <li>
                        <div class="camp_timing_check_box">
                            <input type="checkbox" id="rec_comp3" name="contact" value="email" checked="">
                            <label for="rec_comp3">Include users form all apps </label>
                        </div>
                    </li>
                    <li>
                        <div class="distribute_filter">
                            <p><b></b> 0 Changes Since Last Viewed</p>
                        </div>
                    </li>

                </ul>
            </div>--}}

            <div class="con_event_list_outer clearfix">
                <div class="conversion_event_list ">

                    <div class="camp_timing_check_box">
                        <input type="checkbox" id="sesssion_app1" name="contact" value="email" checked="">
                        <label for="sesssion_app1"> </label>
                    </div>
                    <span>
                           <img src="{{asset('/assets/images/app_beat_img.png')}}" alt="#">
                         </span>
                    <p>
                        Beats by dr.dre
                        <b>Android</b>
                    </p>

                </div>
                <div class="conversion_event_list">

                    <div class="camp_timing_check_box">
                        <input type="checkbox" id="sesssion_app2" name="contact" value="email" checked="">
                        <label for="sesssion_app2"> </label>
                    </div>
                    <span>
                           <img src="{{asset('/assets/images/app_beat_img.png')}}" alt="#">
                         </span>
                    <p>
                        Beats by dr.dre
                        <b>Ios</b>
                    </p>

                </div>
                <div class="conversion_event_list">

                    <div class="camp_timing_check_box">
                        <input type="checkbox" id="sesssion_app3" name="contact" value="email" checked="">
                        <label for="sesssion_app3"> </label>
                    </div>
                    <span>
                           <img src="{{asset('/assets/images/app_beat_img.png')}}" alt="#">
                         </span>
                    <p>
                        Beats by dr.dre
                        <b>Web</b>
                    </p>


                </div>
                <div class="conversion_event_list">
                    <div class="camp_timing_check_box">
                        <input type="checkbox" id="sesssion_app4" name="contact" value="email" checked="">
                        <label for="sesssion_app4"> </label>
                    </div>
                    <span>
                           <img src="{{asset('/assets/images/app_beat_img.png')}}" alt="#">
                         </span>
                    <p>
                        Beats by dr.dre
                        <b>Windows Phone 8</b>
                    </p>
                </div>

            </div>

            <div style="margin-top: 18px;" id="queryBuilderGoesHere"></div>
            {{-- <button class="btn btn-success" id="btn-set">Set Rules</button>--}}
            <button class="btn btn-warning" id="btn-reset">Reset</button>

            {{--<div class="camp_title" style="margin-top: 28px;">--}}
            {{--<h3> Messaging Use </h3>--}}
            {{--</div>--}}

            <div class="seg_messages_btn_outer">
                {{--<div class="seg_messages_use_sec_outer clearfix">--}}
                {{--<div class="messages_use_sec">--}}
                {{--<h3>Segments</h3>--}}
                {{--<p>This segment is not used by any Segments.</p>--}}
                {{--</div>--}}
                {{--<div class="messages_use_sec">--}}
                {{--<h3>Campaign</h3>--}}
                {{--<p>This segment is not used by any Campaign.</p>--}}
                {{--</div>--}}
                {{--<div class="messages_use_sec">--}}
                {{--<h3>News Feed Items</h3>--}}
                {{--<p>This segment is not used by any news feed items.</p>--}}
                {{--</div>--}}
                {{--</div>--}}
                <div class="messages_use_btns clearfix">
                    <ul>
                        <li>
                            <button id="submitSegment" type="button" name="button">Save</button>
                        </li>
                    </ul>
                </div>
            </div>

        </div>
        <!--  -->
        <div class="step-footer save_next_sec segment_footer">

            <div class="reachable_usr_app_message_otr">
                <div class="reachable_user">
                    <p> Using in Campaigns</p>
                    <span class="campaignCount"></span>
                </div>
                <div class="reachable_user">
                    <p> Using in News feeds</p>
                    <span class="newFeedCount"></span>
                </div>
                {{--<div class="reachable_user">--}}
                {{--<p> Reachable Users <i class="noticification_icon"></i></p>--}}
                {{--</div>--}}
                {{--<div class="app_message">--}}
                {{--<p>Total</p>--}}
                {{--<span>1,680,650</span>--}}
                {{--</div>--}}
                {{--<div class="app_message">--}}
                {{--<p>Email</p>--}}
                {{--<span>1,355,527</span>--}}
                {{--</div>--}}
                {{--<div class="app_message">--}}
                {{--<p>Web Push</p>--}}
                {{--<span>0</span>--}}
                {{--</div>--}}
                {{--<div class="app_message">--}}
                {{--<p>iOS Push</p>--}}
                {{--<span>322,991</span>--}}
                {{--</div>--}}
                {{--<div class="app_message">--}}
                {{--<p>Android</p>--}}
                {{--<span>370,213</span>--}}
                {{--</div>--}}
                {{--<div class="app_message">--}}
                {{--<p>Window B Push</p>--}}
                {{--<span>0</span>--}}
                {{--</div>--}}
            </div>

        </div>

    </div>
@stop


@section('jsSection')
    <script src="{{asset('/assets/plugins/queryBuilder/js/query-builder.standalone.js')}}"></script>
    <script src="{{asset('/assets/js/segment/segmentCrud.js')}}"></script>
    <script>
        $("body").attr("class", 'email_html-2');
        $(".db_content_holder").css({'display': 'block'});
        $(".db_content_listing_holder").css({'display': 'none'});
    </script>
    <style>
        .bootstrap-tagsinput .tag {
            text-transform: none !important;
        }

        .save_next_sec {
            padding: 48px 28px 10px 72px !important;
        }

        .save_next_sec p {
            margin: 0 0 0 3px !important;
        }

        .reachable_usr_app_message_otr .app_message {
            vertical-align: top !important;
        }
    </style>
@stop
