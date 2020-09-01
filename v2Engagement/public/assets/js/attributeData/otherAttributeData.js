$(document).ready(function () {
    var oTable;


    $('.dataType li a').bind('click', function () {
        dataType = $(this).attr('data-action');
        $("#otherAttributeDataListing").DataTable().destroy();
        otherAttributeDataRequest(dataType);
    })

    function otherAttributeDataRequest(dataType = '') {

        oTable = $('#otherAttributeDataListing').DataTable({
            "processing": true,
            "serverSide": true,
            "searching": true,
            "bLengthChange": false,
            "iDisplayLength": 25,
            "ajax": {
                "url": baseUrl + '/other-attribute-data-dt?data_type=' + dataType,
                "dataType": "json",
                "type": "GET",
            },
            "columns": [
                {
                    "data": "code",
                    "render": $.fn.dataTable.render.text()
                },
                {
                    "data": "value",
                    "render": $.fn.dataTable.render.text()
                },
                {
                    "data": "created_at",
                    "render": $.fn.dataTable.render.text()
                },
                {
                    "data": "data_type",
                    "render": $.fn.dataTable.render.text()
                },
                {"data": "action"}
            ],
            "aoColumnDefs": [
                {
                    "aTargets": [4],
                    "mData": "action",
                    "mRender": function (data, type, full) {
                        console.log('full', full);
                        return '<div class="lst_tbl_drop_outer">' +
                            '<span class=""> ' +
                            '<img src="' + baseUrl + '/assets/images/sett_icon.png' + '"  alt="#"> </span>' +
                            ' <ul>' +
                            ' <li id="' + full.id + '"  data-action="edit" ><a href="#"> <img src="' + baseUrl + '/assets/images/edit_icon.png' + '" alt="#">Bind Value</a></li> ' +
                            ' <li id="' + full.id + '" data-action="delete"><a  href="#" ><img src="' + baseUrl + '/assets/images/del_icon.png' + '" alt="#">  Delete</a></li></ul></div></td>';
                    },
                    "visible": true,
                    "searchable": false,
                    "orderable": false
                }
            ],
            "order": [[2, "DESC"]],
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

    otherAttributeDataRequest();

    $(document).on('click', '.lst_tbl_drop_outer ul li', function () {
        var dataAction = $(this).attr('data-action');
        var id = $(this).attr('id');
        console.log('dataAction', dataAction);
        console.log('id', id);
        if (dataAction == 'delete') {
            $.ajax({
                type: 'GET',
                url: baseUrl + "/deleteOtherAttributeData/" + id,
                success: function (response) {
                    if (response.status == 200) {
                        oTable.ajax.reload()
                        toastr.success(response.message);
                    } else {
                        toastr.error(response.message);
                    }

                }, error: function (e) {
                    toastr.error(e);
                }
            });
        } else {
            $.ajax({
                type: 'GET',
                url: baseUrl + "/editOtherAttributedata/" + id,
                success: function (response) {
                    console.log('resp', response);
                    $('#campaignOtherDetail').html(response);
                    $('#campaignOtherAttributedata').modal('show');
                }, error: function (e) {
                    toastr.error(e);
                }
            });
        }

    });
});
