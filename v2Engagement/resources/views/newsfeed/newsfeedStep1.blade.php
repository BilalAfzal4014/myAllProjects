<form id="COMPOSE" class="newsfeed" name="newsfeed_step_one" autocomplete="off" method="post" action=""
      enctype="multipart/form-data">

    <input class="form-control" value="@if(isset($news)){{$news->image_url}}@endif" id="image_url" type="hidden"
           name="image_url" size="20">
    @include('cropper')
    <div class="bcd_stp1 step-tab-panel nf_step_1 active">
        <div class="Campaigns_input">
            <input type="text" name="m_name" id="m_name" value="@if(isset($news)){{$news->name}}@endif" maxlength="50"
                   class="b_r" placeholder="Enter Card Name">
        </div>


        <input type="text" name="newstags" placeholder="Enter tags" id="newstags"
               value="@if(isset($news)){{$news->tags}}@endif" class="tags"/>
        <div class="prev_comp_sec_outer nf_prev_compos_sec clearfix">

            <div class="left_pre_comp_sec b_r" style="height: auto;">
                <div class="pre_comp_title">
                    <label> <span id="newsFeedCardType"></span> Newsfeed Preview </label>
                </div>

                <div class="pre_comp_body">

                    <div class="preview_selection clearfix" style="margin: 10px;">
                        <span id="typeError" class="inputError" style="color: #f00;"></span>
                        <div class="inp_select">

                            <select name="type_id" class="b_r" data-placeholder="Please select one..."
                                    id="select_card_type" required tabindex="1">
                                <option value="">Please Select Card Type</option>
                            </select>
                        </div>

                        <div class="nf_temp_img_sec1">
                            <div class="col-xs-12" style="border: 1px dotted #ccc" id="preview_div">

                            </div>
                            <span id="imageError" class="inputError"
                                  style="display: none; color: #f00; font-size: 14px;">Image is required</span>
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

                                <div class="add_tag_sec_outer   clearfix">

                                    <div style="width: 175px;" class="new_btn_div">
                                        <a style="right: 108px !important;" class="templateResetBtn"
                                           id="resetToOriginal" href="#"
                                           data-toggle="tooltip" data-placement="top"
                                           title="Reset to original!"><span
                                                    class="glyphicon glyphicon-retweet"></span></a>
                                        <button type="button" name="button" class="text_plus_icon"> Languages
                                        </button>
                                    </div>
                                    <div id="selectLangNewsFeed" class="left_tag_sec">
                                        <span data-lang="en" class="active"><img
                                                    src="{{asset('assets/images/flag_icon_England.png')}}"
                                                    alt="#"></span>
                                        <span data-lang="ar"> <img src="{{asset('assets/images/flag_icon_UAE.png')}}"
                                                                   alt="#"></span>
                                    </div>

                                </div>

                                <div class="comp_input_sec p_t_b ">
                                    <label>Category</label>
                                    <div class="inp_select">

                                        <select name="newsfeed_category" class="b_r" required
                                                data-placeholder="Please select a category"
                                                id="newsfeed_category" tabindex="1">
                                            <option value="">Please Select a Category</option>
                                            <option value="News">News</option>
                                            <option value="Advertising">Advertising</option>
                                            <option value="Announcements">Announcements</option>
                                            <option value="Social">Social</option>
                                        </select>
                                    </div>

                                </div>

                                <div class="comp_input_sec p_t_b ">
                                    <label>Title</label>
                                    <span id="titleError" class="inputError"
                                          style="color: #f00;"></span>
                                    <div class="Campaigns_input  nf_plus_icon_add">
                                        <input type="text" name="m_title" id="m_title"
                                               value="@if(isset($newsFeedEn)){{$newsFeedEn->title}}@endif" required
                                               placeholder="Enter Card Name" class="b_r">
                                    </div>

                                </div>


                                <div class="comp_input_sec p_t_b">
                                    <label>Message</label>
                                    <div class="nf_plus_icon_add">

                                        <textarea name="m_desc" id="m_desc" class="b_r " rows="5" required cols="50"
                                                  @if(isset($newsFeedEn) and $news->news_feed_template_id == 4) disabled @endif>@if(isset($newsFeedEn)){{$newsFeedEn->message}}@endif</textarea>
                                    </div>
                                </div>
                                <div class="add_tag_sec_outer clearfix">
                                    <label>Image</label>
                                    <button type="button" id="open_model" class="btn form-control"
                                            style="width:155px;background: #2a8689;float: right;margin-top: -5px;    color: white">
                                        Launch image gallery
                                    </button>
                                    <br>
                                    <label style="margin-top: 10px;">Action
                                        text</label>
                                    <input maxlength="255" type="text" name="link_text"
                                           value="@if(isset($newsFeedEn)){{$newsFeedEn->link_text}}@endif"
                                           id="m_link_text"
                                           class="form-control" placeholder="Link Text">
                                </div>
                                <div class="device_option_title p_t_b">
                                    <label> On Click Behavior </label>
                                </div>
                                <div class="comp_input_sec  ">
                                    <div style="margin-bottom: 15px;">
                                        <div class="Campaigns_type_sec inp_select ma_sel1 b_r">
                                            <select class="link_type" data-id="android_url" name="link_type_android"
                                                    id="link_type_android" data-placeholder="Please select one...">
                                                <option value="WEBLINK"> Redirect to Web URL</option>
                                                <option value="DEEPLINK"> Deep Link Into App</option>
                                            </select>
                                        </div>
                                        <input type="@if(isset($news) and  $news->link_type_android == 'DEEPLINK' ) text @else url @endif "
                                               required name="android_url"
                                               value="@if(isset($news)){{$news->android_url}}@endif" id="android_url"
                                               class="form-control" placeholder="Link">
                                    </div>
                                    <div style="margin-bottom: 15px;">
                                        <div class="Campaigns_type_sec inp_select  ma_sel2 b_r">
                                            <select class="link_type" data-id="ios_url" name="link_type_ios"
                                                    id="link_type_ios"
                                                    data-placeholder="Please select one...">
                                                <option value="WEBLINK"> Redirect to Web URL</option>
                                                <option value="DEEPLINK"> Deep Link Into App</option>
                                            </select>

                                        </div>
                                        <input type="@if(isset($news) and  $news->link_type_ios == 'DEEPLINK' ) text @else url @endif"
                                               required name="ios_url" value="@if(isset($news)){{$news->ios_url}}@endif"
                                               id="ios_url" class="form-control" placeholder="Link">
                                    </div>
                                    <div style="margin-bottom: 15px;">
                                        <div class="Campaigns_type_sec inp_select ma_sel3 b_r">
                                            <select class="link_type" data-id="window_url" name="link_type_window"
                                                    id="link_type_window"
                                                    data-placeholder="Please select one...">
                                                <option value="WEBLINK"> Redirect to Web URL</option>
                                                <option value="DEEPLINK"> Deep Link Into App</option>
                                            </select>

                                        </div>
                                        <input type="@if(isset($news) and  $news->link_type_window == 'DEEPLINK' ) text @else url @endif"
                                               required name="window_url"
                                               value="@if(isset($news)){{$news->window_url}}@endif" id="window_url"
                                               class="form-control" placeholder="Link">
                                    </div>
                                    <div style="margin-bottom: 15px;">
                                        <div class="Campaigns_type_sec inp_select ma_sel4 b_r">
                                            <select class="link_type" data-id="web_url" name="link_type_web"
                                                    id="link_type_web" data-placeholder="Please select one...">
                                                <option value="WEBLINK"> Redirect to Web URL</option>
                                                <option value="DEEPLINK"> Deep Link Into App</option>
                                            </select>

                                        </div>

                                        <input type="@if(isset($news) and  $news->link_type_web == 'DEEPLINK' ) text @else url @endif"
                                               required name="web_url" value="@if(isset($news)){{$news->web_url}}@endif"
                                               id="web_url" class="form-control" placeholder="Link">
                                    </div>

                                </div>

                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </div>


    </div>


</form>