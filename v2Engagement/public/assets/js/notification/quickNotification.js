$(document).ready(function () {

    multiSelect();
    getQuickNotificationData();
    events();

    function multiSelect() {
        $('#userSelect').multiselect({
            enableClickableOptGroups: true,
            enableCollapsibleOptGroups: true,
            enableFiltering: true,
            includeSelectAllOption: true
        });
    }

    function events() {
        $("#appNameSelect, #platformSelect, #versionSelect, #buildSelect").change(function () {
            console.log('call', $(this).val());
            rePopulateSelections(this, $(this).val());
        });
        $("#company").change(function () {
            var id = $(this).val();
            console.log('call', $(this).val());

            var url = baseUrl + '/notification/getCompanyKey/' + id;
            $.ajax({
                type: "GET",
                global: false,
                url: url,
                dataType: 'json',
                success: function (response) {
                    //console.log('reponse',response.data['company_key']);
                    $(".companyKey").val(response.data['company_key']);
                },
                error: function (e) {
                    //alert("error");
                    console.log('companyError', e);
                }

            });


            $(".companyId").val($(this).val());
            getNotificationData($(this).val());
        });

        $("#quickNotificationBtn").click(function () {
            // alert('call');
            //console.log('isSilent',$("#isSilent").prop("checked"));
            console.log('companyKey', $(".companyKey").val());
            console.log('companyName', $("#companyName").val());
            var companyName = $("#companyName").val();
            if (!validation()) {
                var obj = {
                    company_key: $(".companyKey").val(),
                    //filter_type: "row_id",
                    //filter_type: "user_id",
                    //items: $("#userSelect").val(),
                    row_id: $("#userSelect").val(),
                    message: $("#inappMessage").val(),
                    platform: $("#platformSelect").val(),
                    type: $("#notificationSelect").val()
                };
                if (companyName == "superadmin") {
                    obj.is_silent = $("#isSilent").prop("checked");
                }
                console.log('obj', obj);
                if (obj.type == 'push' && $("#sandBoxSelection").prop("checked")) {
                    obj.is_test_device = true;
                }
                else if (obj.type != 'push') {
                    obj.custom = {
                        message_type: "full screen",
                        message_position: "",
                    };
                }
                testPreview(obj);
            }

        });
    }

    function getNotificationData(id) {
        showLoader();
        var url = baseUrl + '/notification/getQuickNotificationData/' + id;
        $.ajax({
            type: "GET",
            global: false,
            url: url,
            dataType: 'json',
            success: function (response) {
                console.log('companyResponse', response);
                populateSelect(response.data.appName, '#appNameSelect');
                populateSelect(response.data.platForms, '#platformSelect');
                populateSelect(response.data.version, '#versionSelect');
                populateSelect(response.data.build, '#buildSelect');
                populateSelectMulti(response.data.user, '#userSelect');
                $('#userSelect').multiselect('rebuild');
                hideLoader();
            },
            error: function () {
                //alert("error");
            }

        });
    }

    function getQuickNotificationData() {
        showLoader();
        var url = baseUrl + '/notification/getQuickNotificationData/' + $(".companyId").val();
        $.ajax({
            type: "GET",
            global: false,
            url: url,
            dataType: 'json',
            success: function (response) {
                console.log('onLoad', response);
                populateSelect(response.data.appName, '#appNameSelect');
                populateSelect(response.data.platForms, '#platformSelect');
                populateSelect(response.data.version, '#versionSelect');
                populateSelect(response.data.build, '#buildSelect');
                populateSelectMulti(response.data.user, '#userSelect');
                $('#userSelect').multiselect('rebuild');
                hideLoader();
            },
            error: function () {
                //alert("error");
            }

        });
    }

    function populateSelect(data, id) {
        var str = '';
        for (var i = 0; i < data.length; i++) {
            str += '<option value="' + data[i] + '">' + data[i] + '</option>';
        }
        $(id).html(str);
    }

    function populateSelectMulti(data, id) {
        var str = '';
        for (var i = 0; i < data.length; i++) {
            str += '<option value="' + data[i].id + '">' + data[i].value + '</option>';
        }
        $(id).html(str);
    }

    function rePopulateSelections(ele) {
        showLoader();
        var step = $(ele).attr('step');
        var url = baseUrl;
        console.log('companyid', $(".companyId").val());
        console.log('appNameSelect', $("#appNameSelect").val());
        console.log('platformSelect', $("#platformSelect").val());
        console.log('versionSelect', $("#versionSelect").val());
        console.log('buildSelect', $("#buildSelect").val());
        if (step == 1) {
            url += '/notification/getQuickNotificationDataOnSelection/' + $(".companyId").val() + '/' + step + '/' + $("#appNameSelect").val();
        }
        else if (step == 2) {
            url += '/notification/getQuickNotificationDataOnSelection/' + $(".companyId").val() + '/' + step + '/' + $("#appNameSelect").val() + '/' + $("#platformSelect").val();
        }
        else if (step == 3) {
            url += '/notification/getQuickNotificationDataOnSelection/' + $(".companyId").val() + '/' + step + '/' + $("#appNameSelect").val() + '/' + $("#platformSelect").val() + '/' + $("#versionSelect").val();
        }
        else {
            url += '/notification/getQuickNotificationDataOnSelection/' + $(".companyId").val() + '/' + step + '/' + $("#appNameSelect").val() + '/' + $("#platformSelect").val() + '/' + $("#versionSelect").val() + '/' + $("#buildSelect").val();
        }
        console.log('url',url);
        $.ajax({
            type: "GET",
            global: false,
            url: url,
            dataType: 'json',
            success: function (response) {
                console.log('final', response);
                if(response.data!=null)
                {
                    if (step == 1) {
                        populateSelect(response.data.platForms, '#platformSelect');
                        populateSelect(response.data.version, '#versionSelect');
                        populateSelect(response.data.build, '#buildSelect');
                        populateSelectMulti(response.data.user, '#userSelect');
                    }
                    else if (step == 2) {
                        populateSelect(response.data.version, '#versionSelect');
                        populateSelect(response.data.build, '#buildSelect');
                        populateSelectMulti(response.data.user, '#userSelect');
                    }
                    else if (step == 3) {
                        populateSelect(response.data.build, '#buildSelect');
                        populateSelectMulti(response.data.user, '#userSelect');
                    }
                    else {
                        populateSelectMulti(response.data.user, '#userSelect');
                    }
                    $('#userSelect').multiselect('rebuild');
                    hideLoader();
                }else{
                    //toastr.error('No List Found ');
                }

            },
            error: function () {
                //alert("error");
            }

        });
    }

    function testPreview(obj) {
        var url = baseUrl + '/api/v1/message/send';
        $.ajax({
            type: 'POST',
            global: false,
            url: url,
            data: obj,
            dataType: 'json',
            success: function (response) {
                console.log('SendNotificaitonRes',response);
                if (response.meta.status.toLowerCase() == "error") {
                    for (var i = 0; i < response.errors.length; i++) {
                        toastr.error(response.errors[i]);
                    }
                }
                else {
                    for (var j = 0; j < response.data.length; j++) {
                        toastr.success(response.data[j].message);
                    }
                }
            },error: function (jqXHR, textStatus, errorThrown) {
                var response = jqXHR.responseJSON;
                if (response.meta.status.toLowerCase() == "error") {
                    for (var i = 0; i < response.errors.length; i++) {
                        toastr.error(response.errors[i]);
                    }
                }
            }
        });
    }

    function validation() {
        var error = false;
        if ($("#inappMessage").val() == '') {
            $("#notificationError").text("Please Enter Message");
            error = true;
        }
        else {
            $("#notificationError").text("");
        }

        if ($("#userSelect").val() == null) {
            $("#userError").text("Please select user(s)");
            error = true;
        }
        else {
            $("#userError").text("");
        }
        return error;
    }
});