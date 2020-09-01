<div class="lst_tbl_drop_outer">
    <span class="">
                                 <img src="{{asset('assets/images/sett_icon.png')}}" alt="#"
                                      class="mCS_img_loaded">
                              </span>
    <ul>
        <li onclick="window.location.href = '{{ url('campaignTemplates/edit', $CampaignTemplates->id) }}';">
            <a href="{{ url('campaignTemplates/edit/', $CampaignTemplates->id) }}"> <img
                        src="{{asset('/assets/images/edit_icon.png')}}" alt=""> Edit
            </a>
        </li>
    </ul>
</div>