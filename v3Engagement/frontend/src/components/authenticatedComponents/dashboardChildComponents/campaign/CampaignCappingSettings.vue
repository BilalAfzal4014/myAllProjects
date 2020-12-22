<template>
    <div>
        <div class="page_content_holder">
            <div class="padding_content">
                <div class="white_boxed_content lists_page">
                    <div class="list_holder inner_padding full_scroll">
                        <div id="tab-content" class="center_block">
                            <div class="tab active custom_form no_border" id="general_tab">
                                <h2 class="section_heading b_m_20">Capping Rules</h2>
                                <strong style="color: #666;">Send This Campaign To Users Who</strong>
                                <div class="inner_tab_text active" id="action-based">
                                    <div class="append_divs">

                                        <div v-for="(capRule, index) in capRulesArr">
                                            <div class="append_div multiples" style="background: #f1f1f1; border-radius:4px; overflow:hidden;">
                                                <strong>Send no more than</strong>
                                                <input type="number" min="1" v-model="capRule.capLimit"
                                                       placeholder="0"
                                                       class="small">

                                                <select v-model="capRule.campaignType">
                                                    <option v-for="type in campaignType">{{type}}</option>
                                                </select>

                                                <strong>to a user every</strong>
                                                <input type="number" min="1" v-model="capRule.durationValue"
                                                       placeholder="0"
                                                       class="small">

                                                <select v-model="capRule.durationUnit">
                                                    <option v-for="unit in durationUnit">{{unit}}</option>
                                                </select>

                                                <a class="anchor_pointer row_remover"><i
                                                        class="far fa-trash-alt" v-on:click="deleteRule(index)"></i></a>
                                            </div>
                                            <div v-if="index != (capRulesArr.length - 1)" class="extra_checks"><span
                                                    class="misc_check active">AND</span></div>
                                        </div>

                                    </div>
                                    <header class="inner_tab_header">
                                        <select class="fluid add" v-model="addRule" v-on:change="addRuleFun">
                                            <option v-for="rule in addRuleValues">{{rule}}</option>
                                        </select>
                                    </header>
                                </div>
                            </div>
                            <div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="buttons_strip">
            <button class="right" v-on:click="saveRules">Save</button>
        </div>
    </div>
</template>

<script>
    export default {
        name: "CampaignCappingSettings",
        data() {
            return {
                locked: true,
                campaignType: [
                    'Push',
                    'InApp',
                    'Email'
                ],
                durationUnit: [
                    'minutes',
                    'days',
                    'weeks'
                ],
                addRuleValues: [
                    'Declare Rules...',
                    'Add Rule'
                ],
                addRule: 'Declare Rules...',
                capRulesArr: [],
                errorsArr: []
            };
        },
        mounted() {
            $("#footer").css({display: "none"});
            this.getCappingRules();
        },
        beforeDestroy() {
            $("#footer").css({display: "block"});
        },
        methods: {
            addRuleFun() {
                if (this.capRulesArr.length < this.campaignType.length) {
                    let obj = {
                        capLimit: 1,
                        campaignType: this.campaignType[0],
                        durationValue: 1,
                        durationUnit: this.durationUnit[0],
                    };

                    let error = {
                        capLimitError: '',
                        durationValueError: '',
                    };

                    this.capRulesArr.push(obj);
                    this.errorsArr.push(error);
                }
                this.addRule = 'Declare Rules...';
            },
            deleteRule(index) {
                this.capRulesArr.splice(index, 1);
                this.errorsArr.splice(index, 1);
            },
            saveRules() {
                this.locked = false;
                if (this.validate()) {

                    this.removeDuplicateRules();

                    const payLoad = {
                        resource: "campaign/capping-save",
                        action: "post",
                        data: this.capRulesArr
                    };

                    this.api("post",
                        this.constants.getUrl("backendApiUrl"),
                        payLoad,
                        this.authHeaders,
                        true,
                    ).then((reponse) => {
                        this.$swal({
                            position: 'top-end',
                            type: 'success',
                            title: 'Capping rule(s) saved successfully',
                            showConfirmButton: false,
                            timer: 1500
                        })
                    }).catch((error) => {

                    });
                }
            },
            validate() {

                let success = true;
                for (let i = 0; i < this.capRulesArr.length; i++) {
                    let expression = /^[1-9][0-9]*$/gi;

                    if (!expression.test(this.capRulesArr[i].capLimit)) {
                        success = false;
                        this.errorsArr[i].capLimitError = 'Cap Limit can only be positive integer';
                    } else {
                        this.errorsArr[i].capLimitError = '';
                    }

                    expression = /^[1-9][0-9]*$/gi;
                    if (!expression.test(this.capRulesArr[i].durationValue)) {
                        success = false;
                        this.errorsArr[i].durationValueError = 'Duration value can only be positive integer';
                    } else {
                        this.errorsArr[i].durationValueError = '';
                    }
                }
                return success;
            },
            getCappingRules() {
                const payLoad = {
                    resource: "campaign/capping-settings",
                    action: "get",
                };

                this.api("post",
                    this.constants.getUrl("backendApiUrl"),
                    payLoad,
                    this.authHeaders,
                    true,
                ).then((response) => {

                    for (let i = 0; i < response.data.data.length; i++) {
                        this.errorsArr.push({
                            capLimitError: '',
                            durationValueError: '',
                        });
                    }

                    this.capRulesArr = response.data.data;
                }).catch((error) => {

                })
            },
            removeDuplicateRules() {
                for (let i = 0; i < this.capRulesArr.length; i++) {
                    for (let j = i + 1; j < this.capRulesArr.length; j++) {
                        if (this.capRulesArr[i].campaignType == this.capRulesArr[j].campaignType) {
                            this.capRulesArr.splice(j, 1);
                            this.errorsArr.splice(j, 1);
                            j--;
                        }
                    }
                }
            }
        },
        watch: {
            capRulesArr: {
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
    .inlineb {
        display: inline-block;
    }
</style>