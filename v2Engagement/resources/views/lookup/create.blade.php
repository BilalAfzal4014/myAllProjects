@extends('layouts.master')



@section('content')
    <style>
        input:read-only {
            background: #ebebe4;
            cursor: no-drop;
        }
    </style>
    <div class="tab1 edit_user_form_sec">
        <div class="edit_user_auto clearfix">


            <form class="edit-user-form clearfix" id="lookupForm" action="{{route('lookup.submit')}}" method="post">

                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                @if($lookUpObj)
                    <input type="hidden" id="edit_lookup_id" value="@if($lookUpObj){{$lookUpObj->id}} @endif" name="id">
                    <input type="hidden" value="@if($lookUpObj){{$lookUpObj->created_date}} @endif" name="created_date">

                @endif
                <label for="">
                    <strong> Name: </strong>
                    <input required style="color: #000;" id="name" name="name" class="specialChar"
                           value="@if($lookUpObj){{$lookUpObj->name}}@endif" placeholder="" type="text">
                    <span id="nameError" style="color: #F99; position: relative; "></span>
                </label>
                <label for="">
                    <strong> Code: </strong>
                    <input required style="color: #000;" class="has-error" id="code" name="code"
                           value="@if($lookUpObj){{$lookUpObj->code}}@endif" placeholder="" type="text" readonly>
                    <span id="codeError" style="color: #F99; position: relative;"></span>
                </label>
                <label for="">
                    <strong> Description: </strong>
                    <textarea id="inappMessage" name="description" class="b_r " rows="5" style="width: 100%;"
                              maxlength="150">@if($lookUpObj){{$lookUpObj->description}}@endif</textarea>
                    <span id="descriptionError" style="color: #F99; position: relative;"></span>
                </label>
                <label for="">
                    <strong> Parent: </strong>
                    <div class="Campaigns_type_sec inp_select  b_r">
                            <select name="parent_id" id="parent_id">
                            <option value="">Please Select A Parent</option>
                            @if(Auth::user()->name=='Super Admin')
                                <option value="addparent">=============Make Parent===========</option>
                                @foreach($parentListing as $item)
                                    @if(!empty($lookUpObj))
                                        <option @if($lookUpObj->parent_id == $item->id || $lookUpObj->parent_id == $item->parent_id) selected
                                                @endif value="{{$item->id}}">
                                            {{$item->name}}</option>
                                    @else
                                        <option value="{{$item->id}}">{{$item->name}}</option>
                                    @endif

                                @endforeach
                            @else
                                @if(count($lookUpObj)>0)
                                    @if($lookUpObj->parent_id==1)
                                        <option value="1" selected>Action Triggers</option>
                                        <option value="89">Conversion Types</option>
                                    @else
                                        <option value="1">Action Triggers</option>
                                        <option value="89" selected>Conversion Types</option>
                                    @endif
                                @else
                                    <option value="1">Action Triggers</option>
                                    <option value="89">Conversion Types</option>
                                @endif
                            @endif
                        </select>
                    </div>
                    <span id="parentError" style="color: #F99; position: relative;"></span>
                </label>

                <label for="">
                    <button class="sub_btn" type="button" name="button">Add</button>
                </label>
            </form>
        </div>
    </div>
@stop
@section('jsSection')
    <script src="{{asset('/assets/js/lookup/lookupCrud.js')}}"></script>
    <script type="text/javascript">
        $(document).ready(function () {

        });
    </script>
@stop