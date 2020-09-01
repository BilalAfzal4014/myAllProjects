<template>
    <div>
        <div class="tab" id="conversions_tab">
            <div class="inner_tab_widget">
                <div class="heading_holder">
                    <h2>Assign Conversion Events</h2>
                </div>
                <div class="txt_holder">
                    <div class="row">
                        <strong>The conversion events must be assigned during Campaign creation, and cannot be changed
                            once
                            a Campaign has launched.</strong>
                    </div>
                </div>
            </div>
            <div class="inner_tab_widget">
                <div class="heading_holder">
                    <h2>Choose Conversion Event Type</h2>
                </div>
                <div class="txt_holder">
                    <div class="row">
                        <select style="margin-bottom: 20px" class="fluid add" v-model="conversionUi.selectedConversion"
                                v-on:change="addConversion">
                            <option v-for="conversion in campaignConversion" v-bind:value="conversion">
                                {{conversion.name}}
                            </option>
                        </select>
                        <div class="append_divs t_margin">
                            <div class="conversion_loop" v-for="(conversionObj, index) in conversionUi.conversions">
                                <div class="append_div">
                                    <a class="anchor_pointer row_remover align_right" v-on:click="popConversion(index)"><i
                                            class="far fa-trash-alt"></i></a>
                                    <div class="cols">
                                        <div class="col">
                                            <input type="text" disabled v-model="conversionObj.name">
                                            <strong>Set Conversion Deadline</strong>
                                            <p>The maximum amount of time that may pass between a user receiving a
                                                Campaign
                                                and
                                                performing the assigned action for it to be considered a conversion.</p>
                                        </div>
                                        <div class="col">
                                            <select class="fluid add"
                                                    v-bind:style="(conversion.totalSelectedConversions[index].value != '' && conversion.totalSelectedConversions[index].value != null) ? 'visibility: visible': 'visibility: hidden'"
                                                    v-model="conversion.totalSelectedConversions[index].value">
                                                <option v-for="value in conversionObj.values" v-bind:value="value">
                                                    {{value}}
                                                </option>
                                            </select>
                                            <div class="inner_row">
                                                <input type="number" min="1"
                                                       v-model="conversion.totalSelectedConversions[index].validity">
                                                <select class="fluid add"
                                                        v-model="conversion.totalSelectedConversions[index].period">
                                                    <option v-bind:value="'minute'">Minute</option>
                                                    <option v-bind:value="'hour'">Hour</option>
                                                    <option v-bind:value="'day'">Day</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <span v-if="index != conversionUi.conversions.length - 1">AND</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="inner_tab_widget">
                <div class="heading_holder">
                    <h2>Apps Selection</h2>
                </div>
                <div class="txt_holder" v-if="campaignApps">
                    <div class="checks_row">
                        <input type="checkbox" id="select_all_apps" v-on:change="checkUncheckAllApps"
                               v-bind:checked="campaignApps.length == conversion.apps.length">
                        <label for="select_all_apps"><i class="fa fa-check-square" aria-hidden="true"></i> Select All
                            Apps</label>
                    </div>
                    <div class="checks_row">
                        <ul class="checks_list list_none">
                            <li v-for="app in campaignApps" style="position: relative">
                                <input class="checkBox_visibility" type="checkbox" v-bind:value="app.id"
                                       v-model="conversion.apps">
                                <label>
                                    <i class="fa fa-check-square" aria-hidden="true"></i>
                                    <div class="txt">
                                        {{app.name}}<br>{{app.platform}}
                                    </div>
                                </label>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <div class="tab sec_hidden">
            <div class="inner_tab_widget">
                <div class="heading_holder">
                    <div class="align_right"><a href="#">Read More</a></div>
                    <h2>Assign Conversion Events</h2>
                </div>
                <div class="txt_holder">
                    <div class="row">
                        <strong>Define up to 4 conversion events to track for this Campaign.
                            the
                            conversion events must be assigned during Campaign creation, and
                            cannot be changed once a Campaign has launched.</strong>
                    </div>
                </div>
            </div>
            <div class="inner_tab_widget">
                <div class="heading_holder">
                    <h2>Primary Conversion Event - A</h2>
                </div>
                <div class="txt_holder">
                    <div class="green_msg">
                        <p>The primary conversion event is used to determine the winning message variation for this
                            Campaign.</p>
                    </div>
                    <h4>Choose Conversion Event Type</h4>
                    <div class="row">
                        <select class="fluid add">
                            <option>Choose Conversion</option>
                            <option>Start Session</option>
                            <option>Make Purchase</option>
                            <option>Perform Custom Event</option>
                        </select>
                    </div>
                    <div class="row">
                        <div class="append_divs t_margin">
                            <div class="append_div">
                                <a href="#" class="row_remover align_right"><i class="far fa-trash-alt"></i></a>
                                <div class="cols">
                                    <div class="col">
                                        <input type="text" disabled="" placeholder="Start Session">
                                        <strong>Set Conversion Deadline</strong>
                                        <p>The maximum amount of time that may pass between
                                            a
                                            user receiving a Campaign and performing the
                                            assigned action for it to be considered a
                                            conversion.</p>
                                    </div>
                                    <div class="col">
                                        <select class="fluid add">
                                            <option>open()</option>
                                            <option>openScreen()</option>
                                            <option>savePayment()</option>
                                            <option>doCheckout()</option>
                                        </select>
                                        <div class="inner_row">
                                            <input type="number" min="1" placeholder="1">
                                            <select class="fluid add">
                                                <option>Minute</option>
                                                <option>Hour</option>
                                                <option>Day</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="extra_checks"><span class="misc_check active">AND</span></div>
                            <div class="append_divs t_margin">
                                <div class="append_div">
                                    <a href="#" class="row_remover align_right"><i class="far fa-trash-alt"></i></a>
                                    <div class="cols">
                                        <div class="col">
                                            <input type="text" disabled="" placeholder="Start Session">
                                            <strong>Set Conversion Deadline</strong>
                                            <p>The maximum amount of time that may pass
                                                between
                                                a user receiving a Campaign and performing
                                                the
                                                assigned action for it to be considered a
                                                conversion.</p>
                                        </div>
                                        <div class="col">
                                            <select class="fluid add">
                                                <option>open()</option>
                                                <option>openScreen()</option>
                                                <option>savePayment()</option>
                                                <option>doCheckout()</option>
                                            </select>
                                            <div class="inner_row">
                                                <input type="number" min="1" placeholder="1">
                                                <select class="fluid add">
                                                    <option>Minute</option>
                                                    <option>Hour</option>
                                                    <option>Day</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="inner_tab_widget">
                            <div class="heading_holder">
                                <h2>Apps Selection</h2>
                            </div>
                            <div class="txt_holder">
                                <div class="checks_row">
                                    <input checked="" type="checkbox" id="select_all_apps">
                                    <label for="select_all_apps"><i class="far fa-square"></i> Select All Apps</label>
                                </div>
                                <div class="checks_row">
                                    <ul class="checks_list list_none">
                                        <li>
                                            <input checked="" type="checkbox" id="check1">
                                            <label for="check1">
                                                <i class="far fa-square"></i>
                                                <div class="txt">
                                                    <img src="images/icon.png" alt="icon" class="icon_img">
                                                    <span>DevEngagement<br>IOS</span>
                                                </div>
                                            </label>
                                        </li>
                                        <li>
                                            <input checked="" type="checkbox" id="check2">
                                            <label for="check2">
                                                <i class="far fa-square"></i>
                                                <div class="txt">
                                                    <img src="images/icon.png" alt="icon" class="icon_img">
                                                    <span>DevCoredirection<br>ANDROID</span>
                                                </div>
                                            </label>
                                        </li>
                                        <li>
                                            <input checked="" type="checkbox" id="check3">
                                            <label for="check3">
                                                <i class="far fa-square"></i>
                                                <div class="txt">
                                                    <img src="images/icon.png" alt="icon" class="icon_img">
                                                    <span>DevCoredirection<br>IOS</span>
                                                </div>
                                            </label>
                                        </li>
                                        <li>
                                            <input checked="" type="checkbox" id="check4">
                                            <label for="check4">
                                                <i class="far fa-square"></i>
                                                <div class="txt">
                                                    <img src="images/icon.png" alt="icon" class="icon_img">
                                                    <span>DevEngagement<br>IOS</span>
                                                </div>
                                            </label>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div class="inner_tab">
                            <div class="togle_div">
                                <h3>Record Conversions for Users Who</h3>
                                <div class="purchase_opts">
                                    <div class="purchase_col">
                                        <input type="checkbox" id="purchase_check">
                                        <label for="purchase_check">Make Any Purchase</label>
                                    </div>
                                    <div class="purchase_col">
                                        <input type="checkbox" id="specific_check">
                                        <label for="specific_check">Make Any Purchase</label>
                                    </div>
                                    <div class="purchase_col">
                                        <select class="add">
                                            <option>D16CYCY</option>
                                        </select>
                                    </div>
                                    <div class="purchase_col text-right">
                                        <input type="checkbox" id="filter_checkbox">
                                        <label for="filter_checkbox">Add property filters</label>
                                    </div>
                                </div>
                            </div>
                            <div class="togle_div">
                                <h3>Record Conversions for Users Who</h3>
                                <div class="purchase_opts">
                                    <div class="purchase_col medium">
                                        <select class="add">
                                            <option>referral success</option>
                                            <option>referral success</option>
                                            <option>referral success</option>
                                        </select>
                                    </div>
                                    <div class="purchase_col text-right">
                                        <input type="checkbox" id="filter_checkbox2">
                                        <label for="filter_checkbox2">Add property filters</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="inner_tab_widget">
                <div class="heading_holder">
                    <h2>Set Conversion Deadline</h2>
                </div>
                <div class="txt_holder">
                    <div class="row">
                        <strong class="fluid">The maximum amount of time that may pass
                            between a
                            user receiving a Campaign and performing the assigned action for
                            it
                            to be considered a conversion.</strong>
                        <select>
                            <option>01</option>
                            <option>02</option>
                            <option selected="">03</option>
                            <option>04</option>
                        </select>
                        <select class="w_200">
                            <option>minutes</option>
                            <option>hours</option>
                            <option selected="">days</option>
                            <option>weeks</option>
                        </select>
                        <a href="#" class="custom_btn">Add Conversion Event</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        props: ['campaignConversion', 'campaignApps', 'hitApi', 'campaignId', 'getCampaignStep'],
        data() {
            return {
                conversionUi: {
                    reInject: true,
                    dummyConversion: {
                        id: -1,
                        code: "null",
                        name: "Choose Conversion...",
                        values: []
                    },
                    selectedConversion: {
                        id: -1,
                        code: "null",
                        name: "Choose Conversion...",
                        values: []
                    },
                    conversions: [],
                },
                conversion: {
                    step: "conversion",
                    totalSelectedConversions: [],
                    apps: [],
                }
            };
        },
        methods: {
            addConversion() {
                if (this.conversionUi.selectedConversion.id != -1 && this.conversionUi.conversions.length < 4) {
                    this.conversionUi.conversions.push(JSON.parse(JSON.stringify(this.conversionUi.selectedConversion)));
                    let obj = {
                        id: this.conversionUi.selectedConversion.id,
                        value: this.conversionUi.selectedConversion.values.length > 0 ? this.conversionUi.selectedConversion.values[0] : '',
                        validity: 1,
                        period: 'minute'
                    };
                    this.conversion.totalSelectedConversions.push(obj);
                }
                this.conversionUi.selectedConversion = JSON.parse(JSON.stringify(this.conversionUi.dummyConversion));
            },
            popConversion(index) {
                this.conversionUi.conversions.splice(index, 1);
                this.conversion.totalSelectedConversions.splice(index, 1);
            },
            submitStep() {
                this.removeDuplicateConversions();

                this.bus.$emit("disableNextBtn", true);
                const payLoad = {
                    resource: "campaigns",
                    action: "update",
                    id: this.campaignId,
                    data: this.conversion
                };

                this.api("post",
                    this.constants.getUrl("backendApiUrl"),
                    payLoad,
                    this.authHeaders,
                    true,
                ).then((response) => {
                    this.bus.$emit("disableNextBtn", false);
                    if (response.data.meta.code === 200) {
                        this.updateStep6();
                        this.$emit("goNext");
                    }
                }).catch((error) => {

                });
            },
            removeDuplicateConversions() {
                for (let i = 0; i < this.conversion.totalSelectedConversions.length; i++) {
                    for (let j = i + 1; j < this.conversion.totalSelectedConversions.length; j++) {
                        if (this.conversion.totalSelectedConversions[i].id == this.conversion.totalSelectedConversions[j].id && this.conversion.totalSelectedConversions[i].value == this.conversion.totalSelectedConversions[j].value) {
                            this.conversion.totalSelectedConversions.splice(j, 1);
                            this.conversionUi.conversions.splice(j, 1);
                            j--;
                        }
                    }
                }
            },
            checkUncheckAllApps() {

                if (this.campaignApps.length == this.conversion.apps.length) {
                    this.conversion.apps = [];
                } else {
                    this.conversion.apps = [];
                    for (let i = 0; i < this.campaignApps.length; i++) {
                        this.conversion.apps.push(this.campaignApps[i].id);
                    }
                }
            },
            updateStep6() {
                let arr = [];
                for (let i = 0; i < this.conversionUi.conversions.length; i++) {
                    let obj = this.conversion.totalSelectedConversions[i];
                    obj.name = this.conversionUi.conversions[i].name;
                    arr.push(obj);
                }
                this.bus.$emit("step5", arr);
            }
        },
        watch: {
            hitApi: {
                handler(currVal, oldVal) {
                    if (currVal.step == 5) {
                        this.submitStep();
                    }
                },
                deep: true
            },
            campaignConversion: {
                handler(currVal, oldVal) {
                    if (this.conversionUi.reInject) {
                        this.campaignConversion.unshift(JSON.parse(JSON.stringify(this.conversionUi.dummyConversion)));
                        this.conversionUi.reInject = false;
                    } else {
                        this.conversionUi.reInject = true;
                    }
                },
                deep: true
            },
            getCampaignStep: {
                handler(currVal, oldVal) {
                    let obj = JSON.stringify(currVal);
                    if (obj != "{}") {
                        this.conversion = JSON.parse(obj);
                        for (var i = 0; i < this.conversion.totalSelectedConversions.length; i++) {
                            for (var j = 0; j < this.campaignConversion.length; j++) {
                                if (this.conversion.totalSelectedConversions[i].id == this.campaignConversion[j].id) {
                                    this.conversionUi.conversions.push(JSON.parse(JSON.stringify(this.campaignConversion[j])));
                                    break;
                                }
                            }

                        }
                        this.updateStep6();
                    }
                },
                deep: true
            }
        }
    }

</script>

<style scoped>
    .conversion_loop {
        margin: 0 0 40px 0px;
    }

</style>