<div class="db_list_left_sec ">
    <div class="db_list_left_btm scrollbar_content mCustomScrollbar _mCS_1">
        <label>Tags:</label>
        <ul id="segmentTagsFilter">
            @foreach($tagsCount as $tag => $count)
            <li> <a style="cursor: pointer" class="segment-filters" data-column="tags" data-val="{{ $tag }}">{{ $tag }} ({{ $count }})</a></li>
            @endforeach
        </ul>
    </div>
</div>