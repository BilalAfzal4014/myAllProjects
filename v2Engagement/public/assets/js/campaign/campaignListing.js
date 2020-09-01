$(document).ready(function () {
    var oTable = -1;
    var column = 'all';
    var value = 'all';
    getCampaignFilters();
    getCampaignListing();
    events();

    function getCampaignFilters() {
        var url = baseUrl + '/backend/campaign/filters/' + $(".companyId").val();
        $.ajax({
            type: "GET",
            url: url,
            dataType: "json",
            success: function (response) {
                populateList(response.data.status, 'obj', '#campaignStatus');
                populateList(response.data.schedule, 'obj', '#campaignSchedule');
                populateList(response.data.type, 'obj', '#campaignType');
                populateList(response.data.tags, 'arr', '#campaignTags');
            },
            error: function () {

            }
        });
    }

    function populateList(data, type, id) {
        var str = '';
        if (type == 'obj') {
            for (var key in data) {
                if (data.hasOwnProperty(key) && key != 'key') {
                    str += '<li><a style="cursor: pointer" data-value="' + data[key] + '" data-col="' + data['key'] + '">' + key + '</a></li>';
                }
            }

        }
        else {
            for (var i = 0; i < data.length; i++) {
                str += '<li><a style="cursor: pointer" data-value="' + data[i].tags + '" data-col="tags">' + data[i].tags + " (" + data[i].occurence + ")" + '</a></li>';
            }
        }

        $(id).html(str);
    }

    function getCampaignListing() {
        var url = baseUrl + '/backend/campaign/listing/' + $(".companyId").val();
        $('#campaignListing').DataTable({
            "processing": true,
            "serverSide": true,
            "searching": true,
            "bLengthChange": false,
            "iDisplayLength": listingSize,
            "ajax": {
                "url": url,
                "dataType": "json",
                "type": "GET",
                "data": function (data) {
                    data.column = column;
                    data.value = value;
                },
            },
            "columns": [
                {
                    "data": "id",
                    "render": $.fn.dataTable.render.text()
                },
                {
                    "data": "name",
                },
                {
                    "data": "typeName",
                    "render": $.fn.dataTable.render.text()
                },

                {
                    "data": "targetUser",
                    "render": $.fn.dataTable.render.text()
                },

                {
                    "data": "totalSend",
                    "render": $.fn.dataTable.render.text()
                },
                {
                    "data": "totalSuccess",
                    "render": $.fn.dataTable.render.text()
                },
                {
                    "data": "totalFailed",
                    "render": $.fn.dataTable.render.text()
                },
                {
                    "data": "totalView",
                    "render": $.fn.dataTable.render.text()
                },

                {
                    "data": "updated_at",
                    "render": $.fn.dataTable.render.text()
                },
                {"data": "action"},
            ],
            "aoColumnDefs": [
                {
                    "targets": [0],
                    "visible": false,
                    "searchable": false
                },
                {
                    "targets": [3],
                    "orderable": false,
                    "searchable": false
                },
                {
                    "aTargets": [9],
                    "mData": "action",
                    "mRender": function (data, type, full) {
                        var str = '<div class="lst_tbl_drop_outer"><span class=""> <img src="' + baseUrl + '/assets/images/sett_icon.png' + '"  alt="#"> </span><ul>';
                        if (full.status == 'active') {
                            str += '<li style="display: none" id="' + full.id + '"  data-action="edit" ><a href="#"> <img src="' + baseUrl + '/assets/images/edit_icon.png' + '" alt="#">Edit</a></li><li id="' + full.id + '"  data-action="view"><a href="#"> <img src="' + baseUrl + '/assets/images/view_icon.png' + '" alt="#"> View</a></li><li id="' + full.id + '"  data-action="suspend"><a href="#"> <img src="' + baseUrl + '/assets/images/Suspend.png' + '" alt="#"> Suspend</a></li>';
                        }
                        else if (full.status == 'draft') {
                            str += '<li id="' + full.id + '"  data-action="edit" ><a href="#"><img src="' + baseUrl + '/assets/images/edit_icon.png' + '" alt="#">Edit</a></li><li id="' + full.id + '"  data-action="view"><a href="#"> <img src="' + baseUrl + '/assets/images/view_icon.png' + '" alt="#"> View</a></li>';
                        }
                        else {
                            str += '<li style="display: none" id="' + full.id + '"  data-action="edit" ><a href="#"><img src="' + baseUrl + '/assets/images/edit_icon.png' + '" alt="#">Edit</a></li><li id="' + full.id + '"  data-action="view"><a href="#"> <img src="' + baseUrl + '/assets/images/view_icon.png' + '" alt="#"> View</a></li>';
                        }
                        if (full.status != 'draft')
                            str += '<li id="' + full.id + '"  data-action="stats" ><a href="#"><img src="' + baseUrl + '/assets/images/statistics.png' + '" alt="#">Stats</a></li>';
                            str += '<li id="' + full.id + '"  data-action="export" ><a href="/backend/getusersagainstcampaign/' + full.id + '" ><img src="' + baseUrl + '/assets/images/export_icon.png' + '" alt="#">Export</a></li>';
                        str += '</ul></div></td>';
                        return str;
                    },
                    "visible": true,
                    "searchable": false,
                    "orderable": false
                }
            ],
            "order": [[8, "desc"]],
            "initComplete": function (settings, json) {
                // call after loaded only first time
            },
            "createdRow": function (row, data, dataIndex) {
                if (data.status == 'draft') {
                    $(row).addClass('draft');
                }
                else if (data.status == 'active') {
                    $(row).addClass('launch');
                }
                else if (data.status == 'suspend') {
                    $(row).addClass('suspend');
                }
                else if (data.status == 'expired') {
                    $(row).addClass('expired');
                }
            },
            "drawCallback": function (settings, json) {
                //call after every event cause change in datatable
                var description = $("#campaignListing_info").text();
                $("#campaignListing_info").text("");
                $(".listing_sec_ftr_detail p").text(description);
            }

        });

        oTable = $('#campaignListing').DataTable();
        $('#searchBar').on("change", function () {
            oTable.search($(this).val()).draw();
        });
    }

    function events() {
        $("#campaigns_type2").change(function () {
            window.location.href = baseUrl + '/backend/campaign/createCampaign';
        });

        $(document).on('click', '.lst_tbl_drop_outer ul li', function () {
            mode = $(this).attr('data-action');
            id = $(this).attr('id');
            if (mode === "export")
                window.location.href = baseUrl + '/backend/getusersagainstcampaign/'+ id;
           else if (mode != 'suspend' && mode != 'stats')
                window.location.href = baseUrl + '/backend/campaign/campaignAction/' + mode + '/' + id;
            else if (mode != "stats")
                suspendCampaign(id, this);
            else
                window.location.href = baseUrl + '/backend/campaign/stats/' + id;
        });

        $(document).on("click", "#campaignTags li a, #campaignType li a, #campaignSchedule li a, #campaignStatus li a", function () {
            column = $(this).attr('data-col');
            value = $(this).attr('data-value');
            oTable.draw();
        });
    }

    function suspendCampaign(id, element) {

        swal({
            title: "Are you sure?",
            text: "Campaign will be suspended",
            //icon: "warning",
            buttons: true,
            dangerMode: true,
        }).then((willDelete) => {
            if (willDelete) {
                var url = baseUrl + '/backend/campaign/suspend/' + id;
                $.ajax({
                    type: "GET",
                    url: url,
                    dataType: 'json',
                    success: function (response) {
                        if (response.status) {
                            $(element).css({display: 'none'});
                            //$(element).parent().children().eq(0).css({display: 'block'});
                            $(element).parentsUntil('table').filter('tr').addClass('suspend');
                        }
                    },
                    error: function () {

                    }
                });
            }
        });
    }
});