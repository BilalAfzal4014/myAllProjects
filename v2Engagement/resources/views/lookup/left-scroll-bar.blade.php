<div class="db_list_left_sec ">
    <div class="db_list_left_btm scrollbar_content mCustomScrollbar _mCS_1">
        <label>Filter:</label>
        <ul id="segmentTagsFilter">
            {{--@foreach($tagsCount as $tag => $count)--}}
            @if(Auth::user()->name=="Super Admin")
                <li><a style="cursor: pointer" class="lookup_filters" data-column="app-name" data-val="0">Parent</a></li>
            @else
                <div class="db_list_left_sublist">
                    <h3>Types</h3>
                    <ul>
                        <li><a style="cursor: pointer" class="lookup_filters" data-column="app-name" data-val="1">ACTION
                                TRIGGERS</a></li>
                        <li><a style="cursor: pointer" class="lookup_filters" data-column="app-name" data-val="89">CONVERSION
                                TYPES</a></li>
                    </ul>
                </div>
            @endif

            {{--@endforeach--}}
        </ul>
    </div>
</div>