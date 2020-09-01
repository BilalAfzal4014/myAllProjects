<div class="lst_tbl_drop_outer">
    @if($newFeedTemplates->rec_type=='blacklist')
        <span class="">
                                     <img src="{{asset('/assets/images/sett_icon.png')}}" alt="#">
                                  </span>
        <ul>
            <li onclick="window.location.href = '{{ url('email/delete', $newFeedTemplates->id) }}';">
                <a href="{{ url('email/delete', $newFeedTemplates->id) }}"> <img
                            src="{{asset('/assets/images/del_icon.png')}}" alt="">
                    Delete
                </a>
            </li>
        </ul>
    @endif
</div>