<template>
    <div class="state_col details_col">
        <div style="margin-bottom: 25px">
            <h2>Campaign Tracking -
                <button class="apply_btn" type="button" v-on:click="exportUsers($route.params.id)">Export</button>
            </h2>
        </div>
        <div class="state_header no_padding details_header">
            <div class="align_right">
                <button class="apply_btn" type="button" v-on:click="trackingListReload">Reload List</button>
                <input placeholder="Start Date" class="input_width_campaign_stats" type="date"
                       v-model="tracking.start_date">
                <input placeholder="End Date" class="input_width_campaign_stats" type="date"
                       v-model="tracking.end_date">
                <select class="select_status select_width_campaign_stats" v-model="tracking.selectedVariantFilter">
                    <option v-bind:value="-1">Select Variant</option>
                    <option v-for="filter in variantFilters" v-bind:value="filter.variantId">{{filter.label}}</option>
                </select>
                <select class="select_status select_width_campaign_stats" v-model="tracking.status">
                    <option value="">Select Status</option>
                    <option value="added">Added</option>
                    <option value="executing">Executing</option>
                    <option value="completed">Completed</option>
                    <option value="failed">Failed</option>
                </select>
                <select class="select_status select_width_campaign_stats" v-model="tracking.deviceType">
                    <option value="-1">Select Device</option>
                    <option value="ios">IOS</option>
                    <option value="android">Android</option>
                    <option value="web">Web</option>
                </select>
                <button class="apply_btn" type="button" v-on:click="searchCampaignTracking">Apply</button>
                <input class="table_search active" type="text" v-model="tracking.global">
                <div class="search_opener inline_block">
                    <!--<img alt="#" src="../../../../../assets/images/search_button.png" v-on:click="this.globalFilterTracking">-->
                    <i class="fa fa-search" aria-hidden="true" v-on:click="globalFilterTracking"></i>
                </div>
            </div>
            <!--<h2>Campaign Tracking</h2>-->
        </div>
        <div class="table_holder no_padding details_table">
            <listing v-bind:apiPayLoad="trackingDataTable.payLoad"
                     v-bind:componentName="trackingDataTable.componentName"
                     v-bind:customFiltersProp="trackingDataTable.customFilters"
                     v-bind:tableColumn="trackingDataTable.column"
                     v-bind:tableColumnsClasses="trackingDataTable.columnsClasses"
                     v-bind:tableFilterable="trackingDataTable.filterable"
                     v-bind:tableHeadings="trackingDataTable.headings"
                     v-bind:tableMapColumns="trackingDataTable.mapColumns"
                     v-bind:tableReloading="trackingDataTable.reloadProp"
                     v-bind:tableSearch="trackingDataTable.searchProp"
                     v-bind:tableSortable="trackingDataTable.sortable">
                <template slot="status-slot" slot-scope="data">
                    <span v-bind:class="data.data.row.status == 'added' ? 'added': data.data.row.status == 'failed' ? 'failed': data.data.row.status == 'executing' ? 'executing': data.data.row.status == 'completed' ? 'completed': ''">
                        {{data.data.row.status}}
                    </span>
                </template>
                <template slot="email-slot" slot-scope="data">
                    <router-link
                            v-bind:to="'/dashboard/user-stats/' + data.nameColumn.row.row_id">
                        {{data.nameColumn.row.email}}
                    </router-link>
                </template>
                <template slot="listing" slot-scope="data">
                    <div v-if="data.actionlisting.row.status=='failed'">
                        <i class="fas fa-cog" v-on:click.stop="trackingAction(data.actionlisting)"></i>
                        <i class="fas fa-sort-down" v-on:click.stop="trackingAction(data.actionlisting)"></i>
                        <ul class="table_drop list_none" v-bind:id="data.actionlisting.row.id+'_action'">
                            <li>
                                <a v-on:click="reSendNotification(data.actionlisting.row, 1)"><i
                                        class="fas fa-edit"></i>ReSend</a>
                            </li>
                        </ul>
                    </div>
                </template>
                <template slot="message" slot-scope="data">
                    <a class="anchor_pointer abbbc" title="Message" data-toggle="popover" data-trigger="hover"
                       v-bind:data-content="data.data.row.message">{{data.data.row.message}}</a>
                </template>
                <template slot="variant" slot-scope="data">
                    <span class="anchor_pointer" v-on:click="seeVariantLang(data.data.row.variantLang)">{{data.data.row.variant}}</span>
                </template>
            </listing>
        </div>
    </div>
</template>

