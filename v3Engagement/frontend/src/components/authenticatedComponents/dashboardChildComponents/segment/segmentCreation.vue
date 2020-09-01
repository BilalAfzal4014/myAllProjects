<template>
    <div>
        <div class="page_content_holder">
            <div class="padding_content">
                <div class="white_boxed_content">
                    <header class="sec_header">
                        <div class="align_right small sec_hidden">
                            <strong class="actions_droper">User Data <i class="fas fa-sort-down"></i></strong>
                            <ul class="actions_drop list_none">
                                <li><a href="createCampaign.html">Create Campaign</a></li>
                            </ul>

                            <!--<div class="page_content_holder">
                                <div class="padding_content">
                                    <div class="white_boxed_content">
                                        <header class="sec_header">
                                            <h1>Segment Details > {{segmentObj.data.name}}
                                                <div class="on_off_div"><strong>Analytic Tracking</strong>
                                                    &lt;!&ndash;<div class="an-onoffswitchContainer">
                                                        <input type="checkbox" class="an-switch an-onoffswitch-checkbox" id="an-onoffswitch">
                                                        <label class="an-onoffswitch-label" for="an-onoffswitch">
                                                            <span class="an-onoffswitch-inner">
                                                            </span>
                                                            <span class="an-onoffswitch-switch">

                                                            </span>
                                                        </label>
                                                    </div>&ndash;&gt;
                                                    <label class="switch">
                                                        <input type="checkbox" checked>
                                                        <span class="slider round"></span>
                                                    </label>
                                                    -->
                        </div>
                        <h1>Segment Details > {{segmentObj.data.name}}
                            <div class="on_off_div sec_hidden"><strong>Analytic Tracking</strong> <input
                                    class="an-switch"
                                    name="check1"
                                    type="checkbox"></div>
                        </h1>
                    </header>
                    <div class="list_holder inner_padding full_scroll p_b_50 no_min_height">
                        <div id="tab-content">
                            <div class="tab active" id="general_tab">
                                <div class="input_row">
                                    <input placeholder="Segment Title" type="text" v-model="segmentObj.data.name">
                                    <p class="error">{{errors.name.message}}</p>
                                </div>
                                <div class="input_row">
                                    <input-tag :add-tag-on-blur="true" placeholder="Enter Tags"
                                               v-bind:key="segmentObj.data.tags.length"
                                               v-model="segmentObj.data.tagsOriginal"></input-tag>

                                </div>
                                <div class="filter_area sec_hidden">
                                    <div class="filter_row">
                                        <div class="left_div filter_column">
                                            <strong>Apps Used</strong>
                                            <input class="common_checkbox" id="filter_check" type="checkbox">
                                            <label for="filter_check">Include users from all apps</label>
                                        </div>
                                        <div class="right_div filter_column">
                                            <img alt="#" class="stat_icon" src="images/stats_icon.png">
                                            <strong>0 Changes Since Last Viewed</strong>
                                        </div>
                                    </div>
                                    <div class="inner_tab_widget no_border">
                                        <div class="txt_holder">
                                            <div class="checks_row">
                                                <ul class="checks_list list_none">
                                                    <li>
                                                        <input id="check1" type="checkbox">
                                                        <label for="check1">
                                                            <i class="far fa-square"></i>
                                                            <div class="txt">
                                                                <img alt="icon" class="icon_img" src="images/icon.png">
                                                                <span>DevEngagement<br>Android</span>
                                                            </div>
                                                        </label>
                                                    </li>
                                                    <li>
                                                        <input id="check2" type="checkbox">
                                                        <label for="check2">
                                                            <i class="far fa-square"></i>
                                                            <div class="txt">
                                                                <img alt="icon" class="icon_img" src="images/icon.png">
                                                                <span>DevCoredirection<br>iOS</span>
                                                            </div>
                                                        </label>
                                                    </li>
                                                    <li>
                                                        <input id="check3" type="checkbox">
                                                        <label for="check3">
                                                            <i class="far fa-square"></i>
                                                            <div class="txt">
                                                                <img alt="icon" class="icon_img" src="images/icon.png">
                                                                <span>DevCoredirection<br>Web</span>
                                                            </div>
                                                        </label>
                                                    </li>
                                                    <li>
                                                        <input id="check4" type="checkbox">
                                                        <label for="check4">
                                                            <i class="far fa-square"></i>
                                                            <div class="txt">
                                                                <img alt="icon" class="icon_img" src="images/icon.png">
                                                                <span>DevEngagement<br>Windows Phone 8</span>
                                                            </div>
                                                        </label>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="inner_tab_widget">
                                <div class="heading_holder">
                                    <h2>Filters</h2>
                                </div>

                                <div class="input_row" style="padding:0 20px;">
                                    <div id="queryBuilderGoesHere" style="margin-top: 18px;"></div>
                                    <button class="btn btn-warning" id="btn-reset">Reset</button>
                                </div>
                            </div>
                            <div class="inner_tab_widget sec_hidden">
                                <div class="heading_holder">
                                    <h2>Reachable Users</h2>
                                </div>
                                <div class="txt_holder">
                                    <div class="inner_tab_text active" id="action-based">
                                        <div class="reachable_cols">
                                            <div class="reachable_col">
                                                <strong>Total</strong>
                                                <span>1,680,650</span>
                                            </div>
                                            <div class="reachable_col">
                                                <strong>Email</strong>
                                                <span>1,355,527</span>
                                            </div>
                                            <div class="reachable_col">
                                                <strong>Web Push</strong>
                                                <span>0</span>
                                            </div>
                                            <div class="reachable_col">
                                                <strong>iOS Push</strong>
                                                <span>322,991</span>
                                            </div>
                                            <div class="reachable_col">
                                                <strong>Android</strong>
                                                <span>370,213</span>
                                            </div>
                                            <div class="reachable_col">
                                                <strong>Window B Push</strong>
                                                <span>0</span>
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
        <div class="buttons_strip">
            <button class="right" v-on:click="submitSegment">Save</button>
        </div>
    </div>

