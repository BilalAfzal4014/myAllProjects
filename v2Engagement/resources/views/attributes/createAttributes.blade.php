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

    input:read-only {
        background: #ebebe4;
        cursor: no-drop;
    }

    input[type=number]::-webkit-inner-spin-button,
    input[type=number]::-webkit-outer-spin-button {
        -webkit-appearance: none;
    }

    input[type=number] {
        -moz-appearance: textfield;
    }
</style>
<div id="campaignOtherAttributedata" class="modal fade" tabindex="-1" role="dialog">

    <div class="modal-dialog" role="document">
        <div class="ajax_call_loader" style="display: none;">
            <img src="{{asset('assets/images/loader_ajax.gif')}}">
        </div>
        <div class="modal-content">
            <div class="modal-body">
                <h3>Attributes</h3>
                <div class="row">
                    <div class="col-sm-12">
                        <form class="edit-user-form clearfix" id="attributeForm" action="{{route('attribute.submit')}}"
                              method="post">
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                            <input type="hidden" id="attributeId" value="{{$attributdata['id']}}" name="attributeId">
                            <label for="">
                                <strong> Name: </strong>
                                <input required style="color: #000;" id="name" name="name" class="specialChar"
                                       value="{{$attributdata['name']}}" placeholder="" type="text">
                                <span id="nameError" style="color: #F99; position: relative; "></span>
                            </label>
                            <label for="">
                                <strong> Code: </strong>
                                <input required style="color: #000;" class="has-error" id="code" name="code"
                                       value="{{$attributdata['code']}}" placeholder="" type="text"
                                       readonly>
                                <span id="codeError" style="color: #F99; position: relative;"></span>
                            </label>
                            <label for="">
                                <strong> Data Type: </strong>
                                <select id="data_type" name="data_type" class="form-control" required>

                                    <option value="">Select Data Type</option>
                                    @for($val=0;$val<count($datatype);$val++)
                                        @if($attributdata['data_type']==$datatype[$val])
                                            <option value="{{$datatype[$val]}}" selected>{{$datatype[$val]}}</option>
                                        @else
                                            <option value="{{$datatype[$val]}}">{{$datatype[$val]}}</option>
                                        @endif
                                    @endfor
                                </select>
                                <span id="dataTypeError" style="color: #F99; position: relative;"></span>
                            </label>
                            <label for="" class="datatypeLength" style="display: none;">
                                <strong> Length: </strong>
                                <input type="text" id="length" maxlength="3" name="length"
                                       value="{{$attributdata['length']}}"/>
                                <span id="lengthError" style="color: #F99; position: relative;"></span>
                            </label>

                            <label for="">
                                <button class="sub_btn" type="button" name="button">Add</button>
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
        var regex;
        var attributdata = "{{$attributdata['data_type']}}";
        if (attributdata != "") {
            if (attributdata == 'VARCHAR') {
                $(".datatypeLength").css('display', 'block');
            }

        }
        var attributeId = $("#attributeId").val();
        var nameError = $("#nameError");
        console.log('attributeId', attributeId);
        if (attributeId == "") {
            $("#name").keyup(function (event) {
                nameError.html('');
                var stt = $(this).val();
                regex = /^[A-Za-z_][A-Za-z\d_]*$/;
                if (regex.test(stt)) {
                    var underScoreString = stt.split(' ').join('_');
                    console.log('val', underScoreString.toLowerCase());
                    $('#code').val(underScoreString.toLowerCase());
                } else {
                    $('#code').val('');
                    nameError.html("Please enter only alphanumeric");
                    return false;
                }
            });
        }
        var dtype;
        $("#data_type").on('change', function () {
            dtype = $(this).val();
            if (dtype == "VARCHAR") {
                $(".datatypeLength").css('display', 'block');
            } else {
                $(".datatypeLength").css('display', 'none');
            }
        });
        var validCode = false;
        var validName = false;
        var validDataType = false;
        var validDataLength = false;
        $(".sub_btn").on("click", function (e) {


            var code = $("#code");
            var name = $("#name");
            var data_type = $("#data_type");
            var length = $("#length");

            var codeError = $("#codeError");
            var nameError = $("#nameError");
            var dataTypeError = $("#dataTypeError");
            var lengthError = $("#lengthError");

            var editLookUpID = $("#attributeId");
            var objectId = null;
            if (editLookUpID.val()) {
                objectId = editLookUpID.val();
            }
            if (code.val().length > 0) {
                if (attributeId == null) {
                    $.ajax({
                        type: 'GET',
                        async: false,
                        url: baseUrl + '/attributes/duplication/check/' + code.val(),
                        dataType: 'json',
                        success: function (response) {
                            console.log('response', response);
                            if (response.status == '200') {
                                toastr.error(response.message);
                                codeError.html(response.message);
                            } else {
                                validCode = true;
                                codeError.html("");
                            }
                        },
                        error: function (e) {
                            toastr.error(e);
                        },
                    });

                } else {
                    validCode = true;
                }
                console.log('validCode', validCode);
            }
            else {
                codeError.html("This field is required");
                codeError = false;
            }
            if (name.val().length > 0) {
                regex = /^[A-Za-z_][A-Za-z\d_]*$/;
                console.log('name',name.val());
                if (regex.test(name.val())) {
                    validName = true;
                    nameError.html("");
                } else {
                    $('#code').val('');
                    nameError.html("Please enter only alphanumeric");
                    return false;
                }
            } else {
                nameError.html("This field is required");
            }
            if (data_type.val().length > 0) {
                validDataType = true;
                dataTypeError.html("");
            } else {
                dataTypeError.html("This field is required");
            }
            if (length.val().length > 0) {
                validDataLength = true;
                lengthError.html("");
            } else {
                lengthError.html("This field is required");
            }
            console.log('validCode', validCode);
            console.log('validName', validName);
            console.log('validDataType', validDataType);
            if (validCode && validName && validDataType) {
                $("#attributeForm").submit();
            }
        });
    });
</script>