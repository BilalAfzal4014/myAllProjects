$(document).ready(function () {

    galleryListing();

    var table;
    var filter;
    var filterType;

    function galleryListing() {
        var url = baseUrl + '/gallery/fetch';
        $('#gallery').DataTable({
            "processing": false,
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
                {"data": "image"},
                {"data": "name"},
                {"data": "dimensions"},
                {"data": "size"},
                {"data": "date"},
                {"data": "action"},
            ],
            "aoColumnDefs": [

                {
                    "targets": [0],
                    "orderable": false,
                    "searchable": false
                },
                {
                    "targets": [2],
                    "orderable": false,
                    "searchable": false
                }, {
                    "targets": [3],
                    "orderable": false,
                    "searchable": false
                },{
                    "targets": [4],
                    "orderable": false,
                    "searchable": false
                },
                {
                    "aTargets": [5],
                    "mData": "action",
                    "mRender": function (data, type, full) {
                        return '<div class="lst_tbl_drop_outer"><span class=""> <img src="' + baseUrl + '/assets/images/sett_icon.png' + '"  alt="#"> </span><ul><li><a href="'+full.url+'" data-lightbox="roadtrip'+full.id+'"> <img src="' + baseUrl + '/assets/images/view_icon.png' + '" alt="#"> View</a></li> <li><a href="#" onclick="newsImage('+full.id+')" ><img src="' + baseUrl + '/assets/images/del_icon.png' + '" alt="#">  Delete</a></li></ul></div></td>';
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
                var description = $("#gallery_info").text();
                $("#gallery_info").text("");
                $(".listing_sec_ftr_detail p").text(description);
            }

        });
        table = $('#gallery').DataTable();
        $('#searchBar').on("change", function () {
            $('#gallery').DataTable().search($(this).val()).draw();
        });
    }


});







