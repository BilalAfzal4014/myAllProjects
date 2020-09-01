@extends('layouts.master')

@section('searchBar')
    <div class="tp_BreadCrumb_list_sec clearfix" xmlns="http://www.w3.org/1999/html">
        <label class="sec_tp_title"> News Feed </label>
        <div class="track_userdeta_right clearfix">
            <div class="tp_BreadCrumb_srch_inp">
                <input id="searchBar" type="search" name="search" value="" placeholder="Search...">
            </div>
            <div class="uder_deta_dropdown">
                <div class=" inp_select">
                    <select id="campaigns_type2">
                        <option value="">Actions</option>
                        <option value="">Create Card</option>
                    </select>
                </div>
            </div>
        </div>
    </div>
@stop

@section('create')
    <input type="hidden" class="companyId" value="{{$companyId}}">
    <div>
        <form id="form_id1" class="newsfeed" name="newsfeed" autocomplete="off" method="post" action=""
              enctype="multipart/form-data">
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
            <input type="hidden" name="newsfeedID" id="newsfeedID" value="0">
            <input type="hidden" name="is_active" id="is_active" value="draft">
            <input type="hidden" name="newsfeedStep" id="newsfeedStep" value="COMPOSE">
            <div id="MSG"></div>
            <div class="db_content_holder step-app">

                <div class="tp_BreadCrumb_list_sec clearfix">

                    <label class="sec_tp_title"> News Feed &gt; <span id="newsNameId"> Card Name <span></label>

                </div>

                <div class="savedraft_no_pub_sec">
                    <h3>Saved Draft – Not Published</h3>
                    <p>Once your Campaign has been launched. It will begin sending
                        messages to your users and will no longer be listed as a draft.
                    </p>
                    <span> Last Edited. April 26, 2018 by Jawad Ashraf  </span>
                </div>

                <div class="breadcrumb_steps_outer">
                    <ul class="step-steps">
                        <li class="active"><a href="#step1"> 1. Compose &amp; Schedule </a></li>
                        <li><a href="#step2"> 2. Delivery </a></li>
                        <li><a href="#step6"> 3. Confirm </a></li>
                    </ul>
                </div>

                <div class="breadcrumb_step_det_outer step-content">

                    <div class="bcd_stp1 step-tab-panel nf_step_1 active" id="step1">
                        <span id="nameError" class="inputError" data-duplicate="0" style="color: #f00;"></span>
                        <div class="Campaigns_input b_r">
                            <input type="text" name="m_name" id="m_name" value="" maxlength="30"
                                   placeholder="Enter Card Name">
                        </div>
                        <span id="newstagsError" class="inputError" style="color: #f00;"></span>
                        <input type="text" name="newstags" placeholder="Enter tags" id="newstags" value=""
                               class="tags"/>
                        <div class="prev_comp_sec_outer nf_prev_compos_sec clearfix">

                            <div class="left_pre_comp_sec b_r" style="height: auto;">
                                <div class="pre_comp_title">
                                    <label> <span id="newsFeedCardType"></span> Newsfeed Preview </label>
                                </div>

                                <div class="pre_comp_body">

                                    <div class="preview_selection clearfix" style="margin: 10px;">
                                        <span id="typeError" class="inputError" style="color: #f00;"></span>
                                        <div class="inp_select b_r">

                                            <select name="type_id" data-placeholder="Please select one..."
                                                    id="select_card_type" required class="" tabindex="1">
                                                <option value="">Please Card Type</option>
                                                @foreach($templates as $template)
                                                    <option value="{{$template->id}}">{{$template->name}}</option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="nf_temp_img_sec1">
                                            <div class="col-xs-12" style="border: 1px dotted #ccc" id="preview_div">

                                                <a id="[{id}]" href="#" style="display:block; text-decoration:none">
                                                    <div class="content_holder">
                                                        <div style="overflow:hidden; max-width:767px; padding:10px; margin:0 auto;">
                                                            <div style="font:14px/20px Arial, Helvetica, sans-serif; background:#fff; overflow:hidden; padding:20px; margin:0; border:1px solid #e0e0e0;">
                                                                <div style="display:inline-block; vertical-align: middle; width:20%;">
                                                                    <img id="icon"
                                                                         src="{{asset('/assets/images/ureka_logo2.png')}}"
                                                                         alt="img description"
                                                                         style="display:block; width:100%; height:auto;">
                                                                </div>
                                                                <div style="padding-left:14px; width:76%; display:inline-block; vertical-align: middle; overflow:hidden; text-align:left;">
                                                                    <h2 id="title"
                                                                        style="text-align:left; font:700 18px/20px Arial, Helvetica, sans-serif; color:#031730; ">
                                                                        Dummy Title</h2>
                                                                    <p id="description"
                                                                       style="padding-left:0; padding-bottom: 0; text-align: left; color:#4f4f4f; ">
                                                                        Dummy Description</p>
                                                                    <span id="link_title"
                                                                          style="font:12px/16px Arial, Helvetica, sans-serif; color:#27c1cd; text-decoration:none; text-transform:uppercase;">Dummy Text</span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </a>
                                            </div>
                                        </div>

                                        <div class="nf_search_user_id hide">
                                            <label>Search for a user to preview personalization on this card. </label>

                                            <div class="sett_input_sec nf_search_user_inp b_r hide">
                                                <input type="text" name="" value=""
                                                       placeholder="Email Address or External User ID">
                                                <button type="button" name="button"></button>
                                            </div>

                                        </div>

                                    </div>

                                </div>
                            </div>

                            <div class="right_pre_comp_sec b_r">
                                <div class="pre_comp_title">
                                    <label> Compose </label>
                                </div>

                                <div class="pre_comp_body">
                                    <div class="comp_detail_outer">

                                        <div class="comp_det_sec comp_write_sec nf_compose_sec" id="comp_write_sec"
                                             style="display:block;">
                                            <div class="comp_input_form">
                                                <div class="comp_input_sec p_t_b ">
                                                    <label>Title</label>
                                                    <span id="titleError" class="inputError"
                                                          style="color: #f00;"></span>
                                                    <div class="Campaigns_input b_r nf_plus_icon_add">
                                                        <input type="text" name="m_title" maxlength="30" id="m_title"
                                                               value=""
                                                               placeholder="Enter Card Name">
                                                    </div>

                                                </div>
                                                <div class="comp_input_sec p_t_b">
                                                    <label>Message</label>
                                                    <span id="descError" class="inputError" style="color: #f00;"></span>
                                                    <div class="nf_plus_icon_add">
                                                        <textarea maxlength="250" name="m_desc" id="m_desc" class="b_r "
                                                                  rows="5"
                                                                  cols="50"> </textarea>
                                                    </div>
                                                </div>
                                                <div class="add_tag_sec_outer   clearfix">
                                                    <label>Image</label><br>
                                                    <button type="button" id="open_model" class="btn form-control"
                                                            style="width:155px;background: #2a8689;    color: white">
                                                        Launch image gallery
                                                    </button>
                                                    <br>
                                                    <input class="form-control hide" id="i_file" type="file"
                                                           name="userfile" size="20">
                                                    <input class="form-control" id="image_url" type="hidden"
                                                           name="image_url" size="20">
                                                    <label>Action text</label>
                                                    <input maxlength="255" type="text" name="text_link" value=""
                                                           id="m_link_text"
                                                           class="form-control" placeholder="Link Text">
                                                </div>
                                                <div class="device_option_title p_t_b">
                                                    <label> On Click Behavior </label>
                                                </div>
                                                <div class="comp_input_sec  ">
                                                    <div style="margin-bottom: 15px;">
                                                        <div class="Campaigns_type_sec inp_select ma_sel1 b_r">
                                                            <select class="link_type" data-id="android_url"
                                                                    name="link_type_android" id="link_type_android"
                                                                    data-placeholder="Please select one...">
                                                                <option value="WEBLINK"> Redirect to Web URL</option>
                                                                <option value="DEEPLINK"> Deep Link Into App</option>
                                                            </select>
                                                        </div>
                                                        <input type="url" required name="android_url" value=""
                                                               id="android_url"
                                                               class="form-control" placeholder="Link">
                                                    </div>
                                                    <div style="margin-bottom: 15px;">
                                                        <div class="Campaigns_type_sec inp_select  ma_sel2 b_r">
                                                            <select class="link_type" data-id="ios_url"
                                                                    name="link_type_ios" id="link_type_ios"
                                                                    data-placeholder="Please select one...">
                                                                <option value="WEBLINK"> Redirect to Web URL</option>
                                                                <option value="DEEPLINK"> Deep Link Into App</option>
                                                            </select>

                                                        </div>
                                                        <input type="url" required name="ios_url" value="" id="ios_url"
                                                               class="form-control" placeholder="Link">
                                                    </div>
                                                    <div style="margin-bottom: 15px;">
                                                        <div class="Campaigns_type_sec inp_select ma_sel3 b_r">
                                                            <select class="link_type" data-id="window_url"
                                                                    name="link_type_window" id="link_type_window"
                                                                    data-placeholder="Please select one...">
                                                                <option value="WEBLINK"> Redirect to Web URL</option>
                                                                <option value="DEEPLINK"> Deep Link Into App</option>
                                                            </select>

                                                        </div>
                                                        <input type="url" required name="window_url" value=""
                                                               id="window_url"
                                                               class="form-control" placeholder="Link">
                                                    </div>
                                                    <div style="margin-bottom: 15px;">
                                                        <div class="Campaigns_type_sec inp_select ma_sel4 b_r">
                                                            <select class="link_type" data-id="web_url"
                                                                    name="link_type_web" id="link_type_web"
                                                                    data-placeholder="Please select one...">
                                                                <option value="WEBLINK"> Redirect to Web URL</option>
                                                                <option value="DEEPLINK"> Deep Link Into App</option>
                                                            </select>

                                                        </div>

                                                        <input type="url" required name="web_url" value="" id="web_url"
                                                               class="form-control" placeholder="Link">
                                                    </div>

                                                </div>
                                                <div class="device_option_title p_t_b hide">
                                                    <label>Categories (Optional) </label>
                                                </div>

                                                <div class="comp_det_sec comp_setting_sec hide" id="comp_setting_sec"
                                                     style="display: block;">

                                                    <div class="sett_pairs_outer clearfix">
                                                        <label> Key Value Pairs <i class="noticification_icon"></i>
                                                        </label>

                                                        <button type="button" name="button" class="text_plus_icon">
                                                            <i>+</i> Add
                                                            New Pair
                                                        </button>

                                                    </div>

                                                    <div class="sett_sec_para">
                                                        <p>
                                                            You have not yet defined key value pairs for this
                                                            message.<br>
                                                            To add a new pair, click “Add New Pair”.
                                                        </p>
                                                    </div>

                                                    <div class=" sett_input_sec_outer clearfix">

                                                        <div class="sett_input_sec b_r">
                                                            <input type="text" name="" value="">
                                                            <button type="button" name="button"></button>
                                                        </div>
                                                        <div class="sett_input_sec b_r">
                                                            <input type="text" name="" value="">
                                                            <button type="button" name="button"
                                                                    class="arrow_left"></button>
                                                        </div>
                                                        <a href="#" class="sett_input_sel_btn"></a>

                                                    </div>

                                                </div>

                                                <!--  -->
                                            </div>

                                        </div>


                                        <!--  -->
                                    </div>

                                </div>

                            </div>

                        </div>

                        <!--  -->

                    </div>

                    <div class="bcd_stp2 step-tab-panel" id="step2">

                        <div class="segment_location_outer clearfix">

                            <div class="Campaigns_type_sec inp_select">
                                <select name="seg_id" id="seg_id" data-placeholder="Please select one..."
                                        class="chzn-select form-control" tabindex="1">
                                    <option value="">All Segments</option>
                                    @foreach($segments as $segment)
                                        <option value="{{$segment->id}}">{{$segment->name}}</option>
                                    @endforeach
                                </select>
                                <span id="segError" class="inputError" style="color: #f00;margin: 100px;"></span>
                            </div>


                            <div class="Campaigns_type_sec inp_select">

                                <select name="loc_id" id="loc_id" data-placeholder="Please select one..."
                                        class="chzn-select form-control" tabindex="1">
                                    <option value="">All Locations</option>
                                    @foreach($locations as $location)
                                        <option value="{{$location->id}}">{{$location->default_name}}</option>
                                    @endforeach
                                </select>
                                <span id="locError" class="inputError" style="color: #f00;margin: 100px;"></span>
                            </div>

                        </div>

                        <div class="camp_title">
                            <h3> Campaign Duration
                                <strong>Time Zone:
                                    <mark>UTC</mark>
                                </strong>
                            </h3>
                        </div>
                        <div class="camp_Dur_detail">
                            <ul>
                                <li>
                                    <div class="camp_Dur_timing clearfix">
                                        <div class="camp_timing_check_box">
                                            <label for="start_tm">Start Time (Required)</label>
                                        </div>
                                        <div class="camp_timing_inp_sec">
                                            <div class="b_r">
                                                <label>
                                                    <input type="date" name="startDate" id="startDate">
                                                </label>
                                            </div>
                                            <b>at</b>
                                            <div class=" inp_select  b_r shour">
                                                <select name="startHour" id="startHour">
                                                    <option value="00"> 00</option>
                                                    <option value="01"> 01</option>
                                                    <option value="02"> 02</option>
                                                    <option value="03"> 03</option>
                                                    <option value="04"> 04</option>
                                                    <option value="05"> 05</option>
                                                    <option value="06"> 06</option>
                                                    <option value="07"> 07</option>
                                                    <option value="08"> 08</option>
                                                    <option value="09"> 09</option>
                                                    <option value="10"> 10</option>
                                                    <option value="11"> 11</option>
                                                    <option value="12"> 12</option>
                                                    <option value="13"> 13</option>
                                                    <option value="14"> 14</option>
                                                    <option value="15"> 15</option>
                                                    <option value="16"> 16</option>
                                                    <option value="17"> 17</option>
                                                    <option value="18"> 18</option>
                                                    <option value="19"> 19</option>
                                                    <option value="20"> 20</option>
                                                    <option value="21"> 21</option>
                                                    <option value="22"> 22</option>
                                                    <option value="23"> 23</option>
                                                </select>
                                            </div>
                                            <div class=" inp_select  b_r smint">
                                                <select name="startmin" id="startmin">
                                                    <option value="00"> 00</option>
                                                    <option value="01"> 01</option>
                                                    <option value="02"> 02</option>
                                                    <option value="03"> 03</option>
                                                    <option value="04"> 04</option>
                                                    <option value="05"> 05</option>
                                                    <option value="06"> 06</option>
                                                    <option value="07"> 07</option>
                                                    <option value="08"> 08</option>
                                                    <option value="09"> 09</option>
                                                    <option value="10"> 10</option>
                                                    <option value="11"> 11</option>
                                                    <option value="12"> 12</option>
                                                    <option value="13"> 13</option>
                                                    <option value="14"> 14</option>
                                                    <option value="15"> 15</option>
                                                    <option value="16"> 16</option>
                                                    <option value="17"> 17</option>
                                                    <option value="18"> 18</option>
                                                    <option value="19"> 19</option>
                                                    <option value="20"> 20</option>
                                                    <option value="21"> 21</option>
                                                    <option value="22"> 22</option>
                                                    <option value="23"> 23</option>
                                                    <option value="24"> 24</option>
                                                    <option value="25"> 25</option>
                                                    <option value="26"> 26</option>
                                                    <option value="27"> 27</option>
                                                    <option value="28"> 28</option>
                                                    <option value="29"> 29</option>
                                                    <option value="30"> 30</option>
                                                    <option value="31"> 31</option>
                                                    <option value="32"> 32</option>
                                                    <option value="33"> 33</option>
                                                    <option value="34"> 34</option>
                                                    <option value="35"> 35</option>
                                                    <option value="36"> 36</option>
                                                    <option value="37"> 37</option>
                                                    <option value="38"> 38</option>
                                                    <option value="39"> 39</option>
                                                    <option value="40"> 40</option>
                                                    <option value="41"> 41</option>
                                                    <option value="42"> 42</option>
                                                    <option value="43"> 43</option>
                                                    <option value="44"> 44</option>
                                                    <option value="45"> 45</option>
                                                    <option value="46"> 46</option>
                                                    <option value="47"> 47</option>
                                                    <option value="48"> 48</option>
                                                    <option value="49"> 49</option>
                                                    <option value="50"> 50</option>
                                                    <option value="51"> 51</option>
                                                    <option value="52"> 52</option>
                                                    <option value="53"> 53</option>
                                                    <option value="54"> 54</option>
                                                    <option value="55"> 55</option>
                                                    <option value="56"> 56</option>
                                                    <option value="57"> 57</option>
                                                    <option value="58"> 58</option>
                                                    <option value="59"> 59</option>
                                                </select>
                                            </div>
                                            <br/>
                                            <span id="startDateError" class="inputError"
                                                  style="color: #f00;"></span>
                                        </div>
                                    </div>

                                </li>
                                <li>
                                    <div class="camp_Dur_timing clearfix">

                                        <div class="camp_timing_check_box">
                                            <input type="checkbox" id="end_tm" name="end_tm" value="true">
                                            <label for="end_tm">End Time (Optional) </label>
                                        </div>

                                        <div class="camp_timing_inp_sec" id="end_time_dev">

                                            <div class="b_r">
                                                <label>
                                                    <input type="date" name="endDate" id="endDate">
                                                </label>

                                            </div>
                                            <b>at</b>
                                            <div class="inp_select  b_r shour">
                                                <select name="endHour" id="endHour">
                                                    <option value="00"> 00</option>
                                                    <option value="01"> 01</option>
                                                    <option value="02"> 02</option>
                                                    <option value="03"> 03</option>
                                                    <option value="04"> 04</option>
                                                    <option value="05"> 05</option>
                                                    <option value="06"> 06</option>
                                                    <option value="07"> 07</option>
                                                    <option value="08"> 08</option>
                                                    <option value="09"> 09</option>
                                                    <option value="10"> 10</option>
                                                    <option value="11"> 11</option>
                                                    <option value="12"> 12</option>
                                                    <option value="13"> 13</option>
                                                    <option value="14"> 14</option>
                                                    <option value="15"> 15</option>
                                                    <option value="16"> 16</option>
                                                    <option value="17"> 17</option>
                                                    <option value="18"> 18</option>
                                                    <option value="19"> 19</option>
                                                    <option value="20"> 20</option>
                                                    <option value="21"> 21</option>
                                                    <option value="22"> 22</option>
                                                    <option value="23"> 23</option>
                                                </select>
                                            </div>
                                            <div class=" inp_select  b_r smint">
                                                <select name="endmin" id="endmin">
                                                    <option value="00"> 00</option>
                                                    <option value="01"> 01</option>
                                                    <option value="02"> 02</option>
                                                    <option value="03"> 03</option>
                                                    <option value="04"> 04</option>
                                                    <option value="05"> 05</option>
                                                    <option value="06"> 06</option>
                                                    <option value="07"> 07</option>
                                                    <option value="08"> 08</option>
                                                    <option value="09"> 09</option>
                                                    <option value="10"> 10</option>
                                                    <option value="11"> 11</option>
                                                    <option value="12"> 12</option>
                                                    <option value="13"> 13</option>
                                                    <option value="14"> 14</option>
                                                    <option value="15"> 15</option>
                                                    <option value="16"> 16</option>
                                                    <option value="17"> 17</option>
                                                    <option value="18"> 18</option>
                                                    <option value="19"> 19</option>
                                                    <option value="20"> 20</option>
                                                    <option value="21"> 21</option>
                                                    <option value="22"> 22</option>
                                                    <option value="23"> 23</option>
                                                    <option value="24"> 24</option>
                                                    <option value="25"> 25</option>
                                                    <option value="26"> 26</option>
                                                    <option value="27"> 27</option>
                                                    <option value="28"> 28</option>
                                                    <option value="29"> 29</option>
                                                    <option value="30"> 30</option>
                                                    <option value="31"> 31</option>
                                                    <option value="32"> 32</option>
                                                    <option value="33"> 33</option>
                                                    <option value="34"> 34</option>
                                                    <option value="35"> 35</option>
                                                    <option value="36"> 36</option>
                                                    <option value="37"> 37</option>
                                                    <option value="38"> 38</option>
                                                    <option value="39"> 39</option>
                                                    <option value="40"> 40</option>
                                                    <option value="41"> 41</option>
                                                    <option value="42"> 42</option>
                                                    <option value="43"> 43</option>
                                                    <option value="44"> 44</option>
                                                    <option value="45"> 45</option>
                                                    <option value="46"> 46</option>
                                                    <option value="47"> 47</option>
                                                    <option value="48"> 48</option>
                                                    <option value="49"> 49</option>
                                                    <option value="50"> 50</option>
                                                    <option value="51"> 51</option>
                                                    <option value="52"> 52</option>
                                                    <option value="53"> 53</option>
                                                    <option value="54"> 54</option>
                                                    <option value="55"> 55</option>
                                                    <option value="56"> 56</option>
                                                    <option value="57"> 57</option>
                                                    <option value="58"> 58</option>
                                                    <option value="59"> 59</option>
                                                </select>
                                            </div>
                                            <br>
                                            <span id="endDateError" class="inputError"
                                                  style="color: #f00;"></span>
                                        </div>

                                    </div>
                                </li>
                                <li>
                                    <div class="camp_Dur_timing clearfix">

                                        {{--<div class="camp_timing_check_box">
                                            <input type="checkbox" id="time_zone" name="time_zone" value="true">
                                            <label for="time_zone"> Show NFC to users in their local time zone </label>
                                        </div>--}}

                                    </div>
                                </li>
                            </ul>
                        </div>

                    </div>

                    <div class="bcd_stp6 step-tab-panel" id="step6">

                        <div class="bcd_stp6_a">

                            <div class="camp_title">
                                <h3> Messages
                                    <mark class="updated"> Updated</mark>
                                </h3>
                            </div>

                            <div class="camp_title" style="background:none;">
                                <h3> Variant 1 Preview
                                    <mark class="updated"><i style="margin-right:0;"></i></mark>
                                </h3>
                            </div>

                            <div class="prev_comp_sec_outer nf_center_temp clearfix">

                                <div class="nf_temp_img_sec1 pre_comp_body">

                                    <div class="col-xs-12" style="border: 1px dotted #ccc" id="preview_final">

                                    </div>
                                </div>

                            </div>

                            <div class="camp_title">
                                <h3> Delivery
                                    <mark class="updated"><i></i>Updated</mark>
                                </h3>
                            </div>

                            <div class="comp_action_del_outer clearfix">
                                <div class="lft_comp_rules">
                                    <p></p>
                                </div>

                            </div>

                        </div>

                        <div class="bcd_stp6_b">

                            <div class="step_6b_detail_sec">

                                <div class="step_det_list clearfix">
                                    <span> Messages </span>
                                    <span class="right_step_det">
                             <a href="#"> View Details </a>
                             <mark>Not Updated</mark>
                           </span>


                                </div>

                                <div class="step_det_list clearfix">
                                    <span> Delivery </span>
                                    <span class="right_step_det">
                             <a href="#"> View Details </a>
                             <mark>Not Updated</mark>
                           </span>


                                </div>


                            </div>

                        </div>

                    </div>

                    <div class="step-footer save_next_sec ">
                        <button data-direction="prev" class="step-btn" style="display: none;">Back</button>
                        <button data-direction="next" class="step-btn">Next</button>
                        <button data-direction="launch_btn" class="step-btn launch_btn" onclick="launchNewsfeed();">
                            Launch Newsfeed
                        </button>
                        <button data-direction="" class="save_as_draft" onclick="draftNewsfeed();">Save As Draft
                        </button>

                        <div class="reachable_usr_app_message_otr">
                            <div class="reachable_user hide">
                                <p> Reachable Users
                                    <a href="#" data-toggle="tooltip" data-placement="top" title="Welcome To New User">
                                        <i class="noticification_icon"></i>
                                    </a>

                                </p>
                                <span id="segemntCount"></span>
                            </div>
                            <div class="app_message1 hide">
                                <p style="margin: 0 0 0px;">In app message</p>
                                <span>59,530</span>
                            </div>
                        </div>

                    </div>

                    <div class="" style="padding:5px; background:#fff;">
                    </div>

                </div>

            </div>
        </form>
    </div>

    <!-- Button trigger modal -->
    <style>
        #galleryUpload {
            width: 82.7%;
            line-height: 30px;
            color: #b7b7b7;
            padding: 6px 10px;
            display: inline;
            font-size: 16px;
            font-weight: 500;
            letter-spacing: 1px;
            border: 1px solid #b7b7b7;
            border-radius: 4px;
        }

        .modal-body {
            padding: 0px !important;
        }

        /*gallery datatable*/

        #gallery_filter {
            background: #2a8689;
            border-radius: 7px;
            color: #ffffff;
            font-size: 13px;
            font-weight: 400;
            line-height: 24px;
            padding: 10px 10px 7px 8px;
            margin: 1px 0px;
            letter-spacing: 0.5px;
        }

        #gallery_filter input {
            color: #000 !important;
            padding: 1px 5px;
            border-radius: 5px;
        }

        #gallery_length label {
            position: relative;
            bottom: -10px;
            left: 1px;
        }

        #gallery_length label select {
            border: 1px solid #b7b7b7;
            padding: 0px 10px 0px 7px;
            border-radius: 6px;
        }

        .table-bordered > tbody > tr > td {
            vertical-align: middle;
        }

        .sorting_disabled, .sorting {
            text-align: center;
        }
    </style>

    @include('cropper')
    <div class="modal fade gallery_popup" tabindex="-1" role="dialog" id="exampleModalCenter">
        <div class="modal-dialog" role="document" style="width: 88%">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Gallery</h5>
                </div>
                <div class="modal-body">
                    <div class="btn_header" style="margin-bottom: 10px; height: 57px">
                        <input type="hidden" id="tokenForUploadImg" value="{{ csrf_token() }}"/>
                        <input id="galleryUpload" type="file">
                        <button id="galleryUploadBtn" class="btn"
                                style="padding: 11px 12px; background: #2a8689; color: white">Upload
                        </button>
                        <span id="uploadError" style="color: #F99;"></span>
                    </div>
                    <div class="list_table_body scrollbar_content mCustomScrollbar _mCS_1">
                        <div class="table-responsive">
                            <table style="width: 100%;" class="table table-bordered table-striped" id="gallery">
                                <thead>
                                <th style="width:30%;">Image</th>
                                <th style="width:20%;">Name</th>
                                <th style="width:20%;">Dimensions</th>
                                <th style="width:20%;">Size</th>
                                <th style="width:30%;">Date/Time Added</th>
                                <th style="width:10%;"></th>
                                </thead>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">

                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
