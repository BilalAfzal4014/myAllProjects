<template>
    <div>
        <div class="tab" id="confirm_tab">
            <div class="heading_holder no_border">
                <h2>Messages <span></span></h2>
            </div>
            <div class="twocolumns fixed_width_cols">
                <div class="column">
                    <div class="column_text">
                        <header class="table_header top">
                            <div class="table_header_holder choose_platform_cols add">
                                <a v-on:click="updateStep(2)" class="anchor_pointer edit_tag"><i
                                        class="fas fa-edit"></i></a>
                                <ul class="variant_list fluid list_none" v-if="launch.step2.variants != undefined">
                                    <li class="anchor_pointer" v-for="(variant, index) in launch.step2.variants.length"
                                        v-on:click="shiftVariant(variant-1)"
                                        v-bind:class="index == currentVariant ? 'active': ''">
                                        Variant
                                    </li>
                                </ul>
                            </div>
                        </header>
                        <ul v-if="launch.step2.variants != undefined && launch.step1.campaignType == 'email'"
                            class="list_none info_list">
                            <li>Form <span>(email):</span> {{launch.step1.email}}</li>
                            <li>Form <span>(name):</span> {{launch.step1.name}}</li>
                            <li>Subject: {{launch.step1.subject}}</li>
                        </ul>
                        <div class="languages_holder">
                            <ul class="list_none flags"
                                v-if="launch.step2.variants != undefined && launch.step2.variants.length > 0">
                                <li class="anchor_pointer"
                                    v-for="language in launch.step2.variants[currentVariant].totalLang"
                                    v-on:click="shiftLang(language.value)"
                                    v-bind:class="launch.step2.variants[currentVariant].lang[currentLang].language == language.value ? 'active': ''">
                                    <img v-bind:src="language.imgUrl" alt="England">
                                    <i class="fas fa-times"></i></li>
                            </ul>
                            <div class="table_header_holder"
                                 v-if="launch.step2.variants != undefined && launch.step1.campaignType == 'notEmail'">
                                <div style="width: 100%" class="col dummyCol">
                                    <div class="holder">
                                        <select v-model="changeDevice">
                                            <option v-bind:value="'iphone'">
                                                IPHONE
                                            </option>
                                            <option v-bind:value="'android_mob'">
                                                SAMSUNG
                                            </option>
                                            <option v-bind:value="'ipad'">
                                                IPAD PRO
                                            </option>
                                            <option v-bind:value="'android_tablet'">
                                                SAMSUNG TAB
                                            </option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="confirm_dummy" style="height: 680px">
                            <div v-if="launch.step2.variants != undefined && launch.step1.campaignType == 'notEmail'"
                                 class="preview_skin" v-bind:class="changeDevice">
                                <div class="iframe_holder">
                                    <iframe class="iframe_el"
                                            v-bind:srcdoc="launch.step2.templateCss + launch.step2.variants[currentVariant].lang[currentLang].templateInfo.template"></iframe>
                                </div>
                            </div>
                            <div class="iframe_holder">
                                <iframe style="height: 100%;" class="email_campaign_preview"
                                        v-if="launch.step2.variants != undefined && launch.step1.campaignType == 'email'"
                                        v-bind:srcdoc="launch.step2.variants[currentVariant].lang[currentLang].templateInfo.template"></iframe>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="inner_tab_widget">
                <div class="heading_holder">
                    <div class="align_right">
                        <a class="anchor_pointer" v-on:click="updateStep(3)">
                            <i class="fas fa-edit"></i>
                            <em>Updated</em>
                        </a>
                    </div>
                    <h2>Delivery</h2>
                </div>
                <div class="txt_holder">
                    <div class="row">
                        <div class="row_col">
                            <strong><b>Campaign Rules</b></strong>
                            <h4>Users can receive messages from this Campaign multiple times when at least
                                {{launch.step3.campaignRules}} have
                                elapsed since they were previously targeted</h4>
                        </div>
                        <div class="row_col">
                            <strong><b>Action-Based Delivery</b></strong>
                            <h4>Send Campaign {{launch.step3.actionBased}} after trigger criteria are met</h4>
                        </div>
                    </div>
                </div>
            </div>
            <div class="inner_tab_widget">
                <div class="heading_holder">
                    <div class="align_right">
                        <a class="anchor_pointer" v-on:click="exportUsers">
                            <i class="fas fa-file-export"></i>
                            <em>Export</em>
                        </a>
                        <a class="anchor_pointer" v-on:click="updateStep(4)">
                            <i class="fas fa-edit"></i>
                            <em>Updated</em>
                        </a>
                    </div>
                    <h2>Target Population</h2>
                </div>
                <div class="txt_holder">
                    <div class="row">
                        <strong><b>Reachable Users:</b> {{launch.step4}}</strong>
                    </div>
                </div>
            </div>
            <div class="inner_tab_widget">
                <div class="heading_holder">
                    <div class="align_right">
                        <a class="anchor_pointer" v-on:click="updateStep(5)">
                            <i class="fas fa-edit"></i>
                            <em>Updated</em>
                        </a>
                    </div>
                    <h2>Conversion Events</h2>
                </div>
                <div class="txt_holder">
                    <div class="row">
                        <div class="text_center" v-for="conversionObj in launch.step5">
                            <strong>{{conversionObj.name}} - {{conversionObj.value}}</strong>
                            <strong color="light_color">Conversion Validity: {{conversionObj.validity}} -
                                {{conversionObj.period}}(s)</strong>
                        </div>
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
    import csvDownload from "../../../../../mixins/csvDownload/csvDownload";

    export default {
        props: ['hitApi', 'campaignId'],
        mixins: [csvDownload],
        data() {
            return {
                launch: {
                    step1: {
                        campaignType: "email",
                        email: '',
                        name: '',
                        subject: ''
                    },
                    step2: {},
                    step3: {
                        campaignRules: "NILL",
                        actionBased: "NILL"
                    },
                    step4: 0,
                    step5: [],
                },
                currentVariant: 0,
                currentLang: 0,
                changeDevice: "iphone"
            };
        },
        mounted() {
            this.events();
            this.listenForStep1();
            this.listenForStep2();
            this.listenForStep3();
            this.listenForStep4();
            this.listenForStep5();
        },
        beforeDestroy() {
            this.bus.$off("step1");
            this.bus.$off("step2");
            this.bus.$off("step3");
            this.bus.$off("targetUsers");
            this.bus.$off("step5");
        },
        methods: {
            events() {
                $('.iframe_el').load(function () {
                    let iframe = $('.iframe_el').contents();

                    iframe.find("a").click(function (e) {
                        e.preventDefault();
                    });
                });
            },
            updateStep(stepNo) {
                this.$emit("changeStep", stepNo);
            },
            listenForStep1() {
                this.bus.$on("step1", (data) => {
                    this.launch.step1 = JSON.parse(JSON.stringify(data));
                });
            },
            listenForStep2() {
                this.bus.$on("step2", (data) => {
                    this.currentVariant = 0;
                    this.currentLang = 0;
                    this.launch.step2 = JSON.parse(JSON.stringify(data));
                });
            },
            listenForStep3() {
                this.bus.$on("step3", (data) => {
                    this.launch.step3 = data;
                });
            },
            listenForStep4() {
                this.bus.$on("targetUsers", (data) => {
                    this.launch.step4 = data;
                });
            },
            listenForStep5() {
                this.bus.$on("step5", (data) => {
                    this.launch.step5 = data;
                });
            },
            submitStep() {
                const payLoad = {
                    resource: "campaigns",
                    action: "update",
                    id: this.campaignId,
                    data: {
                        step: "preview",
                        status: 'active'
                    }
                };

                this.api("post",
                    this.constants.getUrl("backendApiUrl"),
                    payLoad,
                    this.authHeaders,
                    true,
                ).then((response) => {
                    if (response.data.meta.code === 200) {
                        this.$router.push("/dashboard/campaign-listing");
                    }
                }).catch((error) => {

                });
            },
            exportUsers() {
                const payLoad = {
                    resource: "campaign/export/" + this.campaignId,
                    action: "get",
                };

                this.api("post",
                    this.constants.getUrl("backendApiUrl"),
                    payLoad,
                    this.authHeaders
                ).then((response) => {
                    if (response.data.meta.code === 200) {
                        this.downloadCSV("campaign - " + new Date().toLocaleDateString() + " " + new Date().toLocaleTimeString() + ".csv", response.data.data);
                    }
                }).catch((error) => {

                });
            },
            shiftLang(value) {
                let langArr = this.launch.step2.variants[this.currentVariant].lang;
                for (let i = 0; i < langArr.length; i++) {
                    if (langArr[i].language == value) {
                        this.currentLang = i;
                        break;
                    }
                }
            },
            shiftVariant(index) {
                this.currentLang = 0;
                this.currentVariant = index;
            }
        },
        watch: {
            hitApi: {
                handler(currVal, oldVal) {
                    if (currVal.step == 6) {
                        this.submitStep();
                    }
                },
                deep: true
            }
        }
    }
