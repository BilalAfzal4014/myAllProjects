$(document).ready(function () {
    $(document).on('click', '.pass_change_btn', function (e) {
        e.preventDefault();
        $('.pass_changer').slideToggle();
    });

    $(document).on('click', '.check_all', function () {
        $('td').find(':checkbox').prop('checked', this.checked);
    });

});