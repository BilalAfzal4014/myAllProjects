<template>
    <div class="timeline">
        <header class="timeline_header">
            <div class="align_left">
                <h3>Users</h3>
            </div>
            <div class="align_right" style="width: 100%; float: none; display: inline-block">
                <div style="padding-top: 10px">
                    <span>from</span>
                    <input v-on:change="getUserStats" style="width: 138px" type="date" class="input_date"
                           v-model="selectedInputs.startDate" placeholder="dd-mm-yyy">
                    <span class="to-date">to</span>
                    <input v-on:change="getUserStats" style="width: 138px" type="date" class="input_date"
                           v-model="selectedInputs.endDate" placeholder="dd-mm-yyy">
                </div>
            </div>
        </header>
        <div class="timeline_map">
            <div class="border_box">
                <div class="boxes_cols">
                    <div class="box_col">
                        <span>Total Users</span>
                        <strong>{{usersCount.total}}</strong>
                    </div>
                    <div class="box_col">
                        <span>Total Active</span>
                        <strong class="green">{{usersCount.active}}</strong>
                    </div>
                    <div class="box_col">
                        <span>Total Inactive</span>
                        <strong class="pink">{{usersCount.inactive}}</strong>
                    </div>
                </div>
                <strong class="box_title">Overall users</strong>
                <div id="chart4" style="width: 100%; height: 206px;display: inline-block;"></div>
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
                userStats: [],
                usersCount: {
                    total: 0,
                    active: 0,
                    inactive: 0
                }
            };
        },
        mounted() {
            this.getUserStats();
        },
        methods: {
            getUserStats() {
                const payLoad = {
                    resource: "dashboard/user-stats/count",
                    action: "post",
                    data: this.selectedInputs
                };

                this.api("post",
                    this.constants.getUrl("backendApiUrl"),
                    payLoad,
                    this.authHeaders
                ).then((response) => {
                    this.userStats = response.data.data;
                    this.transformDate();
                    this.populateUserChart();
                }).catch((error) => {

                });
            },
            populateUserChart() {
                let chart4 = new CanvasJS.Chart("chart4",
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
                                dataPoints: this.userStats.active
                            },
                            {
                                type: "spline",
                                lineColor: "#27c391",
                                markerSize: 0,
                                markerColor: "#f1f6fe",
                                markerBorderColor: "#27c391",
                                markerBorderThickness: 4,
                                dataPoints: this.userStats.inactive
                            }
                        ]
                    });
                chart4.render();
            },
            transformDate() {
                let active = [];
                let activeCount = 0;
                for (let i = 0; i < this.userStats.active.length; i++) {
                    let obj = {
                        label: this.userStats.active[i].created_at,
                        y: this.userStats.active[i].totalUsers
                    };
                    active.push(obj);
                    activeCount += parseInt(this.userStats.active[i].totalUsers);
                }

                let inactive = [];
                let inactiveCount = 0;
                for (let i = 0; i < this.userStats.inactive.length; i++) {
                    let obj = {
                        label: this.userStats.inactive[i].created_at,
                        y: this.userStats.inactive[i].totalUsers
                    };
                    inactive.push(obj);
                    inactiveCount += parseInt(this.userStats.inactive[i].totalUsers);
                }

                this.userStats.active = JSON.parse(JSON.stringify(active));
                this.userStats.inactive = JSON.parse(JSON.stringify(inactive));
                this.usersCount.total = activeCount + inactiveCount;
                this.usersCount.active = activeCount;
                this.usersCount.inactive = inactiveCount;
            }
        }
    }
</script>

<style>

</style>