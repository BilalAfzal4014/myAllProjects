@extends('layouts.master')


@section('create')
    <link href="{{asset('/assets/css/inAppStyles.css')}}" rel="stylesheet" type="text/css">
    <style>
        .left_btns {

            width: 260px;
            display: inline-block;
            vertical-align: top;

        }

        .notice {
            width: 75.2%;
            padding: 3px 0;
            display: inline-block;
            border-radius: 6px;
            vertical-align: top;
            color: black;
            font-size: 11px;
            line-height: 16px;
            background: #dddddd;
        }

        .notice ul {
            overflow: hidden;
        }

        .notice img {
            float: left;
            width: 31px;
            margin: 0 5px;
        }

        .notice li:first-child span:last-child {
            text-indent: -5px;
        }

        .notice li {
            overflow: hidden;
        }

        .notice li span {
            float: left;
        }

        .notice li span:first-child {
            margin-right: 20px;
        }

        .bootstrap-tagsinput .tag {
            text-transform: none !important;
        }

        .bootstrap-tagsinput {
            margin-bottom: 10px !important;
        }

        .multiSelect {
            width: 100%;
            padding: 5px;
            border: 1px solid #6666;
            border-radius: 8px;
        }

        .modal-body {
            padding: 0px;
        }

        .myActive {
            width: fit-content;
            border: 1px solid #2a8689;
            padding: 10px 7px !important;
        }

        input:checked + .slider {
            background-color: #c0c0c0;
        }

        .switch {
            margin-left: 7px;
            margin-right: 7px;
        }

        tr th {
            text-align: center;
        }

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

        .crop_heading {
            display: inline-block;
            vertical-align: top;
            padding: 5px 14px 0 0;
        }

        .cropper_height_width {
            margin-top: -3px;
        }

        .cropper_height_width span {
            display: block;
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
                    <div style="margin-bottom: 10px; min-height: 57px" class="btn_header">
                        <div class="left_btns">
                            <input type="hidden" id="tokenForUploadImg" value="{{ csrf_token() }}"/>
                            <input id="galleryUpload" type="file">
                            <button id="galleryUploadBtn" class="btn"
                                    style="padding: 11px 12px; background: #2a8689; color: white">
                                <i class="fa fa-refresh fa-spin"></i>
                                Upload
                            </button>
                            <span id="uploadError" style="color: #F99;"></span>
                        </div>
                        <div class="notice">
                            <img src="{{asset('/assets/images/bulb_icon.png')}}">
                            <ul>
                                <li>
                                    <span>1 - Minimum optimum size for banner image is Width: 640px Height: 1136px</span>
                                    <span>2 - For full screen minimum preferred image size is Width: 600px Height: 800px </span>
                                </li>
                                <li>
                                    <span>3 - For dialog minimum preferred image size is Width: 100px Height: 100px</span>
                                    <span>4 - We allow .PNG, .GIF, .JPG and JPEG image file formats.</span>
                                </li>
                            </ul>
                        </div>
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

    <div class="modal fade" tabindex="-1" role="dialog" id="attributeDataModel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Attributes</h5>
                </div>
                <div class="modal-body">
                    <div style="position: relative; height: 35px; margin-bottom: 5px;">
                        <strong style="position: absolute; right: 1px;">
                            From Attribute
                            <label class="switch">
                                <input id="attributeSelection" type="checkbox">
                                <span class="slider round"></span>
                            </label>
                            From Attribute Data
                        </strong>
                    </div>
                    <div class="list_table_body scrollbar_content mCustomScrollbar _mCS_1">
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped" id="gallery">
                                <thead>
                                <tr>
                                    <th>Attribute Code</th>
                                    <th>Attribute Name</th>
                                    <th>Operations</th>
                                </tr>
                                </thead>
                                <tbody id="tableAttribute">
                                @foreach ($attributeData->attributes as $attribute)
                                    <tr>
                                        <td>{{$attribute->code}}</td>
                                        <td>{{$attribute->name}}</td>
                                        <td style="vertical-align: middle;">
                                            <button class="btn btn-info" style="background: #2a8689;"
                                                    onclick="selectAttribute('{{$attribute->code}}')">Select
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>

                                <tbody style="display: none" id="tableAttributeData">
                                @foreach ($attributeData->attributesData as $attribute)
                                    <tr>
                                        <td>{{$attribute->code}}</td>
                                        <td>{{$attribute->name}}</td>
                                        <td style="vertical-align: middle;">
                                            <button class="btn btn-info" style="background: #2a8689;"
                                                    onclick="selectAttribute('{{$attribute->code}}')">Select
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>

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

    <input class="companyId" type="hidden" value="{{$companyId}}">
    <input id="campaignAction" type="hidden" value="{{$action or ''}}">
    <input id="campaignEditionId" type="hidden" value="{{$id or ''}}">
    <input id="campaignCreationid" type="hidden" value="">
    <input id="campaignTypQuickAction" type="hidden" value="{{$CampaignType or ''}}">
    <div class="db_content_holder step-app">

        <div class="tp_BreadCrumb_list_sec clearfix">
            <label id="campaignTitleMain" class="sec_tp_title"> Campaign &gt; <span id="campaignTitleHeading"> Campaign Title </span></label>
        </div>

        <div class="breadcrumb_steps_outer">
            <ul class="step-steps">
                <li class="active"><a href="#step1"> 1. General </a></li>
                <li><a href="#step2"> 2. Compose </a></li>
                <li><a href="#step3"> 3. Delivery </a></li>
                <li><a href="#step4"> 4. Target Users</a></li>
                <li><a href="#step5"> 5. Conversions </a></li>
                <li><a href="#step6"> 6. Confirm </a></li>
            </ul>
        </div>

        <div class="breadcrumb_step_det_outer step-content no-padding">

            @include('campaign.step1')
            @include('campaign.step2')
            @include('campaign.step3')
            @include('campaign.step4')
            @include('campaign.step5')
            @include('campaign.step6')

            <div class="step-footer save_next_sec ">
                <button data-direction="prev" class="step-btn" id="back" style="display: none;">Back</button>
                <button data-direction="next" class="step-btn" id="next">Next</button>
                <button id="launchBtn" data-direction="launch_btn" class="step-btn launch_btn">Launch Campaign</button>
                <button id="backToListing" data-direction="launch_btn" class="step-btn launch_btn">Draft and Close
                </button>

                <div class="reachable_usr_app_message_otr hide">
                    <div class="reachable_user">
                        <p> Reachable Users <i class="noticification_icon"></i></p>
                        <span class="reachableUsers"></span>
                    </div>
                </div>

            </div>

            <div class="" style="padding:5px; background:#fff;">
            </div>

        </div>

    </div>
@stop

@section('jsSection')
    {{--<script src="{{asset('/assets/js/ckeditor.js')}}"></script>--}}
    <script src="//cdn.ckeditor.com/4.10.0/full/ckeditor.js"></script>
    <script>
        var campaignImg = '{{asset('assets/images/save_temp_img.png')}}';
        var attributeImg = '{{asset('assets/images/attribute_icon.png')}}';
        if ($("#campaignAction").val() == '') {
            $("body").attr("class", 'email_html-2');
            $(".db_content_holder").css({'display': 'block'});
            $(".db_content_listing_holder").css({'display': 'none'});
        }
    </script>
    <script src="{{asset('/assets/js/campaign/campaignCrud.js')}}"></script>

@stop
