<div class="rt_tp_hdr_outer clearfix">
    <div class="hdr_profile_sec">
        <a class="hdr_menu_btn" style="cursor: pointer;"> <img src="{{ asset('/assets/images/hdr_menu_btn2.png')}}"
                                                               alt="#">
        </a>
        <p>
            <span>{{ Auth::user()->name }}</span>
        </p>

    </div>

    <div class="hdr_lft_list tp_hdr_list">
        <ul>
{{--            <li><a href="#"> <img src="{{asset('html/images/hdr_folder_icon-new.png')}}" alt="#"> </a></li>--}}
{{--            <li><a href="#"> <img src="{{asset('html/images/hdr_filter_icon-new.png')}}" alt="#"> </a></li>--}}
{{--            <li><a href="#"> <img src="{{asset('html/images/hdr_inbox_icon-new.png')}}" alt=""> </a></li>--}}
            <li class="hdr_alert"><a href="{{url('/notification/quickNotification')}}"> <img
                            src="{{asset('html/images/hdr_alert_icon-new.png')}}" alt=""> </a> <i></i>
            </li>
        </ul>
    </div>

    <div class="hdr_rit_search_sec clearfix">

        <div class="tp_hdr_search_sec">
            <form id="ent-serach-top" action="/search">
                <div class="inputWrap">
                    <input class="top-search" type="search" placeholder="Search Anything Here....">
                    <input class="search_btn" type="submit">
                </div>
            </form>
        </div>
        <a style="cursor: pointer;"> <img src="{{ asset('/assets/images/hdr_profile_menu_icon.png')}}" alt="#"> </a>
        <div class="hdr_rt_drop_down">
            <ul>
                <li><a href="{{url('/users/' . Auth::user()->id . '/edit')}}"> Edit Profile </a></li>
                <li><a href="{{ url('/logout') }}"> Log Out </a></li>
            </ul>
        </div>
    </div>
</div>
