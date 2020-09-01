$(document).ready(function () {
    getOverView();
    getEngagement();

    
    function getOverView() {
        getProfileDetails();
        getCustomAttributes();
        getTokens();
    }

    function getEngagement() {
        getCampaignClick();
        getSegmentsInfo();
        getNewsfeedClick();
    }

    // overView methods starts
    function getProfileDetails() {
        showLoader();
        $.ajax({
            type: "GET",
            async:true,
            url: baseUrl + '/backend/attribute/getProfileDetails/' + $(".companyId").val(),
            dataType: 'json',
            success: function (response) {

                hideLoader();
                populateProfileDetails(response.data);
            },
            error: function () {
                // alert("fail");
            }
        });
    }

    function getCustomAttributes() {
        showLoader();
        $.ajax({
            type: "GET",
            async:true,
            url: baseUrl + '/backend/attribute/getCustomAttributes/' + $(".companyId").val(),
            dataType: 'json',
            success: function (response) {
                hideLoader();
                populateCustomAttributes(response.data);
            },
            error: function () {

            }
        });
    }

    function getTokens() {
        $.ajax({
            type: "GET",
            url: baseUrl + '/backend/attribute/getTokens/' + $(".companyId").val(),
            dataType: 'json',
            success: function (response) {
                hideLoader();
                populateTokens(response.data);
            },
            error: function () {

            }
        });
    }

    function populateProfileDetails(data) {

        var profile = data.profile;
        $(".usr_profile_name").text(profile.name);
        $("#userId").prepend("User ID: " + profile.id).show();
        $('#emailId').append(profile.email).show();
        $(".phon_no").append(profile.phone);


        var triggerArr = data.trigger;
        var conversionArr = data.conversion;
        var str = '<thead><th style="width: 20%;">id</th><th style="width: 30%;">App Name</th><th style="width: 40%;">App Value</th></thead>';
        for (var i = 0; i < triggerArr.length; i++) {
            console.log(data[i]);
            var str =str+ '<tr><td>' + triggerArr[i].id + '</td><td>' + triggerArr[i].action_name + '</td><td>' + triggerArr[i].action_value + '</td></tr>';
        }
        $("#appUsage").html(str);

        str = '<thead><th style="width: 20%;">id</th><th style="width: 30%;">App Name</th><th style="width: 40%;">App Value</th></thead>';
        for (var i = 0; i < conversionArr.length; i++) {
            console.log(data[i]);
            var str =str+ '<tr><td>' + conversionArr[i].id + '</td><td>' + conversionArr[i].action_name + '</td><td>' + conversionArr[i].action_value + '</td></tr>';
        }
        $("#profileInfo").html(str);
    }

    function populateCustomAttributes(data) {


        var str = '';
        for (var i = 0; i < data.length; i++) {
            str = str + '<tr><td>' + encodeHTML(data[i].code) + '</td><td>' + encodeHTML(data[i].data_type) + '</td></tr>';
        }
        $("#customAttr").html(str);
    }

    function populateTokens(data) {
        var str = '<thead><th style="width: 5%;">id</th><th style="width: 40%;">email</th></thead>';
        for (var i = 0; i < data.length; i++) {
            str = str + '<tr><td style="width:40%;">' + encodeHTML(data[i].id) + '</td><td>' + encodeHTML(data[i].email)+ '</td></tr>';
        }
        $("#deviceToken").html(str);
    }

    // overView methods ends

    // engagement methods starts
    function getCampaignClick() {
        $.ajax({
            type: "GET",
            url: baseUrl + '/backend/attribute/getCampaignClick/' + $(".companyId").val(),
            dataType: 'json',
            success: function (response) {
                hideLoader();
                populateCampaignClicks(response.data);
            },
            error: function () {
            }
        });
    }

    function getSegmentsInfo() {
        $.ajax({
            type: "GET",
            url: baseUrl + '/backend/attribute/getSegmentsInfo/' + $(".companyId").val(),
            dataType: 'json',
            success: function (response) {
                hideLoader();
                populateSegmentInfo(response.data);

            },
            error: function () {
                // alert("fail");
            }
        });
    }

    function getNewsfeedClick() {
        $.ajax({
            type: "GET",
            url: baseUrl + '/backend/attribute/getNewsfeedClick/' + $(".companyId").val(),
            dataType: 'json',
            success: function (response) {
                hideLoader();
                populategetNewsfeedClick(response.data);
            },
            error: function () {
            }
        });
    }

    function populateCampaignClicks(data) {
        var str = '';
        var str1 = '';
        for (var i = 0; i < data.length; i++) {
            str = str + '<tr><td>' + encodeHTML(data[i].name) + '</td><td>' + encodeHTML(data[i].clicks) + '</td></tr>';
            str1 = str1 + '<tr><td>' + encodeHTML(data[i].name) + '</td><td>' + encodeHTML(data[i].created_at) + '</td></tr>';
        }
        $("#emailClicks").html(str);
        $("#campaignInformation").html(str1);
    }

    function populateSegmentInfo(data) {
        var mainStr = '';
        for (var i = 0; i < data.length; i++) {
            mainStr = mainStr + '<tr>';
            mainStr = mainStr + '<td style="vertical-align: top;">' + data[i].name + '</td>';
            mainStr = mainStr + '<td style="width:68%;">';
            mainStr = mainStr + '<table>';
            for (var j = 0; j < data[i].campaigns.length; j++) {
                mainStr = mainStr + '<tr><td>' + encodeHTML(data[i].campaigns[j].name) + '</td></tr>';
            }
            if (data[i].campaigns.length == 0) {
                mainStr = mainStr + '<tr><td>No Campaign for this Segment</td></tr>';
            }
            mainStr = mainStr + '</table>';
            mainStr = mainStr + '</td>';
            mainStr = mainStr + '</td>';
            mainStr = mainStr + '</tr>';

        }
        $("#segmentsInfo").html(mainStr);
    }

    function populategetNewsfeedClick(data) {
        var str = '';
        for (var i = 0; i < data.length; i++) {
            str = str + '<tr><td>' + encodeHTML(data[i].name) + '</td><td>' + encodeHTML(data[i].clicks) + '</td></tr>';
        }
        $("#newsFeedClicks").html(str);
    }

    // engagement methods ends

    function encodeHTML(s) {
        return s.replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/"/g, '&quot;');
    }

});