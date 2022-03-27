/**
 * Front Script
 */

(function ($, window, document, pluginObject) {
    "use strict";

    $(function () {

    });


    // Remove Booking
    $(document).on('click', '.docpro-remove-booking', function () {

        let elRemoveButton = $(this),
            bookingID = elRemoveButton.data('id'),
            buttonText = elRemoveButton.html();

        if (confirm(pluginObject.confirmText)) {

            elRemoveButton.html(pluginObject.working);

            $.ajax({
                type: 'POST',
                context: this,
                url: pluginObject.ajaxurl,
                data: {
                    'action': 'remove_booking_item',
                    'booking_id': bookingID,
                },
                success: function (response) {
                    if (response.success) {
                        window.location.reload();
                    } else {
                        elRemoveButton.html(buttonText);
                    }
                },
                error: function () {
                    elRemoveButton.html(buttonText);
                }
            });
        }
    });


    // Remove Favourite
    $(document).on('click', '.docpro-remove_fav', function () {

        let elRemoveButton = $(this),
            entityID = elRemoveButton.data('entity'),
            buttonText = elRemoveButton.html();

        elRemoveButton.html(pluginObject.working);

        $.ajax({
            type: 'POST',
            context: this,
            url: pluginObject.ajaxurl,
            data: {
                'action': 'remove_favourite_item',
                'entity_id': entityID,
            },
            success: function (response) {
                if (response.success) {
                    window.location.reload();
                } else {
                    elRemoveButton.html(buttonText);
                }
            },
            error: function () {
                elRemoveButton.html(buttonText);
            }
        });
    });


    $(document).on('ready', function () {
        // $('select:not(.ignore)').niceSelect();
        $('.docpro-table').DataTable();
    });

    //Accordion Box
    if ($('.accordion-box').length) {
        $(".accordion-box").on('click', '.acc-btn', function () {

            let outerBox = $(this).parents('.accordion-box'),
                target = $(this).parents('.accordion');

            if ($(this).hasClass('active') !== true) {
                $(outerBox).find('.accordion .acc-btn').removeClass('active');
            }

            if ($(this).next('.acc-content').is(':visible')) {
                return false;
            } else {
                $(this).addClass('active');
                $(outerBox).children('.accordion').removeClass('active-block');
                $(outerBox).find('.accordion').children('.acc-content').slideUp(300);
                target.addClass('active-block');
                $(this).next('.acc-content').slideDown(300);
            }
        });
    }


    $(document).on('change', '.docpro-auto-submit', function () {
        let formID = $(this).data('form-id');

        if (formID.length > 0) {
            $('#' + formID).submit();
        }
    });


    $(document).on('click', '.docpro-appointment-files > span', function () {
        let fileSource = $(this).find('img').attr('src');
        if (fileSource.length > 0) {
            window.open(fileSource, '_blank');
        }
    });


    $(document).on('click', '.docpro-appointment-files-save', function () {
        let saveButton = $(this),
            bookingID = saveButton.data('id'),
            buttonText = saveButton.html(),
            allFiles = saveButton.parent().find('.appointment-files > div'),
            fileIds = [];

        saveButton.html(pluginObject.saving);

        allFiles.each(function () {
            fileIds.push($(this).find('input').val());
        });

        $.ajax({
            type: 'POST',
            context: this,
            url: pluginObject.ajaxurl,
            data: {
                'action': 'save_appointment_files',
                'file_ids': fileIds,
                'booking_id': bookingID,
            },
            success: function (response) {
                if (response.success) {
                    saveButton.html(response.data);
                    setTimeout(function () {
                        saveButton.html(buttonText);
                    }, 3000);
                } else {
                    saveButton.html(buttonText);
                }
            },
            error: function () {
                saveButton.html(buttonText);
            }
        });
    });


    $(document).on('click', '.docpro-appointment-action', function () {
        let toDo = $(this).data('do'), bookingID = $(this).data('id');

        if (confirm(pluginObject.confirmText) && toDo.length > 0) {
            $.ajax({
                type: 'POST',
                context: this,
                url: pluginObject.ajaxurl,
                data: {
                    'action': 'handle_appointment_action',
                    'to_do': toDo,
                    'booking_id': bookingID,
                },
                success: function (response) {
                    if (response.success) {
                        $(this).parent().find('.docpro-appointment-status').html(response.data);
                    }
                }
            });
        }
    });


    $(document).on('click', '.docpro-repeater-add', function () {
        let repeaterButton = $(this),
            callback = repeaterButton.data('callback'),
            buttonHtml = repeaterButton.html();

        repeaterButton.html(pluginObject.adding);

        $.ajax({
            type: 'POST',
            context: this,
            url: pluginObject.ajaxurl,
            data: {
                'action': 'add_repeater_field',
                'callback': callback,
            },
            success: function (response) {
                if (response.success) {
                    $(this).parent().parent().find('.inner-box').append(response.data);
                }
                repeaterButton.html(buttonHtml);
            }
        });
    });


    $(document).on('click', '.docpro-add-service', function () {
        $.ajax({
            type: 'POST',
            context: this,
            url: pluginObject.ajaxurl,
            data: {
                'action': 'add_service',
            },
            success: function (response) {
                $(this).parent().parent().find('.inner-box').append(response.data);
            }
        });
    });


    $(document).on('click', '.docpro-add-experience', function () {
        $.ajax({
            type: 'POST',
            context: this,
            url: pluginObject.ajaxurl,
            data: {
                'action': 'add_experience',
            },
            success: function (response) {
                $(this).parent().parent().find('.inner-box').append(response.data);
            }
        });
    });


    $(document).on('click', '.docpro-add-education', function () {
        $.ajax({
            type: 'POST',
            context: this,
            url: pluginObject.ajaxurl,
            data: {
                'action': 'add_education',
            },
            success: function (response) {
                $(this).parent().parent().find('.inner-box').append(response.data);
            }
        });
    });


    $(document).on('click', '.docpro-likebox', function () {
        let likeBox = $(this),
            actionTarget = likeBox.hasClass('liked') ? 'unlike' : 'like',
            entityID = likeBox.data('entity');

        likeBox.removeClass('liked unliked');

        $.ajax({
            type: 'POST',
            context: this,
            url: pluginObject.ajaxurl,
            data: {
                'action': 'handle_likebox',
                'target': actionTarget,
                'entity': entityID,
            },
            success: function (response) {
                likeBox.addClass(response.data);
            }
        });
    });


    $(document).on('change', '.docpro-filter', function () {
        let dataTargetFormID = $(this).attr('data-form'),
            dataTargetForm = $('#' + dataTargetFormID);

        if (dataTargetForm.length > 0) {
            dataTargetForm.submit();
        }
    });


    $(document).on('click', '.docpro-items-controller > button', function () {

        let itemsWrapper = $('.docpro-items-wrapper'),
            thisButton = $(this),
            itemsController = thisButton.parent(),
            target = $(this).data('target');

        itemsController.find('button').removeClass('on');
        thisButton.addClass('on');

        itemsWrapper.removeClass('list grid');
        itemsWrapper.addClass(target);
    });

    $(document).on('click', '.tabs-box .tab-buttons .tab-btn', function () {

        let tabButton = $(this),
            tabBox = tabButton.parent().parent().parent(),
            buttonTarget = tabButton.data('target'),
            allButtons = tabButton.parent().find('> li'),
            tabContentAll = tabBox.find('.tabs-content .tab'),
            tabContentTarget = tabContentAll.parent().find('#' + buttonTarget);


        allButtons.removeClass('active-btn');
        tabContentAll.removeClass('active-tab');

        tabButton.addClass('active-btn');
        tabContentTarget.addClass('active-tab');
    });

})(jQuery, window, document, docpro);


function displayPassword() {
    var x = document.getElementById("passwordField");
    if (x.type === "password") {
        x.type = "text";
    } else {
        x.type = "password";
    }
}



