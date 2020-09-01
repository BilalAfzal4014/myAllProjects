$(document).ready(function () {
    setDates();
    getStats();
    events();


    function setDates() {
        var currDate = new Date();
        var last30thDate = new Date().setDate(currDate.getDate() - 7);
        last30thDate = new Date(last30thDate).toISOString().split('T')[0];
        var prevDate = new Date().setDate(new Date().getDate());
        prevDate = new Date(prevDate).toISOString().split('T')[0];
        console.log('last30thDate', last30thDate);
        console.log('prevDate', prevDate);
        $("#rangeStartDate").val(last30thDate);
        $("#date_start").val(last30thDate);
        $("#action_start_date").val(last30thDate);
        $("#rangeEndDate").val(prevDate);
        $("#date_end").val(prevDate);
        $("#action_date_end").val(prevDate);
    }

    function events() {

        $(document).on("click", ".blockAnchor", function (e) {
            e.preventDefault();
        });

        $("#applyDateRange").click(function () {

            if ($("#rangeStartDate").val() > $("#rangeEndDate").val()) {
                $("#dateRangeError").text("End Time should be greater than or equals to Start Time");
                return;
            }
            $("#dateRangeError").text("");
            getStats();
        });
    }

    function getStats() {
        var url = baseUrl + '/backend/campaign/getStats';
        $.ajax({
            type: "POST",
            data: {
                dateRange: {
                    startDate: $("#rangeStartDate").val(),
                    endDate: $("#rangeEndDate").val()
                },
                campaignId: $("#campaignId").val()
            },
            url: url,
            dataType: 'json',
            success: function (response) {
                console.log('response', response);
                populateMobilePlatform(response.data.mobilePlatform);
                populateCharts(response.data.chart);
                populateCampaignDetails(response.data.campaignDetails);
                populatePerformanceStats(response.data.chart);
            },
            error: function () {

            }
        });
    }

    function populateMobilePlatform(mobilePlatform) {
        populateIosPlatform(mobilePlatform.ios);
        populateAndroidPlatform(mobilePlatform.android);
    }

    function populateIosPlatform(ios) {
        $(".clickIphoneCount").text(ios.clicks);
        $(".viewIphoneCount").text(ios.views);

        var clickThrough = 0;

        if (ios.views != 0) {
            clickThrough = (ios.clicks / ios.views) * 100;
        }

        $(".iphoneClickThrough").text(clickThrough);
    }

    function populateAndroidPlatform(android) {
        $(".clickAndroidCount").text(android.clicks);
        $(".viewAndroidCount").text(android.views);

        var clickThrough = 0;

        if (android.views != 0) {
            clickThrough = (android.clicks / android.views) * 100;
        }

        $(".androidClickThrough").text(clickThrough);
    }

    function populateCharts(chart) {
        populateView(chart.interval, chart.view);
        populateClick(chart.interval, chart.clicks);
        populateClickRate(chart.interval, chart.clickRate);
    }

    function populateView(interval, view) {

        Highcharts.chart('total_views', {
            credits: {
                enabled: false
            },
            exporting: {
                enabled: false
            },
            chart: {
                type: 'column'
            },
            title: {
                text: 'Total Views'
            },
            subtitle: {
                text: ''
            },
            xAxis: {
                categories: interval,
                crosshair: true
            },
            yAxis: {
                min: 0,
                title: {
                    text: ''
                }
            },
            tooltip: {
                headerFormat: '<span style="font-size:10px">{point.key}</span><table>',
                pointFormat: '<tr><td style="color:{series.color};padding:0">{series.name}: </td>' +
                    '<td style="padding:0"><b>{point.y:.0f}</b></td></tr>',
                footerFormat: '</table>',
                shared: true,
                useHTML: true
            },
            plotOptions: {
                column: {
                    pointPadding: 0.2,
                    borderWidth: 0
                }
            },
            series: view,
        });
    }

    function populateClick(interval, clicks) {

        Highcharts.chart('total_click', {
            credits: {
                enabled: false
            },
            exporting: {
                enabled: false
            },
            chart: {
                type: 'column'
            },
            title: {
                text: 'Total Clicks'
            },
            subtitle: {
                text: ''
            },
            xAxis: {
                categories: interval,
                crosshair: true
            },
            yAxis: {
                min: 0,
                title: {
                    text: ''
                }
            },
            tooltip: {
                headerFormat: '<span style="font-size:10px">{point.key}</span><table>',
                pointFormat: '<tr><td style="color:{series.color};padding:0">{series.name}: </td>' +
                    '<td style="padding:0"><b>{point.y:.0f}</b></td></tr>',
                footerFormat: '</table>',
                shared: true,
                useHTML: true
            },
            plotOptions: {
                column: {
                    pointPadding: 0.2,
                    borderWidth: 0
                }
            },
            series: clicks,
        });
    }

    function populateClickRate(interval, clickRate) {

        Highcharts.chart('click_through', {
            credits: {
                enabled: false
            },
            exporting: {
                enabled: false
            },
            chart: {
                type: 'column'
            },
            title: {
                text: 'Click Through Rate'
            },
            subtitle: {
                text: ''
            },
            xAxis: {
                categories: interval,
                crosshair: true
            },
            yAxis: {
                min: 0,
                title: {
                    text: ''
                }
            },
            tooltip: {
                headerFormat: '<span style="font-size:10px">{point.key}</span><table>',
                pointFormat: '<tr><td style="color:{series.color};padding:0">{series.name}: </td>' +
                    '<td style="padding:0"><b>{point.y:.0f}</b></td></tr>',
                footerFormat: '</table>',
                shared: true,
                useHTML: true
            },
            plotOptions: {
                column: {
                    pointPadding: 0.2,
                    borderWidth: 0
                }
            },
            series: clickRate,
        });
    }

    function populateCampaignDetails(campaignDetails) {
        $("#campaignNameMain").text(campaignDetails.name);
        $("#campaignNamePreview").text(campaignDetails.name);

        if (campaignDetails.type_id != 1) {
            $("#preview_iframe").css({display: "none"});
            $("#preview_div").html(campaignDetails.en);
        } else {
            $("#preview_div").css({display: "none"});
            $("#preview_iframe").attr("srcdoc", campaignDetails.en);
        }


        $("#startTime").text(campaignDetails.start_time);
        if ((campaignDetails.start_time == null && campaignDetails.end_time == null) || (campaignDetails.start_time != null && campaignDetails.end_time != null)) {
            $("#endTime").text(campaignDetails.end_time);
        } else {
            $("#endTime").text("Never");
        }

        populateTargetAudience(campaignDetails.targetAudience);

    }

    function populateTargetAudience(targetAudience) {

        var segmentString = '';

        for (var i = 0; i < targetAudience.segments.length; i++) {
            segmentString += targetAudience.segments[i];
            segmentString += ', ';
        }
        segmentString = segmentString.slice(0, -2);

        $("#segementsInfo").text(segmentString);
        $("#totalUsers").text(targetAudience.reachableUsers);
    }

    function populatePerformanceStats(chart) {
        var minMax = findMinMaxIndexes(chart.view[0].data, chart.view[1].data);
        $("#views_h").text(chart.interval[minMax.maxIndex]);
        $("#views_l").text(chart.interval[minMax.minIndex]);

        minMax = findMinMaxIndexes(chart.clicks[0].data, chart.clicks[1].data);
        $("#clicks_h").text(chart.interval[minMax.maxIndex]);
        $("#clicks_l").text(chart.interval[minMax.minIndex]);

        minMax = findMinMaxIndexes(chart.clickRate[0].data, chart.clickRate[1].data);
        $("#ctr_h").text(chart.interval[minMax.maxIndex]);
        $("#ctr_l").text(chart.interval[minMax.minIndex]);
    }

    function findMinMaxIndexes(arr1, arr2) {
        var obj = {};
        var myArr = [];

        for (var i = 0; i < arr1.length; i++) {
            myArr[i] = arr1[i] + arr2[i];
        }
        obj.maxIndex = myArr.reduce((iMax, x, i, arr) => x > arr[iMax] ? i : iMax, 0);
        obj.minIndex = myArr.reduce((iMin, x, i, arr) => x < arr[iMin] ? i : iMin, 0);

        return obj;
    }
});

function showChartTab(element) {
    $('.generalChart').addClass('hide');
    $('.' + element).removeClass('hide');
}