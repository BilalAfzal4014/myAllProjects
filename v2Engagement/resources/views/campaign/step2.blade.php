<style>
    .multiselect-native-select button.multiselect.dropdown-toggle.btn.btn-default {
        width: 100%
    }

    .checkbox input {
        width: 3%;
    }

    input:checked + .slider {
        background-color: #c0c0c0;
    }

    .switch {
        margin-left: 7px;
        margin-right: 7px;
    }

    input[type="Range"] {
        -webkit-appearance: slider-horizontal;
    }

</style>
<div class="bcd_stp2 step-tab-panel" id="step2">

    <div id="platformDiv" class="plateform_sec_outer ">

        <label> Choose Platform </label>

        <div class="plateform_sec pf_sel1 inp_select b_r">
            <select id="plateForm">
            </select>
        </div>
        <div class="plateform_sec pf_sel2 inp_select b_r">
            <select id="messageType">
            </select>
        </div>
        <div class="plateform_sec pf_sel3 inp_select b_r">
            <select id="layout" disabled="true">
            </select>
        </div>
    </div>

    <div id="step2MainContainer" class="prev_comp_sec_outer clearfix custom_style_tab">
        <div id="step2Container" class="left_pre_comp_sec b_r">
            <div class="pre_comp_title">
                <label> Preview </label>
            </div>

            <div class="pre_comp_body">

                <div id="sdkVersionDiv" class="plateform_sec_outer preview_selection clearfix">

                    <div style="background: rgba(204, 204, 204, 0.467);"
                         class="plateform_sec preview_sel1 inp_select b_r">
                        <select disabled>
                            <option> SDK Version</option>
                            <option> SDK Version -1</option>
                            <option> SDK Version -2</option>
                        </select>
                    </div>

                    <div class="plateform_sec pf_sel4 inp_select b_r">
                        <select id="devicePosition">
                            <option> Device Type</option>
                            <option> Device Type -1</option>
                            <option> Device Type -2</option>
                        </select>
                    </div>

                </div>
                <div id="loadInAppTemplate" class="pre_upload_sec">
                    <div class="tp_header clearfix"
                         style="display: block;width:100%;background:#000;position:absolute;top:0;left:0;">
                        <ul class=" clearfix" style="padding:6px 10px; margin:0;">
                            <li style=" float:left; list-style:none;"><span
                                        style="/*background:#fff;*/ padding:5px; display:block;"><i class="fa fa-wifi"
                                                                                                    style="color: white"></i></span>
                            </li>
                            <li style=" float:right; list-style:none;"><span
                                        style="/*background:#fff;*/ padding:5px; display:block; "><i
                                            class="fa fa-battery-full" style="color: white"></i></span>
                            </li>
                        </ul>
                    </div>

                </div>

                <p>Actual rendering may not be identical to this step2MainContainer depending on the
                    user’s environment. </p>

            </div>


        </div>

        <div id="step2SecondContainer" class="right_pre_comp_sec b_r">
            <div class="custom_style_div_holder">
                <div class="custom_div_area">
                    <div class="pre_comp_title">
                        <label> Compose Push/In-App Messages </label>
                        <div class="pre_comp_title_icons">
                            <ul>
                                <li><a class="pre_comp_write active" href="#comp_write_sec"> <i></i> </a></li>
                                <li><a class="pre_comp_brush" href="#comp_color_sec"> <i></i> </a></li>
                                <li><a class="pre_comp_view" href="#comp_view_sec"> <i></i> </a></li>
                            </ul>
                        </div>
                    </div>
                    <div class="pre_comp_body">
                <div class="comp_detail_outer">
                    <div class="comp_det_sec comp_write_sec" id="comp_write_sec" style="display:block;">
                        <div class="add_tag_sec_outer   clearfix">

                            <div class="new_btn_div">
                                <a style="right: 108px !important;" id="resetToGlobalInAppPush" href="#"
                                   data-toggle="tooltip" data-placement="top"
                                   title="Reset to original!"><span
                                            class="glyphicon glyphicon-retweet"></span></a>
                                <button type="button" name="button" class="text_plus_icon"> Languages
                                </button>
                            </div>
                            <div id="selectLangInAppPush" class="left_tag_sec">
                                        <span data-lang="en" class="active"><img
                                                    src="{{asset('assets/images/flag_icon_England.png')}}"
                                                    alt="#"></span>
                                <span data-lang="ar"> <img src="{{asset('assets/images/flag_icon_UAE.png')}}"
                                                           alt="#"></span>
                            </div>

                        </div>
                        <div class="comp_input_form">
                            <div id="titleDiv" class="comp_input_sec p_t_b">
                                <label>Title <a class="align_right_btn" style="cursor: pointer" class="pre_comp_setting"
                                                onclick="openAttributeModal('INPUT')"><i class="fa fa-cog"
                                                                                         aria-hidden="true"></i></a></label>
                                <input id="inappType" type="text" class="b_r " name="" value="">
                                <span id="inappTypeError" style="color: #F99"></span>
                            </div>
                            <div class="comp_input_sec p_t_b">
                                <label>Message <a class="align_right_btn" style="cursor: pointer"
                                                  class="pre_comp_setting" onclick="openAttributeModal('TEXTAREA')"><i
                                                class="fa fa-cog" aria-hidden="true"></i></a></label>
                                <textarea id="inappMessage" name="name" class="b_r " rows="5"
                                          cols="50"></textarea>
                                <span id="inappMessageError" style="color: #F99"></span>
                            </div>

                            <div id="actionButtonTextDiv" class="comp_input_sec p_t_b clearfix">

                                <div class="inp_btn_sec_yes_no">
                                    <label>Action 1</label>
                                    <input maxlength="15" id="firstBtn" type="text" name="button" class="b_r button"
                                           value="Yes!">

                                    </input>
                                </div>

                                <div class="inp_btn_sec_yes_no">
                                    <label>Action 2</label>
                                    <input maxlength="15" id="secondBtn" type="text" name="button" class="b_r button"
                                           value="">
                                    </input>
                                </div>


                            </div>

                            <div id="section1Div" class="device_option_title p_t_b">
                                <label> Device Options </label>
                                <div class="dev_opt_icons">
                                    <ul class="clearfix">
                                        <li><a class="dev_opt_and_icon" href="#"> </a></li>
                                        <li><a class="dev_opt_apple_icon" href="#"> </a></li>
                                    </ul>
                                </div>
                            </div>

                            <h3 id="section1Message">Message Action</h3>
                            <div id="section2Div" class="comp_input_sec p_t_b">
                                <label>Action 1</label>
                                <div style="position: relative"
                                     class="Campaigns_type_sec inp_select {{--ma_sel1--}} b_r">
                                    <i class="campaign_action_icon fa fa-mobile" aria-hidden="true"></i>
                                    <select id="action1" style="padding-left: 30px">
                                    </select>
                                </div>
                                <input id="actionInput1" type="text" name="button" class="b_r button"
                                       value="">
                                </input>
                                <span id="actionError1" style="color: #F99"></span>

                            </div>

                            <div id="section3Div" class="comp_input_sec p_t_b">
                                <label>Action 2</label>
                                <div style="position: relative"
                                     class="Campaigns_type_sec inp_select {{--ma_sel1--}} b_r">
                                    <i class="campaign_action_icon fa fa-mobile" aria-hidden="true"></i>
                                    <select id="action2" style="padding-left: 30px">
                                    </select>
                                </div>
                                <input id="actionInput2" type="text" name="button" class="b_r button"
                                       value="">
                                </input>
                                <span id="actionError2" style="color: #F99"></span>

                            </div>

                        </div>

                    </div>

                    <div class="comp_det_sec comp_color_sec" id="comp_color_sec">

                        <div id="headerDiv" class="clr_cod_opc_lst_outer">
                            <h3>Header</h3>
                            <ul>
                                <li>
                                    <div class="clr_opacity_list clearfix">
                                        <label> Heading text color </label>

                                        <div class="rt_clr_opacity">
                                            <span>
                                             <input class="color_code" type="color" id="headerTextColor"
                                                    name="color"
                                                    value="#000"/>
                                            </span>
                                        </div>

                                    </div>
                                </li>
                                <li>
                                    <div class="clr_opacity_list clearfix">
                                        <label> Content text color</label>

                                        <div class="rt_clr_opacity">
                                            <span>
                                             <input class="color_code" type="color" id="contentTextColor"
                                                    name="color"
                                                    value="#666"/>
                                            </span>
                                        </div>

                                    </div>
                                </li>
                            </ul>
                        </div>


                        <div id="buttonsDesign" class="clr_cod_opc_lst_outer">
                            <h3>Buttons</h3>
                            <ul>
                                <li>
                                    <div class="clr_opacity_list clearfix">
                                        <label> Button 1 Background </label>

                                        <div class="rt_clr_opacity">

                                                 <span>
                                                     <input class="color_code" type="color" id="btn1BackgroundColor"
                                                            name="color"
                                                            value="#0071bd"/>
                                                 </span>

                                            <span>
                                                   <mark>Opacity</mark>
                                                <input type="range" id="btn1BackgroundOpacity" name="cowbell"
                                                       class="opacity"
                                                       min="0" max="1" value="1" step="0.1"/>
                                                <span style="width: 76px;">
                                                    Value: 1
                                                </span>
                                            </span>


                                        </div>

                                    </div>
                                </li>
                                <li>
                                    <div class="clr_opacity_list clearfix">
                                        <label> Button 1 Text </label>

                                        <div class="rt_clr_opacity">
                                            <span>
                                                <input class="color_code" type="color" id="btn1textColor"
                                                       name="color"
                                                       value="#ffffff"
                                                       style="margin-right: 242px"/>
                                            </span>
                                        </div>

                                    </div>
                                </li>
                                <li>
                                    <div class="clr_opacity_list clearfix">
                                        <label> Button 2 Background </label>

                                        <div class="rt_clr_opacity">

                                                 <span>
                                                     <input class="color_code" type="color" id="btn2BackgroundColor"
                                                            name="color"
                                                            value="#cd2026"/>
                                                 </span>

                                            <span>
                                                   <mark>Opacity</mark>
                                                <input type="range" id="btn2BackgroundOpacity" name="cowbell"
                                                       class="opacity"
                                                       min="0" max="1" value="1" step="0.1"/>
                                                <span style="width: 76px;">
                                                    Value: 1
                                                </span>
                                            </span>


                                        </div>

                                    </div>
                                </li>
                                <li>
                                    <div class="clr_opacity_list clearfix">
                                        <label> Button 2 Text </label>

                                        <div class="rt_clr_opacity">
                                            <span>
                                                <input class="color_code" type="color" id="btn2textColor"
                                                       name="color"
                                                       value="#ffffff"
                                                       style="margin-right: 242px"/>
                                            </span>
                                        </div>

                                    </div>
                                </li>
                            </ul>
                        </div>

                        <div class="clr_opc_hdr_outer back_color">

                            <div class="clr_opacity_list clearfix">
                                <label> Background Color </label>

                                <div class="rt_clr_opacity">

                                         <span>
                                             <input class="color_code" type="color" id="backgroundColor" name="color"
                                                    {{--value="#f0f0f0"--}} value="#FFFFFF"/>
                                         </span>

                                    <span>
                                           <mark>Opacity</mark>
                                        <input type="range" id="backgroundOpacity" name="cowbell" class="opacity"
                                               min="0" max="1" value="1" step="0.1"/>
                                        <span style="width: 76px;">
                                            Value: 1
                                        </span>

                                    </span>


                                </div>

                            </div>

                        </div>

                        <div class="clr_opc_hdr_outer fram_color">

                            <div class="clr_opacity_list clearfix">
                                <label> Frame Color </label>

                                <div class="rt_clr_opacity">

                                         <span>
                                             <input class="color_code" type="color" id="frameColor" name="color"
                                                    value="#f0f0f0"/>
                                         </span>
                                </div>

                            </div>

                        </div>

                    </div>

                    <div class="comp_det_sec comp_setting_sec" id="comp_setting_sec">

                        <div class="sett_pairs_outer clearfix">
                            <label> Key Value Pairs <i class="noticification_icon"></i> </label>

                            <button type="button" name="button" class="text_plus_icon"><i>+</i>
                                Add New Pair
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

                    <div class="comp_det_sec comp_view_sec" id="comp_view_sec">

                        <div class="view_top_sec">
                            <div class="alert">
                                <p>
                                    Testing In-App Message requires push notifications to be
                                    enabled on the test devices. Ensure push is enabled
                                    before sending.
                                </p>
                            </div>

                            <h3> Test Recipients </h3>
                            <p>
                                Select at least one Content Test Group or individual user to
                                receive this test message. Message will be customized with
                                recipients attributes by default.
                            </p>

                            <div class="comp_input_sec">
                                <label> Add Users </label>
                                <select id="userSelect">
                                </select>
                                <span id="userError" style="color: #F99;"></span>
                            </div>

                            <strong id="switchBtn">
                                Live
                                <label class="switch">
                                    <input id="sandBoxSelection" type="checkbox">
                                    <span class="slider round"></span>
                                </label>
                                SandBox
                            </strong>


                            <div class="additional_inp_sec">
                                <button id="sendPreview" class="sub_btn b_r"><i class="fa fa-refresh fa-spin"></i>Send
                                    Test
                                </button>
                            </div>

                        </div>
                    </div>

                </div>

            </div>
                </div>
            </div>
        </div>

    </div>

    <div class="prev_comp_sec_outer email-2_comp_outer clearfix">

        <div class="right_pre_comp_sec b_r">

            <div class="pre_comp_body panel-container clearfix">

                <div class="e2_compos_left_sec panel-left resizable" style="width: 72%">
                    <div class="pre_comp_title">
                        <label> Compose HTML </label>

                    </div>

                    <div class="add_tag_sec_outer   clearfix">
                        <div class="new_btn_div">
                            <a id="resetToGlobalEmail" href="#" data-toggle="tooltip" data-placement="top"
                               title="Reset to original!"><span
                                        class="glyphicon glyphicon-retweet"></span></a>
                            <button type="button" name="button" class="text_plus_icon"> Languages
                            </button>
                        </div>
                        <div id="selectLang" class="left_tag_sec">
                                        <span data-lang="en" class="active"><img
                                                    src="{{asset('assets/images/flag_icon_England.png')}}"
                                                    alt="#"></span>
                            <span data-lang="ar"> <img src="{{asset('assets/images/flag_icon_UAE.png')}}"
                                                       alt="#"></span>
                        </div>
                    </div>

                    <textarea name="content" id="editor"></textarea>

                </div>

                <div class="splitter clearfix" style="touch-action: none;"></div>

                <div class="comp_detail_outer e2_compos_right_sec panel-right">
                    <div class="pre_comp_title">
                        <label>Settings</label>
                        <div class="pre_comp_title_icons2 clearfix">
                            <ul>
                                <li><a class="pre_comp_view active" href="#comp_view_sec2"> <i></i>
                                    </a></li>
                            </ul>
                        </div>
                    </div>

                    <div class="comp_det_sec comp_view_sec" id="comp_view_sec2" style="display: block">

                        <div class="view_top_sec">

                            <h3> Test Recipients </h3>
                            <p>
                                Select at least one Content Test Group or individual user to
                                receive this test message. Message will be customized with
                                recipients attributes by default.
                            </p>

                            <div class="comp_input_sec">
                                <label>From Email </label>
                                <input type="email" name="email" value="" id="emailTestUsers" placeholder="email"
                                       style="border-radius: 6px; border: 1px solid #aaa; padding: 5px 7px;">
                                <span id="emailTestUsersError" style="color: #F99;"></span>
                            </div>

                            <div class="comp_input_sec">
                                <label> Subject </label>
                                <input id="subjectTestUsers" placeholder="Subject"
                                       style="border-radius: 6px; border: 1px solid #aaa; padding: 5px 7px;"/>
                                <span id="subjectTestUsersError" style="color: #F99;"></span>
                            </div>

                            <div class="comp_input_sec">
                                <label> Add Users </label>
                                <select id="userSelectEmail">
                                </select>
                                <span id="userErrorEmail" style="color: #F99;"></span>
                            </div>
                            <div class="additional_inp_sec  ">

                                <button id="emailTestPreview" class="sub_btn b_r"><i class="fa fa-refresh fa-spin"></i>Send
                                    Test
                                </button>

                            </div>

                        </div>


                    </div>

                </div>

            </div>

        </div>

    </div>

    <div id="inAppPushPlaceHolders" style="display: none;">
    </div>

    <img id="sizeImage" src="">

