<template>
    <div class="timeline">
        <header class="timeline_header">
            <div class="align_left">
                <h3>Campaigns</h3>
            </div>
            <div class="align_right" style="width: 100%; float: none; display: inline-block">
                <div style="padding-top: 10px">
                    <span>from</span>
                    <input v-on:change="getCampaignStatsCount" style="width: 138px" type="date" class="input_date"
                           v-model="selectedInputs.startDate" placeholder="dd-mm-yyy">
                    <span class="to-date">to</span>
                    <input v-on:change="getCampaignStatsCount" style="width: 138px" type="date" class="input_date"
                           v-model="selectedInputs.endDate" placeholder="dd-mm-yyy">
                </div>
                <div class="box_campaign">
                    <span>Campaign type</span>
                    <select v-model="selectedInputs.selectedType" v-on:change="getCampaignStatsCount">
                        <option v-for="type in campaignObj.types"
                                v-bind:value="type">{{type.label}}
                        </option>
                    </select>
                </div>
            </div>
        </header>
        <div class="timeline_map">
            <div class="border_box">
                <div class="boxes_cols">
                    <div class="box_col">
                        <span>Sent</span>
                        <strong style="color:rgb(254, 196, 0)">{{campaignStats.Sent}}</strong>
                    </div>
                    <div class="box_col">
                        <span>Failed</span>
                        <strong style="color:rgb(181, 88, 246)" class="green">{{campaignStats.Fail}}</strong>
                    </div>
                    <div class="box_col">
                        <span>In-Queue</span>
                        <strong style="color: rgb(0, 178, 254)" class="pink">{{campaignStats['In-Queue']}}</strong>
                    </div>
                </div>
                <strong class="box_title">Overall users</strong>
                <div id="chart5" style="width: 100%; height: 206px;display: inline-block;"></div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        data() {
            let startDate = new Date();
            startDate.setMonth(startDate.getMonth() - 1);
            startDate = startDate.toISOString().split('T')[0];

            return {
                campaignObj: {
                    types: [
                        {
                            label: "All",
                            code: "all"
                        },
                        {
                            label: "Push",
                            code: "push"
                        },
                        {
                            label: "In App",
                            code: "inapp"
                        },
                        {
                            label: "Email",
                            code: "email"
                        }
                    ],
                },
                selectedInputs: {
                    selectedType: {
                        label: "All",
                        code: "all"
                    },
                    startDate: startDate,
                    endDate: new Date().toISOString().split('T')[0]
                },
                campaignStats: {}
            };
        },
        mounted() {
            this.getCampaignStatsCount();
        },
        methods: {
            getCampaignStatsCount() {
                const payLoad = {
                    resource: "dashboard/campaign-stats/count",
                    action: "post",
                    data: this.selectedInputs
                };

                this.api("post",
                    this.constants.getUrl("backendApiUrl"),
                    payLoad,
                    this.authHeaders
                ).then((response) => {
                    this.campaignStats = response.data.data;
                    this.initializeChart();
                }).catch((error) => {

                });

            },
            initializeChart() {
                let chart = new CanvasJS.Chart("chart5", {
                    animationEnabled: true,
                    title: {
                        text: "",
                    },
                    axisY: {
                        interval: 10,
                        gridColor: "#ebedf4",
                        tickLength: 0,
                        lineThickness: 0,
                        labelFormatter: function () {
                            return " ";
                        }
                    },
                    axisX: {
                        interval: 10,
                        gridColor: "#ebedf4",
                        tickLength: 0,
                        lineThickness: 0,
                        labelFormatter: function () {
                            return " ";
                        }
                    },
                    dataPointWidth: 10,
                    borderRadius: 10,
                    legend: {
                        cursor: "pointer",
                    },
                    toolTip: {
                        shared: true,
                    },
                    data: [{
                        type: "bar",
                        showInLegend: true,
                        dataPoints: [
                            {y: this.campaignStats['In-Queue'], color: "#00b2fe"},
                            {y: this.campaignStats.Fail, color: "#b558f6"},
                            {y: this.campaignStats.Sent, color: "#fec400"},
                        ]
                    }]
                });
                chart.render();
            }
        }
    }
</script>

<style>
    .gm-style-iw-d div {
        color: black;
    }
</style>