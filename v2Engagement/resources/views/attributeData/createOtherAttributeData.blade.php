<style>
    .ajax_call_loader {
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        z-index: 5;
        text-align: center;
        background: rgba(0, 0, 0, 0.1);
    }

    .ajax_call_loader img {
        width: 40px;
        position: absolute;
        top: 50%;
        left: 50%;
        margin: -60px 0 0 -40px;
    }

    .modal-dialog {
        position: relative;
    }
</style>
<div id="campaignOtherAttributedata" class="modal fade" tabindex="-1" role="dialog">

    <div class="modal-dialog" role="document">
        <div class="ajax_call_loader" style="display: none;">
            <img src="{{asset('assets/images/loader_ajax.gif')}}">
        </div>
        <div class="modal-content">
            <div class="modal-body">
                <div class="row">
                    <div class="col-sm-12">
                        <form class="edit-user-form clearfix" id="lookupForm"
                              action="{{url('/saveOtherAttribute')}}"
                              method="post">
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                            <label for="">
                                <strong> Action Type: </strong>
                                <select id="actionType" name="actionType" class="form-control" required>
                                    @if($attributeobj['action']!="")
                                        @if($attributeobj['action']=='action')
                                            <option value="0" selected>ACTION_TRIGGERS</option>
                                            <option value="1">CONVERSION_TYPES</option>
                                        @else
                                            <option value="0">ACTION_TRIGGERS</option>
                                            <option value="1" selected>CONVERSION_TYPES</option>
                                        @endif
                                    @else
                                        <option value="">Select Type</option>
                                        <option value="0">ACTION_TRIGGERS</option>
                                        <option value="1">CONVERSION_TYPES</option>
                                    @endif

                                </select>
                                <input type="hidden" id="attributeid" name="attributeid"
                                       value="{{$attributeobj['id']}}"/>
                            </label>
                            <label>
                                <strong> Code: </strong>
                                <select id="code" name="code" class="form-control" required>
                                    <option value="">Select Code</option>
                                </select>
                            </label>
                            <label for="">
                                <strong> Value: </strong>
                                <input style="color: #000;" id="value" name="value" class="specialChar"
                                       value="{{$attributeobj['value']}}" placeholder="" type="text">
                                <span id="nameError" style="color: #F99; position: relative; "></span>
                            </label>
                            <label for="">
                                <button class="sub_btn" type="submit" name="button">Add</button>
                            </label>
                        </form>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-12 text-right close_btn_holder">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    $(document).ready(function () {

        var selectoptionval;
        var codeval = '';
        var otherAttributeobj = "{{$attributeobj['code']}}";
        var actionValue = "{{$attributeobj['action']}}";
        if (otherAttributeobj != "") {
            if (actionValue == 'action') {
                codeval = '0';
            } else {
                codeval = '1';
            }
            getCodeLisiting(codeval);
        }
        console.log('otherAttributeobj', otherAttributeobj);
        $(document).on('change', '#actionType', function (e) {
            codeval = $(this).val();
            $(".ajax_call_loader").css('display', 'block');
            getCodeLisiting(codeval);
        });

        function getCodeLisiting(codeval) {
            $("#code").html("");
            console.log('codeval', codeval);
            $(".ajax_call_loader").css('display', 'block');
            /*return;*/
            $.ajax({
                type: 'GET',
                async: false,
                url: baseUrl + '/lookupCodeListing/' + codeval,
                dataType: 'json',
                success: function (response) {
                    console.log('response', response);
                    selectoptionval = null;
                    if (response.status == 200) {
                        for (var val = 0; val < response.data.length; val++) {
                            if (otherAttributeobj == response.data[val]['code']) {
                                console.log('matched');
                                selectoptionval += '<option value="' + response.data[val]['code'] + '" selected>' + response.data[val]['code'] + '</option>';
                            } else {
                                selectoptionval += '<option value="' + response.data[val]['code'] + '">' + response.data[val]['code'] + '</option>';
                            }
                        }
                        $("#code").append('<option value="">' + 'Select Code' + '</option>' + selectoptionval);
                    } else {
                        toastr.error('Please Add Lookup First');
                    }

                    /*setTimeout(function () {*/
                        $(".ajax_call_loader").css('display', 'none');
                    /*}, 4000);*/

                }, error: function (e) {
                    //  toastr.error(e);
                }
            });
        }
    });
</script>