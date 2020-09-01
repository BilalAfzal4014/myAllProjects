<div class="lst_tbl_drop_outer">
    <span class="">
                                 <img src="{{asset('assets/images/sett_icon.png')}}" alt="#"
                                      class="mCS_img_loaded">
                              </span>
    <ul>
        <li>
            <a onclick="javascript: processJob(this, {{ $user->id }});"
               style="cursor:pointer ">
                @if (in_array($user->status, ['Available', 'Processing']))
                    <i class="fa fa-play"></i> Execute
                @else
                    <i class="fa fa-bookmark"></i> Set to Available
                @endif
            </a>

        </li>
        <li>
            <a onclick="deleteJob(this, {{ $user->id }});" style="cursor:pointer ">
                <img src="{{asset('/assets/images/del_icon.png')}}" alt="">Remove
            </a>
        </li>
        @if($user->status=='Complete')
            <li>
                <a onclick="tracking(this, {{ $user->campaign_id }});"
                   style="cursor:pointer ">
                    <img src="{{asset('/assets/images/edit_icon.png')}}" alt="">Tracking
                </a>
            </li>
        @endif
    </ul>
</div>