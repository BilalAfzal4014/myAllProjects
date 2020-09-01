@extends('layouts.master')

@section('searchBar')
    {{--<div class="tp_BreadCrumb_list_sec clearfix">--}}
        {{--<label class="sec_tp_title"> Company Stats </label>--}}
        {{--<div class="track_userdeta_right clearfix" style="height: 64px;">--}}
            {{--<div class="tp_BreadCrumb_srch_inp" style="width:96%">--}}
                {{--<input type="hidden" name="" value="" placeholder="Search...">--}}
            {{--</div>--}}
            {{--<span class="tp_BreadCrumb_info_icon"> <i class="noticification_icon"></i> </span>--}}
        {{--</div>--}}
    {{--</div>--}}
@stop

@section('create')
    <input class="companyId" type="hidden" value="{{$companyId}}">
@stop




@section('content')
{{--    @include('partials.left-scroll-bar')--}}
    <div class="dash_chart_head clearfix">
        <div class=" usr_profile_top_outer clearfix" style="padding: 22px 22px;">
            <div class="usr_profile_pic_detail clearfix">
                <div class="usr_profile_pic">
                    <span> <img src="" alt="#"> </span>
                </div>
                <div class="usr_profile_det">
                    <ul>
                        <li><a href="#" class="usr_profile_name"></a></li>
                        <li><span id="userId"><a href="{{asset('/users/' . $companyId . '/edit')}}"
                                                 class="usr_sett_icon" title="Setting"></a></span>

                        </li>
                        <li><a id="emailId" href="#"><img src="{{asset('assets/images/email_icon.png')}}" alt="#"></a>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="usr_profile_with_map">
                <div class="usr_profile_phone_sec ">
                    <a href="#" class="phon_no"><img src="{{asset('assets/images/phone_icon.png')}}" alt="#"></a>
                </div>
                <div class="usr_profile_map">
                    <label> Most Recent Location </label>
                    <span> <img src="{{asset('assets/images/map_img.png')}}" alt="#"> </span>
                </div>
            </div>

        </div>
        @if (Session::get('MSG'))
            <div class="alert {{ Session::get('MSG')['CLASS'] }}">
                {{Session::get('MSG')['MSG']}}
            </div>
        @endif
        <div class="usr_prof_btm_tab_outer">
            <div class="usr_prof_tab_btns clearfix">
                <ul>
                    <li><a href="#usr_prof_overview" class="active"> Overview </a></li>
                    <li><a href="#usr_prof_engagement"> Engagement </a></li>
                    {{--<li><a href="#import_targeted_clients"> Import Targeted Clients </a></li>--}}
                    <li><a href="#usr_prof_feedback" style="display: none;"> Feedback </a></li>
                    <li><a href="#usr_prof_social" style="display: none;"> Social </a></li>
                </ul>
            </div>
            <div class="usr_prof_tab_detail_outer">
                <div class="usr_prof_tab_det usr_prof_overview" id="usr_prof_overview" style="display:block;">
                    <div class="usr_prof_tab_box">
                        <label class="usr_prof_tab_box_title"> <img src="{{asset('assets/images/profile_icon.png')}}"
                                                                    alt="#"> Campaign Coversion
                        </label>
                        <div class="usr_prof_tab_box_detlist mCustomScrollbar _mCS_1">
                            <table id="profileInfo">

                            </table>
                        </div>
                    </div>
                    <div class="usr_prof_tab_box">
                        <label class="usr_prof_tab_box_title"> <img src="{{asset('assets/images/app_usage_icon.png')}}"
                                                                    alt="#"> Campaign Trigger
                        </label>
                        <div class="usr_prof_tab_box_detlist mCustomScrollbar _mCS_1">
                            <table id="appUsage">

                            </table>
                        </div>
                    </div>
                    <div class="usr_prof_tab_box">
                        <label class="usr_prof_tab_box_title"> <img
                                    src="{{asset('assets/images/custome_att_icon.png')}}" alt="#"> Custom
                            Attributes </label>
                        <div class="usr_prof_tab_box_detlist mCustomScrollbar _mCS_1">
                            <table id="customAttr">

                            </table>
                        </div>
                    </div>
                    <div class="usr_prof_tab_box">
                        <label class="usr_prof_tab_box_title"> <img src="{{asset('assets/images/app_icon.png')}}"
                                                                    alt="#"> User List
                        </label>
                        <div class="usr_prof_tab_box_detlist mCustomScrollbar _mCS_1">
                            <table id="deviceToken">

                            </table>
                        </div>
                    </div>
                    <div style="display: none;" class="usr_prof_tab_box">
                        <label class="usr_prof_tab_box_title"> <img src="{{asset('assets/images/calender_icon.png')}}"
                                                                    alt="#"> Custom
                            Events </label>
                        <div class="usr_prof_tab_box_detlist mCustomScrollbar _mCS_1">
                            <table>
                                <tr>
                                    <td>Began Purchase</td>
                                    <td style="width:15%">125</td>
                                    <td> Jun 4, 2017 at 11:58 am</td>
                                </tr>
                                <tr>
                                    <td>Lost On Leaderboard</td>
                                    <td style="width:15%">2</td>
                                    <td> Jan 2, 2017 at 8:00 pm</td>
                                </tr>
                                <tr>
                                    <td>New Badge</td>
                                    <td style="width:15%">9</td>
                                    <td> Dec 7, 2017 at 7:14 pm</td>
                                </tr>
                                <tr>
                                    <td>New Level</td>
                                    <td style="width:15%">367</td>
                                    <td> Dec 13, 2017 at 1:58 pm</td>
                                </tr>
                                <tr>
                                    <td>Ping Offer</td>
                                    <td style="width:15%">13</td>
                                    <td> 19 days ago</td>
                                </tr>
                                <tr>
                                    <td>PingOffer</td>
                                    <td style="width:15%">4</td>
                                    <td> Dec 1, 2015 at 10:40 pm</td>
                                </tr>
                                <tr>
                                    <td>Receive Ping</td>
                                    <td style="width:15%">4</td>
                                    <td> Oct 16, 2016 at 8:37 pm</td>
                                </tr>
                                <tr>
                                    <td>Redeemed</td>
                                    <td style="width:15%">1</td>
                                    <td> 4 days ago</td>
                                </tr>
                                <tr>
                                    <td>Began Purchase</td>
                                    <td style="width:15%">125</td>
                                    <td> Jun 4, 2017 at 11:58 am</td>
                                </tr>
                                <tr>
                                    <td>Lost On Leaderboard</td>
                                    <td style="width:15%">2</td>
                                    <td> Jan 2, 2017 at 8:00 pm</td>
                                </tr>
                                <tr>
                                    <td>New Badge</td>
                                    <td style="width:15%">9</td>
                                    <td> Dec 7, 2017 at 7:14 pm</td>
                                </tr>
                                <tr>
                                    <td>New Level</td>
                                    <td style="width:15%">367</td>
                                    <td> Dec 13, 2017 at 1:58 pm</td>
                                </tr>
                                <tr>
                                    <td>Ping Offer</td>
                                    <td style="width:15%">13</td>
                                    <td> 19 days ago</td>
                                </tr>
                                <tr>
                                    <td>PingOffer</td>
                                    <td style="width:15%">4</td>
                                    <td> Dec 1, 2015 at 10:40 pm</td>
                                </tr>
                                <tr>
                                    <td>Receive Ping</td>
                                    <td style="width:15%">4</td>
                                    <td> Oct 16, 2016 at 8:37 pm</td>
                                </tr>
                                <tr>
                                    <td>Redeemed</td>
                                    <td style="width:15%">1</td>
                                    <td> 4 days ago</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                    <div style="display: none;" class="usr_prof_tab_box">
                        <label class="usr_prof_tab_box_title"> <img src="{{asset('assets/images/product_icon.png')}}"
                                                                    alt="#"> Purchase
                        </label>
                        <div class="usr_prof_tab_box_detlist mCustomScrollbar _mCS_1">
                            <table cell-pad>
                                <tr>
                                    <td><label> Overview </label></td>
                                    <td style="width:15%;"></td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td>Lifetime Revinue</td>
                                    <td style="width:15%;"></td>
                                    <td> $204.20</td>
                                </tr>
                                <tr>
                                    <td>Last Purchase</td>
                                    <td style="width:15%;"></td>
                                    <td> Oct 25, 2017 at 3:39 pm</td>
                                </tr>
                                <tr>
                                    <td>Total Number of Purchases</td>
                                    <td style="width:15%;"></td>
                                    <td> 3</td>
                                </tr>
                                <tr>
                                    <td><label> Name </label></td>
                                    <td style="width:15%;"><label> Count </label></td>
                                    <td><label> Last Time </label></td>
                                </tr>
                                <tr>
                                    <td>D18BDDL</td>
                                    <td style="width:15%;">1</td>
                                    <td> Oct 25, 2017 at 3:39 pm</td>
                                </tr>
                                <tr>
                                    <td><label> Overview </label></td>
                                    <td style="width:15%;"></td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td>Lifetime Revinue</td>
                                    <td style="width:15%;"></td>
                                    <td> $204.20</td>
                                </tr>
                                <tr>
                                    <td>Last Purchase</td>
                                    <td style="width:15%;"></td>
                                    <td> Oct 25, 2017 at 3:39 pm</td>
                                </tr>
                                <tr>
                                    <td>Total Number of Purchases</td>
                                    <td style="width:15%;"></td>
                                    <td> 3</td>
                                </tr>
                                <tr>
                                    <td><label> Name </label></td>
                                    <td style="width:15%;"><label> Count </label></td>
                                    <td><label> Last Time </label></td>
                                </tr>
                                <tr>
                                    <td>D18BDDL</td>
                                    <td style="width:15%;">1</td>
                                    <td> Oct 25, 2017 at 3:39 pm</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                    <!--  -->
                </div>
                <div class="usr_prof_tab_det usr_prof_engagement" id="usr_prof_engagement">
                    <div class="usr_prof_tab_box">
                        <label class="usr_prof_tab_box_title"> <img
                                    src="{{asset('assets/images/contact_set_icon.png')}}" alt="#"> Campaign Clicks
                        </label>
                        <div class="usr_prof_tab_box_detlist mCustomScrollbar _mCS_1">
                            <table>
                                <tr>
                                    <td style="vertical-align: top;">Email Clicks<i class="noticification_icon"></i>
                                    </td>
                                    <td style="width:68%;">
                                        <table id="emailClicks">

                                        </table>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                    <div class="usr_prof_tab_box">
                        <label class="usr_prof_tab_box_title"> <img src="{{asset('assets/images/app_icon.png')}}"
                                                                    alt="#"> Campaign Received
                            <p>( Total - 1,2356 )</p></label>
                        <div class="usr_prof_tab_box_detlist mCustomScrollbar _mCS_1">
                            <table id="campaignInformation">

                            </table>
                        </div>
                    </div>
                    <div class="usr_prof_tab_box">
                        <label class="usr_prof_tab_box_title"> <img src="{{asset('assets/images/app_usage_icon.png')}}"
                                                                    alt="#">Segments
                        </label>
                        <div class="usr_prof_tab_box_detlist mCustomScrollbar _mCS_1">
                            <table id="segmentsInfo">
                                <tr>
                                    <td style="vertical-align: top;">Email Clicks<i class="noticification_icon"></i>
                                    </td>
                                    <td style="width:68%;">
                                        <table>

                                        </table>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                    {{--<div class="usr_prof_tab_box">
                        <label class="usr_prof_tab_box_title"> <img src="{{asset('assets/images/profile_icon.png')}}"
                                                                    alt="#">
                            Communications Stats </label>
                        <div class="usr_prof_tab_box_detlist mCustomScrollbar _mCS_1">
                            <table>
                                <tr>
                                    <td style="width:60%;"> Last Received Any Campaign</td>
                                    <td> 1 days ago</td>
                                </tr>
                                <tr>
                                    <td> Last Received Push</td>
                                    <td> 14 days ago</td>
                                </tr>
                                <tr>
                                    <td> Last Received Email</td>
                                    <td> 14 days ago</td>
                                </tr>
                                <tr>
                                    <td> Last Received Webhosts</td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td>Last Viewed News Feed</td>
                                    <td>7 hours ago</td>
                                </tr>
                                <tr>
                                    <td> New Feed Impressions</td>
                                    <td> 1,394</td>
                                </tr>
                            </table>
                        </div>
                    </div>--}}
                    <div class="usr_prof_tab_box">
                        <label class="usr_prof_tab_box_title"> <img src="{{asset('assets/images/app_icon.png')}}"
                                                                    alt="#">News Feed Cards
                            Clicked </label>
                        <div class="usr_prof_tab_box_detlist mCustomScrollbar _mCS_1">
                            <table>
                                <tr>
                                    <td style="vertical-align: top;">News Feed Clicks<i class="noticification_icon"></i>
                                    </td>
                                    <td style="width:68%;">
                                        <table id="newsFeedClicks">

                                        </table>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                {{--<div class="usr_prof_tab_box">
                    <label class="usr_prof_tab_box_title"> <img
                                src="{{asset('assets/images/install_att_icon.png')}}" alt="#"> Install
                        Attributes </label>
                    <div class="usr_prof_tab_box_detlist mCustomScrollbar _mCS_1">
                        <table>
                            <tr>
                                <td style="text-align:center;"> No Install Attribution Information.</td>
                            </tr>
                        </table>
                    </div>
                </div>--}}
                <!--  -->
                </div>
                {{--<div class="usr_prof_tab_det import_targeted_clients" id="import_targeted_clients">--}}
                    {{--<form action="{{ route('importTargetedUsers') }}" method="post" enctype="multipart/form-data">--}}
                        {{--{{ csrf_field() }}--}}
                        {{--<div class="col-sm-12">--}}
                            {{--<div class="col-sm-3 form-group">--}}
                                {{--<input type="file" name="imported_file" class="btn btn-primary form-control"/>--}}
                            {{--</div>--}}
                        {{--</div>--}}
                        {{--<div class="col-sm-12">--}}
                            {{--<div class="col-sm-2 form-group">--}}
                                {{--<button type="submit" class="btn btn-primary form-control">Submit</button>--}}
                            {{--</div>--}}
                            {{--<div class="col-sm-3 form-group">--}}
                                {{--<a style="line-height: 4;" href="{{ route('downloadSampleFile') }}">Download Sample File</a>--}}
                            {{--</div>--}}
                    {{--</form>--}}

                {{--</div>--}}
                <div class="usr_prof_tab_det usr_prof_feedback" id="usr_prof_feedback">
                    <div class="usr_prof_tab_box">
                        <label class="usr_prof_tab_box_title clearfix">
                            <img src="images/feedback_icon.png" alt="#"> Feedback
                            <div class="feedback_right_tab_btn">
                                <ul>
                                    <li class="active "><a href="#fb_prof_idea" class="fb_idea">Idea</a></li>
                                    <li><a href="#fb_prof_problem" class="fb_pro">Problem</a></li>
                                    <li><a href="#fb_prof_ques" class="fb_ques">Question</a></li>
                                </ul>
                            </div>
                        </label>
                        <div class="usr_prof_tab_box_detlist">
                            <div class="fb_prof_tab_det fb_prof_idea" id="fb_prof_idea" style="display:block;">
                                <div class="feedback_sec_outer clearfix">
                                       <span class="feedback_left_img">
                                         <img src="images/feedback_pro_img.png" alt="#">
                                       </span>
                                    <div class="feedback_right_sec">
                                        <div class="fb_profile_name_sec">
                                            <label>Lorem ipsum dolor sit amet, consectetuer adipiscing elit.</label>
                                            <span>Under Review</span>
                                        </div>
                                        <div class="fb_comment_sec">
                                            <p>
                                                Aenean commodo ligula eget dolor. Aenean massa. Cum sociis natoque
                                                penatibus etmagnis dis
                                                parturient montes, nascetur ridiculus mus. Donec quam felis, ultricies
                                                nec, pellentesque eu,
                                                pretium quis, sem. Nulla consequat massa quis enim. Donec pede justo,
                                                fringill vel, aliquet nec,
                                                vulputate eget, arcu. In enim justo, rhoncus ut, imperdiet a, venenatis
                                                vitae, justo. Nullam
                                                dictum felis eu pede mollis pretium. <a href="#"> read more...</a>
                                            </p>
                                        </div>
                                        <div class="fb_btm_action_sec clearfix">
                                            <ul>
                                                <li>
                                                    <p>By <a href="#"> Keiron </a></p>
                                                </li>
                                                <li>
                                                    <p class="comment_icon"><a href="#"> 7 Comments </a></p>
                                                </li>
                                                <li>
                                                    <p><a href="#"> Delete </a></p>
                                                </li>
                                                <li>
                                                    <p><a href="#"> Reply </a></p>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                                <div class="feedback_sec_outer clearfix">
                                       <span class="feedback_left_img">
                                         <img src="images/feedback_pro_img.png" alt="#">
                                       </span>
                                    <div class="feedback_right_sec">
                                        <div class="fb_profile_name_sec">
                                            <label>Lorem ipsum dolor sit amet, consectetuer adipiscing elit.</label>
                                            <span>Under Review</span>
                                        </div>
                                        <div class="fb_comment_sec">
                                            <p>
                                                Aenean commodo ligula eget dolor. Aenean massa. Cum sociis natoque
                                                penatibus etmagnis dis
                                                parturient montes, nascetur ridiculus mus. Donec quam felis, ultricies
                                                nec, pellentesque eu,
                                                pretium quis, sem. Nulla consequat massa quis enim. Donec pede justo,
                                                fringill vel, aliquet nec,
                                                vulputate eget, arcu. In enim justo, rhoncus ut, imperdiet a, venenatis
                                                vitae, justo. Nullam
                                                dictum felis eu pede mollis pretium. <a href="#"> read more...</a>
                                            </p>
                                        </div>
                                        <div class="fb_btm_action_sec clearfix">
                                            <ul>
                                                <li>
                                                    <p>By <a href="#"> Keiron </a></p>
                                                </li>
                                                <li>
                                                    <p class="comment_icon"><a href="#"> 7 Comments </a></p>
                                                </li>
                                                <li>
                                                    <p><a href="#"> Delete </a></p>
                                                </li>
                                                <li>
                                                    <p><a href="#"> Reply </a></p>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                                <div class="feedback_sec_outer clearfix">
                                     <span class="feedback_left_img">
                                       <img src="images/feedback_pro_img.png" alt="#">
                                     </span>
                                    <div class="feedback_right_sec">
                                        <div class="fb_profile_name_sec">
                                            <label>Lorem ipsum dolor sit amet, consectetuer adipiscing elit.</label>
                                            <span>Under Review</span>
                                        </div>
                                        <div class="fb_comment_sec">
                                            <p>
                                                Aenean commodo ligula eget dolor. Aenean massa. Cum sociis natoque
                                                penatibus etmagnis dis
                                                parturient montes, nascetur ridiculus mus. Donec quam felis, ultricies
                                                nec, pellentesque eu,
                                                pretium quis, sem. Nulla consequat massa quis enim. Donec pede justo,
                                                fringill vel, aliquet nec,
                                                vulputate eget, arcu. In enim justo, rhoncus ut, imperdiet a, venenatis
                                                vitae, justo. Nullam
                                                dictum felis eu pede mollis pretium. <a href="#"> read more...</a>
                                            </p>
                                        </div>
                                        <div class="fb_btm_action_sec clearfix">
                                            <ul>
                                                <li>
                                                    <p>By <a href="#"> Keiron </a></p>
                                                </li>
                                                <li>
                                                    <p class="comment_icon"><a href="#"> 7 Comments </a></p>
                                                </li>
                                                <li>
                                                    <p><a href="#"> Delete </a></p>
                                                </li>
                                                <li>
                                                    <p><a href="#"> Reply </a></p>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="fb_prof_tab_det fb_prof_problem" id="fb_prof_problem">
                                <div class="feedback_sec_outer clearfix">
                                       <span class="feedback_left_img">
                                         <img src="images/feedback_pro_img.png" alt="#">
                                       </span>
                                    <div class="feedback_right_sec">
                                        <div class="fb_profile_name_sec">
                                            <label>Lorem ipsum dolor sit amet, consectetuer adipiscing elit.</label>
                                            <span>Under Review</span>
                                        </div>
                                        <div class="fb_comment_sec">
                                            <p>
                                                Aenean commodo ligula eget dolor. Aenean massa. Cum sociis natoque
                                                penatibus etmagnis dis
                                                parturient montes, nascetur ridiculus mus. Donec quam felis, ultricies
                                                nec, pellentesque eu,
                                                pretium quis, sem. Nulla consequat massa quis enim. Donec pede justo,
                                                fringill vel, aliquet nec,
                                                vulputate eget, arcu. In enim justo, rhoncus ut, imperdiet a, venenatis
                                                vitae, justo. Nullam
                                                dictum felis eu pede mollis pretium. <a href="#"> read more...</a>
                                            </p>
                                        </div>
                                        <div class="fb_btm_action_sec clearfix">
                                            <ul>
                                                <li>
                                                    <p>By <a href="#"> Keiron </a></p>
                                                </li>
                                                <li>
                                                    <p class="comment_icon"><a href="#"> 7 Comments </a></p>
                                                </li>
                                                <li>
                                                    <p><a href="#"> Delete </a></p>
                                                </li>
                                                <li>
                                                    <p><a href="#"> Reply </a></p>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                                <div class="feedback_sec_outer clearfix">
                                       <span class="feedback_left_img">
                                         <img src="images/feedback_pro_img.png" alt="#">
                                       </span>
                                    <div class="feedback_right_sec">
                                        <div class="fb_profile_name_sec">
                                            <label>Lorem ipsum dolor sit amet, consectetuer adipiscing elit.</label>
                                            <span>Under Review</span>
                                        </div>
                                        <div class="fb_comment_sec">
                                            <p>
                                                Aenean commodo ligula eget dolor. Aenean massa. Cum sociis natoque
                                                penatibus etmagnis dis
                                                parturient montes, nascetur ridiculus mus. Donec quam felis, ultricies
                                                nec, pellentesque eu,
                                                pretium quis, sem. Nulla consequat massa quis enim. Donec pede justo,
                                                fringill vel, aliquet nec,
                                                vulputate eget, arcu. In enim justo, rhoncus ut, imperdiet a, venenatis
                                                vitae, justo. Nullam
                                                dictum felis eu pede mollis pretium. <a href="#"> read more...</a>
                                            </p>
                                        </div>
                                        <div class="fb_btm_action_sec clearfix">
                                            <ul>
                                                <li>
                                                    <p>By <a href="#"> Keiron </a></p>
                                                </li>
                                                <li>
                                                    <p class="comment_icon"><a href="#"> 7 Comments </a></p>
                                                </li>
                                                <li>
                                                    <p><a href="#"> Delete </a></p>
                                                </li>
                                                <li>
                                                    <p><a href="#"> Reply </a></p>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                                <div class="feedback_sec_outer clearfix">
                                       <span class="feedback_left_img">
                                         <img src="images/feedback_pro_img.png" alt="#"> </span>
                                    <div class="feedback_right_sec">
                                        <div class="fb_profile_name_sec">
                                            <label>Lorem ipsum dolor sit amet, consectetuer adipiscing elit.</label>
                                            <span>Under Review</span>
                                        </div>
                                        <div class="fb_comment_sec">
                                            <p>
                                                Aenean commodo ligula eget dolor. Aenean massa. Cum sociis natoque
                                                penatibus etmagnis dis
                                                parturient montes, nascetur ridiculus mus. Donec quam felis, ultricies
                                                nec, pellentesque eu,
                                                pretium quis, sem. Nulla consequat massa quis enim. Donec pede justo,
                                                fringill vel, aliquet nec,
                                                vulputate eget, arcu. In enim justo, rhoncus ut, imperdiet a, venenatis
                                                vitae, justo. Nullam
                                                dictum felis eu pede mollis pretium. <a href="#"> read more...</a>
                                            </p>
                                        </div>
                                        <div class="fb_btm_action_sec clearfix">
                                            <ul>
                                                <li>
                                                    <p>By <a href="#"> Keiron </a></p>
                                                </li>
                                                <li>
                                                    <p class="comment_icon"><a href="#"> 7 Comments </a></p>
                                                </li>
                                                <li>
                                                    <p><a href="#"> Delete </a></p>
                                                </li>
                                                <li>
                                                    <p><a href="#"> Reply </a></p>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="fb_prof_tab_det fb_prof_ques" id="fb_prof_ques">
                                <div class="feedback_sec_outer clearfix">
                                         <span class="feedback_left_img">
                                           <img src="images/feedback_pro_img.png" alt="#">
                                         </span>
                                    <div class="feedback_right_sec">
                                        <div class="fb_profile_name_sec">
                                            <label>Lorem ipsum dolor sit amet, consectetuer adipiscing elit.</label>
                                            <span>Under Review</span>
                                        </div>
                                        <div class="fb_comment_sec">
                                            <p>
                                                Aenean commodo ligula eget dolor. Aenean massa. Cum sociis natoque
                                                penatibus etmagnis dis
                                                parturient montes, nascetur ridiculus mus. Donec quam felis, ultricies
                                                nec, pellentesque eu,
                                                pretium quis, sem. Nulla consequat massa quis enim. Donec pede justo,
                                                fringill vel, aliquet nec,
                                                vulputate eget, arcu. In enim justo, rhoncus ut, imperdiet a, venenatis
                                                vitae, justo. Nullam
                                                dictum felis eu pede mollis pretium. <a href="#"> read more...</a>
                                            </p>
                                        </div>
                                        <div class="fb_btm_action_sec clearfix">
                                            <ul>
                                                <li>
                                                    <p>By <a href="#"> Keiron </a></p>
                                                </li>
                                                <li>
                                                    <p class="comment_icon"><a href="#"> 7 Comments </a></p>
                                                </li>
                                                <li>
                                                    <p><a href="#"> Delete </a></p>
                                                </li>
                                                <li>
                                                    <p><a href="#"> Reply </a></p>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                                <div class="feedback_sec_outer clearfix">
                                         <span class="feedback_left_img">
                                           <img src="images/feedback_pro_img.png" alt="#">
                                         </span>
                                    <div class="feedback_right_sec">
                                        <div class="fb_profile_name_sec">
                                            <label>Lorem ipsum dolor sit amet, consectetuer adipiscing elit.</label>
                                            <span>Under Review</span>
                                        </div>
                                        <div class="fb_comment_sec">
                                            <p>
                                                Aenean commodo ligula eget dolor. Aenean massa. Cum sociis natoque
                                                penatibus etmagnis dis
                                                parturient montes, nascetur ridiculus mus. Donec quam felis, ultricies
                                                nec, pellentesque eu,
                                                pretium quis, sem. Nulla consequat massa quis enim. Donec pede justo,
                                                fringill vel, aliquet nec,
                                                vulputate eget, arcu. In enim justo, rhoncus ut, imperdiet a, venenatis
                                                vitae, justo. Nullam
                                                dictum felis eu pede mollis pretium. <a href="#"> read more...</a>
                                            </p>
                                        </div>
                                        <div class="fb_btm_action_sec clearfix">
                                            <ul>
                                                <li>
                                                    <p>By <a href="#"> Keiron </a></p>
                                                </li>
                                                <li>
                                                    <p class="comment_icon"><a href="#"> 7 Comments </a></p>
                                                </li>
                                                <li>
                                                    <p><a href="#"> Delete </a></p>
                                                </li>
                                                <li>
                                                    <p><a href="#"> Reply </a></p>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                                <div class="feedback_sec_outer clearfix">
                                         <span class="feedback_left_img">
                                           <img src="images/feedback_pro_img.png" alt="#"> </span>
                                    <div class="feedback_right_sec">
                                        <div class="fb_profile_name_sec">
                                            <label>Lorem ipsum dolor sit amet, consectetuer adipiscing elit.</label>
                                            <span>Under Review</span>
                                        </div>
                                        <div class="fb_comment_sec">
                                            <p>
                                                Aenean commodo ligula eget dolor. Aenean massa. Cum sociis natoque
                                                penatibus etmagnis dis
                                                parturient montes, nascetur ridiculus mus. Donec quam felis, ultricies
                                                nec, pellentesque eu,
                                                pretium quis, sem. Nulla consequat massa quis enim. Donec pede justo,
                                                fringill vel, aliquet nec,
                                                vulputate eget, arcu. In enim justo, rhoncus ut, imperdiet a, venenatis
                                                vitae, justo. Nullam
                                                dictum felis eu pede mollis pretium. <a href="#"> read more...</a>
                                            </p>
                                        </div>
                                        <div class="fb_btm_action_sec clearfix">
                                            <ul>
                                                <li>
                                                    <p>By <a href="#"> Keiron </a></p>
                                                </li>
                                                <li>
                                                    <p class="comment_icon"><a href="#"> 7 Comments </a></p>
                                                </li>
                                                <li>
                                                    <p><a href="#"> Delete </a></p>
                                                </li>
                                                <li>
                                                    <p><a href="#"> Reply </a></p>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="usr_prof_tab_det usr_prof_social" id="usr_prof_social">
                    <div class="usr_prof_tab_box">
                        <label class="usr_prof_tab_box_title clearfix">
                            <img src="images/facebook_icon.png" alt="#"> Facebook
                        </label>
                        <div class="usr_prof_tab_box_detlist ">
                            <p>
                                Engagement Platform APIs can export data to your Facebook marketing audiences.
                            </p>
                            <div class="social_text_inp_outer clearfix">
                                <div class="left_social_text_sec">
                                    <label>Facebook Marketing App ID</label>
                                    <p>The Facebook App ID that identifies your <br>
                                        Facebook marketing app.
                                    </p>
                                </div>
                                <div class="right_social_sec">
                                      <span>
                                        <input type="text" name="" value="" placeholder="Facebook Marketing App ID">
                                      </span>
                                </div>
                            </div>
                            <div class="social_text_inp_outer clearfix">
                                <div class="left_social_text_sec">
                                    <label>Facebook Marketing App Secret</label>
                                    <p>The Facebook App Secret that will be used to <br>
                                        access your Facebook marketing app.
                                    </p>
                                </div>
                                <div class="right_social_sec">
                                      <span>
                                        <input type="text" name="" value="" placeholder="Facebook Marketing App Secret">
                                      </span>
                                </div>
                            </div>
                            <div class="social_text_inp_outer clearfix">
                                <div class="left_social_text_sec">
                                    <label>Facebook App ID</label>
                                    <p>Optional Facebook app ID that we can use to <br>
                                        identify your users from their Facebook user ID.
                                    </p>
                                </div>
                                <div class="right_social_sec">
                                      <span>
                                        <input type="text" name="" value="" placeholder="Facebook App ID">
                                      </span>
                                </div>
                            </div>
                        </div>
                        <label class="usr_prof_tab_box_title clearfix">
                            <img src="images/twitter_icon.png" alt="#"> Twitter
                        </label>
                        <div class="usr_prof_tab_box_detlist ">
                            <p>
                                Engagement Platform APIs can export data to your Twitter marketing audiences.
                            </p>
                            <div class="social_text_inp_outer clearfix">
                                <div class="left_social_text_sec">
                                    <label>Twitter Marketing App ID</label>
                                    <p>The Twitter App ID that identifies your <br>
                                        Twitter marketing app.
                                    </p>
                                </div>
                                <div class="right_social_sec">
                                      <span>
                                        <input type="email" name="" value="" placeholder="Twitter Marketing App ID">
                                      </span>
                                </div>
                            </div>
                            <div class="social_text_inp_outer clearfix">
                                <div class="left_social_text_sec">
                                    <label>twitter Marketing App Secret</label>
                                    <p>The twitter App Secret that will be used to <br>
                                        access your twitter marketing app.
                                    </p>
                                </div>
                                <div class="right_social_sec">
                                      <span>
                                        <input type="text" name="" value="" placeholder="Twitter Marketing App Secret">
                                      </span>
                                </div>
                            </div>
                            <div class="social_text_inp_outer clearfix">
                                <div class="left_social_text_sec">
                                    <label>Twitter App ID</label>
                                    <p>Optional Twitter app ID that we can use to <br>
                                        identify your users from their Twitter user ID.
                                    </p>
                                </div>
                                <div class="right_social_sec">
                                      <span>
                                        <input type="text" name="" value="" placeholder="Twitter App ID">
                                      </span>
                                </div>
                            </div>
                        </div>
                        <label class="usr_prof_tab_box_title clearfix">
                            <img src="images/linkedin_icon.png" alt="#"> Linkedin
                        </label>
                        <div class="usr_prof_tab_box_detlist ">
                            <p>
                                Engagement Platform APIs can export data to your Linkedin marketing audiences.
                            </p>
                            <div class="social_text_inp_outer clearfix">
                                <div class="left_social_text_sec">
                                    <label>Linkedin Marketing App ID</label>
                                    <p>The Linkedin App ID that identifies your <br>
                                        Linkedin marketing app.
                                    </p>
                                </div>
                                <div class="right_social_sec">
                                      <span>
                                        <input type="email" name="" value="" placeholder="Linkedin Marketing App ID">
                                      </span>
                                </div>
                            </div>
                            <div class="social_text_inp_outer clearfix">
                                <div class="left_social_text_sec">
                                    <label>Linkedin Marketing App Secret</label>
                                    <p>The Linkedin App Secret that will be used to <br>
                                        access your Linkedin marketing app.
                                    </p>
                                </div>
                                <div class="right_social_sec">
                                      <span>
                                        <input type="text" name="" value="" placeholder="Twitter Linkedin App Secret">
                                      </span>
                                </div>
                            </div>
                            <div class="social_text_inp_outer clearfix">
                                <div class="left_social_text_sec">
                                    <label>Linkedin App ID</label>
                                    <p>Optional Linkedin app ID that we can use to <br>
                                        identify your users from their Linkedin user ID.
                                    </p>
                                </div>
                                <div class="right_social_sec">
                                      <span>
                                        <input type="text" name="" value="" placeholder="Linkedin App ID">
                                      </span>
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
    <script src="{{asset('/assets/js/attributeData/companyStats.js')}}"></script>
@stop
