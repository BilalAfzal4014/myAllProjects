<div class="db_list_left_sec ">
    <div class="db_list_left_tp">
        <label> Filters : </label>
        {{--<div class=" inp_select  b_r">--}}
        {{--<select id="create_by">--}}
        {{--<option value="app_message"> Created By </option>--}}
        {{--<option value="email_html-2"> Created By 2 </option>--}}
        {{--</select>--}}
        {{--</div>--}}
    </div>
    <div class="db_list_left_btm scrollbar_content mCustomScrollbar _mCS_1">
        {{--<label>Show:</label>--}}
        <ul>

            <li>
                <div class="db_list_left_sublist">
                    <h3>Company</h3>
                    {{--<select id="company_id" name="company_id" class="form-control filter-status">--}}
                    {{--<option>Select a company</option>--}}
                    {{--@foreach($users as $user)--}}
                    {{--<option value="{{$user->id}}">{{$user->name}}</option>--}}
                    {{--@endforeach--}}
                    {{--</select>--}}
                    <ul>
                        @foreach( $users  as $user)
                            <li><a class="filter" href="javascript:void(0);" data-type="app_name" data-action="{{$user->id}}">{{$user->name}}</a></li>
                        @endforeach
                    </ul>
                </div>
            </li>
        </ul>
    </div>

</div>