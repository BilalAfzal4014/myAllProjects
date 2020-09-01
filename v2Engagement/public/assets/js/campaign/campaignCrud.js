var segmentArray = [];
var typeOfCampaign = 'email';
var element = '';
var step = "#step1";
var galleryImg = '';
var scrollLock = false;
var tagName = 'TEXTAREA';
var currentLanguage = 'en';
var langArrSaveContent = [];
var conversionListCount = 0;

/*$(window).load(function () {
    var width = $("#step2MainContainer").width() - ($("#step2Container").width() + 30);
    $("#step2SecondContainer").css({width: width + "px"});
});*/

$(document).ready(function () {
    var activeClassId = -1;
    var editor;
    var editorPreviewInstance;
    var editorSituation = 'create';
    var nextBtn = "CreateMode";
    var launchPressed = false;
    var currentInAppTemplate = 'Dialogue';
    var inAppTemplates = [];
    var campaignActionsArr = [];
    var campaignConversionArr = [];
    var campaignStatus = 'create';
    var FromAttribute = 'attribute';
    var pressed = {
        step1: false,
        step2: false,
        step3: false,
        step4: false,
        step5: false,
    };
    var sendTestEmailPressed = false;


    const image = document.getElementById('cropImage');
    const cropper = new Cropper(image, {
        responsive: false,
        background: false,
        modal: false,
        checkImageOrigin: false,
        crop: function (e) {
            var data = e.detail;
            $(".cropper_height_width").children().eq(0).text("Height: " + Math.round(data.height) + "px");
            $(".cropper_height_width").children().eq(1).text("Width: " + Math.round(data.width) + "px");
        }

    });

    $(document).on("click", ".selectImage", function () {

        var ImageUrl = $(this).attr("data-image-name");
        if (typeOfCampaign == 'email') {
            var imageTag = '<image src="' + ImageUrl + '"/>';
            CKEDITOR.instances['editor'].insertHtml(imageTag);
            $('#exampleModalCenter').modal('hide');
            $("#cropImageSelected").modal('hide');
        } else {


            checkImageRequirement(ImageUrl).then((response) => {
                if (!response) {
                    $(element).parent().css({background: "none"});
                    if ($(element).parent().prop("tagName") == "LABEL") {
                        $(element).parent().parent().css({background: "none"});
                    }
                    $(element).parent().children('img').attr("src", ImageUrl);
                    element = '';
                    $("#cropImageSelected").modal('hide');
                    $('#exampleModalCenter').modal('hide');
                }
            });

        }
        $("#uploadError").text("");
    });

    $(document).on("click", ".cropImage", function () {

        var image_url = $(this).attr("data-image-name");
        $("#crop_info").css({display: "inline-block"});
        $("#cropImageSelected").modal("show");
        cropper.clear();
        cropper.replace(image_url);
        $('#exampleModalCenter').modal('hide');
    });

    $(document).on("click", "#cancelCaropButton", function () {


        swal({
            title: "Do you want to discard save changes?",
            //icon: "warning",
            buttons: {
                no: "No",
                yes: "Yes",
            },
            dangerMode: true,
        }).then((value) => {

            switch (value) {

                case "yes":

                    cropper.clear();
                    $("#exampleModalCenter").modal('hide');
                    $("#cropImageSelected").modal("hide");
                    break;

                case "no":

                    return false;
                // break;
                default:
                    swal("Wrong Choice!");
            }
        });
        // $('#gallery').DataTable().draw();
    });

    $("#cropButton").on("click", function () {

        cropper.getCroppedCanvas().toBlob((blob) => {
            const formData = new FormData();

            formData.append('croppedImage', blob);

            $(".fa-spin").css({display: "inline-block"});
            // Use `jQuery.ajax` method
            $.ajax(baseUrl + "/gallery/crop", {
                method: "POST",
                data: formData,
                processData: false,
                global: false,
                contentType: false,
                success(data) {
                    $(".fa-spin").css({display: "none"});
                    if (data.status == 200) {

                        var ImageUrl = data.image_url;
                        if (typeOfCampaign == 'email') {
                            var imageTag = '<image src="' + ImageUrl + '"/>';
                            CKEDITOR.instances['editor'].insertHtml(imageTag);
                            $("#cropImageSelected").modal('hide');
                        } else {
                            checkImageRequirement(ImageUrl).then((response) => {
                                if (!response) {
                                    $(element).parent().css({background: "none"});
                                    if ($(element).parent().prop("tagName") == "LABEL") {
                                        $(element).parent().parent().css({background: "none"});
                                    }
                                    $(element).parent().children('img').attr("src", ImageUrl);
                                    element = '';
                                    $("#cropImageSelected").modal('hide');
                                }
                            });
                        }
                        $("#uploadError").text("");

                        toastr.success("image successfully cropped and save");
                        $('#gallery').DataTable().draw();
                    }

                },
                error() {
                    $(".fa-spin").css({display: "none"});
                },
            });
        });
    });

    getGalleryData();
    fetchPreLoadData();
    tagsInputStep1();
    events();
    multiSelect();
    validationReactiveNess();


    function validationReactiveNess() {

        $("#emailTestUsers, #subjectTestUsers").keyup(function () {
            if (sendTestEmailPressed)
                validateEmailTestUsers();
        });

        $("#userSelectEmail").change(function () {
            if (sendTestEmailPressed)
                validateEmailTestUsers();
        });

        $("#campaignTitle, #email, #name, #subject").keyup(function () {
            if (pressed.step1)
                validateStep1();
        });

        $("#actionInput1, #actionInput2, #inappMessage, #inappType").keyup(function () {
            if (pressed.step2)
                validateStep2();
        });

        $(document).on("change", ".multiselect-container li a label input[type=checkbox]", function () {
            validateEmailTestUsers();
        });

        $(document).on("change", ".multiselect-container li a label input[type=checkbox]", function () {
            validatePushInAppUsers();
        });

        $(".shour select, .smint select, .ehour select, .emint select, .campaignRepitition select, .campaignAll select, #startDate, #endDate").change(function () {
            if (pressed.step3)
                validateStep3();
        });

        $(document).on("click", ".text_del_outer span", function () {
            if (pressed.step4)
                validateStep4();
        });
    }

    function getGalleryData() {
        var url = baseUrl + '/gallery/fetch';
        $('#gallery').DataTable({
            "processing": true,
            "serverSide": true,
            "searching": true,
            /*"bLengthChange": false,*/
            "iDisplayLength": 25,
            "ajax": {
                "url": url,
                "dataType": "json",
                "type": "GET",
                "global": false,
            },
            "columns": [
                {"data": "url"},
                {"data": "name"},
                {"data": "dimensions"},
                {"data": "size"},
                {"data": "date"},
                {"data": "action"},
            ],
            "aoColumnDefs": [
                {
                    "aTargets": [0],
                    "mData": "url",
                    "mRender": function (data, type, full) {
                        return '<img src="' + full.url + '" alt="' + full.name + '" style="margin: 0 auto;width: 100px;">';
                    },
                    "visible": true,
                    "searchable": false,
                    "orderable": false
                },
                {
                    "aTargets": [5],
                    "mData": "action",
                    "mRender": function (data, type, full) {
                        return '<div class="links_crop_select"><a href="javascript:void(0)" class="btn btn-info selectImage" style="color: white" data-image-name="' + full.url + '"><img src="' + baseUrl + '/assets/images/Select.png" ></a><a href="javascript:void(0)" class="btn btn-success cropImage" data-image-name="' + full.url + '" style="color: white"><img src="' + baseUrl + '/assets/images/Crop.png" ></a></div>';
                    },
                    "visible": true,
                    "searchable": false,
                    "orderable": false
                },
                {
                    "aTargets": [4],
                    "mData": "created_at",
                    "visible": false,
                    "searchable": false,
                    "orderable": false
                }
            ],
            "order": [[3, "desc"]],
            "initComplete": function (settings, json) {
                // call after loaded only first time
            },
            "createdRow": function (row, data, dataIndex) {

            },
            "drawCallback": function (settings, json) {
                //call after every event cause change in datatable
                /*var description = $("#campaignListing_info").text();
                $("#campaignListing_info").text("");
                $(".listing_sec_ftr_detail p").text(description);*/
            }

        });

        /*oTable = $('#campaignListing').DataTable();
        $('#searchBar').on("change", function () {
            oTable.search($(this).val()).draw();
        });*/
    }

    function multiSelect() {
        $('#userSelect').select2({
            ajax: {
                global: false,
                url: baseUrl + '/backend/campaign/getTestUsers',
                dataType: 'json',
                data: function (params) {
                    return {
                        companyId: $(".companyId").val(),
                        searchStr: params.term,
                        campaignType: 'notEmail',
                        deviceType: $("#plateForm").val(),
                    }
                }
            },
            minimumInputLength: 3,
            placeholder: 'Select User(s)',
            width: '100%',
            multiple: true,
        });

        $('#userSelectEmail').select2({
            ajax: {
                global: false,
                url: baseUrl + '/backend/campaign/getTestUsers',
                dataType: 'json',
                data: function (params) {
                    return {
                        companyId: $(".companyId").val(),
                        searchStr: params.term,
                        campaignType: 'email',
                        deviceType: $("#plateForm").val(),
                    }
                }
            },
            minimumInputLength: 3,
            placeholder: 'Select User(s)',
            width: '100%',
            multiple: true,
        });

        $('.multiSelect').select2({
            ajax: {
                global: false,
                url: baseUrl + '/backend/campaign/getSegments',
                dataType: 'json',
                data: function (params) {
                    return {
                        companyId: $(".companyId").val(),
                        searchStr: params.term,
                    }
                }
            },
            minimumInputLength: 3,
            placeholder: 'Select Segment',
            width: '100%',
            multiple: true,
        });
    }

    function tagsInputStep1() {
        $('.tags').tagsinput({
            allowDuplicates: false,
            maxChars: 15
        });

        $(".tags").on('itemAdded', function (event) {

            $(".tags").parent().children().filter('.bootstrap-tagsinput').find('input').attr('placeholder', '');

            if (/\s/.test(event.item)) {
                $('.tags').tagsinput('remove', event.item);
            }
        });

        $(".tags").on('itemRemoved', function (event) {
            if ($(".tags").val() == '') {
                $(".tags").parent().children().filter('.bootstrap-tagsinput').find('input').attr('placeholder', 'Enter Tag(s)');
            }
        });

        $(".tags").on('beforeItemAdd', function (event) {
            var tag = event.item;

            if (/\s/.test(tag)) {
                if (!event.options || !event.options.preventPost) {
                    $(".tags").tagsinput('add', tag.replace(/\s/g, '_'), {preventPost: true});
                }
            }
        });
    }

    function fetchPreLoadData() {
        showLoader();
        var url = baseUrl + '/backend/campaign/preData/' + $(".companyId").val();

        if ($("#campaignAction").val() != '') {
            url += '/' + $("#campaignEditionId").val();
        } else {
            url += '/-1';
        }

        $.ajax({
            global: false,
            type: 'GET',
            url: url,
            dataType: 'json',
            success: function (response) {
                populateTemplate(response.data.template);
                populateAction(response.data.campaignAction);
                populateCampaignTemplate(response.data.campaignTemplate);
                populateInAppData(response.data.inAppData);
                populateCampaignConversion(response.data.campaignConversion);
                populateApps(response.data.campaignApps);
                populateStartDateUtc();
                $(".campaignRepitition select").val("ONCE");
                $(".campaignRepitition select").trigger("change");

                $("#rec_comp").prop("checked", false);
                $("#rec_comp").trigger("change");

                $("#e2-rec_comp3").trigger("click");

                if ($("#campaignTypQuickAction").val() != '') {
                    $("#campaigns_type").val($("#campaignTypQuickAction").val());
                    $("#campaigns_type").trigger('change');
                }

                if ($("#campaignAction").val() != '') {
                    readCampaign($("#campaignEditionId").val(), $("#campaignAction").val(), response.data.readData);
                } else {
                    $("#campaigns_type").trigger('change');
                }
                hideLoader();
                $("#demo").addClass("auto_height");
            },
            error: function () {
                //alert("failure");
            },
        });
    }

    function events() {

        $("#cropper_mcsb").mCustomScrollbar({
            mouseWheel: {
                enable: false
            }
        });

        $("body").addClass('listing_ftr_hide');

        $("#campaignSegmentExport").click(function () {
            var url = baseUrl + '/backend/getusersagainstcampaign/' + $("#campaignCreationid").val();
            window.location.href = url;
        });

        $(".select_button a").click(function (e) {
            if ($(this).attr("href") == "#step_a" || $(this).attr("href") == "#step_c") {
                $("#delivery_control_html").css({display: "block"});
                $("#rec_comp").prop("disabled", false);
                $("#endTimeHide").css({display: "block"});
            } else {
                $(".campaignRepitition select").trigger("change");
            }
        });

        $("#actionTriggerInput, #deliveryInput").on('input', function (event) {
            if ($(this).val() > 999) {
                $(this).val(999);
            }
        });

        $(".shour select, .smint select").change(function () {
            var time = $(".shour select").val() + ':' + $(".smint select").val();
            var str = "<strong>Summary:</strong> Send Campaign immediately after trigger criteria are met, beginning at " + time + "UTC";
            $("#setScheduleTime").html(str);
        });

        $("#camp_code_copy").click(function () {
            var copyText = $("#camp_code");
            copyText.select();
            document.execCommand("copy");
        });

        $("#camp_curl_copy").click(function () {
            $("#camp_curl_textarea").css({display: "block"});
            var copyText = $("#camp_curl_textarea");
            copyText.select();
            document.execCommand("copy");
            $("#camp_curl_textarea").css({display: "none"});
        });

        $("#rec_comp").change(function () {
            updateDeliveryControl();
        });

        $(".opacity").change(function () {
            $(this).parent().children().filter('span').text("Value: " + $(this).val());
            setTemplatedesign();
        });

        $(".color_code").change(function () {
            setTemplatedesign();
        });

        $(".Conversion_event_outer").css({display: 'block'});

        $('#inappMessage, #inappType').focus(function () {
            tagName = $(this).prop('tagName');
        });

        $("body").click(function () {
            $(".con_event_dropdown ul").css({display: "none"});
        });

        $("#galleryUpload").change(function (e) {
            e.preventDefault();
            imgUploadValidation();
            galleryImg = e.target.files;
        });


        $("#galleryUploadBtn").click(function () {
            if (!imgUploadValidation())
                uploadGalleryImage(galleryImg);
        });

        $("#e2-rec_comp3").click(function () {
            if ($(this).prop("checked")) {
                $("#appendApps").children().find('input').each(function () {
                    $(this).prop("checked", true);
                });
            } else {
                $("#appendApps").children().find('input').each(function () {
                    $(this).prop("checked", false);
                });
            }
        });

        $("#plateForm").change(function () {
            $('#userSelect').val(null).trigger('change');
            $('#userSelectEmail').val(null).trigger('change');
        });

        $(document).on('click', "#appendApps .conversion_event_list .camp_timing_check_box input", function () {
            if (!$(this).prop("checked")) {
                $("#e2-rec_comp3").prop("checked", false);
            }
            checkForAllCheckedApps();
        });

        $("#emailTestPreview").click(function () {
            sendTestEmailPressed = true;
            if (!validateEmailTestUsers())
                getUserIdByEmail();
        });

        $("#backToListing").click(function () {
            window.location.href = baseUrl + '/backend/campaign/campaigns';
        });

        $("#attributeSelection").click(function () {
            if (FromAttribute == 'attribute') {
                FromAttribute = 'attributeData';
                $("#tableAttributeData").css({'display': 'table-row-group'});
                $("#tableAttribute").css({'display': 'none'});
            } else {
                FromAttribute = 'attribute';
                $("#tableAttributeData").css({'display': 'none'});
                $("#tableAttribute").css({'display': ' table-row-group'});
            }
        });


        $("#actionTypeId").change(function () {
            for (var i = 0; i < campaignActionsArr.length; i++) {
                if (campaignActionsArr[i].name == $("#actionTypeId").val()) {
                    populateSelect(campaignActionsArr[i].values, "#actionValueId");
                    break;
                }
            }
        });

        $("#sendPreview").click(function () {
            if (!validatePushInAppUsers() && !validateStep2()) {
                getUserIdByEmail();
            }
        });

        $("#actionInput1").on("input", function () {
            if ($("#messageType").val() != 'Banner') {
                $(".btm_btns").children().eq(0).attr('href', $("#actionInput1").val());
            } else {
                $("#divAction").parent().attr('href', $("#actionInput1").val());
            }
        });

        $("#actionInput2").on("input", function () {
            $(".btm_btns").children().eq(1).attr('href', $("#actionInput2").val());
        });

        $(document).on("click", '.blockAnchor', function (e) {
            e.preventDefault();
        });

        $(document).on("click", '.blockAnchor', function (e) {
            e.preventDefault();
        });

        $(document).on("click", '.blockAnchor', function (e) {
            e.preventDefault();
        });

        $("#campaigns_type").change(function () {
            langArrSaveContent = [];
            if ($(this).val() == 1) {
                typeOfCampaign = 'email';
            } else if ($(this).val() == 2) {
                typeOfCampaign = 'push';
                currentInAppTemplate = 'Push';
                populateInAppTemplate(inAppTemplates, '#loadInAppTemplate');
                setGUIRules();
            } else {
                typeOfCampaign = 'inApp';
                currentInAppTemplate = $("#messageType").val();
                populateInAppTemplate(inAppTemplates, '#loadInAppTemplate');
                setGUIRules();
            }
        });

        $("#action1").change(function () {
            setActions();
            if ($(this).val() == 'Close') {
                $("#actionInput1").prop('disabled', true);
            } else {
                $("#actionInput1").prop('disabled', false);
            }
        });

        $("#action2").change(function () {
            setActions();
            if ($(this).val() == 'Close') {
                $("#actionInput2").prop('disabled', true);
            } else {
                $("#actionInput2").prop('disabled', false);
            }
        });

        $("#inappType").on("input", function () {
            $("#headingT").text($(this).val());
        });

        $("#inappMessage").on("input", function () {
            $("#headingT").parent().children('p').text($(this).val());
        });

        $("#firstBtn").on("input", function () {
            $(".btm_btns").children().eq(0).text($(this).val());
        });

        $("#secondBtn").on("input", function () {
            if ($(this).val() == '') {
                $(".btm_btns").children().eq(1).css({display: 'none'});
                if ($("#action2").val() == 'http://closeme.engagement.com/') {
                    $("#actionInput2").val('');
                    $("#actionInput2").trigger("input");
                }
            } else {
                $(".btm_btns").children().eq(1).css({display: 'inline-block'});
            }
            $(".btm_btns").children().eq(1).text($(this).val());
        });

        $("#messageType").change(function () {
            langArrSaveContent = [];
            currentInAppTemplate = $(this).val();
            populateInAppTemplate(inAppTemplates, '#loadInAppTemplate');
            setGUIRules();
            setTemplatedesign();
            setLanguageIndentation($("#selectLangInAppPush").children('span.active').attr('data-lang'));
            if (pressed.step2)
                validateStep2();
        });

        $("#selectLang span").click(function () {

            if ($(this).attr('data-lang') != currentLanguage) {
                var obj = {
                    lang: currentLanguage,
                    content: CKEDITOR.instances['editor'].getData()
                };

                for (var i = 0; i < langArrSaveContent.length; i++) {
                    if (langArrSaveContent[i].lang == obj.lang) {
                        langArrSaveContent.splice(i, 1);
                    }
                }
                langArrSaveContent.push(obj);

                for (var i = 0; i < langArrSaveContent.length; i++) {
                    if (langArrSaveContent[i].lang == $(this).attr('data-lang')) {
                        loadEditorData(langArrSaveContent[i].lang, langArrSaveContent[i].content);
                        break;
                    }
                }
                if (i >= langArrSaveContent.length) {
                    checkIfTemplateExistInDb($(this).attr('data-lang'));
                }
            }

            currentLanguage = $(this).attr('data-lang');
        });

        $("#selectLangInAppPush span").click(function () {

            if ($("#campaigns_type").val() == 3) {
                if ($(this).attr('data-lang') != currentLanguage) {

                    var obj = {
                        language: currentLanguage,
                        templateInfo: {
                            template: $("#loadInAppTemplate").children().eq(1).prop('outerHTML'),
                            title: checkDisabled("#inappType"),
                            message: checkDisabled("#inappMessage"),
                            action1: {
                                label: checkDisabled("#firstBtn"),
                                type: $("#action1").val(),
                                value: checkDisabled("#actionInput1"),
                            },
                            action2: {
                                label: checkDisabled("#secondBtn"),
                                type: $("#action2").val(),
                                value: checkDisabled("#actionInput2"),
                            },
                            design: {
                                header: {
                                    headingColor: $("#headerTextColor").val(),
                                    contentColor: $("#contentTextColor").val()
                                },
                                button1: {
                                    color: $("#btn1BackgroundColor").val(),
                                    opacity: $("#btn1BackgroundOpacity").val(),
                                    textColor: $("#btn1textColor").val()
                                },
                                button2: {
                                    color: $("#btn2BackgroundColor").val(),
                                    opacity: $("#btn2BackgroundOpacity").val(),
                                    textColor: $("#btn2textColor").val()
                                },
                                background: {
                                    color: $("#backgroundColor").val(),
                                    opacity: $("#backgroundOpacity").val()
                                },
                                frame: {
                                    color: $("#frameColor").val(),
                                },
                            }
                        }
                    };

                    for (var i = 0; i < langArrSaveContent.length; i++) {
                        if (langArrSaveContent[i].language == obj.language) {
                            langArrSaveContent.splice(i, 1);
                        }
                    }
                    langArrSaveContent.push(obj);

                    for (var i = 0; i < langArrSaveContent.length; i++) {
                        if (langArrSaveContent[i].language == $(this).attr('data-lang')) {
                            populateInAppfromChangeLanguage(langArrSaveContent[i]);
                            break;
                        }
                    }
                    if (i >= langArrSaveContent.length) {
                        checkIfInAppPushTemplateExistInDb($(this).attr('data-lang'));
                    }
                }
            } else {
                if ($(this).attr('data-lang') != currentLanguage) {

                    var obj = {
                        language: currentLanguage,
                        templateInfo: {
                            template: $("#loadInAppTemplate").children().eq(1).prop('outerHTML'),
                            text: $("#headingT").parent().children().eq(1).text(),
                            title: checkDisabled("#inappType"),
                            message: checkDisabled("#inappMessage"),
                            action1: {
                                label: -1,
                                type: $("#action1").val(),
                                value: $("#actionInput1").val().trim(),
                            },
                            action2: {
                                label: -1,
                                type: -1,
                                value: -1,
                            },
                        }
                    };

                    for (var i = 0; i < langArrSaveContent.length; i++) {
                        if (langArrSaveContent[i].language == obj.language) {
                            langArrSaveContent.splice(i, 1);
                        }
                    }
                    langArrSaveContent.push(obj);

                    for (var i = 0; i < langArrSaveContent.length; i++) {
                        if (langArrSaveContent[i].language == $(this).attr('data-lang')) {
                            populatePushfromChangeLanguage(langArrSaveContent[i]);
                            break;
                        }
                    }
                    if (i >= langArrSaveContent.length) {
                        checkIfInAppPushTemplateExistInDb($(this).attr('data-lang'));
                    }
                }

            }
            currentLanguage = $(this).attr('data-lang');
        });

        $("#selectLangEmailPreview span").click(function () {
            checkIfTemplateExistInDb($(this).attr('data-lang'));
        });

        $("#selectLangInAppPushPreview span").click(function () {
            checkIfInAppPushTemplateExistInDb($(this).attr('data-lang'));
        });

        $("#resetToGlobalInAppPush").click(function (e) {
            e.preventDefault();
            checkIfInAppPushTemplateExistInDb($("#selectLangInAppPush").children('span.active').attr('data-lang'));
        });

        $("#resetToGlobalEmail").click(function (e) {
            e.preventDefault();
            checkIfTemplateExistInDb($("#selectLang").children('span.active').attr('data-lang'));
        });

        $(".campaignRepitition select").change(function () {
            $("#weekError").text("");
            if ($(this).val() == 'WEEEKLY') {
                $(".campaignAll").css({display: 'block'});
            } else {
                $(".campaignAll").css({display: 'none'});
            }

            if ($(this).val() == 'ONCE') {
                $("#endTimeHide").css({display: 'none'});
                $("#endDate").val("");
                $("#rec_comp").prop("checked", false);
                updateDeliveryControl();
                $("#delivery_control_html").css({display: "none"});
                $("#rec_comp").prop("disabled", true);
            } else {
                $("#endTimeHide").css({display: 'block'});
                $("#delivery_control_html").css({display: "block"});
                $("#rec_comp").prop("disabled", false);
                //updateDeliveryControl();
            }
        });

        $("#campaignTitle").on('input', function () {
            $("#campaignTitleMain").text("Campaign > " + encodeHTML($("#campaignTitle").val()));
        });

        $(document).on('click', '#campaignTemplateList li', function () {
            if (activeClassId != $(this).attr('id')) {
                $("#campaignTemplateList").children().each(function () {
                    $(this).removeClass('myActive');
                });
                $("#templateList").children().each(function () {
                    $(this).children().eq(0).removeClass('active');
                });
                $(this).addClass('myActive');
                activeClassId = $(this).attr('id');
            }

            if (campaignStatus == 'read' || $("#campaignCreationid").val() != '') {
                swal({
                    title: "Are you sure?",
                    text: "Your current changes in template will be lost if you save this",
                    //icon: "warning",
                    buttons: true,
                    dangerMode: true,
                }).then((willDelete) => {
                    if (!willDelete) {
                        editorSituation = 'read';
                        activeClassId = -1;
                        $("#templateList").children().each(function () {
                            $(this).children().eq(0).removeClass('active');
                        });
                        $("#campaignTemplateList").children().each(function () {
                            $(this).removeClass('myActive');
                        });
                    } else {
                        editorSituation = 'create';
                        $("#templateList").children().each(function () {
                            $(this).children().eq(0).removeClass('active');
                        });
                        $("#campaignTemplateList").children().each(function () {
                            $(this).removeClass('myActive');
                        });
                        $("#" + activeClassId).addClass('myActive');
                    }
                });
            }

        });

        $(document).on('click', '#templateList li a', function () {

            if (activeClassId != $(this).attr('id')) {

                $("#campaignTemplateList").children().each(function () {
                    $(this).removeClass('myActive');
                });

                $("#templateList").children().each(function () {
                    $(this).children().eq(0).removeClass('active');
                });


                $(this).addClass('active');
                activeClassId = $(this).attr('id');
                editorSituation = 'create';
            }

            if (campaignStatus == 'read' || $("#campaignCreationid").val() != '') {
                swal({
                    title: "Are you sure?",
                    text: "Your current changes in template will be lost if you save this",
                    //icon: "warning",
                    buttons: true,
                    dangerMode: true,
                }).then((willDelete) => {
                    if (!willDelete) {
                        editorSituation = 'read';
                        activeClassId = -1;
                        $("#templateList").children().each(function () {
                            $(this).children().eq(0).removeClass('active');
                        });
                    } else {
                        editorSituation = 'create';
                        $("#templateList").children().each(function () {
                            $(this).children().eq(0).removeClass('active');
                        });
                        $("#" + activeClassId).addClass('active');
                    }
                });
            }
        });

        $("#launchBtn").click(function () {

            swal({
                title: "Are you sure?",
                text: "You can't edit this campaign, once it's launched",
                //icon: "info",
                buttons: true,
                //dangerMode: true,
            }).then((willDelete) => {
                if (willDelete) {
                    launchPressed = true;
                    var obj = {
                        step: 6
                    };
                    submit(obj);
                }
            });
        });

        $("#next, #back").click(campaignSubmissionCycle);


        $(".multiSelect").change(function () {
            if ($(this).val() != -1) {
                var index = segmentArray.indexOf(parseInt($(this).val()));
                if (index < 0) {
                    var obj = {
                        id: $(this).val(),
                        name: $(".multiSelect option:selected").text(),
                    };
                    segmentArray.push(parseInt($(this).val()));
                    populateSegmentCollection(obj);
                }
                $('.multiSelect').val("");
                $("#segmentError").text("");
            }
        });

        $("#conversionMultiSelect").change(function () {
            if ($(this).val() != -1) {

                for (var i = 0; i < campaignConversionArr.length; i++) {
                    if (campaignConversionArr[i].name == $(this).val() && conversionListCount < 4) {
                        populateConversionCollection($(this).val(), campaignConversionArr[i].values);
                        break;
                    }
                }
                $('#conversionMultiSelect option:eq(0)').prop('selected', true);
                $("#conversionError").text("");
            }
        });

        $("#actionMultiSelect").change(function () {
            if ($(this).val() != -1) {

                for (var i = 0; i < campaignActionsArr.length; i++) {
                    if (campaignActionsArr[i].name == $(this).val()) {
                        populateActionCollection($(this).val(), campaignActionsArr[i].values);
                        break;
                    }
                }
                $('#actionMultiSelect option:eq(0)').prop('selected', true);
                $("#actionError").text("");
            }
        });

        $("#devicePosition").change(function () {
            $("#positionDiv").css({'vertical-align': $("#devicePosition").val().toLowerCase()});
        });
    }

    function readCampaign(id, action, data) {
        campaignStatus = 'read';
        $("#campaignCreationid").val(id);

        switch (data.steps.length) {
            case 1:
                populateStep1(data.steps[0]);
                break;
            case 2:
                populateStep1(data.steps[0]);
                populateStep2(data.steps[1]);
                break;
            case 3:
                populateStep1(data.steps[0]);
                populateStep2(data.steps[1]);
                populateStep3(data.steps[2]);
                break;
            case 4:
                populateStep1(data.steps[0]);
                populateStep2(data.steps[1]);
                populateStep3(data.steps[2]);
                populateStep4(data.steps[3]);
                break;
            case 5:
                populateStep1(data.steps[0]);
                populateStep2(data.steps[1]);
                populateStep3(data.steps[2]);
                populateStep4(data.steps[3]);
                populateStep5(data.steps[4]);
        }

        if (action == "view") {
            $("#backToListing").text("Close");
        }
        $(".db_content_holder").show();
        $(".reachable_usr_app_message_otr").removeClass('hide');
        $(".db_content_listing_holder").hide();
        if (data.steps.length != 1)
            nextBtn = "ViewMode";
        for (var i = 0; i < data.steps.length; i++) {
            $("#next").trigger('click');
        }

        var stepCount = data.steps.length + 1;
        step = "#step" + stepCount;

        if (action == 'edit') {
            nextBtn = "CreateMode";
        } else {
            $("#launchBtn").css({display: 'none'});
        }
    }

    function populateStep1(data) {
        $("#campaigns_type").prop("disabled", true);
        $("#campaignTitleMain").text('Campaign > ' + data.name);
        $("#campaignTitle").val(data.name);
        $("#campaigns_type").val(data.type_id);
        $("#campaigns_type").trigger('change');
        $(".tags").tagsinput('add', data.tags);
        $("#email").val(data.from_email);
        $("#name").val(data.from_name);
        $("#subject").val(data.subject);
        $("#templateList").children().each(function () {
            $(this).children().eq(0).removeClass('active');
        });
        if (data.status == 'active') {
            $("#launchBtn").css({display: 'none'});
        }
    }

    function populateStep2(data) {

        if (data.cappingRuleControl == null) {
            $("#capping_control").css({display: "none"});
        } else {
            $("#capping_control").css({display: "block"});
        }
        $("#camp_code").val(data.code);
        $(".shour select, .smint select").trigger("change");

        if ($("#campaigns_type").val() == 1) {
            activeClassId = -1;
            $("#selectLang").children('span.active').removeClass('active');
            $("#selectLang span[data-lang='" + data.lang + "']").addClass('active');
            editorSituation = 'read';
            currentLanguage = data.lang;
            fetchEditorAndData(editorSituation, data.content);
            editorPreview(data.lang, data.content);
        } else if ($("#campaigns_type").val() == 2) {
            currentLanguage = data.lang;
            $("#selectLangInAppPush").children('span.active').removeClass('active');
            $("#selectLangInAppPush span[data-lang='" + data.lang + "']").addClass('active');
            $("#switchBtn").css({display: 'inline-block'});
            $("#action1 option").each(function () {
                if ($(this).attr("value") != "Deep Link") {
                    $(this).css({display: "none"});
                }

            });
            $(".pre_comp_brush").css({display: 'none'});
            $(".left_pre_comp_sec").css({height: '743px'});
            $("#step2Container").css({height: '829px'});
            $(".pre_upload_sec").css({height: '672px'});
            $("#step6Preview").css({height: '703px'});
            $(".right_pre_comp_sec").css({height: '800px'});
            $("#plateForm").val(data.plateform);
            $("#inappType").val(data.templateInfo.title);

            //if (data.templateInfo.message != -1)
            $("#inappMessage").val(data.templateInfo.message);

            $("#loadInAppTemplate").children().eq(1).remove();
            $(data.templateInfo.template).insertAfter($("#loadInAppTemplate").children().eq(0));

            $("#action1").val(data.templateInfo.action1.type);
            $("#actionInput1").val(data.templateInfo.action1.value);

            $("#inappType").trigger("input");
            $("#inappMessage").trigger("input");

            var obj = getDateTime();
            $(".time_date h1").text(obj.time);
            $(".time_date span").text(obj.date);

            setLanguageIndentation(data.lang);
        } else {
            currentLanguage = data.lang;
            $("#selectLangInAppPush").children('span.active').removeClass('active');
            $("#selectLangInAppPush span[data-lang='" + data.lang + "']").addClass('active');
            $("#messageType").prop("disabled", true);
            $("#messageType").parent().css({background: "#ccc7"});
            $("#switchBtn").css({display: 'none'});
            $("#action1 option").each(function () {
                $(this).css({display: "block"});
            });
            $(".pre_comp_brush").css({display: 'block'});
            $("#plateForm").val(data.plateform);
            $("#messageType").val(data.messageType);
            $("#layout").val(data.orientation);
            $("#step2Container").css({height: 'auto'});
            $(".right_pre_comp_sec").css({height: '1000px'});

            var getDesign = data.templateInfo.design;
            $("#headerTextColor").val(getDesign.header.headingColor);
            $("#contentTextColor").val(getDesign.header.contentColor);

            $("#btn1BackgroundColor").val(getDesign.button1.color);
            $("#btn1textColor").val(getDesign.button1.textColor);
            $("#btn1BackgroundOpacity").val(getDesign.button1.opacity);
            $("#btn1BackgroundOpacity").trigger('change');

            $("#btn2BackgroundColor").val(getDesign.button2.color);
            $("#btn2textColor").val(getDesign.button2.textColor);
            $("#btn2BackgroundOpacity").val(getDesign.button2.opacity);
            $("#btn2BackgroundOpacity").trigger('change');
            $("#backgroundColor").val(getDesign.background.color);
            $("#backgroundOpacity").val(getDesign.background.opacity);
            $("#backgroundOpacity").trigger('change');

            $("#frameColor").val(getDesign.frame.color);

            if (data.templateInfo.title != -1)
                $("#inappType").val(data.templateInfo.title);
            if (data.templateInfo.message != -1)
                $("#inappMessage").val(data.templateInfo.message);

            if (data.position != -1)
                $("#devicePosition").val(data.position);

            $("#loadInAppTemplate").children().eq(1).remove();
            $(data.templateInfo.template).insertAfter($("#loadInAppTemplate").children().eq(0));

            if (data.templateInfo.action1.label != -1)
                $("#firstBtn").val(data.templateInfo.action1.label);
            else
                $("#firstBtn").val('');

            $("#action1").val(data.templateInfo.action1.type);

            if (data.templateInfo.action1.value != -1)
                $("#actionInput1").val(data.templateInfo.action1.value);
            else
                $("#actionInput1").val('');


            if (data.templateInfo.action2.label != -1)
                $("#secondBtn").val(data.templateInfo.action2.label);
            else
                $("#secondBtn").val('');

            $("#action2").val(data.templateInfo.action2.type);

            if (data.templateInfo.action2.value != -1)
                $("#actionInput2").val(data.templateInfo.action2.value);
            else
                $("#actionInput2").val('');

            $("#inappType").trigger("input");
            $("#inappMessage").trigger("input");

            setGUIRules();
            setTemplatedesign();
            setLanguageIndentation(data.lang);
        }
        $("#plateForm").trigger('change');
    }

    function populateStep3(data) {
        if (data.delivery_type == 'schedule') {
            $("#contactChoice2").trigger('click');
            $(".campaignRepitition select").val(data.schedule_type);

            if (data.schedule_type == 'WEEEKLY') {
                $(".campaignAll select").val(data.days);
            }

            $(".campaignRepitition select").trigger('change');

        } else if (data.delivery_type == 'action') {

            $("#contactChoice1").trigger('click');

            for (var i = 0; i < data.actions.length; i++) {

                for (var j = 0; j < campaignActionsArr.length; j++) {
                    if (campaignActionsArr[j].name == data.actions[i].action_id) {
                        populateActionCollection(data.actions[i].action_id, campaignActionsArr[j].values, data.actions[i].value);
                        break;
                    }
                }
            }
            $("#actionTriggerInput").val(data.input);
            $("#actionTriggerValue").val(data.value);
        } else {
            $("#contactChoice3").trigger('click');
        }

        $("#startDate").val(data.start_time.split(' ')[0]);

        $(".shour select").val(data.start_time.split(' ')[1].split(':')[0]);
        $(".smint select").val(data.start_time.split(' ')[1].split(':')[1]);

        if (data.end_time != null) {
            $("#endDate").val(data.end_time.split(' ')[0]);
            $(".ehour select").val(data.end_time.split(' ')[1].split(':')[0]);
            $(".emint select").val(data.end_time.split(' ')[1].split(':')[1]);
        }
        $(".shour select, .smint select").trigger("change");

        $("#deliveryInput").val(data.deliveryInput);
        $("#deliveryValue").val(data.deliveryValue);
        $("#deliveryPriority").val(data.deliveryPriority);

        if (data.deliveryIsChecked == 1) {
            $("#rec_comp").prop("checked", true);
            updateDeliveryControl();
        } else {
            $("#rec_comp").prop("checked", false);
            updateDeliveryControl();
        }

        if (data.enableCapping == 1) {
            $(".fr_cap").prop("checked", true);
        } else {
            $(".fr_cap").prop("checked", false);
        }

    }

    function populateStep4(data) {

        $('.reachableUsers').html(data.reachableUsers);
        $('#reachAbleOnCompose').html('<b>Reachable Users:</b>' + " " + data.reachableUsers);

        segmentArray = [];
        for (var i = 0; i < data.segments.length; i++) {
            segmentArray.push(parseInt(data.segments[i].id));
            var obj = {
                id: data.segments[i].id,
                name: data.segments[i].text,
            };
            populateSegmentCollection(obj);
        }
    }

    function populateStep5(data) {

        for (var i = 0; i < data.conversion.length; i++) {

            for (var j = 0; j < campaignConversionArr.length; j++) {
                if (campaignConversionArr[j].name == data.conversion[i].conversionType) {
                    populateConversionCollection(data.conversion[i].conversionType, campaignConversionArr[j].values, data.conversion[i].conversionValue, data.conversion[i].conversionValidity, data.conversion[i].period);
                    break;
                }
            }
        }

        $("#e2-rec_comp3").prop("checked", false);

        $("#appendApps").children().find('input').each(function () {
            $(this).prop("checked", false);
        });


        $("#appendApps").children().find('input').each(function () {
            if (data.apps.indexOf($(this).attr('data-orgid')) > -1) {
                $(this).prop("checked", true);
            }
        });
        checkForAllCheckedApps();
        populateStep6();
    }

    function populateStep6() {
        if ($("#campaigns_type").val() == 1) {
            $('#fromPreEmail').text($("#email").val());
            $('#fromPreName').text($("#name").val());
            $('#fromPreSubject').text($("#subject").val());

            editorPreview($("#selectLang").children('span.active').attr("data-lang"), CKEDITOR.instances['editor'].getData());

            var lang = $("#selectLang").children('span.active').attr("data-lang");
            $("#selectLangEmailPreview").children('span.active').removeClass('active');
            $("#selectLangEmailPreview span[data-lang='" + lang + "']").addClass('active');
        } else {

            var lang = $("#selectLangInAppPush").children('span.active').attr("data-lang");
            $("#selectLangInAppPushPreview").children('span.active').removeClass('active');
            $("#selectLangInAppPushPreview span[data-lang='" + lang + "']").addClass('active');

            $(".pre_upload_sec").html($("#loadInAppTemplate").html());
        }

        var str = '<h3>Action-Based Delivery</h3>';
        if ($("#contactChoice1").parent().hasClass('active')) {
            str += '<p>Send Campaign ' + $("#actionTriggerInput").val() + " " + $("#actionTriggerValue").val() + '(s) after trigger criteria are met</p>';
        } else {
            str += '<p>NILL</p>';
        }
        $("#actionTriggerComposeScreen").html(str);


        str = '<h3>Campaign Rules</h3>';
        if ($("#rec_comp").prop("checked")) {
            str += '<p>Users can receive messages from this Campaign multiple times when at least ' + $("#deliveryInput").val() + " " + $("#deliveryValue").val() + '(s) have elapsed since they were previously targeted</p>';
        } else {
            str += '<p>NILL</p>';
        }
        $("#deliveryControlComposeScreen").html(str);

        $(".prim_con_sec_outer").remove();
        var conversionArr = getConversionForCampaign();
        var str = '';
        for (var i = 0; i < conversionArr.length; i++) {
            str += '<div class="prim_con_sec_outer">';
            str += '<p>' + conversionArr[i].conversionType + ' - ' + conversionArr[i].conversionValue + '</p>';
            str += '<p><b>conversion validity: ' + conversionArr[i].conversionValidity + ' - ' + conversionArr[i].period + '(s)</b></p>';
        }
        $(".bcd_stp6_a").append(str);
    }

    function campaignSubmissionCycle() {
        if ($(this).attr('id') == 'back') {
            step = $(".step-steps").children().filter(".active").children("a").attr("href");
            if (!scrollLock)
                $(".wpr_content_holder").scrollTop(0);
            else
                scrollLock = false;
        } else if (nextBtn == "CreateMode") {

            if (step == "#step1") {
                pressed.step1 = true;
                if (!validateStep1()) {
                    $(".wpr_content_holder").scrollTop(0);
                    var obj = {
                        campaignTitle: $("#campaignTitle").val(),
                        campaignType: $("#campaigns_type").val(),
                        tagsInput: $(".tags").val(),
                        email: $("#email").val(),
                        name: $("#name").val(),
                        subject: $("#subject").val(),
                        activeClassId: activeClassId,
                        /*htmlContent: atob($("#" + activeClassId + ' h1').attr('data-content')),*/
                        htmlContent: $("#" + activeClassId + ' h1').attr('data-content'),
                        step: 1,
                    };

                    submit(obj);
                    step = $(".step-steps").children().filter(".active").children("a").attr("href");
                } else {
                    scrollLock = true;
                    $("#back").trigger('click');
                }
            } else if (step == "#step2") {
                pressed.step2 = true;
                if (!validateStep2() && !validateImage()) {

                    $(".wpr_content_holder").scrollTop(0);
                    if ($("#campaigns_type").val() == 1) {


                        for (var i = 0; i < langArrSaveContent.length; i++) {
                            if (langArrSaveContent[i].lang == $("#selectLang").children('span.active').attr('data-lang')) {
                                langArrSaveContent.splice(i, 1);
                                break;
                            }
                        }

                        var objContent = {
                            lang: $("#selectLang").children('span.active').attr('data-lang'),
                            content: CKEDITOR.instances['editor'].getData()
                        };

                        langArrSaveContent.push(objContent);

                        var obj = {
                            editorContent: JSON.parse(JSON.stringify(langArrSaveContent)),
                            step: 2
                        };


                        for (var i = 0; i < obj.editorContent.length; i++) {
                            obj.editorContent[i].content = btoa(unescape(encodeURIComponent(obj.editorContent[i].content)));
                        }

                    } else if ($("#campaigns_type").val() == 2) {

                        for (var i = 0; i < langArrSaveContent.length; i++) {
                            if (langArrSaveContent[i].language == $("#selectLangInAppPush").children('span.active').attr('data-lang')) {
                                langArrSaveContent.splice(i, 1);
                                break;
                            }
                        }

                        var objContent = {
                            language: $("#selectLangInAppPush").children('span.active').attr('data-lang'),
                            templateInfo: {
                                template: $("#loadInAppTemplate").children().eq(1).prop('outerHTML'),
                                text: $("#headingT").parent().children().eq(1).text(),
                                title: checkDisabled("#inappType"),
                                message: checkDisabled("#inappMessage"),
                                action1: {
                                    label: -1,
                                    type: $("#action1").val(),
                                    value: $("#actionInput1").val().trim(),
                                },
                                action2: {
                                    label: -1,
                                    type: -1,
                                    value: -1,
                                },
                            }
                        };
                        langArrSaveContent.push(objContent);

                        for (var i = 0; i < langArrSaveContent.length; i++) {
                            if (langArrSaveContent[i].templateInfo.title == "" || langArrSaveContent[i].templateInfo.title == -1) {
                                langArrSaveContent[i].templateInfo.title = $("#inappType").val();
                            }

                            if (langArrSaveContent[i].templateInfo.message == "" || langArrSaveContent[i].templateInfo.message == -1) {
                                langArrSaveContent[i].templateInfo.message = $("#inappMessage").val();
                            }
                        }

                        var obj = {
                            step: 2,
                            plateform: $("#plateForm").val(),
                            messageType: -1,
                            orientation: -1,
                            position: -1,
                        };

                        obj.templatesInfo = JSON.parse(JSON.stringify(langArrSaveContent));
                        for (var i = 0; i < obj.templatesInfo.length; i++) {
                            obj.templatesInfo[i].templateInfo.template = btoa(unescape(encodeURIComponent(obj.templatesInfo[i].templateInfo.template)));
                        }
                    } else {

                        for (var i = 0; i < langArrSaveContent.length; i++) {
                            if (langArrSaveContent[i].language == $("#selectLangInAppPush").children('span.active').attr('data-lang')) {
                                langArrSaveContent.splice(i, 1);
                                break;
                            }
                        }

                        var objContent = {
                            language: $("#selectLangInAppPush").children('span.active').attr('data-lang'),
                            templateInfo: {
                                template: $("#loadInAppTemplate").children().eq(1).prop('outerHTML').replace(/>\s+|\s+</g, function (m) {
                                    return m.trim();
                                }),
                                title: checkDisabled("#inappType"),
                                message: checkDisabled("#inappMessage"),
                                action1: {
                                    label: checkDisabled("#firstBtn"),
                                    type: $("#action1").val(),
                                    value: checkDisabled("#actionInput1"),
                                },
                                action2: {
                                    label: checkDisabled("#secondBtn"),
                                    type: $("#action2").val(),
                                    value: checkDisabled("#actionInput2"),
                                },
                                design: {
                                    header: {
                                        headingColor: $("#headerTextColor").val(),
                                        contentColor: $("#contentTextColor").val()
                                    },
                                    button1: {
                                        color: $("#btn1BackgroundColor").val(),
                                        opacity: $("#btn1BackgroundOpacity").val(),
                                        textColor: $("#btn1textColor").val()
                                    },
                                    button2: {
                                        color: $("#btn2BackgroundColor").val(),
                                        opacity: $("#btn2BackgroundOpacity").val(),
                                        textColor: $("#btn2textColor").val()
                                    },
                                    background: {
                                        color: $("#backgroundColor").val(),
                                        opacity: $("#backgroundOpacity").val()
                                    },
                                    frame: {
                                        color: $("#frameColor").val(),
                                    },
                                }
                            }
                        };
                        langArrSaveContent.push(objContent);


                        for (var i = 0; i < langArrSaveContent.length; i++) {
                            if (langArrSaveContent[i].templateInfo.action1.value == "" || langArrSaveContent[i].templateInfo.action1.value == -1) {
                                langArrSaveContent[i].templateInfo.action1.value = $("#actionInput1").val();
                            }

                            if (langArrSaveContent[i].templateInfo.action2.value == "" || langArrSaveContent[i].templateInfo.action2.value == -1) {
                                langArrSaveContent[i].templateInfo.action2.value = $("#actionInput2").val();
                            }

                            if (langArrSaveContent[i].templateInfo.title == "" || langArrSaveContent[i].templateInfo.title == -1) {
                                langArrSaveContent[i].templateInfo.title = $("#inappType").val();
                            }

                            if (langArrSaveContent[i].templateInfo.message == "" || langArrSaveContent[i].templateInfo.message == -1) {
                                langArrSaveContent[i].templateInfo.message = $("#inappMessage").val();
                            }
                        }

                        for (var i = 0; i < langArrSaveContent.length; i++) {

                            var html = langArrSaveContent[i].templateInfo.template;
                            var regex = /<img.*?src="(.*?)"/;

                            if (regex.exec(html) != null) {
                                langArrSaveContent[i].templateInfo.image = regex.exec(html)[1];
                            } else {
                                langArrSaveContent[i].templateInfo.image = '';
                            }
                        }

                        var obj = {
                            step: 2,
                            plateform: $("#plateForm").val(),
                            messageType: $("#messageType").val(),
                            orientation: $("#layout").val(),
                            position: checkDisabled("#devicePosition"),
                        };

                        obj.templatesInfo = JSON.parse(JSON.stringify(langArrSaveContent));

                        for (var i = 0; i < obj.templatesInfo.length; i++) {
                            obj.templatesInfo[i].templateInfo.template = btoa(unescape(encodeURIComponent(obj.templatesInfo[i].templateInfo.template)));
                        }
                    }
                    submit(obj);
                    step = $(".step-steps").children().filter(".active").children("a").attr("href");
                } else {
                    scrollLock = true;
                    $("#back").trigger('click');

                    if ($("#actionInput1").offset().top > 524) {
                        $('.wpr_content_holder, body').animate({
                            scrollTop: $("#actionInput1").offset().top
                        }, 2000);
                    }

                }
            } else if (step == "#step3") {
                pressed.step3 = true;
                if (!validateStep3()) {
                    $(".wpr_content_holder").scrollTop(0);
                    var obj = {
                        campaignRepitition: $(".campaignRepitition select").val(),
                        campaignAll: $(".campaignAll select").val(),
                        startDate: $("#startDate").val() + ' ' + $(".shour select").val() + ':' + $(".smint select").val() + ':00',
                        endDate: $("#endDate").val() + ' ' + $(".ehour select").val() + ':' + $(".emint select").val() + ':00',
                        sendCampaignToUsers: $("#tm_zom").is(':checked'),
                        actions: getActionForCampaign(),
                        actionTriggerDelivery: {
                            input: $("#actionTriggerInput").val(),
                            value: $("#actionTriggerValue").val()
                        },
                        delivery: {
                            input: $("#deliveryInput").val(),
                            value: $("#deliveryValue").val(),
                            priority: $("#deliveryPriority").val(),
                            isChecked: $("#rec_comp").prop("checked"),
                        },
                        enableCapping: $(".fr_cap").prop("checked"),
                        step: 3
                    };
                    if ($("#contactChoice1").parent().hasClass('active'))
                        obj.deliveryType = 'action';
                    else if ($("#contactChoice2").parent().hasClass('active'))
                        obj.deliveryType = 'schedule';
                    else
                        obj.deliveryType = 'api';

                    submit(obj);
                    step = $(".step-steps").children().filter(".active").children("a").attr("href");
                } else {
                    scrollLock = true;
                    $("#back").trigger('click');
                }
            } else if (step == "#step4") {
                pressed.step4 = true;
                if (!validateStep4()) {
                    $(".wpr_content_holder").scrollTop(0);
                    var obj = {
                        segmentIds: segmentArray,
                        step: 4
                    };
                    submit(obj);
                    step = $(".step-steps").children().filter(".active").children("a").attr("href");
                } else {
                    scrollLock = true;
                    $("#back").trigger('click');
                }
            } else if (step == "#step5") {
                pressed.step5 = true;
                if (true  /*!validateStep5()*/) {
                    $(".wpr_content_holder").scrollTop(0);
                    var obj = {};

                    obj.conversion = getConversionForCampaign();
                    obj.step = 5;
                    obj.apps = [];

                    $("#appendApps").children().each(function () {
                        if ($(this).find('input').prop("checked")) {
                            obj.apps.push($(this).find('input').attr('data-orgid'));
                        }
                    });

                    submit(obj);
                    populateStep6();
                    step = $(".step-steps").children().filter(".active").children("a").attr("href");
                } else {
                    scrollLock = true;
                    $("#back").trigger('click');
                }
            }
        }
    }

    function validateStep1() {

        var error = false;

        if ($("#campaignTitle").val() == "") {
            $("#campaignError").text("Campaign Title is required");
            error = true;
        } else {
            if ($("#campaignTitle").val().length < 5) {
                $("#campaignError").text("Campaign Title must have atleast 5 characters");
                error = true;
            } else {
                $("#campaignError").text("");
            }
        }

        if ($("#campaigns_type").val() == 1) {

            if ($("#email").val() == "") {
                $("#emailError").text("Email is required");
                error = true;
            } else {

                var emailReg = /[A-Z0-9._%+-]+@[A-Z0-9.-]+\.[A-Z]{2,4}/igm;
                if (!emailReg.test($("#email").val())) {
                    $("#emailError").text("Invalid Email");
                    error = true;
                } else {
                    $("#emailError").text("");
                }

            }

            if ($("#name").val() == "") {
                $("#nameError").text("Name is required");
                error = true;
            } else {
                $("#nameError").text("");
            }

            if ($("#subject").val() == "") {
                $("#subjectError").text("Subject is required");
                error = true;
            } else {
                $("#subjectError").text("");
            }
        }
        return error;
    }

    function validateStep2() {
        var error = false;
        //var deepNewsReg = /^\S*$/;
        var deepNewsReg = /[ ]/g;
        if ($("#campaigns_type").val() == 3) {

            if ($("#action1").val() == 'Url') {
                var expression = /(http:\/\/www\.|https:\/\/www\.|http:\/\/|https:\/\/)[a-z0-9]+([\-\.]{1}[a-z0-9]+)*\.[a-z]{2,5}(:[0-9]{1,5})?(\/.*)?$/gi;
                if (!expression.test($("#actionInput1").val())) {
                    $("#actionError1").text("Invalid Url");
                    error = true;
                } else {
                    $("#actionError1").text("");
                }
            } else if ($("#action1").val() == 'Deep Link' || $("#action1").val() == 'NewsFeed') {
                if ($("#actionInput1").val().indexOf("://") <= 0 || deepNewsReg.test($("#actionInput1").val())) {
                    $("#actionError1").text("Invalid " + $("#action1").val());
                    error = true;
                } else {
                    $("#actionError1").text("");
                }
            } else {
                $("#actionError1").text("");
            }


            if ($("#action2").val() == 'Url' && ($("#messageType").val() == 'Full Screen' || $("#messageType").val() == 'Dialogue') && $("#secondBtn").val() != '') {
                var expression = /(http:\/\/www\.|https:\/\/www\.|http:\/\/|https:\/\/)[a-z0-9]+([\-\.]{1}[a-z0-9]+)*\.[a-z]{2,5}(:[0-9]{1,5})?(\/.*)?$/gi;
                if (!expression.test($("#actionInput2").val())) {
                    $("#actionError2").text("Invalid Url");
                    error = true;
                } else {
                    $("#actionError2").text("");
                }
            } else if (($("#action2").val() == 'Deep Link' || $("#action2").val() == 'NewsFeed') && ($("#messageType").val() == 'Full Screen' || $("#messageType").val() == 'Dialogue') && $("#secondBtn").val() != '') {
                if ($("#actionInput2").val().indexOf("://") <= 0 || deepNewsReg.test($("#actionInput2").val())) {
                    $("#actionError2").text("Invalid " + $("#action2").val());
                    error = true;
                } else {
                    $("#actionError2").text("");
                }
            } else {
                $("#actionError2").text("");
            }

            if ($("#inappType").val() == "" && $("#messageType").val() != "Banner") {
                error = true;
                $("#inappTypeError").text("Title is required");
            } else {
                $("#inappTypeError").text("");
            }

            if ($("#inappMessage").val() == "" && $("#messageType").val() != "Banner") {
                error = true;
                $("#inappMessageError").text("Message is required");
            } else {
                $("#inappMessageError").text("");
            }
        } else if ($("#campaigns_type").val() == 2) {

            if ($("#inappType").val() == "") {
                error = true;
                $("#inappTypeError").text("Title is required");
            } else {
                $("#inappTypeError").text("");
            }


            if ($("#inappType").val().length > 400) {
                error = true;
                $("#inappTypeError").text("Title length should not be greater than 400 characters");
            } else {
                $("#inappTypeError").text("");
            }


            if ($("#inappMessage").val() == "") {
                error = true;
                $("#inappMessageError").text("Message is required");
            } else {
                $("#inappMessageError").text("");
            }


            if ($("#inappMessage").val().length > 1000) {
                error = true;
                $("#inappMessageError").text("Message length should not be greater than 1000 characters");
            } else {
                $("#inappMessageError").text("");
            }

            if ($("#actionInput1").val() != "") {
                if ($("#actionInput1").val().indexOf("://") <= 0 || deepNewsReg.test($("#actionInput1").val())) {
                    $("#actionError1").text("Invalid " + $("#action1").val());
                    error = true;
                } else {
                    $("#actionError1").text("");
                }
            } else {
                $("#actionError1").text("");
            }
        }
        if (error) {
            $(".pre_comp_write").trigger("click");
        }
        return error;
    }

    function validateStep3() {

        var error = false;
        var startDateIf = false;

        if ($("#contactChoice2").parent().hasClass('active')) {
            if ($(".campaignRepitition select").val() == 'WEEEKLY') {

                if ($(".campaignAll select").val() == null) {
                    $("#weekError").text("Select day(s)");
                    error = true;
                } else {
                    $("#weekError").text("");
                }

            }

            if ($("#startDate").val() == "") {
                startDateIf = true;
                $("#startDateError").text("start date is required");
                error = true;
            } else {
                $("#startDateError").text("");
            }


            if ($(".campaignRepitition select").val() == 'WEEEKLY' || $(".campaignRepitition select").val() == 'DAILY') {
                if ($("#endDate").val() == "") {
                    $("#endDateError").text("End date is required");
                    error = true;
                } else {
                    $("#endDateError").text("");
                }
            }

            var startDateTime = $("#startDate").val() + ' ' + $(".shour select").val() + ':' + $(".smint select").val() + ':00';
            var endDateTime = $("#endDate").val() + ' ' + $(".ehour select").val() + ':' + $(".emint select").val() + ':00';

            if ($("#startDate").val() != "" && $("#endDate").val() != "" && endDateTime <= startDateTime) {
                $("#startDateError").text("start date/time should less than end date/time");
                error = true;
            } else {
                if (!startDateIf)
                    $("#startDateError").text("");
            }
        } else {

            if ($("#startDate").val() == "") {
                startDateIf = true;
                $("#startDateError").text("start date is required");
                error = true;
            } else {
                $("#startDateError").text("");
            }

            if ($("#endDate").val() == "") {
                $("#endDateError").text("End date is required");
                error = true;
            } else {
                $("#endDateError").text("");
            }

            var startDateTime = $("#startDate").val() + ' ' + $(".shour select").val() + ':' + $(".smint select").val() + ':00';
            var endDateTime = $("#endDate").val() + ' ' + $(".ehour select").val() + ':' + $(".emint select").val() + ':00';

            if ($("#startDate").val() != "" && $("#endDate").val() != "" && endDateTime <= startDateTime) {
                $("#startDateError").text("start date/time should less than end date/time");
                error = true;
            } else {
                if (!startDateIf)
                    $("#startDateError").text("");
            }

            if (getActionForCampaign().length == 0) {
                $("#actionError").text("Please select action(s)");
                error = true;
            } else {
                $("#actionError").text("");
            }
        }
        return error;
    }

    function validateStep4() {

        var error = false;
        if (segmentArray.length == 0) {
            error = true;
            $("#segmentError").text("Select segment(s)");
        } else {
            $("#segmentError").text("");
        }
        return error;

    }

    function validateStep5() {

        var error = false;

        if (getConversionForCampaign().length == 0) {
            $("#conversionError").text("Please select conversion(s)");
            error = true;
        } else {
            $("#conversionError").text("");
        }
        return error;
    }

    function validateImage() {
        if (($("#campaigns_type").val() == 3) && (($("#messageType").val() != "Dialogue" && $(".img_uploader label img").attr("src") == "") || ($("#messageType").val() == "Dialogue" && $(".tp_img_text span img").attr("src") == ""))) {
            toastr.error("Please select " + $("#messageType").val() + " image");
            return true;
        }
        return false;
    }

    function submit(obj) {
        var url = baseUrl + '/backend/campaign/campaignInsertion';
        obj.companyId = $(".companyId").val();
        obj.campaignId = $("#campaignCreationid").val();
        $.ajax({
            /*global: false,*/
            type: 'POST',
            url: url,
            data: {
                obj,
                _token: $('input[name="_token"]').val(),
            },
            dataType: 'json',
            headers: {
                'X-CSRF-Token': $('input[name="_token"]').val()
            },
            success: function (response) {

                if (!response.status) {
                    $("#back").trigger('click');
                    toastr.error(response.message);
                    return;
                }

                if (step == "#step5") {
                    $(".reachable_usr_app_message_otr").removeClass('hide');
                    $('.reachableUsers').html(response.data);
                    $('#reachAbleOnCompose').html('<b>Reachable Users:</b>' + " " + response.data);
                } else if (step == "#step3") {
                    $("#camp_code").val(response.data.code);
                    if (response.data.cappingRuleControl == null) {
                        $("#capping_control").css({display: "none"});
                    } else {
                        $("#capping_control").css({display: "block"});
                    }
                } else {
                    $("#campaignCreationid").val(response.data);
                }

                if (step == "#step2" && $("#campaigns_type").val() == 1) {
                    if (editorSituation != 'read') {
                        fetchEditorAndData(editorSituation, '');
                    }
                } else if (step == "#step2" && $("#campaigns_type").val() == 3) {
                    $("#switchBtn").css({display: 'none'});
                    $("#action1 option").each(function () {
                        $(this).css({display: "block"});
                    });
                    $(".pre_comp_brush").css({display: 'block'});
                    $("#step2Container").css({height: 'auto'});
                    $(".right_pre_comp_sec").css({height: '1000px'});
                    setGUIRules();
                    setTemplatedesign();
                } else if (step == "#step2" && $("#campaigns_type").val() == 2) {
                    $("#switchBtn").css({display: 'inline-block'});
                    $("#action1 option").each(function () {
                        if ($(this).attr("value") != "Deep Link") {
                            $(this).css({display: "none"});
                        }
                    });
                    $("#action1").val("Deep Link");
                    $(".pre_comp_brush").css({display: 'none'});
                    $(".pre_comp_write").trigger("click");
                    $(".left_pre_comp_sec").css({height: '743px'});
                    $("#step2Container").css({height: '829px'});
                    $(".pre_upload_sec").css({height: '672px'});
                    $("#step6Preview").css({height: '703px'});
                    $(".right_pre_comp_sec").css({height: '800px'});
                }

                if (step == "#step3" && $("#campaigns_type").val() == 3) {
                    $("#messageType").prop("disabled", true);
                    $("#messageType").parent().css({background: "#ccc7"});
                }

                if (launchPressed) {
                    $("#launchBtn").css({display: 'none'});
                    launchPressed = !launchPressed;
                    window.location.href = baseUrl + '/backend/campaign/campaigns';
                }
            },
            error: function () {
                $("#back").trigger('click');
                //alert(step);
            }
        });
    }

    function populateTemplate(template) {
        var str = '';

        for (var i = 0; i < template.length; i++) {
            str = str + '<li>';
            if (i == 0) {
                activeClassId = template[i].id;
                str = str + '<a id="' + template[i].id + '" class="active" >';
            } else {
                str = str + '<a id="' + template[i].id + '">';
            }
            str = str + '<h1 data-content="' + btoa(template[i].content) + '"></h1>';
            str = str + '<img src="' + template[i].thumbNail + '" alt="#">';
            str = str + '</a></li>';

        }

        $("#templateList").html(str);
    }

    function populateSegmentCollection(obj) {
        var str = '';
        str = '<li id="' + obj.id + '_delMain">';
        str += '<div class="text_del_outer clearfix">';
        str += '<div class="Campaigns_input top_left_inp b_r">';
        str += '<input disabled type="text" name="" value="' + obj.name + '" data-id="' + obj.id + '" placeholder="UK Launch 2018 Members ">';
        str += '</div>';
        str += '<span id="' + obj.id + '_del" onclick="clearSegment(this.id)"><i></i></span>';
        str += '</div>';
        str += '</li>';
        str += '<li id="' + obj.id + '_delInner" class="clearfix">';
        str += '<div class="togggle_btn_reach_user">';
        str += '<span class=" and_or_toggle_btn active">AND</span>';
        str += '</div>';
        str += '</li>';

        $(str).insertBefore($("#multiSelectLi"));
    }

    function populateActionCollection(name, valueArr, value = "") {

        var str = '';
        str = '<li class="true" id="' + name + '_delMain">';
        str += '<div class="text_del_outer clearfix">';
        str += '<div style="padding: 5px 0px;" class="Campaigns_input top_left_inp b_r">';
        str += '<input disabled class="actionInput" type="text" name="" value="' + name + '" data-id="' + name + '" placeholder="Do not remove it">';
        str += '<select class="actionList">';

        if (value == "") {
            for (var i = 0; i < valueArr.length; i++) {
                str += '<option value="' + valueArr[i] + '">' + valueArr[i] + '</option>';
            }
        } else {
            for (var i = 0; i < valueArr.length; i++) {
                if (value == valueArr[i]) {
                    str += '<option selected value="' + valueArr[i] + '">' + valueArr[i] + '</option>';
                } else {
                    str += '<option value="' + valueArr[i] + '">' + valueArr[i] + '</option>';
                }
            }
        }

        str += '</select>';
        str += '</div>';
        str += '<span style="top: 7px" id="' + name + '_del" onclick="clearActions(this)"><i></i></span>';
        str += '</div>';
        str += '</li>';
        str += '<li id="' + name + '_delInner" class="clearfix">';
        str += '<div class="togggle_btn_reach_user">';
        str += '<span class=" and_or_toggle_btn active">AND</span>';
        str += '</div>';
        str += '</li>';

        $(str).insertBefore($("#actionMultiSelectLi"));
    }

    function populateConversionCollection(name, valueArr, value = "", number = "", periodInp = "") {

        var period = ['minute', 'hour', 'day'];

        var str = '';
        str = '<li class="true" id="' + name + '_delMain">';
        str += '<div class="text_del_outer clearfix">';
        str += '<div style="padding: 5px 0px 0;" class="Campaigns_input top_left_inp b_r">';
        str += '<input disabled class="conversionInput" type="text" name="" value="' + name + '" data-id="' + name + '" placeholder="Do not remove it">';
        str += '<select class="conversionList">';

        if (value == "") {
            for (var i = 0; i < valueArr.length; i++) {
                str += '<option value="' + valueArr[i] + '">' + valueArr[i] + '</option>';
            }
        } else {
            for (var i = 0; i < valueArr.length; i++) {
                if (value == valueArr[i]) {
                    str += '<option selected value="' + valueArr[i] + '">' + valueArr[i] + '</option>';
                } else {
                    str += '<option value="' + valueArr[i] + '">' + valueArr[i] + '</option>';
                }
            }
        }

        str += '</select>';


        str += '<div class="input_fields_holder">';
        str += '<div class="new_fields">';

        if (number == "")
            str += '<input style="width:12% !important;" min="0" class="conversionInput" type="number" value="0" placeholder="0">';
        else
            str += '<input style="width:12% !important;" min="0" class="conversionInput" type="number" value="' + number + '" placeholder="0">';

        str += '<select style="width:79% !important;" class="conversionList">';


        if (periodInp == "") {
            for (var i = 0; i < period.length; i++) {
                str += '<option value="' + period[i] + '">' + period[i] + '</option>';
            }
        } else {
            for (var i = 0; i < period.length; i++) {
                if (periodInp == period[i]) {
                    str += '<option selected value="' + period[i] + '">' + period[i] + '</option>';
                } else {
                    str += '<option value="' + period[i] + '">' + period[i] + '</option>';
                }
            }
        }

        str += '</select>';

        str += '</div>';

        str += '<div class="fields_text">';


        str += '<div class="camp_title custom_one sub_title">';
        str += '<h3>Set Conversion Deadline</h3>';
        str += '<p>The maximum amount of time that may pass between a user receiving a Campaign and performing the assigned action for it to be considered a conversion.</p>';
        str += '</div>';

        str += '</div>';


        str += '</div>';

        str += '</div>';
        str += '<span style="top: 7px" id="' + name + '_del" onclick="clearConversion(this)"><i></i></span>';
        str += '</div>';
        str += '</li>';
        str += '<li id="' + name + '_delInner" class="clearfix">';
        str += '<div class="togggle_btn_reach_user">';
        str += '<span class=" and_or_toggle_btn active">AND</span>';
        str += '</div>';
        str += '</li>';

        $(str).insertBefore($("#conversionMultiSelectLi"));
        conversionListCount++;
    }

    function populateCampaignTemplate(campaignTemplate) {
        var str = '';
        for (var i = 0; i < campaignTemplate.length; i++) {
            str += '<li id="' + campaignTemplate[i].id + 'campaign" >';
            str = str + '<h1 data-content="' + btoa(campaignTemplate[i].content) + '"></h1>';
            str += '<span>';
            str += '<img src="' + campaignImg + '" alt="#">';
            str += campaignTemplate[i].mutateCode;
            str += '</span>';
            str += '</li>';
        }
        $("#campaignTemplateList").html(str);
    }

    function populateInAppData(inAppData) {
        populateSelect(inAppData.platformList, '#plateForm');
        populateSelect(inAppData.messageTypeList, '#messageType');

        populateSelect(inAppData.layoutTypeList, '#layout');
        $('#layout option[value="Landscape"]').attr("disabled", "disabled");

        populateSelect(inAppData.devicePositionList, '#devicePosition');
        populateSelect(inAppData.actionList, '#action1');
        populateSelect(inAppData.actionList, '#action2');

        inAppTemplates = inAppData.inAppTemplates;
        populateInAppTemplate(inAppData.inAppTemplates, '#loadInAppTemplate');
        setGUIRules();
    }

    function populateInAppTemplate(templates, id) {
        for (var i = 0; i < templates.length; i++) {
            if (templates[i].type.toLowerCase() == currentInAppTemplate.toLowerCase()) {
                $(id).children().eq(1).remove();
                $(templates[i].content).insertAfter($(id).children().eq(0));

            }
        }
    }

    function setGUIRules() {
        setActions();
        if ($("#messageType").val() == 'Dialogue') {
            $("#loadInAppTemplate").css({"height": "917px"});
            $("#devicePosition").prop('disabled', false);
            $("#devicePosition").parent().css({background: "#fff"});
            $("#positionDiv").css({'vertical-align': $("#devicePosition").val().toLowerCase()});
        } else {
            $("#loadInAppTemplate").css({"height": "auto"});
            $("#devicePosition").prop('disabled', true);
            $("#devicePosition").parent().css({background: "#ccc7"});
        }

        if ($("#messageType").val() != 'Banner') {
            $("#inappType").prop('disabled', false);
            $("#inappMessage").prop('disabled', false);

            $("#headingT").text($("#inappType").val());
            $("#headingT").parent().children('p').text($("#inappMessage").val());
        } else if (typeOfCampaign != 'push') {
            $("#inappType").prop('disabled', true);
            $("#inappMessage").prop('disabled', true);
        }

        if ($("#messageType").val() == 'Full Screen' || $("#messageType").val() == 'Dialogue') {
            $("#secondBtn").trigger('input');
            $("#firstBtn").prop('disabled', false);
            $("#secondBtn").prop('disabled', false);

            if ($("#action1").val() != 'Close') {
                $("#actionInput1").prop('disabled', false);
            } else {
                $("#actionInput1").prop('disabled', true);
            }

            $("#action2").prop('disabled', false);
            $("#action2").parent().css({background: '#fff'});
            if ($("#action2").val() != 'Close') {
                $("#actionInput2").prop('disabled', false);
            } else {
                $("#actionInput2").prop('disabled', true);
            }

        } else {
            $("#firstBtn").prop('disabled', true);
            $("#secondBtn").prop('disabled', true);
            if ($("#action1").val() != 'Close') {
                $("#actionInput1").prop('disabled', false);
            } else {
                $("#actionInput1").prop('disabled', true);
            }
            $("#action2").prop('disabled', true);
            $("#action2").parent().css({background: '#ccc7'});
            $("#actionInput2").prop('disabled', true);
        }

    }

    function setActions() {
        if ($("#messageType").val() == 'Full Screen' || $("#messageType").val() == 'Dialogue') {
            $(".btm_btns").children().eq(0).attr('action', $("#action1").val());
            $(".btm_btns").children().eq(1).attr('action', $("#action2").val());

            $(".btm_btns").children().eq(0).text($("#firstBtn").val());

            $(".btm_btns").children().eq(1).text($("#secondBtn").val());

            if ($("#action1").val() != "Close") {
                $(".btm_btns").children().eq(0).attr('href', $("#actionInput1").val());
            } else {
                $(".btm_btns").children().eq(0).attr('href', 'http://closeme.engagement.com/');
            }

            if ($("#action2").val() != "Close") {
                $(".btm_btns").children().eq(1).attr('href', $("#actionInput2").val());
            } else {
                $(".btm_btns").children().eq(1).attr('href', 'http://closeme.engagement.com/');
            }

        } else {
            $("#divAction").parent().attr('action', $("#action1").val());

            if ($("#action1").val() != "Close") {
                $("#divAction").parent().attr('href', $("#actionInput1").val());
            } else {
                $("#divAction").parent().attr('href', 'http://closeme.engagement.com/');
            }
        }

        if ($("#messageType").val() == 'Banner') {
            $("#divAction").parent().attr('id', $("#campaignCreationid").val());
        } else {
            $(".btm_btns").children().eq(0).attr('id', $("#campaignCreationid").val());
            $(".btm_btns").children().eq(0).attr('id', $("#campaignCreationid").val());
        }

        if (typeOfCampaign == 'push') {
            var obj = getDateTime();
            $(".time_date h1").text(obj.time);
            $(".time_date span").text(obj.date);

            $("#inappMessage").prop('disabled', false);
            $("#messageType").parent().css({'display': 'none'});
            $("#layout").parent().css({'display': 'none'});
            //$("#titleDiv").css({'display': 'none'});
            $("#actionButtonTextDiv").css({'display': 'none'});
            //$("#section1Message").css({'display': 'none'});
            //$("#section1Div").css({'display': 'none'});
            //$("#section2Div").css({'display': 'none'});
            $("#section3Div").css({'display': 'none'});
            $("#sdkVersionDiv").css({'display': 'none'});
        } else {
            $("#messageType").parent().css({'display': 'block'});
            $("#layout").parent().css({'display': 'block'});
            $("#titleDiv").css({'display': 'block'});
            $("#actionButtonTextDiv").css({'display': 'block'});
            $("#section1Message").css({'display': 'block'});
            $("#section1Div").css({'display': 'block'});
            $("#section2Div").css({'display': 'block'});
            $("#section3Div").css({'display': 'block'});
            $("#sdkVersionDiv").css({'display': 'block'});
        }
    }

    function setTemplatedesign() {

        if ($("#messageType").val() == 'Dialogue') {
            $("#headerDiv").css({display: "block"});
            $("#headingT").css({color: $("#headerTextColor").val()});
            $("#headingT").parent().children().eq(1).css({color: $("#contentTextColor").val()});

            //$("#buttonsDesign").css({display: "none"});

            $("#buttonsDesign").css({display: "block"});
            $(".btm_btns").children().eq(0).css({background: $("#btn1BackgroundColor").val()});
            $(".btm_btns").children().eq(0).css({opacity: $("#btn1BackgroundOpacity").val()});
            $(".btm_btns").children().eq(0).css({color: $("#btn1textColor").val()});

            $(".btm_btns").children().eq(1).css({background: $("#btn2BackgroundColor").val()});
            $(".btm_btns").children().eq(1).css({opacity: $("#btn2BackgroundOpacity").val()});
            $(".btm_btns").children().eq(1).css({color: $("#btn2textColor").val()});


            $(".back_color").css({display: "block"});
            $("#positionDiv").children().eq(0)/*.children().eq(0)*/.css({background: $("#backgroundColor").val()});
            $("#positionDiv").children().eq(0)/*.children().eq(0)*/.css({opacity: $("#backgroundOpacity").val()});

            $(".fram_color").css({display: "block"});
            $("#positionDiv").children().eq(0).css({'border': '1px solid ' + $("#frameColor").val()});
        } else if ($("#messageType").val() == 'Full Screen' /*|| $("#messageType").val() == 'Dialogue'*/) {
            $("#headerDiv").css({display: "block"});
            $("#headingT").css({color: $("#headerTextColor").val()});
            $("#headingT").parent().children().eq(1).css({color: $("#contentTextColor").val()});

            $("#buttonsDesign").css({display: "block"});
            $(".btm_btns").children().eq(0).css({background: $("#btn1BackgroundColor").val()});
            $(".btm_btns").children().eq(0).css({opacity: $("#btn1BackgroundOpacity").val()});
            $(".btm_btns").children().eq(0).css({color: $("#btn1textColor").val()});

            $(".btm_btns").children().eq(1).css({background: $("#btn2BackgroundColor").val()});
            $(".btm_btns").children().eq(1).css({opacity: $("#btn2BackgroundOpacity").val()});
            $(".btm_btns").children().eq(1).css({color: $("#btn2textColor").val()});


            $(".back_color").css({display: "block"});
            $("#loadInAppTemplate").children().eq(1).css({background: $("#backgroundColor").val()});
            $("#loadInAppTemplate").children().eq(1).css({opacity: $("#backgroundOpacity").val()});

            $(".fram_color").css({display: "block"});
            $("#loadInAppTemplate").children().eq(1).css({'border': '1px solid ' + $("#frameColor").val()});
        } else if ($("#messageType").val() == 'Banner') {
            $("#headerDiv").css({display: "none"});
            $("#buttonsDesign").css({display: "none"});

            $(".back_color").css({display: "block"});
            $("#loadInAppTemplate").children().eq(1).children().eq(0).css({background: $("#backgroundColor").val()});
            $("#loadInAppTemplate").children().eq(1).children().eq(0).css({opacity: $("#backgroundOpacity").val()});

            $(".fram_color").css({display: "block"});
            $("#loadInAppTemplate").children().eq(1).children().eq(0).css({'border': '1px solid ' + $("#frameColor").val()});
        }

    }

    function populateSelect(data, id) {
        var str = '';
        for (var i = 0; i < data.length; i++) {
            str += '<option value="' + data[i] + '">' + data[i] + '</option>'
        }
        $(id).html(str);
    }

    function fetchEditorAndData(type, content = '') {
        if (editor) {
            editor.destroy();
        }

        editor = CKEDITOR.replace('editor', {
            fullPage: true,
            allowedContent: true,
            language: $("#selectLang").children('span.active').attr('data-lang'),
            height: 500,
        });

        editor.addCommand("mySimpleCommand", {
            exec: function (edt) {
                $('#exampleModalCenter').modal('show');
                $("#uploadError").text("");
            }
        });
        editor.ui.addButton('SuperButton', {
            label: "Media Gallery",
            command: 'mySimpleCommand',
            icon: addImageIcon
        });

        editor.addCommand("insertAttributeCommand", {
            exec: function (edt) {
                $('#attributeDataModel').modal('show');
            }
        });

        editor.ui.addButton('insertAttributeButton', {
            label: "Insert Attribute",
            command: 'insertAttributeCommand',
            icon: attributeImg
        });

        if (type == 'create') {
            CKEDITOR.instances['editor'].setData(atob($("#" + activeClassId + ' h1').attr('data-content')));
        } else {
            CKEDITOR.instances['editor'].setData(content);
        }

        if (campaignStatus == 'create') {
            editorSituation = 'read';
        }
    }

    function encodeHTML(s) {
        return s.replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/"/g, '&quot;');
    }

    function checkDisabled(id) {
        if ($(id).prop('disabled')) {
            return -1;
        }
        return $(id).val().trim();
    }

    function getUserIdByEmail() {
        $(".fa-spin").css({display: "inline-block"});
        $("#emailTestPreview").prop("disabled", true);
        $("#sendPreview").prop("disabled", true);
        var url = baseUrl + '/backend/campaign/getUserIdByEmail';
        $.ajax({
            type: 'POST',
            global: false,
            url: url,
            data: {
                companyId: $(".companyId").val(),
            },
            dataType: 'json',
            success: function (response) {
                if (!response.status)
                    toastr.error(response.message);
                else {
                    if ($("#campaigns_type").val() == 1) {
                        testPreviewEmail(response.data);
                    } else {
                        testPreviewInAppPush(response.data);
                    }
                }

            },
            error: function () {

            }
        });
    }

    function testPreviewEmail(obj) {
        obj.row_id = $("#userSelectEmail").val();
        obj.sender_email = $("#emailTestUsers").val();
        obj.sender_name = $("#name").val();
        obj.subject = $("#subjectTestUsers").val();
        obj.type = "email";
        obj.message = CKEDITOR.instances['editor'].getData();
        notificationApi(obj);
    }

    function testPreviewInAppPush(obj) {
        obj.row_id = $("#userSelect").val();
        if ($("#campaigns_type").val() == 2) {
            obj.title = $("#headingT").text().trim();
            obj.message = $("#headingT").parent().children().eq(1).text().trim();
            obj.target = $("#actionInput1").val().trim();
            obj.type = 'push';

            if ($("#sandBoxSelection").prop("checked")) {
                obj.is_test_device = true;
            }
        } else if ($("#campaigns_type").val() == 3) {
            if ($("#messageType").val() != "Dialogue") {
                obj.message = $("#loadInAppTemplate").children().eq(1).prop('outerHTML');
                obj.custom = {
                    message_type: $("#messageType").val().toLowerCase(),
                    message_position: ""
                };
            } else {
                obj.message = $("#positionDiv").html();
                obj.custom = {
                    message_type: $("#messageType").val().toLowerCase(),
                    message_position: $("#devicePosition").val().toLowerCase()
                };
            }
            obj.auto_close = ($("#messageType").val().toLowerCase() == "banner" || ($("#firstBtn").val() == "" || $("#secondBtn").val() == "")) ? true: false;
            obj.type = 'inapp';
        }

        obj.platform = $("#plateForm").val();
        notificationApi(obj);
    }

    function notificationApi(obj) {
        var url = baseUrl + '/api/v1/message/send';
        $.ajax({
            type: 'POST',
            global: false,
            url: url,
            data: obj,
            dataType: 'json',
            timeout: 30000,
            success: function (response) {
                $(".fa-spin").css({display: "none"});
                $("#emailTestPreview").prop("disabled", false);
                $("#sendPreview").prop("disabled", false);

                if (response.meta.status.toLowerCase() == "error") {
                    for (var i = 0; i < response.errors.length; i++) {
                        toastr.error(response.errors[i]);
                    }
                } else {
                    for (var i = 0; i < response.data.length; i++) {
                        if (response.data[i].status.toLowerCase() == "error")
                            toastr.error(response.data[i].message);
                        else {
                            toastr.success(response.data[i].message);
                        }
                    }
                }
            },
            error: function (jqXHR, textStatus, errorThrown) {
                if (textstatus == "timeout") {
                    toastr.error("Got Timeout");
                } else {
                    var response = jqXHR.responseJSON;
                    if (response.meta.status.toLowerCase() == "error") {
                        for (var i = 0; i < response.errors.length; i++) {
                            toastr.error(response.errors[i]);
                        }
                    }
                    $("#emailTestPreview").prop("disabled", false);
                    $("#sendPreview").prop("disabled", false);
                    $(".fa-spin").css({display: "none"});
                }
            },
        });
    }

    function populateCampaignConversion(conversion) {
        campaignConversionArr = conversion;
        var campaignConversionNames = [];
        for (var i = 0; i < campaignConversionArr.length; i++) {
            campaignConversionNames.push(campaignConversionArr[i].name);
        }
        populateConversionHtml(campaignConversionNames);
    }

    function populateAction(action) {
        campaignActionsArr = action;
        var campaignActionNames = [];
        for (var i = 0; i < campaignActionsArr.length; i++) {
            campaignActionNames.push(campaignActionsArr[i].name);
        }
        populateActionsHtml(campaignActionNames);
    }

    function getActionForCampaign() {
        if ($("#contactChoice1").parent().hasClass("active")) {
            var arr = [];
            $("#actionCollection").children().each(function () {
                if ($(this).hasClass("true")) {
                    var getInputs = $(this).children().eq(0).children().eq(0).children();

                    var obj = {
                        actionType: getInputs.eq(0).attr("data-id"),
                        actionValue: getInputs.eq(1).val()
                    };

                    for (var i = 0; i < arr.length; i++) {
                        if (JSON.stringify(arr[i]) == JSON.stringify(obj))
                            break;
                    }
                    if (i >= arr.length)
                        arr.push(obj);
                }
            });
            return arr;
        }
        return -1;
    }

    function getConversionForCampaign() {
        var arr = [];
        $("#conversionCollection").children().each(function () {
            if ($(this).hasClass("true")) {
                var getInputs = $(this).children().eq(0).children().eq(0).children();

                var obj = {
                    conversionType: getInputs.eq(0).attr("data-id"),
                    conversionValue: getInputs.eq(1).val(),
                    conversionValidity: getInputs.eq(2).children().eq(0).children().eq(0).val(),
                    period: getInputs.eq(2).children().eq(0).children().eq(1).val()
                };

                for (var i = 0; i < arr.length; i++) {
                    if (arr[i].conversionType == obj.conversionType && arr[i].conversionValue == obj.conversionValue)
                        break;
                }
                if (i >= arr.length)
                    arr.push(obj);
            }
        });

        return arr;
    }

    function populateActionsHtml(action) {

        var str = '<option value="-1" selected>Choose Actions...</option>';
        for (var i = 0; i < action.length; i++) {
            str = str + '<option value="' + action[i] + '">' + encodeHTML(action[i]) + '</option>';
        }
        $("#actionMultiSelect").html(str);
    }

    function populateConversionHtml(conversion) {

        var str = '<option value="-1" selected>Choose Conversion...</option>';
        for (var i = 0; i < conversion.length; i++) {
            str = str + '<option value="' + conversion[i] + '">' + encodeHTML(conversion[i]) + '</option>';
        }
        $("#conversionMultiSelect").html(str);
    }

    function populateApps(apps) {

        var str = '';
        for (var i = 0; i < apps.length; i++) {
            str += '<div class="conversion_event_list ">';
            str += '<div class="camp_timing_check_box">';
            str += '<input type="checkbox" data-orgId="' + apps[i].id + '" id="' + apps[i].id + '_apps" name="contact">';
            str += '<label for="' + apps[i].id + '_apps"> </label>';
            str += '</div>';
            str += '<span>';
            str += '<img border="0" src="' + apps[i].logoUrl + '" alt="#">';
            str += '</span>';
            str += '<p>';
            str += apps[i].name;
            str += '<b>' + apps[i].platform + '</b>';
            str += '</p>';
            str += '</div>';
        }

        $("#appendApps").append(str);
    }

    function populateStartDateUtc() {
        var isoDate = new Date().toISOString();
        var mints = isoDate.split(":")[1];
        var hours = isoDate.split(":")[0].split("T")[1];
        $("#startDate").val(isoDate.split(":")[0].split("T")[0]);
        $(".shour select").val(hours);
        $(".smint select").val(mints);
        $(".shour select, .smint select").trigger("change");
    }

    function checkForAllCheckedApps() {
        var allCheck = true;

        $("#appendApps").children().find('input').each(function () {
            if (!$(this).prop("checked")) {
                allCheck = false;
            }
        });

        if (allCheck) {
            $("#e2-rec_comp3").prop("checked", true);
        }
    }

    function uploadGalleryImage(galleryImg) {
        var data = new FormData();
        data.append('file', galleryImg[0], galleryImg[0].name);
        data.append('_token', $("#tokenForUploadImg").val());

        $(".fa-spin").css({display: "inline-block"});
        $.ajax({
            type: 'POST',
            url: baseUrl + '/gallery/do_upload',
            cache: false,
            processData: false,
            global: false,
            contentType: false,
            dataType: 'json',
            data: data,
            headers: {
                'X-CSRF-Token': $('input[name="_token"]').val()
            },
            success: function (response) {


                $(".fa-spin").css({display: "none"});
                $("#galleryUpload").val("");
                if (response.status == 200) {

                    toastr.success("Image Uploaded");
                    swal({
                        title: "Want to Crop?",
                        //icon: "warning",
                        buttons: {
                            no: "No",
                            yes: "Yes",
                        },
                        dangerMode: true,
                    }).then((value) => {

                        switch (value) {

                            case "no":

                                var ImageUrl = response.image_url;
                                $("#exampleModalCenter").modal('hide');


                                if (typeOfCampaign == 'email') {
                                    var imageTag = '<image src="' + ImageUrl + '"/>';
                                    CKEDITOR.instances['editor'].insertHtml(imageTag);
                                    $("#cropImageSelected").modal('hide');
                                } else {

                                    checkImageRequirement(ImageUrl).then((response) => {
                                        if (!response) {
                                            $(element).parent().css({background: "none"});
                                            if ($(element).parent().prop("tagName") == "LABEL") {
                                                $(element).parent().parent().css({background: "none"});
                                            }
                                            $(element).parent().children('img').attr("src", ImageUrl);
                                            element = '';
                                            $("#cropImageSelected").modal('hide');
                                        }
                                    });
                                }
                                $("#uploadError").text("");

                                toastr.success("image successfully cropped and save");
                                $('#gallery').DataTable().draw();
                                break;

                            case "yes":

                                var image_url = response.image_url;
                                $("#crop_info").css({display: "inline-block"});
                                $("#cropImageSelected").modal("show");
                                cropper.clear();
                                cropper.replace(image_url);
                                $('#exampleModalCenter').modal('hide');
                                break;

                            default:
                                swal("Wrong Choice!");
                        }
                    });
                    $('#gallery').DataTable().draw();
                } else {

                    toastr.error(response.msg);
                }

            },
            error: function () {
                $(".fa-spin").css({display: "none"});
            }
        });

    }

    function validateEmailTestUsers() {

        var error = false;

        if ($("#emailTestUsers").val() == "") {
            $("#emailTestUsersError").text("Email is required");
            error = true;
        } else {

            var emailReg = /[A-Z0-9._%+-]+@[A-Z0-9.-]+\.[A-Z]{2,4}/igm;
            if (!emailReg.test($("#emailTestUsers").val())) {
                $("#emailTestUsersError").text("Invalid Email");
                error = true;
            } else {
                $("#emailTestUsersError").text("");
            }

        }

        if ($("#subjectTestUsers").val() == "") {
            $("#subjectTestUsersError").text("Subject is required");
            error = true;
        } else {
            $("#subjectTestUsersError").text("");
        }


        if ($("#userSelectEmail").val() == null) {
            $("#userErrorEmail").text("Please select user(s)");
            error = true;
        } else {
            $("#userErrorEmail").text("");
        }

        return error;
    }

    function validatePushInAppUsers() {
        var errors = false;
        if ($("#userSelect").val() == null) {
            $("#userError").text("Please select user(s)");
            errors = true;
        } else {
            $("#userError").text("");
        }
        return errors;
    }

    function imgUploadValidation() {
        var errors = false;
        var path = $("#galleryUpload").val();
        if (path) {
            var name = path.split('\\').pop();
            if (name != "") {
                if (name.split(".")[1].toLowerCase() != 'png' && name.split(".")[1].toLowerCase() != 'jpg' && name.split(".")[1].toLowerCase() != 'jpeg' && name.split(".")[1].toLowerCase() != 'gif') {
                    $("#uploadError").text(".png, jpg, jpeg and .gif formats only");
                    errors = true;
                } else {
                    $("#uploadError").text("");
                }
            }
        } else {
            $("#uploadError").text("Please choose file");
            errors = true;
        }
        return errors;
    }

    function getDateTime() {
        var date = new Date();
        var time = date.toLocaleString('en-US', {hour: 'numeric', minute: 'numeric', hour12: true});

        date += date + '';
        date = date.split(" ");
        date = date[0] + ' ' + date[2] + ', ' + date[1];

        return {
            time: time,
            date: date
        };
    }

    function checkIfTemplateExistInDb(lang) {
        var url = baseUrl + '/backend/checkAndGetCampaignTemplate';
        $.ajax({
            type: "post",
            url: url,
            data: {
                column: lang,
                campaignId: $("#campaignCreationid").val(),
                _token: $('input[name="_token"]').val(),
            },
            headers: {
                'X-CSRF-Token': $('input[name="_token"]').val()
            },
            dataType: "json",
            success: function (response) {
                var checkStep = $(".step-steps").children().filter('.active').children().eq(0).attr('href');
                if (checkStep != "#step6") {
                    if (response.status) {
                        loadEditorData(lang, response.data);
                    } else {
                        loadEditorData(lang);
                    }
                } else {
                    if (response.status) {
                        editorPreview(lang, response.data);
                    }
                }
            },
            error() {
                //alert("this cause error");
            }
        });
    }

    function checkIfInAppPushTemplateExistInDb(lang) {
        var url = baseUrl + '/backend/checkAndGetCampaignTemplate';
        $.ajax({
            type: "post",
            url: url,
            data: {
                column: lang,
                campaignId: $("#campaignCreationid").val(),
                _token: $('input[name="_token"]').val(),
            },
            headers: {
                'X-CSRF-Token': $('input[name="_token"]').val()
            },
            dataType: "json",
            success: function (response) {
                var checkStep = $(".step-steps").children().filter('.active').children().eq(0).attr('href');
                if (checkStep != "#step6") {
                    if (response.status) {
                        populateStep2(response.data);
                    } else {
                        setLanguageIndentation(lang);
                    }
                } else {
                    if (response.status) {
                        var str = '<div class="tp_header clearfix" style="display: block;width:100%;background:#000;position:absolute;top:0;left:0;"><ul class=" clearfix" style="padding:6px 10px; margin:0;"><li style=" float:left; list-style:none;"><span style="/*background:#fff;*/ padding:5px; display:block;"><i class="fa fa-wifi" style="color: white"></i></span></li><li style=" float:right; list-style:none;"><span style="/*background:#fff;*/ padding:5px; display:block; "><i class="fa fa-battery-full" style="color: white"></i></span></li></ul></div>';
                        $("#step6Preview").html(str + response.data.templateInfo.template);
                    }
                }
            },
            error() {
                //alert("this cause error");
            }
        });
    }

    function setLanguageIndentation(lang) {

        if ($("#campaigns_type").val() == 2) {
            if (lang == 'ar') {
                $("#headingT").css({direction: 'rtl'});
                $("#headingT").parent().children().eq(1).css({direction: 'rtl'});
                $("#inappType").css({direction: 'rtl'});
                $("#inappMessage").css({direction: 'rtl'});
            } else {
                $("#headingT").css({direction: 'ltr'});
                $("#headingT").parent().children().eq(1).css({direction: 'ltr'});
                $("#inappType").css({direction: 'ltr'});
                $("#inappMessage").css({direction: 'ltr'});
            }
        } else if ($("#campaigns_type").val() == 3) {
            if (lang == 'ar') {
                $("#headingT").css({direction: 'rtl'});
                $("#headingT").parent().children().eq(1).css({direction: 'rtl'});
                $("#inappType").css({direction: 'rtl'});
                $("#inappMessage").css({direction: 'rtl'});
                $("#firstBtn").css({direction: 'rtl'});
                $("#secondBtn").css({direction: 'rtl'});
            } else {
                $("#headingT").css({direction: 'ltr'});
                $("#headingT").parent().children().eq(1).css({direction: 'ltr'});
                $("#inappType").css({direction: 'ltr'});
                $("#inappMessage").css({direction: 'ltr'});
                $("#firstBtn").css({direction: 'ltr'});
                $("#secondBtn").css({direction: 'ltr'});
            }
        }
    }

    function loadEditorData(lang, content = "") {
        if (editor) {
            editor.destroy();
        }

        editor = CKEDITOR.replace('editor', {
            fullPage: true,
            allowedContent: true,
            language: lang,
            height: 500,
        });

        editor.addCommand("mySimpleCommand", {
            exec: function (edt) {
                $('#exampleModalCenter').modal('show');
                $("#uploadError").text("");
            }
        });

        editor.ui.addButton('SuperButton', {
            label: "Media Gallery",
            command: 'mySimpleCommand',
            icon: addImageIcon
        });

        editor.addCommand("insertAttributeCommand", {
            exec: function (edt) {
                $('#attributeDataModel').modal('show');
            }
        });

        editor.ui.addButton('insertAttributeButton', {
            label: "Insert Attribute",
            command: 'insertAttributeCommand',
            icon: attributeImg
        });

        if (content != "") {
            CKEDITOR.instances['editor'].setData(content);
        }
    }

    function updateDeliveryControl() {
        if ($("#rec_comp").prop("checked")) {
            $("#hideLastThreeChildren").children().slice(-3).each(function () {
                $(this).css({"display": "block"});
            });
            $("#priorityDelieveryType").css({"display": "block"});
        } else {
            $("#deliveryInput").val(1);
            $("#deliveryValue").val("Minute");
            $("#deliveryPriority").val("Medium");
            $("#hideLastThreeChildren").children().slice(-3).each(function () {
                $(this).css({"display": "none"});
            });
            $("#priorityDelieveryType").css({"display": "none"});
        }
    }

    function populateInAppfromChangeLanguage(obj) {

        $("#actionError1").text("");
        $("#actionError2").text("");

        var getDesign = obj.templateInfo.design;
        $("#headerTextColor").val(getDesign.header.headingColor);
        $("#contentTextColor").val(getDesign.header.contentColor);

        $("#btn1BackgroundColor").val(getDesign.button1.color);
        $("#btn1textColor").val(getDesign.button1.textColor);
        $("#btn1BackgroundOpacity").val(getDesign.button1.opacity);
        $("#btn1BackgroundOpacity").trigger('change');

        $("#btn2BackgroundColor").val(getDesign.button2.color);
        $("#btn2textColor").val(getDesign.button2.textColor);
        $("#btn2BackgroundOpacity").val(getDesign.button2.opacity);
        $("#btn2BackgroundOpacity").trigger('change');
        $("#backgroundColor").val(getDesign.background.color);
        $("#backgroundOpacity").val(getDesign.background.opacity);
        $("#backgroundOpacity").trigger('change');

        $("#frameColor").val(getDesign.frame.color);

        if (obj.templateInfo.title != -1)
            $("#inappType").val(obj.templateInfo.title);
        if (obj.templateInfo.message != -1)
            $("#inappMessage").val(obj.templateInfo.message);

        $("#loadInAppTemplate").children().eq(1).remove();
        $(obj.templateInfo.template).insertAfter($("#loadInAppTemplate").children().eq(0));

        if (obj.templateInfo.action1.label != -1)
            $("#firstBtn").val(obj.templateInfo.action1.label);
        else
            $("#firstBtn").val('');

        $("#action1").val(obj.templateInfo.action1.type);

        if (obj.templateInfo.action1.value != -1)
            $("#actionInput1").val(obj.templateInfo.action1.value);
        else
            $("#actionInput1").val('');


        if (obj.templateInfo.action2.label != -1)
            $("#secondBtn").val(obj.templateInfo.action2.label);
        else
            $("#secondBtn").val('');

        $("#action2").val(obj.templateInfo.action2.type);

        if (obj.templateInfo.action2.value != -1)
            $("#actionInput2").val(obj.templateInfo.action2.value);
        else
            $("#actionInput2").val('');

        $("#inappType").trigger("input");
        $("#inappMessage").trigger("input");

        setGUIRules();
        setTemplatedesign();
        setLanguageIndentation(obj.language);
    }

    function populatePushfromChangeLanguage(data) {
        $("#inappType").val(data.templateInfo.title);
        if (data.templateInfo.message != -1)
            $("#inappMessage").val(data.templateInfo.message);

        $("#loadInAppTemplate").children().eq(1).remove();
        $(data.templateInfo.template).insertAfter($("#loadInAppTemplate").children().eq(0));

        $("#action1").val(data.templateInfo.action1.type);
        $("#actionInput1").val(data.templateInfo.action1.value);

        $("#inappType").trigger("input");
        $("#inappMessage").trigger("input");

        var obj = getDateTime();
        $(".time_date h1").text(obj.time);
        $(".time_date span").text(obj.date);

        setLanguageIndentation(data.language);
    }

    function editorPreview(lang, content) {
        if (editorPreviewInstance) {
            editorPreviewInstance.destroy();
        }
        editorPreviewInstance = CKEDITOR.replace('ckEditorPreview', {
            fullPage: true,
            allowedContent: true,
            language: lang,
            height: 500,
        });
        CKEDITOR.instances['ckEditorPreview'].setData(content);
        $(".editor_temp span").css({display: "none"});
    }


    async function checkImageRequirement(ImageUrl) {

        var obj = {};
        await getDimensions(ImageUrl).then((response) => {
            obj = response;
        });

        if ($("#messageType").val() == "Banner") {
            if (obj.width < 640 || obj.height < 1136) {

                toastr.error("Minimum optimum size for banner image should be Width: 640px Height: 1136px");
                return true;
            }
        } else if ($("#messageType").val() == "Full Screen") {

            if (obj.width < 600 || obj.height < 800) {

                toastr.error("Minimum optimum size for full screen image should be Width: 600px Height: 800px");
                return true;
            }
        }
        return false;
    }

    function getDimensions(ImageUrl) {

        return new Promise(function (resolve, reject) {
            var img = new Image();
            img.src = ImageUrl;
            img.onload = function () {
                var obj = {
                    width: this.width,
                    height: this.height,
                };
                resolve(obj);
            }
        });

    }

});


