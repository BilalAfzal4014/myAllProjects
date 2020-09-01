$(document).ready(function () {
    var oTable;
    var column = 'all';
    var value = 'all';
    getSegmentListing();
    events();

    function getSegmentListing() {
        var url = baseUrl + '/backend/segment/listing/' + $(".companyId").val();
        $('#segmentListing').DataTable({
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
                    //console.log('data',data);
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
                    "data": "created_at",
                    "render": $.fn.dataTable.render.text()
                },
                {
                    "data": "name"
                },
                {
                    "data": "user_target",
                    "render": $.fn.dataTable.render.text()
                },
                /*{
                    "data": "filters",
                    "render": $.fn.dataTable.render.text()
                },
                {
                    "data": "campaign",
                    "render": $.fn.dataTable.render.text()
                },
                {
                    "data": "cards",
                    "render": $.fn.dataTable.render.text()
                },
                {
                    "data": "lastEdited",
                    "render": $.fn.dataTable.render.text()
                },*/
                {
                    "data": "created_by",
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
                    "targets": [1],
                    "visible": false,
                    "searchable": false
                },
                {
                    "aTargets": [2],
                    "mData": "name",
                    "className": "nf_seg_name",
                    "mRender": function (data, type, full) {
                        return '<a href="#">\n' +
                            full.name +
                            '</a>\n' +
                            '<div class="nf_seg_name_detail">\n' +
                            '    <table>\n' +
                            '<thead>' +
                            '<tr>' +
                            '<th colspan="2" style="text-align: center;">Usage Count</th>' +
                            '</tr>' +
                            '</thead>' +
                            '<tbody>' +
                            '        <tr>\n' +
                            '            <td style="width:80%;">Newsfeed</td>\n' +
                            '            <td style="width:20%;">' + full.newsfeedCount + '</td>\n' +
                            '        </tr>\n' +
                            '        <tr>\n' +
                            '            <td style="width:80%;">Inapp</td>\n' +
                            '            <td style="width:20%;">' + full.inAppCount + '</td>\n' +
                            '        </tr>\n' +
                            '        <tr>\n' +
                            '            <td style="width:80%;">Push</td>\n' +
                            '            <td style="width:20%;">' + full.pushCount + '</td>\n' +
                            '        </tr>\n' +
                            '        <tr>\n' +
                            '            <td style="width:80%;">Email</td>\n' +
                            '            <td style="width:20%;">' + full.emailCount + '</td>\n' +
                            '        </tr>\n' +
                            '    </tbody>' +
                            '</table>\n' +
                            '</div>';
                    },
                    "visible": true,
                },
                {
                    "targets": [3],
                    "searchable": false,
                    "orderable": false
                },
                {
                    "aTargets": [5],
                    "mData": "action",
                    "mRender": function (data, type, full) {
                        return '<div class="lst_tbl_drop_outer"><span class=""> <img src="' + baseUrl + '/assets/images/sett_icon.png' + '"  alt="#"> </span><ul><li id="' + full.id + '"  data-action="edit" ><a href="#"> <img src="' + baseUrl + '/assets/images/edit_icon.png' + '" alt="#"> Edit</a></li><li id="' + full.id + '"  data-action="view"><a href="#"> <img src="' + baseUrl + '/assets/images/view_icon.png' + '" alt="#"> View</a></li><li id="' + full.id + '"  data-action="Export"><a href="#"> <img src="' + baseUrl + '/assets/images/import.png' + '" alt="#"> Export</a></li></ul></div></td>';
                    },
                    "visible": true,
                    "searchable": false,
                    "orderable": false
                }
            ],
            "order": [[1, "desc"]],
            "initComplete": function (settings, json) {
                // call after loaded only first time
            },
            "drawCallback": function (settings, json) {
                //call after every event cause change in datatable
                var description = $("#segmentListing_info").text();
                $("#segmentListing_info").text("");
                $(".listing_sec_ftr_detail p").text(description);
            }

        });

        oTable = $('#segmentListing').DataTable();
        $('#searchBar').on("change", function () {
            oTable.search($(this).val()).draw();
        });
    }

    function events() {


        $("#segmentTagsFilter  li a").click(function () {
            column = $(this).attr('data-column');
            value = $(this).attr('data-val');
            oTable.draw();
        });

        $("#campaigns_type2").change(function () {
            window.location.href = baseUrl + '/backend/segment/createSegment';
        });

        $(document).on('click', '.lst_tbl_drop_outer ul li', function () {
            mode = $(this).attr('data-action');
            id = $(this).attr('id');
            if (mode != "Export")
                window.location.href = baseUrl + '/backend/segment/segmentAction/' + mode + '/' + id;
            else
                window.location.href = baseUrl + '/backend/segment/cacheusers/' + id;
        });
    }

});