</div>

<style>
    .cke_button__save, .cke_button__newpage,
    .cke_button__preview, .cke_button__print,
    .cke_button__templates, .cke_button__cut,
    .cke_button__copy, .cke_button__paste,
    .cke_button__pastetext, .cke_button__pastefromword,
    .cke_button__undo, .cke_button__redo,
    .cke_button__find, .cke_button__replace,
    .cke_button__selectall, .cke_button__scayt,
    .cke_button__form, .cke_button__checkbox,
    .cke_button__radio, .cke_button__textfield,
    .cke_button__textarea, .cke_button__select,
    .cke_button__button, .cke_button__imagebutton,
    .cke_button__hiddenfield, .cke_button__strike,
    .cke_button__subscript, .cke_button__superscript,
    .cke_button__copyformatting, .cke_button__removeformat,
    .cke_button__numberedlist, .cke_button__bulletedlist,
    .cke_button__outdent, .cke_button__indent,
    .cke_button__blockquote, .cke_button__creatediv,
    .cke_button__justifyblock, .cke_button__bidiltr,
    .cke_button__bidirtl, .cke_button__language,
    .cke_button__unlink, .cke_button__anchor,
    .cke_button__flash, .cke_button__table,
    .cke_button__horizontalrule, .cke_button__smiley,
    .cke_button__specialchar, .cke_button__pagebreak,
    .cke_button__iframe, .cke_combo__styles,
    .cke_combo__format, /*.cke_button__textcolor,*/
        /*.cke_button__bgcolor,*/
    .cke_button__maximize,
    .cke_button__showblocks, .cke_button__about,
    .cke_toolbar_separator, .cke_toolbar_break {
        display: none !important;
    }

    .cke_top {
        padding: 20px 28px !important;
        background: transparent !important;
    }

    .cke_reset_all * {
        font-size: 14px !important;
        font-family: arial !important;
    }

    #sizeImage {
        max-width: none;
        display: none;
        width: auto;
        height: auto;
        max-height: none
    }
</style>