<script>
    import listing from '../../../../otherComponents/datatable/datatableComponent';
    import csvDownload from "../../../../../mixins/csvDownload/csvDownload";

    export default {
        components: {
            listing
        },
        mixins: [csvDownload],
        data() {
            let id = this.$route.params.id;

            return {
                trackingDataTable: {
                    reloadProp: false,
                    componentName: "campaignTracking",
                    column: ['variant', 'track_key', 'email', 'sent_at', 'status', 'device_type', 'viewed_at', 'message', 'Action'],
                    mapColumns: ['id', 'row_id', 'variantLang', 'variant', 'track_key', 'email', 'sent_at', 'status', 'device_type', 'viewed_at', 'message'],
                    filterable: ['track_key', 'email', 'sent_at', 'status', 'device_type', 'viewed_at', 'message'],
                    sortable: ['track_key', 'email', 'sent_at', 'status', 'device_type', 'viewed_at', 'message'],
                    headings: {
                        track_key: 'Track key',
                        email: 'Email ',
                        sent_at: 'Sent At',
                        status: 'Status',
                        viewed_at: 'Viewed At',
                        message: 'Message',
                        action: 'Action',
                        device_type: 'Device Type'
                    },
                    columnsClasses: {
                        track_key: '',
                        email: '',
                        sent_at: '',
                        status: '',
                        viewed_at: '',
                        message: '',
                        action: ''
                    },
                    payLoad: {
                        "resource": "campaign/tracking/list",
                        "action": "get",
                        "id": id
                    },
                    searchProp: '',
                    customFilters: ['track_table_filter'],
                    campaignFilterProp: {},
                },
                tracking: {
                    start_date: '',
                    end_date: '',
                    status: '',
                    global: '',
                    selectedVariantFilter: -1,
                    deviceType: -1,
                },
                variantFilters: [],

            }
        },
        mounted() {
            this.fetchVariants(this.$route.params.id);
            $("#footer").css({display: "none"});
        },
        beforeDestroy() {
            $("#footer").css({display: "block"});
        },
        methods: {
            trackingListReload() {
                this.trackingDataTable.reloadProp = !this.trackingDataTable.reloadProp;
            },
            searchCampaignTracking() {
                let data = {
                    status: this.tracking.status,
                    start_date: this.tracking.start_date,
                    end_date: this.tracking.end_date,
                    global: this.tracking.global,
                    variantFilter: this.tracking.selectedVariantFilter,
                    deviceType: this.tracking.deviceType
                };

                this.trackingDataTable.searchProp = data
            },
            globalFilterTracking() {
                this.tracking.status = '';
                this.tracking.start_date = '';
                this.tracking.end_date = '';
                this.searchCampaignTracking();
            },
            trackingAction(rowObj) {
                $(".table_drop:not(#" + rowObj.row.id + "_action)").hide();
                $("#" + rowObj.row.id + "_action").slideToggle('100');
            },
            reSendNotification(rowObj) {
                var payload = {
                    resource: "campaign/resend/notification",
                    action: "post",
                    data: rowObj
                };
                this.api("post",
                    this.constants.getUrl("backendApiUrl"),
                    payload,
                    this.authHeaders
                ).then((response) => {
                    console.log("response", response);
                    if (response.data.meta.code == 200) {
                        this.$swal('success', 'Notification Send Successfully', 'success');
                    } else {
                        this.$swal('error', response.data.error[0], 'error');
                    }
                    this.trackingDataTable.reloadProp = !this.trackingDataTable.reloadProp;
                }).catch((error) => {
                    console.log(error);
                    this.$swal('error', error, 'error');
                });
            },
            seeVariantLang(str) {
                str = str.split("-");
                let obj = {
                    variantIndex: str[0],
                    lang: str[1]
                };
                this.$emit("changeVariantAndLang", obj);
            },
            fetchVariants(campaignId) {
                const payLoad = {
                    resource: "campaign/variants/" + campaignId,
                    action: "get",
                };

                this.api(
                    "post",
                    this.constants.getUrl("backendApiUrl"),
                    payLoad,
                    this.authHeaders,
                ).then((response) => {
                    this.variantFilters = response.data.data;
                }).catch((error) => {

                })
            },
            exportUsers(campaignId) {

                const payLoad = {
                    resource: "campaign/tracking/export",
                    action: "get",
                    id: campaignId,
                    data: {
                        track_table_filter: {
                            status: this.tracking.status,
                            start_date: this.tracking.start_date,
                            end_date: this.tracking.end_date,
                            global: this.tracking.global,
                            variantFilter: this.tracking.selectedVariantFilter,
                            deviceType: this.tracking.deviceType
                        },
                        columns: ["track_key", "email", "sent_at", "status", "device_type", "viewed_at", "message"],
                        query: "",
                        orderBy: "row_id",
                        ascending: 1,
                        byColumn: 0
                    }
                };

                this.api("post",
                    this.constants.getUrl("backendApiUrl"),
                    payLoad,
                    this.authHeaders
                ).then((response) => {
                    if (response.data.meta.code === 200) {
                        this.downloadCSV("campaignTracking - " + new Date().toLocaleDateString() + " " + new Date().toLocaleTimeString() + ".csv", response.data.data);
                    }
                }).catch((error) => {

                });
            },
        }
    }
</script>

<style>
    .added {
        background: #605b5b;
        width: 80px;
        display: inline-block;
        border-radius: 4px;
        color: #fff;
        padding: 3px 10px 4px;
        font-size: 11px;
    }

    .executing {
        background: #f09c25;
        width: 80px;
        display: inline-block;
        border-radius: 4px;
        color: #fff;
        padding: 3px 10px 4px;
        font-size: 11px;
    }

    .completed {
        background: #2a8689;
        width: 80px;
        display: inline-block;
        border-radius: 4px;
        color: #fff;
        padding: 3px 10px 4px;
        font-size: 11px;
    }

    .failed {
        background: #ed1c24;
        width: 80px;
        display: inline-block;
        border-radius: 4px;
        color: #fff;
        padding: 3px 10px 4px;
        font-size: 11px;
    }

    #campaignTracking table tbody tr td:nth-child(6) a {
        color: #666;
        overflow: hidden;
        white-space: nowrap;
        text-overflow: ellipsis;
        display: block;
    }

    .popover {
        width: 200px;
        right: auto !important;
        margin: 0 !important;
        left: -201px !important;
        top: 0 !important;
        border-radius: 0;
        text-align: center;
    }

    .popover.right > .arrow {
        left: auto;
        right: -11px;
        top: 10px !important;
        margin-top: 0;
        transform: rotate(180deg);
        -webkit-transform: rotate(180deg);
        -moz-transform: rotate(180deg);
    }

    .select_width_campaign_stats {
        width: 124px !important;
    }

    .input_width_campaign_stats {
        width: 135px !important;
    }


</style>