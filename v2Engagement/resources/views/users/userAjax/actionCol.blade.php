<div class="lst_tbl_drop_outer">
                              <span class="">
                                 <img src="{{asset('/assets/images/sett_icon.png')}}" alt="#">
                              </span>
    <ul>
        <li onclick="fn_edit('{{ route('users.edit', $user->id) }}')">
            <a href="{{ route('users.edit', $user->id) }}"> <img
                        src="{{asset('/assets/images/edit_icon.png')}}" alt=""> Edit
            </a>
        </li>
        <li onclick="fn_updateStatus('{{ route('userStatus', ['id'=>$user->id,'status'=>$user->status]) }}',{{$user->status}})">
            <a>
                <img src="{{asset('/assets/images/del_icon.png')}}"
                     alt=""> {{ ($user->status ==1 ) ? 'Inactive':'Active' }}
            </a>
        </li>
    </ul>
</div>