@stop

@section('content')

@stop

@section('jsSection')
    <script src="{{asset('/assets/js/newsFeed/newsFeed_Crud.js')}}"></script>
    <script src="{{asset('/assets/js/newsFeed/newsFeed.js')}}"></script>
    <script>
        var nameIsExist  = true;
        $(document).keypress(
            function (event) {
                if (event.which == '13') {
                    event.preventDefault();
                }
            });

        /*gallery + image upload code start*/
        var galleryImg = '';

        $("#open_model").click(function () {
            $("#exampleModalCenter").modal('show');
            $("#galleryUpload").val("");
            $("#uploadError").text("");
        });

        /*gallery + image upload code ends*/

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


            $("[name='m_name']").on("change",function () {


                $.ajax({
                    async: true,
                    type: "POST",
                    url: baseUrl+"/backend/newsfeed/check/duplication?name="+$(this).val()+"&id="+$("[name='newsfeedID']").val(),
                    success: function (data) {

                        if(data.status === 411){
                            $("#m_name").focus();
                            $('#nameError').html(data.message);
                            nameIsExist = true;
                            return;

                        }else{
                            $("#nameError").attr("data-duplicate",0);
                            nameIsExist = false;
                        }
                    }
                });
            })
            populateStartDateUtc();
        });


        $('#demo').steps({
            onChange: function (currentIndex, newIndex, stepDirection) {
                //console.log('onChange', currentIndex, newIndex, stepDirection);
                // tab1
                $('.inputError').html('');
                if (!$("#form_id1").valid()) {

                    return;
                }
                if (currentIndex === 0) {
                    if (stepDirection === 'forward') {
                        if (newsfeed.m_name.value == "") {
                            $('#nameError').html('Please Enter the Name');
                            $("#m_name").focus();
                            return;
                        }


                        if(nameIsExist){
                            $('#nameError').html('Name already exist');
                            $("#m_name").focus();
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
                        newsfeedStepSave();
                        $('#newsfeedStep').val('DELIVERY');
                    }
                    if (stepDirection === 'backward') {
                        $('#newsfeedStep').val('COMPOSE');
                    }
                }

                // tab2
                if (currentIndex === 1) {
                    if (stepDirection === 'forward') {

                        if (newsfeed.startDate.value == "") {
                            $('#startDateError').html('Please enter the Start Date');
                            $("#startDate").focus();
                            return;
                        }


                        if (document.getElementById("end_tm").checked == true) {

                            if (newsfeed.endDate.value == "") {
                                $('#endDateError').html('Please enter the End Date');
                                $("#endDate").focus();
                                return;
                            }
                            var startDateTime = $("#startDate").val() + ' ' + $("#startHour").val() + ':' + $("#startmin").val() + ':00';
                            var endDateTime = $("#endDate").val() + ' ' + $("#endHour").val() + ':' + $("#endmin").val() + ':00';
                            // alert(startDateTime);
                            // alert(endDateTime);
                            if (endDateTime <= startDateTime) {
                                $('#endDateError').html('Please enter the end Date greater then start date');
                                $("#endDate").focus();
                                return;
                            }
                        }
                        newsfeedStepSave();
                        $('#newsfeedStep').val('CONFIRM')
                    }

                    if (stepDirection === 'backward') {
                        $('#newsfeedStep').val('COMPOSE')
                    }

                }

                // tab3
                if (currentIndex === 2) {
                    if (stepDirection === 'forward') {

                        newsfeedStepSave();
                        $('#newsfeedStep').val('CONFIRM')
                    }
                    if (stepDirection === 'backward') {
                        $('#newsfeedStep').val('DELIVERY')
                    }
                }
                return true;

            },
            onFinish: function () {
                alert('Wizard Completed');
            }
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
            $('.inputError').html('');
            if (newsfeed.newsfeedStep.value == "COMPOSE") {
                if (newsfeed.m_name.value == "") {
                    $('#nameError').html('Please Enter the Name');
                    $("#m_name").focus();
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
            $('#is_active').val("active");
            newsfeedSave();


        }


        function draftNewsfeed() {
            $('.inputError').html('');

            if (newsfeed.m_name.value == "") {
                $('#nameError').html('Please Enter the Name');
                $("#m_name").focus();
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

                    //console.log(data.result['id']);
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
                        window.location = "{{ route('newsfeedList') }}";
                        return;
                    }

                    if (data.error == true) {
                        $('#MSG').html('<div class="alert alert-warning">\n\
			 					<strong>Warning! </strong> something wrong\n\
							   </div>');
                        $("html, body").animate({scrollTop: 0}, "slow");
                        return false;
                    }

                    //console.log(data.result['id']);
                },
                error: function (errorMsg) {


                }
            })
        }



        function populateStartDateUtc() {
            var isoDate = new Date().toISOString();
            var mints = isoDate.split(":")[1];
            var hours = isoDate.split(":")[0].split("T")[1];
            $("#startDate").val(isoDate.split(":")[0].split("T")[0]);
            $("#startHour").val(hours);
            $("#startmin").val(mints);
        }
    </script>


@stop