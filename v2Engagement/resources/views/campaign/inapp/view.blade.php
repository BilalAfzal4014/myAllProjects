<link href="{{ asset('assets/css/inAppStyles.css') }}" rel="stylesheet" type="text/css" />
<meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
@if(!empty($track_link))
<img id="track_img"  src="{{ $track_link }}" />
@endif
<?php echo $message; ?>
<script>
    document.getElementById('track_img').style='display:none';    
    
</script>