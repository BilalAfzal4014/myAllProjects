@extends('layouts.master')

@section('searchBar')
    <div class="tp_BreadCrumb_list_sec clearfix">
        <label class="sec_tp_title"> News Feed </label>
        <div class="track_userdeta_right clearfix">
            <div class="tp_BreadCrumb_srch_inp">
                <input type="search" name="" value="" placeholder="Search...">
            </div>

            <div class="uder_deta_dropdown">
                <div class=" inp_select">
                    <select id="campaigns_type2">
                        <option value="app_message"> Create Card</option>
                        <option value="email_html-2"> Create Card-2</option>
                    </select>
                </div>


            </div>

        </div>

    </div>
@stop

@section('create')
    <div class="db_content_holder step-app">

        <div class="tp_BreadCrumb_list_sec clearfix">

            <label class="sec_tp_title"> News Feed &gt; {Card Name}</label>

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

                <div class="Campaigns_input b_r">
                    <input type="text" name="" value="" placeholder="Enter Card Name ">
                </div>

                <div class="add_tag_sec_outer b_r clearfix">
                    <div class=" left_tag_sec">
                        <span>Advertising <i></i> </span>
                        <span>Dubai <i></i> </span>
                        <span>Conversation <i></i> </span>
                    </div>
                    <button type="button" name="button" class=" text_plus_icon" style="width:15px; right:0;"><i>+</i>
                    </button>

                </div>

                <div class="prev_comp_sec_outer nf_prev_compos_sec clearfix">

                    <div class="left_pre_comp_sec b_r">
                        <div class="pre_comp_title">
                            <label> Classic Card Preview </label>
                        </div>

                        <div class="pre_comp_body">

                            <div class="preview_selection clearfix">

                                <div class="inp_select b_r">
                                    <select>
                                        <option> Card Type</option>
                                        <option> Card Type -1</option>
                                        <option> Card Type -2</option>
                                    </select>
                                </div>

                                <div class="nf_temp_img_sec ">
                                    <span> <img src="{{asset('/assets/images/nf_sale_img.png')}}" alt=""> </span>
                                </div>

                                <div class="nf_search_user_id">
                                    <label>Search for a user to preview personalization on this card. </label>

                                    <div class="sett_input_sec nf_search_user_inp b_r">
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

                                    <div class="add_tag_sec_outer   clearfix">
                                        <div class=" left_tag_sec">
                                            <span> <img src="{{asset('/assets/images/flag_icon_1.png')}}"
                                                        alt="#"> <i></i> </span>
                                            <span> <img src="{{asset('/assets/images/flag_icon_2.png')}}"
                                                        alt="#"> <i></i> </span>
                                            <span> <img src="{{asset('/assets/images/flag_icon_3.png')}}"
                                                        alt="#"> <i></i> </span>

                                        </div>
                                        <button type="button" name="button" class="text_plus_icon"><i>+</i> Add
                                            Languages
                                        </button>

                                    </div>

                                    <div class="comp_input_form">
                                        <div class="comp_input_sec p_t_b ">
                                            <label>Title</label>
                                            <div class="Campaigns_input b_r nf_plus_icon_add">
                                                <input type="text" name="" value="" placeholder="Enter Card Name ">
                                                <button type="button" name="button" class=" text_plus_icon"
                                                        style="width:15px; right:0;"><i>+</i></button>
                                            </div>

                                        </div>
                                        <div class="comp_input_sec p_t_b">
                                            <label>Message</label>
                                            <div class="nf_plus_icon_add">
                                                <textarea name="name" class="b_r " rows="5" cols="50"> </textarea>
                                                <button type="button" name="button" class=" text_plus_icon"
                                                        style="width:15px; right:0;"><i>+</i></button>
                                            </div>
                                        </div>
                                        <div class="device_option_title p_t_b">
                                            <label> On Click Behavior </label>
                                        </div>
                                        <div class="comp_input_sec  ">

                                            <div class="Campaigns_type_sec inp_select ma_sel1 b_r">
                                                <select>
                                                    <option> Android</option>
                                                    <option> Android 1</option>
                                                    <option> Android 2</option>
                                                </select>
                                            </div>
                                            <div class="Campaigns_type_sec inp_select ma_sel2 b_r">
                                                <select>
                                                    <option> iOS</option>
                                                    <option> iOS 1</option>
                                                    <option> iOS 2</option>
                                                </select>
                                            </div>
                                            <div class="Campaigns_type_sec inp_select ma_sel3 b_r">
                                                <select>
                                                    <option> iOS</option>
                                                    <option> iOS 1</option>
                                                    <option> iOS 2</option>
                                                </select>
                                            </div>
                                            <div class="Campaigns_type_sec inp_select ma_sel4 b_r">
                                                <select>
                                                    <option> iOS</option>
                                                    <option> iOS 1</option>
                                                    <option> iOS 2</option>
                                                </select>
                                            </div>

                                        </div>
                                        <div class="device_option_title p_t_b">
                                            <label>Categories (Optional) </label>
                                        </div>

                                        <div class="comp_det_sec comp_setting_sec" id="comp_setting_sec"
                                             style="display: block;">

                                            <div class="sett_pairs_outer clearfix">
                                                <label> Key Value Pairs <i class="noticification_icon"></i> </label>

                                                <button type="button" name="button" class="text_plus_icon"><i>+</i> Add
                                                    New Pair
                                                </button>

                                            </div>

                                            <div class="sett_sec_para">
                                                <p>
                                                    You have not yet defined key value pairs for this message.<br>
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
                                                    <button type="button" name="button" class="arrow_left"></button>
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
                    <div class="Campaigns_type_sec inp_select  b_r">
                        <select id="">
                            <option value="app_message"> Select Segment</option>
                            <option value="email_html-2"> Select Segment 1</option>
                        </select>
                    </div>
                    <div class="Campaigns_type_sec inp_select  b_r">
                        <select id="">
                            <option value="app_message"> Target Location</option>
                            <option value="email_html-2"> Target Location 1</option>
                        </select>
                    </div>
                </div>

                <div class="camp_title">
                    <h3> Campaign Duration
                        <strong>Time Zone:
                            <mark>Abu Dhabi</mark>
                        </strong>
                    </h3>
                </div>

                <div class="camp_Dur_detail">
                    <ul>
                        <li>
                            <div class="camp_Dur_timing clearfix">

                                <div class="camp_timing_check_box">
                                    <label for="start_tm"> Start Time (Required) </label>
                                </div>

                                <div class="camp_timing_inp_sec">

                                    <div class=" inp_dat_picker b_r">
                                        <label>
                                            <input type="date" name="bday">
                                        </label>
                                    </div>
                                    <b>at</b>
                                    <div class=" inp_select  b_r">
                                        <select>
                                            <option> 1</option>
                                            <option> 2</option>
                                            <option> 3</option>
                                        </select>
                                    </div>
                                    <div class=" inp_select  b_r">
                                        <select>
                                            <option> 00</option>
                                            <option> 30</option>
                                            <option> 45</option>
                                        </select>
                                    </div>
                                    <div class=" inp_select  b_r">
                                        <select>
                                            <option> am</option>
                                            <option> pm</option>

                                        </select>
                                    </div>

                                </div>
                            </div>
                        </li>
                        <li>
                            <div class="camp_Dur_timing clearfix">

                                <div class="camp_timing_check_box">
                                    <input type="checkbox" id="end_tm" name="contact" value="email">
                                    <label for="end_tm"> End Time (Optional) </label>
                                </div>

                                <div class="camp_timing_inp_sec">

                                    <div class=" inp_dat_picker b_r">
                                        <label>
                                            <input type="date" name="bday">
                                        </label>
                                    </div>
                                    <b>at</b>
                                    <div class=" inp_select  b_r">
                                        <select>
                                            <option> 1</option>
                                            <option> 2</option>
                                            <option> 3</option>
                                        </select>
                                    </div>
                                    <div class=" inp_select  b_r">
                                        <select>
                                            <option> 00</option>
                                            <option> 30</option>
                                            <option> 45</option>
                                        </select>
                                    </div>
                                    <div class=" inp_select  b_r">
                                        <select>
                                            <option> am</option>
                                            <option> pm</option>

                                        </select>
                                    </div>

                                </div>
                            </div>
                        </li>
                        <li>
                            <div class="camp_Dur_timing clearfix">

                                <div class="camp_timing_check_box">
                                    <input type="checkbox" id="tm_zom" name="contact" value="email">
                                    <label for="tm_zom"> Send Campaign to users in their local time zone </label>
                                </div>

                            </div>
                        </li>
                        <li>
                            <p><strong>Summary:</strong> Send Campaign immediately after trigger criteria are met,
                                beginning Today at 5:45PM. </p>
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

                        <div class="nf_temp_img_sec ">
                            <span> <img src="{{asset('/assets/images/nf_sale_img.png')}}" alt=""> </span>
                        </div>

                    </div>

                    <div class="camp_title">
                        <h3> Delivery
                            <mark class="updated"><i></i>Updated</mark>
                        </h3>
                    </div>

                    <div class="comp_action_del_outer clearfix">
                        <div class="lft_comp_rules">
                            <p>Send Campaign 2 hours after trigger criteria are met, beginning today at
                                5:45 PM.
                            </p>
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
                <button data-direction="launch_btn" class="step-btn launch_btn">Launch Campaign</button>
                <button data-direction="" class="save_as_draft">Save As Draft</button>

                <div class="reachable_usr_app_message_otr">
                    <div class="reachable_user">
                        <p> Reachable Users <i class="noticification_icon"></i></p>
                    </div>
                    <div class="app_message">
                        <p>In app message</p>
                        <span>59,530</span>
                    </div>
                </div>

            </div>

            <div class="" style="padding:5px; background:#fff;">
            </div>

        </div>


    </div>
