<style>
    .legend{
        padding: 0px 5px;
        position: absolute;
        right: 20px;
        top: 7px;
        height: 10px;
    }

    .suspendIcon {
        background: red;
    }

    .launchIcon{
        background: greenyellow;
    }

    .draftIcon{
        background: blue;
    }

    .expiredIcon{
        background: black;
    }

    .campaignStatus {
        color: #666;
        font-size: 12px;
        font-weight: 700;
        letter-spacing: 0.5px;
        text-overflow: ellipsis;
    }

    .legendListing li{
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
    <div class="db_list_left_btm scrollbar_content mCustomScrollbar _mCS_1">
        <label>Show:</label>
        <ul>

            {{--<li> <a href="#" id="status-all"> All  </a> </li>--}}

            <li>
                <div class="db_list_left_sublist">
                    <h3>Status</h3>
                    <ul id="campaignStatus">
                    </ul>
                </div>
            </li>
            <li>
                <div class="db_list_left_sublist">
                    <h3>Schedule</h3>
                    <ul id="campaignSchedule">
                    </ul>
                </div>
            </li>
            <li>
                <div class="db_list_left_sublist">
                    <h3>Campaign Type</h3>
                    <ul id="campaignType">
                    </ul>
                </div>
                <div class="db_list_left_sublist">
                    <h3>Tags</h3>
                    <ul id="campaignTags">
                    </ul>
                </div>
            </li>
        </ul>
    </div>
</div>