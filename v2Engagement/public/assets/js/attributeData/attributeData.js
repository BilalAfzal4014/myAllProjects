$(document).ready(function () {


    var filter = null;
    var filterType = null;

    var oTable = null;
    $('.db_list_right_sec').on('click','li[data-action="edit"]', function(){

        window.location = baseUrl+"/backend/attribute/user/Stat/"+$(this).attr("id");
    });
    getAttributeDataListingAjax();

    function getAttributeDataListingAjax() {
        var url = baseUrl + '/backend/attribute/attributeDataFilter';
        $("#attributeDataListing").DataTable({
            dom: 'Bfrtip',
            buttons: [
                {
                    extend: 'csv',
                    text: 'Import Data',
                    exportOptions: {
                        modifier: {
                            search: 'none'
                        }
                    },

                }
            ],
            "order": [[5, "desc"]],

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
            "aoColumnDefs": [
                {
                    "targets": [0],
                    "visible": false,
                    "searchable": false
                },
                {
                    "targets": [1],
                    "searchable": false,
                },
                {
                    "targets": [2],
                    "searchable": false,
                },
                {
                    "targets": [3],
                    "searchable": false,
                },
                {
                    "aTargets": [6],
                    "mData": "action",
                    "mRender": function (data, type, full) {

                        console.log(full[7]);
                        var actionHtml = '';
                        if(full[7] == 1){

                            actionHtml = '<li id="' + full[0] + '" data-app-name="'+full[4]+'" data-action-user="0" data-user-id="'+full[6]+'"  data-action="delete">' +
                            '<a href="#"><img src="' + baseUrl + '/assets/images/del_icon.png' + '" alt="#"> ' +
                            'Inactive' +
                            '</a>' +
                            '</li>';
                        }else{

                            actionHtml = '<li id="' + full[0] + '" data-app-name="'+full[4]+'" data-action-user="1" data-user-id="'+full[6]+'"  data-action="delete">' +
                                '<a href="#"><img src="' + baseUrl + '/assets/images/del_icon.png' + '" alt="#"> ' +
                                'Active' +
                                '</a>' +
                                '</li>';
                        }
                        return '<div class="lst_tbl_drop_outer"><span class=""><img src="' + baseUrl + '/assets/images/sett_icon.png' + '"  alt="#"></span><ul>' +
                            '<li id="' + full[0] + '"  data-action="edit">' +
                            '<a href="' + baseUrl + '/backend/attribute/user/Stat/' + full[0] + '"><img src="' + baseUrl + '/assets/images/view_icon.png' + '" alt="#">' +
                            ' View' +
                            '</a>' +
                            '</li>' +actionHtml+
                            '</ul>' +
                            '</div>';
                    },
                    "visible": true,
                    "searchable": false,
                    "orderable": false
                },
                {
                    "aTargets": [4],
                    "visible": true
                },

            ],
            "drawCallback": function (settings, json) {
                //call after every event cause change in datatable
                var description = $("#attributeDataListing_info").text();
                $("#attributeDataListing_info").text("");
                $(".listing_sec_ftr_detail p").text(description);
            }
        });
        var oTable = $('#attributeDataListing').DataTable();
        $('#searchBar').on("change",function () {
            oTable.search($(this).val()).draw();
        });

    }


    $(".filter").on("click",function (e) {

        filter = $(this).attr("data-action");
        filterType = $(this).attr("data-type");
        $("#attributeDataListing").DataTable().draw();

    });


    $('.db_list_right_sec').on('click','li[data-action="delete"]', function(){



        swal({
            title: "Are you sure?",
            icon: "warning",
            buttons: true,
            dangerMode: true,
        }).then((willDelete) => {
            if (!willDelete) {
                return false;
            }else{

                $rowId = $(this).attr('id');
                $appName = $(this).attr('data-app-name');
                $userId = $(this).attr('data-user-id');
                $action = $(this).attr('data-action-user');
                $.ajax({
                    url: baseUrl+"/attribute-data/delete-attr",
                    type: "POST",
                    data: 'rowId=' + $rowId+'&appName='+$appName+'&userId='+$userId+"&action="+$action,
                    success: function (res) {

                        // $('#attributeDataListing').DataTable().draw();
                        window.location.href = baseUrl+"/backend/attribute/attributeData";
                    },
                });
            }
        });
    });

    $(document).on('click', '#importData', function(e) {

        $(".buttons-csv").trigger("click");

    });
});
