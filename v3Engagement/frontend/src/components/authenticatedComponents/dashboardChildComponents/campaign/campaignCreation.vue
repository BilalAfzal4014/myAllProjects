<template>
    <div>
        <div class="page_content_holder">
            <div class="padding_content">
                <div class="white_boxed_content">
                    <header class="sec_header">
                        <h1>Campaign <i> > </i> {{campaignName}}</h1>
                    </header>
                    <div class="list_holder inner_padding full_scroll">
                        <!--v-if="$route.params.action != 'view'"-->
                        <div v-if="getCampaignSteps[0].status === 'draft'" class="draft_msg">
                            <strong>Saved Draft - Not Publishded</strong>
                            <p>Once your campaign has been launched. It will begin sending messages to your users and
                                will no longer be listed as a draft.</p>
                            <span>Last Edited. {{this.getLastModifiedDate(getCampaignSteps[0].updated_at)}} by {{userObj.name}}</span>
                        </div>
                        <ul class="tabs list_none inline_list">
                            <li v-bind:class="step == 1 ? 'active': ''"><a class="anchor_pointer"
                                                                           data-href="#general_tab">1. General</a>
                            </li>
                            <li v-bind:class="step == 2 ? 'active': ''"><a
                                    class="anchor_pointer"
                                    data-href="compose_tab">2.
                                Compose</a></li>
                            <li v-bind:class="step == 3 ? 'active': ''"><a class="anchor_pointer"
                                                                           data-href="delivery_tab">3.
                                Delivery</a></li>
                            <li v-bind:class="step == 4 ? 'active': ''"><a class="anchor_pointer"
                                                                           data-href="target_tab">4.
                                Target
                                Users</a></li>
                            <li v-bind:class="step == 5 ? 'active': ''"><a class="anchor_pointer"
                                                                           data-href="conversions_tab">5.
                                Conversions</a></li>
                            <li v-bind:class="step == 6 ? 'active': ''"><a class="anchor_pointer"
                                                                           data-href="confirm_tab">6.
                                Confirm</a></li>
                        </ul>
                        <div id="tab-content">
                            <step1 v-show="stepComponent == 'step1'"
                                   v-bind:campaignTemplates="campaignObj.templates"
                                   v-bind:campaignTypes="campaignObj.campaignTypes"
                                   v-bind:hitApi="hitApi"
                                   v-bind:campaignId="campaignId"
                                   v-bind:getCampaignStep="getCampaignSteps[0]"
                                   v-on:campaignChangeEvent="sendCampaignType"
                                   v-on:changeName="updateName"
                                   v-on:goNext="oneStepNext"
                                   v-on:emitCode="getCode"
                            ></step1>
                            <step2 v-show="stepComponent == 'step2'"
                                   v-bind:campaignType="campaignType"
                                   v-bind:languages="campaignObj.languages"
                                   v-bind:campaignTemplates="campaignObj.templates"
                                   v-bind:inAppPushData="campaignObj.inAppPushData"
                                   v-bind:hitApi="hitApi"
                                   v-bind:campaignId="campaignId"
                                   v-bind:getCampaignStep="getCampaignSteps[1]"
                                   v-on:goNext="oneStepNext"
                                   v-on:totalVariants="getTotalVariants"
                            ></step2>
                            <step3 v-show="stepComponent == 'step3'"
                                   v-bind:campaignAction="campaignObj.campaignAction"
                                   v-bind:hitApi="hitApi"
                                   v-bind:campaignId="campaignId"
                                   v-bind:getCampaignStep="getCampaignSteps[2]"
                                   v-bind:getCode="campaignCode"
                                   v-on:goNext="oneStepNext"
                            ></step3>
                            <step4 v-show="stepComponent == 'step4'"
                                   v-bind:hitApi="hitApi"
                                   v-bind:campaignId="campaignId"
                                   v-bind:getCampaignStep="getCampaignSteps[3]"
                                   v-bind:totalVariants="totalVariants"
                                   v-on:goNext="oneStepNext"></step4>
                            <step5 v-show="stepComponent == 'step5'"
                                   v-bind:campaignConversion="campaignObj.campaignConversion"
                                   v-bind:campaignApps="campaignObj.campaignApps"
                                   v-bind:hitApi="hitApi"
                                   v-bind:campaignId="campaignId"
                                   v-bind:getCampaignStep="getCampaignSteps[4]"
                                   v-on:goNext="oneStepNext"
                            ></step5>
                            <step6 v-show="stepComponent == 'step6'"
                                   v-on:changeStep="navigateStep"
                                   v-bind:hitApi="hitApi"
                                   v-bind:campaignId="campaignId"></step6>
                        </div>
                    </div>
                    <div class="campaign_footer_outer">
                        <div class="campaign_footer_holder">

                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="buttons_strip">
            <button v-on:click="back" class="back" v-if="step != 1">Back</button>

            <button v-bind:disabled="disableNextButton" v-on:click="next" class="next right" v-if="step != 6">Next
            </button>
            <div class="next right" v-if="step == 6">
                <button v-if="$route.params.action != 'view'" class="right" v-on:click="launchCampaign(true)">Launch
                    Campaign
                </button>
                <button class="right" v-on:click="launchCampaign(false)">{{$route.params.action != 'view' ? 'Draft and':
                    ''}} Close
                </button>
            </div>
            <div v-show="totalUsers != -1" class="reachable_users">
                <p>Reachable user(s)</p>
                <span>{{totalUsers}}</span>
            </div>
        </div>
    </div>
