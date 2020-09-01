$(document).ready(function (e) {

    var filter = null;
    var filterType = null;
    var oTable = null;

    function getLookupListing() {
        var url = baseUrl + '/lookup/listing/fetch';
        $('#lookupListing').DataTable({
            "processing": true,
            "serverSide": true,
            "searching": true,
            "bLengthChange": false,
            "iDisplayLength": listingSize,
            "defaultContent":"",
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
                {"data": "code"},
                {"data": "name"},
                {"data": "parent"},
                {"data": "created_by"},
                {"data": "Action"}
            ],
            "aoColumnDefs": [

                {
                    "aTargets": [4],
                    "mData": "action",
                    "mRender": function (data, type, full) {
                        return '<div class="lst_tbl_drop_outer">' +
                            '<span class=""> ' +
                            '<img src="' + baseUrl + '/assets/images/sett_icon.png' + '"  alt="#"> </span>' +
                            ' <ul>' +
                            ' <li id="' + full.id + '"  data-action="edit" ><a data-action="edit" href="' + baseUrl + '/lookup/create?id=' + full.id + '"> <img src="' + baseUrl + '/assets/images/edit_icon.png' + '" alt="#"> Edit</a></li> ' +
                            ' <li id="' + full.id + '" ><a data-action="delete" href="' + baseUrl + '/lookup/delete/' + full.id + '" ><img src="' + baseUrl + '/assets/images/del_icon.png' + '" alt="#">  Delete</a></li></ul></div></td>';
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
                var description = $("#lookupListing_info").text();
                $("#lookupListing_info").text("");
                $(".listing_sec_ftr_detail p").html(description);
            }

        });


        oTable = $('#lookupListing').DataTable();
        $('#searchBar').on("change", function () {
            $('#lookupListing').DataTable().search($(this).val()).draw();
        });
    }

    getLookupListing();
    $(".lookup_filters").on("click", function (e) {

        filter = $(this).attr('data-val');
        filterType = $(this).attr('data-column');
        console.log('filter', filter);
        console.log('filterType', filterType);
        oTable.draw();
    });

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

});