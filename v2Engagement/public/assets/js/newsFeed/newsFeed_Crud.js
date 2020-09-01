$(document).ready(function () {
    var langArr = [];
    var content = "";
    var prevStep = $(".step-steps").children().filter(".active").children("a").attr("href");
    var steps = ["next", "launch_btn", "draft"];
    var isImageSelected = true;
    var status = null;
    var stepNewfeed = null;
    var today = new Date().toISOString().split('T')[0];
    var enlishContentImg = null;

    function updateFinalPreview() {

        content = $("#preview_div").html();
        $("#preview_final").html(content);
    }

    function placeImage(image_url) {

        $("#exampleModalCenter").modal('hide')


        $(".col-xs-2 img").fadeIn("slow").attr('src', image_url);
        $('.content_holder img').fadeIn("slow").attr('src', image_url);
        $('#image_url').val(image_url);
        $("#cropImageSelected").modal('hide');
        $('#gallery').DataTable().draw();
        isImageSelected = false;
        $("#imageError").slideUp();

    }

    function openCropper(image_url) {
        $("#cropImageSelected").modal("show");
        cropper.clear();
        cropper.replace(image_url);
        $('#exampleModalCenter').modal('hide');
    }

    $(".link_type").on("change", function () {
        targetId = $(this).attr('data-id');

        if ($(this).val() === 'DEEPLINK') {
            $("#" + targetId).attr('type', 'text');

        } else {

            $("#" + targetId).attr('type', 'url');

        }


    });

    function imgUploadValidation() {
        var errors = false;
        var path = $("#galleryUpload").val();
        if (path) {
            var name = path.split('\\').pop();
            if (name != "") {
                if (name.split(".")[1].toLowerCase() != 'png' && name.split(".")[1].toLowerCase() != 'gif' && name.split(".")[1].toLowerCase() != 'jpg') {
                    $("#uploadError").text("Uploaded file is not a valid image. Only JPG, JPEG, PNG and GIF files are allowed");
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
            contentType: false,
            global: false,
            dataType: 'json',
            data: data,
            headers: {
                'X-CSRF-Token': $('input[name="_token"]').val()
            },
            success: function (response) {

                $(".fa-spin").css({display: "none"});
                if (response.status == 200) {

                    toastr.success("Image Uploaded");
                    swal({
                        title: "Want to Crop?",
                        icon: "warning",
                        buttons: {
                            no: "No",
                            yes: "Yes",
                        },
                        dangerMode: true,
                    }).then((value) => {

                        switch (value) {

                            case "no":

                                placeImage(response.image_url);

                                break;

                            case "yes":

                                openCropper(response.image_url);

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

    /*
        open recent added view
    */
    function openEditView(newsfeed) {

        if (newsfeed.step !== 'COMPOSE') {

            $("li.active").removeClass("active");
            var elementSelect = '#' + newsfeed.step;
            $("[href=" + elementSelect + "]").parent('li').addClass("active");
            $(".newsfeed").removeClass("active");
            $(".newsfeed").addClass("hide");
            $(elementSelect).addClass("active");
            $(elementSelect).removeClass("hide");
            $("[data-direction='prev']").fadeIn('slow');
            prevStep = elementSelect;

            if (newsfeed.step === 'CONFIRM') {
                $(".launch_btn").slideDown();
                $("[data-direction='next']").slideUp();
            }
        }
    }

    function populateEditData(newsfeed) {

        enlishContentImg = newsfeed.image_url;
        if (newsfeed.segment_id) {
            $("#seg_id").val(newsfeed.segment_id);
        }
        isImageSelected = false;
        if (newsfeed.location_id != 0) {
            $("#loc_id").val(newsfeed.location_id);
        }

        $("[name='link_type_ios']").val(newsfeed.link_type_ios);
        $("[name='link_type_android']").val(newsfeed.link_type_android);
        $("[name='link_type_window']").val(newsfeed.link_type_window);
        $("[name='link_type_web']").val(newsfeed.link_type_web);
        $("#select_card_type").val(newsfeed.news_feed_template_id).trigger("change");

        if (newsfeed.category) {

            $("[name='newsfeed_category']").val(newsfeed.category)
        }
        if (newsfeed.start_time) {
            var mints = newsfeed.start_time.split(":")[1];
            var hours = newsfeed.start_time.split(":")[0].split(" ")[1];
            var startDate = newsfeed.start_time.split(":")[0].split(" ")[0];
            $("#startHour").val(hours);
            $("#startmin").val(mints);
            $("#startDate").val(startDate);
        }

        if (newsfeed.enable_end_time == 1) {

            var mintsEx = newsfeed.end_time.split(":")[1];
            var hoursEx = newsfeed.end_time.split(":")[0].split(" ")[1];
            var startDateEx = newsfeed.end_time.split(":")[0].split(" ")[0];
            $("#endHour").val(hoursEx);
            $("#endmin").val(mintsEx);
            $("#endDate").val(startDateEx);
        }

        if (newsfeed.status === 'active') {
            status = 'active';
        }

        if (newsfeed.step === 'CONFIRM') {
            stepNewfeed = 'CONFIRM';
        }
        updateFinalPreview();

        $.get(baseUrl + '/backend/newsfeed/segment/count?id=' + $("#seg_id").val(), function (data) {

            $("#segemntCount").html(data.count);
            $(".reachable_user").removeClass("hide");
            if ($("#seg_id").val() == "") {
                $("#segemntCount").text("All");
            }
        });

        openEditView(newsfeed);
    }

    $.get(baseUrl + "/backend/newsfeed/preloadData?newsfeedId=" + $("#newsfeedId").val(), function (data) {

        $("#select_card_type").append(data.template);
        $("#seg_id").append(data.segment);
        $("#loc_id").append(data.location);
        if (data.newsfeed) {
            populateEditData(data.newsfeed);
        }
    });

    $('#newstags').on('beforeItemAdd', function (event) {
        var tag = event.item;

        if (/\s/.test(tag)) {
            if (!event.options || !event.options.preventPost) {
                $('#newstags').tagsinput('add', tag.replace(/\s/g, '_'), {preventPost: true});
            }
        }
    });


    $(".tags").on('itemAdded', function (event) {
        if ($(".tags").val() != '') {
            $(".tags").parent().children().filter('.bootstrap-tagsinput').find('input').attr('placeholder', '');
        }
        if (/\s/.test(event.item)) {
            $('.tags').tagsinput('remove', event.item);
        }
    });

    $(".tags").on('itemRemoved', function (event) {
        if ($(".tags").val() == '') {
            $(".tags").parent().children().filter('.bootstrap-tagsinput').find('input').attr('placeholder', 'Enter Tag(s)');
        }
    });


    $("#startDate").attr("min", today);
    $("#endDate").attr("min", today);
    $(document).on("click", "#cancelCaropButton", function () {


        swal({
            title: "Do you want to discard save changes?",
            icon: "warning",
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
    });

    $("#galleryUpload").on("change", function (e) {
        e.preventDefault();
        imgUploadValidation();
        galleryImg = e.target.files;
    });
    $("#galleryUploadBtn").on("click", function () {
        if (!imgUploadValidation())
            uploadGalleryImage(galleryImg);
    });

    $(".db_content_holder").show();
    $(".db_content_listing_holder").hide();

    const image = document.getElementById('cropImage');
    const cropper = new Cropper(image, {
        responsive: false,
        background: false,
        modal: false,
        crop: function (e) {
            var data = e.detail;
            $(".cropper_height_width").children().eq(0).text("Height: " + Math.round(data.height) + "px");
            $(".cropper_height_width").children().eq(1).text("Width: " + Math.round(data.width) + "px");
        }

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

                        placeImage(data.image_url);
                        toastr.success("image successfully cropped and save");
                    }

                },
                error() {
                    $(".fa-spin").css({display: "none"});
                },
            });
        });
    });
    $(document).on("click", ".selectImage", function () {

        var image_url = $(this).attr("data-image-name");
        placeImage(image_url);
    });

    $(document).on("click", ".cropImage", function () {

        var image_url = $(this).attr("data-image-name");
        $("#cropImageSelected").modal("show");
        cropper.clear();
        cropper.replace(image_url);
        $('#exampleModalCenter').modal('hide');
    });

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
            }

        });

    }

    $("#seg_id").on("change", function () {

        $.get(baseUrl + '/backend/newsfeed/segment/count?id=' + $(this).val(), function (data) {

            $("#segemntCount").text(data.count);
            $(".reachable_user").removeClass("hide");
            if ($("#seg_id").val() == "") {
                $("#segemntCount").text("All");
            }
        });

    });


    $('#m_name').on("keyup", function () {

        var titleText = $('#m_name').val();
        $('#newsNameId').html(titleText);
        $('.newsNameId').html(titleText);
    });

    $('#m_title').on("keyup", function () {

        var titleText = $('#m_title').val();
        $('#title').html(titleText);
        $('.title').html(titleText);
        updateFinalPreview();
    });

    $('#m_desc').on("keyup", function () {

        var titleText = $('#m_desc').val();
        $('#description').html(titleText);
        $('.description').html(titleText);
        updateFinalPreview();

    });

    $('#m_link_text').on("keyup", function () {

        var titleText = $('#m_link_text').val();
        $('#link_title').html(titleText);
        updateFinalPreview();
    });


    $('.tags').tagsinput({
        allowDuplicates: false,
        maxChars: 15,
    });

    $(document).on('change', '#select_card_type', function (e) {
        langArr = [];
        $("#selectLangNewsFeed span").removeClass("active");
        $("#selectLangNewsFeed").children().filter("[data-lang='en']").addClass("active");
        $('#reload_preview').show();
        var valueSelected = this.value;
        var selecttionName = $(this).find(":selected").text();
        $("#newsFeedCardType").html(selecttionName);
        if (valueSelected != '') {

            $.ajax({
                type: 'get',
                url: baseUrl + '/backend/newsFeeds/getTemplateData/' + valueSelected,
                success: function (data) {
                    $("#preview_div").html(data);
                    $("#preview_final").html(data);
                    $('#reload_preview').hide('slow');
                    $("#select_card_type").prop("disabled", false);
                    var titleText = $('#m_title').val();
                    var image_url = enlishContentImg == null ? $('#image_url').val() : enlishContentImg;
                    if (titleText == '') {
                        $('.content_holder #title').html("Dummy Title");
                    } else {
                        $('.content_holder #title').html(titleText);
                    }

                    var descText = $('#m_desc').val();

                    if (descText == '') {
                        $('.content_holder  #description').html("Dummy Description");
                    } else {
                        $('.content_holder  #description').html(descText);
                    }

                    var linkText = $('#m_link_text').val();
                    if (linkText == '') {
                        $('.content_holder #link_title').html("Dummy Text");
                    } else {
                        $('.content_holder #link_title').html(linkText);
                    }
                    $('.preview_div  a').removeAttr('href');
                    if (image_url == '') {
                        $('.content_holder #icon').attr('src', 'http://devengagement.engagiv.com/assets/images/ureka_logo2.png')
                    } else {
                        $('.content_holder #icon').attr('src', image_url)
                    }

                    if (valueSelected == 4) {
                        $("#m_desc").val('');
                        $("#m_desc").prop('disabled', true);
                    } else {
                        $("#m_desc").prop('disabled', false);
                    }
                }
            });
        } else {

            $("#preview_div").html("");
            $("#preview_final").html("");
            $('#reload_preview').hide('slow');
        }

    });

    function openGallery() {

        $("#exampleModalCenter").modal('show');
        $("#galleryUpload").val("");
        $("#uploadError").text("");
    }

    function populateStartDateUtc() {
        var isoDate = new Date().toISOString();
        var mints = isoDate.split(":")[1];
        var hours = isoDate.split(":")[0].split("T")[1];
        $("#startDate").val(isoDate.split(":")[0].split("T")[0]);
        $("#startHour").val(hours);
        $("#startmin").val(mints);
    }

    $(document).on("click", "#icon", function (e) {

        openGallery();
    });


    $("#open_model").click(function () {

        openGallery();
    });


    function inArray(needle, haystack) {
        var length = haystack.length;
        for (var i = 0; i < length; i++) {
            if (haystack[i] == needle) return true;
        }
        return false;
    }


    function getStatus(step, dataDirection) {

        if (status) {

            return status;
        } else {

            if (dataDirection == 'draft') {
                return 'draft';
            }
            switch (step) {
                case "#COMPOSE":
                case "#DELIVERY":
                    return 'draft';
                    break;

                case "#CONFIRM":
                    return 'active';
                    break;
            }
        }
    }

    function getStepToSave(step) {

        if (stepNewfeed) {
            return stepNewfeed;
        } else {
            return step.replace("#", "")
        }
    }

    $.validator.addMethod("regx", function (value, element, regexpr) {
        return regexpr.test(value);
    }, "Please enter alpha numeric only.");
    $(".step-btn").on("click", function (e) {

        step = $(".step-steps").children().filter(".active").children("a").attr("href");
        var dataDirection = $(this).attr('data-direction');
        if (inArray(dataDirection, steps)) {

            switch (prevStep) {
                case '#COMPOSE':
                    $(prevStep).validate({
                        rules: {
                            m_name: {
                                required: true,
                                regx: /^[\u0600-\u065F\u066A-\u06EF\u06FA-\u06FFa-zA-Z]*[\u0600-\u065F\u066A-\u06EF\u06FA-\u06FFa-zA-Z0-9-_: ]*[\u0600-\u065F\u066A-\u06EF\u06FA-\u06FFa-zA-Z0-9]$/,
                            },
                            m_title: {
                                required: true,
                            },
                            link_text: {
                                required: true,
                            },
                        }

                    });
                    break;
                default:
                    $(prevStep).validate();
                    break;
            }


            $("#m_desc").valid();
            if (!$(prevStep).valid() || isImageSelected) {

                if (isImageSelected) {

                    if ($("#select_card_type").val() !== '') {
                        $("#imageError").slideDown();
                    }
                }
                if (dataDirection !== 'draft') {
                    $("[data-direction='prev']").trigger('click');
                } else {

                    return false;
                }
            } else {

                if (prevStep === '#DELIVERY') {

                    var startDateTime = $("#startDate").val() + ' ' + $("#startHour").val() + ':' + $("#startmin").val() + ':00';

                    if ($("#end_tm").is(":checked")) {

                        if ($("#endDate").val() === '') {

                            $('#endDateError').html('End date is required');
                            $("#endDate").focus();
                            $("[data-direction='prev']").trigger('click');
                            return;
                        } else {

                            var endDateTime = $("#endDate").val() + ' ' + $("#endHour").val() + ':' + $("#endmin").val() + ':00';
                            if (endDateTime <= startDateTime) {
                                $('#endDateError').html('End date must be greater than start date');
                                $("#endDate").focus();
                                $("[data-direction='prev']").trigger('click');
                                return;
                            } else {
                                $('#endDateError').html('');

                            }
                        }

                    } else {
                        $('#endDateError').html('');
                    }

                }

                if (prevStep == "#COMPOSE") {
                    saveLangObj();
                    lookForMissedContent();
                    var dataToSave = {
                        langArr,
                        token: $("#csr_token").val(),
                        newsfeedStep: getStepToSave(prevStep),
                        is_active: getStatus(prevStep, $(this).attr('data-direction')),
                        newsfeedId: $("#newsfeedId").val(),
                        type_id: $("#select_card_type").val(),
                        name: $("#m_name").val(),
                        newstags: $("#newstags").val(),
                        link_type_android: $("#link_type_android").val(),
                        android_url: $("#android_url").val(),
                        link_type_ios: $("#link_type_ios").val(),
                        ios_url: $("#ios_url").val(),
                        link_type_web: $("#link_type_web").val(),
                        web_url: $("#web_url").val(),
                        link_type_window: $("#link_type_window").val(),
                        window_url: $("#window_url").val(),
                        newsfeed_category: $("#newsfeed_category").val(),
                    };
                    updateFinalPreview();
                } else {
                    var dataToSave = $(prevStep).serialize() + "&_token=" + $("#csr_token").val() + "&newsfeedStep=" + getStepToSave(prevStep) + "&is_active=" + getStatus(prevStep, dataDirection) + "&newsfeedId=" + $("#newsfeedId").val();
                }
                var url = $("#save_url").val();

                $.ajax({
                    type: "POST",
                    data: dataToSave,
                    url: url,
                    dataType: "json",
                    success: function (data) {
                        if (data.error == false) {
                            $('#newsfeedId').val(data.result);
                            $(prevStep).removeClass("active");
                            $(prevStep).addClass("hide");
                            $(step).addClass("active");
                            $(step).removeClass("hide");

                            if (prevStep === '#DELIVERY') {
                                $(".launch_btn").fadeIn()
                            } else {

                                $(".launch_btn").fadeOut()
                            }

                            if (prevStep === '#CONFIRM' || dataDirection === 'draft') {

                                window.location = baseUrl + "/backend/newsfeed/list";

                            }
                            prevStep = step;
                            return;
                        }

                        if (data.error == true) {

                            toastr.error(data.message);
                            $("[data-direction='prev']").trigger('click');
                        }
                    },
                    error: function (data) {

                        toastr.error(data.message);
                        $("[data-direction='prev']").trigger('click');
                    }
                });
            }
        } else {

            $(prevStep).removeClass("active");
            $(prevStep).addClass("hide");
            $(step).addClass("active");
            $(step).removeClass("hide");
            $(".launch_btn").fadeOut();
            prevStep = step;
        }

    });


    $(".updated").on("click", function (e) {

        if ($(this).attr("data-action") === 'compose') {
            step = "#COMPOSE";

            $("[data-direction='prev']").trigger('click');
            $("[data-direction='prev']").trigger('click');
        } else {
            step = "#DELIVERY";
            $("[data-direction='prev']").trigger('click');
        }
        $(prevStep).removeClass("active");
        $(prevStep).addClass("hide");
        $(step).addClass("active");
        $(step).removeClass("hide");
        $(".launch_btn").fadeOut();
        prevStep = step;
        $("[data-direction='next']").fadeIn();
    });
    getGalleryData();
    populateStartDateUtc();

    function makeObj(serialArr) {
        var obj = {};
        for (var i = 0; i < serialArr.length; i++) {
            obj[serialArr[i].name] = serialArr[i].value;
        }
        obj["lang"] = $("#selectLangNewsFeed").children('span.active').attr('data-lang');

        return obj;
    }

    function saveLangObj() {
        var obj = makeObj($(prevStep).serializeArray());
        obj.image_url = $("#icon").attr("src");
        updateLangObj(obj);
    }

    function updateLangObj(obj) {
        for (var i = 0; i < langArr.length; i++) {
            if (langArr[i].lang == obj.lang) {
                langArr.splice(i, 1);
                break;
            }
        }
        langArr.push(obj);
    }

    function populateComposeStep(obj) {
        $("#m_title").val(obj.m_title);
        $("#m_title").trigger("keyup");
        $("#m_desc").val(obj.m_desc);
        $("#m_desc").trigger("keyup");
        $("#m_link_text").val(obj.link_text);
        $("#m_link_text").trigger("keyup");
        $("#icon").attr("src", obj.image_url);
    }

    function populateComposing() {
        for (var i = 0; i < langArr.length; i++) {
            if (langArr[i].lang == $("#selectLangNewsFeed").children('span.active').attr('data-lang')) {
                populateComposeStep(langArr[i]);
                break;
            }
        }

        if (i >= langArr.length) {
            fetchNewsfeedFromDataBase("compose", $("#selectLangNewsFeed").children('span.active').attr('data-lang'));
        }
    }

    function fetchNewsfeedFromDataBase(screen, lang) {
        $.ajax({
            type: "post",
            url: baseUrl + "/backend/newsFeed/get-multilingual",
            data: {
                id: $("#newsfeedId").val(),
                lang,
            },
            success: function (response) {
                if (response.status && screen == "compose") {
                    updateLangObj(response.data);
                    populateComposeStep(response.data);
                } else if (response.status && screen == "preview") {
                    populateViewStep(response.data);
                }
            },
            error: function (error) {

            }
        });
    }

    $("#cropper_mcsb").mCustomScrollbar({
        mouseWheel: {
            enable: false
        }
    });

    $("#selectLangNewsFeed span").click(function () {
        saveLangObj();
        $("#selectLangNewsFeed span").removeClass("active");
        $("#showLangNewsFeed span").removeClass("active");
        $(this).addClass("active");
        $("#showLangNewsFeed").children().filter("[data-lang='" + $(this).attr('data-lang') + "']").addClass("active");
        populateComposing();
    });

    $("#showLangNewsFeed span").click(function () {
        $("#showLangNewsFeed span").removeClass("active");
        $(this).addClass("active");
        fetchNewsfeedFromDataBase("preview", $(this).attr('data-lang'));

    });

    $("#resetToOriginal").click(function () {
        fetchNewsfeedFromDataBase("compose", $("#selectLangNewsFeed").children('span.active').attr('data-lang'));
    });

    function populateViewStep(obj) {
        $("#preview_final").find("[id='title']").text(obj.m_title);
        $("#preview_final").find("[id='description']").text(obj.m_desc);
        $("#preview_final").find("[id='icon']").attr("src", obj.image_url);
        $("#preview_final").find("[id='link_title']").text(obj.link_text);
    }

    function lookForMissedContent() {
        for (var i = 0; i < langArr.length; i++) {
            if (langArr[i].lang == $("#selectLangNewsFeed").children('span.active').attr('data-lang')) {
                var obj = langArr[i];
                break;
            }
        }

        for (var i = 0; i < langArr.length; i++) {
            if (langArr[i].lang != obj.lang) {
                if (langArr[i].m_title == "") {
                    langArr[i].m_title = obj.m_title;
                }
                if (langArr[i].m_desc == "") {
                    langArr[i].m_desc = obj.m_desc;
                }
                if (langArr[i].image_url == $("#actualImg").val() || langArr[i].image_url == "") {
                    langArr[i].image_url = obj.image_url;
                }
                if (langArr[i].link_text == "") {
                    langArr[i].link_text = obj.link_text;
                }
            }
        }
    }
});