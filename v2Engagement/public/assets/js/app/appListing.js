$(document).ready(function (e) {
    var filter = null;
    var filterType = null;
    var oTable = null;

    function getLookupListing() {
        var url = baseUrl + '/backend/app/listing/fetch';
        $('#appListing').DataTable({
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

                    data.filter = filter;
                    data.filterType = filterType;

                },
            },
            "columns": [
                {
                    "data": "name",
                    "render": $.fn.dataTable.render.text()
                },
                {
                    "data": "app_id",
                    "render": $.fn.dataTable.render.text()
                },
                {
                    "data": "description",
                    "render": $.fn.dataTable.render.text()
                },
                {
                    "data": "platform",
                    "render": $.fn.dataTable.render.text()
                },
                {
                    "data": "created_at",
                    "render": $.fn.dataTable.render.text()
                },
            ],
            "aoColumnDefs": [
                {
                    "aTargets": [5],
                    "mData": "action",
                    "mRender": function (data, type, full) {
                        /*<li id="' + full.id + '" ><a data-action="delete" href="' + baseUrl + '/backend/app/delete/' + full.id + '" ><img src="' + baseUrl + '/assets/images/del_icon.png' + '" alt="#">  Delete</a></li>*/
                        return '<div class="lst_tbl_drop_outer"><span class=""> <img src="' + baseUrl + '/assets/images/sett_icon.png' + '"  alt="#"> </span><ul><li id="' + full.id + '"  data-action="edit" ><a data-action="edit" href="' + baseUrl + '/backend/app/edit/' + full.id + '"> <img src="' + baseUrl + '/assets/images/edit_icon.png' + '" alt="#"> Edit</a></li>  </ul></div></td>';
                    },
                    "visible": true,
                    "searchable": false,
                    "orderable": false
                }
            ],
            "order": [[4, "desc"]],
            "initComplete": function (settings, json) {
                // call after loaded only first time
            },
            "drawCallback": function (settings, json) {
                //call after every event cause change in datatable
                var description = $("#appListing_info").text();
                $("#appListing_info").text("");
                $(".listing_sec_ftr_detail p").text(description);
            }

        });
        oTable = $('#appListing').DataTable();
        $('#searchBar').on("change", function () {
            $('#appListing').DataTable().search($(this).val()).draw();
        });
    }

    getLookupListing();

    $(document).on('click', '.lst_tbl_drop_outer ul li a', function () {


        if ($(this).attr('data-action') == "delete") {


            swal({
                title: "Are you sure?",
                icon: "warning",
                buttons: true,
                dangerMode: true,
            }).then((willDelete) => {
                if (!willDelete) {
                    return false;
                } else {

                    $.get($(this).attr('href'), function (data) {
                        if (data.status_code == 200) {
                            oTable.draw();
                        }
                    });
                }
            });

        } else {
            window.location = $(this).attr('href');
        }
    });

    $(".filter-query").on("click", function (e) {

        filter = $(this).attr("data-filter");
        filterType = $(this).attr("data-filtertype");
        oTable.draw();
    });
});