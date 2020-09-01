@extends('layouts.master')

@section('create')
    <style>
        input:checked + .slider {
            background-color: #c0c0c0;
        }

        .switch {
            margin-left: 7px;
            margin-right: 7px;
        }

        .selectEle {
            display: block;
            border: 1px solid #b2b2b2;
            padding: 8px 11px;
            border-radius: 6px;
            width: 85%;
        }

        .outerDivSelect {
            width: 16.409%;
            display: inline-block;
        }

        .outerDivSelect label {
            font-size: 16px;
            font-weight: 700;
            color: #2a8689;
        }

        .spantest:after {
            content: '';
            margin-top: 3px;
            position: relative;
            left: 73%;
            bottom: 19px;
            margin-left: 6px;
            vertical-align: middle;
            border-top: 6px solid #2a8689;
            border-right: 6px solid transparent;
            border-left: 6px solid transparent;
            pointer-events: none;
        }

        .company_right strong, .company_right .outerDivSelect {
            display: inline-block;
            vertical-align: middle;
            width: 200px;
        }

    </style>
    @if( in_array('SUPER-ADMIN', $roleArr))
        <input class="companyName" type="hidden" value="superadmin" id="companyName">
    @else
        <input class="companyName" type="hidden" value="company" id="companyName">
    @endif
    <input class="companyId" type="hidden" value="{{$companyId}}">
    <input class="companyKey" type="hidden" value="{{$companyKey}}">
    <div class="db_content_holder step-app">
        <div class="tp_BreadCrumb_list_sec clearfix">
            <label id="" class="sec_tp_title"> <span id=""> Quick Notification </span></label>
            @if( in_array('SUPER-ADMIN', $roleArr))
                <div class="company_right" style="float: right">
                    <strong>
                        Silent Message
                        <label class="switch">
                            <input id="isSilent" type="checkbox" name="isSilent">
                            <span class="slider round"></span>
                        </label>
                    </strong>
                    <div class="outerDivSelect">
                        <label>Select Company</label>
                        <span class="spantest">
                    <select id="company" class="selectEle" step="0">
                        <option value="">Select Company</option>
                        @for($val=0;$val<count($users);$val++)
                            <option value="{{$users[$val]['id']}}">{{$users[$val]['name']}}</option>
                        @endfor
                    </select>
                </span>
                    </div>
                </div>
            @endif
        </div>

        <div class="breadcrumb_step_det_outer step-content" style="margin-top: 10px;">

            <div class="outerDivSelect">
                <label>App Name</label>
                <span class="spantest">
                    <select id="appNameSelect" class="selectEle" step="0">

                    </select>
                </span>
            </div>

            <div class="outerDivSelect">
                <label>Platform</label>
                <span class="spantest">
                    <select id="platformSelect" class="selectEle" step="2">
                    </select>
                </span>
            </div>

            <div class="outerDivSelect">
                <label>Version</label>
                <span class="spantest">
                    <select id="versionSelect" class="selectEle" step="3">

                    </select>
                </span>
            </div>

            <div class="outerDivSelect">
                <label>Build#</label>
                <span class="spantest">
                    <select id="buildSelect" class="selectEle" step="4">

                    </select>
                </span>
            </div>

            <div class="outerDivSelect">
                <label>Users</label>
                <select id="userSelect" class="selectEle" multiple="multiple">
                </select>
                <span id="userError" style="color: #F99;"></span>
            </div>

            <div class="outerDivSelect">
                <label>Notification Type</label>
                <span class="spantest">
                    <select id="notificationSelect" class="selectEle">
                        <option value="push">Push</option>
                        <option value="inapp">In App</option>
                    </select>
                </span>
            </div>

            <strong>
                Live
                <label class="switch">
                    <input id="sandBoxSelection" type="checkbox">
                    <span class="slider round"></span>
                </label>
                SandBox
            </strong>


            <div class="comp_input_sec p_t_b" style="padding: 10px 20px 10px 0px;">
                <textarea placeholder="Message" id="inappMessage" name="name" class="b_r "
                          style="height: 250px"></textarea>
                <span id="notificationError" style="color: #F99;"></span>
            </div>

            <div class="step-footer save_next_sec ">
                <button data-direction="next" class="step-btn" style="display: none;">Next</button>
                <button data-direction="launch_btn" class="step-btn launch_btn">Launch Campaign</button>
                <button data-direction="" class="save_as_draft" id="quickNotificationBtn">Send Notification</button>

            </div>

            <div class="" style="padding:5px; background:#fff;">
            </div>

        </div>

    </div>
@stop

@section('jsSection')
    <script src="{{asset('/assets/js/notification/quickNotification.js')}}"></script>
    <script>
        var Role = "{!! $role !!}";
        console.log('role', Role);
        $("body").attr("class", 'email_html-2');
        $(".db_content_holder").css({'display': 'block'});
        $(".db_content_listing_holder").css({'display': 'none'});
    </script>
@stop