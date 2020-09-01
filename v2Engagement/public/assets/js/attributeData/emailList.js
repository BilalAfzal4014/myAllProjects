$(document).ready(function () {
    var oTable;
    emailListRequest();


    function emailListRequest(userType = '') {

        oTable = $('#emailListing').DataTable({
            "processing": true,
            "serverSide": true,
            "searching": true,
            "bLengthChange": false,
            "iDisplayLength": listingSize,
            "ajax": {
                "url": emailListDT + '?userType=' + userType,
                "dataType": "json",
                "type": "GET",
            },
            "columns": [
                {
                    "data": "email",
                    "render": $.fn.dataTable.render.text()
                },
                {
                    "data": "type",
                    "render": $.fn.dataTable.render.text()
                },
                {
                    "data": "created_date",
                    "render": $.fn.dataTable.render.text()
                },
                {
                    "data": "action",
                },
                // {"data": "action"},
            ],
            "aoColumnDefs": [
                {
                    "aTargets": [3],
                    "mData": "action",
                    "mRender": function (data, type, full) {
                        return '<div class="lst_tbl_drop_outer"><span class=""><img src="' + baseUrl + '/assets/images/sett_icon.png' + '"  alt="#"></span><ul><li id="' + full.id + '"  data-action="delete"><a href="#"><img src="' + baseUrl + '/assets/images/del_icon.png' + '" alt="#"> Delete</a></li></ul></div></td>';
                    },
                    "visible": true,
                    "searchable": false,
                    "orderable": false
                },
            ],
            "order": [[3, "asc"]],
            "initComplete": function (settings, json) {
                // call after loaded only first time
            },
            "drawCallback": function (settings, json) {
                //call after every event cause change in datatable
                var description = $("#attributeDataListing_info").text();
                $("#attributeDataListing_info").text("");
                $(".listing_sec_ftr_detail p").text(description);
            }
        });
    }


    $('#searchBar').on("change", function () {
        oTable.search($(this).val()).draw();
    });


    $('.userType li a').bind('click', function () {
        userType = $(this).attr('data-action');
        $("#emailListing").DataTable().destroy();
        emailListRequest(userType);
    })


    $('.db_list_right_sec').on('click', 'li[data-action="delete"]', function () {


        swal({
            title: "Are you sure?",
            icon: "warning",
            buttons: true,
            dangerMode: true,
        }).then((willDelete) => {
            if (!willDelete) {
                return false;
            }else{

                id = $(this).attr('id');
                $.ajax({
                    url: deleteEmailList,
                    type: "POST",
                    data: 'id=' + id,
                    success: function (msg) {
                        //window.location.href = emailListView;
                        $('.MSG').html(msg);
                        oTable.draw();
                    },
                });
            }
        });

    })
});
