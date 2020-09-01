<div class="left_menu_list ">
    <div class="menu_left_logo">
        <a href="{{route('dashboard')}}"> <img src="{{asset('html/images/ureka_logo2.png')}}"> </a>
    </div>
    @php
        $roleArr = Auth::user()->roles()->pluck('name')->toArray()
    @endphp
    <ul>
        @if( !in_array('SUPER-ADMIN', $roleArr))
            <li>
                <a href="{{route('lookup.index')}}"
                   class="{{ Request::path() == 'lookup/listing' ? 'active' : '' }}">
                    <i class="fa fa-search" aria-hidden="true"></i>
                    <b class="link_inline"> Lookup</b>
                    <b class="link_block"> Lookup</b>
                </a>
            </li>
            <li>
                <a style="cursor: pointer"
                   class="{{ Request::path() == 'backend/app/listing' || Request::path() == 'import-data/import-file-view' || Request::path() == 'attributes/list' ? 'active' : '' }}">
                    <i class="fa fa-cog" aria-hidden="true"></i>
                    <b class="link_inline"> Settings</b>
                    <b class="link_block"> Settings</b>
                </a>
                <ul data-list="settings1" class="inner_menu">
                    <li><a href="{{url('/backend/app/listing')}}"
                           class="{{ Request::path() == 'backend/app/listing' ? 'active' : '' }}">App List</a></li>
                    <li><a href="{{route('importFileView')}}"
                           class="{{ Request::path() == 'import-data/import-file-view' ? 'active' : '' }}">Import
                            List</a>
                    </li>
                    <li><a href="{{url('/attributes/list')}}"
                           class="{{ Request::path() == 'attributes/list' ? 'active' : '' }}">Attribute List</a>
                    </li>
                </ul>
            </li>
            <li>
                <a style="cursor: pointer"
                   class="{{ Request::path() == 'backend/campaign/campaigns' || Request::path() == 'backend/campaign/capping-settings'|| Request::path() == 'location/index' ? 'active' : '' }}">
                    <i class="fa fa-bullhorn" aria-hidden="true"></i>
                    <b class="link_inline"> Campaign</b>
                    <b class="link_block"> Campaign</b>
                </a>
                <ul data-list="campaign" class="inner_menu">
                    <li><a class="{{ Request::path() == 'backend/campaign/campaigns' ? 'active' : '' }}"
                           href="{{url('/backend/campaign/campaigns')}}">List</a></li>
                    <li><a class="{{ Request::path() == 'backend/campaign/capping-settings' ? 'active' : '' }}"
                           href="{{url('/backend/campaign/capping-settings')}}">Frequency Capping</a></li>

                </ul>
            </li>
            <li>
                <a href="{{asset('/backend/segment/segments')}}"
                   class="{{ Request::path() == 'backend/segment/segments' ? 'active' : '' }}">
                    <i class="fa fa-users" aria-hidden="true"></i>
                    <b class="link_inline"> Segments</b>
                    <b class="link_block"> Segments</b>
                </a>
            </li>
            <li>
                <a href="{{url('/location/index')}}"
                   class="{{ Request::path() == 'location/index' ? 'active' : '' }}">
                    <i class="fa fa-map-marker" aria-hidden="true"></i>
                    <b class="link_inline"> Location</b>
                    <b class="link_block"> Location</b>
                </a>
            </li>
            <li>
                <a href="{{ route('newsfeedList') }}"
                   class="{{ Request::path() == 'backend/newsfeed/list' ? 'active' : '' }}">
                    <i class="fa fa-newspaper-o" aria-hidden="true"></i>
                    <b class="link_inline"> Newsfeed</b>
                    <b class="link_block"> Newsfeed</b>
                </a>
            </li>
            <li>
                <a href="{{route('gallery')}}" class="{{ Request::path() == 'gallery' ? 'active' : '' }}">
                    <i class="fa fa-camera" aria-hidden="true"></i>
                    <b class="link_inline">Gallery</b>
                    <b class="link_block">Gallery</b>
                </a>
            </li>
            <li>
                <a href="{{asset('/backend/attribute/attributeData')}}"
                   class="{{ Request::path() == 'backend/attribute/attributeData' ? 'active' : '' }}">
                    <i class="fa fa-user" aria-hidden="true"></i>
                    <b class="link_inline">User</b>
                    <b class="link_block">User</b>
                </a>
            </li>
        @endif
        @if( in_array('SUPER-ADMIN', $roleArr))
            <li>
                <a href="{{route('lookup.index')}}" class="{{ Request::path() == 'lookup/listing' ? 'active' : '' }}">
                    <i class="fa fa-search" aria-hidden="true"></i>
                    <b class="link_inline"> Lookup</b>
                    <b class="link_block"> Lookup</b>
                </a>
            </li>
            <li>
                <a data-list="settings2" style="cursor: pointer"
                   class="{{ Request::path() == 'company/cache' || Request::path() == 'campaignQueue'|| Request::path() == 'duplicates/attribute' ? 'active' : '' }}">
                    <i class="fa fa-cog" aria-hidden="true"></i>
                    <b class="link_inline"> Settings</b>
                    <b class="link_block"> Settings</b>
                </a>
                <ul class="inner_menu">
                    {{--<li><a class="{{Request::path() == 'company/cache' ? 'active' : '' }}"--}}
                           {{--href="{{url('/company/cache')}}">Sync Data</a></li>--}}
                    <li><a class="{{Request::path() == 'campaignQueue' ? 'active' : '' }}"
                           href="{{url('campaignQueue')}}">Campaign Queue</a></li>
                    {{--<li><a class="{{Request::path() == 'duplicates/attribute' ? 'active' : '' }}"--}}
                           {{--href="{{route('duplicates.attribute')}}">Duplicate</a></li>--}}
                </ul>
            </li>
            <li>
                <a href="{{route('location.index')}}">
                    <i class="fa fa-map-marker" aria-hidden="true"></i>
                    <b class="link_inline"> Location</b>
                    <b class="link_block"> Location</b>
                </a>
            </li>
            {{--<li>--}}
                {{--<a data-list="templates" style="cursor: pointer"--}}
                   {{--class="{{Request::path() == 'newsFeedTemplates' || Request::path() == 'campaignTemplates' ? 'active' : '' }}">--}}
                    {{--<i class="fa fa-cog" aria-hidden="true"></i>--}}
                    {{--<b class="link_inline"> Templates</b>--}}
                    {{--<b class="link_block"> Templates</b>--}}
                {{--</a>--}}
                {{--<ul class="inner_menu">--}}
                    {{--<li><a href="{{url('newsFeedTemplates')}}"--}}
                           {{--class="{{Request::path() == 'newsFeedTemplates' ? 'active' : '' }}">News Feed Template</a>--}}
                    {{--</li>--}}
                    {{--<li><a href="{{url('campaignTemplates')}}"--}}
                           {{--class="{{Request::path() == 'campaignTemplates' ? 'active' : '' }}">Campaign Template</a>--}}
                    {{--</li>--}}
                {{--</ul>--}}
            {{--</li>--}}
            <li>
                <a href="{{route('users.index')}}" class="{{ Request::path() == 'users' ? 'active' : '' }}">
                    <i class="fa fa-user" aria-hidden="true"></i>
                    <b class="link_inline"> Users</b>
                    <b class="link_block"> Users</b>
                </a>
            </li>
            
            <li>
                <a href="{{route('cache.index')}}" class="{{ Request::path() == 'cache' ? 'active' : '' }}">
                    <i class="fa fa-book" aria-hidden="true"></i>
                    <b class="link_inline"> Cache Viewer</b>
                    <b class="link_block"> Cache Viewer</b>
                </a>
            </li>
            
        @endif
    </ul>
</div>