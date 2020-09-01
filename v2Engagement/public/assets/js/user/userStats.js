$(document).ready(function () {

    $(".notification_btns_div input").click(function () {
        var obj = {
            rowId: $("#row_id").val(),
            key: $(this).attr('data-key'),
            value: $(this).prop("checked") == true ? 1 : 0,
        };
        console.log(obj);

        $.ajax({
            type: "post",
            url: baseUrl + '/changeNotification',
            data: obj,
            datatype: 'json',
            success: function (response) {
                $("#userAttributeData tr").each(function () {
                    if ($(this).children().eq(0).text().trim() == obj.key) {
                        $(this).children().eq(1).text(obj.value);
                    }
                });
            },
            error: function (error) {
                console.log('error', error);
            }
        });
    });


    $.get(baseUrl + "/backend/user/attribute/data/conversion/" + $("#row_id").val(), function (data) {

        if (data.status) {
            populateTokens(data.data, $("#userConversionData"));
        }
    });
    $.get(baseUrl + "/backend/user/attribute/data/action/" + $("#row_id").val(), function (data) {

        if (data.status) {
            populateTokens(data.data, $("#userActionData"));
        }
    });


    $("#demo").addClass("auto_height");


    getOverView();
    getEngagement();


    function getOverView() {
        getCampaignClicks();
    }

    function getEngagement() {
        getNewsfeedClick();
        getCampaignConversion()
    }

    function getCampaignClicks() {
        $.ajax({
            type: "GET",
            url: baseUrl + '/backend/user/attribute/campaignTracking/1/' + $("#row_id").val(),
            dataType: 'json',
            success: function (response) {
                hideLoader();
                populateTokens(response, $("#emailClicks"));
            },
            error: function () {

            }
        });

        $.ajax({
            type: "GET",
            url: baseUrl + '/backend/user/attribute/campaignTracking/2/' + $("#row_id").val(),
            dataType: 'json',
            success: function (response) {
                hideLoader();
                populateTokens(response, $("#campaignPushClick"));
            },
            error: function () {

            }
        });

        $.ajax({
            type: "GET",
            url: baseUrl + '/backend/user/attribute/campaignTracking/3/' + $("#row_id").val(),
            dataType: 'json',
            success: function (response) {
                hideLoader();
                populateTokens(response, $("#inAppStat"));
            },
            error: function () {

            }
        });
    }

    function populateTokens(data, table) {

        var str = '';
        var index = 0;
        for (var k in data) {
            if (data.hasOwnProperty(k)) {
                str = str + '<tr><td>' + k + '</td><td>' + data[k] + '</td></tr>';
            }
        }

        table.html(str);
    }

    function populatelastLogin(data, table) {

        var str = '';
        var index = 0;
        for (var i = 0; i < data.length; i++) {

            if (data[i].app_name && data[i].last_login) {

                var str = str + '<tr><td>' + data[i].app_name + '</td><td>' + data[i].last_login + '</td></tr>';
            }

        }


        table.html(str);
    }

    function populateAppListing(data, table) {

        var str = '';
        var index = 0;
        for (var i = 0; i < data.length; i++) {

            if (data[i].app_name) {

                var str = str + '<tr><td style="text-align:center">' + data[i].app_name + '</td></tr>';
            }

        }


        table.html(str);
    }

    // overView methods ends

    // engagement methods starts


    function getNewsfeedClick() {
        $.ajax({
            type: "GET",
            url: baseUrl + '/backend/user/getNewsfeedClick/' + $("#row_id").val(),
            dataType: 'json',
            success: function (response) {
                hideLoader();
                populategetNewsfeedClick(response.data);
            },
            error: function () {
            }
        });
    }


    function populategetNewsfeedClick(data) {

        var str = '';
        for (var k in data) {
            if (data.hasOwnProperty(k)) {
                str = str + '<tr><td>' + k + '</td><td>' + data[k] + '</td></tr>';
            }
        }
        $("#newsFeedClicks").html(str);
    }

    // engagement methods ends

    function encodeHTML(s) {
        return s.replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/"/g, '&quot;');
    }

    function getCampaignConversion() {

        $.ajax({
            type: "GET",
            url: baseUrl + '/backend/user/getCampaignConversion/2/' + $("#row_id").val(),
            dataType: 'json',
            success: function (response) {
                hideLoader();
                populateTokens(response, $("#profileInfo"));
            },
            error: function () {
            }
        });

        $.ajax({
            type: "GET",
            url: baseUrl + '/backend/user/getCampaignConversion/3/' + $("#row_id").val(),
            dataType: 'json',
            success: function (response) {
                hideLoader();
                populateTokens(response, $("#appUsage"));
            },
            error: function () {
            }
        });


        $.ajax({
            type: "GET",
            url: baseUrl + '/backend/user/getAppLastLogin/' + $("#row_id").val(),
            dataType: 'json',
            success: function (response) {
                hideLoader();
                populatelastLogin(response, $("#customAttr"));
            },
            error: function () {
            }
        });

        $.ajax({
            type: "GET",
            url: baseUrl + '/backend/user/getAppListing/' + $("#row_id").val(),
            dataType: 'json',
            success: function (response) {
                hideLoader();
                populateAppListing(response, $("#deviceList"));
            },
            error: function () {
            }
        });


    }
});