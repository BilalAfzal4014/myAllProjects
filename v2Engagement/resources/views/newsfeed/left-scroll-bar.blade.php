<style>
    .legend {
        padding: 0px 5px;
        position: absolute;
        right: 20px;
        top: 7px;
        height: 10px;
    }

    .suspendIcon {
        background: red;
    }

    .launchIcon {
        background: greenyellow;
    }

    .draftIcon {
        background: blue;
    }

    .expiredIcon {
        background: black;
    }

    .campaignStatus {
        color: #666;
        font-size: 12px;
        font-weight: 700;
        letter-spacing: 0.5px;
        text-overflow: ellipsis;
    }

    .legendListing li {
        position: relative;
    }
</style>
<div class="db_list_left_sec small_scroll">
    <div class="db_list_left_tp">
        <label style="color: #2a8689;">Status:</label>
        <ul class="legendListing">
            <li>
                <span class="campaignStatus">Active</span>
                <i class="legend launchIcon"></i>
            </li>
            <li>
                <span class="campaignStatus">Draft</span>
                <i class="legend draftIcon"></i>
            </li>
            <li>
                <span class="campaignStatus">Suspend</span>
                <i class="legend suspendIcon"></i>
            </li>
            <li>
                <span class="campaignStatus">Expired</span>
                <i class="legend expiredIcon"></i>
            </li>
        </ul>
    </div>
    <div class="db_list_left_tp hide">
        <label> Group by: </label>
        <div class=" inp_select  b_r">
            <select id="create_by">
                <option value="app_message"> Created By</option>
                <option value="email_html-2"> Created By 2</option>
            </select>
        </div>
    </div>
    <div class="db_list_left_btm scrollbar_content mCustomScrollbar _mCS_1">
        <label>Filters:</label>
        <ul>
            <li>
                <div class="db_list_left_sublist">
                    <h3>Status</h3>
                    <ul>
                        <li><a class="filter-query" data-filter="active" data-filtertype="status" href="javascript:void(0);">Active</a>
                        </li>
                        <li><a class="filter-query" href="javascript:void(0);" data-filter="draft" data-filtertype="status">Drafts</a>
                        </li>
                        <li>
                            <a class="filter-query" href="javascript:void(0);" data-filter="expired" data-filtertype="status">Expired</a>
                        </li>
                        <li>
                            <a class="filter-query" href="javascript:void(0);" data-filter="suspend" data-filtertype="status">Suspend</a>
                        </li>
                    </ul>
                </div>
            </li>
            <li>
                <div class="db_list_left_sublist">
                    <h3>Platform</h3>
                    <ul>
                        <li><a href="javascript:void(0);" class="filter-query" data-filter="ios_url"
                               data-filtertype="platform">iOS</a></li>
                        <li><a href="javascript:void(0);" class="filter-query" data-filter="android_url"
                               data-filtertype="platform">Android</a></li>
                        <li><a class="filter-query" href="javascript:void(0);" data-filter="window_url"
                               data-filtertype="platform">Window</a></li>
                        <li><a class="filter-query" data-filter="web_url" href="javascript:void(0);"
                               data-filtertype="platform">Web</a></li>
                    </ul>
                </div>
            </li>
            <li>
                <div class="db_list_left_sublist">
                    <h3>Types</h3>
                    <ul>
                        @foreach($templates as $template)
                            <li><a class="filter-query" href="javascript:void(0);" data-filter="{{$template->id}}"
                                   data-filtertype="types">{{$template->name}}</a></li>
                        @endforeach
                    </ul>
                </div>
            </li>
            <li class="hide">
                <div class="db_list_left_sublist">
                    <h3>Created By</h3>
                    <ul>
                        <li><a href="#{{ Auth::user()->name }}"
                               onclick="filterNewsfeed({{ Auth::user()->id }}, 'createdby')">{{ Auth::user()->name }}</a>
                        </li>
                    </ul>
                </div>
            </li>
        </ul>
        <ul>

        </ul>
        <label>Tags:</label>
        <ul id="newsFeedTags">
            @foreach($tagsNewsFeed as $tag => $count)
                <li><a href="javascript:void(0);" class="news-feed-filters" data-tag="{{ $tag }}">{{ $tag }}
                        ({{ $count }})</a></li>
            @endforeach
        </ul>
    </div>
</div>
