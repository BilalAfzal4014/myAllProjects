@extends('layouts.master')
@section('content')
<div id="regMGS"></div>
<div class='col-lg-8 col-lg-offset-2'>
     <hr>
     <form name="setting" id="setting" role="form" method="post" action="{{ url('setting/update') }}" onsubmit="return  validation()">
       <input type="hidden" name="_token" value="{{csrf_token()}}">
       <input type="hidden" name="id" value="{{ $setting['id'] }}">
    <div class="form-group">
        @include ('errors.list')
    </div>
    @if($setting['id']==1)
    <div class="form-group">
         <label>From name: </label>
         <input class="form-control" type="text" name="from_name" required="true"
                                               value="{{$setting['from_name'] }}">
    </div>

     <div class="form-group">
         <label>From Email: </label>
        <input class="form-control" type="email" name="from_address" required="true"
                                               value="{{$setting['from_address'] }}">
    </div>

     <div class="form-group">
         <label>BCC Address: </label>
        <input class="form-control" type="text" name="bcc_address" required="true"
                                               value="{{$setting['bcc_address'] }}">
    </div>
    @endif

    @if($setting['id']==2)
    <div class="form-group">
         <label>Queue Enabled: </label>
<select class="form-control" name="queue_enabled" required="true">
  <option  @if($setting['queue_enabled']==1) selected @endif value="1">Yes</option>
  <option  @if($setting['queue_enabled']==0) selected @endif value="0">No</option>
</select>
    </div>

     <div class="form-group">
         <label>Batch Size: </label>
        <input class="form-control" type="number" name="batch_size" required="true"
                                               value="{{$setting['batch_size'] }}">
    </div>

     <div class="form-group">
         <label>Retry Attempts: </label>
        <input class="form-control" type="number" name="retry_attempts" required="true"
                                               value="{{$setting['retry_attempts'] }}">
    </div>

      <div class="form-group">
         <label>Parallel Processes: </label>
        <input class="form-control" type="number" name="parallel_processes" required="true"
                                               value="{{$setting['parallel_processes'] }}">
    </div>
    @endif

     {{ Form::submit('Update', array('class' => 'btn btn-primary')) }}
      <a href="{{route('setting')}}" class="btn btn-info" role="button">Cancel</a>
    {{ Form::close() }}
</div>

<script>
 function validation(){
                $('#regMGS').html('');
                regName = /^[a-zA-Z-,]+(\s{0,1}[a-zA-Z-, ])*$/;
                if (regName.test(setting.from_name.value) != true)
                {
                    $('#regMGS').html('<div class="alert alert-warning">\n\
									  <strong>Warning! </strong>The from name field may only contain alphabetical characters.\n\
									</div>');
                    $("html, body").animate({scrollTop: 0}, "slow");
                    return false;

                }

 }
</script>
 
@endsection
