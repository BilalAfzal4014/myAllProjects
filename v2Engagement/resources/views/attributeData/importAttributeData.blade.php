@extends('layouts.master')
@section("create-class")
    "listing_ftr_hide"
@stop
@section('content')

<div class="segment_content_outer deta_imp_cont_sec clearfix">
    <div class="MSG"></div>
    <form class="importData">
        {{ csrf_field() }}
        <div class="deta_imp_upload_sec">
            <label class="deta_imps_upload_btn">
                <input type="file"  name="imported_file" onclick="this.value=null;">
                <span style="text-align: center;display: block;" id="fileName"></span>
            </label>
        </div>
    </form>
</div>

<div class="step-footer save_next_sec segment_footer">
    <button data-direction="prev" class="step-btn" style="display: inline-block;"> Back</button>
    <button data-direction="next" class="step-btn" style="display: none;">Next</button>

    <button data-direction="" class="save_as_draft">Save</button>
    <button data-href="{{ route('downloadSampleFile') }}"  class="download_sample_file">Download Sample File</button>
</div>
@stop

@section('jsSection')
<script type="text/javascript">
    $(document).ready(function(){


        $("[name='imported_file']").on("change",function () {

            var  fullPath = $(this).val();
            if (fullPath) {
                var startIndex = (fullPath.indexOf('\\') >= 0 ? fullPath.lastIndexOf('\\') : fullPath.lastIndexOf('/'));
                var filename = fullPath.substring(startIndex);
                if (filename.indexOf('\\') === 0 || filename.indexOf('/') === 0) {
                    filename = filename.substring(1);
                }
                $("#fileName").html(filename)
            }
        });
        $('.download_sample_file').bind('click', function(){
            window.location.href = $(this).attr('data-href');
        })

        // $('input[type="file"]').bind('change', function(){
        $('.save_as_draft').bind('click', function(){
            formdata = new FormData($('.importData')[0]);
            if( $('input[name="imported_file"]').val() == '' ){
                HTML = '<div class="alert alert-danger">\n' +
                    '<strong>Warning! </strong>Please upload a file.</div>'
                $(".MSG").html(HTML);
                return false;
            }
            $.ajax({
                url: "{{ route('uploadFile') }}",
                type: "POST",
                data: formdata,
                processData: false,
                contentType: false,
                success: function (res) {
                    window.location.href ='{{ route("importFileView") }}';
                },
                error: function(res){
                    HTML = '<div class="alert alert-danger">\n' +
                        '<strong>Warning!</strong>'+res.responseText+'</div>'
                    $(".MSG").html(HTML);
                }
            });
        });
    });
</script>
@stop