@stop

@section('content')
    <div class="db_list_right_sec">
        <div class="list_table_header">
            <table cellspacing="0" cellpadding="0">
                <tbody>
                <tr>
                    <th style="width:34%;">Segment Name</th>
                    <th style="width:15%;">Type</th>
                    <th style="width:10%;">Impressions (Unique)</th>
                    <th style="width:10%;">Start Date</th>
                    <th style="width:10%;">End Date</th>
                    <th style="width:15%;">Created By</th>
                    <th style="width:6%;"></th>
                </tr>
                </tbody>
            </table>
        </div>
        <div class="list_table_body scrollbar_content mCustomScrollbar _mCS_1 _mCS_2">
            <div id="mCSB_2" class="mCustomScrollBox mCS-light mCSB_vertical mCSB_inside" tabindex="0"
                 style="max-height: none;">
                <div id="mCSB_2_container" class="mCSB_container"
                     style="position:relative; top:0; left:0; padding-right: 0px !important;" dir="ltr">
                    <table cellspacing="0" cellpadding="0" class="listingTable" style="border-collapse: collapse;">

                        <tbody>
                        <tr>
                            <td style="width:34%;"><a href="#"> <img src="{{asset('/assets/images/nf_list_img1.png')}}"
                                                                     alt="#"
                                                                     class="mCS_img_loaded"> NFC_Members_Ad Sale </a>
                            </td>
                            <td style="width:15%;"> Captioned image</td>
                            <td style="width:10%;">0 (0)</td>
                            <td class="list_time_date" style="width:10%;"> 09-05-2018 <b>12:00 PM </b></td>
                            <td class="list_time_date" style="width:10%;"> 11-05-2018 <b>11:55 PM </b></td>
                            <td style="width:15%;"> Jawad Ashraf</td>
                            <td style="width:6%;">
                                <div class="lst_tbl_drop_outer">
                                    <span class=""> <img src="{{asset('/assets/images/sett_icon.png')}}" alt="#"
                                                         class="mCS_img_loaded"> </span>
                                    <ul>
                                        <li><a href="#"> <img src="{{asset('/assets/images/edit_icon.png')}}" alt=""
                                                              class="mCS_img_loaded">
                                                Edit </a></li>
                                        <li><a href="#"> <img src="{{asset('/assets/images/view_icon.png')}}" alt=""
                                                              class="mCS_img_loaded">
                                                View </a></li>
                                    </ul>
                                </div>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>


    </div>
@stop

@section('jsSection')
    <script src="{{asset('/assets/js/newsFeed/newsFeed.js')}}"></script>
@stop