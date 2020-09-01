<template>
    <div class="page_content_holder">
        <div class="padding_content">
            <div class="white_boxed_content">
                <header class="sec_header">
                    <h1>News Feed <i> > </i> {{name}}</h1>
                </header>
                <div class="list_holder inner_padding full_scroll">
                    <div v-if="!isActive" false class="draft_msg">
                        <strong>Saved Draft - Not Publishded</strong>
                        <p>Once your campaign has been launched. It will begin sending messages to your users and will
                            no longer be listed as a draft.</p>
                        <span>Last Edited. April 26, 2018 by Jawad Ashraf</span>
                    </div>
                    <ul class="tabs list_none inline_list newsfeed_edit">
                        <li v-bind:class="step == 1 ? 'active' : ''"><a class="anchor_pointer">1. Compose &amp;
                            Schedule</a></li>
                        <li v-bind:class="step == 2 ? 'active' : ''"><a class="anchor_pointer">2. Delivery</a></li>
                        <li v-bind:class="step == 3 ? 'active' : ''"><a class="anchor_pointer">3. Confirm</a></li>
                    </ul>
                    <div id="tab-content">
                        <step1 v-bind:getNewsFeedStep="getNewsFeedSteps[0]"
                               v-bind:hitApi="hitApi"
                               v-bind:newsFeedId="newsFeedId"
                               v-bind:step1Prop="newsFeedObj.step1"
                               v-on:changeName="updateName"
                               v-on:goNext="oneStepNext"
                               v-on:isStatusActive="makeActive"
                               v-show="stepComponent == 'step1'"
                        ></step1>
                        <step2 v-bind:getNewsFeedStep="getNewsFeedSteps[1]"
                               v-bind:hitApi="hitApi"
                               v-bind:newsFeedId="newsFeedId"
                               v-bind:step2Prop="newsFeedObj.step2"
                               v-on:goNext="oneStepNext"
                               v-show="stepComponent == 'step2'"
                        ></step2>
                        <step3 v-bind:hitApi="hitApi"
                               v-bind:newsFeedId="newsFeedId"
                               v-on:changeStep="navigateStep"
                               v-show="stepComponent == 'step3'"
                        ></step3>
                    </div>
                </div>
                <div class="buttons_strip">
                    <button class="back" v-if="step != 1" v-on:click="back">Back</button>
                    <button class="next right" v-if="step != 3" v-on:click="next">Next</button>
                    <div class="next" v-if="step == 3">
                        <button v-if="!isActive" class="right" v-on:click="launchNewsFeed(true)">Launch NewsFeed
                        </button>
                        <button class="right" v-on:click="launchNewsFeed(false)">{{!isActive ? 'Draft and Close': 'Close'}}</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import step1 from './NewsFeedCreationSteps/step1';
    import step2 from './NewsFeedCreationSteps/step2';
    import step3 from './NewsFeedCreationSteps/step3';

    export default {
        components: {
            step1,
            step2,
            step3
        },
        data() {
            return {
                isActive: false,
                newsFeedId: -1,
                name: '',
                stepComponent: 'step',
                step: 1,
                newsFeedObj: {},
                getNewsFeedSteps: [{}, {}],
                hitApi: {
                    step: 1,
                    token: false
                },
            };
        },
        mounted() {
            this.stepComponent = 'step' + this.step;
            this.getPreData();
        },
        beforeDestroy() {
            $("#footer").css({display: "block"});
        },
        methods: {
            getPreData() {
                $("#footer").css({display: "none"});
                const payLoad = {
                    resource: "company/presets/newsfeed",
                    action: "get",
                };

                this.api("post",
                    this.constants.getUrl("backendApiUrl"),
                    payLoad,
                    this.authHeaders,
                    true,
                ).then((response) => {
                    this.newsFeedObj = response.data.data;
                    if (this.$route.params.id != null) {
                        this.getNewsFeed(this.$route.params.id);
                    }
                }).catch((error) => {

                });
            },
            updateName(name) {
                this.name = name;
            },
            back() {
                this.step--;
                this.stepComponent = 'step' + this.step;
            },
            next() {
                this.hitApi.step = this.step;
                this.hitApi.token = !this.hitApi.token;
            },
            oneStepNext(id) {

                if (id != undefined) {
                    this.newsFeedId = id;
                }

                this.step++;
                this.stepComponent = "step" + this.step;
            },
            getNewsFeed(id) {
                const payLoad = {
                    "resource": "newsfeeds",
                    "action": "get",
                    "id": id
                };

                this.api("post",
                    this.constants.getUrl("backendApiUrl"),
                    payLoad,
                    this.authHeaders,
                    true,
                ).then((response) => {

                    if (response.data.data.status != undefined && !response.data.data.status) {
                        this.$router.push("/dashboard/newsFeed-Listing");
                    } else {
                        let currentLastStep = response.data.data.length;
                        this.newsFeedId = id;
                        this.getNewsFeedSteps = response.data.data;

                        for (var i = this.getNewsFeedSteps.length; i < 2; i++) {
                            this.getNewsFeedSteps.push({});

                        }
                        this.step = currentLastStep == 3 ? currentLastStep : (currentLastStep + 1);
                        this.stepComponent = "step" + this.step;
                    }

                }).catch((error) => {

                });
            },
            launchNewsFeed(token) {
                if (!token) {
                    this.$router.push("/dashboard/newsFeed-Listing");
                } else {
                    this.$swal({
                        position: 'center',
                        type: 'info',
                        title: "Are you sure ?",
                        text: "You want to launch NewsFeed",
                        /*text: "once you launch a NewsFeed you cannot edit it",*/
                        showConfirmButton: true,
                        showCancelButton: true,
                    }).then((willDelete) => {
                        if (willDelete.value != undefined && willDelete.value) {
                            this.hitApi.step = this.step;
                            this.hitApi.token = !this.hitApi.token;
                        }
                    });
                }
            },
            navigateStep(stepNo) {
                this.step = stepNo;
                this.stepComponent = "step" + this.step;
            },
            makeActive(data) {
                this.isActive = data;
            }
        },
        watch: {
            '$route.params.id'(currVal, oldVal) {
                this.getNewsFeed(currVal);
            },
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

    .reachable_users p {
        margin: 0px;
    }

</style>