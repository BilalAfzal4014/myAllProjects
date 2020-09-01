<div class="bcd_stp6 step-tab-panel" id="step6">

    <div class="bcd_stp6_a">

        <div class="camp_title">
            <h3> Compose
                <mark style="cursor: pointer" onclick="goBack(4)" class="updated"><i></i>Updated</mark>
            </h3>
        </div>


        <div class="prev_comp_sec_outer clearfix">
            <div class="left_pre_comp_sec b_r seperate">
                <div class="pre_comp_body">
                    <div class="add_tag_sec_outer clearfix" style="width: 98%; margin-left: 7px !important;">
                        <div id="selectLangInAppPushPreview" class="left_tag_sec">
                            <span data-lang="en" class="active"><img
                                        src="{{asset('assets/images/flag_icon_England.png')}}"
                                        alt="#"></span>
                            <span data-lang="ar"> <img src="{{asset('assets/images/flag_icon_UAE.png')}}"
                                                       alt="#"></span>
                        </div>
                        <button style="top: 14px !important;" type="button" name="button" class="text_plus_icon">
                            Languages
                        </button>
                    </div>
                    <div id="step6Preview" class="pre_upload_sec seperate">
                        <i class="close_btn"></i>

                        <div class="pre_upload_file_sec">
                            <div class="t_cell_v_midd">

                                <input type="file" name="file-1[]" id="file-2" class="inputfile inputfile-2"
                                       data-multiple-caption="{count} files selected" multiple="">
                                <label for="file-2"> <img src="{{asset('/assets/images/upload_img.png')}}"
                                                          alt="#">
                                </label>
                            </div>


                        </div>

                        <div class="pre_upload_btns clearfix">
                            <button type="button" name="button"
                                    style="background:#0071bc; border:1px solid #0071bc;">YES!
                            </button>
                            <button type="button" name="button"
                                    style="background:#cd2026; border:1px solid #cd2026;">NO
                            </button>
                        </div>

                    </div>

                </div>


            </div>

        </div>
        <!--  -->

        <div class="e2-temp_sec_outer">
            <div class="emil_send_sec clearfix">

                <div class="add_tag_sec_outer   clearfix">
                    <div id="selectLangEmailPreview" class="left_tag_sec">
                        <span data-lang="en" class="active"><img
                                    src="{{asset('assets/images/flag_icon_England.png')}}"
                                    alt="#"></span>
                        <span data-lang="ar"> <img src="{{asset('assets/images/flag_icon_UAE.png')}}"
                                                   alt="#"></span>
                    </div>
                    <button type="button" name="button" class="text_plus_icon"> Languages
                    </button>
                </div>

                <div class="emial_list b_r">
                    <strong>From <i>(email):</i> </strong>
                    <span id="fromPreEmail"> </span>
                </div>
                <div class="emial_list b_r">
                    <strong>From <i>(name):</i> </strong>
                    <span id="fromPreName"> </span>
                </div>
                <div class="emial_list b_r">
                    <strong>Subject:</strong>
                    <span id="fromPreSubject"> </span>
                </div>
            </div>

            <div class="camp_title step6_Variant_Prev1">
                <h3> Variant 1
                    <mark style="cursor: pointer" onclick="goBack(4)" class="updated"><i></i></mark>
                </h3>
            </div>

            <div class="editor_temp">
                <textarea disabled name="content" id="ckEditorPreview"></textarea>

            </div>

        </div>


        <div class="campaign_widget">
            <div class="camp_title">
                <h3> Delivery
                    <mark style="cursor: pointer" onclick="goBack(3)" class="updated"><i></i>Updated</mark>
                </h3>
            </div>
            <div class="comp_action_del_outer clearfix">
                <div id="deliveryControlComposeScreen" class="lft_comp_rules">
                    <h3>Campaign Rules</h3>
                    {{--<p> Send to users who Perform Custom Event (Receive Ping) </p>--}}
                    <p>Users can receive messages from this Campaign multiple times when at least
                        3 days have elapsed since they were previously targeted
                    </p>
                </div>

                <div id="actionTriggerComposeScreen" class="rt_action_del">
                    <h3>Action-Based Delivery</h3>
                    <p>Send Campaign 2 hours after trigger criteria are met, beginning today at
                        5:45 PM.
                    </p>
                </div>
            </div>
        </div>

        <div class="campaign_widget">
            <div class="camp_title">
                <h3> Target Population
                    <mark style="cursor: pointer" onclick="goBack(2)" class="updated"><i></i>Updated</mark>
                    <mark style="cursor: pointer" id="campaignSegmentExport" class="updated export-btn"><i></i>Export
                    </mark>
                </h3>
            </div>

            <div class="aud_summary">
                <p id="reachAbleOnCompose">
                </p>
            </div>
        </div>


        <div class="camp_title">
            <h3> Conversion Events
                <mark style="cursor: pointer" onclick="goBack(1)" class="updated"><i></i>Updated</mark>
            </h3>
        </div>
    </div>
</div>