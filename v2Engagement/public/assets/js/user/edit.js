$(document).ready(function () {
    var step = "tab1";


    if (isAdmin) {
        events();

        function events() {
            $(".edit-user-form").submit(function (e) {
                e.preventDefault();
                if (step == "tab1") {
                    if (!invalidInformation_step1())
                        submitCompany(0);
                }
                else if (step == "tab4") {
                    if (!invalidInformation_step4())
                        submitCompany(1);
                }
            });

            $(".tab-view li a").click(function () {
                step = $(this).attr("href");
            });
        }

    } else {
        events();

        function events() {
            $(".edit-user-form").submit(function (e) {
                e.preventDefault();
                if (step == "tab1") {
                    if (!invalidInformation_step1())
                        submitCompany(0);
                }
                else if (step == "tab2") {
                    if (!invalidInformation_step2())
                        submitCompany(1);
                }
                else if (step == "tab3") {
                    if (!invalidInformation_step3())
                        submitCompany(2);
                }
                else if (step == "tab4") {
                    if (!invalidInformation_step4())
                    //submitCompany(3);
                        submitCompany(2);
                }
            });

            $(".tab-view li a").click(function () {
                step = $(this).attr("href");
            });
        }

    }


    function submitCompany(formIndex) {
        $.ajax({
            type: 'POST',
            url: baseUrl + '/backend/user/update',
            cache: false,
            processData: false,
            contentType: false,
            dataType: 'json',
            data: new FormData($(".edit-user-form")[formIndex]),
            headers: {
                'X-CSRF-Token': $('input[name="_token"]').val()
            },
            success: function (response) {
                if (response.status) {
                    updateMessageAndInfo(response);
                }
                else {
                    $("#companyEmailDupError").text(response.message);
                }
            },
            error: function () {

            }
        });
    }

    function updateMessageAndInfo(response) {
        if (response.data != '') {
            $(".menu_left_logo a img").attr("src", response.data);
        }
        $(".hdr_profile_sec p span").text($("#companyName").val());
        toastr.success(response.message);
    }

    function invalidInformation_step1() {
        $("#companyEmailDupError").text("");

        var errors = false;
        if ($("#companyName").val() == "") {
            $("#companyNameError").text("Company name is required");
            errors = true;
        }
        else {
            $("#companyNameError").text("");
        }

        if (getFileExtension($('#companyLogo').val()) != "png" && getFileExtension($('#companyLogo').val()) != "jpg" && getFileExtension($('#companyLogo').val()) != "accept") {
            $("#companyLogoError").text("Must be .jpg or .png extension only");
            errors = true;
        }
        else {
            $("#companyLogoError").text("");
        }

        if ($("#companyEmail").val() == "") {
            $("#companyEmailError").text("Email is required");
            errors = true;
        }
        else {

            var emailReg = /[A-Z0-9._%+-]+@[A-Z0-9.-]+\.[A-Z]{2,4}/igm;
            if (!emailReg.test($("#companyEmail").val())) {
                $("#companyEmailError").text("Invalid Email");
                errors = true;
            }
            else {
                $("#companyEmailError").text("");
            }
        }

        if ($("#companyPhone").val() == "") {
            $("#companyPhoneError").text("Phone number is required");
            errors = true;
        }
        else {
            $("#companyPhoneError").text("");
        }

        if (getFileExtension($('#companyFile1').val()) != "pem" && getFileExtension($('#companyFile1').val()) != "accept") {
            errors = true;
            $("#companyFile1Error").text("Must be .pem extension only");
        }
        else {
            $("#companyFile1Error").text("");
        }

        if (getFileExtension($('#companyFile2').val()) != "pem" && getFileExtension($('#companyFile2').val()) != "accept") {
            errors = true;
            $("#companyFile2Error").text("Must be .pem extension only");
        }
        else {
            $("#companyFile2Error").text("");
        }

        return errors;
    }

    function invalidInformation_step2() {
        var errors = false;

        // if ($("#Passphrase").val() == "") {
        //     $("#PassphraseError").text("PassPhrase is required");
        //     errors = true;
        // }
        // else {
        //     $("#PassphraseError").text("");
        // }

        if ($("#fireBaseKey").val() == "") {
            $("#fireBaseKeyError").text("Fire Base Key is required");
            errors = true;
        }
        else {
            $("#fireBaseKeyError").text("");
        }

        if (getFileExtension($('#companyFile1').val()) != "pem" && getFileExtension($('#companyFile1').val()) != "accept") {
            errors = true;
            $("#companyFile1Error").text("Must be .pem extension only");
        }
        else {
            $("#companyFile1Error").text("");
        }

        if (getFileExtension($('#companyFile2').val()) != "pem" && getFileExtension($('#companyFile2').val()) != "accept") {
            errors = true;
            $("#companyFile2Error").text("Must be .pem extension only");
        }
        else {
            $("#companyFile2Error").text("");
        }

        return errors;
    }

    function invalidInformation_step3() {
        var errors = false;

        if ($("#smtpHost").val() == "") {
            $("#smtpHostError").text("Smtp Host is required");
            errors = true;
        }
        else {
            $("#smtpHostError").text("");
        }

        if ($("#smtpUser").val() == "") {
            $("#smtpUserError").text("Smtp User is required");
            errors = true;
        }
        else {
            $("#smtpUserError").text("");
        }

        if ($("#smtpPassword").val() == "") {
            $("#smtpPasswordError").text("Password is required");
            errors = true;
        }
        else {
            $("#smtpPasswordError").text("");
        }

        if ($("#smtpPort").val() == "") {
            $("#smtpPortError").text("Smtp Port is required");
            errors = true;
        }
        else {
            $("#smtpPortError").text("");
        }

        if ($("#smtpFromName").val() == "") {
            $("#smtpFromNameError").text("From Name is required");
            errors = true;
        }
        else {
            $("#smtpFromNameError").text("");
        }

        if ($("#smtpFromEmail").val() == "") {
            $("#smtpFromEmailError").text("From Email is required");
            errors = true;
        }
        else {
            var emailReg = /[A-Z0-9._%+-]+@[A-Z0-9.-]+\.[A-Z]{2,4}/igm;
            if (!emailReg.test($("#smtpFromEmail").val())) {
                $("#smtpFromEmailError").text("Invalid Email");
                errors = true;
            }
            else {
                $("#smtpFromEmailError").text("");
            }
        }

        if ($("input:radio.typeRadio:checked").val() === undefined) {
            errors = true;
            $("input:radio.typeRadio:checked").val();
            $("#typeError").text("Please Select value");
        }
        else {
            $("#typeError").text("");
        }
        return errors;
    }

    function invalidInformation_step4() {
        var errors = false;

        if ($("#password").val() == "") {
            $("#passwordError").text("password is required");
            errors = true;
        }
        else {
            var passWordRegex = /^(?=.*\d)(?=.*[a-zA-Z]).{8,16}$/;
            if (!passWordRegex.test($("#password").val())) {
                $("#passwordError").text("Password must contain Alpha-Numeric characters and having length between 8-16");
                errors = true;
            }
            else {
                $("#passwordError").text("");
            }
        }

        if ($("#confirmPassword").val() != $("#password").val()) {
            $("#confirmPasswordError").text("password didn't matched");
            errors = true;
        }
        else {
            $("#confirmPasswordError").text("");
        }


        return errors;
    }

    function getFileExtension(path) {
        if (path) {

            var name = path.split('\\').pop();
            if (name != "")
                return name.split(".")[1];
        }
        return "accept";
    }
});
