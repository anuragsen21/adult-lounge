$(function () {

    "use strict";

    /*
     *  Custom form validation  
     **/
    $(".form-validation").submit(function (e) {

        //check requst product blank field
        var Status = 0;
        $('.requiredCheck').each(function () {

            var blank_value = $.trim($(this).val());
            var blank_attr = $(this).attr('data-check');

            if (!blank_value) {

                Status = 1;
                $(this).css('border', '1px solid red');
                $(this).siblings(".select2-container").css('border', '1px solid red');
            } else {

                if (blank_attr == 'email') {

                    var reg = /^([A-Za-z0-9_\-\.])+\@([A-Za-z0-9_\-\.])+\.([A-Za-z]{2,4})$/;
                    if (reg.test(blank_value) == false) {
                        Status = 1;
                        $(this).parents('.form-group').addClass("has-error");
                        $(this).parents('.form-group').find('.help-block').html('Enter valid Email address');
                    } else {
                        $(this).parents('.form-group').removeClass("has-error");
                        $(this).parents('.form-group').find('.help-block').html('');
                    }
                }

                if (blank_attr == 'phone') {

                    if (blank_value.length != 10) {
                        Status = 1;
                        $(this).parents('.form-group').addClass("has-error");
                        $(this).parents('.form-group').find('.help-block').html('Enter 10 digit phone number');
                    } else {
                        $(this).parents('.form-group').removeClass("has-error");
                        $(this).parents('.form-group').find('.help-block').html('');
                    }
                }

                $(this).css('border', '');
                $(this).siblings(".select2-container").css('border', '');
            }
        });

        if (Status == 1) {
            $('.status-message').text('(*) Marks field are mandatory to fill up*. Please see the errors above.').slideDown();
            //prevent Default functionality
            e.preventDefault();
            return false;
        } else {
            return true;
        }
    });

    /*
        Common AJAX Submit Form Using Class Name
        */
    $(".commonFormSubmitByAjax").submit(function (e) {

        //prevent Default functionality
        e.preventDefault();
        var form_action = $(this).attr('action');
        var return_to = $(this).attr('return_to');
        //check required field
        var Status = 0;
        $('.commonRequired').each(function () {

            var blank_value = $.trim($(this).val());
            var blank_attr = $(this).attr('data-check');
            if (!blank_value) {
                Status = 1;
                $(this).css('border', '1px solid red');
                $(this).siblings(".select2-container").css('border', '1px solid red');
            } else {

                $(this).css('border', '');
                $(this).siblings(".select2-container").css('border', '');
            }
        });

        if (Status == 1) {
            swal({
                html: true,
                title: "",
                text: "<span style='color:red;'>(*) Marks field are mandatory to fill up.<span>"
            });
        } else {

            $.ajax({
                data: new FormData(this),
                cache: false,
                contentType: false,
                processData: false,
                type: 'POST',
                url: form_action,
                beforeSend: function () {
                    $('.form-submit-button').prop("disabled", true);
                    $('.status-message').text('Please wait...').slideDown();
                },
                success: function (resultData) {
                    $('.status-message').slideUp().text('');
                    var obj = JSON.parse(resultData);
                    if (obj.success == false) {
                        $('.form-submit-button').prop("disabled", false);
                        swal({
                            html: true,
                            title: "",
                            text: '<span style="color:red;">' + obj.message + '</span>'
                        });
                    }

                    if (obj.success == true) {
                        $(".commonFormSubmitByAjax")[0].reset();
                        swal({
                            html: true,
                            title: "",
                            text: '<span style="color:green;">' + obj.message + '</span>'
                        }, function () {
                            window.location.href = return_to;
                        });
                    }
                }
            });
        }
    });
    /*
        Admin logout
    */
    $('.admin-logout').click( function(){
        
        swal({
            title: "Confirmation",
            text: "Are you sure you want to log out?",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#DD6B55",
            confirmButtonText: 'Yes, logout!',
            closeOnConfirm: false,
            //closeOnCancel: false
        },
        function(){             
            window.location = base_url+"admin/logout";
        });
    });
    
    /* allow only letter & space */
    $(".allowOnlyLetter").keypress(function (event) {
        var inputValue = event.charCode;
        if (!(inputValue >= 65 && inputValue <= 120) && (inputValue != 32 && inputValue != 0)) {
            event.preventDefault();
        }
    });

    /* Change status */
    $(document).on('click', '.change-status', function () {

        var module = this;
        var id = $(this).attr('id');
        var table = $(this).attr('data-table');

        if (id && table) {
            $.ajax({
                type: 'POST',
                url: base_url + $(this).attr('data-url'),
                data: {
                    id: id,
                    table: table
                },
                success: function (resultData) {
                    $(module).html(resultData);
                    swal("Successfully change status.")
                }
            });
        }
    });

    /*
        delete
    */
    $(document).on('click', '.delete-data', function () {

        var $this = $(this);
        swal({
                title: "Confirmation",
                text: "Are you sure you want to delete?",
                type: "warning",
                showCancelButton: true,
                confirmButtonClass: 'btn-danger',
                confirmButtonText: 'Yes',
                closeOnConfirm: false
            },
            function () {
                $.post({
                    type: $this.data('method'),
                    url: $this.data('url'),
                    data: {
                        id: $this.attr('id')
                    }
                }).done(function (data) {
                    var returndata = JSON.parse(data);
                    if (returndata.success == true) {
                        $this.parents('.table-tr').remove();
                        swal.close()
                    }

                    if (returndata.success == false) {
                        swal(returndata.message)
                    }
                });
            });

    });

    
    /* ***
        Restrict Special Characters in textbox 
        ***/
    $(document).on('keyup', '.restrict_special', function () {

        var yourInput = $(this).val();
        var re = /[`~!@#$%^&*()_|+\-=?;:'",.<>\{\}\[\]\\\/]/gi;
        var isSplChar = re.test(yourInput);
        if (isSplChar) {
            var no_spl_char = yourInput.replace(/[`~!@#$%^&*()_|+\-=?;:'",.<>\{\}\[\]\\\/]/gi, '');
            $(this).val(no_spl_char);
        }
    });

    /****
        Allow only number and dot
    **/
    $(".allowNumberDot").keyup(function () {
        var $this = $(this);
        $this.val($this.val().replace(/[^\d.]/g, ''));
    });



});