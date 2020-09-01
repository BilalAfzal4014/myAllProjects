<template>
    <div class="timeline">
        <header class="timeline_header">
            <div class="align_left">
                <h3>Newsfeed</h3>
            </div>
            <div class="align_right" style="width: 100%; float: none; display: inline-block">
                <div style="padding-top: 10px">
                    <span>from</span>
                    <input v-on:change="getNewsFeedStatsCount" style="width: 138px" type="date" class="input_date"
                           v-model="selectedInputs.startDate" placeholder="dd-mm-yyy">
                    <span class="to-date">to</span>
                    <input v-on:change="getNewsFeedStatsCount" style="width: 138px" type="date" class="input_date"
                           v-model="selectedInputs.endDate" placeholder="dd-mm-yyy">
                </div>
            </div>
        </header>
        <div class="timeline_map">
            <div class="border_box">
                <div class="boxes_cols two">
                    <div class="box_col">
                        <span>Clicks</span>
                        <strong>{{newsFeedStats.clicks}}</strong>
                    </div>
                    <div class="box_col">
                        <span>Views</span>
                        <strong class="green">{{newsFeedStats.views}}</strong>
                    </div>
                </div>
                <strong class="box_title">Overall Stastics</strong>
                <div id="chart3" style="width: 100%; height: 206px; display: inline-block;"></div>
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
                selectedInputs: {
                    startDate: startDate,
                    endDate: new Date().toISOString().split('T')[0]
                },
                newsFeedStats: {}
            };
        },
        mounted() {
            this.getNewsFeedStatsCount();
        },
        methods: {
            getNewsFeedStatsCount() {
                const payLoad = {
                    resource: "dashboard/newsfeed-stats/count",
                    action: "post",
                    data: this.selectedInputs
                };

                this.api("post",
                    this.constants.getUrl("backendApiUrl"),
                    payLoad,
                    this.authHeaders
                ).then((response) => {
                    this.newsFeedStats = response.data.data;
                    this.transformDatea();
                    this.populateNewsFeedChart();
                }).catch((error) => {

                });
            },
            populateNewsFeedChart() {
                let chart3 = new CanvasJS.Chart("chart3",
                    {
                        animationEnabled: true,
                        title: {
                            text: ""
                        },
                        axisX: {
                            interval: 10,
                            type: 'date',
                            gridColor: "transparent",
                            tickLength: 0,
                            lineThickness: 0,
                            labelFormatter: function () {
                                return " ";
                            }
                        },
                        axisY: {
                            interval: 10,
                            gridColor: "transparent",
                            tickLength: 0,
                            lineThickness: 0,
                            labelFormatter: function () {
                                return " ";
                            }
                        },
                        data: [
                            {
                                type: "spline",
                                lineColor: "#4274ee",
                                markerSize: 0,
                                markerColor: "#f1f6fe",
                                markerBorderColor: "#4274ee",
                                markerBorderThickness: 4,
                                dataPoints: this.newsFeedStats.clicksInterval
                            },
                            {
                                type: "spline",
                                lineColor: "#27c391",
                                markerSize: 0,
                                markerColor: "#f1f6fe",
                                markerBorderColor: "#27c391",
                                markerBorderThickness: 4,
                                dataPoints: this.newsFeedStats.viewsInterval
                            }
                        ]
                    });
                chart3.render();


            },
            transformDatea() {
                let arr = [];
                for (let key in this.newsFeedStats.clicksInterval) {
                    let obj = {
                        label: key,
                        y: this.newsFeedStats.clicksInterval[key]
                    };
                    arr.push(obj);
                }
                this.newsFeedStats.clicksInterval = JSON.parse(JSON.stringify(arr));

                arr = [];
                for (let key in this.newsFeedStats.viewsInterval) {
                    let obj = {
                        label: key,
                        y: this.newsFeedStats.viewsInterval[key]
                    };
                    arr.push(obj);
                }
                this.newsFeedStats.viewsInterval = JSON.parse(JSON.stringify(arr));
            }
        }
    }
</script>

<style>

</style>