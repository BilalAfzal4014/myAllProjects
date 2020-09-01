<template>
    <div class="tab" id="compose_tab">
        <div id="emoji_listing_modal" class="modal fade" role="dialog">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">Emoji Listing</h4>
                    </div>
                    <div class="modal-body" style="max-height: 500px;">
                        <div style="max-height: 450px; padding-right: 0px;" class="table_holder default_scroll">
                            <table id="emoji_listing_table" class="display dataTable no-footer" role="grid">
                                <tbody>
                                <tr v-for="(n, index) in emojis.length" v-if="index % 5 == 0">
                                    <td v-for="(n1, index1) in 5"
                                        v-if="(index + index1) < emojis.length"
                                        v-on:click="pasteEmoji(emojis[index + index1])">
                                        {{emojis[index + index1]}}
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div id="decode_html_div" style="display: none"></div>
        <attributeListingModal v-bind:closeModal="attributeListingModal.off"
                               v-bind:openModal="attributeListingModal.on"
                               v-on:getCode="pasteCode">
        </attributeListingModal>
        <header class="table_header top add">
            <div class="table_header_holder choose_platform_cols add">
                <div class="variant_drop">
                    <a class="anchor_pointer variant_droper active" v-on:click.stop="addVariant"> <i
                            class="fas fa-plus"></i>
                        Add Variant</a>
                    <ul class="list_none ">
                        <li><a class="anchor_pointer" v-on:click="copyVariant(variantsArray[0],false)">Copy from Variant
                            1</a>
                        </li>
                        <li><a class="anchor_pointer" v-on:click="copyVariant(blankVariant, true)">Compose Blank
                            {{campaignType.name}}
                            Message</a></li>
                    </ul>
                </div>
                <ul class="variant_list list_none">
                    <li style="cursor: pointer;" v-bind:class="index == variantIndex ? 'active': ''"
                        v-for="(variant, index) in variantsArray"
                        v-on:click="shiftVariant(index)">Variant <!--{{index + 1}}--><em
                            v-on:click.stop="popVariant(index)">x</em></li>
                </ul>
            </div>
        </header>
        <header class="table_header top" v-if="inAppPushData" v-show="rules[ruleIndex].header.vShow">
            <strong>Choose Platform</strong>
            <div class="table_header_holder choose_platform_cols">
                <div class="col" v-show="rules[ruleIndex].platform.vShow">
                    <div class="holder">
                        <i class="fas fa-desktop"></i>
                        <select v-bind:disabled="rules[ruleIndex].platform.vDisabled"
                                v-model="variantsArray[variantIndex].platform">
                            <option v-bind:value="platform" v-for="platform in inAppPushData.platformList">
                                {{platform.name}}
                            </option>
                        </select>
                    </div>
                </div>
                <div class="col" v-show="rules[ruleIndex].messageType.vShow">
                    <div class="holder">
                        <i class="far fa-comment"></i>
                        <select v-bind:disabled="rules[ruleIndex].messageType.vDisabled"
                                v-model="variantsArray[variantIndex].messageType" v-on:change="changeMessageType">
                            <option v-bind:value="messageType" v-for="messageType in inAppPushData.messageTypeList">
                                {{messageType.name}}
                            </option>
                        </select>
                    </div>
                </div>
                <div class="col" v-show="rules[ruleIndex].orientation.vShow">
                    <div class="holder">
                        <i class="fas fa-portrait"></i>
                        <select v-bind:disabled="rules[ruleIndex].orientation.vDisabled"
                                v-model="variantsArray[variantIndex].orientation">
                            <option v-bind:disabled="layoutType.name == 'Landscape' ? true: false"
                                    v-bind:value="layoutType"
                                    v-for="layoutType in inAppPushData.layoutTypeList">
                                {{layoutType.name}}

                            </option>
                        </select>
                    </div>
                </div>
            </div>
        </header>
        <div class="twocolumns">
            <div class="column same-height"
                 v-bind:style="rules[ruleIndex].emailControl.vShow ? 'width: 70%': 'width: 49%'">
                <strong class="title">{{rules[ruleIndex].preview ? 'preview': 'Compose HTML'}}</strong>
                <div class="column_text new_col" v-show="rules[ruleIndex].pushInAppControl.vShow">
                    <div class="table_header_holder">
                        <div class="col dummyCol">
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
                        <div class="col dummyCol" v-if="inAppPushData" v-show="rules[ruleIndex].position.vShow">
                            <div class="holder">
                                <select v-bind:disabled="rules[ruleIndex].position.vDisabled"
                                        v-model="variantsArray[variantIndex].position">
                                    <option v-bind:value="devicePosition"
                                            v-for="devicePosition in inAppPushData.devicePositionList">
                                        {{devicePosition.name}}
                                    </option>
                                </select>
                            </div>
                        </div>
                        <div class="col dummyCol" v-show="rules[ruleIndex].sdk.vShow">
                            <div class="holder">
                                <select v-bind:disabled="rules[ruleIndex].sdk.vDisabled">
                                    <option>SDK Version</option>
                                    <option>Android</option>
                                    <option>iOS</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="compose_layout_holder">
                        <div class="dialogue_holder">
                            <div class="full_height" style="display: none;">
                                <div id="bind_template" v-bind:key="variantIndex + '' + langIndex"
                                     v-html="variantsArray[variantIndex].lang[langIndex].templateInfo.template"
                                     v-manipulateTemplate="variantsArray[variantIndex].lang[langIndex].templateInfo"
                                ></div>
                            </div>
                            <p style="text-align: center" class="error">{{errors.image.message}}</p>
                            <div v-bind:class="variantsArray[variantIndex].messageType.name">
                                <div class="preview_skin" v-bind:class="changeDevice">
                                    <div class="iframe_holder">
                                        <iframe class="iframe_el"
                                                v-bind:srcdoc="iframeStyleLink + variantsArray[variantIndex].lang[langIndex].templateInfo.template"></iframe>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <strong class="notice">Actual rendering may not be identical to this preview depending on the
                            user’s environment.</strong>
                    </div>
                </div>
                <div class="column_text" v-show="rules[ruleIndex].emailControl.vShow">
                    <div class="languages_holder" style="position: relative">
                        <strong class="anchor_pointer" v-on:click.stop="addLangPopUp()"><i
                                class="fas fa-plus"></i> Add Languages</strong>
                        <ul class="language_list">
                            <li class="anchor_pointer" v-bind:class="isActiveLang(lang)"
                                v-for="lang in langOptions"
                                v-on:click="addLang(lang)">
                                <img class="adjust_height"
                                     v-bind:src="lang.imgUrl">
                                {{lang.label}}
                                <i class="fas fa-check" v-if="isActiveLang(lang) == 'active'"></i>
                            </li>
                        </ul>
                        <ul class="list_none flags">
                            <li class="anchor_pointer"
                                v-bind:class="variantsArray[variantIndex].lang[langIndex].language == language.value ? 'active': ''"
                                v-for="language in variantsArray[variantIndex].totalLang"
                                v-on:click="switchLang(language.value)">
                                <img v-bind:src="language.imgUrl">
                                <i class="fas fa-times" v-if="language.value != 'en'"
                                   v-on:click.stop="removeLang(language)"></i>
                                <i class="fas fa-check" v-else></i>
                            </li>
                        </ul>
                    </div>
                    <div class="compose_layout_holder"
                         v-if="campaignType.code != undefined && campaignType.code.toLowerCase() == 'email'">
                        <ckEditor v-bind:editorObj="{
                                        content: variantsArray[variantIndex].lang[langIndex].templateInfo.template,
                                        lang: variantsArray[variantIndex].lang[langIndex].language,
                                        key: variantIndex + '' + langIndex
                                  }"
                                  v-bind:key="emailContent.called"
                                  v-on:openAttributeModal="attributeListingModal.on = !attributeListingModal.on"
                                  v-on:openGalleryModal="activateGalleryModal"
                        ></ckEditor>
                    </div>
                </div>
            </div>
            <div class="column same-height" style="height: auto;"
                 v-bind:style="rules[ruleIndex].emailControl.vShow ? 'width: 29%': 'width: 49%'">
                <strong class="title">
                    <ul class="compose_tabs list_none">
                        <li v-bind:class="composeTab == 1 ? 'active': ''"
                            v-show="rules[ruleIndex].composeTab.vShow"><a
                                class="anchor_pointer"
                                v-on:click="shiftComposeTabs(1)"><i
                                class="fas fa-pencil-alt"></i></a></li>
                        <li v-bind:class="composeTab == 2 ? 'active': ''"
                            v-show="rules[ruleIndex].brushTab.vShow"><a
                                class="anchor_pointer"
                                v-on:click="shiftComposeTabs(2)"><i
                                class="fas fa-paint-brush"></i></a></li>
                        <li v-bind:class="composeTab == 3 ? 'active': ''"
                            v-show="rules[ruleIndex].settingsTab.vShow"><a
                                class="anchor_pointer"
                                v-on:click="shiftComposeTabs(3)"><i
                                class="fas fa-cog"></i></a></li>
                        <li v-bind:class="composeTab == 4 ? 'active': ''"
                            v-show="rules[ruleIndex].sendTestTab.vShow"><a
                                class="anchor_pointer"
                                v-on:click="shiftComposeTabs(4)"><i
                                class="far fa-eye"></i></a>
                        </li>
                    </ul>
                    <span>{{rules[ruleIndex].pushInAppControl.vShow ? 'Compose Push/In-App Messages' : 'Settings'}}</span>
                </strong>
                <div class="compose_tab_content">
                    <div id="edit" v-bind:class="composeTab == 1 ? 'tab_content active': 'tab_content'">
                        <div class="compose_widget">
                            <div class="column_text">
                                <div class="languages_holder" style="position: relative">
                                    <strong class="anchor_pointer" v-on:click.stop="addLangPopUp()"><i
                                            class="fas fa-plus"></i> Add Languages</strong>
                                    <ul class="language_list">
                                        <li class="anchor_pointer" v-bind:class="isActiveLang(lang)"
                                            v-for="lang in langOptions"
                                            v-on:click="addLang(lang)">
                                            <img class="adjust_height"
                                                 v-bind:src="lang.imgUrl">
                                            {{lang.label}}
                                            <i class="fas fa-check" v-if="isActiveLang(lang) == 'active'"></i>
                                        </li>
                                    </ul>
                                    <ul class="list_none flags">
                                        <li class="anchor_pointer"
                                            v-bind:class="variantsArray[variantIndex].lang[langIndex].language == language.value ? 'active': ''"
                                            v-for="language in variantsArray[variantIndex].totalLang"
                                            v-on:click="switchLang(language.value)">
                                            <img v-bind:src="language.imgUrl">
                                            <i class="fas fa-times" v-if="language.value != 'en'"
                                               v-on:click.stop="removeLang(language)"></i>
                                            <i class="fas fa-check" v-else></i>
                                        </li>
                                    </ul>
                                </div>
                                <form action="#" class="compose_form">
                                    <fieldset>
                                        <div class="label_holder" v-show="rules[ruleIndex].title.vShow">
                                            <div class="variant_drop attr_holder">
                                                <strong><a v-on:click="activateAttributeModal('title_field')"> <i
                                                        class="fas fa-plus"></i> Add Attribute</a></strong>
                                                <strong v-if="this.campaignType.code != undefined && this.campaignType.code.toLowerCase() == 'push'"><a
                                                        v-on:click="activateEmojiModal('title_field')"> <i
                                                        class="fas fa-plus"></i> Insert Emoji</a></strong>
                                            </div>
                                            <label for="title_field">Title</label>
                                        </div>
                                        <input id="title_field" type="text"
                                               v-bind:dir="variantsArray[variantIndex].totalLang[langIndex].dir"
                                               v-bind:disabled="rules[ruleIndex].title.vDisabled"
                                               v-if="variantsArray[variantIndex].totalLang.length > 0"
                                               v-model="variantsArray[variantIndex].lang[langIndex].templateInfo.title"
                                               v-show="rules[ruleIndex].title.vShow">
                                        <p class="error">{{errors.title.message}}</p>
                                        <div class="label_holder" v-show="rules[ruleIndex].message.vShow">
                                            <div class="variant_drop attr_holder">
                                                <strong><a v-on:click="activateAttributeModal('msg_field')"> <i
                                                        class="fas fa-plus"></i> Add Attribute</a></strong>
                                                <strong v-if="this.campaignType.code != undefined && this.campaignType.code.toLowerCase() == 'push'"><a
                                                        v-on:click="activateEmojiModal('msg_field')"> <i
                                                        class="fas fa-plus"></i> Insert Emoji</a></strong>
                                            </div>
                                            <label for="msg_field">Message</label>
                                        </div>
                                        <textarea id="msg_field"
                                                  v-bind:dir="variantsArray[variantIndex].totalLang[langIndex].dir"
                                                  v-bind:disabled="rules[ruleIndex].message.vDisabled"
                                                  v-if="variantsArray[variantIndex].totalLang.length > 0"
                                                  v-model="variantsArray[variantIndex].lang[langIndex].templateInfo.message"
                                                  v-show="rules[ruleIndex].message.vShow"></textarea>
                                        <p class="error">{{errors.message.message}}</p>

                                        <div class="label_holder" v-if="rules[ruleIndex].galleryControl.vShow">
                                            <label>Image
                                                <a v-on:click="activateGalleryModal"
                                                   class="anchor_pointer popup_opener img_upload_btn">Launch
                                                    image gallery
                                                </a>
                                            </label>
                                        </div>
                                        <div class="table_header_holder">
                                            <div class="col" v-show="rules[ruleIndex].action1Label.vShow">
                                                <div class="holder">
                                                    <div class="label_holder"><label for="button_1">Action 1</label>
                                                    </div>
                                                    <input id="button_1"
                                                           type="text"
                                                           v-bind:dir="variantsArray[variantIndex].totalLang[langIndex].dir"
                                                           v-bind:disabled="rules[ruleIndex].action1Label.vDisabled"
                                                           v-if="variantsArray[variantIndex].totalLang.length > 0"
                                                           v-model="variantsArray[variantIndex].lang[langIndex].templateInfo.action1.label">
                                                    <p class="error">{{errors.action1.label.message}}</p>
                                                </div>
                                            </div>
                                            <div class="col" v-show="rules[ruleIndex].action2Label.vShow">
                                                <div class="holder">
                                                    <div class="label_holder"><label for="button_2">Action 2</label>
                                                    </div>
                                                    <input id="button_2" type="text"
                                                           v-bind:dir="variantsArray[variantIndex].totalLang[langIndex].dir"
                                                           v-bind:disabled="rules[ruleIndex].action2Label.vDisabled"
                                                           v-if="variantsArray[variantIndex].totalLang.length > 0"
                                                           v-model="variantsArray[variantIndex].lang[langIndex].templateInfo.action2.label">
                                                    <p class="error">{{errors.action2.label.message}}</p>
                                                </div>
                                            </div>
                                        </div>
                                    </fieldset>
                                </form>
                            </div>
                        </div>
                        <div class="compose_widget">
                            <strong class="widget_heading">
                                <i class="fab fa-apple"></i>
                                <i class="fab fa-android"></i>
                                <span>Device Options</span>
                            </strong>
                            <div class="column_text" v-if="inAppPushData">
                                <h4>Message Action</h4>
                                <div class="row" v-show="rules[ruleIndex].action1Type.vShow">
                                    <h5>Action 1</h5>
                                    <select class="add" v-bind:disabled="rules[ruleIndex].action1Type.vDisabled"
                                            v-model="variantsArray[variantIndex].lang[langIndex].templateInfo.action1.type">
                                        <option v-bind:value="action" v-for="action in inAppPushData.actionList"
                                                v-if="action1Qualification(action)">
                                            {{action.name}}
                                        </option>
                                    </select>
                                    <input type="text"
                                           v-bind:disabled="rules[ruleIndex].action1Value.vDisabled || variantsArray[variantIndex].lang[langIndex].templateInfo.action1.type.name.toLowerCase() == 'close'"
                                           v-model="variantsArray[variantIndex].lang[langIndex].templateInfo.action1.value"/>
                                    <p class="error">{{errors.action1.value.message}}</p>
                                </div>
                                <div class="row" v-show="rules[ruleIndex].action2Type.vShow">
                                    <h5>Action 2</h5>
                                    <select class="add" v-bind:disabled="rules[ruleIndex].action2Type.vDisabled"
                                            v-model="variantsArray[variantIndex].lang[langIndex].templateInfo.action2.type">
                                        <option v-bind:value="action" v-for="action in inAppPushData.actionList">
                                            {{action.name}}
                                        </option>
                                    </select>
                                    <input type="text"
                                           v-bind:disabled="rules[ruleIndex].action2Value.vDisabled || variantsArray[variantIndex].lang[langIndex].templateInfo.action2.type.name.toLowerCase() == 'close'"
                                           v-model="variantsArray[variantIndex].lang[langIndex].templateInfo.action2.value"/>
                                    <p class="error">{{errors.action2.value.message}}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div id="colors" v-bind:class="composeTab == 2 ? 'tab_content active': 'tab_content'">
                        <div class="compose_widget">
                            <div class="sec_hidden colors_row">
                                <div class="colors_row_holder">
                                    <div class="txt">
                                        <ul class="alignments list_none">
                                            <li class="active"><a href="#"><i class="fas fa-align-left"></i></a></li>
                                            <li><a href="#"><i class="fas fa-align-center"></i></a></li>
                                            <li><a href="#"><i class="fas fa-align-right"></i></a></li>
                                        </ul>
                                    </div>
                                    <strong class="colors_title">Text Alignment</strong>
                                </div>
                            </div>
                            <div class="colors_row" v-show="rules[ruleIndex].headerSection.vShow">
                                <div class="colors_row_holder">
                                    <h4>Header</h4>
                                    <div class="colors_inner_div">
                                        <div class="txt">
                                            <ul class="alignments list_none text-right">
                                                <li>
                                                    <input class="color_demo_bo" type="color"
                                                           v-model="variantsArray[variantIndex].lang[langIndex].templateInfo.design.header.headingColor">
                                                    <span class="selected_color_value">{{variantsArray[variantIndex].lang[langIndex].templateInfo.design.header.headingColor}}</span>
                                                </li>
                                            </ul>
                                        </div>
                                        <strong class="colors_title">Heading text color</strong>
                                    </div>
                                    <div class="colors_inner_div">
                                        <div class="txt">
                                            <ul class="alignments list_none text-right">
                                                <li>
                                                    <input class="color_demo_bo" type="color"
                                                           v-model="variantsArray[variantIndex].lang[langIndex].templateInfo.design.header.contentColor">
                                                    <span class="selected_color_value">{{variantsArray[variantIndex].lang[langIndex].templateInfo.design.header.contentColor}}</span>
                                                </li>
                                            </ul>
                                        </div>
                                        <strong class="colors_title">Content text color</strong>
                                    </div>
                                </div>
                            </div>
                            <div class="colors_row no_border">
                                <div class="colors_row_holder">
                                    <h4 v-show="rules[ruleIndex].buttonSection.vShow">Buttons</h4>
                                    <div class="colors_inner_div" v-show="rules[ruleIndex].buttonSection.vShow">
                                        <div class="txt">
                                            <ul class="alignments list_none">
                                                <li>
                                                    <input class="color_demo_bo" type="color"
                                                           v-model="variantsArray[variantIndex].lang[langIndex].templateInfo.design.button1.color">
                                                    <span class="selected_color_value">{{variantsArray[variantIndex].lang[langIndex].templateInfo.design.button1.color}}</span>
                                                    <em>Opacity</em>
                                                    <input placeholder="100" type="text"
                                                           v-model="variantsArray[variantIndex].lang[langIndex].templateInfo.design.button1.opacity">
                                                    <!--<em>%</em>-->
                                                </li>
                                            </ul>
                                        </div>
                                        <strong class="colors_title">Button 1 Background</strong>
                                    </div>
                                    <div class="colors_inner_div" v-show="rules[ruleIndex].buttonSection.vShow">
                                        <div class="txt">
                                            <ul class="alignments list_none">
                                                <li>
                                                    <input class="color_demo_bo" type="color"
                                                           v-model="variantsArray[variantIndex].lang[langIndex].templateInfo.design.button1.textColor">
                                                    <span class="selected_color_value">{{variantsArray[variantIndex].lang[langIndex].templateInfo.design.button1.textColor}}</span>
                                                </li>
                                            </ul>
                                        </div>
                                        <strong class="colors_title">Button 1 Text</strong>
                                    </div>
                                    <div class="colors_inner_div" v-show="rules[ruleIndex].buttonSection.vShow">
                                        <div class="txt">
                                            <ul class="alignments list_none">
                                                <li>
                                                    <input class="color_demo_bo" type="color"
                                                           v-model="variantsArray[variantIndex].lang[langIndex].templateInfo.design.button2.color">
                                                    <span class="selected_color_value">{{variantsArray[variantIndex].lang[langIndex].templateInfo.design.button2.color}}</span>
                                                    <em>Opacity</em>
                                                    <input placeholder="100" type="text"
                                                           v-model="variantsArray[variantIndex].lang[langIndex].templateInfo.design.button2.opacity">
                                                    <!--<em>%</em>-->
                                                </li>
                                            </ul>
                                        </div>
                                        <strong class="colors_title">Button 2 Background</strong>
                                    </div>
                                    <div class="colors_inner_div" v-show="rules[ruleIndex].buttonSection.vShow">
                                        <div class="txt">
                                            <ul class="alignments list_none">
                                                <li>
                                                    <input class="color_demo_bo" type="color"
                                                           v-model="variantsArray[variantIndex].lang[langIndex].templateInfo.design.button2.textColor">
                                                    <span class="selected_color_value">000000</span>
                                                </li>
                                            </ul>
                                        </div>
                                        <strong class="colors_title">Button 2 Text</strong>
                                    </div>
                                    <div class="colors_inner_div abc" v-show="rules[ruleIndex].backgroundSection.vShow">
                                        <div class="txt">
                                            <ul class="alignments list_none">
                                                <li>
                                                    <input class="color_demo_bo" type="color"
                                                           v-model="variantsArray[variantIndex].lang[langIndex].templateInfo.design.background.color">
                                                    <span class="selected_color_value">{{variantsArray[variantIndex].lang[langIndex].templateInfo.design.background.color}}</span>
                                                    <em>Opacity</em>
                                                    <input placeholder="100" type="text"
                                                           v-model="variantsArray[variantIndex].lang[langIndex].templateInfo.design.background.opacity">
                                                    <!--<em>%</em>-->
                                                </li>
                                            </ul>
                                        </div>
                                        <strong class="colors_title">Background Color</strong>
                                    </div>
                                </div>
                            </div>
                            <div class="colors_row" v-show="rules[ruleIndex].frameSection.vShow">
                                <div class="colors_row_holder colors_border_div">
                                    <div class="txt">
                                        <ul class="alignments list_none text-right">
                                            <li>
                                                <input class="color_demo_bo" type="color"
                                                       v-model="variantsArray[variantIndex].lang[langIndex].templateInfo.design.frame.color">
                                                <span class="selected_color_value">{{variantsArray[variantIndex].lang[langIndex].templateInfo.design.frame.color}}</span>
                                            </li>
                                        </ul>
                                    </div>
                                    <strong class="colors_title">Frame Color</strong>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div id="settings" v-bind:class="composeTab == 3 ? 'tab_content active': 'tab_content'">
                        <div class="compose_widget">
                            <div class="column_text">
                                <div class="languages_holder">
                                    <strong><i class="fas fa-plus"></i> Add New Pair</strong>
                                    <h4 class="left">Key Value Pairs</h4>
                                </div>
                                <div class="center_text">
                                    <p>You have not yet defined key value pairs for this message.<br>To add a new pair,
                                        click “Add New Pair”.</p>
                                </div>
                                <div class="table_header_holder">
                                    <i class="fas fa-trash-alt align_right"></i>
                                    <div class="table_header_holder_cols">
                                        <div class="col">
                                            <div class="holder">
                                                <input type="search">
                                            </div>
                                        </div>
                                        <div class="col">
                                            <div class="holder">
                                                <input class="go" type="text">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div id="view" v-bind:class="composeTab == 4 ? 'tab_content active': 'tab_content'">
                        <div class="compose_widget">
                            <div class="column_text">
                                <p class="view_notice" v-show="rules[ruleIndex].pushInAppControl.vShow">Testing In-App
                                    Message requires push notifications to be enabled
                                    on the test devices. Ensure push is enabled before sending.</p>
                                <header class="msg_inner_header">
                                    <h4>Test Recipients</h4>
                                    <p>Select at least one Content Test Group or individual user to receive this test
                                        message. Message will be customized with recipients attributes by default.</p>
                                    <div class="select_user">
                                        <div class="user_row" v-show="rules[ruleIndex].emailControl.vShow">
                                            <strong class="colors_title">From Email</strong>
                                            <input placeholder="Email" type="email" v-model="emailContent.fromEmail">
                                            <p class="error">{{sendTestErrors.fromEmail.message}}</p>
                                        </div>
                                        <div class="user_row" v-show="rules[ruleIndex].emailControl.vShow">
                                            <strong class="colors_title">Subject</strong>
                                            <input placeholder="Subject" type="text" v-model="emailContent.subject">
                                            <p class="error">{{sendTestErrors.subject.message}}</p>
                                        </div>
                                        <div class="user_row">
                                            <strong class="colors_title">Add Users</strong>
                                            <vSelect multiple placeholder="Search User(s)"
                                                     v-bind:filterable="false"
                                                     v-bind:options="sendTest.users"
                                                     v-model="sendTest.selectedUsers"
                                                     v-on:search="onSearch">
                                            </vSelect>
                                            <p class="error">{{sendTestErrors.users.message}}</p>
                                        </div>
                                        <div class="submit_holder">
                                            <button class="user_submit" style="background: #2a8689"
                                                    type="button" v-bind:disabled="sendTest.disable"
                                                    v-on:click="sendNotification">
                                                <i class="fa fa-refresh fa-spin"
                                                   v-bind:style="sendTest.disable ? 'display: inline-block': 'display: none'">
                                                </i> Send Test
                                            </button>
                                        </div>
                                    </div>
                                </header>
                                <p></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import vSelect from 'vue-select';
    import ckEditor from '../../../../otherComponents/ckEditor/ckEditor';
    import attributeListingModal from '../../../../otherComponents/attributeListingModal/attributeListingModal';

    export default {
        props: ['campaignType', 'languages', 'inAppPushData', 'campaignTemplates', 'hitApi', 'campaignId', 'getCampaignStep'],
        components: {
            ckEditor,
            vSelect,
            attributeListingModal,
        },
        data() {
            return {
                locked: true,
                lockedSendTest: true,
                emailContent: {
                    called: false,
                    fromEmail: '',
                    subject: ''
                },
                sendTest: {
                    disable: false,
                    users: [],
                    selectedUsers: []
                },
                attributeListingModal: {
                    on: false,
                    off: false
                },
                currentCode: "title_field",
                colors: ["#2a8689", "#7abbbd", "#5b8688", "#ffbdd2"],
                iframeStyleLink: "",
                currentTemplate: 'dialog',
                changeDevice: 'iphone',
                ruleIndex: 0,
                rules: [
                    {
                        name: "dialog",
                        platform: {
                            vShow: true,
                            vDisabled: false,
                        },
                        messageType: {
                            vShow: true,
                            vDisabled: false,
                        },
                        orientation: {
                            vShow: true,
                            vDisabled: false,
                        },
                        sdk: {
                            vShow: true,
                            vDisabled: true,
                        },
                        position: {
                            vShow: true,
                            vDisabled: false,
                        },
                        title: {
                            vShow: true,
                            vDisabled: false
                        },
                        message: {
                            vShow: true,
                            vDisabled: false
                        },
                        action1Label: {
                            vShow: true,
                            vDisabled: false
                        },
                        action2Label: {
                            vShow: true,
                            vDisabled: false
                        },
                        action1Type: {
                            vShow: true,
                            vDisabled: false
                        },
                        action2Type: {
                            vShow: true,
                            vDisabled: false
                        },
                        action1Value: {
                            vShow: true,
                            vDisabled: false
                        },
                        action2Value: {
                            vShow: true,
                            vDisabled: false
                        },
                        composeTab: {
                            vShow: true,
                            vDisabled: false
                        },
                        brushTab: {
                            vShow: true,
                            vDisabled: false
                        },
                        settingsTab: {
                            vShow: true,
                            vDisabled: false
                        },
                        sendTestTab: {
                            vShow: true,
                            vDisabled: false
                        },
                        headerSection: {
                            vShow: true,
                            vDisabled: false
                        },
                        buttonSection: {
                            vShow: true,
                            vDisabled: false
                        },
                        backgroundSection: {
                            vShow: true,
                            vDisabled: false
                        },
                        frameSection: {
                            vShow: true,
                            vDisabled: false
                        },
                        galleryControl: {
                            vShow: true,
                            vDisabled: false,
                        },
                        header: {
                            vShow: true,
                            vDisabled: false
                        },
                        preview: {
                            vShow: true,
                            vDisabled: false
                        },
                        emailControl: {
                            vShow: false,
                            vDisabled: true
                        },
                        pushInAppControl: {
                            vShow: true,
                            vDisabled: false
                        },
                    },
                    {
                        name: "full screen",
                        platform: {
                            vShow: true,
                            vDisabled: false,
                        },
                        messageType: {
                            vShow: true,
                            vDisabled: false,
                        },
                        orientation: {
                            vShow: true,
                            vDisabled: false,
                        },
                        sdk: {
                            vShow: true,
                            vDisabled: true,
                        },
                        position: {
                            vShow: true,
                            vDisabled: true,
                        },
                        title: {
                            vShow: true,
                            vDisabled: false
                        },
                        message: {
                            vShow: true,
                            vDisabled: false
                        },
                        action1Label: {
                            vShow: true,
                            vDisabled: false
                        },
                        action2Label: {
                            vShow: true,
                            vDisabled: false
                        },
                        action1Type: {
                            vShow: true,
                            vDisabled: false
                        },
                        action2Type: {
                            vShow: true,
                            vDisabled: false
                        },
                        action1Value: {
                            vShow: true,
                            vDisabled: false
                        },
                        action2Value: {
                            vShow: true,
                            vDisabled: false
                        },
                        composeTab: {
                            vShow: true,
                            vDisabled: false
                        },
                        brushTab: {
                            vShow: true,
                            vDisabled: false
                        },
                        settingsTab: {
                            vShow: true,
                            vDisabled: false
                        },
                        sendTestTab: {
                            vShow: true,
                            vDisabled: false
                        },
                        headerSection: {
                            vShow: true,
                            vDisabled: false
                        },
                        buttonSection: {
                            vShow: true,
                            vDisabled: false
                        },
                        backgroundSection: {
                            vShow: true,
                            vDisabled: false
                        },
                        frameSection: {
                            vShow: true,
                            vDisabled: false
                        },
                        galleryControl: {
                            vShow: true,
                            vDisabled: false,
                        },
                        header: {
                            vShow: true,
                            vDisabled: false
                        },
                        preview: {
                            vShow: true,
                            vDisabled: false
                        },
                        emailControl: {
                            vShow: false,
                            vDisabled: true
                        },
                        pushInAppControl: {
                            vShow: true,
                            vDisabled: false
                        },
                    },
                    {
                        name: "banner",
                        platform: {
                            vShow: true,
                            vDisabled: false,
                        },
                        messageType: {
                            vShow: true,
                            vDisabled: false,
                        },
                        orientation: {
                            vShow: true,
                            vDisabled: false,
                        },
                        sdk: {
                            vShow: true,
                            vDisabled: true,
                        },
                        position: {
                            vShow: true,
                            vDisabled: true,
                        },
                        title: {
                            vShow: true,
                            vDisabled: true
                        },
                        message: {
                            vShow: true,
                            vDisabled: true
                        },
                        action1Label: {
                            vShow: true,
                            vDisabled: true
                        },
                        action2Label: {
                            vShow: true,
                            vDisabled: true
                        },
                        action1Type: {
                            vShow: true,
                            vDisabled: false
                        },
                        action2Type: {
                            vShow: true,
                            vDisabled: true
                        },
                        action1Value: {
                            vShow: true,
                            vDisabled: false
                        },
                        action2Value: {
                            vShow: true,
                            vDisabled: true
                        },
                        composeTab: {
                            vShow: true,
                            vDisabled: false
                        },
                        brushTab: {
                            vShow: true,
                            vDisabled: false
                        },
                        settingsTab: {
                            vShow: true,
                            vDisabled: false
                        },
                        sendTestTab: {
                            vShow: true,
                            vDisabled: false
                        },
                        headerSection: {
                            vShow: false,
                            vDisabled: true
                        },
                        buttonSection: {
                            vShow: false,
                            vDisabled: true
                        },
                        backgroundSection: {
                            vShow: true,
                            vDisabled: false
                        },
                        frameSection: {
                            vShow: true,
                            vDisabled: false
                        },
                        galleryControl: {
                            vShow: true,
                            vDisabled: false,
                        },
                        header: {
                            vShow: true,
                            vDisabled: false
                        },
                        preview: {
                            vShow: true,
                            vDisabled: false
                        },
                        emailControl: {
                            vShow: false,
                            vDisabled: true
                        },
                        pushInAppControl: {
                            vShow: true,
                            vDisabled: false
                        },
                    },
                    {
                        name: "push",
                        platform: {
                            vShow: true,
                            vDisabled: false,
                        },
                        messageType: {
                            vShow: false,
                            vDisabled: true,
                        },
                        orientation: {
                            vShow: false,
                            vDisabled: true,
                        },
                        sdk: {
                            vShow: false,
                            vDisabled: true,
                        },
                        position: {
                            vShow: false,
                            vDisabled: true,
                        },
                        title: {
                            vShow: true,
                            vDisabled: false
                        },
                        message: {
                            vShow: true,
                            vDisabled: false
                        },
                        action1Label: {
                            vShow: false,
                            vDisabled: true
                        },
                        action2Label: {
                            vShow: false,
                            vDisabled: true
                        },
                        action1Type: {
                            vShow: true,
                            vDisabled: false
                        },
                        action2Type: {
                            vShow: false,
                            vDisabled: true
                        },
                        action1Value: {
                            vShow: true,
                            vDisabled: false
                        },
                        action2Value: {
                            vShow: false,
                            vDisabled: true
                        },
                        composeTab: {
                            vShow: true,
                            vDisabled: false
                        },
                        brushTab: {
                            vShow: false,
                            vDisabled: true
                        },
                        settingsTab: {
                            vShow: true,
                            vDisabled: false
                        },
                        sendTestTab: {
                            vShow: true,
                            vDisabled: false
                        },
                        headerSection: {
                            vShow: false,
                            vDisabled: true
                        },
                        buttonSection: {
                            vShow: false,
                            vDisabled: true
                        },
                        backgroundSection: {
                            vShow: false,
                            vDisabled: true
                        },
                        frameSection: {
                            vShow: false,
                            vDisabled: true
                        },
                        galleryControl: {
                            vShow: true,
                            vDisabled: false,
                        },
                        header: {
                            vShow: true,
                            vDisabled: false
                        },
                        preview: {
                            vShow: true,
                            vDisabled: false
                        },
                        emailControl: {
                            vShow: false,
                            vDisabled: true
                        },
                        pushInAppControl: {
                            vShow: true,
                            vDisabled: false
                        },
                    },
                    {
                        name: "email",
                        platform: {
                            vShow: false,
                            vDisabled: true,
                        },
                        messageType: {
                            vShow: false,
                            vDisabled: true,
                        },
                        orientation: {
                            vShow: false,
                            vDisabled: true,
                        },
                        sdk: {
                            vShow: false,
                            vDisabled: true,
                        },
                        position: {
                            vShow: false,
                            vDisabled: true,
                        },
                        title: {
                            vShow: false,
                            vDisabled: true
                        },
                        message: {
                            vShow: false,
                            vDisabled: true
                        },
                        action1Label: {
                            vShow: false,
                            vDisabled: true
                        },
                        action2Label: {
                            vShow: false,
                            vDisabled: true
                        },
                        action1Type: {
                            vShow: false,
                            vDisabled: true
                        },
                        action2Type: {
                            vShow: false,
                            vDisabled: true
                        },
                        action1Value: {
                            vShow: true,
                            vDisabled: false
                        },
                        action2Value: {
                            vShow: false,
                            vDisabled: true
                        },
                        composeTab: {
                            vShow: false,
                            vDisabled: true
                        },
                        brushTab: {
                            vShow: false,
                            vDisabled: true
                        },
                        settingsTab: {
                            vShow: false,
                            vDisabled: true
                        },
                        sendTestTab: {
                            vShow: true,
                            vDisabled: false
                        },
                        headerSection: {
                            vShow: false,
                            vDisabled: true
                        },
                        buttonSection: {
                            vShow: false,
                            vDisabled: true
                        },
                        backgroundSection: {
                            vShow: false,
                            vDisabled: true
                        },
                        frameSection: {
                            vShow: false,
                            vDisabled: true
                        },
                        galleryControl: {
                            vShow: false,
                            vDisabled: false,
                        },
                        header: {
                            vShow: false,
                            vDisabled: false
                        },
                        preview: {
                            vShow: false,
                            vDisabled: true
                        },
                        emailControl: {
                            vShow: true,
                            vDisabled: false
                        },
                        pushInAppControl: {
                            vShow: false,
                            vDisabled: true
                        },
                    },
                ],
                variantIndex: 0,
                langIndex: 0,
                composeTab: 1,
                variantsArray: [{
                    totalLang: [],
                    platform: {},
                    messageType: {},
                    orientation: {},
                    position: {},
                    lang: [{
                        language: 'en',
                        templateInfo: {
                            imgUrl: '',
                            template: '',
                            title: '',
                            message: '',
                            action1: {
                                label: 'Yes',
                                type: '',
                                value: '',
                            },
                            action2: {
                                label: '',
                                type: '',
                                value: '',
                            },
                            design: {
                                header: {
                                    headingColor: '#000000',
                                    contentColor: '#000000'
                                },
                                button1: {
                                    color: '#0071bd',
                                    opacity: '1',
                                    textColor: '#fcfbfb'
                                },
                                button2: {
                                    color: '#cd2026',
                                    opacity: '1',
                                    textColor: '#fcfbfb'
                                },
                                background: {
                                    color: '#fafafa',
                                    opacity: '1'
                                },
                                frame: {
                                    color: '#791283',
                                },
                            }
                        }
                    }]
                }],
                blankVariant: {
                    totalLang: [],
                    platform: {},
                    messageType: {},
                    orientation: {},
                    position: {},
                    lang: [{
                        language: 'en',
                        templateInfo: {
                            imgUrl: '',
                            template: '',
                            title: '',
                            message: '',
                            action1: {
                                label: 'Yes',
                                type: '',
                                value: '',
                            },
                            action2: {
                                label: '',
                                type: '',
                                value: '',
                            },
                            design: {
                                header: {
                                    headingColor: '#000000',
                                    contentColor: '#000000'
                                },
                                button1: {
                                    color: '#0071bd',
                                    opacity: '1',
                                    textColor: '#fcfbfb'
                                },
                                button2: {
                                    color: '#cd2026',
                                    opacity: '1',
                                    textColor: '#fcfbfb'
                                },
                                background: {
                                    color: '#fafafa',
                                    opacity: '1'
                                },
                                frame: {
                                    color: '#791283',
                                },
                            }
                        }
                    }]
                },
                blankLanguage: {
                    language: 'en',
                    templateInfo: {
                        imgUrl: '',
                        template: '',
                        title: '',
                        message: '',
                        action1: {
                            label: 'Yes',
                            type: '',
                            value: '',
                        },
                        action2: {
                            label: '',
                            type: '',
                            value: '',
                        },
                        design: {
                            header: {
                                headingColor: '#000000',
                                contentColor: '#000000'
                            },
                            button1: {
                                color: '#0071bd',
                                opacity: '1',
                                textColor: '#fcfbfb'
                            },
                            button2: {
                                color: '#cd2026',
                                opacity: '1',
                                textColor: '#fcfbfb'
                            },
                            background: {
                                color: '#fafafa',
                                opacity: '1'
                            },
                            frame: {
                                color: '#791283',
                            },
                        }
                    }
                },
                langOptions: [],
                runDirective: true,
                blockForEmail: false,
                deepLinkObj: {},
                errors: {
                    title: {
                        message: ''
                    },
                    message: {
                        message: ''
                    },
                    action1: {
                        label: {
                            message: '',
                        },
                        value: {
                            message: ''
                        }
                    },
                    action2: {
                        label: {
                            message: '',
                        },
                        value: {
                            message: ''
                        }
                    },
                    image: {
                        message: ''
                    }
                },
                sendTestErrors: {
                    users: {
                        message: '',
                    },
                    subject: {
                        message: '',
                    },
                    fromEmail: {
                        message: ''
                    }
                },
                emojis: [
                    "😀",
                    "😅",
                    "😍",
                    "🤩",
                    "😘",
                    "😛",
                    "😜",
                    "🤔",
                    "🤐",
                    "😏",
                    "😷",
                    "🤓",
                    "😲",
                    "😕",
                    "😞",
                    "😡",
                    "😠",
                    "😈",
                    "💋",
                    "❤",
                    "💯",
                    "💣",
                    "🗨",
                    "👋",
                    "🤚",
                    "🖖",
                    "👌",
                    "✌",
                    "👈",
                    "👉",
                    "👆",
                    "👇",
                    "👍",
                    "👎",
                    "👊",
                ]
            };
        },
        mounted() {
            this.initialActivity();
            this.clickEvent();
        },
        beforeDestroy() {
            $(".notice").css({display: "none"});
            this.bus.$off("passEmailContent");
            this.bus.$off('updateEditorContent');
            this.bus.$off("galleryModalReturn");
        },
        methods: {
            onSearch(search, loading) {
                loading(true);
                let campaignType = this.campaignType.name.toLowerCase() == "email" ? "email" : "notEmail";
                let deviceType = this.variantsArray[this.variantIndex].platform.name.toLowerCase();

                const payLoad = {
                    resource: "company/presets/users/" + search + "/" + campaignType + "/" + deviceType,
                    action: "get",
                };

                this.api("post",
                    this.constants.getUrl("backendApiUrl"),
                    payLoad,
                    this.authHeaders
                ).then((response) => {
                    if (response.data.meta.code === 200) {
                        loading(false);
                        this.sendTest.users = response.data.data;
                    }
                }).catch((error) => {

                });
            },
            addLangPopUp() {
                $(".language_list").slideToggle();
            },
            addLang(obj) {
                for (var i = 0; i < this.variantsArray[this.variantIndex].totalLang.length; i++) {
                    if (JSON.stringify(this.variantsArray[this.variantIndex].totalLang[i]) == JSON.stringify(obj)) {
                        break;
                    }
                }
                if (i >= this.variantsArray[this.variantIndex].totalLang.length) {
                    this.removeErrors();
                    this.variantsArray[this.variantIndex].totalLang.push(JSON.parse(JSON.stringify(obj)));
                    var langObj = JSON.parse(JSON.stringify(this.blankLanguage));
                    langObj.language = obj.value;
                    this.variantsArray[this.variantIndex].lang.push(JSON.parse(JSON.stringify(langObj)));
                    this.langIndex = i;
                    //this.ckEditorObj.editorConfig.language = this.variantsArray[this.variantIndex].lang[this.langIndex].language;
                    this.assignTemplate(this.campaignTemplates);
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
                        for (var i = 0; i < this.variantsArray[this.variantIndex].totalLang.length; i++) {
                            if (JSON.stringify(this.variantsArray[this.variantIndex].totalLang[i]) == JSON.stringify(obj)) {

                                this.runDirective = false;
                                if (this.langIndex > i || (this.langIndex == i && i == this.variantsArray[this.variantIndex].lang.length - 1)) {
                                    this.langIndex--;
                                    //this.ckEditorObj.editorConfig.language = this.variantsArray[this.variantIndex].lang[this.langIndex].language;
                                }

                                this.variantsArray[this.variantIndex].totalLang.splice(i, 1);
                                this.variantsArray[this.variantIndex].lang.splice(i, 1);
                                break;
                            }
                        }
                    }
                });

            },
            isActiveLang(obj) {
                for (var i = 0; i < this.variantsArray[this.variantIndex].totalLang.length; i++) {
                    if (JSON.stringify(this.variantsArray[this.variantIndex].totalLang[i]) == JSON.stringify(obj)) {
                        return "active";
                    }
                }
            },
            initialActivity() {

                this.bus.$on("passEmailContent", (data) => {
                    this.variantIndex = 0;
                    this.langIndex = 0;
                    this.runDirective = false;
                    this.blockForEmail = true;
                    this.variantsArray = [];
                    this.variantsArray.push(JSON.parse(JSON.stringify(this.blankVariant)));
                    this.variantsArray[this.variantIndex].lang[this.langIndex].templateInfo.template = data;
                    this.emailContent.called = !this.emailContent.called;
                });

                this.bus.$on('updateEditorContent', (data) => {
                    this.variantsArray[this.variantIndex].lang[this.langIndex].templateInfo.template = data;
                });

                this.bus.$on("galleryModalReturn", (url) => {
                    if (this.campaignType.code.toLowerCase() == 'email') {
                        this.bus.$emit("passContent", {
                            type: 'image',
                            content: url
                        });
                    } else {

                        let img = new Image();
                        img.src = url;
                        img.onload = () => {
                            let obj = {
                                width: img.width,
                                height: img.height,
                            };

                            if (this.campaignType.code.toLowerCase() == "inapp") {
                                if (this.validateImageForInApp(obj)) {
                                    this.runDirective = false;
                                    this.variantsArray[this.variantIndex].lang[this.langIndex].templateInfo.imgUrl = url;
                                    $("#pasteImage").attr("src", url);
                                    $(".tp_img_text span, .img_uploader label").css({
                                        background: "none"
                                    });
                                }
                            } else if (this.campaignType.code.toLowerCase() == "push") {
                                this.runDirective = false;
                                this.variantsArray[this.variantIndex].lang[this.langIndex].templateInfo.imgUrl = url;
                                $("#pasteImage").attr("src", url);
                            }
                        }
                    }
                });
            },
            validateImageForInApp(obj) {

                if (this.variantsArray[this.variantIndex].messageType.name.toLowerCase() == 'banner') {
                    if (obj.width < 640 || obj.height < 1136) {

                        this.$swal({
                            position: 'top-end',
                            type: 'info',
                            title: "Minimum optimum size for banner image should be Width: 640px Height: 1136px",
                            showConfirmButton: true,
                            //toast: true
                        });

                        return false;
                    }
                } else if (this.variantsArray[this.variantIndex].messageType.name.toLowerCase() == 'full screen') {

                    if (obj.width < 600 || obj.height < 800) {

                        this.$swal({
                            position: 'top-end',
                            type: 'info',
                            title: "Minimum optimum size for full screen image should be Width: 600px Height: 800px",
                            showConfirmButton: true,
                            //toast: true
                        });

                        return false;
                    }
                } else if (this.variantsArray[this.variantIndex].messageType.name.toLowerCase() == 'dialog') {

                    if (obj.width < 100 || obj.height < 100) {

                        this.$swal({
                            position: 'top-end',
                            type: 'info',
                            title: "Minimum optimum size for dialog image should be Width: 100px Height: 100px",
                            showConfirmButton: true,
                            //toast: true
                        });

                        return false;
                    }
                }
                return true;
            },
            clickEvent() {
                this.composeTab = 1;
                $("body").click(function () {
                    $(".variant_drop .list_none").slideUp();
                    $(".language_list").slideUp();
                });

                $('.iframe_el').load(function () {
                    let iframe = $('.iframe_el').contents();

                    iframe.find("a").click(function (e) {
                        e.preventDefault();
                    });
                });


                let url = window.location.href.replace('#' + this.$route.path, '') + '/src/assets/css/templates.css';
                fetch(
                    url
                ).then((response) => {
                    response.text().then((text) => {
                        this.iframeStyleLink = "<style>" + text + "</style>";
                    });
                });
            },
            addVariant() {
                $(".variant_drop .list_none").slideToggle();
            },
            shiftComposeTabs(num) {
                this.composeTab = num;
            },
            popVariant(index) {

                if (this.variantsArray.length > 1) {

                    this.$swal({
                        title: "Are you sure ?  You want to remove this Variant !",
                        type: "warning",
                        showCancelButton: true,
                        confirmButtonColor: "#3085d6",
                        confirmButtonText: "Yes, Do it!"
                    }).then((result) => {

                        if (result.value) {

                            this.runDirective = false;
                            if (this.variantIndex > index || (this.variantIndex == index && index == this.variantsArray.length - 1)) {
                                this.variantIndex--;
                            }
                            this.variantsArray.splice(index, 1);
                            this.langIndex = 0;
                            //this.ckEditorObj.editorConfig.language = this.variantsArray[this.variantIndex].lang[this.langIndex].language;
                            //this.assignTemplate(this.campaignTemplates, true);
                            //this.currentTemplate = this.variantsArray[this.variantIndex].messageType.name.toLowerCase();
                            if (this.campaignType.name.toLowerCase() == "inapp") {
                                this.currentTemplate = this.variantsArray[this.variantIndex].messageType.name.toLowerCase();
                            }

                        }
                    });

                }
            },
            shiftVariant(index) {
                this.runDirective = false;
                //this.variantsArray[this.variantIndex].lang[this.langIndex].templateInfo.template = $("#bind_template").html();
                this.variantIndex = index;
                this.langIndex = 0;
                //this.ckEditorObj.editorConfig.language = this.variantsArray[this.variantIndex].lang[this.langIndex].language;
                //this.assignTemplate(this.campaignTemplates, true);

                //this.currentTemplate = this.variantsArray[this.variantIndex].messageType.name.toLowerCase();
                if (this.campaignType.name.toLowerCase() == "inapp") {
                    this.currentTemplate = this.variantsArray[this.variantIndex].messageType.name.toLowerCase();
                }

            },
            copyVariant(obj, isBlankVariant) {
                if (this.variantsArray.length < 4) {
                    this.removeErrors();
                    this.variantsArray.push(JSON.parse(JSON.stringify(obj)));
                    this.variantIndex = this.variantsArray.length - 1;

                    if (this.campaignType.code.toLowerCase() == 'email')
                        this.runDirective = false;

                    this.langIndex = 0;

                    //this.ckEditorObj.editorConfig.language = this.variantsArray[this.variantIndex].lang[this.langIndex].language;
                    if (isBlankVariant) {
                        this.runDirective = false;
                        this.assignTemplate(this.campaignTemplates, true);
                    }
                    //this.currentTemplate = this.variantsArray[this.variantIndex].messageType.name.toLowerCase();
                    if (this.campaignType.name.toLowerCase() == "inapp") {
                        this.currentTemplate = this.variantsArray[this.variantIndex].messageType.name.toLowerCase();
                    }
                }
                $(".variant_drop .list_none").slideToggle();
            },
            switchLang(lang) {
                this.runDirective = false;
                //this.variantsArray[this.variantIndex].lang[this.langIndex].templateInfo.template = $("#bind_template").html();
                let langArr = this.variantsArray[this.variantIndex].lang;
                for (let i = 0; i < langArr.length; i++) {
                    if (langArr[i].language == lang) {
                        this.langIndex = i;
                        //this.ckEditorObj.editorConfig.language = this.variantsArray[this.variantIndex].lang[this.langIndex].language;
                        break;
                    }
                }
            },
            changeMessageType() {
                this.runDirective = false;
                this.langIndex = 0;
                for (var i = 0; i < this.variantsArray[this.variantIndex].totalLang.length; i++) {
                    if (this.variantsArray[this.variantIndex].totalLang[i].value != "en") {
                        this.variantsArray[this.variantIndex].totalLang.splice(i, 1);
                        i--;
                    }
                }
                this.variantsArray[this.variantIndex].lang = [];
                this.variantsArray[this.variantIndex].lang.push(JSON.parse(JSON.stringify(this.blankLanguage)));
                this.assignTemplate(this.campaignTemplates);
            },
            assignTemplate(arr, changeAnyWay = false) {
                for (var i = 0; i < arr.length; i++) {

                    if (arr[i].type.toLowerCase() == this.campaignType.name.toLowerCase()) {

                        var children = arr[i].children;
                        for (var j = 0; j < children.length; j++) {

                            if (this.campaignType.name.toLowerCase() == "inapp") {
                                if (children[j].childType.toLowerCase() == this.variantsArray[this.variantIndex].messageType.name.toLowerCase()) {
                                    this.currentTemplate = children[j].childType.toLowerCase();

                                    if (this.variantsArray[this.variantIndex].lang[this.langIndex].templateInfo.template == "" || changeAnyWay) {
                                        this.variantsArray[this.variantIndex].lang[this.langIndex].templateInfo.template = children[j].content[0].content;
                                    }
                                    break;
                                }
                            } else if (this.campaignType.name.toLowerCase() == "push") {
                                this.currentTemplate = this.campaignType.name.toLowerCase();
                                if (this.variantsArray[this.variantIndex].lang[this.langIndex].templateInfo.template == "" || changeAnyWay) {
                                    this.variantsArray[this.variantIndex].lang[this.langIndex].templateInfo.template = children[j].content[0].content;
                                }
                                break;
                            } else if (this.campaignType.name.toLowerCase() == "email") {
                                this.currentTemplate = this.campaignType.name.toLowerCase();
                                /*if (this.variantsArray[this.variantIndex].lang[this.langIndex].templateInfo.template == "" || changeAnyWay) {
                                    this.variantsArray[this.variantIndex].lang[this.langIndex].templateInfo.template = this.emailContent;
                                }*/
                            }
                        }

                        if (j < children.length) {
                            break;
                        }

                    }
                }
            },
            changeTemplate(obj) {

                if (!this.rules[this.ruleIndex].title.vDisabled) {
                    $("#headingT").text(obj.title != "" ? obj.title : 'Dummy Title');
                    if (this.variantsArray[this.variantIndex].totalLang.length > 0)
                        $("#headingT").attr("dir", this.variantsArray[this.variantIndex].totalLang[this.langIndex].dir);
                }

                if (!this.rules[this.ruleIndex].message.vDisabled) {
                    $("#messageT").text(obj.message != "" ? obj.message : "Dummy message");
                    if (this.variantsArray[this.variantIndex].totalLang.length > 0)
                        $("#messageT").attr("dir", this.variantsArray[this.variantIndex].totalLang[this.langIndex].dir);
                }


                if (!this.rules[this.ruleIndex].action1Label.vDisabled) {
                    $("#bt1T").text(obj.action1.label);
                    if (this.variantsArray[this.variantIndex].totalLang.length > 0)
                        $("#bt1T").attr("dir", this.variantsArray[this.variantIndex].totalLang[this.langIndex].dir);
                }

                if (!this.rules[this.ruleIndex].action1Type.vDisabled) {
                    $("#bt1T").attr("action", obj.action1.type.name);
                }

                if (!this.rules[this.ruleIndex].action1Value.vDisabled) {

                    if (obj.action1.type.name != undefined && obj.action1.type.name.toLowerCase() == 'close') {
                        obj.action1.value = 'close-me';
                    }

                    $("#bt1T").attr("href", obj.action1.value);
                }

                if (!this.rules[this.ruleIndex].action2Label.vDisabled) {
                    $("#bt2T").text(obj.action2.label);
                    if (this.variantsArray[this.variantIndex].totalLang.length > 0)
                        $("#bt2T").attr("dir", this.variantsArray[this.variantIndex].totalLang[this.langIndex].dir);
                }

                if (!this.rules[this.ruleIndex].action2Type.vDisabled) {
                    $("#bt2T").attr("action", obj.action2.type.name);
                }

                if (!this.rules[this.ruleIndex].action2Value.vDisabled) {

                    if (obj.action2.type.name != undefined && obj.action2.type.name.toLowerCase() == 'close') {
                        obj.action2.value = 'close-me';
                    }

                    $("#bt2T").attr("href", obj.action2.value);
                }

                if (!this.rules[this.ruleIndex].brushTab.vDisabled) {

                    if (!this.rules[this.ruleIndex].headerSection.vDisabled) {
                        $("#headingT").css({color: obj.design.header.headingColor});
                        $("#messageT").css({color: obj.design.header.contentColor});
                    }

                    if (!this.rules[this.ruleIndex].buttonSection.vDisabled) {
                        $("#bt1T").css({
                            color: obj.design.button1.textColor,
                            background: obj.design.button1.color,
                            opacity: obj.design.button1.opacity
                        });

                        $("#bt2T").css({
                            color: obj.design.button2.textColor,
                            background: obj.design.button2.color,
                            opacity: obj.design.button2.opacity
                        });
                    }

                    if (!this.rules[this.ruleIndex].backgroundSection.vDisabled) {
                        let selector = "";

                        if (this.variantsArray[this.variantIndex].messageType.name != undefined && this.variantsArray[this.variantIndex].messageType.name.toLowerCase() == 'dialog') {
                            selector = "#bind_template .templete .dialogue_height";
                        } else {
                            selector = "#bind_template .templete";
                        }

                        $(selector).css({
                            background: obj.design.background.color,
                            opacity: obj.design.background.opacity
                        });
                    }

                    if (!this.rules[this.ruleIndex].frameSection.vDisabled) {
                        let selector = "";

                        if (this.variantsArray[this.variantIndex].messageType.name != undefined && this.variantsArray[this.variantIndex].messageType.name.toLowerCase() == 'dialog') {
                            selector = "#bind_template .templete .dialogue_height";
                        } else {
                            selector = "#bind_template .templete";
                        }

                        $(selector).css({
                            border: "1px solid " + obj.design.frame.color,
                        });
                    }

                    if (!this.rules[this.ruleIndex].position.vDisabled) {
                        $("#positionDiv").css({
                            'vertical-align': this.variantsArray[this.variantIndex].position.name != undefined ? this.variantsArray[this.variantIndex].position.name.toLowerCase() : "top"
                        });
                    }
                }

                if (this.runDirective) {
                    this.variantsArray[this.variantIndex].lang[this.langIndex].templateInfo.template = $("#bind_template").html();
                }
                this.runDirective = true;
            },
            action1Qualification(action) {
                if (this.campaignType.code.toLowerCase() == 'push') {
                    if (action.name.toLowerCase() == 'deep link') {
                        return true;
                    }
                    return false;
                }
                return true;
            },
            submitStep() {
                this.locked = false;
                if (this.validateAll()) {

                    this.bus.$emit("disableNextBtn", true);

                    const payLoad = {
                        resource: "campaigns",
                        action: "update",
                        id: this.campaignId,
                        data: {
                            step: "compose",
                            variants: btoa(unescape(encodeURIComponent((JSON.stringify(this.variantsArray)))))
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
                            this.emitVariantDistribution(response.data.data.variants);
                            this.updateStep(this.variantsArray);
                            this.$emit("goNext");
                        }
                    }).catch((error) => {

                    });
                }
            },
            emitVariantDistribution(variants) {
                let variantsDistribution = [];
                for (let i = 0; i < variants.length; i++) {
                    let obj = {
                        color: this.colors[i],
                        value: variants[i].distribution,
                    };
                    variantsDistribution.push(obj);
                }
                this.$emit("totalVariants", variantsDistribution);
            },
            activateAttributeModal(id) {
                this.attributeListingModal.on = !this.attributeListingModal.on;
                this.currentCode = id;
            },
            activateEmojiModal(id) {
                $("#emoji_listing_modal").modal("show");
                this.currentCode = id;
            },
            activateGalleryModal() {
                if (this.campaignType.code.toLowerCase() == "inapp") {
                    $(".notice").css({display: "block"});
                }

                this.bus.$emit('galleryModalOpen', false);
            },
            pasteCode(code) {

                this.attributeListingModal.off = !this.attributeListingModal.off;
                if (this.campaignType.code.toLowerCase() == 'email') {
                    this.bus.$emit("passContent", {
                        type: 'code',
                        content: code
                    });
                } else {

                    let cursorPosition = $("#" + this.currentCode).prop("selectionStart");
                    let value = $("#" + this.currentCode).val();
                    let textBefore = value.substring(0, cursorPosition);
                    let textAfter = value.substring(cursorPosition, value.length);
                    let attributeSpan = '&lrm;[[$' + code + ']]&lrm;';

                    if (this.currentCode == "msg_field") {
                        this.variantsArray[this.variantIndex].lang[this.langIndex].templateInfo.message = textBefore + this.htmlDecode(attributeSpan) + textAfter;
                    } else {
                        this.variantsArray[this.variantIndex].lang[this.langIndex].templateInfo.title = textBefore + this.htmlDecode(attributeSpan) + textAfter;
                    }
                }

            },
            pasteEmoji(code) {
                let cursorPosition = $("#" + this.currentCode).prop("selectionStart");
                let value = $("#" + this.currentCode).val();
                let textBefore = value.substring(0, cursorPosition);
                let textAfter = value.substring(cursorPosition, value.length);
                let attributeSpan = '&lrm;' + code + '&lrm;';

                if (this.currentCode == "msg_field") {
                    this.variantsArray[this.variantIndex].lang[this.langIndex].templateInfo.message = textBefore + this.htmlDecode(attributeSpan) + textAfter;
                } else {
                    this.variantsArray[this.variantIndex].lang[this.langIndex].templateInfo.title = textBefore + this.htmlDecode(attributeSpan) + textAfter;
                }
                $("#emoji_listing_modal").modal("hide");
            },
            htmlDecode(value) {
                return $('#decode_html_div').html(value).text();
            },
            updateStep(variants) {
                this.bus.$emit("step2", {
                    variants,
                    templateCss: this.iframeStyleLink
                });
            },
            chooseActiveTab() {
                let arr = [
                    this.rules[this.ruleIndex].composeTab.vShow,
                    this.rules[this.ruleIndex].brushTab.vShow,
                    this.rules[this.ruleIndex].settingsTab.vShow,
                    this.rules[this.ruleIndex].sendTestTab.vShow,
                ];

                for (let i = 0; i < arr.length; i++) {
                    if (!arr[i]) {
                        this.composeTab = i + 2;
                    } else {
                        break
                    }
                }

            },
            sendNotification() {
                this.lockedSendTest = false;
                if (this.validateSendTest() && this.validateCurrentSuccess(this.variantIndex, this.langIndex)) {
                    let payLoad = {
                        resource: "notifications",
                        method: "send",
                        data: {
                            items: [],
                            title: this.variantsArray[this.variantIndex].lang[this.langIndex].templateInfo.title,
                            message: this.campaignType.name.toLowerCase() != "email" ? this.variantsArray[this.variantIndex].lang[this.langIndex].templateInfo.message : this.variantsArray[this.variantIndex].lang[this.langIndex].templateInfo.template,
                            //message: this.campaignType.name.toLowerCase() == "push" ? this.variantsArray[this.variantIndex].lang[this.langIndex].templateInfo.message : this.variantsArray[this.variantIndex].lang[this.langIndex].templateInfo.template,
                            html_content: this.variantsArray[this.variantIndex].lang[this.langIndex].templateInfo.template,
                            platform: this.variantsArray[this.variantIndex].platform.name.toLowerCase(),
                            type: this.campaignType.name.toLowerCase(),
                            message_type: this.variantsArray[this.variantIndex].messageType.name.toLowerCase(),
                            message_position: this.variantsArray[this.variantIndex].position.name.toLowerCase(),
                            email: {
                                from_email: this.emailContent.fromEmail,
                                subject: this.emailContent.subject
                            },
                            action: {
                                type: this.variantsArray[this.variantIndex].lang[this.langIndex].templateInfo.action1.type.name.toLowerCase() == "deep link" ? this.variantsArray[this.variantIndex].lang[this.langIndex].templateInfo.action1.type.name : this.variantsArray[this.variantIndex].lang[this.langIndex].templateInfo.action1.type.name.toLowerCase(),
                                value: this.variantsArray[this.variantIndex].lang[this.langIndex].templateInfo.action1.value
                            },
                            auto_close: (this.campaignType.name.toLowerCase() != "inapp" || this.variantsArray[this.variantIndex].messageType.name.toLowerCase() == "banner" || this.variantsArray[this.variantIndex].lang[this.langIndex].templateInfo.action2.label == "" || this.variantsArray[this.variantIndex].lang[this.langIndex].templateInfo.action2.label == null) ? true : false,
                            image_url: this.variantsArray[this.variantIndex].lang[this.langIndex].templateInfo.imgUrl,
                        }
                    };

                    for (let i = 0; i < this.sendTest.selectedUsers.length; i++) {
                        payLoad.data.items.push(this.sendTest.selectedUsers[i].value);
                    }

                    payLoad.data.message = btoa(unescape(encodeURIComponent(payLoad.data.message)));
                    payLoad.data.html_content = btoa(unescape(encodeURIComponent(payLoad.data.html_content)));

                    this.sendTest.disable = true;
                    this.api("post",
                        this.constants.getUrl("backendApiUrl"),
                        payLoad,
                        this.authHeaders,
                    ).then((response) => {
                        if (response.data.meta.status == 'success') {
                            let notificationResponseArr = response.data.data;

                            let str = '';
                            for (let i = 0; i < notificationResponseArr.length; i++) {
                                if (notificationResponseArr[i].status == 200) {
                                    str += (i + 1) + '- Notification sent successfully. \n';
                                } else {
                                    str += (i + 1) + '- ' + notificationResponseArr[i].error + '\n';
                                }
                            }

                            this.$swal({
                                position: 'top-end',
                                title: str,
                                showConfirmButton: true,
                            });

                        }
                        this.sendTest.disable = false;
                    }).catch((error) => {
                        this.sendTest.disable = false;
                    });

                }
            },
            validateAll() {
                let success = true;
                let variantIndex = -1;
                let langIndex = -1;
                if (this.campaignType.code.toLowerCase() != 'email') {
                    for (let i = 0; i < this.variantsArray.length; i++) {
                        for (let j = 0; j < this.variantsArray[i].lang.length; j++) {
                            if ((this.campaignType.code.toLowerCase() == 'push') || (this.campaignType.code.toLowerCase() == 'inapp' && this.variantsArray[i].messageType.name.toLowerCase() != 'banner')) {
                                if (this.variantsArray[i].lang[j].templateInfo.title == '') {
                                    success = false;
                                    this.errors.title.message = 'Title is required';

                                } else {
                                    this.errors.title.message = '';
                                }

                                if (this.variantsArray[i].lang[j].templateInfo.message == '') {
                                    success = false;
                                    this.errors.message.message = 'Message is required';

                                } else {
                                    this.errors.message.message = '';
                                }
                            } else {
                                this.errors.title.message = '';
                                this.errors.message.message = '';
                            }

                            if (this.campaignType.code.toLowerCase() == 'inapp' && (this.variantsArray[i].messageType.name.toLowerCase() == 'dialog' || this.variantsArray[i].messageType.name.toLowerCase() == 'full screen')) {
                                if (this.variantsArray[i].lang[j].templateInfo.action1.label == '') {
                                    success = false;
                                    this.errors.action1.label.message = 'action1 is required';

                                } else {
                                    this.errors.action1.label.message = '';
                                }

                                /*if (this.variantsArray[i].lang[j].templateInfo.action2.label == '') {
                                    success = false;
                                    this.errors.action2.label.message = 'action2 is required';

                                } else {
                                    this.errors.action2.label.message = '';
                                }*/

                                if (this.variantsArray[i].lang[j].templateInfo.action2.label != '' && this.variantsArray[i].lang[j].templateInfo.action2.type.name.toLowerCase() == 'url') {

                                    let expression = /(http:\/\/www\.|https:\/\/www\.|http:\/\/|https:\/\/)[a-z0-9]+([\-\.]{1}[a-z0-9]+)*\.[a-z]{2,5}(:[0-9]{1,5})?(\/.*)?$/gi;
                                    if (!expression.test(this.variantsArray[i].lang[j].templateInfo.action2.value)) {
                                        this.errors.action2.value.message = 'Invalid url';
                                        success = false;
                                    } else {
                                        this.errors.action2.value.message = '';
                                    }

                                } else if (this.variantsArray[i].lang[j].templateInfo.action2.label != '' && this.variantsArray[i].lang[j].templateInfo.action2.type.name.toLowerCase() != 'close') {

                                    let expression1 = /[A-Za-z0-9]+:\/\/([A-Za-z0-9]+|\/)+/gi;
                                    let expression2 = /(http:\/\/www\.|https:\/\/www\.|http:\/\/|https:\/\/)[a-z0-9]+([\-\.]{1}[a-z0-9]+)*\.[a-z]{2,5}(:[0-9]{1,5})?(\/.*)?$/gi;
                                    if ((!expression1.test(this.variantsArray[i].lang[j].templateInfo.action2.value)) || (expression2.test(this.variantsArray[i].lang[j].templateInfo.action2.value))) {
                                        if (!(this.campaignType.code.toLowerCase() == 'push' && (this.variantsArray[i].lang[j].templateInfo.action2.value == "" || this.variantsArray[i].lang[j].templateInfo.action2.value == null))) {
                                            this.errors.action2.value.message = 'Invalid ' + this.variantsArray[i].lang[j].templateInfo.action2.type.name.toLowerCase();
                                            success = false;
                                        } else {
                                            this.errors.action2.value.message = '';
                                        }
                                    } else {
                                        this.errors.action2.value.message = '';
                                    }

                                } else {
                                    this.errors.action2.value.message = '';
                                }

                            } else {
                                this.errors.action1.label.message = '';
                                this.errors.action2.label.message = '';
                                this.errors.action2.value.message = '';
                            }

                            if (this.variantsArray[i].lang[j].templateInfo.action1.type.name.toLowerCase() == 'url') {

                                let expression = /(http:\/\/www\.|https:\/\/www\.|http:\/\/|https:\/\/)[a-z0-9]+([\-\.]{1}[a-z0-9]+)*\.[a-z]{2,5}(:[0-9]{1,5})?(\/.*)?$/gi;
                                if (!expression.test(this.variantsArray[i].lang[j].templateInfo.action1.value)) {
                                    this.errors.action1.value.message = 'Invalid url';
                                    success = false;
                                } else {
                                    this.errors.action1.value.message = '';
                                }

                            } else if (this.variantsArray[i].lang[j].templateInfo.action1.type.name.toLowerCase() != 'close') {

                                let expression1 = /[A-Za-z0-9]+:\/\/([A-Za-z0-9]+|\/)+/gi;
                                let expression2 = /(http:\/\/www\.|https:\/\/www\.|http:\/\/|https:\/\/)[a-z0-9]+([\-\.]{1}[a-z0-9]+)*\.[a-z]{2,5}(:[0-9]{1,5})?(\/.*)?$/gi;
                                if ((!expression1.test(this.variantsArray[i].lang[j].templateInfo.action1.value)) || (expression2.test(this.variantsArray[i].lang[j].templateInfo.action1.value))) {
                                    if (!(this.campaignType.code.toLowerCase() == 'push' && (this.variantsArray[i].lang[j].templateInfo.action1.value == "" || this.variantsArray[i].lang[j].templateInfo.action1.value == null))) {
                                        this.errors.action1.value.message = 'Invalid ' + this.variantsArray[i].lang[j].templateInfo.action1.type.name.toLowerCase();
                                        success = false;
                                    } else {
                                        this.errors.action1.value.message = '';
                                    }
                                } else {
                                    this.errors.action1.value.message = '';
                                }

                            } else {
                                this.errors.action1.value.message = '';
                            }

                            if (this.campaignType.code.toLowerCase() == 'inapp'/*this.campaignType.code.toLowerCase() != 'email'*/) {
                                if (this.variantsArray[i].lang[j].templateInfo.imgUrl == "") {
                                    success = false;
                                    this.errors.image.message = "Image is Required";
                                } else {
                                    this.errors.image.message = "";
                                }
                            } else {
                                this.errors.image.message = "";
                            }

                            if (success == false) {
                                langIndex = j;
                                break;
                            }
                        }


                        if (success == false) {
                            variantIndex = i;
                            break;
                        }
                    }
                }

                if (success == false) {
                    this.runDirective = false;
                    this.variantIndex = variantIndex;
                    this.langIndex = langIndex;

                    if (this.campaignType.name.toLowerCase() == "inapp") {
                        this.currentTemplate = this.variantsArray[this.variantIndex].messageType.name.toLowerCase();
                        for (let i = 0; i < this.rules.length; i++) {
                            if (this.rules[i].name.toLowerCase() == this.currentTemplate.toLowerCase()) {
                                this.ruleIndex = i;
                                break;
                            }
                        }
                        this.chooseActiveTab();
                    }
                }
                return success;
            },
            validateCurrent(i, j) {
                if (this.campaignType.code.toLowerCase() != 'email') {
                    if ((this.campaignType.code.toLowerCase() == 'push') || (this.campaignType.code.toLowerCase() == 'inapp' && this.variantsArray[i].messageType.name.toLowerCase() != 'banner')) {
                        if (this.variantsArray[i].lang[j].templateInfo.title == '') {
                            this.errors.title.message = 'Title is required';

                        } else {
                            this.errors.title.message = '';
                        }

                        if (this.variantsArray[i].lang[j].templateInfo.message == '') {
                            this.errors.message.message = 'Message is required';

                        } else {
                            this.errors.message.message = '';
                        }
                    } else {
                        this.errors.title.message = '';
                        this.errors.message.message = '';
                    }

                    if (this.campaignType.code.toLowerCase() == 'inapp' && (this.variantsArray[i].messageType.name.toLowerCase() == 'dialog' || this.variantsArray[i].messageType.name.toLowerCase() == 'full screen')) {
                        if (this.variantsArray[i].lang[j].templateInfo.action1.label == '') {
                            this.errors.action1.label.message = 'action1 is required';

                        } else {
                            this.errors.action1.label.message = '';
                        }

                        /*if (this.variantsArray[i].lang[j].templateInfo.action2.label == '') {
                            this.errors.action2.label.message = 'action2 is required';

                        } else {
                            this.errors.action2.label.message = '';
                        }*/

                        if (this.variantsArray[i].lang[j].templateInfo.action2.label != '' && this.variantsArray[i].lang[j].templateInfo.action2.type.name.toLowerCase() == 'url') {

                            let expression = /(http:\/\/www\.|https:\/\/www\.|http:\/\/|https:\/\/)[a-z0-9]+([\-\.]{1}[a-z0-9]+)*\.[a-z]{2,5}(:[0-9]{1,5})?(\/.*)?$/gi;
                            if (!expression.test(this.variantsArray[i].lang[j].templateInfo.action2.value)) {
                                this.errors.action2.value.message = 'Invalid url';
                            } else {
                                this.errors.action2.value.message = '';
                            }

                        } else if (this.variantsArray[i].lang[j].templateInfo.action2.label != '' && this.variantsArray[i].lang[j].templateInfo.action2.type.name.toLowerCase() != 'close') {

                            let expression1 = /[A-Za-z0-9]+:\/\/([A-Za-z0-9]+|\/)+/gi;
                            let expression2 = /(http:\/\/www\.|https:\/\/www\.|http:\/\/|https:\/\/)[a-z0-9]+([\-\.]{1}[a-z0-9]+)*\.[a-z]{2,5}(:[0-9]{1,5})?(\/.*)?$/gi;
                            if ((!expression1.test(this.variantsArray[i].lang[j].templateInfo.action2.value)) || (expression2.test(this.variantsArray[i].lang[j].templateInfo.action2.value))) {
                                if (!(this.campaignType.code.toLowerCase() == 'push' && (this.variantsArray[i].lang[j].templateInfo.action2.value == "" || this.variantsArray[i].lang[j].templateInfo.action2.value == null))) {
                                    this.errors.action2.value.message = 'Invalid ' + this.variantsArray[i].lang[j].templateInfo.action2.type.name.toLowerCase();
                                } else {
                                    this.errors.action2.value.message = '';
                                }
                            } else {
                                this.errors.action2.value.message = '';
                            }

                        } else {
                            this.errors.action2.value.message = '';
                        }

                    } else {
                        this.errors.action1.label.message = '';
                        this.errors.action2.label.message = '';
                        this.errors.action2.value.message = '';
                    }

                    if (this.variantsArray[i].lang[j].templateInfo.action1.type.name.toLowerCase() == 'url') {

                        let expression = /(http:\/\/www\.|https:\/\/www\.|http:\/\/|https:\/\/)[a-z0-9]+([\-\.]{1}[a-z0-9]+)*\.[a-z]{2,5}(:[0-9]{1,5})?(\/.*)?$/gi;
                        if (!expression.test(this.variantsArray[i].lang[j].templateInfo.action1.value)) {
                            this.errors.action1.value.message = 'Invalid url';
                        } else {
                            this.errors.action1.value.message = '';
                        }

                    } else if (this.variantsArray[i].lang[j].templateInfo.action1.type.name.toLowerCase() != 'close') {

                        let expression1 = /[A-Za-z0-9]+:\/\/([A-Za-z0-9]+|\/)+/gi;
                        let expression2 = /(http:\/\/www\.|https:\/\/www\.|http:\/\/|https:\/\/)[a-z0-9]+([\-\.]{1}[a-z0-9]+)*\.[a-z]{2,5}(:[0-9]{1,5})?(\/.*)?$/gi;
                        if ((!expression1.test(this.variantsArray[i].lang[j].templateInfo.action1.value)) || (expression2.test(this.variantsArray[i].lang[j].templateInfo.action1.value))) {
                            if (!(this.campaignType.code.toLowerCase() == 'push' && (this.variantsArray[i].lang[j].templateInfo.action1.value == "" || this.variantsArray[i].lang[j].templateInfo.action1.value == null))) {
                                this.errors.action1.value.message = 'Invalid ' + this.variantsArray[i].lang[j].templateInfo.action1.type.name.toLowerCase();
                            } else {
                                this.errors.action1.value.message = '';
                            }
                        } else {
                            this.errors.action1.value.message = '';
                        }

                    } else {
                        this.errors.action1.value.message = '';
                    }

                    if (this.campaignType.code.toLowerCase() == 'inapp'/*this.campaignType.code.toLowerCase() != 'email'*/) {
                        if (this.variantsArray[this.variantIndex].lang[this.langIndex].templateInfo.imgUrl == "") {
                            this.errors.image.message = "Image is Required";
                        } else {
                            this.errors.image.message = "";
                        }
                    } else {
                        this.errors.image.message = "";
                    }
                }
            },
            validateCurrentSuccess(i, j) {
                let success = true;
                if (this.campaignType.code.toLowerCase() != 'email') {
                    if ((this.campaignType.code.toLowerCase() == 'push') || (this.campaignType.code.toLowerCase() == 'inapp' && this.variantsArray[i].messageType.name.toLowerCase() != 'banner')) {
                        if (this.variantsArray[i].lang[j].templateInfo.title == '') {
                            success = false;
                            this.errors.title.message = 'Title is required';

                        } else {
                            this.errors.title.message = '';
                        }

                        if (this.variantsArray[i].lang[j].templateInfo.message == '') {
                            success = false;
                            this.errors.message.message = 'Message is required';

                        } else {
                            this.errors.message.message = '';
                        }
                    } else {
                        this.errors.title.message = '';
                        this.errors.message.message = '';
                    }

                    if (this.campaignType.code.toLowerCase() == 'inapp' && (this.variantsArray[i].messageType.name.toLowerCase() == 'dialog' || this.variantsArray[i].messageType.name.toLowerCase() == 'full screen')) {
                        if (this.variantsArray[i].lang[j].templateInfo.action1.label == '') {
                            success = false;
                            this.errors.action1.label.message = 'action1 is required';

                        } else {
                            this.errors.action1.label.message = '';
                        }

                        /*if (this.variantsArray[i].lang[j].templateInfo.action2.label == '') {
                            success = false;
                            this.errors.action2.label.message = 'action2 is required';

                        } else {
                            this.errors.action2.label.message = '';
                        }*/

                        if (this.variantsArray[i].lang[j].templateInfo.action2.label != '' && this.variantsArray[i].lang[j].templateInfo.action2.type.name.toLowerCase() == 'url') {

                            let expression = /(http:\/\/www\.|https:\/\/www\.|http:\/\/|https:\/\/)[a-z0-9]+([\-\.]{1}[a-z0-9]+)*\.[a-z]{2,5}(:[0-9]{1,5})?(\/.*)?$/gi;
                            if (!expression.test(this.variantsArray[i].lang[j].templateInfo.action2.value)) {
                                success = false;
                                this.errors.action2.value.message = 'Invalid url';
                            } else {
                                this.errors.action2.value.message = '';
                            }

                        } else if (this.variantsArray[i].lang[j].templateInfo.action2.label != '' && this.variantsArray[i].lang[j].templateInfo.action2.type.name.toLowerCase() != 'close') {

                            let expression1 = /[A-Za-z0-9]+:\/\/([A-Za-z0-9]+|\/)+/gi;
                            let expression2 = /(http:\/\/www\.|https:\/\/www\.|http:\/\/|https:\/\/)[a-z0-9]+([\-\.]{1}[a-z0-9]+)*\.[a-z]{2,5}(:[0-9]{1,5})?(\/.*)?$/gi;
                            if ((!expression1.test(this.variantsArray[i].lang[j].templateInfo.action2.value)) || (expression2.test(this.variantsArray[i].lang[j].templateInfo.action2.value))) {
                                if (!(this.campaignType.code.toLowerCase() == 'push' && (this.variantsArray[i].lang[j].templateInfo.action2.value == "" || this.variantsArray[i].lang[j].templateInfo.action2.value == null))) {
                                    success = false;
                                    this.errors.action2.value.message = 'Invalid ' + this.variantsArray[i].lang[j].templateInfo.action2.type.name.toLowerCase();
                                } else {
                                    this.errors.action2.value.message = '';
                                }
                            } else {
                                this.errors.action2.value.message = '';
                            }

                        } else {
                            this.errors.action2.value.message = '';
                        }

                    } else {
                        this.errors.action1.label.message = '';
                        this.errors.action2.label.message = '';
                        this.errors.action2.value.message = '';
                    }

                    if (this.variantsArray[i].lang[j].templateInfo.action1.type.name.toLowerCase() == 'url') {

                        let expression = /(http:\/\/www\.|https:\/\/www\.|http:\/\/|https:\/\/)[a-z0-9]+([\-\.]{1}[a-z0-9]+)*\.[a-z]{2,5}(:[0-9]{1,5})?(\/.*)?$/gi;
                        if (!expression.test(this.variantsArray[i].lang[j].templateInfo.action1.value)) {
                            success = false;
                            this.errors.action1.value.message = 'Invalid url';
                        } else {
                            this.errors.action1.value.message = '';
                        }

                    } else if (this.variantsArray[i].lang[j].templateInfo.action1.type.name.toLowerCase() != 'close') {

                        let expression1 = /[A-Za-z0-9]+:\/\/([A-Za-z0-9]+|\/)+/gi;
                        let expression2 = /(http:\/\/www\.|https:\/\/www\.|http:\/\/|https:\/\/)[a-z0-9]+([\-\.]{1}[a-z0-9]+)*\.[a-z]{2,5}(:[0-9]{1,5})?(\/.*)?$/gi;
                        if ((!expression1.test(this.variantsArray[i].lang[j].templateInfo.action1.value)) || (expression2.test(this.variantsArray[i].lang[j].templateInfo.action1.value))) {
                            if (!(this.campaignType.code.toLowerCase() == 'push' && (this.variantsArray[i].lang[j].templateInfo.action1.value == "" || this.variantsArray[i].lang[j].templateInfo.action1.value == null))) {
                                success = false;
                                this.errors.action1.value.message = 'Invalid ' + this.variantsArray[i].lang[j].templateInfo.action1.type.name.toLowerCase();
                            } else {
                                this.errors.action1.value.message = '';
                            }
                        } else {
                            this.errors.action1.value.message = '';
                        }

                    } else {
                        this.errors.action1.value.message = '';
                    }

                    if (this.campaignType.code.toLowerCase() == 'inapp'/*this.campaignType.code.toLowerCase() != 'email'*/) {
                        if (this.variantsArray[this.variantIndex].lang[this.langIndex].templateInfo.imgUrl == "") {
                            success = false;
                            this.errors.image.message = "Image is Required";
                        } else {
                            this.errors.image.message = "";
                        }
                    } else {
                        this.errors.image.message = "";
                    }
                }
                return success;
            },
            removeErrors() {
                this.locked = true;
                let errors = {
                    title: {
                        message: ''
                    },
                    message: {
                        message: ''
                    },
                    action1: {
                        label: {
                            message: '',
                        },
                        value: {
                            message: ''
                        }
                    },
                    action2: {
                        label: {
                            message: '',
                        },
                        value: {
                            message: ''
                        }
                    },
                    image: {
                        message: '',
                    }
                };
                this.errors = JSON.parse(JSON.stringify(errors));
            },
            validateSendTest() {

                let success = true;
                let errors = {
                    users: {
                        message: '',
                    },
                    subject: {
                        message: '',
                    },
                    fromEmail: {
                        message: ''
                    }
                };
                this.sendTestErrors = JSON.parse(JSON.stringify(errors));

                if (this.sendTest.selectedUsers.length == 0) {
                    success = false;
                    this.sendTestErrors.users.message = "Please select user(s)";
                }

                if (this.campaignType.name.toLowerCase() == 'email') {
                    let expression = /[A-Z0-9._%+-]+@[A-Z0-9.-]+\.[A-Z]{2,4}/igm;
                    if (!expression.test(this.emailContent.fromEmail)) {
                        success = false;
                        this.sendTestErrors.fromEmail.message = "Enter a valid email address";
                    }

                    if (this.emailContent.subject == "") {
                        success = false;
                        this.sendTestErrors.subject.message = "Subject is required";
                    }
                }

                return success;
            }
        },
        watch: {
            inAppPushData: {
                handler(currVal, oldVal) {
                    this.variantsArray[this.variantIndex].platform = currVal.platformList[0];
                    this.variantsArray[this.variantIndex].messageType = currVal.messageTypeList[0];
                    this.variantsArray[this.variantIndex].orientation = currVal.layoutTypeList[0];
                    this.variantsArray[this.variantIndex].position = currVal.devicePositionList[0];
                    this.variantsArray[this.variantIndex].lang[this.langIndex].templateInfo.action1.type = currVal.actionList[0];
                    this.variantsArray[this.variantIndex].lang[this.langIndex].templateInfo.action2.type = currVal.actionList[0];


                    for (let i = 0; i < currVal.actionList.length; i++) {
                        if (currVal.actionList[i].name.toLowerCase() == "deep link") {
                            this.deepLinkObj = currVal.actionList[i];
                            break;
                        }
                    }

                    this.blankVariant.platform = currVal.platformList[0];
                    this.blankVariant.messageType = currVal.messageTypeList[0];
                    this.blankVariant.orientation = currVal.layoutTypeList[0];
                    this.blankVariant.position = currVal.devicePositionList[0];
                    this.blankVariant.lang[this.langIndex].templateInfo.action1.type = currVal.actionList[0];
                    this.blankVariant.lang[this.langIndex].templateInfo.action2.type = currVal.actionList[0];

                    this.blankLanguage.templateInfo.action1.type = currVal.actionList[0];
                    this.blankLanguage.templateInfo.action2.type = currVal.actionList[0];
                },
                deep: true
            },
            campaignTemplates: {
                handler(currVal, oldVal) {
                    this.assignTemplate(currVal);
                },
                deep: true,
            },
            languages: {
                handler(currVal, oldVal) {
                    this.langOptions = currVal[0];

                    this.variantsArray[this.variantIndex].lang[this.langIndex].language = currVal[1].value;
                    this.variantsArray[this.variantIndex].totalLang.push(JSON.parse(JSON.stringify(currVal[1])));

                    this.blankVariant.lang[this.langIndex].language = currVal[1].value;
                    this.blankVariant.totalLang.push(JSON.parse(JSON.stringify(currVal[1])));

                    this.blankLanguage.language = currVal[1].value;

                },
                deep: true
            },
            campaignType: {
                handler(currVal, oldVal) {
                    if (currVal.code.toLowerCase() == 'push') {
                        this.variantsArray[this.variantIndex].lang[this.langIndex].templateInfo.action1.type = JSON.parse(JSON.stringify(this.deepLinkObj));
                        this.blankVariant.lang[this.langIndex].templateInfo.action1.type = JSON.parse(JSON.stringify(this.deepLinkObj));
                        this.blankLanguage.templateInfo.action1.type = JSON.parse(JSON.stringify(this.deepLinkObj));
                    }

                    if (!this.blockForEmail) {
                        this.variantIndex = 0;
                        this.langIndex = 0;
                        this.variantsArray = [];
                        this.variantsArray.push(JSON.parse(JSON.stringify(this.blankVariant)));
                    }
                    this.assignTemplate(this.campaignTemplates, true);
                },
                deep: true
            },
            currentTemplate(currVal, oldVal) {
                for (var i = 0; i < this.rules.length; i++) {
                    if (this.rules[i].name.toLowerCase() == currVal.toLowerCase()) {
                        this.ruleIndex = i;
                        break;
                    }
                }
                this.chooseActiveTab();
            },
            hitApi: {
                handler(currVal, oldVal) {
                    if (currVal.step == 2) {
                        this.submitStep();
                    }
                },
                deep: true
            },
            getCampaignStep: {
                handler(currVal, oldVal) {
                    if (JSON.stringify(currVal) != "{}") {
                        if (typeof currVal[0].lang[0] != "string") {
                            this.variantsArray = currVal;
                        } else {
                            this.variantIndex = 0;
                            this.langIndex = 0;
                            this.variantsArray[this.variantIndex].lang[this.langIndex].templateInfo.template = currVal[0].lang[0];
                        }

                        if (this.campaignType.code.toLowerCase() == "inapp") {
                            this.currentTemplate = this.variantsArray[this.variantIndex].messageType.name.toLowerCase();
                        }

                        this.emitVariantDistribution(this.variantsArray);
                        this.updateStep(this.variantsArray)
                    }
                },
                deep: true
            },
            variantsArray: {
                handler(currVal, oldVal) {
                    if (!this.locked && this.campaignType.code.toLowerCase() != 'email') {
                        this.validateCurrent(this.variantIndex, this.langIndex);
                    }
                },
                deep: true
            },
            variantIndex(currVal, oldVal) {
                if (!this.locked && this.campaignType.code.toLowerCase() != 'email') {
                    this.validateCurrent(this.variantIndex, this.langIndex);
                }
            },
            langIndex(currVal, oldVal) {
                if (!this.locked && this.campaignType.code.toLowerCase() != 'email') {
                    this.validateCurrent(this.variantIndex, this.langIndex);
                }
            },
            emailContent: {
                handler(currVal, oldVal) {
                    if (!this.lockedSendTest) {
                        this.validateSendTest();
                    }
                },
                deep: true
            },
            'sendTest.selectedUsers': {
                handler(currVal, oldVal) {
                    if (!this.lockedSendTest) {
                        this.validateSendTest();
                    }
                },
                deep: true
            }
        },
        directives: {
            manipulateTemplate: {
                bind: function (el, binding, vNode, oldVnode) {
                    vNode.context.changeTemplate(binding.value);
                },
                update: function (el, binding, vNode, oldVnode) {
                    vNode.context.changeTemplate(binding.value);
                },
            }
        }
    }
