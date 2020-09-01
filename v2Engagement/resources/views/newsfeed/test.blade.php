@extends('layouts.main')
@section('content')
    <div class="tp_BreadCrumb_list_sec clearfix">
        <label class="sec_tp_title"> News Feed </label>
        <div class="track_userdeta_right clearfix">
            <div class="tp_BreadCrumb_srch_inp">
                <input type="search" name="" value="" placeholder="Search...">
            </div>
            <div class="uder_deta_dropdown">
                <div class=" inp_select">
                    <select id="campaigns_type2">
                        <option value=""> Create Card</option>
                        <option value=""> Create Card-2</option>
                    </select>
                </div>
            </div>
        </div>
    </div>

    <!--  -->
    <div class="listing_sec_outer clearfix">
        <div class="db_list_left_sec ">
            <div class="db_list_left_tp">
                <label> Group by: </label>
                <div class=" inp_select  b_r">
                    <select id="create_by">
                        <option value="app_message"> Created By</option>

                    </select>
                </div>
            </div>
            <div class="db_list_left_btm scrollbar_content mCustomScrollbar _mCS_1">
                <label>Show:</label>
                <ul>

                    <li><a href="#"> All </a></li>

                    <li>
                        <div class="db_list_left_sublist">
                            <h3>All Active</h3>
                            <ul>
                                <li><a href="#">All Active 1</a></li>
                                <li><a href="#">All Active 2</a></li>
                                <li><a href="#">All Active 3</a></li>
                                <li><a href="#">All Active 4</a></li>
                                <li><a href="#">All Active 5</a></li>
                            </ul>
                        </div>
                    </li>
                    <li>
                        <div class="db_list_left_sublist">
                            <h3>In Active</h3>
                            <ul>
                                <li><a href="#">In Active 1</a></li>
                                <li><a href="#">In Active 2</a></li>
                                <li><a href="#">In Active 3</a></li>
                                <li><a href="#">In Active 4</a></li>
                                <li><a href="#">In Active 5</a></li>
                            </ul>
                        </div>
                    </li>
                    <li>
                        <div class="db_list_left_sublist">
                            <h3>Region</h3>
                            <ul>
                                <li><a href="#">Region 1</a></li>
                                <li><a href="#">Region 2</a></li>
                                <li><a href="#">Region 3</a></li>
                                <li><a href="#">Region 4</a></li>
                                <li><a href="#">Region 5</a></li>
                            </ul>
                        </div>
                    </li>
                    <li>
                        <div class="db_list_left_sublist">
                            <a href="#">Last-Feed</a>
                            <h3>Type</h3>
                            <ul>
                                <li><a href="#">Type-1</a></li>
                                <li><a href="#">Type-2</a></li>
                                <li><a href="#">Type-3</a></li>
                                <li><a href="#">Type-4</a></li>
                                <li><a href="#">Type-5</a></li>
                            </ul>
                        </div>
                    </li>


                </ul>
                <label>Tags:</label>
                <ul>
                    <li><a href="#"> 2018 Creative AB… </a></li>
                    <li><a href="#"> 2018 Language AB… </a></li>
                    <li><a href="#"> 2018 CTA AB Test… </a></li>
                    <li><a href="#"> 2018 CTA AB Test… </a></li>
                    <li><a href="#"> 2018 Language AB… </a></li>
                    <li><a href="#"> 2018 OR AB Test… </a></li>
                    <li><a href="#"> 2017 Creative AB… </a></li>
                    <li><a href="#"> 2018 Language AB… </a></li>
                    <li><a href="#"> 2018 OR AB Test… </a></li>
                </ul>
            </div>
        </div>
        <div class="db_list_right_sec">
            <div class="list_table_header">
                <table cellspacing="0" cellpadding="0">
                    <tr>
                        <th style="width:34%;">Newsfeed Name</th>
                        <th style="width:15%;">Tempalate</th>
                        <th style="width:10%;">Impressions (Unique)</th>
                        <th style="width:10%;">Start Date</th>
                        <th style="width:10%;">End Date</th>
                        <th style="width:15%;">Status</th>
                        <th style="width:6%;"></th>
                    </tr>
                </table>
            </div>


            <div class="list_table_body scrollbar_content mCustomScrollbar _mCS_1">
                <table cellspacing="0" cellpadding="0" padding-right:10px;>
                    @foreach($newsLists as $newslist)
                        <tr>
                            <td class="nf_seg_name" style="width:34%;">

                                <a href="#">
                                    <img src="{{ $newslist->image_url }}" alt="#" style="width: 100px;">
                                    {{$newslist->name}}
                                </a>


                                <div class="nf_seg_name_detail ">
                                    <table>
                                        <tr>
                                            <td style="width:60%;">Target Users</td>
                                            <td>--</td>
                                        </tr>
                                        <tr>
                                            <td style="width:60%;">Segment</td>
                                            <td></td>
                                        </tr>
                                        <tr>
                                            <td style="width:60%;">Clicks(Unique)</td>
                                            <td>0(0)</td>
                                        </tr>
                                        <tr>
                                            <td style="width:60%;">Impressions(Unique)</td>
                                            <td>0(0)</td>
                                        </tr>
                                        <tr>
                                            <td style="width:60%;">Clickthrough Rate(Unique)</td>
                                            <td>0.00% (0.00%)</td>
                                        </tr>
                                    </table>
                                </div>
                            </td>
                            <td style="width:15%;">{{ $newslist->name }}</td>
                            <td style="width:10%;">0 (0)</td>
                            <td class="list_time_date" style="width:10%;">{{ $newslist->name }}
                                <b>{{ $newslist->name }} </b></td>
                            <td class="list_time_date" style="width:10%;">{{ $newslist->name }}
                                <b>{{ $newslist->name }} </b></td>
                            <td style="width:15%;">@if($newslist->name == 1)
                                    Active
                                @else
                                    Draft
                                @endif
                            </td>
                            <td style="width:6%;">
                                <div class="lst_tbl_drop_outer">
                                    <span class=""> <img src="images/sett_icon.png" alt="#"> </span>
                                    <ul>
                                        <li><a href="#"> <img src="images/edit_icon.png" alt=""> Edit </a></li>
                                        <li><a href="#"> <img src="images/view_icon.png" alt=""> View </a></li>
                                    </ul>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                    <tr>
                        <td class="nf_seg_name" style="width:34%;">
                            <a href="#"> <img src="images/nf_list_img2.png" alt="#"> Leaderboard Testing </a>
                            <div class="nf_seg_name_detail">
                                <table>
                                    <tr>
                                        <td style="width:60%;">Target Users</td>
                                        <td>--</td>
                                    </tr>
                                    <tr>
                                        <td style="width:60%;">Segment</td>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <td style="width:60%;">Clicks(Unique)</td>
                                        <td>0(0)</td>
                                    </tr>
                                    <tr>
                                        <td style="width:60%;">Impressions(Unique)</td>
                                        <td>0(0)</td>
                                    </tr>
                                    <tr>
                                        <td style="width:60%;">Clickthrough Rate(Unique)</td>
                                        <td>0.00% (0.00%)</td>
                                    </tr>
                                </table>
                            </div>
                        </td>
                        <td style="width:15%;"> Captioned image</td>
                        <td style="width:10%;">0 (0)</td>
                        <td class="list_time_date" style="width:10%;"> 12-04-2018 <b>12:00 PM </b></td>
                        <td class="list_time_date" style="width:10%;"> 19-04-2018 <b>11:55 PM </b></td>
                        <td style="width:15%;"> Edward Tucker</td>
                        <td style="width:6%;">
                            <div class="lst_tbl_drop_outer">
                                <span class=""> <img src="images/sett_icon.png" alt="#"> </span>
                                <ul>
                                    <li><a href="#"> <img src="images/edit_icon.png" alt=""> Edit </a></li>
                                    <li><a href="#"> <img src="images/view_icon.png" alt=""> View </a></li>
                                </ul>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td class="nf_seg_name" style="width:34%;">
                            <a href="#"> <img src="images/nf_list_img3.png" alt="#"> Harmony Day 21 March </a>
                            <div class="nf_seg_name_detail">
                                <table>
                                    <tr>
                                        <td style="width:60%;">Target Users</td>
                                        <td>--</td>
                                    </tr>
                                    <tr>
                                        <td style="width:60%;">Segment</td>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <td style="width:60%;">Clicks(Unique)</td>
                                        <td>0(0)</td>
                                    </tr>
                                    <tr>
                                        <td style="width:60%;">Impressions(Unique)</td>
                                        <td>0(0)</td>
                                    </tr>
                                    <tr>
                                        <td style="width:60%;">Clickthrough Rate(Unique)</td>
                                        <td>0.00% (0.00%)</td>
                                    </tr>
                                </table>
                            </div>
                        </td>
                        <td style="width:15%;"> Captioned image</td>
                        <td style="width:10%;">0 (0)</td>
                        <td class="list_time_date" style="width:10%;"> 04-03-2018 <b>12:00 PM </b></td>
                        <td class="list_time_date" style="width:10%;"> 06-03-2018 <b>11:55 PM </b></td>
                        <td style="width:15%;"> Ash</td>
                        <td style="width:6%;">
                            <div class="lst_tbl_drop_outer">
                                <span class=""> <img src="images/sett_icon.png" alt="#"> </span>
                                <ul>
                                    <li><a href="#"> <img src="images/edit_icon.png" alt=""> Edit </a></li>
                                    <li><a href="#"> <img src="images/view_icon.png" alt=""> View </a></li>
                                </ul>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td class="nf_seg_name" style="width:34%;">
                            <a href="#"> <img src="images/nf_list_img4.png" alt="#"> True To Your Mission </a>
                            <div class="nf_seg_name_detail">
                                <table>
                                    <tr>
                                        <td style="width:60%;">Target Users</td>
                                        <td>--</td>
                                    </tr>
                                    <tr>
                                        <td style="width:60%;">Segment</td>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <td style="width:60%;">Clicks(Unique)</td>
                                        <td>0(0)</td>
                                    </tr>
                                    <tr>
                                        <td style="width:60%;">Impressions(Unique)</td>
                                        <td>0(0)</td>
                                    </tr>
                                    <tr>
                                        <td style="width:60%;">Clickthrough Rate(Unique)</td>
                                        <td>0.00% (0.00%)</td>
                                    </tr>
                                </table>
                            </div>
                        </td>
                        <td style="width:15%;"> Captioned image</td>
                        <td style="width:10%;">0 (0)</td>
                        <td class="list_time_date" style="width:10%;"> 02-02-2018 <b>12:00 PM </b></td>
                        <td class="list_time_date" style="width:10%;"> 04-02-2018 <b>11:55 PM </b></td>
                        <td style="width:15%;"> Ash</td>
                        <td style="width:6%;">
                            <div class="lst_tbl_drop_outer">
                                <span class=""> <img src="images/sett_icon.png" alt="#"> </span>
                                <ul>
                                    <li><a href="#"> <img src="images/edit_icon.png" alt=""> Edit </a></li>
                                    <li><a href="#"> <img src="images/view_icon.png" alt=""> View </a></li>
                                </ul>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td class="nf_seg_name" style="width:34%;">
                            <a href="#"> <img src="images/nf_list_img1.png" alt="#"> Grand Opening </a>
                            <div class="nf_seg_name_detail">
                                <table>
                                    <tr>
                                        <td style="width:60%;">Target Users</td>
                                        <td>--</td>
                                    </tr>
                                    <tr>
                                        <td style="width:60%;">Segment</td>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <td style="width:60%;">Clicks(Unique)</td>
                                        <td>0(0)</td>
                                    </tr>
                                    <tr>
                                        <td style="width:60%;">Impressions(Unique)</td>
                                        <td>0(0)</td>
                                    </tr>
                                    <tr>
                                        <td style="width:60%;">Clickthrough Rate(Unique)</td>
                                        <td>0.00% (0.00%)</td>
                                    </tr>
                                </table>
                            </div>
                        </td>

                        <td style="width:15%;"> Captioned image</td>
                        <td style="width:10%;">0 (0)</td>
                        <td class="list_time_date" style="width:10%;"> 28-12-2017 <b>12:00 PM </b></td>
                        <td class="list_time_date" style="width:10%;"> 02-01-2018 <b>11:55 PM </b></td>
                        <td style="width:15%;"> Ash</td>
                        <td style="width:6%;">
                            <div class="lst_tbl_drop_outer">
                                <span class=""> <img src="images/sett_icon.png" alt="#"> </span>
                                <ul>
                                    <li><a href="#"> <img src="images/edit_icon.png" alt=""> Edit </a></li>
                                    <li><a href="#"> <img src="images/view_icon.png" alt=""> View </a></li>
                                </ul>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td class="nf_seg_name" style="width:34%;">
                            <a href="#"> <img src="images/nf_list_img6.png" alt="#"> Summer Jazz Festival </a>
                            <div class="nf_seg_name_detail">
                                <table>
                                    <tr>
                                        <td style="width:60%;">Target Users</td>
                                        <td>--</td>
                                    </tr>
                                    <tr>
                                        <td style="width:60%;">Segment</td>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <td style="width:60%;">Clicks(Unique)</td>
                                        <td>0(0)</td>
                                    </tr>
                                    <tr>
                                        <td style="width:60%;">Impressions(Unique)</td>
                                        <td>0(0)</td>
                                    </tr>
                                    <tr>
                                        <td style="width:60%;">Clickthrough Rate(Unique)</td>
                                        <td>0.00% (0.00%)</td>
                                    </tr>
                                </table>
                            </div>
                        </td>
                        <td style="width:15%;"> Captioned image</td>
                        <td style="width:10%;">0 (0)</td>
                        <td class="list_time_date" style="width:10%;"> 28-11-2017 <b>12:00 PM </b></td>
                        <td class="list_time_date" style="width:10%;"> 04-12-2017 <b>11:55 PM </b></td>
                        <td style="width:15%;"> Ash</td>
                        <td style="width:6%;">
                            <div class="lst_tbl_drop_outer">
                                <span class=""> <img src="images/sett_icon.png" alt="#"> </span>
                                <ul>
                                    <li><a href="#"> <img src="images/edit_icon.png" alt=""> Edit </a></li>
                                    <li><a href="#"> <img src="images/view_icon.png" alt=""> View </a></li>
                                </ul>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td class="nf_seg_name" style="width:34%;">
                            <a href="#"> <img src="images/nf_list_img7.png" alt="#">Yearly Membership Word Press </a>
                            <div class="nf_seg_name_detail">
                                <table>
                                    <tr>
                                        <td style="width:60%;">Target Users</td>
                                        <td>--</td>
                                    </tr>
                                    <tr>
                                        <td style="width:60%;">Segment</td>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <td style="width:60%;">Clicks(Unique)</td>
                                        <td>0(0)</td>
                                    </tr>
                                    <tr>
                                        <td style="width:60%;">Impressions(Unique)</td>
                                        <td>0(0)</td>
                                    </tr>
                                    <tr>
                                        <td style="width:60%;">Clickthrough Rate(Unique)</td>
                                        <td>0.00% (0.00%)</td>
                                    </tr>
                                </table>
                            </div>
                        </td>
                        <td style="width:15%;"> Captioned image</td>
                        <td style="width:10%;">0 (0)</td>
                        <td class="list_time_date" style="width:10%;"> 26-10-2017 <b>12:00 PM </b></td>
                        <td class="list_time_date" style="width:10%;"> 02-11-2017 <b>11:55 PM </b></td>
                        <td style="width:15%;"> Ash</td>
                        <td style="width:6%;">
                            <div class="lst_tbl_drop_outer">
                                <span class=""> <img src="images/sett_icon.png" alt="#"> </span>
                                <ul>
                                    <li><a href="#"> <img src="images/edit_icon.png" alt=""> Edit </a></li>
                                    <li><a href="#"> <img src="images/view_icon.png" alt=""> View </a></li>
                                </ul>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td class="nf_seg_name" style="width:34%;">
                            <a href="#"> <img src="images/nf_list_img8.png" alt="#">Summer Parenting Community </a>
                            <div class="nf_seg_name_detail">
                                <table>
                                    <tr>
                                        <td style="width:60%;">Target Users</td>
                                        <td>--</td>
                                    </tr>
                                    <tr>
                                        <td style="width:60%;">Segment</td>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <td style="width:60%;">Clicks(Unique)</td>
                                        <td>0(0)</td>
                                    </tr>
                                    <tr>
                                        <td style="width:60%;">Impressions(Unique)</td>
                                        <td>0(0)</td>
                                    </tr>
                                    <tr>
                                        <td style="width:60%;">Clickthrough Rate(Unique)</td>
                                        <td>0.00% (0.00%)</td>
                                    </tr>
                                </table>
                            </div>
                        </td>
                        <td style="width:15%;"> Captioned image</td>
                        <td style="width:10%;">0 (0)</td>
                        <td class="list_time_date" style="width:10%;"> 20-09-2017 <b>12:00 PM </b></td>
                        <td class="list_time_date" style="width:10%;"> 11-05-2018 <b>11:55 PM </b></td>
                        <td style="width:15%;"> Ash</td>
                        <td style="width:6%;">
                            <div class="lst_tbl_drop_outer">
                                <span class=""> <img src="images/sett_icon.png" alt="#"> </span>
                                <ul>
                                    <li><a href="#"> <img src="images/edit_icon.png" alt=""> Edit </a></li>
                                    <li><a href="#"> <img src="images/view_icon.png" alt=""> View </a></li>
                                </ul>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td class="nf_seg_name" style="width:34%;">
                            <a href="#"> <img src="images/nf_list_img9.png" alt="#"> Free Shipping </a>
                            <div class="nf_seg_name_detail">
                                <table>
                                    <tr>
                                        <td style="width:60%;">Target Users</td>
                                        <td>--</td>
                                    </tr>
                                    <tr>
                                        <td style="width:60%;">Segment</td>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <td style="width:60%;">Clicks(Unique)</td>
                                        <td>0(0)</td>
                                    </tr>
                                    <tr>
                                        <td style="width:60%;">Impressions(Unique)</td>
                                        <td>0(0)</td>
                                    </tr>
                                    <tr>
                                        <td style="width:60%;">Clickthrough Rate(Unique)</td>
                                        <td>0.00% (0.00%)</td>
                                    </tr>
                                </table>
                            </div>
                        </td>
                        <td style="width:15%;"> Captioned image</td>
                        <td style="width:10%;">0 (0)</td>
                        <td class="list_time_date" style="width:10%;"> 14-09-2017 <b>12:00 PM </b></td>
                        <td class="list_time_date" style="width:10%;"> 24-09-2017 <b>11:55 PM </b></td>
                        <td style="width:15%;"> Ash</td>
                        <td style="width:6%;">
                            <div class="lst_tbl_drop_outer">
                                <span class=""> <img src="images/sett_icon.png" alt="#"> </span>
                                <ul>
                                    <li><a href="#"> <img src="images/edit_icon.png" alt=""> Edit </a></li>
                                    <li><a href="#"> <img src="images/view_icon.png" alt=""> View </a></li>
                                </ul>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td class="nf_seg_name" style="width:34%;">
                            <a href="#"> <img src="images/nf_list_img1.png" alt="#"> NFC_Members_Ad Sale </a>
                            <div class="nf_seg_name_detail">
                                <table>
                                    <tr>
                                        <td style="width:60%;">Target Users</td>
                                        <td>--</td>
                                    </tr>
                                    <tr>
                                        <td style="width:60%;">Segment</td>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <td style="width:60%;">Clicks(Unique)</td>
                                        <td>0(0)</td>
                                    </tr>
                                    <tr>
                                        <td style="width:60%;">Impressions(Unique)</td>
                                        <td>0(0)</td>
                                    </tr>
                                    <tr>
                                        <td style="width:60%;">Clickthrough Rate(Unique)</td>
                                        <td>0.00% (0.00%)</td>
                                    </tr>
                                </table>
                            </div>
                        </td>
                        <td style="width:15%;"> Captioned image</td>
                        <td style="width:10%;">0 (0)</td>
                        <td class="list_time_date" style="width:10%;"> 09-05-2018 <b>12:00 PM </b></td>
                        <td class="list_time_date" style="width:10%;"> 11-05-2018 <b>11:55 PM </b></td>
                        <td style="width:15%;"> Jawad Ashraf</td>
                        <td style="width:6%;">
                            <div class="lst_tbl_drop_outer">
                                <span class=""> <img src="images/sett_icon.png" alt="#"> </span>
                                <ul>
                                    <li><a href="#"> <img src="images/edit_icon.png" alt=""> Edit </a></li>
                                    <li><a href="#"> <img src="images/view_icon.png" alt=""> View </a></li>
                                </ul>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td class="nf_seg_name" style="width:34%;">
                            <a href="#"> <img src="images/nf_list_img2.png" alt="#"> Leaderboard Testing </a>
                            <div class="nf_seg_name_detail">
                                <table>
                                    <tr>
                                        <td style="width:60%;">Target Users</td>
                                        <td>--</td>
                                    </tr>
                                    <tr>
                                        <td style="width:60%;">Segment</td>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <td style="width:60%;">Clicks(Unique)</td>
                                        <td>0(0)</td>
                                    </tr>
                                    <tr>
                                        <td style="width:60%;">Impressions(Unique)</td>
                                        <td>0(0)</td>
                                    </tr>
                                    <tr>
                                        <td style="width:60%;">Clickthrough Rate(Unique)</td>
                                        <td>0.00% (0.00%)</td>
                                    </tr>
                                </table>
                            </div>
                        </td>
                        <td style="width:15%;"> Captioned image</td>
                        <td style="width:10%;">0 (0)</td>
                        <td class="list_time_date" style="width:10%;"> 12-04-2018 <b>12:00 PM </b></td>
                        <td class="list_time_date" style="width:10%;"> 19-04-2018 <b>11:55 PM </b></td>
                        <td style="width:15%;"> Edward Tucker</td>
                        <td style="width:6%;">
                            <div class="lst_tbl_drop_outer">
                                <span class=""> <img src="images/sett_icon.png" alt="#"> </span>
                                <ul>
                                    <li><a href="#"> <img src="images/edit_icon.png" alt=""> Edit </a></li>
                                    <li><a href="#"> <img src="images/view_icon.png" alt=""> View </a></li>
                                </ul>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td class="nf_seg_name" style="width:34%;">
                            <a href="#"> <img src="images/nf_list_img3.png" alt="#"> Harmony Day 21 March </a>
                            <div class="nf_seg_name_detail">
                                <table>
                                    <tr>
                                        <td style="width:60%;">Target Users</td>
                                        <td>--</td>
                                    </tr>
                                    <tr>
                                        <td style="width:60%;">Segment</td>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <td style="width:60%;">Clicks(Unique)</td>
                                        <td>0(0)</td>
                                    </tr>
                                    <tr>
                                        <td style="width:60%;">Impressions(Unique)</td>
                                        <td>0(0)</td>
                                    </tr>
                                    <tr>
                                        <td style="width:60%;">Clickthrough Rate(Unique)</td>
                                        <td>0.00% (0.00%)</td>
                                    </tr>
                                </table>
                            </div>

                        </td>
                        <td style="width:15%;"> Captioned image</td>
                        <td style="width:10%;">0 (0)</td>
                        <td class="list_time_date" style="width:10%;"> 04-03-2018 <b>12:00 PM </b></td>
                        <td class="list_time_date" style="width:10%;"> 06-03-2018 <b>11:55 PM </b></td>
                        <td style="width:15%;"> Ash</td>
                        <td style="width:6%;">
                            <div class="lst_tbl_drop_outer">
                                <span class=""> <img src="images/sett_icon.png" alt="#"> </span>
                                <ul>
                                    <li><a href="#"> <img src="images/edit_icon.png" alt=""> Edit </a></li>
                                    <li><a href="#"> <img src="images/view_icon.png" alt=""> View </a></li>
                                </ul>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td class="nf_seg_name" style="width:34%;">
                            <a href="#" class="nf_seg_name"> <img src="images/nf_list_img4.png" alt="#"> True To Your
                                Mission </a>
                            <div class="nf_seg_name_detail">
                                <table>
                                    <tr>
                                        <td style="width:60%;">Target Users</td>
                                        <td>--</td>
                                    </tr>
                                    <tr>
                                        <td style="width:60%;">Segment</td>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <td style="width:60%;">Clicks(Unique)</td>
                                        <td>0(0)</td>
                                    </tr>
                                    <tr>
                                        <td style="width:60%;">Impressions(Unique)</td>
                                        <td>0(0)</td>
                                    </tr>
                                    <tr>
                                        <td style="width:60%;">Clickthrough Rate(Unique)</td>
                                        <td>0.00% (0.00%)</td>
                                    </tr>
                                </table>
                            </div>
                        </td>
                        <td style="width:15%;"> Captioned image</td>
                        <td style="width:10%;">0 (0)</td>
                        <td class="list_time_date" style="width:10%;"> 02-02-2018 <b>12:00 PM </b></td>
                        <td class="list_time_date" style="width:10%;"> 04-02-2018 <b>11:55 PM </b></td>
                        <td style="width:15%;"> Ash</td>
                        <td style="width:6%;">
                            <div class="lst_tbl_drop_outer">
                                <span class=""> <img src="images/sett_icon.png" alt="#"> </span>
                                <ul>
                                    <li><a href="#"> <img src="images/edit_icon.png" alt=""> Edit </a></li>
                                    <li><a href="#"> <img src="images/view_icon.png" alt=""> View </a></li>
                                </ul>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td class="nf_seg_name" style="width:34%;">
                            <a href="#"> <img src="images/nf_list_img1.png" alt="#"> Grand Opening </a>
                            <div class="nf_seg_name_detail">
                                <table>
                                    <tr>
                                        <td style="width:60%;">Target Users</td>
                                        <td>--</td>
                                    </tr>
                                    <tr>
                                        <td style="width:60%;">Segment</td>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <td style="width:60%;">Clicks(Unique)</td>
                                        <td>0(0)</td>
                                    </tr>
                                    <tr>
                                        <td style="width:60%;">Impressions(Unique)</td>
                                        <td>0(0)</td>
                                    </tr>
                                    <tr>
                                        <td style="width:60%;">Clickthrough Rate(Unique)</td>
                                        <td>0.00% (0.00%)</td>
                                    </tr>
                                </table>
                            </div>
                        </td>
                        <td style="width:15%;"> Captioned image</td>
                        <td style="width:10%;">0 (0)</td>
                        <td class="list_time_date" style="width:10%;"> 28-12-2017 <b>12:00 PM </b></td>
                        <td class="list_time_date" style="width:10%;"> 02-01-2018 <b>11:55 PM </b></td>
                        <td style="width:15%;"> Ash</td>
                        <td style="width:6%;">
                            <div class="lst_tbl_drop_outer">
                                <span class=""> <img src="images/sett_icon.png" alt="#"> </span>
                                <ul>
                                    <li><a href="#"> <img src="images/edit_icon.png" alt=""> Edit </a></li>
                                    <li><a href="#"> <img src="images/view_icon.png" alt=""> View </a></li>
                                </ul>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td class="nf_seg_name" style="width:34%;">
                            <a href="#"> <img src="images/nf_list_img6.png" alt="#"> Summer Jazz Festival </a>
                            <div class="nf_seg_name_detail">
                                <table>
                                    <tr>
                                        <td style="width:60%;">Target Users</td>
                                        <td>--</td>
                                    </tr>
                                    <tr>
                                        <td style="width:60%;">Segment</td>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <td style="width:60%;">Clicks(Unique)</td>
                                        <td>0(0)</td>
                                    </tr>
                                    <tr>
                                        <td style="width:60%;">Impressions(Unique)</td>
                                        <td>0(0)</td>
                                    </tr>
                                    <tr>
                                        <td style="width:60%;">Clickthrough Rate(Unique)</td>
                                        <td>0.00% (0.00%)</td>
                                    </tr>
                                </table>
                            </div>
                        </td>
                        <td style="width:15%;"> Captioned image</td>
                        <td style="width:10%;">0 (0)</td>
                        <td class="list_time_date" style="width:10%;"> 28-11-2017 <b>12:00 PM </b></td>
                        <td class="list_time_date" style="width:10%;"> 04-12-2017 <b>11:55 PM </b></td>
                        <td style="width:15%;"> Ash</td>
                        <td style="width:6%;">
                            <div class="lst_tbl_drop_outer">
                                <span class=""> <img src="images/sett_icon.png" alt="#"> </span>
                                <ul>
                                    <li><a href="#"> <img src="images/edit_icon.png" alt=""> Edit </a></li>
                                    <li><a href="#"> <img src="images/view_icon.png" alt=""> View </a></li>
                                </ul>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td class="nf_seg_name" style="width:34%;">
                            <a href="#"> <img src="images/nf_list_img7.png" alt="#">Yearly Membership Word Press </a>
                            <div class="nf_seg_name_detail">
                                <table>
                                    <tr>
                                        <td style="width:60%;">Target Users</td>
                                        <td>--</td>
                                    </tr>
                                    <tr>
                                        <td style="width:60%;">Segment</td>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <td style="width:60%;">Clicks(Unique)</td>
                                        <td>0(0)</td>
                                    </tr>
                                    <tr>
                                        <td style="width:60%;">Impressions(Unique)</td>
                                        <td>0(0)</td>
                                    </tr>
                                    <tr>
                                        <td style="width:60%;">Clickthrough Rate(Unique)</td>
                                        <td>0.00% (0.00%)</td>
                                    </tr>
                                </table>
                            </div>
                        </td>
                        <td style="width:15%;"> Captioned image</td>
                        <td style="width:10%;">0 (0)</td>
                        <td class="list_time_date" style="width:10%;"> 26-10-2017 <b>12:00 PM </b></td>
                        <td class="list_time_date" style="width:10%;"> 02-11-2017 <b>11:55 PM </b></td>
                        <td style="width:15%;"> Ash</td>
                        <td style="width:6%;">
                            <div class="lst_tbl_drop_outer">
                                <span class=""> <img src="images/sett_icon.png" alt="#"> </span>
                                <ul>
                                    <li><a href="#"> <img src="images/edit_icon.png" alt=""> Edit </a></li>
                                    <li><a href="#"> <img src="images/view_icon.png" alt=""> View </a></li>
                                </ul>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td class="nf_seg_name" style="width:34%;">
                            <a href="#"> <img src="images/nf_list_img7.png" alt="#">Yearly Membership Word Press </a>
                            <div class="nf_seg_name_detail">
                                <table>
                                    <tr>
                                        <td style="width:60%;">Target Users</td>
                                        <td>--</td>
                                    </tr>
                                    <tr>
                                        <td style="width:60%;">Segment</td>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <td style="width:60%;">Clicks(Unique)</td>
                                        <td>0(0)</td>
                                    </tr>
                                    <tr>
                                        <td style="width:60%;">Impressions(Unique)</td>
                                        <td>0(0)</td>
                                    </tr>
                                    <tr>
                                        <td style="width:60%;">Clickthrough Rate(Unique)</td>
                                        <td>0.00% (0.00%)</td>
                                    </tr>
                                </table>
                            </div>
                        </td>
                        <td style="width:15%;"> Captioned image</td>
                        <td style="width:10%;">0 (0)</td>
                        <td class="list_time_date" style="width:10%;"> 26-10-2017 <b>12:00 PM </b></td>
                        <td class="list_time_date" style="width:10%;"> 02-11-2017 <b>11:55 PM </b></td>
                        <td style="width:15%;"> Ash</td>
                        <td style="width:6%;">
                            <div class="lst_tbl_drop_outer">
                                <span class=""> <img src="images/sett_icon.png" alt="#"> </span>
                                <ul>
                                    <li><a href="#"> <img src="images/edit_icon.png" alt=""> Edit </a></li>
                                    <li><a href="#"> <img src="images/view_icon.png" alt=""> View </a></li>
                                </ul>
                            </div>
                        </td>
                    </tr>


                </table>
            </div>


        </div>
    </div>
@stop