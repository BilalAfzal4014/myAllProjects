@if($type == 'template')
    @foreach($templates as $template)
    <option  value="{{$template->id}}">{{$template->name}}</option>
    @endforeach
@endif
@if($type == 'location')
    @foreach($locations as $location)
    <option value="{{$location->id}}">{{$location->default_name}}</option>
    @endforeach
@endif
@if($type == 'segment')
    @foreach($segments as $segment)
    <option  value="{{$segment->id}}">{{$segment->name}}</option>
    @endforeach
@endif