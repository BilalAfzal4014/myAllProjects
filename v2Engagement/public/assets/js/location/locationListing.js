$(document).ready(function (e) {
    var filter = null;
    var filterType = null;
    var oTable = null;
    function getLookupListing() {
        var url = baseUrl + '/location/get';
        $('#locationListing').DataTable({
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
                    "data": "address",
                    "render": $.fn.dataTable.render.text()
                },
                {
                    "data": "lat",
                    "render": $.fn.dataTable.render.text()
                },
                {
                    "data": "lng",
                    "render": $.fn.dataTable.render.text()
                },
                {
                    "data": "currency",
                    "render": $.fn.dataTable.render.text()
                },
                {
                    "data": "radius",
                    "render": $.fn.dataTable.render.text()
                },
                {
                    "data": "created_at",
                    "render": $.fn.dataTable.render.text()
                },
            ],
            "aoColumnDefs": [
                {
                    "aTargets": [7],
                    "mData": "action",
                    "mRender": function (data, type, full) {
                        return '<div class="lst_tbl_drop_outer"><span class=""> <img src="' + baseUrl + '/assets/images/sett_icon.png' + '"  alt="#"> </span><ul><li id="' + full.id + '"  data-action="edit" ><a data-action="edit" href="' + baseUrl + '/location/edit/' + full.id + '"> <img src="' + baseUrl + '/assets/images/edit_icon.png' + '" alt="#"> Edit</a></li>  <li id="' + full.id + '" ><a data-action="delete" href="' + baseUrl + '/location/delete/' + full.id + '" ><img src="' + baseUrl + '/assets/images/del_icon.png' + '" alt="#">  Delete</a></li></ul></div></td>';
                    },
                    "visible": true,
                    "searchable": false,
                    "orderable": false
                }
            ],
            "order": [[5, "desc"]],
            "initComplete": function (settings, json) {
                // call after loaded only first time
            },
            "drawCallback": function (settings, json) {
                //call after every event cause change in datatable
                var description = $("#locationListing_info").text();
                $("#locationListing_info").text("");
                $(".listing_sec_ftr_detail p").text(description);
            }

        });
        oTable = $('#locationListing').DataTable();
        $('#searchBar').on("change",function () {
            $('#locationListing').DataTable().search($(this).val()).draw();
        });
    }

    getLookupListing();

    $(document).on('click', '.lst_tbl_drop_outer ul li a', function () {


        if($(this).attr('data-action') == "delete"){


            swal({
                title: "Are you sure?",
                icon: "warning",
                buttons: true,
                dangerMode: true,
            }).then((willDelete) => {
                if (!willDelete) {
                    return false;
                }else{

                    $.get($(this).attr('href'),function (data) {
                        if(data.status_code == 200){
                            oTable.draw();
                        }
                    });
                }
            });

        }else {
            window.location = $(this).attr('href');
        }
    });

});