<template>
    <div class="tab" id="confirm_tab">
        <div class="draft_rows sec_hidden">
            <div class="draft_row">
                <div class="right">
                    <a href="#">View Details</a>
                    <span class="draft_status">Not Updated</span>
                </div>
                <strong class="draft_name">Messages</strong>
            </div>
            <div class="draft_row">
                <div class="right">
                    <a href="#">View Details</a>
                    <span class="draft_status">Not Updated</span>
                </div>
                <strong class="draft_name">Delivery</strong>
            </div>
        </div>
        <div class="inner_tab_widget">
            <div class="heading_holder">
                <div class="align_right">
                    <a class="anchor_pointer" v-on:click="updateStep(1)">
                        <i class="fas fa-edit"></i>
                        <em>Updated</em>
                    </a>
                </div>
                <h2>Messages</h2>
            </div>
            <div class="txt_holder">
                <div class="row sec_hidden">
                    <strong><b>Variant 1 Preview</b></strong>
                </div>
                <div class="dialogue card preview" style="background: none;">
                    <div class="languages_holder">
                        <ul class="list_none flags"
                            v-if="launch.step1.totalLang != undefined && launch.step1.totalLang.length > 0">
                            <li class="anchor_pointer"
                                v-for="language in launch.step1.totalLang"
                                v-on:click="shiftLang(language.value)"
                                v-bind:class="launch.step1.langArr[langIndex].language == language.value ? 'active': ''">
                                <img v-bind:src="language.imgUrl" alt="England">
                                <i class="fas fa-times"></i></li>
                        </ul>
                    </div>
                    <div class="table_header_holder choose_platform_cols add sec_hidden">
                        <a class="edit_tag"><i class="fas fa-edit"></i></a>
                        <ul class="variant_list fluid list_none">
                            <li>Variant</li>
                            <li>Variant</li>
                        </ul>
                    </div>
                    <div id="composeNewsfeed" class="preview_panel" v-if="launch.step1.totalLang != undefined"
                         v-html="launch.step1.langArr[langIndex].template">

                    </div>
                </div>
            </div>
        </div>
        <div class="inner_tab_widget sec_hidden">
            <div class="heading_holder">
                <div class="align_right">
                    <a class="anchor_pointer" v-on:click="updateStep(2)">
                        <i class="fas fa-edit"></i>
                        <em>Updated</em>
                    </a>
                </div>
                <h2>Delivery</h2>
            </div>
            <div class="row">
                <strong class="msg_heading">Send Campaign 2 hours after trigger criteria are met, beginning today at
                    5:45 PM.</strong>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        props: ['hitApi', 'newsFeedId'],
        data() {
            return {
                launch: {
                    step1: {},
                },
                langIndex: 0,
            };
        },
        mounted() {
            this.listenForStep1();
        },
        methods: {
            updateStep(stepNo) {
                this.$emit("changeStep", stepNo);
            },
            submitStep() {
                const payLoad = {
                    resource: "newsfeeds",
                    action: "update",
                    id: this.newsFeedId,
                    data: {
                        step: "confirm",
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
                        this.$router.push("/dashboard/newsFeed-Listing");
                    }
                }).catch((error) => {

                });
            },
            listenForStep1() {
                this.bus.$on("step1", (data) => {
                    this.langIndex = 0;
                    this.launch.step1 = JSON.parse(JSON.stringify(data));
                });
            },
            shiftLang(value) {
                let langArr = this.launch.step1.langArr;
                for (let i = 0; i < langArr.length; i++) {
                    if (langArr[i].language == value) {
                        this.langIndex = i;
                        break;
                    }
                }
            },
        },
        watch: {
            hitApi: {
                handler(currVal, oldVal) {
                    if (currVal.step == 3) {
                        this.submitStep();
                    }
                },
                deep: true
            }
        }
    }

</script>

<style scoped>
    #composeNewsfeed {
        padding: 14px 14px !important;
        background: #e2e2e2 !important;
    }

    .flags img, .adjust_height {
        width: 27px;
        height: 17px;
    }

    .flags li.active {
        background: gainsboro;
    }
</style>