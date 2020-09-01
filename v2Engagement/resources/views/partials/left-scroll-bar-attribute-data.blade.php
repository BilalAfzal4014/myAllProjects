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
                    <h3>Type</h3>
                    <ul>
                        <li><a class="filter" href="javascript:void(0);" data-type="type" data-action="1">Excel Import</a></li>
                        <li><a class="filter" href="javascript:void(0);" data-type="type" data-action="0">API Import</a>
                        </li>
                    </ul>
                </div>
            </li>
            <li>
                <div class="db_list_left_sublist">
                    <h3>Status</h3>
                    <ul>
                        <li><a class="filter" href="javascript:void(0);" data-type="status" data-action="0">Inactive</a></li>
                        <li><a class="filter" href="javascript:void(0);" data-type="status" data-action="1">Active</a>
                        </li>
                    </ul>
                </div>
            </li>
            <li>
                <div class="db_list_left_sublist">
                    <h3>App Name</h3>
                    <ul>
                        @foreach( $appCount  as $item)

                            <li><a class="filter" href="javascript:void(0);" data-type="app_name" data-action="{{$item->app_name}}">{{$item->app_name}} ({{$item->cnt}})</a></li>
                        @endforeach
                    </ul>
                </div>
            </li>
        </ul>
    </div>

</div>