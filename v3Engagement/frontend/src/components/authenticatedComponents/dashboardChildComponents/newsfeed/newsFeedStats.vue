<template>
    <div class="page_content_holder">
        <div class="padding_content stats_div">
            <div class="homepage_charts">
                <article class="post">
                    <div class="user_state_cols">
                        <div class="state_col details_col">
                            <div class="state_header no_padding details_header">
                                <div class="align_right">
                                    <input class="table_search" type="text">
                                    <div class="search_opener inline_block"><img
                                            alt="#" src="./../../../../assets/images/search_button.png"></div>
                                </div>
                                <h2>NewsFeed Details</h2>
                            </div>
                            <div class="stats_columns">
                                <div class="single_column">
                                    <div class="same-height">
                                        <div class="single_widget">
                                            <strong class="single_title">{{newsfeed.name}}</strong>
                                            <div style="padding: 0px 20px 61px;" class="photo_holder">
                                                <div class="languages_holder">
                                                    <ul class="list_none flags flag_mode"
                                                        v-if="languages.totalSelectedLang != undefined && languages.totalSelectedLang.length > 0">
                                                        <li v-for="language in languages.totalSelectedLang"
                                                            class="anchor_pointer"
                                                            v-bind:class="languages.languageArr[langIndex].language == language.value ? 'active': ''"
                                                            v-on:click="switchLang(language.value)">
                                                            <img v-bind:src="language.imgUrl">
                                                            <i class="fas fa-times"></i>
                                                        </li>
                                                    </ul>
                                                </div>
                                                <div id="composeNewsfeed" class="compose_layout_holder">
                                                    <div v-if="languages.languageArr != undefined && languages.languageArr.length > 0" id="previewHtml" class="dialogue_holder no_max_height"
                                                         v-html="languages.languageArr[langIndex].template">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="single_column">
                                    <div class="same-height">
                                        <div class="single_widget">
                                            <strong class="single_title">
                                                <a class="float_right" data-target="#delivery_details"
                                                   data-toggle="modal"
                                                   href="#">
                                                    <img alt="#" class="apps_icon"
                                                         src="./../../../../assets/images/file_icon.png"> Details
                                                </a>
                                                <span>Delivery Window</span>
                                            </strong>
                                            <ul class="list_none timings">
                                                <li>
                                                    <strong class="inner_title">START TIME :</strong>
                                                    <span class="inner_txt">{{newsfeed.start_time}}</span>
                                                </li>
                                                <li>
                                                    <strong class="inner_title">END TIME: </strong>
                                                    <span class="inner_txt">{{newsfeed.end_time}}</span>
                                                </li>
                                            </ul>
                                        </div>
                                        <div class="single_widget">
                                            <strong class="single_title">Targeted Audience</strong>
                                            <ul class="list_none timings fluid">
                                                <li>
                                                    <strong class="inner_title">SEGMENT :</strong>
                                                    <div class="inner_cols">
                                                        <div class="inner_col">
                                                            <p>{{newsfeed.segment}}</p>
                                                        </div>
                                                    </div>
                                                </li>
                                            </ul>
                                        </div>
                                        <div class="single_widget">
                                            <table class="performance_table">
                                                <thead>
                                                <tr>
                                                    <th colspan="2">Performance Statistics</th>
                                                    <th><em class="indi_icon"></em> Best Day</th>
                                                    <th><em class="indi_icon red"></em> Worst Day</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                <tr>
                                                    <td colspan="2"><span>VIEWS</span></td>
                                                    <td><em class="indi_icon" v-if="views[0]"></em>
                                                        <span v-if="views[0]"> {{views[0]["created_at"]}}</span>
                                                        <span v-else> N/A</span>
                                                    </td>
                                                    <td><em class="indi_icon red" v-if="views[1]"></em>
                                                        <span v-if="views[1]"> {{views[1]["created_at"]}}</span>
                                                        <span v-else> N/A</span>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td colspan="2"><span>CLICKS</span></td>
                                                    <td><em class="indi_icon" v-if="clicks[0]"></em>
                                                        <span v-if="clicks[0]"> {{clicks[0]["created_at"]}}</span>
                                                        <span v-else> N/A</span>
                                                    </td>
                                                    <td><em class="indi_icon red" v-if="clicks[1]"></em>
                                                        <span v-if="clicks[1]"> {{clicks[1]["created_at"]}}</span>
                                                        <span v-else> N/A</span>
                                                    </td>
                                                </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="state_col">
                            <div class="state_header">
                                <div class="align_right">
                                    <ul class="list_none stats_tabbing">
                                        <li v-bind:class="activetab == 'views' ? 'active' : ''">
                                            <a v-on:click="activetab = 'views'">View</a></li>
                                        <li v-bind:class="activetab == 'clicks' ? 'active' : ''">
                                            <a v-on:click="activetab = 'clicks'">Click</a></li>
                                        <li v-bind:class="activetab == 'click_through' ? 'active' : ''">
                                            <a v-on:click="activetab = 'click_through'">Click Through</a></li>
                                    </ul>
                                    <ul class="state_list add">
                                        <li>
                                            <em class="color_indicate android"></em>
                                            <em>android</em>
                                        </li>
                                        <li>
                                            <em class="color_indicate ios"></em>
                                            <em>ios</em>
                                        </li>
                                    </ul>
                                </div>
                                <h2>Views - Clicks</h2>
                            </div>

                            <div id="stats_tab_content">
                                <div id="view_chart" v-bind:class="activetab === 'views' ? 'tab active' : 'tab'">
                                    <div id="chart_views" style="height: 300px; width: 100%;"></div>
                                </div>
                                <div id="click_chart" v-bind:class="activetab === 'clicks' ? 'tab active' : 'tab'">
                                    <div id="chart_clicks" style="height: 300px; width: 100%;"></div>
                                </div>
                                <div id="click_through"
                                     v-bind:class="activetab === 'click_through' ? 'tab active' : 'tab'">
                                    <div id="chart_click_through" style="height: 300px; width: 100%;"></div>
                                </div>
                            </div>
                        </div>
                        <div class="state_col details_col">
                            <div class="state_header no_padding details_header">
                                <div class="align_right">
                                    <input placeholder="Start Date" type="date" v-model="model_views_clicks.start_date">
                                    <input placeholder="End Date" type="date" v-model="model_views_clicks.end_date">
                                    <button class="apply_btn" type="button" v-on:click="filterViewsCount">Apply</button>
                                </div>
                                <h2>Heading</h2>
                            </div>
                            <div class="stats_columns">
                                <div class="single_column light_border">
                                    <div class="single_widget">
                                        <strong class="single_title text-center">IOS</strong>
                                        <ul class="list_none timings fluid percentages">
                                            <li>
                                                <div class="inner_cols">
                                                    <div class="inner_col">
                                                        <p>CLICKS</p>
                                                        <h5 v-if="views_clicks.ios">{{views_clicks.ios.clicks}}</h5>
                                                    </div>
                                                    <div class="inner_col">
                                                        <p>VIEWS</p>
                                                        <h5 v-if="views_clicks.ios">{{views_clicks.ios.views}}</h5>
                                                    </div>
                                                    <div class="inner_col">
                                                        <p>PERCENTAGE</p>
                                                        <h5 v-if="views_clicks.ios">{{views_clicks.ios.percentage}}</h5>
                                                    </div>
                                                </div>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                                <div class="single_column light_border">
                                    <div class="single_widget">
                                        <strong class="single_title text-center">ANDROID</strong>
                                        <ul class="list_none timings fluid percentages">
                                            <li>
                                                <div class="inner_cols">
                                                    <div class="inner_col">
                                                        <p>CLICKS</p>
                                                        <h5 v-if="views_clicks.android">
                                                            {{views_clicks.android.clicks}}</h5>
                                                    </div>
                                                    <div class="inner_col">
                                                        <p>VIEWS</p>
                                                        <h5 v-if="views_clicks.android">
                                                            {{views_clicks.android.views}}</h5>
                                                    </div>
                                                    <div class="inner_col">
                                                        <p>PERCENTAGE</p>
                                                        <h5 v-if="views_clicks.android">
                                                            {{views_clicks.android.percentage}}</h5>
                                                    </div>
                                                </div>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </article>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        name: "newsfeed-Stats",
        mounted() {
            let id = this.$route.params.id;
            this.getById(id);
            this.getByViewsClicks(id);
            this.getChart('views');
            this.getChart('clicks');
            this.getChart('click_through');
        },
        data() {
            return {
                activetab: "views",
                views: {},
                clicks: {},
                views_clicks: {},
                model_views_clicks: {
                    start_date: '',
                    end_date: ''
                },
                iframeStyleLink: '',
                currentVariant: 0,
                currentLang: 0,
                languages: {},
                newsfeed: {},
                langIndex: 0,
            }
        },
        methods: {
            switchLang(lang) {
                let langArr = this.languages.languageArr;
                for (let i = 0; i < langArr.length; i++) {
                    if (langArr[i].language == lang) {
                        this.langIndex = i;
                        break;
                    }
                }
            },
            filterViewsCount() {
                let id = this.$route.params.id;
                this.getByViewsClicks(id)
            },
            getById(id) {
                var payload = {
                    resource: "newsFeed/stats",
                    action: "get",
                    id: id
                };
                this.api("post",
                    this.constants.getUrl("backendApiUrl"),
                    payload,
                    this.authHeaders
                ).then((response) => {
                    console.log('statsResponse', response);
                    let data = response.data.data;
                    this.newsfeed = data.newsfeed;
                    this.views = data.views;
                    this.clicks = data.clicks;
                    this.languages = data.lang;
                }).catch((error) => {
                    console.log('error', error);
                });
            },
            getByViewsClicks(id) {
                var payload = {
                    resource: "newsFeed/stats/views-clicks",
                    action: "get",
                    data: this.model_views_clicks,
                    id: id
                };
                this.api("post",
                    this.constants.getUrl("backendApiUrl"),
                    payload,
                    this.authHeaders
                ).then((response) => {
                    let data = response.data.data;
                    console.log("newsfeed Resource", data);
                    this.views_clicks = data
                }).catch((error) => {
                    console.log('error', error);
                });
            },
            getChart(type) {
                let $this = this;
                let id = this.$route.params.id;

                var payload = {
                    resource: "newsfeed/stats/chart",
                    action: "get",
                    id: id,
                    data: {
                        "type": type
                    }
                };
                this.api("post",
                    this.constants.getUrl("backendApiUrl"),
                    payload,
                    this.authHeaders
                ).then((response) => {
                    console.log("chart " + type, response);
                    $this.$nextTick(() => {
                        $this.chartSelector(response.data.data, type)
                    });
                }).catch((error) => {
                    console.log('error', error);
                });
            },
            chartSelector(data, type) {
                var options = {
                    animationEnabled: true,
                    exportEnabled: true,
                    theme: "light1", // "light1", "light2", "dark1", "dark2"
                    dataPointWidth: 25,
                    title: {
                        text: ""
                    },
                    axisY: {
                        title: ""
                    },
                    legend: {
                        cursor: "pointer",
                        itemclick: this.toggleDataSeries
                    },
                    toolTip: {
                        shared: true,
                        content: this.toolTipFormatter
                    },
                    data: data
                };
                $("#chart_" + type).CanvasJSChart(options);
            },
            toolTipFormatter(e) {
                var str = "";
                var total = 0;
                var str3;
                var str2;
                for (var i = 0; i < e.entries.length; i++) {
                    var str1 = "<span style= \"color:" + e.entries[i].dataSeries.color + "\">" + e.entries[i].dataSeries.name + "</span>: <strong>" + e.entries[i].dataPoint.y + "</strong> <br/>";
                    total = e.entries[i].dataPoint.y + total;
                    str = str.concat(str1);
                }
                str2 = "<strong>" + e.entries[0].dataPoint.label + "</strong> <br/>";
                str3 = "<span style = \"color:Tomato\">Total: </span><strong>" + total + "</strong><br/>";
                return (str2.concat(str)).concat(str3);
            },
            toggleDataSeries(e) {
                if (typeof (e.dataSeries.visible) === "undefined" || e.dataSeries.visible) {
                    e.dataSeries.visible = false;
                } else {
                    e.dataSeries.visible = true;
                }
                chart.render();
            },
        }
    }
</script>

<style scoped>
    .flags li.active {
        background: gainsboro;
    }

    .languages_holder .flag_mode {
        text-align: left !important;
        margin-top: 20px !important;
        margin-left: 8px !important;
    }

    #composeNewsfeed {
        padding: 14px 14px !important;
        background: #e2e2e2 !important;
    }

    .dialogue.card.cross-promotion .txt {
        max-width: 49%;
    }

</style>

<style>
    #previewHtml div {
        z-index: 0;
    }
</style>
