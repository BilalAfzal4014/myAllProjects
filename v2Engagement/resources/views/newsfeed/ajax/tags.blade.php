@foreach($tagsNewsFeed as $tag => $count)
    <li> <a href="javascript:void(0);" class="news-feed-filters" data-tag="{!! $tag !!}">{!! $tag !!} ({!! $count !!} )</a></li>
@endforeach