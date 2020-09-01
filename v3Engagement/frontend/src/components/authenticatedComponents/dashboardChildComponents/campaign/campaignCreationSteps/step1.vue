<template>
    <div class="tab active" id="general_tab">
        <div class="input_row">
            <input type="text" placeholder="Campaign Title" v-model="campaign.name">
            <p class="error">{{errors.name.message}}</p>
        </div>
        <div class="input_row">
            <select v-bind:disabled="campaignId != -1" class="add" v-model="campaign.selectedCampaignType"
                    v-on:change="changeCampaignType">
                <option v-for="type in campaignTypes" v-bind:value="type">{{type.name}}
                </option>
            </select>
        </div>
        <div class="input_row">
            <input-tag placeholder="Enter Tags" :add-tag-on-blur="true"
                       v-bind:key="campaign.tagsOriginal.length"
                       v-model="campaign.tagsOriginal"></input-tag>
        </div>
        <div class="toggle_div active" v-if="campaign.selectedCampaignType.name == 'Email'">
            <div class="input_row">
                <strong class="temp_title">Emails will be sent from this address</strong>
                <div class="input_cols">
                    <div class="input_col">
                        <div class="holder">
                            <label for="from_email">From</label>
                            <input id="from_email" type="text"
                                   v-model="campaign.fromEmail"
                                   placeholder="(email)">
                            <p class="error">{{errors.fromEmail.message}}</p>
                        </div>
                    </div>
                    <div class="input_col">
                        <div class="holder">
                            <label for="from_name">From</label>
                            <input id="from_name" type="text" placeholder="(name)"
                                   v-model="campaign.fromName">
                            <p class="error">{{errors.fromName.message}}</p>
                        </div>
                    </div>
                    <div class="input_col">
                        <div class="holder">
                            <label for="subject">Subject</label>
                            <input id="subject" type="text" placeholder=""
                                   v-model="campaign.subject">
                            <p class="error">{{errors.subject.message}}</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="templates_sample">
                <strong class="temp_title">Choose Template <!--<a class="anchor_pointer create_temp"><i
                        class="fas fa-plus"></i>
                    OR CODE YOUR OWN</a>--></strong>
                <div v-for="templates in campaignTemplates" v-if="templates.type == 'Email'">
                    <ul v-for="child in templates.children" v-if="child.childType == 'CompanyTemplates'"
                        class="templates_sample inline_list list_none">
                        <li v-for="content in child.content" v-on:click="campaignContent(content)"
                            v-bind:class="JSON.stringify(content) == JSON.stringify(campaign.selectedTemplate)? 'active': ''">
                            <div class="holder">
                                <a class="anchor_pointer"><img v-bind:src="content.fullThumbnail" alt="#"></a>
                            </div>
                        </li>
                    </ul>
                </div>
                <p class="error">{{errors.selectedTemplate.message}}</p>
            </div>
            <div class="saved_temps">
                <strong class="temp_title">Saved Templates</strong>
                <div v-for="templates in campaignTemplates" v-if="templates.type == 'Email'">
                    <ul v-for="child in templates.children" v-if="child.childType == 'CampaignTemplates'"
                        class="list_none">
                        <li v-for="content in child.content"
                            v-bind:class="JSON.stringify(content) == JSON.stringify(campaign.selectedTemplate)? 'active': ''">
                            <a class="anchor_pointer" v-on:click="campaignContent(content)">
                                <i class="far fa-newspaper"></i>
                                {{content.name}}
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import InputTag from 'vue-input-tag';

    export default {
        props: ['campaignTypes', 'campaignTemplates', 'hitApi', 'campaignId', 'getCampaignStep'],
        components: {
            InputTag,
        },
        data() {
            return {
                locked: true,
                action: "create",
                haveSaveFirstStep: false,
                campaign: {
                    name: "",
                    tagsOriginal: [],
                    step: "general",
                    fromEmail: "",
                    fromName: "",
                    subject: "",
                    selectedCampaignType: {},
                    selectedTemplate: {},
                },
                errors: {
                    name: {
                        message: ''
                    },
                    fromEmail: {
                        message: ''
                    },
                    fromName: {
                        message: ''
                    },
                    subject: {
                        message: ''
                    },
                    selectedTemplate: {
                        message: ''
                    }
                },
                blockGetCampaignStep: false
            };
        },
        mounted() {
        },
        methods: {
            campaignContent(template) {
                if (this.haveSaveFirstStep) {
                    this.$swal({
                        position: 'center',
                        type: 'info',
                        title: "Are you sure ?",
                        text: "Your previous changes to template will be overwritten, Once you save this step",
                        showConfirmButton: true,
                        showCancelButton: true,
                    }).then((willDelete) => {
                        if (willDelete.value != undefined && willDelete.value) {
                            this.haveSaveFirstStep = false;
                            this.blockGetCampaignStep = true;
                            this.campaign.selectedTemplate = template;
                        }
                    });
                } else {
                    this.haveSaveFirstStep = false;
                    this.blockGetCampaignStep = true;
                    this.campaign.selectedTemplate = template;
                }

            },
            changeCampaignType() {
                this.$emit("campaignChangeEvent", this.campaign.selectedCampaignType);
            },
            submitStep() {
                this.locked = false;
                if (this.validate()) {

                    this.bus.$emit("disableNextBtn", true);

                    let payLoad = {
                        resource: "campaigns",
                        action: this.action,
                        data: JSON.parse(JSON.stringify(this.campaign))
                    };

                    if (this.campaignId != -1) {
                        payLoad.id = this.campaignId;
                    }

                    if (payLoad.data.selectedTemplate.content != undefined) {
                        payLoad.data.selectedTemplate.content = btoa(unescape(encodeURIComponent(payLoad.data.selectedTemplate.content)));
                    }

                    this.api("post",
                        this.constants.getUrl("backendApiUrl"),
                        payLoad,
                        this.authHeaders,
                        true,
                    ).then((response) => {
                        this.bus.$emit("disableNextBtn", false);
                        if (response.data.meta.code === 200) {
                            if (response.data.data.status != undefined && !response.data.data.status && response.data.data.dialogueOpen) {
                                this.$swal({
                                    position: 'top-right',
                                    type: 'warning',
                                    title: response.data.data.message,
                                    showConfirmButton: false,
                                    timer: 2500
                                });
                            } else {

                                if (!this.haveSaveFirstStep && this.campaign.selectedCampaignType.code.toLowerCase() == 'email') {
                                    this.bus.$emit("passEmailContent", this.campaign.selectedTemplate.content);
                                    this.campaign.selectedTemplate = {};
                                    this.haveSaveFirstStep = true;
                                }

                                this.bus.$emit("displayCapController", response.data.data.frequencyCapController);
                                this.$emit("emitCode", response.data.data.code);
                                this.$emit("goNext", response.data.data.id);
                                this.updateStep();
                            }
                        }
                    }).catch((error) => {

                    });
                }
            },
            updateStep() {
                this.bus.$emit("step1", {
                    campaignType: this.campaign.selectedCampaignType.code.toLowerCase() == "email" ? "email" : "notEmail",
                    email: this.campaign.fromEmail,
                    name: this.campaign.fromName,
                    subject: this.campaign.subject
                });
            },
            validate() {
                let success = true;

                if (this.campaign.name == '') {
                    success = false;
                    this.errors.name.message = 'Campaign Name is required';
                } else {
                    this.errors.name.message = '';
                }

                if (this.campaign.selectedCampaignType.code.toLowerCase() == "email") {
                    let expression = /[A-Z0-9._%+-]+@[A-Z0-9.-]+\.[A-Z]{2,4}/igm;
                    if (!expression.test(this.campaign.fromEmail)) {
                        success = false;
                        this.errors.fromEmail.message = 'Invalid From Email Address';
                    } else {
                        this.errors.fromEmail.message = '';
                    }

                    if (this.campaign.fromName == '') {
                        success = false;
                        this.errors.fromName.message = 'From Name is required';
                    } else {
                        this.errors.fromName.message = '';
                    }

                    if (this.campaign.subject == '') {
                        success = false;
                        this.errors.subject.message = 'Subject is required';
                    } else {
                        this.errors.subject.message = '';
                    }

                    if (JSON.stringify(this.campaign.selectedTemplate) == "{}" && this.action == "create") {
                        success = false;
                        this.errors.selectedTemplate.message = 'Please select Template';
                    } else {
                        this.errors.selectedTemplate.message = '';
                    }

                } else {
                    this.errors.fromEmail.message = '';
                    this.errors.fromName.message = '';
                    this.errors.subject.message = '';
                }

                return success;
            }
        },
        watch: {
            campaignTypes: {
                handler(currVal, oldVal) {
                    //this.campaign.selectedCampaignType = currVal[0];
                    this.campaign.selectedCampaignType = currVal[2];
                    this.$emit("campaignChangeEvent", this.campaign.selectedCampaignType);
                },
                deep: true
            },
            'campaign.name'(currVal, oldVal) {
                this.$emit("changeName", currVal);
            },
            hitApi: {
                handler(currVal, oldVal) {
                    if (currVal.step == 1) {
                        this.submitStep();
                    }
                },
                deep: true
            },
            campaignId(currVal, oldVal) {
                this.action = "update";
            },
            getCampaignStep: {
                handler(currVal, oldVal) {
                    if (JSON.stringify(currVal) != "{}") {
                        this.campaign = currVal;
                        this.$emit("emitCode", this.campaign.code);
                        this.$emit("campaignChangeEvent", this.campaign.selectedCampaignType);
                        this.bus.$emit("displayCapController", this.campaign.frequencyCapController);

                        if (this.campaign.selectedTemplate.emailContent != undefined) {
                            this.bus.$emit("passEmailContent", this.campaign.selectedTemplate.emailContent);
                            delete this.campaign.selectedTemplate.emailContent;
                        }

                        if (this.campaign.selectedCampaignType.name.toLowerCase() == "email" && !this.blockGetCampaignStep) {
                            this.haveSaveFirstStep = true;
                        }

                        this.updateStep();
                    }
                },
                deep: true
            },
            campaign: {
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

<style>
    .vue-input-tag-wrapper .new-tag {
        background: transparent !important;
        border: none !important;
        color: #777 !important;
        flex-grow: 1 !important;
        margin-bottom: 6px !important;
        margin-top: 1px !important;
        outline: none !important;
        padding: 4px 4px 4px 0 !important;
        height: 34px !important;
        display: inline-block !important;
        width: auto !important;
    }

    .vue-input-tag-wrapper .input-tag {
        height: 30px !important;
        background-color: #f0f0f0 !important;
        border: none !important;
        border-radius: 6px !important;
        color: #666 !important;
        padding: 5px 8px !important;
    }

    .vue-input-tag-wrapper .input-tag .remove {
        color: #2a8689 !important
    }

</style>