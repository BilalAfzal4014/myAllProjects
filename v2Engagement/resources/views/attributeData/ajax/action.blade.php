<div class="lst_tbl_drop_outer">
    <span class="">
        <img src="{{asset('/assets/images/sett_icon.png')}}"  alt="#">
    </span>
    <ul>
        <li id="{{$data->id}}"  data-action="delete">
            <a href="#">
                <img src="{{asset('/assets/images/del_icon.png')}}" alt="#"> Delete
            </a>
        </li>
        <li id="{{$data->id}}"  data-action="download">
            <a href="#"><img src="{{asset('/assets/images/download.png')}}" alt="#"> Download</a>
        </li>

        <li id="{{$data->id}}"  data-action="importFile">
            <a href="#"><img src="{{asset('/assets/images/import.png')}}" alt="#"> Import File</a>
        </li>
    </ul>
</div>