function selectAttribute(attribute) {
    if (typeOfCampaign == 'email') {
        var attributeSpan = '&lrm;[[$' + attribute + ']]&lrm;';
        CKEDITOR.instances['editor'].insertHtml(attributeSpan);
    } else {
        var id = '';
        if (tagName == 'INPUT') {
            id = '#inappType';
        } else {
            id = '#inappMessage';
        }
        var cursorPosition = $(id).prop("selectionStart");

        var value = $(id).val();
        var textBefore = value.substring(0, cursorPosition);
        var textAfter = value.substring(cursorPosition, value.length);
        var attributeSpan = '&lrm;[[$' + attribute + ']]&lrm;';
        $(id).val(textBefore + htmlDecode(attributeSpan) + textAfter);
        $(id).trigger("input");

    }
    $('#attributeDataModel').modal('hide');
}

function htmlDecode(value) {
    return $('#inAppPushPlaceHolders').html(value).text();
}

function clearSegment(id) {
    var index = segmentArray.indexOf(parseInt(id.split("_")[0]));
    if (index > -1) {
        segmentArray.splice(index, 1);
        $("#" + id + "Main").remove();
        $("#" + id + "Inner").remove();
    }
}

function openGallery(elObj) {
    element = elObj;
    $("#galleryUpload").val("");
    if (step != "#step6")
        $('#exampleModalCenter').modal('show');
    $("#uploadError").text("");
}

function openAttributeModal(tag) {
    if ($('#messageType').val() != "Banner" || $('#campaigns_type').val() != 3) {
        tagName = tag;
        $('#attributeDataModel').modal('show');
    }

}

function clearActions(ele) {
    var firstParent = $(ele).parent().parent();
    var secondParent = $(ele).parent().parent().next();
    firstParent.remove();
    secondParent.remove();
}

function clearConversion(ele) {
    var firstParent = $(ele).parent().parent();
    var secondParent = $(ele).parent().parent().next();
    firstParent.remove();
    secondParent.remove();
    conversionListCount--;
}

function goBack(counter) {
    for (var i = 0; i < counter; i++) {
        $("#back").trigger('click');
    }
}
