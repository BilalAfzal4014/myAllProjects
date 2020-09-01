$(document).ready(function () {

    var operators = [
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
    ];
    var opera = {
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
    };

    var validation = false;
    var rowId = -1;
    var submitPressed = false;
    var distinctFields = [];

    tagsInput();
    queryBuilderAjaxCall();
    events();
    validationReactiveNess();

    function queryBuilderAjaxCall() {
        var url = baseUrl + '/backend/segment/filters/' + $(".companyId").val();
        $.ajax({
            type: 'GET',
            url: url,
            dataType: "json",
            success: function (response) {
                queryBuilder(response.data);
            },
            error: function (xhr, ajaxOptions, thrownError) {
                alert("error");
            }
        });
    }

    function queryBuilder(myFilters) {


        for (let i = 0; i < myFilters.length; i++) {

            if (myFilters[i].id == "email") {
                myFilters[i].validation.callback = function (value, rule) {
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
            plugins: ['bt-tooltip-errors'],
            filters: myFilters,
            display_errors: true,
            //rules: '' // this will work
        });

        $('#btn-reset').on('click', function () {
            $('#queryBuilderGoesHere').queryBuilder('reset');
        });

        $('#queryBuilderGoesHere').on('afterUpdateRuleValue.queryBuilder', function (e, rule) {
            if (rule.filter.plugin === 'datepicker') {
                rule.$el.find('.rule-value-container input').datepicker('update');
            }
        });

        if ($("#segmentAction").val() != '') {
            readSegment($("#segmentEditionId").val(), $("#segmentAction").val());
        }
    }

    function tagsInput() {
        $('.tags').tagsinput({
            allowDuplicates: false,
            maxChars: 15
        });

        $(".tags").on('itemAdded', function (event) {

            $(".tags").parent().children().filter('.bootstrap-tagsinput').find('input').attr('placeholder', '');
            if (/\s/.test(event.item)) {
                $('.tags').tagsinput('remove', event.item);
            }
        });

        $(".tags").on('itemRemoved', function (event) {
            if ($(".tags").val() == '') {
                $(".tags").parent().children().filter('.bootstrap-tagsinput').find('input').attr('placeholder', 'Enter Tag(s)');
            }
        });

        $(".tags").on('beforeItemAdd', function (event) {
            var tag = event.item;

            if (/\s/.test(tag)) {
                if (!event.options || !event.options.preventPost) {
                    $(".tags").tagsinput('add', tag.replace(/\s/g, '_'), {preventPost: true});
                }
            }
        });
    }

    function events() {
        $("#campaignTitle").on('input', function () {
            $("#segmentTitle").text("Segment Details > " + encodeHTML($("#campaignTitle").val()));
        });

        $("#submitSegment").click(function () {
            validation = true;
            submitPressed = true;
            var campaignTitle = $("#campaignTitle").val();
            var rules = $('#queryBuilderGoesHere').queryBuilder('getRules');
            var tagsInput = $(".tags").tagsinput('items');
            var errors = validate(campaignTitle, rules, tagsInput);
            if (!errors) {
                var sql = $('#queryBuilderGoesHere').queryBuilder('getSQL');
                submitSegment(campaignTitle, rules, tagsInput, sql, rowId);
            }
        });
    }

    function readSegment(SegmentId, segmentAction) {
        var segmentObj;
        rowId = SegmentId;
        var action = segmentAction;
        var url = baseUrl + '/backend/segment/get/' + rowId;
        $.ajax({
            type: 'GET',
            url: url,
            dataType: "json",
            success: function (response) {
                segmentObj = response.data;
                $('.campaignCount').text(response.campaignCount);
                $('.newFeedCount').text(response.newFeedCount);
                if (action == "view") {
                    $("#btn-reset").css({'display': 'none'});
                    $("#submitSegment").css({'display': 'none'});
                }
                $("#segmentTitle").append(encodeHTML(segmentObj.name)).show();
                $("#campaignTitle").val(segmentObj.name);
                $('.tags').tagsinput('add', segmentObj.tags);
                $('#queryBuilderGoesHere').queryBuilder('setRules', JSON.parse(segmentObj.rules));
                $(".db_content_holder").show();
                $(".db_content_listing_holder").hide();
                $(".segment_footer").show();
                submitPressed = true;
            },
            error: function (xhr, ajaxOptions, thrownError) {
                //alert("error");
            }

        });
    }

    function validate(campaignTitle, rules, tagsInput) {
        var bool = false;
        if (campaignTitle.length == 0) {
            $("#campaignError").text("Campaign Title is required");
            bool = true;

        } else {
            $("#campaignError").text("");
        }

        if ($.isEmptyObject(rules)) {
            bool = true;
        }

        $(".tags").on('itemAdded', function (event) {
            $("#tagsError").text("");
        });

        return bool;
    }

    function submitSegment(campaignTitle, rules, tagsInput, sql, rowId) {
        var keySql = keyValueSql(rules);
        var segmentObj = {
            id: rowId,
            companyId: $(".companyId").val(),
            campaignTitle,
            rules: JSON.stringify(rules),
            tagsInput: $(".tags").val(),
            sql,
            keySql,
            distinctFields
        };
        var url = baseUrl + '/backend/segment/create';
        segmentObj.keySql = btoa(segmentObj.keySql);
        segmentObj.sql.sql = btoa(segmentObj.sql.sql);

        $.ajax({
            type: 'POST',
            url: url,
            data: {
                segmentObj,
                _token: $('input[name="_token"]').val()
            },
            dataType: "json",
            headers: {
                'X-CSRF-Token': $('input[name="_token"]').val()
            },
            success: function (response) {
                if (response.status) {
                    window.location.href = baseUrl + '/backend/segment/segments';
                } else {
                    toastr.error(response.message);
                }
            },
            error: function (xhr, ajaxOptions, thrownError) {
                alert("error");
            }

        });
    }

    function keyValueSql(obj) {

        if (obj.condition) {
            var str = ' ( ';
            for (var i = 0; i < obj.rules.length; i++) {
                str = str + keyValueSql(obj.rules[i]);

                if (i != (obj.rules.length - 1)) {
                    str = str + ' ' + obj.condition;
                }
            }
            str = str + ' ) ';
            return str;
        } else {

            var token = ' ( ' + 'code = "' + obj.field + '" AND ' + ' value ' + opera[obj.operator] + getOptValue(obj.value, obj.operator, obj.type) + ' )';
            var index = distinctFields.indexOf(obj.field);

            if (index < 0) {
                distinctFields.push(obj.field);
            }
            return token;
        }
    }

    function getOptValue(value, opt, type) {
        for (var i = 0; i < operators.length; i++) {
            if (i == 0) {
                if (operators[i][opt]) {
                    if (type == 'integer')
                        return ' ' + value;
                    else
                        return " '" + value + "'";
                }
            } else if (i == 1) {
                if (operators[i][opt]) {
                    return " '" + "'";
                }
            } else if (i == 2) {
                if (operators[i][opt]) {
                    return ' NULL';
                }
            } else if (i == 3) {
                if (operators[i][opt]) {
                    if (type == 'integer')
                        return "(" + value + ")";
                    else
                        return "(" + "'" + value + "'" + ")";
                }
            } else if (i == 4) {
                if (operators[i][opt]) {
                    return ' ' + value[0] + ' AND ' + value[1];
                }
            } else if (i == 5) {
                if (operators[i][opt]) {
                    return "(" + "'" + value + "%'" + ")";
                }
            } else if (i == 6) {
                if (operators[i][opt]) {
                    return "(" + "'%" + value + "%'" + ")";
                }
            } else if (i == 7) {
                if (operators[i][opt]) {
                    return "(" + "'%" + value + "'" + ")";
                }
            }
        }
    }

    function validationReactiveNess() {
        $("#campaignTitle").on("input", function () {
            if (submitPressed) {
                var campaignTitle = $("#campaignTitle").val();
                var rules = $('#queryBuilderGoesHere').queryBuilder('getRules');
                var tagsInput = $(".tags").tagsinput('items');
                validate(campaignTitle, rules, tagsInput);
            }
        });
    }

    function encodeHTML(s) {
        return s.replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/"/g, '&quot;');
    }

});
