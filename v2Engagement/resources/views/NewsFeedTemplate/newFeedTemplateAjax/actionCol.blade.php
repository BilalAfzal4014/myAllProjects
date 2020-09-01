<div class="lst_tbl_drop_outer">
    <span class="">
                                     <img src="{{asset('assets/images/sett_icon.png')}}" alt="#"
                                      class="mCS_img_loaded">
                              </span>
    <ul>
        <li onclick="window.location.href = '{{ url('newFeedTemplates/edit', $newFeedTemplates->id) }}';">
            <a href="{{ url('newFeedTemplates/edit/', $newFeedTemplates->id) }}"> <img
                        src="{{asset('/assets/images/edit_icon.png')}}" alt=""> Edit
            </a>
        </li>
        <li onclick="window.location.href = '{{ url('newFeedTemplatesStatus', ['id'=>$newFeedTemplates->id,'is_active'=>$newFeedTemplates->is_active]) }}';">
            <a href="{{ url('newFeedTemplatesStatus', ['id'=>$newFeedTemplates->id,'is_active'=>$newFeedTemplates->is_active]) }}">
                <img src="{{asset('/assets/images/del_icon.png')}}"
                     alt=""> {{ ($newFeedTemplates->is_active ==1 ) ? 'Inactive':'Active' }} </a>
        </li>
    </ul>
</div>