$(document).ready(function () {

    getImportDataListing();

    function getImportDataListing() {
       table = $('#importDataListing').DataTable({
            "processing": true,
            "serverSide": true,
            "searching": false,
            "bLengthChange": false,
            "iDisplayLength": listingSize,
            "ajax": {
                "url": route,
                "dataType": "json",
                "type": "GET",
            },
            "columns": [
                {
                    "data": "id",
                    "render": $.fn.dataTable.render.text()
                },
                {
                    "data": "file_name",
                    "render": $.fn.dataTable.render.text()
                },
                {
                    "data": "file_size",
                    "render": $.fn.dataTable.render.text()
                },
                {
                    "data": "created_at",
                    "render": $.fn.dataTable.render.text()
                },
                {
                    "data": "is_processed",
                    "render": $.fn.dataTable.render.text()
                },
                {
                    "data": "remaning_file",
                    "render": $.fn.dataTable.render.text()
                },
                {
                    "data": "process_date",
                    "render": $.fn.dataTable.render.text()
                },
                {"data": "action"},
            ],
            "aoColumnDefs": [
                {
                    "targets": [0],
                    "visible": false,
                    "searchable": false,
                    "orderable": false
                },
                {
                    "targets": [2],
                    "searchable": true,
                },
                {
                    "targets": [3],
                    "searchable": true,
                },
                {
                    "targets": [4],
                    "searchable": true,
                },
                {
                    "targets": [5],
                    "searchable": true,
                },
                {
                    "targets": [6],
                    "searchable": true,
                },
                {
                    "aTargets": [7],
                    "mData": "action",
                    "mRender": function (data, type, full) {
                        // console.log(full.row_id);
                        // console.log(data.row_id);
                        return '<div class="lst_tbl_drop_outer"><span class="">' +
                            '<img src="' + baseUrl + '/assets/images/sett_icon.png' + '"  alt="#"></span>' +
                            '<ul>' +
                            '<li id="' + full.id + '"  data-action="delete"><a href="#"><img src="' + baseUrl + '/assets/images/del_icon.png' + '" alt="#"> Delete</a></li>' +
                            '<li id="' + full.id + '"  data-action="download"><a href="#"><img src="' + baseUrl + '/assets/images/download.png' + '" alt="#">  Download</a></li>' +
                            '<li id="' + full.id + '"  data-action="importFile"><a href="#"><img src="' + baseUrl + '/assets/images/import.png' + '" alt="#"> Import File</a></li>'+
                            '</ul></div></td>';
                    },
                    "visible": true,
                    "searchable": false,
                    "orderable": false
                }
            ],
            "order": [[0, "desc"]],
            "initComplete": function (settings, json) {
                // call after loaded only first time
            },
            "drawCallback": function (settings, json) {
                //call after every event cause change in datatable
                var description = $("#importDataListing_info").text();
                $("#importDataListing_info").text("");
                $(".listing_sec_ftr_detail p").text(description);
            }

        });

        var oTable = $('#importDataListing').DataTable();
        $('#searchBar').on("change",function () {
            oTable.search($(this).val()).draw();
        });

    }
});
