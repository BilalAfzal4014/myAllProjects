@extends('layouts.master')

@section('title', '| Edit User')
@section('content')
    <style>
        .tab2, .tab3, .tab4 {
            display: none;
        }

        #ssl, #tls {
            width: 5% !important;
            -webkit-appearance: radio;
        }
    </style>
    <div class="edit_user">
        <div class="tp_BreadCrumb_list_sec clearfix">
            <label></label>
        </div>
        <div class="tabClass usr_prof_tab_btns clearfix">
            <ul class="tab-view">
                <li><a href="tab1" class="active"> General </a></li>
                @if( !in_array('SUPER-ADMIN', $availRoleArr))
                @endif
                <li><a href="tab4"> Password </a></li>
            </ul>
        </div>
        <div class="tab1 edit_user_form_sec">
            <div class="edit_user_auto clearfix">
                <form class="edit-user-form clearfix" action="" method="post">
                    <label for="">
                        <strong> Company Name:</strong>
                        <input type="text" id="companyName" name="companyName" value="{{$user->name}}" placeholder="">
                        <span id="companyNameError" style="color: #F99;"></span>
                    </label>
                    <label for="">
                        <strong> Upload Company logo: </strong>
                        <div style="width: 100%;">
                            <input type="file" id="companyLogo" name="companyLogo" value="" placeholder="">
                            <img style="width: 150px;height: 150px;" id="companyLogoPreview"
                                 src="@if($user and $user->logo){{ \Illuminate\Support\Facades\Storage::disk('s3')->url($user->logo) }}@else{{ asset('assets/images/ureka_logo2.png')  }}@endif">
                        </div>
                        <label id="companyLogoPreviewLabel">{{$user->logo}}</label>
                        <span id="companyLogoError" style="color: #F99;"></span>
                    </label>
                    <label for="">
                        <strong> Email: </strong>
                        <input type="text" id="companyEmail" name="companyEmail" value="{{$user->email}}" placeholder=""
                               readonly>
                        <span id="companyEmailError" style="color: #F99;"></span>
                        <span id="companyEmailDupError" style="color: #F99;"></span>
                    </label>
                    <label for="">
                        <strong> Phone: </strong>
                        <input type="Number" id="companyPhone" name="companyPhone" value="{{$user->phone}}"
                               placeholder="">
                        <span id="companyPhoneError" style="color: #F99;"></span>
                    </label>
                    <input type="hidden" id="companyKeyOrg1" name="companyKeyOrg" value="{{$user->id}}" placeholder=" "
                           readonly>
                    <input type="hidden" id="tab1" name="tab" value="tab1" placeholder=" "
                           readonly>
                    <label for="">
                        <strong> Company key: </strong>
                        <input type="text" id="companyKey" name="companyKey"
                               value="{{$user->company_key}}" placeholder=" "
                               readonly>
                    </label>
                    @if( in_array('SUPER-ADMIN', $availRoleArr) && \Auth::user()->id != $user->id)
                        <label for="">
                            <strong> Status: </strong>
                            <select id="status" name="status" class="form-control">
                                <option value="">Select Any</option>
                                <option value="1" {{ ( $user->status == 1 ) ? 'selected':''  }}>Active</option>
                                <option value="0" {{ ( $user->status == 0 ) ? 'selected':''  }}>Inactive</option>
                            </select>
                        </label>
                    @endif
                    <label for="">
                        <button class="sub_btn" type="submit" name="button">Add</button>
                    </label>
                </form>
            </div>
        </div>
        @if( !in_array('SUPER-ADMIN', $availRoleArr))
            <div class="tab3 edit_user_form_sec">
                <div class="edit_user_auto clearfix">
                    <form class="edit-user-form clearfix" action="" method="post">
                        <input type="hidden" id="companyKeyOrg2" name="companyKeyOrg" value="{{$user->id}}"
                               placeholder=" "
                               readonly>
                        <input type="hidden" id="tab2" name="tab" value="tab3" placeholder=" "
                               readonly>
                        <label for="">
                            <strong> Smtp Host: </strong>
                            <input type="text" id="smtpHost" name="smtpHost" value="{{$user->smtp_host}}"
                                   placeholder="">
                            <span id="smtpHostError" style="color: #F99;"></span>
                        </label>
                        <label for="">
                            <strong> Smtp User: </strong>
                            <input type="text" id="smtpUser" name="smtpUser" value="{{$user->smtp_user}}"
                                   placeholder="">
                            <span id="smtpUserError" style="color: #F99;"></span>
                        </label>
                        <label for="">
                            <strong> Smtp Password: </strong>
                            <input type="password" id="smtpPassword" name="smtpPassword" value=""
                                   placeholder="">
                            <span id="smtpPasswordError" style="color: #F99;"></span>
                        </label>
                        <label for="">
                            <strong> Smtp Port: </strong>
                            <input type="text" id="smtpPort" name="smtpPort" value="{{$user->smtp_port}}"
                                   placeholder="">
                            <span id="smtpPortError" style="color: #F99;"></span>
                        </label>

                        <label for="">
                            <strong> Type: </strong>
                            SSL:
                            <input type="radio" id="ssl" class="typeRadio" name="typeRadio" value="ssl"
                                   placeholder="">
                            TSL:
                            <input type="radio" id="tls" class="typeRadio" name="typeRadio" value="tsl"
                                   placeholder="">
                            <span id="typeError" style="color: #F99;"></span>
                        </label>

                        <label for="">
                            <strong> From Name: </strong>
                            <input type="text" id="smtpFromName" name="smtpFromName"
                                   value="{{$user->smtp_from_name}}" placeholder=" ">
                            <span id="smtpFromNameError" style="color: #F99;"></span>
                        </label>
                        <label for="">
                            <strong> From Email: </strong>
                            <input type="text" id="smtpFromEmail" name="smtpFromEmail"
                                   value="{{$user->smtp_from_email}}" placeholder=" ">
                            <span id="smtpFromEmailError" style="color: #F99;"></span>
                        </label>
                        <label for="">
                            <button class="sub_btn" type="submit" name="button">Add</button>
                        </label>
                    </form>
                </div>
            </div>
        @endif
        <div class="tab4 edit_user_form_sec">
            <div class="edit_user_auto clearfix">
                <form class="edit-user-form clearfix" action="" method="post">
                    <input type="hidden" id="companyKeyOrg3" name="companyKeyOrg" value="{{$user->id}}" placeholder=" "
                           readonly>
                    <input type="hidden" id="tab3" name="tab" value="tab4" placeholder=" "
                           readonly>
                    <label for="">
                        <strong>Password: </strong>
                        <input type="password" id="password" name="password" value=""
                               placeholder="">
                        <span id="passwordError" style="color: #F99;"></span>
                    </label>
                    <label for="">
                        <strong>Confirm Password: </strong>
                        <input type="password" id="confirmPassword" name="confirmPassword" value=""
                               placeholder="">
                        <span id="confirmPasswordError" style="color: #F99;"></span>
                    </label>
                    <label for="">
                        <button class="sub_btn" type="submit" name="button">Update</button>
                    </label>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('jsSection')
    <script>
        var isAdmin = '{{ (in_array('SUPER-ADMIN', $availRoleArr)) ? true: false  }}';
        var typeSslTsl = '{{$user->ssl_tsl}}';
        if (typeSslTsl != "") {
            $("input[name='typeRadio'][value=" + typeSslTsl + "]").prop("checked", true);
        }
        $(document).ready(function () {
            function readURL(input) {
                if (input.files && input.files[0]) {
                    var reader = new FileReader();

                    reader.onload = function (e) {
                        $('#companyLogoPreview').attr('src', e.target.result);
                    }

                    reader.readAsDataURL(input.files[0]);
                }
            }

            $("#companyLogo").change(function () {
                $("#companyLogoPreviewLabel").hide();
                readURL(this);
            });
        })
    </script>
    <script src="{{asset('/assets/js/user/edit.js')}}"></script>
@stop