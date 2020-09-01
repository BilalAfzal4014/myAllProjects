@extends('layouts.master')



@section('content')
    <style>
        input {

            color: #0c0c0c !important;
        }

        .error {
            color: #F99
        }
    </style>
    <div class="edit_user">

        {{--<div class="tabClass usr_prof_tab_btns clearfix">--}}
        {{--<ul class="tab-view">--}}
        {{--<li><a href="tab1" class="active"> General </a></li>--}}
        {{--<li><a href="tab2"> Notification </a></li>--}}
        {{--<li><a href="tab3"> Email </a></li>--}}

        {{--</ul>--}}
        {{--</div>--}}
        <input type="hidden" name="basicFormSubmit" id="basicFormSubmit" value="@if($appObj) 1 @else 0 @endif">
        <div class="tab1 edit_user_form_sec">

            <div class="edit_user_auto clearfix">
                <form class="edit-user-form clearfix" id="firstTab" action="{{route('app.submit')}}" method="post"
                      enctype="multipart/form-data">

                    <input type="hidden" value="@if($appObj){{$appObj->id}} @endif" id="appId" name="id">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <label for="">
                        <strong> App Name: </strong>
                        <input id="appName" name="appName" required value="@if($appObj){{$appObj->name}}@endif"
                               placeholder="" type="text">
                        <span id="appNameError" style="color: #F99;"></span>
                    </label>
                    <label for="">
                        <strong> App logo: </strong>
                        <div style="width: 100%;">
                            <input id="appLogo" name="appLogo" @if($appObj and !$appObj->logo)required @endif value=""
                                   type="file">

                            <img style="width: 150px;height: 150px;" id="appLogoPreview"
                                 src="@if($appObj and $appObj->logo){{ \Illuminate\Support\Facades\Storage::disk('s3')->url($appObj->logo) }}@else{{ asset('assets/images/ureka_logo2.png')  }}@endif">

                        </div>
                        <span id="appLogoError" style="color: #F99;"></span>
                    </label>
                    <label for="">
                        <strong> App ID: </strong>
                        <input id="appID" name="appID" required value="@if($appObj){{$appObj->app_id}}@endif"
                               placeholder="" type="text">
                        <span id="appEmailError" style="color: #F99;"></span>
                        <span id="appEmailDupError" style="color: #F99;"></span>
                    </label>
                    <label for="">
                        <strong> Description: </strong>
                        <textarea id="inappMessage" name="description" class="b_r " required rows="5"
                                  style="width: 100%;padding: 10px;"
                                  maxlength="150">@if($appObj){{$appObj->description}}@endif</textarea>
                        <span id="appPhoneError" style="color: #F99;"></span>
                    </label>
                    <label for="">
                        <strong> Platform: </strong>
                        <div class="Campaigns_type_sec inp_select  b_r">
                            <select name="platform" id="platform" required="">
                                <option value="">Please Select a value</option>
                                <option @if($appObj and $appObj->platform =='IOS') selected @endif  value="IOS">IOS
                                </option>
                                <option @if($appObj and $appObj->platform =='ANDROID') selected @endif value="ANDROID">
                                    ANDROID
                                </option>
                                <option @if($appObj and $appObj->platform =='WEB') selected @endif value="WEB">WEB
                                </option>
                            </select>
                        </div>
                        <label id="platform-error" class="error" for="platform"></label>
                    </label>
                    <fieldset id="ios_section" @if($appObj and $appObj->platform =='IOS')style="display: block"
                              @else style="display: none"@endif>
                        <legend>Push:</legend>
                        {{--<label for="">
                            <strong> IOS PassPhrase: </strong>
                            <input type="text" id="Passphrase" name="Passphrase" value="@if($appObj){{$appObj->ios_passphrase}}@endif" placeholder="">
                            <span id="PassphraseError" style="color: #F99;"></span>
                        </label>--}}
                        <label for="">
                            <strong> Upload IOS production certificate files:</strong>
                            <input type="file" id="companyFile1" name="companyFile1" value=""
                                   placeholder="IOS production certificate files">
                            <label>@if($appObj){{$appObj->ios_cert_live}}@endif</label>
                            <span id="companyFile1Error" style="color: #F99;"></span>
                        </label>
                        <label for="">
                            <strong> Upload IOS development certificate file: </strong>
                            <input type="file" id="companyFile2" name="companyFile2" value="" placeholder="">
                            <label>@if($appObj){{$appObj->ios_cert_dev}}@endif</label>
                            <span id="companyFile2Error" style="color: #F99;"></span>
                        </label>

                        <div class="sandbox_label">
                            <label for="">
                                <strong> Sandbox Enable: </strong>
                            </label>
                            <label class="switch">
                                <input name="is_sandbox" type="checkbox"
                                       @if($appObj && $appObj->is_sandbox) checked @endif >
                                <span class="slider round"></span>
                            </label>
                        </div>
                    </fieldset>
                    <fieldset>
                        <legend>In App:</legend>
                        <label for="">
                            <strong> FireBase key: </strong>
                            <input type="text" id="fireBaseKey" name="fireBaseKey" required
                                   value="@if($appObj){{$appObj->firebase_server_api_key}}@endif" placeholder="">
                            <span id="fireBaseKeyError" style="color: #F99;"></span>
                        </label>

                        <div class="sandbox_label">
                            <label for="">
                                <strong> Active: </strong>
                            </label>
                            <label class="switch">
                                <input name="is_active" type="checkbox" @if(!$appObj) checked
                                       @endif  @if($appObj && $appObj->is_active) checked @endif >
                                <span class="slider round"></span>
                            </label>
                        </div>
                    </fieldset>
                    <label for="">
                        <button class="sub_btn" type="button" data-action-for="tab1" name="button">Save</button>
                    </label>
                </form>
            </div>

        </div>
    </div>
@stop



@section('jsSection')
    <script src="{{asset('/assets/js/app/appCrud.js')}}"></script>

@stop