var trigger1 = "";
var trigger2 = "";
$(document).ready(function () {
    setTimeout($.unblockUI, 1000);
    // Class Add/ Remove On Wrapper
    $(".hdr_menu_btn").click(function () {
        $(".wpr_content_holder ").toggleClass("left_menu_expand");
    });
    // Left Header button active on click Function
    $(".left_menu_list ul li a").click(function () {
        if (trigger2 != "" && trigger2.attr("data-list") != $(this).next().attr("data-list")) {
            trigger2.hide();
        }
        if ($(this).next().prop("tagName") == "UL") {
            trigger1 = $(".left_menu_list ul li");
            $(this).next().slideToggle();
        }
        trigger2 = $(this).next();
    });

    $("body").click(function (event) {
        if (trigger1 != "" && trigger1 !== event.target && !trigger1.has(event.target).length) {
            $(".inner_menu").slideUp("fast");
        }
    });

//  campaigns_type Section add/Remove class on parent
    $("#campaigns_type").change(function () {
        var lang_var = $("#campaigns_type option:selected").val();

        if (lang_var == 1) {
            $("body").attr("class", 'email_html-2 listing_ftr_hide');
        } else {
            $("body").attr("class", 'app_message listing_ftr_hide');
        }


    });

    // Compose In-App Messages Tab Function
    $(".pre_comp_title_icons ul li a").click(function (e) {

        if ($(this).attr('class') != 'pre_comp_setting') {

            $(".pre_comp_title_icons ul li a").removeClass("active");
            $(this).addClass("active");

            var this_id = $(this).attr("href");
            $(".comp_det_sec").hide();
            $(this_id).show();
            return false;
        }
    });
    // Top Header-right Drop Down Function
    $(".hdr_rit_search_sec a").click(function (e) {
        e.stopPropagation();
        $(".hdr_rt_drop_down").slideToggle();
        $(".hdr_rt_drop_down ul").show();

    });
    $(".hdr_rt_drop_down ul li a").click(function () {
        $(this).parents(".hdr_rt_drop_down").slideUp();

    });

    // Step function
    $('#demo').steps({
        startAt: 0,
        showBackButton: true,
        showFooterButtons: true,
    });
//////////////////

    $(document).on("click", ".con_event_dropdown span ", function (e) {
        $(".con_event_dropdown ul").slideDown();

    });

    $(document).on("click", ".con_event_dropdown a", function (e) {
        $(".con_event_dropdown ul ").slideUp();

    });

//    Step-5 Tab with Dropdown
    $(document).on("click", ".con_event_dropdown li a", function () {
        var test = $(this).text();
        $('.con_event_dropdown span').text('');
        $('.con_event_dropdown span').text(test);

        var this_id = $(this).attr("tab");
        $('.conversion_event_step').removeClass('active');
        $('#' + this_id).addClass('active');
    });


    $(".rt_aligning_btn ul li a").click(function (e) {
        $(".rt_aligning_btn ul li a").removeClass("active");
        $(this).addClass("active");
    });


// And Or Toggle Btns Effects

    $(".and_or_toggle_btn ").click(function () {
        $(this).toggleClass('active');

    });


    //  Tab Step-3
    // tab Function
    $(".select_button a").click(function (e) {

        $(".select_button a").removeClass("active");
        $(this).addClass("active");

        var this_id = $(this).attr("href");
        $(".sel_btn_det").fadeOut('fast');
        $(this_id).fadeIn('slow');
        return false;
    });

// Sec-2 - Email

//  step-1 temp_Add Remove class

    $(".tamp_list_outer ul li a").click(function (e) {
        $(".tamp_list_outer ul li a").removeClass("active");
        $(this).addClass("active");
    });

//
//  Compose In-App Messages Tab Function
    $(".pre_comp_title_icons2 ul li a").click(function (e) {
        $(".pre_comp_title_icons2 ul li a").removeClass("active");
        $(this).addClass("active");

        var this_id = $(this).attr("href");
        $(".comp_det_sec").fadeOut('fast');
        $(this_id).fadeIn('slow');
        return false;

    });

    $(document).on('click', '.lst_tbl_drop_outer span', function (e) {
        $('.lst_tbl_drop_outer ul').hide();
        $(this).parent(".lst_tbl_drop_outer").find("ul").slideToggle('slow');
        return false;

    });

    /* $(".lst_tbl_drop_outer span").click(function (e) {
         //alert("hello 1");
         $(this).parent(".lst_tbl_drop_outer").find("ul").slideToggle('slow');
         return false;

     });*/

    $(document).on('click', '.lst_tbl_drop_outer ul li', function (e) {
        //alert("hello 2");
        $('.lst_tbl_drop_outer ul').hide();
        $(this).parent(".lst_tbl_drop_outer ul").slideUp('slow');
        return false;

    });

    /*$(".lst_tbl_drop_outer ul li").click(function (e) {
        //alert("hello 2");
        $(this).parent(".lst_tbl_drop_outer ul").slideUp('slow');
        return false;

    });*/

// Splitter function
    $(".panel-left").resizable({
        handleSelector: ".splitter",
        resizeHeight: false,
        resizeWidth: true,
    });

    //   listing to Campaign show ///////////
    $("#campaigns_type2").change(function () {

        var lang_var = $("#campaigns_type2 option:selected").val();
        $("body").attr("class", lang_var);
        $(".db_content_holder").show();
        $(".db_content_listing_holder").hide();
        $(".segment_footer").hide();
    });
    ////////////////////////

//   Listing-left-bottom- Sub-Menu
    $(document).on('click', '.db_list_left_sublist h3', function (e) {
        $(this).toggleClass('list_left_sublist_active');
        $(this).parent().find('ul').slideToggle(500);
    });

    // user search Function
    // tab Function
    $(".usr_prof_tab_btns ul li a").click(function (e) {
        $(".usr_prof_tab_btns ul li a").removeClass("active");
        $(this).addClass("active");

        var this_id = $(this).attr("href");
        $(".usr_prof_tab_det").fadeOut('fast');
        $(this_id).fadeIn('slow');
        return false;
    });
    // tab Function
    $(".feedback_right_tab_btn ul li a").click(function (e) {
        $(".feedback_right_tab_btn ul li").removeClass("active");
        $(this).parent("li").addClass("active");

        var this_id = $(this).attr("href");
        $(".fb_prof_tab_det").fadeOut('fast');
        $(this_id).fadeIn('slow');
        return false;
    });

    $(".tabClass ul li a").click(function () {
        if ($(this).attr("href") == "tab1") {
            $(".tab1").css({"display": "block"});
            $(".tab2").css({"display": "none"});
            $(".tab3").css({"display": "none"});
            $(".tab4").css({"display": "none"});
        } else if ($(this).attr("href") == "tab2") {
            $(".tab1").css({"display": "none"});
            $(".tab2").css({"display": "block"});
            $(".tab3").css({"display": "none"});
            $(".tab4").css({"display": "none"});
        } else if ($(this).attr("href") == "tab3") {
            $(".tab1").css({"display": "none"});
            $(".tab2").css({"display": "none"});
            $(".tab3").css({"display": "block"});
            $(".tab4").css({"display": "none"});
        } else if ($(this).attr("href") == "tab4") {
            $(".tab1").css({"display": "none"});
            $(".tab2").css({"display": "none"});
            $(".tab3").css({"display": "none"});
            $(".tab4").css({"display": "block"});
        }
    });

    $("#selectLangInAppPushPreview span").click(function () {
        $("#selectLangInAppPushPreview").children().each(function () {
            $(this).removeClass('active');
        });
        $(this).addClass('active');
    });

    $("#selectLangInAppPush span").click(function () {
        $("#selectLangInAppPush").children().each(function () {
            $(this).removeClass('active');
        });
        $(this).addClass('active');
    });

    $("#selectLang span").click(function () {
        $("#selectLang").children().each(function () {
            $(this).removeClass('active');
        });
        $(this).addClass('active');
    });

    $("#selectLangEmailPreview span").click(function () {
        $("#selectLangEmailPreview").children().each(function () {
            $(this).removeClass('active');
        });
        $(this).addClass('active');
    });

    ////////////////////////

    $("#dashboard_quick_action").on("change", function () {

        var url = $(this).val();
        if (url) {
            window.location = url;
        }
    });

    $(document).ajaxStart(function () {
        showLoader()
        // setTimeout($.unblockUI, 2000);
    });

    $(document).ajaxStop(function () {
        hideLoader()
    });


});

function showLoader() {

    $(".loading_popup_outer_ajax").css("opacity", "0.8");
    $(".loading_popup_outer_ajax").fadeIn();

}

function hideLoader() {
    $(".loading_popup_outer_ajax").fadeOut();

}

$(window).on("load", function () {
    $(".loading_popup_outer").fadeOut();
});

