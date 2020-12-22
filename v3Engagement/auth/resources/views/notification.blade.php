<link href="{{ asset('css/inAppStyles.css') }}" rel="stylesheet" type="text/css" />
<meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
@if(!empty($data['track_link']))
    <img src="{{ $data['track_link'] }}" />
@endif
<?php echo $data['notification']; ?>