</template>

<script>
    import InputTag from 'vue-input-tag';

    export default {
        components: {
            InputTag,
        },
        data() {
            return {
                locked: true,
                segmentFilters: [],
                segmentObj: {
                    resource: "segments",
                    action: "create",
                    data: {
                        name: "",
                        criteria: "",
                        rules: {},
                        tags: [],
                        tagsOriginal: [],
                    }
                },
                errors: {
                    name: {
                        message: ''
                    }
                },
                operators: [
                    {
                        "equal": "=",
                        "not_equal": "!=",
                        "less": "<",
                        "less_or_equal": "<=",
                        "greater": ">",
                        "greater_or_equal": ">="
                    },
                    {
                        "is_empty": "=",
                        "is_not_empty": "!="
                    },
                    {
                        "is_null": "IS",
                        "is_not_null": "IS NOT"
                    },
                    {
                        "in": "IN",
                        "not_in": "NOT IN"
                    },
                    {
                        "between": "BETWEEN",
                        "not_between": "NOT BETWEEN"
                    },
                    {
                        "begins_with": "LIKE",
                        "not_begins_with": "NOT LIKE"
                    },
                    {
                        "contains": "LIKE",
                        "not_contains": "NOT LIKE"
                    },
                    {
                        "ends_with": "LIKE",
                        "not_ends_with": "NOT LIKE"
                    }
                ],
                opera: {
                    "equal": "=",
                    "not_equal": "!=",
                    "less": "<",
                    "less_or_equal": "<=",
                    "greater": ">",
                    "greater_or_equal": ">=",
                    "is_empty": "=",
                    "is_not_empty": "!=",
                    "is_null": "IS",
                    "is_not_null": "IS NOT",
                    "in": "IN",
                    "not_in": "NOT IN",
                    "between": "BETWEEN",
                    "not_between": "NOT BETWEEN",
                    "begins_with": "LIKE",
                    "not_begins_with": "NOT LIKE",
                    "contains": "LIKE",
                    "not_contains": "NOT LIKE",
                    "ends_with": "LIKE",
                    "not_ends_with": "NOT LIKE"
                }
            };
        },
        mounted() {
            $("#footer").css({display: "none"});
            this.appendScriptFilesToHead();
            this.getPreDataSegment();
        },
        beforeDestroy() {
            $("#footer").css({display: "block"});
        },
        methods: {
            getPreDataSegment() {
                const payLoad = {
                    resource: "company/presets/segment",
                    action: "get",
                };

                this.api("post",
                    this.constants.getUrl("backendApiUrl"),
                    payLoad,
                    this.authHeaders,
                    true,
                ).then((response) => {

                    setTimeout(() => {
                            this.segmentFilters = response.data.data.queryBuilderFilters;
                            this.initializeQueryBuilder();
                            if (this.$route.params.id != null) {
                                this.segmentObj.action = "update";
                                this.segmentObj.id = this.$route.params.id;
                                this.getSegment(this.$route.params.id);
                            }
                        },
                        150);

                }).catch((error) => {

                });
            },
            initializeQueryBuilder() {

                for (let i = 0; i < this.segmentFilters.length; i++) {

                    if (this.segmentFilters[i].id == "email") {
                        this.segmentFilters[i].validation.callback = function (value, rule) {
                            let valid = true;
                            let operators = ['equal', 'not_equal', 'not_in', 'in'];
                            let expression = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;

                            if (operators.indexOf(rule.operator.type) > -1) {
                                valid = expression.test(value);
                            }

                            if (valid) {
                                return true;
                            } else {
                                return ['Invalid Email', value];
                            }
                        };
                    }
                }

                $("#queryBuilderGoesHere").queryBuilder({
                    plugins: {
                        'bt-selectpicker': {
                            liveSearch: true
                        }
                    },
                    filters: this.segmentFilters,
                    display_errors: true,
                });

                $('#queryBuilderGoesHere').on('afterCreateRuleInput.queryBuilder', function (e, rule) {
                    if (rule.filter.type === 'datetime') {
                        var $input = rule.$el.find('.rule-value-container [name*=_value_]');
                        $input.on('dp.change', function () {
                            $input.trigger('change');
                        });
                    }
                });
                this.showHideGroupValuesAndOtherEvents();
            },
            showHideGroupValuesAndOtherEvents() {

                $(document).on("click", ".rule-operator-container .filter-option", function () {
                    $(".inner li").css({display: "block"});
                });

                $(document).on("click", ".rule-filter-container .filter-option", function () {

                    $(".inner li").css({display: "none"});

                    $(".dropdown-header").css({
                        display: "block",
                        cursor: "pointer",
                    });

                    $(".dropdown-header").off("click");

                    $(".dropdown-header").click(function () {
                        var className = $("." + $(this).attr("class").split(" ")[1]);
                        className.eq(1).css("display") == "none" ? className.css({display: "block"}) : className.css({display: "none"});
                        $(".dropdown-header").css({
                            display: "block",
                            cursor: "pointer",
                        });
                    });

                });

                $('#btn-reset').on('click', function () {
                    $('#queryBuilderGoesHere').queryBuilder('reset');
                });

                $('#queryBuilderGoesHere').on('afterUpdateRuleValue.queryBuilder', function (e, rule) {
                    if (rule.filter.plugin === 'datepicker') {
                        rule.$el.find('.rule-value-container input').datepicker('update');
                    }
                });
            },
            submitSegment() {
                this.locked = false;

                if (this.validate() && $('#queryBuilderGoesHere').queryBuilder('validate')) {

                    let rules = $('#queryBuilderGoesHere').queryBuilder('getRules');
                    this.segmentObj.data.criteria = btoa($('#queryBuilderGoesHere').queryBuilder('getSQL').sql);
                    //this.segmentObj.data.criteria = btoa(this.keyValueSqlMaker(rules));
                    this.segmentObj.data.rules = JSON.stringify(rules);
                    this.segmentObj.data.tags = this.segmentObj.data.tagsOriginal.toString();
                    this.segmentObj.data.fieldName = [];

                    this.segmentObj.data.attributeType = {
                        action: [],
                        conversion: [],
                        user: [],
                    };

                    this.findDistinctFields(rules, this.segmentObj.data.fieldName);
                    delete this.segmentObj.data.fieldName;

                    this.api("post",
                        this.constants.getUrl("backendApiUrl"),
                        this.segmentObj,
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
                                this.$swal({
                                    position: 'top-right',
                                    type: 'success',
                                    title: 'Segment Created Successfully',
                                    showConfirmButton: false,
                                    timer: 2500
                                });
                                this.$router.push("/dashboard/segment-listing");
                            }
                        }
                    }).catch((error) => {

                    });
                }
            },
            getSegment(id) {
                const payLoad = {
                    resource: "segments",
                    action: "get",
                    id,
                };

                this.api("post",
                    this.constants.getUrl("backendApiUrl"),
                    payLoad,
                    this.authHeaders,
                    true,
                ).then((response) => {
                    if (response.data.meta.code === 200) {
                        if (response.data.data.status != undefined && !response.data.data.status) {
                            this.$router.push("/dashboard/segment-listing");
                        } else {
                            this.segmentObj.data = response.data.data;
                            this.segmentObj.data.tagsOriginal = this.segmentObj.data.tags != '' ? this.segmentObj.data.tags.split(",") : [];
                            this.segmentObj.data.rules = JSON.parse(this.segmentObj.data.rules);
                            this.updateRulesAttribute(this.segmentObj.data.rules);
                            $('#queryBuilderGoesHere').queryBuilder('setRules', this.segmentObj.data.rules);
                        }
                    }
                }).catch((error) => {

                });
            },
            findDistinctFields(obj, arr) {
                if (obj.condition) {
                    for (let i = 0; i < obj.rules.length; i++) {
                        this.findDistinctFields(obj.rules[i], arr);
                    }
                } else {
                    if (arr.indexOf(obj.field) < 0) {
                        arr.push(obj.field);
                        this.segmentObj.data.attributeType[obj.data.type].push(obj.field);
                    }
                }

            },
            keyValueSqlMaker(obj) {
                if (obj.condition) {
                    let str = "( ";
                    for (let i = 0; i < obj.rules.length; i++) {
                        str += this.keyValueSqlMaker(obj.rules[i]);
                        if (i != (obj.rules.length - 1)) {
                            str = str + " " + obj.condition + " ";
                        }
                    }
                    str += " )";
                    return str;
                } else {
                    let str = "";
                    if (obj.data.type == "user") {
                        str = obj.field + " " + this.opera[obj.operator] + this.getOptValue(obj.value, obj.operator, obj.type);
                    } else {
                        str = "( event_id = '" + obj.field + "' and event_value " + this.opera[obj.operator] + this.getOptValue(obj.value, obj.operator, obj.type) + ")";
                    }
                    return str;
                }
            },
            getOptValue(value, opt, type) {
                for (let i = 0; i < this.operators.length; i++) {
                    if (i == 0) {
                        if (this.operators[i][opt]) {
                            if (type == 'integer')
                                return ' ' + value;
                            else
                                return " '" + value + "'";
                        }
                    } else if (i == 1) {
                        if (this.operators[i][opt]) {
                            return " '" + "'";
                        }
                    } else if (i == 2) {
                        if (this.operators[i][opt]) {
                            return ' NULL';
                        }
                    } else if (i == 3) {
                        if (this.operators[i][opt]) {
                            if (type == 'integer')
                                return "(" + value + ")";
                            else
                                return "(" + "'" + value + "'" + ")";
                        }
                    } else if (i == 4) {
                        if (this.operators[i][opt]) {
                            return ' ' + value[0] + ' AND ' + value[1];
                        }
                    } else if (i == 5) {
                        if (this.operators[i][opt]) {
                            return "(" + "'" + value + "%'" + ")";
                        }
                    } else if (i == 6) {
                        if (this.operators[i][opt]) {
                            return "(" + "'%" + value + "%'" + ")";
                        }
                    } else if (i == 7) {
                        if (this.operators[i][opt]) {
                            return "(" + "'%" + value + "'" + ")";
                        }
                    }
                }
            },
            updateRulesAttribute(obj) {
                if (obj.condition) {
                    for (let i = 0; i < obj.rules.length; i++) {
                        this.updateRulesAttribute(obj.rules[i]);
                    }
                } else {
                    for (let j = 0; j < this.segmentFilters.length; j++) {
                        if (this.segmentFilters[j].id == obj.id) {
                            obj.data.type = this.segmentFilters[j].data.type;
                            break;
                        }
                    }
                }
            },
            appendScriptFilesToHead() {
                var filePath = "./src/assets/plugins/queryBuilder/js/query-builder.standalone.js";
                if ($('head script[src="' + filePath + '"]').length <= 0) {
                    var plugin = document.createElement("script");
                    plugin.setAttribute(
                        "src",
                        /*"https://cdn.jsdelivr.net/npm/jQuery-QueryBuilder@2.4.4/dist/js/query-builder.standalone.js"*/
                        "./src/assets/plugins/queryBuilder/js/query-builder.standalone.js"
                    );
                    plugin.async = true;
                    document.head.appendChild(plugin);
                }

                var filePath = "./src/assets/js/moment.js";
                if ($('head script[src="' + filePath + '"]').length <= 0) {
                    plugin = document.createElement("script");
                    plugin.setAttribute(
                        "src",
                        "./src/assets/js/moment.js"
                        /*"https://rawgit.com/moment/moment/2.2.1/min/moment.min.js"*/
                    );
                    plugin.async = true;
                    document.head.appendChild(plugin);
                }
            },
            validate() {
                let success = true;
                if (this.segmentObj.data.name == '') {
                    success = false;
                    this.errors.name.message = 'Segment name is required';
                } else {
                    this.errors.name.message = '';
                }
                return success;
            }
        },
        watch: {
            '$route.params.id'(currVal, oldVal) {
                this.segmentObj.id = currVal;
                this.getSegment(currVal);
            },
            'segmentObj.data': {
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
    @import '../../../../assets/plugins/queryBuilder/css/query-builder.default.css';
    @import '../../../../assets/plugins/bootstrap-selectpicker/css/selectpicker.css';
    @import '../../../../assets/plugins/bootstrap-datepicker/css/bootstrap-datepicker.css';
</style>

<style>
    .vue-input-tag-wrapper {
        padding: 0;
    }

    .vue-input-tag-wrapper .new-tag {
        margin: 0;
        padding: 10px;
        font-weight: bold;
        font-size: 16px;
        background: transparent !important;
        color: #777 !important;
        flex-grow: 1 !important;
        outline: none !important;
        display: inline-block !important;
        width: auto !important;
    }

    .vue-input-tag-wrapper .input-tag {
        height: 30px;
        background-color: #f0f0f0;
        border: none;
        border-radius: 6px;
        color: #666;
        padding: 5px 8px;
    }

    .vue-input-tag-wrapper .input-tag .remove {
        color: #2a8689
    }

    .save_segment {
        background: #2a8689;
        color: #fff;
        width: 210px !important;
        float: right;
    }

    .switch {
        position: relative;
        display: inline-block;
        width: 60px;
        height: 30px;
    }

    .switch input {
        opacity: 0;
        width: 0;
        height: 0;
    }

    .slider {
        position: absolute;
        cursor: pointer;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background-color: #ccc;
        -webkit-transition: .4s;
        transition: .4s;
    }

    .slider:before {
        position: absolute;
        content: "";
        height: 26px;
        width: 26px;
        left: 4px;
        bottom: 4px;
        background-color: white;
        -webkit-transition: .4s;
        transition: .4s;
    }

    input:checked + .slider {
        background-color: #2196F3;
    }

    input:focus + .slider {
        box-shadow: 0 0 1px #2196F3;
    }

    input:checked + .slider:before {
        -webkit-transform: translateX(26px);
        -ms-transform: translateX(26px);
        transform: translateX(26px);
    }

    /* Rounded sliders */
    .slider.round {
        border-radius: 34px;
    }

    .slider.round:before {
        border-radius: 50%;
    }

    /*rounded slider end*/

    .bootstrap-select > .dropdown-toggle {
        height: 44px;
        font-weight: 600;
        color: #666;
        padding: 8px 24px 8px 7px;
        font-size: 16px;
        background: none;
        border: 1px solid #b2b2b2;
    }

    .dropdown-menu {
        z-index: 99999999999999999999999999999999999999999999999999999 !important;
    }
</style>