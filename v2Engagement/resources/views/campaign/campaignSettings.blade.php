@extends('layouts.master')


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
    <input class="companyId" type="hidden" value="{{$companyId}}"/>
    <div class="edit_user">
        <div class="tp_BreadCrumb_list_sec clearfix">
            <label></label>
        </div>
        <div class="tabClass usr_prof_tab_btns clearfix">
            <ul class="tab-view">
                <li><a style="background: none;" href="tab1" class="active"> Capping Rules </a></li>
            </ul>
        </div>
        <div class="tab1 edit_user_form_sec">
            <div class="inp_and_sel_det_outer rad_det_step clearfix">
                <label>Send This Campaign To Users Who </label>
                <ul id="cappingCollection">
                    <li id="cappingMultiSelectLi">
                        <div class="inp_select b_r" style="width: 100%;">
                            <select id="actionMultiSelect">
                                <option value="-1" selected="">Declare Rules...</option>
                                <option value="Add Rule">Add Rule</option>
                            </select>
                        </div>
                        <span id="cappingError" style="color: #F99; float: left; width: auto !important;"></span>
                    </li>
                </ul>
            </div>

            <div class="seg_messages_btn_outer">
                <div class="messages_use_btns clearfix">
                    <ul>
                        <li>
                            <button id="submitCapping" type="button" name="button">Save</button>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('jsSection')
    <script src="{{asset('/assets/js/campaign/campaignSettings.js')}}"></script>
@stop