var counter = 0;
$(document).ready(function () {
    var capping_unit = ['days', 'minutes', 'weeks'];
    var campaignType = ['Push', 'InApp', 'Email'];

    getCappingRules();
    events();

    function events() {
        $("#submitCapping").click(function () {
            //if (!validation())
            swal({
                title: "Are you sure?",
                text: "Campaign Cap rules will save globally and affect all campaigns",
                buttons: true,
                dangerMode: true,
            }).then((willDelete) => {
                if (willDelete) {
                    saveRules();
                }
            });
        });

        $("#cappingMultiSelectLi").change(function () {
            if (counter < 3)
                addRule();
            $("#cappingError").text("");
            $("#cappingMultiSelectLi option[value='-1']").prop('selected', true);
        });
    }

    function addRule(obj = "") {
        var str = '<li class="true">';
        str += '<div class="text_del_outer clearfix">';
        str += '<div style="padding: 5px 0px;" class="Campaigns_input top_left_inp b_r">';
        str += '<label style="margin-left: 13px; display: inline-block;">Send no more than </label>';

        if (obj == "")
            str += '<input style="width: 5% !important;" class="cappingLimit actionInput" type="number" min="0" name="" value="0" >';
        else
            str += '<input style="width: 5% !important;" class="cappingLimit actionInput" type="number" min="0" name="" value="' + obj.cappingLimit + '" >';

        str += '<select class="campaignType actionList">';
        for (var i = 0; i < campaignType.length; i++) {
            if (obj == "")
                str += '<option value="' + campaignType[i] + '">' + campaignType[i] + '</option>';
            else {
                if (obj.campaignType != campaignType[i])
                    str += '<option value="' + campaignType[i] + '">' + campaignType[i] + '</option>';
                else
                    str += '<option selected value="' + campaignType[i] + '">' + campaignType[i] + '</option>';
            }
        }
        str += '</select>';

        str += '<label style="display: inline-block;">to a user every </label>';

        if (obj == "")
            str += '<input style="width: 5% !important;" class="durationValue actionInput" type="number" min="0" name="" value="0" >';
        else
            str += '<input style="width: 5% !important;" class="durationValue actionInput" type="number" min="0" name="" value="' + obj.durationValue + '" >';


        str += '<select class="actionList durationUnit">';
        for (var i = 0; i < capping_unit.length; i++) {

            if (obj == "")
                str += '<option value="' + capping_unit[i] + '">' + capping_unit[i] + '</option>';
            else {
                if (obj.durationUnit != capping_unit[i])
                    str += '<option value="' + capping_unit[i] + '">' + capping_unit[i] + '</option>';
                else
                    str += '<option selected value="' + capping_unit[i] + '">' + capping_unit[i] + '</option>';
            }
        }

        str += '</select>';
        str += '</div>';
        str += '<span style="top: 7px" id="App Launch_del" onclick="clearRule(this)"><i></i></span>';
        str += '</div>';
        str += '</li>';
        str += '<li class="clearfix">';
        str += '<div class="togggle_btn_reach_user">';
        str += '<span class=" and_or_toggle_btn active">AND</span>';
        str += '</div>';
        str += '</li>';
        $(str).insertBefore("#cappingMultiSelectLi");
        counter++;
    }

    function getCappingRules() {
        var url = baseUrl + "/backend/get/campaign/capping-settings/" + $(".companyId").val();
        $.ajax({
            type: "get",
            url,
            dataType: "json",
            success: function (response) {
                populateRules(response.data);
            },
            error: function () {

            }
        });
    }

    function populateRules(data) {
        for (var i = 0; i < data.length; i++) {
            addRule(data[i]);
        }
    }

    function saveRules() {
        var url = baseUrl + "/backend/submit-capping-rules/" + $(".companyId").val();
        $.ajax({
            type: "post",
            url,
            data: {
                rules: getRules(),
            },
            dataType: "json",
            success: function (response) {
                toastr.success(response.message);
            },
            error: function () {

            }
        });
    }

    function getRules() {
        var arr = [];
        $("#cappingCollection").children().each(function () {
            if ($(this).hasClass("true")) {
                var parent = $(this).children().eq(0).children().eq(0);

                for (var i = 0; i < arr.length; i++) {
                    if (arr[i].campaignType == parent.children(".campaignType").val()) {
                        break;
                    }
                }
                if (i >= arr.length) {
                    var obj = {
                        cappingLimit: parent.children(".cappingLimit").val(),
                        campaignType: parent.children(".campaignType").val(),
                        durationValue: parent.children(".durationValue").val(),
                        durationUnit: parent.children(".durationUnit").val(),
                    };
                    arr.push(obj);
                }
            }
        });
        return arr;
    }

    function validation() {
        var errors = false;
        if (counter == 0) {
            $("#cappingError").text("Please select campaign capping rules");
            errors = true;
        } else {
            $("#cappingError").text("");
        }
        return errors;
    }
});

function clearRule(element) {
    counter--;
    $(element).parentsUntil('ul').each(function () {

        if ($(this).prop("tagName") == "LI") {
            var nextLi = $(this).next();
            $(this).remove();
            nextLi.remove();
        }
    });
}