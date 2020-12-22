<template>
    <div class="tab active" id="compose_tab">
        <div class="input_row">
            <input type="text" placeholder="Enter Card Name" v-model="step1.name">
            <p class="error">{{errors.name.message}}</p>
        </div>
        <div class="input_row">
            <input-tag placeholder="Enter Tags" :add-tag-on-blur="true"
                       v-bind:key="step1.tags.length"
                       v-model="step1.tags">
            </input-tag>
        </div>
        <div class="twocolumns">
            <div class="column">
                <strong class="title">Card Preview </strong>
                <div class="column_text">
                    <div class="table_header_holder">
                        <div class="col fluid">
                            <div class="holder">
                                <select v-if="step1Prop != undefined" class="add"
                                        v-model="step1.cardType" v-on:change="changeCardType(true)">
                                    <option v-for="template in step1Prop.newsFeedTemplates"
                                            v-bind:value="template.id">
                                        {{template.name}}
                                    </option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div id="composeNewsfeed" class="compose_layout_holder">
                        <div id="previewHtml" class="dialogue_holder no_max_height"
                             v-html="step1.languageArr[langIndex].template">
                            <!--<div id="previewHtml" v-html="step1.languageArr[langIndex].template">

                            </div>-->
                            <div class="head_inputs sec_hidden">
                                <strong>Search for a user to preview personalization on this card.</strong>
                                <div class="table_header_holder_cols">
                                    <div class="col">
                                        <div class="holder">
                                            <input type="text" class="search_input"
                                                   placeholder="Email Address or External User ID">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <p class="error">{{errors.content.image.message}}</p>
                </div>
            </div>
            <div class="column" style="height: auto;">
                <strong class="title">
                    <span>Compose</span>
                </strong>
                <div class="compose_tab_content">
                    <div class="tab_content active" id="edit">
                        <div class="compose_widget">
                            <div class="column_text">
                                <form action="#" class="compose_form">
                                    <fieldset>
                                        <div style="position: relative" class="languages_holder">
                                            <strong class="anchor_pointer" v-on:click.stop="addLangPopUp()"><i
                                                    class="fas fa-plus"></i> Add Languages</strong>
                                            <ul class="language_list">
                                                <li v-for="lang in languageObj.totalLang" class="anchor_pointer"
                                                    v-bind:class="isActiveLang(lang)"
                                                    v-on:click="addLang(lang)">
                                                    <img class="adjust_height" v-bind:src="lang.imgUrl">
                                                    {{lang.label}}
                                                    <i v-if="isActiveLang(lang) == 'active'" class="fas fa-check"
                                                       style="color: white;"></i>
                                                </li>
                                            </ul>
                                            <ul class="list_none flags">
                                                <li v-for="language in step1.totalSelectedLang" class="anchor_pointer"
                                                    v-bind:class="step1.languageArr[langIndex].language == language.value ? 'active': ''"
                                                    v-on:click="switchLang(language.value)">
                                                    <img v-bind:src="language.imgUrl">
                                                    <i v-if="language.value != 'en'" class="fas fa-times"
                                                       v-on:click.stop="removeLang(language)"></i>
                                                    <i v-else class="fas fa-check"></i>
                                                </li>
                                            </ul>
                                        </div>
                                        <div class="label_holder"><label
                                                for="title_field">Category</label>
                                        </div>
                                        <select class="add" v-model="step1.category">
                                            <option v-bind:value="'news'">News</option>
                                            <option v-bind:value="'advertising'">Advertising</option>
                                            <option v-bind:value="'announcements'">Announcements</option>
                                            <option v-bind:value="'social'">Social</option>
                                        </select>

                                        <div style="margin-top: 10px" class="label_holder"><label
                                                for="title_field">Title</label>
                                        </div>
                                        <input v-if="step1.totalSelectedLang[langIndex] != undefined" type="text"
                                               id="title_field"
                                               v-bind:dir="step1.totalSelectedLang[langIndex].dir"
                                               v-model="step1.languageArr[langIndex].title">
                                        <p class="error">{{errors.content.title.message}}</p>

                                        <div class="label_holder"><label
                                                for="msg_field">Message</label>
                                        </div>

                                        <textarea v-bind:disabled="disable"
                                                  v-if="step1.totalSelectedLang[langIndex] != undefined" id="msg_field"
                                                  v-bind:dir="step1.totalSelectedLang[langIndex].dir"
                                                  v-model="step1.languageArr[langIndex].message"></textarea>
                                        <p class="error">{{errors.content.message.message}}</p>

                                        <div class="label_holder">
                                            <label>Image
                                                <a v-on:click="activateGalleryModal"
                                                   class="anchor_pointer popup_opener img_upload_btn">Launch
                                                    image gallery
                                                </a>
                                            </label>
                                        </div>

                                        <div class="table_header_holder">
                                            <div class="col fluid">
                                                <div class="holder">
                                                    <div class="label_holder"><label for="button_1">Action
                                                        Text</label></div>
                                                    <input v-bind:disabled="disable" type="text" id="button_1"
                                                           placeholder="Link text"
                                                           v-if="step1.totalSelectedLang[langIndex] != undefined"
                                                           v-bind:dir="step1.totalSelectedLang[langIndex].dir"
                                                           v-model="step1.languageArr[langIndex].actionText">
                                                    <p class="error">{{errors.content.actionText.message}}</p>
                                                </div>
                                            </div>
                                        </div>
                                    </fieldset>
                                </form>
                            </div>
                        </div>
                        <div class="compose_widget">
                            <strong class="widget_heading">
                                <span>On Click Behavior</span>
                            </strong>
                            <div class="column_text add">
                                <div class="row">
                                    <div class="brand_holder">
                                        <i class="fab fa-apple"></i>
                                        <select class="add" v-model="step1.links[0].type">
                                            <option v-bind:value="'WEBLINK'">Redirect to Web URL</option>
                                            <option v-bind:value="'DEEPLINK'">Deep Link Into App</option>
                                        </select>
                                    </div>
                                    <div class="before_div android_before">
                                        <input type="text" v-model="step1.links[0].value">
                                        <p class="error">{{errors.links[0].message}}</p>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="brand_holder">
                                        <i class="fab fa-android"></i>
                                        <select class="add" v-model="step1.links[1].type">
                                            <option v-bind:value="'WEBLINK'">Redirect to Web URL</option>
                                            <option v-bind:value="'DEEPLINK'">Deep Link Into App</option>
                                        </select>
                                    </div>
                                    <input type="text" v-model="step1.links[1].value">
                                    <p class="error">{{errors.links[1].message}}</p>
                                </div>
                                <div class="row">
                                    <div class="brand_holder">
                                        <i class="fab fa-windows"></i>
                                        <select class="add" v-model="step1.links[2].type">
                                            <option v-bind:value="'WEBLINK'">Redirect to Web URL</option>
                                            <option v-bind:value="'DEEPLINK'">Deep Link Into App</option>
                                        </select>
                                    </div>
                                    <input type="text" v-model="step1.links[2].value">

                                    <p class="error">{{errors.links[2].message}}</p>
                                </div>
                                <div class="row">
                                    <div class="brand_holder">
                                        <i class="fas fa-globe"></i>
                                        <select class="add" v-model="step1.links[3].type">
                                            <option v-bind:value="'WEBLINK'">Redirect to Web URL</option>
                                            <option v-bind:value="'DEEPLINK'">Deep Link Into App</option>
                                        </select>
                                    </div>
                                    <input type="text" v-model="step1.links[3].value">
                                </div>
                                <p class="error">{{errors.links[3].message}}</p>

                            </div>
                            <div class="sec_hidden">
                                <strong class="widget_heading">
                                    <span>Categories (Optional)</span>
                                </strong>
                                <div class="column_text" style="padding-top:20px;">
                                    <div class="languages_holder">
                                        <strong><i class="fas fa-plus"></i> Add New
                                            Pair</strong>
                                        <h4 class="left">Key Value Pairs</h4>
                                    </div>
                                    <div class="center_text">
                                        <p>You have not yet defined key value pairs for
                                            this
                                            message.<br>To add a new pair, click “Add
                                            New
                                            Pair”.</p>
                                    </div>
                                    <div class="table_header_holder">
                                        <i class="fas fa-trash-alt align_right"></i>
                                        <div class="table_header_holder_cols">
                                            <div class="col">
                                                <div class="holder">
                                                    <input type="search" class="search_input">
                                                </div>
                                            </div>
                                            <div class="col">
                                                <div class="holder">
                                                    <input type="text" class="go">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import InputTag from 'vue-input-tag';

    export default {
        props: ['step1Prop', 'newsFeedId', 'hitApi', 'getNewsFeedStep'],
        components: {
            InputTag,
        },
        data() {
            return {
                locked: true,
                langIndex: 0,
                step1: {
                    step: 'compose',
                    name: '',
                    tags: [],
                    cardType: '',
                    totalSelectedLang: [],
                    languageArr: [
                        {
                            language: 'en',
                            title: '',
                            message: '',
                            actionText: '',
                            template: '',
                            imgUrl: '',
                        }
                    ],
                    category: 'news',
                    links: [
                        {
                            category: 'ios',
                            type: 'WEBLINK',
                            value: ''
                        },
                        {
                            category: 'android',
                            type: 'WEBLINK',
                            value: ''
                        },
                        {
                            category: 'windowPhone',
                            type: 'WEBLINK',
                            value: ''
                        },
                        {
                            category: 'web',
                            type: 'WEBLINK',
                            value: ''
                        },
                    ],
                    isActive: false
                },
                blankLanguage: {
                    language: 'en',
                    title: '',
                    message: '',
                    actionText: '',
                    template: '',
                    imgUrl: '',
                },
                languageObj: {
                    totalLang: [],
                },
                errors: {
                    name: {
                        message: '',
                    },
                    links: [
                        {
                            message: '',
                        },
                        {
                            message: '',
                        },
                        {
                            message: '',
                        },
                        {
                            message: '',
                        }
                    ],
                    content: {
                        title: {
                            message: '',
                        },
                        message: {
                            message: '',
                        },
                        actionText: {
                            message: '',
                        },
                        image: {
                            message: ''
                        }
                    }
                },
                globalImage: '',
                disable: false
            };
        },
        mounted() {
            this.initialActivity();
        },
        beforeDestroy() {
            this.bus.$off("galleryModalReturn");
        },
        methods: {
            initialActivity() {
                $(document).on("click", "a", function (e) {
                    e.preventDefault();
                });

                $("body").click(function () {
                    $(".language_list").slideUp();
                });

                this.bus.$on("galleryModalReturn", (url) => {
                    this.globalImage = url;
                    $("#pasteImage").attr("src", url);
                    this.step1.languageArr[this.langIndex].imgUrl = url;
                    setTimeout(() => {
                        this.manipulateHtml();
                    }, 1);
                });
            },
            changeCardType(splice = false) {
                if (splice) {
                    this.langIndex = 0;
                    let totalLanguages = this.step1.languageArr.length;
                    if (totalLanguages > 1) {
                        this.step1.languageArr.splice(1, totalLanguages - 1);
                        this.step1.totalSelectedLang.splice(1, totalLanguages - 1);
                    }
                }

                for (let i = 0; i < this.step1Prop.newsFeedTemplates.length; i++) {
                    if (this.step1Prop.newsFeedTemplates[i].id == this.step1.cardType) {
                        this.step1.languageArr[this.langIndex].template = this.step1Prop.newsFeedTemplates[i].template;

                        setTimeout(() => {
                            if (/*splice && */this.globalImage != '') {
                                $("#pasteImage").attr("src", this.globalImage);
                                this.step1.languageArr[this.langIndex].imgUrl = this.globalImage;
                            } else if (this.globalImage == '') {
                                this.step1.languageArr[this.langIndex].imgUrl = this.globalImage;
                            }
                            this.manipulateHtml();
                        }, 1);
                        break;
                    }
                }

                this.disableFields();
            },
            addLangPopUp() {
                $(".language_list").slideToggle();
            },
            addLang(obj) {
                for (var i = 0; i < this.step1.totalSelectedLang.length; i++) {
                    if (JSON.stringify(this.step1.totalSelectedLang[i]) == JSON.stringify(obj)) {
                        break;
                    }
                }
                if (i >= this.step1.totalSelectedLang.length) {
                    this.removeErrors();
                    this.step1.totalSelectedLang.push(JSON.parse(JSON.stringify(obj)));
                    let langObj = JSON.parse(JSON.stringify(this.blankLanguage));
                    langObj.language = obj.value;
                    this.step1.languageArr.push(JSON.parse(JSON.stringify(langObj)));
                    this.langIndex = i;
                    this.changeCardType();
                    if (this.globalImage != '') {
                        $("#pasteImage").attr("src", this.globalImage);
                        this.step1.languageArr[this.langIndex].imgUrl = this.globalImage;
                    }
                }
            },
            isActiveLang(obj) {
                for (var i = 0; i < this.step1.totalSelectedLang.length; i++) {
                    if (JSON.stringify(this.step1.totalSelectedLang[i]) == JSON.stringify(obj)) {
                        return "active";
                    }
                }
            },
            switchLang(lang) {
                let langArr = this.step1.languageArr;
                for (let i = 0; i < langArr.length; i++) {
                    if (langArr[i].language == lang) {
                        this.langIndex = i;
                        break;
                    }
                }
            },
            removeLang(obj) {

                this.$swal({
                    title: "Are you sure ?  You want to remove this language !",
                    type: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#3085d6",
                    confirmButtonText: "Yes, Do it!"
                }).then((result) => {

                    if (result.value) {
                        for (let i = 0; i < this.step1.totalSelectedLang.length; i++) {
                            if (JSON.stringify(this.step1.totalSelectedLang[i]) == JSON.stringify(obj)) {

                                if (this.langIndex > i || (this.langIndex == i && i == this.step1.languageArr.length - 1)) {
                                    this.langIndex--;
                                }

                                this.step1.totalSelectedLang.splice(i, 1);
                                this.step1.languageArr.splice(i, 1);
                                break;
                            }
                        }
                    }
                });
            },
            manipulateHtml() {
                $("#nf_title").text(this.step1.languageArr[this.langIndex].title != '' ? this.step1.languageArr[this.langIndex].title : 'Dummy Title');
                $("#nf_title").attr("dir", this.step1.totalSelectedLang[this.langIndex].dir);
                $("#nf_message").text(this.step1.languageArr[this.langIndex].message != '' ? this.step1.languageArr[this.langIndex].message : 'Dummy Message');
                $("#nf_message").attr("dir", this.step1.totalSelectedLang[this.langIndex].dir);
                $("#nf_action").text(this.step1.languageArr[this.langIndex].actionText != '' ? this.step1.languageArr[this.langIndex].actionText : 'Dummy Action Text');
                $("#nf_action").attr("dir", this.step1.totalSelectedLang[this.langIndex].dir);
                this.step1.languageArr[this.langIndex].template = $("#previewHtml").html();
            },
            activateGalleryModal() {
                this.bus.$emit('galleryModalOpen', false);
            },
            submitStep() {
                this.locked = false;
                let validation1 = this.validate();
                let validation2 = this.validateContent();
                if (validation1 && validation2) {
                    let payLoad = {
                        resource: "newsfeeds",
                        action: this.newsFeedId == -1 ? 'create' : 'update',
                        data: JSON.parse(JSON.stringify(this.step1))
                    };


                    payLoad.data.languageArr = btoa(unescape(encodeURIComponent((JSON.stringify(payLoad.data.languageArr)))));

                    if (this.newsFeedId != -1) {
                        payLoad.id = this.newsFeedId;
                    }

                    this.api("post",
                        this.constants.getUrl("backendApiUrl"),
                        payLoad,
                        this.authHeaders,
                        true,
                    ).then((response) => {
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
                                this.$emit("goNext", response.data.data.id);
                                this.updateStep();
                            }
                        }
                    }).catch((error) => {

                    });
                }
            },
            disableFields() {
                this.disable = false;
                for (let i = 0; i < this.step1Prop.newsFeedTemplates.length; i++) {
                    if (this.step1Prop.newsFeedTemplates[i].id == this.step1.cardType && this.step1Prop.newsFeedTemplates[i].name.toLowerCase() == 'captioned image') {
                        this.disable = true;
                        break;
                    }
                }
            },
            updateStep() {
                let obj = {
                    totalLang: this.step1.totalSelectedLang,
                    langArr: this.step1.languageArr,
                };
                this.bus.$emit("step1", obj);
            },
            validate() {

                let success = true;
                if (this.step1.name == '') {
                    this.errors.name.message = 'NewsFeed name is required';
                    success = false;
                } else {
                    this.errors.name.message = '';
                }

                for (let i = 0; i < this.step1.links.length; i++) {
                    if (this.step1.links[i].type == 'WEBLINK') {

                        let expression = /(http:\/\/www\.|https:\/\/www\.|http:\/\/|https:\/\/)[a-z0-9]+([\-\.]{1}[a-z0-9]+)*\.[a-z]{2,5}(:[0-9]{1,5})?(\/.*)?$/gi;
                        if (!expression.test(this.step1.links[i].value)) {
                            this.errors.links[i].message = 'Invalid WebLink';
                            success = false;
                        } else {
                            this.errors.links[i].message = '';
                        }

                    } else {
                        let expression1 = /[A-Za-z0-9]+:\/\/([A-Za-z0-9]+|\/)+/gi;
                        let expression2 = /(http:\/\/www\.|https:\/\/www\.|http:\/\/|https:\/\/)[a-z0-9]+([\-\.]{1}[a-z0-9]+)*\.[a-z]{2,5}(:[0-9]{1,5})?(\/.*)?$/gi;
                        if ((!expression1.test(this.step1.links[i].value)) && (!expression2.test(this.step1.links[i].value))) {
                            this.errors.links[i].message = 'Invalid DeepLink';
                            success = false;
                        } else {
                            this.errors.links[i].message = '';
                        }
                    }
                }

                return success;
            },
            validateContent() {
                let success = true;
                for (let i = 0; i < this.step1.languageArr.length; i++) {
                    if (this.step1.languageArr[i].title == '') {
                        success = false;
                        this.errors.content.title.message = 'Title is required';
                    } else {
                        this.errors.content.title.message = '';
                    }
                    if (this.step1.languageArr[i].message == '' && !this.disable) {
                        success = false;
                        this.errors.content.message.message = 'Message is required';
                    } else {
                        this.errors.content.message.message = '';
                    }
                    if (this.step1.languageArr[i].actionText == '' && !this.disable) {
                        success = false;
                        this.errors.content.actionText.message = 'Action Text is required';
                    } else {
                        this.errors.content.actionText.message = '';
                    }
                    if (this.step1.languageArr[i].imgUrl == '') {
                        success = false;
                        this.errors.content.image.message = 'Image is required';
                    } else {
                        this.errors.content.image.message = '';
                    }

                    if (!success) {
                        if (i != this.langIndex) {
                            this.langIndex = i;
                        }
                        break;
                    }
                }
                return success;
            },
            validateCurrentContent(i) {

                if (this.step1.languageArr[i].title == '') {
                    this.errors.content.title.message = 'Title is required';
                } else {
                    this.errors.content.title.message = '';
                }
                if (this.step1.languageArr[i].message == '' && !this.disable) {
                    this.errors.content.message.message = 'Message is required';
                } else {
                    this.errors.content.message.message = '';
                }
                if (this.step1.languageArr[i].actionText == '' && !this.disable) {
                    this.errors.content.actionText.message = 'Action Text is required';
                } else {
                    this.errors.content.actionText.message = '';
                }
                if (this.step1.languageArr[i].imgUrl == '') {
                    success = false;
                    this.errors.content.image.message = 'Image is required';
                } else {
                    this.errors.content.image.message = '';
                }

            },
            removeErrors() {
                this.locked = true;
                this.errors.content.title.message = '';
                this.errors.content.message.message = '';
                this.errors.content.actionText.message = '';
            }
        },
        watch: {
            step1Prop: {
                handler(currVal, oldVal) {
                    this.step1.cardType = currVal.newsFeedTemplates[0].id;

                    this.disableFields();

                    this.step1.languageArr[this.langIndex].template = currVal.newsFeedTemplates[0].template;
                    this.languageObj.totalLang = JSON.parse(JSON.stringify(currVal.languages[0]));
                    this.step1.totalSelectedLang.push(JSON.parse(JSON.stringify(currVal.languages[1])));
                },
                deep: true
            },
            step1: {
                handler(currVal, oldVal) {
                    setTimeout(() => {
                        this.manipulateHtml();
                    }, 1);

                    if (!this.locked) {
                        this.validate();
                        this.validateCurrentContent(this.langIndex);
                    }
                },
                deep: true
            },
            langIndex(currVal, oldVal) {
                setTimeout(() => {
                    this.manipulateHtml();
                }, 1);

                if (!this.locked) {
                    this.validate();
                    this.validateCurrentContent(this.langIndex);
                }
            },
            'step1.name'(currVal, oldVal) {
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
            getNewsFeedStep: {
                handler(currVal, oldVal) {
                    let val = JSON.stringify(currVal);
                    if (val != '{}') {
                        this.step1 = JSON.parse(val);
                        this.globalImage = this.step1.languageArr[0].imgUrl;
                        this.$emit("isStatusActive", this.step1.isActive);
                        this.updateStep();
                        this.disableFields();
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

    .adjust_height {
        margin-top: 3px;
        margin-right: 5px;
    }

    .language_list {
        display: none;
        max-height: 300px;
        overflow: auto;
        padding-left: 0px;
        position: absolute;
        list-style: none;
        right: 0px;
        top: 100%;
        background: #f0f0f0;
        width: 273px;
        border-radius: 5px;
        z-index: 100;
    }

    .language_list li {
        color: black;
        padding: 7px 15px;
    }

    .language_list li:hover, .language_list li.active {
        background: #2a8689;
        color: white;
    }

    .fa-check {
        float: right;
        font-size: 17px;
    }

    .fa-times {
        font-size: 17px;
    }

    .brand_holder {
        border: 1px solid #b2b2b2;
        position: relative;
        border-radius: 4px;
        overflow: hidden;
        margin: 0 0 10px;
    }

    .brand_holder i {
        color: #2a8689;
        font-size: 21px;
        position: absolute;
        left: 10px;
        top: 12px;
    }

    .brand_holder select {
        margin: 0 !important;;
        border: none !important;
        padding-left: 36px !important;
    }

    .dialogue.card.cross-promotion .txt {
        max-width: 49%;
    }

    #previewHtml .dialogue * {
        word-break: break-all;
    }

    #previewHtml div {
        z-index: 0;
    }

</style>

<style>

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