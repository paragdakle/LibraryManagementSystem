$(document).ready(function() {

    /*-----------------------------------/
    /*	TOP NAVIGATION AND LAYOUT
    /*----------------------------------*/

    $('.btn-toggle-fullwidth').on('click', function() {
        if (!$('body').hasClass('layout-fullwidth')) {
            $('body').addClass('layout-fullwidth');
        } else {
            $('body').removeClass('layout-fullwidth');
        }

        $(this).find('.lnr').toggleClass('lnr-arrow-left-circle lnr-arrow-right-circle');

        if ($(window).innerWidth() < 1025) {
            if (!$('body').hasClass('offcanvas-active')) {
                $('body').addClass('offcanvas-active');
            } else {
                $('body').removeClass('offcanvas-active');
            }
        }
    });

    $(window).on('load resize', function() {
        if ($(this).innerWidth() < 1025) {
            $('body').addClass('layout-fullwidth');
        } else {
            $('body').removeClass('layout-fullwidth');
        }
    });

    $(window).on('load', function() {
        if ($(window).innerWidth() < 1025) {
            $('.btn-toggle-fullwidth').find('.icon-arrows')
                .removeClass('icon-arrows-move-left')
                .addClass('icon-arrows-move-right');
        }

        /* to make sure footer on the bottom, 
        adjust .main height when it's shorter than .sidebar. Timeout to wait chart rendered */

        setTimeout(function() {

            if ($('.main').height() < $('.sidebar').height()) {
                $('.main').height($('.sidebar').height());
            }
        }, 500);

    });


    /*-----------------------------------/
    /*	SIDEBAR NAVIGATION
    /*----------------------------------*/

    $('.sidebar a[data-toggle="collapse"]').on('click', function() {
        if ($(this).hasClass('collapsed')) {
            $(this).addClass('active');
        } else {
            $(this).removeClass('active');
        }
    });

    if ($('.sidebar-scroll').length > 0) {
        $('.sidebar-scroll').slimScroll({
            height: '85%',
            wheelStep: 2,
        });
    }

    /*-----------------------------------/
    /*	PANEL FUNCTIONS
    /*----------------------------------*/

    $('#btn_loan').on('click', function() {
        if ($('#loan_search_text_box').val().trim() == "") {
            showError("Kindly provide a search term!");
        } else {
            window.location = '/book/search?term=' + $('#loan_search_text_box').val();
        }
    });

    $('#btn_return').on('click', function() {
        if ($('#return_search_text_box').val().trim() == "") {
            showError("Kindly provide a search term!");
        } else {
            window.location = '/bookloans/search?term=' + $('#return_search_text_box').val();
        }
    });

    $('#btn_checkout').on('click', function() {
        if ($('#co_book_isbn_text_box').val().trim() == "") {
            showError("Kindly provide a book isbn!");
            return;
        }
        if ($('#co_card_no_text_box').val().trim() == "") {
            showError("Kindly provide a user card number!");
            return;
        }
        $(this).attr('disabled', true);
        var initialText = $(this)[0].innerHTML;
        $(this)[0].innerHTML = '<i class="fa fa-spinner fa-spin"></i> Processing...';
        var parameters = {
            'isbn': $('#co_book_isbn_text_box').val(),
            'card_id': $('#co_card_no_text_box').val()
        }
        $.post('/api/book/loan', parameters, 'json')
            .success(function(data) {
                if (data.status == "success") {
                    showMessage(data.data);
                }
            })
            .error(function(data) {
                showError(data.responseJSON.data.errorMessage);
            });
        $(this).attr('disabled', false);
        $(this)[0].innerHTML = initialText;
    });

    $('#btn_checkin').on('click', function() {
        if ($('#ci_book_isbn_text_box').val().trim() == "") {
            showError("Kindly provide a book isbn!");
            return;
        }
        if ($('#ci_card_no_text_box').val().trim() == "") {
            showError("Kindly provide a user card number!");
            return;
        }
        $(this).attr('disabled', true);
        var initialText = $(this)[0].innerHTML;
        $(this)[0].innerHTML = '<i class="fa fa-spinner fa-spin"></i> Processing...';
        var parameters = {
            'isbns': [$('#ci_book_isbn_text_box').val()],
            'card_id': $('#ci_card_no_text_box').val()
        }
        $.post('/api/book/return', parameters, 'json')
            .success(function(data) {
                if (data.status == "success") {
                    showMessage(data.data);
                }
            })
            .error(function(data) {
                showError(data.responseJSON.data.errorMessage);
            });
        $(this).attr('disabled', false);
        $(this)[0].innerHTML = initialText;
    });

    $('#btn_checkin_bk').on('click', function() {
        if (($('#card_no_text_box').length) && ($('#card_no_text_box').val().trim() == "")) {
            showError("Kindly provide a card number!");
            return;
        }
        $(this).attr('disabled', true);
        var initialText = $(this)[0].innerHTML;
        $(this)[0].innerHTML = '<i class="fa fa-spinner fa-spin"></i> Processing...';
        var parameters = {
            'isbns': [$(this).data('isbn')],
            'card_id': ''
        }
        if ($('#card_no_text_box').length) {
            parameters.card_id = $('#card_no_text_box').val();
        } else {
            parameters.card_id = $(this).data('card');
        }
        $.post('/api/book/return', parameters, 'json')
            .success(function(data) {
                if (data.status == "success") {
                    showMessage(data.data);
                    window.location.reload();
                }
            })
            .error(function(data) {
                showError(data.responseJSON.data.errorMessage);
            });
        $(this).attr('disabled', false);
        $(this)[0].innerHTML = initialText;
    });

    $('#btn_bk_checkout').on('click', function() {
        if ($('#card_no_text_box').val().trim() == "") {
            showError("Kindly provide a user card number!");
            return;
        }
        $(this).attr('disabled', true);
        var initialText = $(this)[0].innerHTML;
        $(this)[0].innerHTML = '<i class="fa fa-spinner fa-spin"></i> Processing...';
        var parameters = {
            'isbn': $(this).data('isbn'),
            'card_id': $('#card_no_text_box').val()
        }
        $.post('/api/book/loan', parameters, 'json')
            .success(function(data) {
                if (data.status == "success") {
                    showMessage(data.data);
                    window.location.reload();
                }
            })
            .error(function(data) {
                showError(data.responseJSON.data.errorMessage);
            });
        $(this).attr('disabled', false);
        $(this)[0].innerHTML = initialText;
    });

    $('#btn_pay_fine').on('click', function() {
        $(this).attr('disabled', true);
        var initialText = $(this)[0].innerHTML;
        $(this)[0].innerHTML = '<i class="fa fa-spinner fa-spin"></i> Processing...';
        var parameters = {
            'loan_ids': [$(this).data('loan')],
            'card_id': $(this).data('card')
        }
        $.post('/api/borrower/payfine', parameters, 'json')
            .success(function(data) {
                if (data.status == "success") {
                    showMessage(data.data);
                    window.location.reload();
                }
            })
            .error(function(data) {
                showError(data.responseJSON.data.errorMessage);
            });
        $(this).attr('disabled', false);
        $(this)[0].innerHTML = initialText;
    });

    $('#btn_clear_fines').on('click', function() {
        $(this).attr('disabled', true);
        var initialText = $(this)[0].innerHTML;
        $(this)[0].innerHTML = '<i class="fa fa-spinner fa-spin"></i> Processing...';
        var parameters = {
            'card_id': $(this).data('card')
        }
        $.post('/api/borrower/payfine', parameters, 'json')
            .success(function(data) {
                if (data.status == "success") {
                    showMessage(data.data);
                }
            })
            .error(function(data) {
                showError(data.responseJSON.data.errorMessage);
            });
        $(this).attr('disabled', false);
        $(this)[0].innerHTML = initialText;
    });

    $('#btn_add_new_user').on('click', function() {
        window.location = "/user/add";
    });

    $('#btn_add').on('click', function() {
        if ($('#user_name_text_box').val().trim() == "") {
            showError("Kindly provide user name!");
            return;
        }
        if ($('#user_ssn_text_box').val().trim() == "") {
            showError("Kindly provide user ssn!");
            return;
        }
        if ($('#user_ssn_text_box').val().length != 9) {
            showError("Kindly provide a valid SSN!");
            return;
        }
        if ($('#user_email_text_box').val().trim() == "") {
            showError("Kindly provide user email!");
            return;
        } else {
            var x = $('#user_email_text_box').val();
            var atpos = x.indexOf("@");
            var dotpos = x.lastIndexOf(".");
            if (atpos < 1 || dotpos < atpos + 2 || dotpos + 2 >= x.length) {
                showError("Kindly provide a valid user email!");
                return;
            }
        }
        if ($('#user_address_text_box').val().trim() == "") {
            showError("Kindly provide user address!");
            return;
        }
        if ($('#user_city_text_box').val().trim() == "") {
            showError("Kindly provide user city!");
            return;
        }
        if ($('#user_state_text_box').val().trim() == "") {
            showError("Kindly provide user state!");
            return;
        }
        if ($('#user_phone_text_box').val().trim() == "") {
            showError("Kindly provide user phone number!");
            return;
        } else {
            var isnum = /^\d+$/.test($('#user_phone_text_box').val());
            if (!isnum) {
                showError("Kindly provide a valid user phone number!");
                return;
            }
        }
        $(this).attr('disabled', true);
        var initialText = $(this)[0].innerHTML;
        $(this)[0].innerHTML = '<i class="fa fa-spinner fa-spin"></i> Processing...';
        var parameters = {
            'ssn': $('#user_ssn_text_box').val(),
            'name': $('#user_name_text_box').val(),
            'email': $('#user_email_text_box').val(),
            'address': $('#user_address_text_box').val(),
            'city': $('#user_city_text_box').val(),
            'state': $('#user_state_text_box').val(),
            'phone': $('#user_phone_text_box').val(),
        }
        $.post('/api/borrower/add', parameters, 'json')
            .success(function(data) {
                if (data.status == "success") {
                    showMessage(data.data);
                }
            })
            .error(function(data) {
                showError(data.responseJSON.data.errorMessage);
            });
        $(this).attr('disabled', false);
        $(this)[0].innerHTML = initialText;
    });

    /*-----------------------------------/
    /*	PANEL FUNCTIONS
    /*----------------------------------*/

    // panel remove
    $('.panel .btn-remove').click(function(e) {

        e.preventDefault();
        $(this).parents('.panel').fadeOut(300, function() {
            $(this).remove();
        });
    });

    // panel collapse/expand
    var affectedElement = $('.panel-body');

    $('.panel .btn-toggle-collapse').clickToggle(
        function(e) {
            e.preventDefault();

            // if has scroll
            if ($(this).parents('.panel').find('.slimScrollDiv').length > 0) {
                affectedElement = $('.slimScrollDiv');
            }

            $(this).parents('.panel').find(affectedElement).slideUp(300);
            $(this).find('i.lnr-chevron-up').toggleClass('lnr-chevron-down');
        },
        function(e) {
            e.preventDefault();

            // if has scroll
            if ($(this).parents('.panel').find('.slimScrollDiv').length > 0) {
                affectedElement = $('.slimScrollDiv');
            }

            $(this).parents('.panel').find(affectedElement).slideDown(300);
            $(this).find('i.lnr-chevron-up').toggleClass('lnr-chevron-down');
        }
    );


    /*-----------------------------------/
    /*	PANEL SCROLLING
    /*----------------------------------*/

    if ($('.panel-scrolling').length > 0) {
        $('.panel-scrolling .panel-body').slimScroll({
            height: '430px',
            wheelStep: 2,
        });
    }

    if ($('#panel-scrolling-demo').length > 0) {
        $('#panel-scrolling-demo .panel-body').slimScroll({
            height: '175px',
            wheelStep: 2,
        });
    }
});

// toggle function
$.fn.clickToggle = function(f1, f2) {
    return this.each(function() {
        var clicked = false;
        $(this).bind('click', function() {
            if (clicked) {
                clicked = false;
                return f2.apply(this, arguments);
            }

            clicked = true;
            return f1.apply(this, arguments);
        });
    });

}

function showMessage(message) {
    $context = 'info';
    $message = message;
    $positionClass = 'toast-top-right';
    toastr.remove();
    toastr[$context]($message, '', { positionClass: $positionClass });
}

function showError(message) {
    $context = 'error';
    $message = message;
    $positionClass = 'toast-top-right';
    toastr.remove();
    toastr[$context]($message, '', { positionClass: $positionClass });
}