</script>

<style scoped>
    @import '../../../../../assets/css/templates.css';
</style>

<style scoped>

    #emoji_listing_table tr td {
        font-size: 30px;
        cursor: pointer;
        text-align: center;
    }

    .twocolumns .table_header_holder .dummyCol {
        width: 33% !important;
    }

    .dropdown-menu img,
    .d-center img {
        height: 15px;
        width: 23px;
        margin: 5px 6px 0 0;
    }

    .variant_drop {
        float: right;
        position: relative;
    }

    .variant_drop .list_none {
        display: none
    }

    .variant_drop ul {
        position: absolute;
        top: 100%;
        right: 0;
        width: 250px;
        border: 1px solid #6e6e6e;
        z-index: 5;
        background: #f0f0f0;
    }

    .variant_drop ul a {
        display: block;
        font-weight: 600;
        padding: 8px 10px;
        font-size: 15px;
        border-bottom: 1px solid #6e6e6e;
    }

    .variant_drop ul li:last-child a {
        border: none;
    }

    .variant_list {
        max-width: 50%;
        float: left;
        color: #fff;
        padding: 7px;
    }


    .variant_droper {
        padding: 12px 17px 12px 7px;
        float: right;
        color: #6e6e6e;
        font-weight: bold;
    }

    .variant_droper:hover,
    .variant_droper.active {
        background: #949292;
        color: #fff;
    }

    .variant_droper:hover i,
    .variant_droper.active i {
        color: #fff;
    }

    .variant_droper i {
        font-size: 14px;
        color: #2a8689;
        float: left;
        margin: 5px 10px 0;
    }

    .device_thumbs {
        padding: 3px 30px;
        background: #f0f0f0;
        overflow: hidden;
        margin: 0 0 10px;
    }

    .device_thumbs li {
        display: inline-block;
        vertical-align: middle;
        font-size: 26px;
        margin-right: 13px;
    }

    .device_thumbs a {
        color: #000;
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
    }

</style>

<style>
    .v-select.single.open .selected-tag {
        position: static !important;
        opacity: 1 !important;
    }

    .sendTest .swal2-title {
        text-align: left !important;
    }

    .sendTest .swal2-header {
        padding-bottom: 22px;
    }

    .sendTest .swal2-actions {
        position: absolute;
        bottom: 5px;
        right: 0;
    }
</style>