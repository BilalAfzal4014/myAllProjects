<template>
    <div>
        <div class="tab" id="target_tab">
            <div class="inner_tab_widget">
                <div class="heading_holder">
                    <h2>Targeting Options</h2>
                </div>
                <div class="txt_holder">
                    <div class="row">
                        <strong>Target users by choosing multiple segments they must fall into. Further refine your
                            audience
                            by adding additional filters.</strong>
                        <h5>Target Users By Segment</h5>
                        <div class="append_divs t_margin">
                            <div class="loop_elem" v-for="(segment, index) in segmentObj.totalSelectedSegments">
                                <div class="append_div">
                                    <div style="float: left; width: 85%">
                                        <input disabled v-bind:value="segment.label"/>
                                    </div>
                                    <a v-on:click="removeSegment(index)" class="anchor_pointer row_remover"><i
                                            class="far fa-trash-alt"></i></a>
                                </div>
                                <span v-if="index != segmentObj.totalSelectedSegments.length - 1">AND</span>
                            </div>
                        </div>
                        <vSelect placeholder="Search Segment(s)" v-bind:options="segment"
                                 v-model="selectedSegment"
                                 v-bind:filterable="false"
                                 v-on:search="onSearch">
                        </vSelect>
                        <p class="error">{{errors.totalSegments.message}}</p>
                    </div>
                </div>
            </div>
            <div class="inner_tab_widget">
                <div class="heading_holder">
                    <h2>Rate Limiting</h2>
                </div>
                <div class="txt_holder">
                    <div class="row">
                        <input type="checkbox" v-model="segmentObj.rateLimit.enable" id="allow_rate_limit">
                        <label for="allow_rate_limit"><i aria-hidden="true" class="fa fa-check-square"></i> Enable rate limiting</label>
                    </div>
                    <div v-show="segmentObj.rateLimit.enable" class="row big_inputs">
                        <b>Every</b>
                        <input type="number" min="1" placeholder="1" v-model="segmentObj.rateLimit.campaignRateLimit">
                        <select class="fix_width" v-model="segmentObj.rateLimit.durationUnit">
                            <option v-for="unit in rateLimitUnit" v-bind:value="unit">{{unit.charAt(0).toUpperCase() +
                                unit.slice(1)}}
                            </option>
                        </select>
                        <select v-model="segmentObj.rateLimit.durationValue">
                            <option v-for="value in rateLimitDurationValue" v-bind:value="value">{{value}}</option>
                        </select>
                        <strong>Users</strong>
                        <p class="error">{{errors.rateLimit.campaignRateLimit.message}}</p>
                    </div>
                </div>
            </div>
            <div class="inner_tab_widget">
                <div class="heading_holder">
                    <h2>Distribution</h2>
                </div>
                <div class="txt_holder">
                    <div class="row">
                        <div class="color_bar">
                            <div v-for="distribution in segmentObj.variantsDistribution.distribution"
                                 class="color_col variant_1"
                                 v-bind:style="'width:' + distribution.value + '%; background:'  + distribution.color">
                                {{distribution.value}} %
                            </div>
                        </div>
                    </div>
                    <div class="row big_inputs variant_inputs">
                        <div style="display: inline-block"
                             v-for="(distribution, index) in segmentObj.variantsDistribution.distribution"
                             v-bind:class="index > 0 ? 'margin_class': ''">
                            <b v-bind:style="'color:' + distribution.color">Variant {{index + 1}}</b>
                            <input type="number" v-model="distribution.value" placeholder="25">
                        </div>
                        <p class="error">{{errors.variantsDistribution.message}}</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="tab sec_hidden">
            <div class="inner_tab_widget">
                <div class="heading_holder">
                    <h2>Targeting Options</h2>
                </div>
                <div class="txt_holder">
                    <div class="row">
                        <strong>Target users by choosing multiple segments they must fall
                            into.
                            Further refine your audience by adding additional
                            filters.</strong>
                        <h5>Target Users By Segment</h5>
                        <div class="row">
                            <select class="fluid add">
                                <option>Select Segment</option>
                                <option>Segment 1</option>
                                <option>Segment 2</option>
                                <option>Segment 3</option>
                                <option>Segment 4</option>
                            </select>
                        </div>
                        <div class="append_divs t_margin">
                            <div class="append_div">
                                <select class="add">
                                    <option>Choose Actions...</option>
                                    <option>App Launch</option>
                                    <option>Help</option>
                                    <option>Savings</option>
                                    <option>Tutorials</option>
                                </select>
                                <select class="add">
                                    <option>Choose Actions...</option>
                                    <option>App Launch</option>
                                    <option>Help</option>
                                    <option>Savings</option>
                                    <option>Tutorials</option>
                                </select>
                                <a href="#" class="row_remover"><i class="far fa-trash-alt"></i></a>
                            </div>
                            <div class="extra_checks"><span class="misc_check active">AND</span></div>
                            <div class="append_div">
                                <select class="add">
                                    <option>Choose Actions...</option>
                                    <option>App Launch</option>
                                    <option>Help</option>
                                    <option>Savings</option>
                                    <option>Tutorials</option>
                                </select>
                                <select class="add">
                                    <option>Choose Actions...</option>
                                    <option>App Launch</option>
                                    <option>Help</option>
                                    <option>Savings</option>
                                    <option>Tutorials</option>
                                </select>
                                <a href="#" class="row_remover"><i class="far fa-trash-alt"></i></a>
                            </div>
                            <div class="extra_checks"><span class="misc_check active">AND</span></div>
                            <div class="append_div">
                                <select class="add">
                                    <option>Choose Actions...</option>
                                    <option>App Launch</option>
                                    <option>Help</option>
                                    <option>Savings</option>
                                    <option>Tutorials</option>
                                </select>
                                <select class="add">
                                    <option>Choose Actions...</option>
                                    <option>App Launch</option>
                                    <option>Help</option>
                                    <option>Savings</option>
                                    <option>Tutorials</option>
                                </select>
                                <a href="#" class="row_remover"><i class="far fa-trash-alt"></i></a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="inner_tab_widget">
                <div class="heading_holder">
                    <h2>Additional Filters</h2>
                </div>
                <div class="txt_holder">
                    <div class="append_divs t_margin">
                        <div class="append_div">
                            <select class="fluid add">
                                <option>Choose Actions...</option>
                                <option>App Launch</option>
                                <option>Help</option>
                                <option>Savings</option>
                                <option>Tutorials</option>
                            </select>
                            <a href="#" class="row_remover"><i class="far fa-trash-alt"></i></a>
                        </div>
                        <div class="extra_checks">
                            <span class="misc_check active">AND</span>
                            <span class="or_span misc_check">OR</span>
                        </div>
                        <div class="append_div">
                            <select class="fluid add">
                                <option>Search Filters...</option>
                                <option>App Launch</option>
                                <option>Help</option>
                                <option>Savings</option>
                                <option>Tutorials</option>
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <strong><b>Audience Summary:</b> "UK Launch 2018 EN' {Product SKU is
                            includes value D17DBFM, Product SKU doesn't include value
                            D17ADAD,
                            Product SKU doesn't include value D17DBFD, and language is not
                            Arabic} AND Has redeemed more than 4 times.</strong>
                    </div>
                    <div class="row">
                        <input type="checkbox" id="limit_people">
                        <label for="limit_people"><i class="far fa-square"></i> Limit the
                            number
                            of people who will receive this in-app message</label>
                    </div>
                </div>
            </div>
            <div class="inner_tab_widget">
                <div class="heading_holder">
                    <h2>Rate Limiting</h2>
                </div>
                <div class="txt_holder">
                    <div class="row">
                        <input checked="" type="checkbox" id="allow_rate_limit">
                        <label for="allow_rate_limit"><i class="far fa-square"></i> Enable
                            rate
                            limiting</label>
                    </div>
                    <div class="row big_inputs">
                        <b>Every</b>
                        <input type="text" placeholder="1">
                        <select class="fix_width">
                            <option>Minute</option>
                            <option>Day</option>
                            <option>Week</option>
                            <option>Month</option>
                        </select>
                        <select>
                            <option>100</option>
                            <option>500</option>
                            <option>1000</option>
                        </select>
                        <strong>Users</strong>
                    </div>
                </div>
            </div>
            <div class="inner_tab_widget">
                <div class="heading_holder">
                    <h2>Distribute Users Among Variants
                        <div class="align_right"><strong class="int_strong">Intelligent
                            Selection</strong>
                            <div class="on_off_div">
                                <div class="an-onoffswitchContainer"><input type="checkbox"
                                                                            class="an-switch an-onoffswitch-checkbox"
                                                                            name="check1" id="an-onoffswitch0"><label
                                        class="an-onoffswitch-label" for="an-onoffswitch0"><span
                                        class="an-onoffswitch-inner"></span><span class="an-onoffswitch-switch"></span></label>
                                </div>
                            </div>
                        </div>
                    </h2>
                </div>
                <div class="txt_holder">
                    <div class="row">
                        <p class="notice_bar">Control group and Intelligent Selection are
                            disabled for this Campaign because users are able to receive
                            this
                            Campaign more than once. this is done in rder to preserve the
                            integrity of any historical control group you might have. Any
                            users
                            who were previously enrolled in the control will never receive a
                            new
                            message from this Campaign.</p>
                    </div>
                    <div class="row">
                        <strong class="fluid">Choose how to distribute message to your target audience. This an be
                            changed later by editing the Campaign.</strong>
                        <div class="color_bar">
                            <div class="color_col variant_1" style="width: 25%;background: #2a8689;">25 %
                            </div>
                            <div class="color_col variant_2" style="width: 25%;background: #7abbbd;">25 %
                            </div>
                            <div class="color_col variant_3" style="width: 25%;background: #5b8688;">25 %
                            </div>
                            <div class="color_col variant_3" style="width: 25%;background: #ffbdd2;">25 %
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <strong><b>CG</b>Control Group 0%</strong>
                    </div>
                    <div class="row big_inputs variant_inputs">
                        <b style="color:#2a8689;">Variant 1: 100 %</b>
                        <input type="number" placeholder="100 %" min="0" max="100">
                    </div>
                    <div class="row">
                        <strong><i class="far fa-chart-bar"></i> Distribute Evenly (without
                            Control Group)</strong>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import vSelect from 'vue-select';

    export default {
        props: ['hitApi', 'campaignId', 'getCampaignStep', 'totalVariants'],
        components: {
            vSelect,
        },
        data() {
            return {
                locked: true,
                rateLimitUnit: [
                    "minutes",
                    "hours",
                    "days",
                    "weeks",
                ],
                rateLimitDurationValue: [
                    100,
                    500,
                    1000
                ],
                segment: [],
                selectedSegment: {},
                segmentObj: {
                    totalSelectedSegments: [],
                    rateLimit: {
                        enable: true,
                        campaignRateLimit: 1,
                        durationValue: 100,
                        durationUnit: 'minutes',
                    },
                    variantsDistribution: {
                        distribution: []
                    }
                },
                errors: {
                    totalSegments: {
                        message: '',
                    },
                    rateLimit: {
                        campaignRateLimit: {
                            message: ''
                        }
                    },
                    variantsDistribution: {
                        message: ''
                    }
                }
            };
        },
        methods: {
            onSearch(search, loading) {
                loading(true);
                const payLoad = {
                    resource: "company/presets/segments/" + search,
                    action: "get",
                };
                this.api("post",
                    this.constants.getUrl("backendApiUrl"),
                    payLoad,
                    this.authHeaders
                ).then((response) => {
                    loading(false);
                    this.segment = response.data.data;
                }).catch((error) => {

                });

            },
            removeSegment(index) {
                this.segmentObj.totalSelectedSegments.splice(index, 1);
            },
            submitStep() {
                this.locked = false;

                if (this.validate()) {

                    this.bus.$emit("disableNextBtn", true);
                    let segmentsArr = [];
                    for (var i = 0; i < this.segmentObj.totalSelectedSegments.length; i++) {
                        segmentsArr.push(this.segmentObj.totalSelectedSegments[i].value);
                    }

                    const payLoad = {
                        resource: "campaigns",
                        action: "update",
                        id: this.campaignId,
                        data: {
                            step: "target",
                            targetObj: {
                                segments: segmentsArr,
                                rateLimit: this.segmentObj.rateLimit,
                                variantDistribution: this.segmentObj.variantsDistribution
                            }
                        }
                    };

                    this.api("post",
                        this.constants.getUrl("backendApiUrl"),
                        payLoad,
                        this.authHeaders,
                        true,
                    ).then((response) => {
                        this.bus.$emit("disableNextBtn", false);
                        if (response.data.meta.code === 200) {
                            this.updateRelevantStep(response.data.data.reachableUsers);
                            this.$emit("goNext");
                        }
                    }).catch((error) => {

                    });
                }
            },
            updateRelevantStep(totalUsers) {
                this.bus.$emit("targetUsers", totalUsers);
            },
            validate() {
                let success = true;
                let errors = {
                    totalSegments: {
                        message: '',
                    },
                    rateLimit: {
                        campaignRateLimit: {
                            message: ''
                        }
                    },
                    variantsDistribution: {
                        message: ''
                    }
                };
                this.errors = JSON.parse(JSON.stringify(errors));

                if (this.segmentObj.totalSelectedSegments.length == 0) {
                    success = false;
                    this.errors.totalSegments.message = "Please select segment(s)";
                }

                if (this.segmentObj.rateLimit.enable) {
                    let expression = /^[1-9][0-9]*$/gi;
                    if (!expression.test(this.segmentObj.rateLimit.campaignRateLimit)) {
                        success = false;
                        this.errors.rateLimit.campaignRateLimit.message = 'Rate Limit value can only be a postive integer';
                    }
                }

                let sum = 0;
                for (let i = 0; i < this.segmentObj.variantsDistribution.distribution.length; i++) {
                    sum += parseInt(this.segmentObj.variantsDistribution.distribution[i].value);
                }

                if (sum != 100) {
                    success = false;
                    this.errors.variantsDistribution.message = 'Distribution sum must be 100';
                }
                return success;
            }
        },
        watch: {
            selectedSegment: {
                handler(currVal, oldVal) {
                    if (JSON.stringify(currVal) != "{}") {
                        for (var i = 0; i < this.segmentObj.totalSelectedSegments.length; i++) {
                            if (JSON.stringify(this.segmentObj.totalSelectedSegments[i]) == JSON.stringify(currVal)) {
                                break;
                            }
                        }

                        if (i >= this.segmentObj.totalSelectedSegments.length && currVal != null && currVal != {}) {
                            this.segmentObj.totalSelectedSegments.push(currVal);
                        }
                        this.selectedSegment = {};
                    }

                },
                deep: true
            },
            hitApi: {
                handler(currVal, oldVal) {
                    if (currVal.step == 4) {
                        this.submitStep();
                    }
                },
                deep: true
            },
            getCampaignStep: {
                handler(currVal, oldVal) {
                    if (JSON.stringify(currVal) != "{}") {
                        this.segmentObj = currVal;
                        this.updateRelevantStep(this.segmentObj.reachableUsers);
                    }
                },
                deep: true
            },
            totalVariants: {
                handler(currVal, oldVal) {
                    this.segmentObj.variantsDistribution.distribution = JSON.parse(JSON.stringify(currVal));
                },
                deep: true
            },
            segmentObj: {
                handler(currVal, oldVal) {
                    if (!this.locked) {
                        this.validate();
                    }
                },
                deep: true
            }
        }
    }

</script>

<style scoped>
    .append_div input {
        background: none !important;
        border: none !important;
    }

    .margin_class {
        margin-left: 15px;
    }

    .loop_elem {
        margin: 0 0 40px 0px;
    }
</style>