</template>

<script>
    import step1 from './campaignCreationSteps/step1';
    import step2 from './campaignCreationSteps/step2';
    import step3 from './campaignCreationSteps/step3';
    import step4 from './campaignCreationSteps/step4';
    import step5 from './campaignCreationSteps/step5';
    import step6 from './campaignCreationSteps/step6';

    export default {

        components: {
            step1,
            step2,
            step3,
            step4,
            step5,
            step6,
        },
        data() {
            return {
                totalUsers: -1,
                campaignName: "{Campaign Title}",
                stepComponent: "step",
                step: 1,
                hitApi: {
                    step: 1,
                    token: false
                },
                campaignObj: {},
                campaignType: {},
                campaignId: -1,
                action: null,
                getCampaignSteps: [{}, {}, {}, {}, {}, {}],
                campaignCode: "",
                totalVariants: [],
                userObj: {},
                disableNextButton: false,
            }
        },
        mounted() {
            $("#footer").css({display: "none"});
            this.stepComponent = "step" + this.step;

            this.bus.$on("disableNextBtn", (data) => {
                this.disableNextButton = data;
            });

            this.getPreData();
            this.reachableUsers();
            this.getUser()
        },
        beforeDestroy() {
            $("#footer").css({display: "block"});
            this.bus.$off("targetUsers");
        },
        methods: {
            reachableUsers() {
                this.bus.$on("targetUsers", (data) => {
                    this.totalUsers = data;
                });
            },
            shiftTabs(step) {
                this.stepComponent = step;
            },
            getPreData() {
                const payLoad = {
                    resource: "company/presets/campaign",
                    action: "get",
                };

                this.api("post",
                    this.constants.getUrl("backendApiUrl"),
                    payLoad,
                    this.authHeaders,
                    true,
                ).then((response) => {
                    this.campaignObj = response.data.data;
                    if (this.$route.params.id != null) {
                        this.getCampaign(this.$route.params.id);
                    }
                }).catch((error) => {

                });
            },
            sendCampaignType(obj) {
                this.campaignType = obj;
            },
            updateName(name) {
                this.campaignName = name;
            },
            back() {
                this.step--;
                this.stepComponent = "step" + this.step;
            },
            next() {
                if (this.$route.params.action == "view") {
                    this.step++;
                    this.stepComponent = "step" + this.step;
                } else {
                    this.hitApi.step = this.step;
                    this.hitApi.token = !this.hitApi.token;
                }
            },
            oneStepNext(id) {

                if (id != undefined) {
                    this.campaignId = id;
                }

                this.step++;
                this.stepComponent = "step" + this.step;
            },
            getCampaign(id) {
                const payLoad = {
                    "resource": "campaigns",
                    "action": "get",
                    "id": id
                };

                this.api("post",
                    this.constants.getUrl("backendApiUrl"),
                    payLoad,
                    this.authHeaders,
                    true,
                ).then((response) => {
                    if (response.data.meta.code === 200) {
                        if (response.data.data.status != undefined && !response.data.data.status) {
                            this.$router.push("/dashboard/campaign-listing");
                        } else {
                            console.log('campaign', response.data.data)
                            let currentLastStep = response.data.data.length;
                            this.campaignId = this.$route.params.id;
                            this.getCampaignSteps = response.data.data;
                            if ((this.getCampaignSteps[0].status != "draft" && this.$route.params.action.toLowerCase() != "view") || (this.getCampaignSteps[0].status == "draft" && this.$route.params.action.toLowerCase() != "edit")) {
                                this.$router.push("/dashboard/campaign-listing");
                            }

                            for (var i = this.getCampaignSteps.length; i < 6; i++) {
                                this.getCampaignSteps.push({});

                            }
                            this.step = currentLastStep == 6 ? currentLastStep : (currentLastStep + 1);
                            this.stepComponent = "step" + this.step;
                        }
                    }
                }).catch((error) => {

                });
            },
            getCode(code) {
                this.campaignCode = code;
            },
            getTotalVariants(totalVariants) {
                this.totalVariants = totalVariants;
            },
            navigateStep(stepNo) {
                this.step = stepNo;
                this.stepComponent = "step" + this.step;
            },
            launchCampaign(launch) {
                if (launch) {
                    this.$swal({
                        position: 'center',
                        type: 'info',
                        title: "Are you sure ?",
                        text: "once you launch a campaign you cannot edit it",
                        showConfirmButton: true,
                        showCancelButton: true,
                    }).then((willDelete) => {
                        if (willDelete.value != undefined && willDelete.value) {
                            this.hitApi.step = this.step;
                            this.hitApi.token = !this.hitApi.token;
                        }
                    });
                } else {
                    this.$router.push("/dashboard/campaign-listing");
                }
            },
            getLastModifiedDate(updatedAt) {
                const months = ["January", "February", "MArch", "April", "May", "June", "July", "August", "September", "October", "November", "December"];

                var current_datetime = new Date(updatedAt);
                return (months[current_datetime.getMonth()]) + " " + current_datetime.getDate() + ", " + current_datetime.getFullYear();
            },
            getUser() {
                const payLoad = {
                    resource: "user",
                    action: "get",
                };

                this.api("post",
                    this.constants.getUrl("backendApiUrl"),
                    payLoad,
                    this.authHeaders,
                    true,
                ).then((response) => {
                    this.userObj = response.data
                }).catch((error) => {

                });
            }
        },
        watch: {
            '$route.params.id'(currVal, oldVal) {
                this.getCampaign(currVal);
            },
            '$route.params.action'(currVal, oldVal) {
                this.getCampaign(this.$route.params.id);
            }
        }
    }
</script>

<style scoped>
    .campaign_footer_outer {
        padding-left: 96px;
        left: 0;
        bottom: 0;
        position: fixed;
        width: 100%;
        z-index: 1;
        right: 0;
    }

    .campaign_footer_holder {
        padding: 10px 20px 0;
        background: #f0f0f0;
        overflow: hidden;
        text-align: center;
    }

    .campaign_footer_holder .next {
        float: right;
        outline: none;
    }

    .campaign_footer_holder .back {
        float: left;
        outline: none;
    }

    .list_holder.inner_padding {
        padding: 20px 15px 57px;
    }

    .campaign_footer_holder button {
        margin-top: 1px;
        background: #2a8689;
        color: #fff;
        border: 0;
        padding: 6px 30px;
        text-transform: capitalize;
        border-radius: 30px;
    }

    .reachable_users {
        display: inline-block;
        vertical-align: top;
    }

    .buttons_strip {
        text-align: center;
    }

    .reachable_users p {
        margin: 0px;
    }

</style>