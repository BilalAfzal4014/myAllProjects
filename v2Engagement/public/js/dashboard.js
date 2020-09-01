$(document).ready(function () {
    $.ajax({
        type: 'POST',
        url: '/stats',
        cache: false,
        data: {filter: 'campaign', interval: 'today'},
        dataType: 'json',
        success: function (response) {
            plotCampaignGraphs(response.data.text.email, response.data.categories, response.data.series.email, 'emailChart');
            plotCampaignGraphs(response.data.text.push, response.data.categories, response.data.series.push, 'pushChart');
            plotCampaignGraphs(response.data.text.inapp, response.data.categories, response.data.series.inapp, 'inappChart');
        }
    });

    $.ajax({
        type: 'POST',
        url: '/stats',
        cache: false,
        data: {filter: 'newsfeed', interval: 'today'},
        success: function (response) {
            plotGraphs(response, 'newsfeedChart');
        }
    });

    $.ajax({
        type: 'POST',
        url: '/stats',
        cache: false,
        data: {filter: 'conversion', interval: 'today'},
        success: function (response) {
            plotGraphs(response, 'conversionChart');
        }
    });
});

$('#campaign_graph_intervals').bind('change', function () {
    var value = $("#" + $(this).attr('id') + " option:selected").val();

    if (value !== '') {
        $.ajax({
            type: 'POST',
            url: '/stats',
            cache: false,
            data: {filter: 'campaign', interval: value},
            dataType: 'json',
            success: function (response) {
                plotCampaignGraphs(response.data.text.email, response.data.categories, response.data.series.email, 'emailChart');
                plotCampaignGraphs(response.data.text.push, response.data.categories, response.data.series.push, 'pushChart');
                plotCampaignGraphs(response.data.text.inapp, response.data.categories, response.data.series.inapp, 'inappChart');
            }
        });
    }
});

$('#newsfeed_graph_intervals').bind('change', function () {
    var value = $("#" + $(this).attr('id') + " option:selected").val();

    if (value !== '') {
        $.ajax({
            type: 'POST',
            url: '/stats',
            cache: false,
            data: {_token: $('meta[name=csrf-token]').val(), filter: 'newsfeed', interval: value},
            success: function (response) {
                plotGraphs(response, 'newsfeedChart');
            }
        });
    }
});

$('#conversion_graph_intervals').bind('change', function () {
    var value = $("#" + $(this).attr('id') + " option:selected").val();

    if (value !== '') {
        $.ajax({
            type: 'POST',
            url: '/stats',
            cache: false,
            data: {filter: 'conversion', interval: value},
            success: function (response) {
                plotGraphs(response, 'conversionChart');
            }
        });
    }
});

function plotGraphs(response, element)
{
    Highcharts.chart(element, {
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
            text: response.data.text
        },
        xAxis: {
            categories: response.data.categories
        },
        yAxis: {
            min: 0,
            title: {
                text: 'Total Hits/Impressions'
            },
            stackLabels: {
                enabled: true,
                style: {
                    fontWeight: 'bold',
                    color: (Highcharts.theme && Highcharts.theme.textColor) || 'gray'
                }
            }
        },
        legend: {
            align: 'right',
            x: -30,
            verticalAlign: 'top',
            y: 25,
            floating: true,
            backgroundColor: (Highcharts.theme && Highcharts.theme.background2) || 'white',
            borderColor: '#CCC',
            borderWidth: 1,
            shadow: false
        },
        tooltip: {
            headerFormat: '<b>{point.x}</b><br/>',
            pointFormat: '{series.name}: {point.y}<br/>Total: {point.stackTotal}'
        },
        plotOptions: {
            column: {
                stacking: 'normal',
                dataLabels: {
                    enabled: true,
                    color: (Highcharts.theme && Highcharts.theme.dataLabelsColor) || 'white'
                }
            }
        },
        series: response.data.series
    });
}

function plotCampaignGraphs(text, categories, series, element)
{
    Highcharts.chart(element, {
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
            text: text
        },
        xAxis: {
            categories: categories
        },
        yAxis: {
            min: 0,
            title: {
                text: text
            },
            stackLabels: {
                enabled: true,
                style: {
                    fontWeight: 'bold',
                    color: (Highcharts.theme && Highcharts.theme.textColor) || 'gray'
                }
            }
        },
        legend: {
            align: 'right',
            x: -30,
            verticalAlign: 'top',
            y: 25,
            floating: true,
            backgroundColor: (Highcharts.theme && Highcharts.theme.background2) || 'white',
            borderColor: '#CCC',
            borderWidth: 1,
            shadow: false
        },
        tooltip: {
            headerFormat: '<b>{point.x}</b><br/>',
            pointFormat: '{series.name}: {point.y}<br/>Total: {point.stackTotal}'
        },
        plotOptions: {
            column: {
                stacking: 'normal',
                dataLabels: {
                    enabled: true,
                    color: (Highcharts.theme && Highcharts.theme.dataLabelsColor) || 'white'
                }
            }
        },
        series: series
    });
}

function showCampaignGraph(element) {
    $('.campaignChart').addClass('hide');
    $('.'+element).removeClass('hide');
}