<div class="db_list_left_sec ">
    <div class="db_list_left_tp">
        {{--<div class="row">--}}
        {{----}}
        {{--</div>--}}

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
            @if(Auth::user()->name=='Super Admin')
                <li>
                    <a id="importData" class="btn btn-primary">Import Data</a>
                </li>
            @endif
            <li>
                <div class="db_list_left_sublist">
                    <h3>Status</h3>
                    <ul>
                        <li><a class="filter" href="javascript:void(0);" data-type="app_name"
                               data-action="added">Added</a></li>
                        <li><a class="filter" href="javascript:void(0);" data-type="app_name" data-action="executing">Executing</a>
                        <li><a class="filter" href="javascript:void(0);" data-type="app_name" data-action="completed">Completed</a>
                        </li>
                        <li><a class="filter" href="javascript:void(0);" data-type="app_name" data-action="failed">Failed</a>
                        </li>
                    </ul>
                </div>
            </li>
        </ul>
    </div>

</div>