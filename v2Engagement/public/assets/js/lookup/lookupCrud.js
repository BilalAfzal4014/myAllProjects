$(document).ready(function () {
    var regex = /^[A-Za-z_ ][A-Za-z\d_ ]*$/;
    var edit_lookup_id = $("#edit_lookup_id").val();
    var nameError = $("#nameError");
    console.log('edit_lookup_id', edit_lookup_id);
    if (edit_lookup_id == null) {
        $("#name").keyup(function (event) {
            nameError.html('');
            var stt = $(this).val();
            if (regex.test(stt)) {
                var underScoreString = stt.split(' ').join('_');
                console.log('val', underScoreString.toUpperCase());
                $('#code').val(underScoreString.toUpperCase());
            } else {
                $('#code').val('');
                nameError.html("Please Enter Only Alphanumeric");
                return false;
            }
        });
    }
    var validCode = false;
    var validName = false;
    var validDesc = false;
    var validParent = false;
    $(".sub_btn").on("click", function (e) {


        var code = $("#code");
        var name = $("#name");
        var parentid = $("#parent_id");
        var inappMessage = $("#inappMessage");

        var codeError = $("#codeError");
        var nameError = $("#nameError");
        var parentError = $("#parentError");
        var inappMessageError = $("#descriptionError");


        var editLookUpID = $("#edit_lookup_id");
        var objectId = null;
        if (editLookUpID.val()) {
            objectId = editLookUpID.val();
        }
        if (code.val().length > 0) {
            $.ajax({
                type: 'GET',
                async: false,
                url: baseUrl + '/lookup/check_duplication?code=' + code.val() + '&id=' + objectId,
                dataType: 'json',
                success: function (data) {
                    console.log('data', data);
                    if (data.error === true) {
                        codeError.html(data.msg);
                    } else {
                        validCode = true;
                        codeError.html("");
                    }
                },
                error: function (e) {
                    console.log('error', e);
                },
            });
        } else {
            codeError.html("This field is required");
            codeError = false;
        }

        if (name.val().length > 0) {
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

        if (parentid.val() != "") {
            validParent = true;
            inappMessageError.html("");
        } else {
            parentError.html("This field is required");
        }
        if (validCode && validName && validParent) {

            $("#lookupForm").submit();
        }
    });
});