</script>

<style scoped>
    .flags img, .adjust_height {
        width: 27px;
        height: 17px;
    }

    .flags li.active {
        background: gainsboro;
    }

    .twocolumns.fixed_width_cols .column {
        width: 100%;
        float: none;
        overflow: hidden;
    }

    .twocolumns.fixed_width_cols .column_text {
        padding: 20px 24px;
    }

    .info_list {
        margin: 0 0 12px;
        color: #666;
        overflow: hidden;
        font-size: 17px;
    }

    .info_list li {
        margin: 0 0 10px;
    }

    .flags li {
        margin-right: 7px;
        padding: 8px 12px;
        border-radius: 5px;
        display: inline-block;
        vertical-align: top;
        background: #f0f0f0;
        line-height: 0;
    }

    .variant_list {
        max-width: 50%;
        float: left;
        color: #fff;
        padding: 7px;
    }

    .variant_list.fluid {
        float: none;
        max-width: none;
        overflow: hidden;
    }


    .twocolumns.fixed_width_cols {
        max-width: 666px;
        margin: 20px auto 20px;
    }

    .fixed_width_cols .variant_list li {
        width: 93px;
        text-align: center;
    }

    .edit_tag {
        float: right;
        margin: 12px 10px 0;
    }

    .column_text .table_header_holder {
        margin: 0 0 15px;
    }


    .no_border {
        border: none !important;
    }

    .heading_holder {
        overflow: hidden;
        background: #f0f0f0;
        padding: 13px 20px;
        border-bottom: 1px solid #dedddd;
    }

    .email_campaign_preview {
        width: 100%;
        max-height: 500px;
        overflow: auto;
    }
</style>