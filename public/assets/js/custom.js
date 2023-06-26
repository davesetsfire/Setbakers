let child_window_handle = null;
let displayInformationModal;

window.addEventListener('message', function (e) {
    if (e.data == 'success') {
        location.reload();
    }
}, false);

function locationChangeHandler() {
    var place = $('#ort-order-plz').val();
    var distance = $('#ortorder').select2('data')[0].text;
    var distanceValue = $('#ortorder').val();
    $('#location-distance .value-wrap .loc').text(place);
    if (distanceValue > 0) {
        $('#location-distance .value-wrap .km').text(distance);
    } else {
        $('#location-distance .value-wrap .km').text('');
    }
    if (place !== '') {
        $('#location-distance .value-wrap').css('display', 'flex');
    } else {
        $('#location-distance .value-wrap').css('display', 'none');
        $('#location-distance .value-wrap').removeClass('d-flex');
    }
}

window.onload = function () {

    (function () {

        $('.loader-container').fadeOut();

        $('[data-toggle="tooltip"]').tooltip();

        $('.pr-listing.owl-carousel').owlCarousel({
            margin: 10,
            loop: false,
            autoWidth: true,
        })

        function userBoxHeight() {
            if ($(window).width() > 767) {
                var heightRightCol = $('.data-wrap .data_user').height();
                var heightLeftCol = $('.data-wrap .data_fundus').height();
                var height = heightLeftCol > heightRightCol ? heightLeftCol : heightRightCol;
                $('.data-wrap .min-height-800').css('min-height', height);
            }
        }

        userBoxHeight();

        var container = document.querySelectorAll('.products-container');

        $('.funds-details .funds-info .short-description').click(function (event) {
            $('.funds-details .funds-info .short-description').toggleClass('expand-description');
            // $('.funds-details .funds-info .full-description').show();
        });

        // $('.funds-details .funds-info .full-description button').click(function (event) {
        //    $('.funds-details .funds-info .full-description').hide();
        //     $('.funds-details .funds-info .short-description') .removeClass('expand-description');
        // });

        if ($("#fundus_description_user").length > 0) {
            $('.word-count #word_left').text($("#fundus_description_user").val().length);
        }

        $("#fundus_description_user").on('keyup', function () {
            let charCounts = $(this).val().length;

            if (charCounts > 400) {
                let description = $(this).val();
                $(this).val(description.substr(0, 400));
                charCounts = $(this).val().length;
            }

            $('.word-count #word_left').text(charCounts);

        });

        function inializeRowGrid() {
            container.forEach(function (item, index, arr) {
                rowGrid(item, {
                    itemSelector: '.item-box',
                    minMargin: 10,
                    maxMargin: 25,
                    firstItemClass: 'first-item',
                    lastRowClass: 'last-row',
                    minWidth: 500
                });
            })
        }

        inializeRowGrid();

        $(".collapse").on('shown.bs.collapse', function () {
            inializeRowGrid();
            $('.select2-single').select2({
                placeholder: "-Please Select-",
            });
        });
        $(".collapse").on('hidden.bs.collapse', function () {
            inializeRowGrid();
            $('.select2-single').select2({
                placeholder: "-Please Select-",
            });
        });


        //crop profile pic

        let result = document.querySelector('.profile-upload-result'),
                save = document.querySelector('#cropProfilePic'),
                cropped = document.querySelector('.profile-pic .cropped'),
                upload = document.querySelector('#file-input'),
                cropper = '';

        if (upload) {
            upload.addEventListener('change', (e) => {
                if (e.target.files.length) {
                    const reader = new FileReader();
                    reader.onload = (e) => {
                        if (e.target.result) {
                            let img = document.createElement('img');
                            img.id = 'image';
                            img.src = e.target.result
                            result.innerHTML = '';
                            result.appendChild(img);
                            $('#cropImage').modal({
                                backdrop: 'static',
                                keyboard: false,
                                show: true
                            });
                            $("#cropImage").on('shown.bs.modal', function () {
                                cropper = new Cropper(img, {
                                    aspectRatio: 1 / 1,
                                    autoCropArea: 1
                                });
                            });
                        }
                    };
                    reader.readAsDataURL(e.target.files[0]);
                }
            });
        }

        if (save) {
            save.addEventListener('click', (e) => {
                e.preventDefault();
                let imgSrc = cropper.getCroppedCanvas({
                    width: 500
                }).toDataURL();

                $('.profile-wrapper .delete-img-btn').show();
                $('.profile-wrapper .change-profile-pic').hide();
                $('.profile-wrapper .profile-pic img').css('opacity', 1);

                fetch(imgSrc)
                        .then(res => res.blob())
                        .then((myBlob) => {
                            let fileInputElement = document.getElementById('fundus_profile_picture');
                            let container = new DataTransfer();
                            let file = new File([myBlob], "profile_picture.jpg", {type: myBlob.type, lastModified: new Date().getTime()});

                            container.items.add(file);
                            fileInputElement.files = container.files;
                        });

                cropped.src = imgSrc;
                $('#cropImage').modal('hide');
            });
        }

        // crop prifle end


    })();

};


function deleteProfilePic(el) {
    $(el).prev('img').css('opacity', 0);
    $(el).parents('.profile-wrapper').find('input[type=file]').val('');
    $(el).parents('.profile-wrapper').find('.change-profile-pic').show();
    $(el).hide();
}

$(document).on('click', '#cancelProfileCrop', function (event) {
    $('.profile-wrapper .change-profile-pic').find('input[type=file]').val('');
});




function resetFormErrorMessages(formId) {
    $('#' + formId + ' .error').each(function (key, errorObject) {
        $(this).text('');
        $(this).val('');
    });

    $('#' + formId + ' .success').each(function (key, errorObject) {
        $(this).text('');
        $(this).val('');
    });

    $('#' + formId + ' .form-control.error').each(function (key, errorObject) {
        $(this).removeClass('error');
    });
}

function progressIndicator(modelId, displayAction) {
    if (displayAction == 'show') {
        $('#' + modelId + ' .model_disabled_container_class').addClass('model_disabled_container');
        $('#' + modelId + ' .spinner-border.button_spinner').addClass('start');
    } else {
        $('#' + modelId + ' .model_disabled_container_class').removeClass('model_disabled_container');
        $('#' + modelId + ' .spinner-border.button_spinner').removeClass('start');
    }
}

function copyToClipboard(element) {
    var $temp = $("<input>");
    $("body").append($temp);
    $temp.val($(element).text()).select();
    document.execCommand("copy");
    $temp.remove();
}

function showProjectForm() {
    $('#login-modal').modal('hide');
    $('#project-form').modal('show');
}
var paymentPopupFlag = false;
var registrationFields = {
    'create-account': ['first_name', 'last_name', 'email', 'phone_number'],
    'project-data': ['project_name', 'company_name', 'ust_id', 'street', 'house_number', 'postal_code', 'location', 'country'],
    'fundus-data': ['fundus_name', 'fundus_email', 'fundus_phone', 'fundus_phone_number', 'fundus_owner_first_name', 'fundus_owner_last_name',
        'fundus_website', 'fundus_company_name', 'fundus_ust_id', 'fundus_street', 'fundus_house_number', 'fundus_postal_code', 'fundus_location', 'fundus_country'],
};

function registerationHandler(id, back) {
    if (!back && !$('#register_form').valid()) {
        return;
    }
    let accountType = $('input[name="account_type"]:checked').val();
    let addFundusStore = $('input[name="add_fundus_store"]').prop('checked');

    if (id == 'create-account') {
        if (accountType == 'fundus') {
            $('#registeration-popup li[data-id=project-data]').hide();
            $('#registeration-popup .fundus-data-view').show();
            $('#registeration-popup .project-data-view').hide();
            $('#registeration-popup .fundus-data-view').removeClass('col-md-6');
            $('#registeration-popup .fundus-data-view').addClass('col-md-12');
            $('#registeration-popup .fundus-data-view .data').removeClass('col-md-12');
            $('#registeration-popup .fundus-data-view .data').addClass('col-md-6');
            $('#registeration-popup .cancellation_approval').hide();

        } else {
            $('#registeration-popup li[data-id=project-data]').show();
            $('#registeration-popup .fundus-data-view').show();
            $('#registeration-popup .project-data-view').show();
            $('#registeration-popup .fundus-data-view').removeClass('col-md-12');
            $('#registeration-popup .fundus-data-view').addClass('col-md-6');
            $('#registeration-popup .fundus-data-view .data').removeClass('col-md-6');
            $('#registeration-popup .fundus-data-view .data').addClass('col-md-12');
            $('#registeration-popup .cancellation_approval').show();
        }
    } else if (id == 'project-data' && accountType == 'fundus' && back === true) {
        id = 'create-account';
    } else if (id == 'project-data' && accountType == 'fundus') {
        id = 'fundus-data';
    } else if (id == 'fundus-data' && accountType == 'complete' && addFundusStore === false && back === true) {
        id = 'project-data';
        $('#registeration-popup li[data-id="fundus-data"]').show();
    } else if (id == 'fundus-data' && accountType == 'complete' && addFundusStore === false) {
        id = 'comlplete-registeration';
        $('#registeration-popup li[data-id="fundus-data"]').hide();
    }

    if (id == 'comlplete-registeration' && accountType == 'fundus') {
        $('#registeration-popup .fundus-data-view').show();
        $('#registeration-popup .project-data-view').hide();
        $('#registeration-popup .fundus-data-view').removeClass('col-md-6');
        $('#registeration-popup .fundus-data-view').addClass('col-md-12');
        $('#registeration-popup .fundus-data-view .data').removeClass('col-md-12');
        $('#registeration-popup .fundus-data-view .data').addClass('col-md-6');
    } else if (id == 'comlplete-registeration' && accountType == 'complete' && addFundusStore === false) {
        $('#registeration-popup .fundus-data-view').hide();
        $('#registeration-popup .project-data-view').show();
        $('#registeration-popup .project-data-view').removeClass('col-md-6');
        $('#registeration-popup .project-data-view').addClass('col-md-12');
        $('#registeration-popup .project-data-view .data').removeClass('col-md-12');
        $('#registeration-popup .project-data-view .data').addClass('col-md-6');

    } else if (id == 'comlplete-registeration' && accountType == 'complete' && addFundusStore === true) {
        $('#registeration-popup .fundus-data-view').show();
        $('#registeration-popup .project-data-view').show();

        $('#registeration-popup .project-data-view').removeClass('col-md-12');
        $('#registeration-popup .project-data-view').addClass('col-md-6');
        $('#registeration-popup .project-data-view .data').removeClass('col-md-6');
        $('#registeration-popup .project-data-view .data').addClass('col-md-12');

        $('#registeration-popup .fundus-data-view').removeClass('col-md-12');
        $('#registeration-popup .fundus-data-view').addClass('col-md-6');
        $('#registeration-popup .fundus-data-view .data').removeClass('col-md-6');
        $('#registeration-popup .fundus-data-view .data').addClass('col-md-12');
    }

    $('#registeration-popup .body-inner').hide();
    $('#' + id).fadeIn();
    $('#register-form-progress').show();
    $('#registeration-popup li[data-id=' + id + ']').addClass('active');
    $('#registeration-popup li[data-id=' + id + ']').nextAll('li').removeClass('active');

    $.each(registrationFields, function (stepIndex, stepData) {
        $.each(stepData, function (key, value) {
            $('.cls_' + value).html($('#registeration-popup [name="' + value + '"]').val());
            if (['phone_number', 'fundus_phone', 'fundus_website', 'company_name', 'ust_id', 'fundus_company_name', 'fundus_ust_id'].indexOf(value) > -1) {

                if ($('#registeration-popup [name="' + value + '"]').val() != '') {
                    $('.cls_' + value).closest('.registration-disp-field').show();
                } else {
                    $('.cls_' + value).closest('.registration-disp-field').hide();
                }
            }
        });
    });
}

function loadUrl(url) {
    // window.location = url;
    location.reload()
}


function handleDistanceBox() {
    setTimeout(function () {
        if ($('.filter-form #result .data[select2-id="ortorder"] span').length) {
            $('.filter-form #result .data[select2-id="ortorder"]').hide();
        } else {
            $('.filter-form #result .data[select2-id="ortorder"]').show();
        }
    }, 0)
}
handleDistanceBox();


$(function () {

    $('.select2-single').select2({
        placeholder: "-Please Select-",
    }).on('select2:select', function (e) {
        handleDistanceBox();
    });

    $('.select4-single').select2({
        placeholder: "-Please Select-",
        allowClear: true,
    }).on('select2:select', function (e) {
        handleDistanceBox();
    });

    $('.select2-multiple').select2({
        minimumResultsForSearch: Infinity,
    })
    $('.select2-multiple-pr').select2({
        dropdownParent: $('#project-detail')
    })
    $('.select2-theme').select2({
        dropdownParent: $('.favourite_main_section')
    })
    $('.select2-single-download').select2({
        dropdownParent: $('#bulk-upload')
    })


    //show edit btn
    $(".favourite_main_section.edit-products-wrap .collapse").on('show.bs.collapse', function () {
        $(this).parent('.card').find('.card-header .group-theme-button').show();
    });
    $(".favourite_main_section.edit-products-wrap .collapse").on('hide.bs.collapse', function () {
        $(this).parent('.card').find('.card-header .group-theme-button').hide();
    });


    $('.item-add-edit .item-details .price .add-price-plus').click(function (event) {
        let priceInput = $('.price-wrapper-clone .price-wrapper').clone();
        let parentSlug = $('.s-parent-category.active').data('slug');
        if (parentSlug === 'grafik') {
            priceInput.find('.duration_option').remove();
        } else {
            priceInput.find('.duration_graphics_option').remove();
        }
        $('.item-add-edit .item-details .add-price-input').append(priceInput);
    });

    $(document).on('click', '.item-add-edit .item-details .price .add-price.remove', function (e) {
        let selectedPriceId;
        selectedPriceId = $(this).parent().find('input[name="price_index[]"]').val();
        if (selectedPriceId != '') {
            $('input[name="current_selected_prices"]').val($('input[name="current_selected_prices"]').val() + ',' + selectedPriceId)
        }
        $(this).parents('.price-wrapper').remove();
    });

    $(".collapse").on('show.bs.collapse', function () {
        $(this).prev().find('.card-link,.fundus-dates-desc').addClass('active');
        $(this).prev().find('.btn-toggle,.toggle_button').addClass('show-toggle');
        $(this).prev().find('.daterange-single3').removeAttr("readonly");
        $(this).prev().find('.daterange-single3').attr('placeholder', 'TT.MM.JJJJ');
    });
    $(".collapse").on('hide.bs.collapse', function () {
        $(this).prev().find('.card-link,.fundus-dates-desc').removeClass('active');
        $(this).prev().find('.btn-toggle,.toggle_button').removeClass('show-toggle');
        $(this).prev().find('.daterange-single3').attr("readonly", 'off');
        $(this).prev().find('.daterange-single3.pickup_date').attr('placeholder', 'Abholung');
        $(this).prev().find('.daterange-single3.return_date').attr('placeholder', 'Rückgabe');
    });

    $('.daterange-single.nobackdate').daterangepicker({
        singleDatePicker: true,
        minDate: new Date(),
        autoApply: true,
        showDropdowns: true,
        locale: {
            format: 'DD.MM.YYYY'
        }
    });

    $('.daterange-single2').daterangepicker({
        singleDatePicker: true,
        autoApply: true,
        showDropdowns: true,
        locale: {
            format: 'DD.MM.YYYY'
        }
    });

    activateDateRangePicker3('down');

    function activateFbThemePicker() {
        $('.daterange-single2-fb-theme').daterangepicker({
            singleDatePicker: true,
            autoApply: true,
            showDropdowns: true,
            locale: {
                format: 'DD.MM.YYYY'
            }
        }).on("apply.daterangepicker", function (e, picker) {

            //disable future dates based on pickup date
            let pickup_date = picker.startDate.format('YYYY-MM-DD');
            if ($(this).hasClass('start_date')) {

                let pickerOptions = {
                    singleDatePicker: true,
                    autoApply: true,
                    showDropdowns: true,
                    minDate: new Date(pickup_date),
                    locale: {
                        format: 'DD.MM.YYYY'
                    }
                }
                $(this).next().next('.daterange-single2-fb-theme.end_date').daterangepicker(pickerOptions);
                $(this).parents('.dates-wrapper').find('.daterange-single2-fb-theme.end_date').daterangepicker(pickerOptions);
            }
            //end

        });
    }

    activateFbThemePicker();

    // $('body').on('focus', ".daterange-single2-fb-theme", function () {
    //     activateFbThemePicker();
    // });    

    $('body').on('focus', ".daterange-single2", function () {

        //if( $(this).hasClass('hasDatepicker') === false )  {
        $(this).daterangepicker({
            singleDatePicker: true,
            autoApply: true,
            showDropdowns: true,
            locale: {
                format: 'DD.MM.YYYY'
            }
        });
        //}

    });

    //input search data show
    $('#mindestmenge').on('keyup', function () {
        var mindest = $(this).val();
        if (mindest != "") {
            $('#result #mindestmenge-data .value-wrap .value').text('> ' + mindest);
            $('#result #mindestmenge-data .value-wrap').css('display', 'flex');
        } else {
            $('#result #mindestmenge-data .value-wrap').css('display', 'none');
            $('#result #mindestmenge-data .value-wrap').removeClass('d-flex');
        }
    })
    $('#result #mindestmenge-data .value-wrap button').click(function (event) {
        $('#mindestmenge').val('').focus();
        $(this).parent().hide().removeClass('d-flex');
    });
    $('#result #location-distance .value-wrap button').click(function (event) {
        $('#ort-order-plz').val('').focus();
        $('#geo_location').val('');
        $('#ortorder').val('0').trigger('change');
        $(this).parent().hide().removeClass('d-flex');
        $('.selects-wrapper .distance .placeholder').text($('#ortorder').select2('data')[0].text);
    });

    $('#ortorder').on('change', function (e) {
        locationChangeHandler();
        updateSearchLocation();
    })


    var nameInput = document.querySelectorAll('.edit-funds-name');
    function setInputWidth(e) {
        var offset = $(window).width() < 767 ? 0 : 10;
        var ml_offset = $(window).width() < 767 ? 7 : 10;
        if (!e) {
            nameInput.forEach(function (item, index, arr) {
                item.style.width = ((item.value.length + 1) * ml_offset) + offset + 'px';
            });
        } else {
            e.style.width = ((e.value.length + 1) * ml_offset) + offset + 'px';
        }
    }
    setInputWidth();
    $('.edit-funds-name').keypress(function (event) {
        setInputWidth(this);
    });

    $('.edit-products-wrap').on('click', '.edit-button i', function (event) {
        $(this).parents('.card-header').find('.card-link,.motive-children-header-left').find('.form-control').removeAttr("readonly");
        $(this).parents('.card-header').find('.card-link,.motive-children-header-left').find('.form-control').first().focus();
        $(this).hide();
        $(this).parent().find('.save').show();
        $(this).parent().parent().find('.delete-button').show();
        $(this).parents('.card').addClass('edit-mode');

    });

    jQuery.validator.addMethod("lessThan",
            function (value, element, params) {

                let [day, month, year] = value.split(".");
                let startDate = year + '-' + month + '-' + day;

                [day, month, year] = params.val().split(".");
                let endDate = year + '-' + month + '-' + day;

                if (!/Invalid|NaN/.test(new Date(startDate))) {
                    return new Date(startDate) <= new Date(endDate);
                }

                return isNaN(startDate) && isNaN(endDate)
                        || (Number(startDate) <= Number(endDate));
            }, 'Anfangsdatum muss vor Enddatum liegen.');

    $('.edit-products-wrap').on('click', '.edit-button .save', function (event) {
        let formObject = $(this).closest('form');
        let params = formObject.serialize();

        formObject.validate({
            rules: {
                start_date: {
                    required: true,
                    lessThan: $(this).closest('form').find('input[name="end_date"]')
                },
                end_date: {
                    required: false,
                },
                name: {
                    required: true,
                },
            },
            messages: {
                start_date: {
                    required: 'Anfangsdatum Feld ist erforderlich.'
                },
                end_date: {
                    required: 'Enddatum Feld ist erforderlich.'
                },
                name: {
                    required: 'Namensfeld ist erforderlich.'
                }
            }
        });

//        if (formObject.valid()) {
//            formObject.submit();
//        }

        formObject.find('.error').each(function (key, errorObject) {
            $(this).text('');
            $(this).val('');
        });

        let favoriteId = $(this).data('favourite');
        if (favoriteId !== "") {
            favoriteId = '/' + favoriteId;
        }

        formObject.find('.spinner-border.button_spinner').addClass('start');

        callApi('/favourites/theme' + favoriteId, params, function (status, response) {
            if (status == 'success') {
                if (response.status == 'success') {
                    location.reload();
                }
            } else {
                if (response.status == 422) {
                    $.each(response.responseJSON.errors, function (key, value) {
                        let elementObject;
                        if (key.indexOf('.') > -1) {
                            inputName = key.split('.');
                            keyIndex = inputName[1];
                            key = inputName[0] + '[]';

                            elementObject = formObject.find('[name="' + key + '"]').eq(keyIndex);

                        } else {
                            elementObject = formObject.find('[name="' + key + '"]');
                        }
                        if (elementObject.next().attr('class') == 'error') {
                            elementObject.next().text(value);
                        } else {
                            elementObject.after('<span class="error" style="text-align: left">' + value + '</span>');
                        }

                    });
                } else if (response.status == 419) {
                    location.reload();
                }
            }
            formObject.find('.spinner-border.button_spinner').removeClass('start');

        });
    });

    //add dates
    $(document).on('click', '.edit-products-wrap #add-btn', function (event) {
        event.preventDefault();
        event.stopPropagation();
        var dateHTML = `<div class="dates-wrapper">
                            <div><input type="hidden" name="range_id[]" value="0"><input type="text" class="form-control daterange-single2-fb-theme start_date" placeholder="von" name="start_date[]" value=""></div><span class="mx-1">-</span>
                            <div><input type="text" class="form-control daterange-single2-fb-theme end_date" placeholder="bis" name="end_date[]" value=""></div>
                            <button type="button" class="remove-add-btn" id="remove-btn"><i class="zmdi zmdi-minus"></i></button>
                        </div>`;
        $(this).parents('.dates-container').append(dateHTML);
        activateFbThemePicker();
    });
    //remove dates
    $(document).on('click', '.edit-products-wrap #remove-btn', function (event) {
        event.stopPropagation();
        event.preventDefault();
        $(this).parents('.dates-wrapper').remove();
    });

    var parentElement = $(".filter-selects");
    var select2 = $('.filter-selects .select2-single').select2({
        dropdownParent: parentElement
    }).on("select2:select", function (e) {

        var select2ID = $(this).next().attr('data-select2-id');

        var item = $(this).next().find('.select2-selection__rendered').html();

        $('#result div[select2-id=' + $(this).attr('id') + ']').html(item);

    }).on('select2:unselect', function (e) {
        var id = $(this).attr('id');
        var item = $('.filter-selects #' + id).next().find('.select2-selection__rendered').html();
        $('#result div[select2-id=' + id + ']').html(item);
    });

    select2.trigger('select2:select');

    $('.select2-single-distance').select2({
        dropdownParent: parentElement
    }).on("select2:select", function (e) {
        $(this).parent('.distance').find('.placeholder').text($(this).select2('data')[0].text);
    });

    $('.select2-single-distance').trigger('select2:select');

    $(document).on('click', '#result .select2-selection__choice__remove', function (e) {
        var id = $(this).parent().attr('data-select2-id');
        var select2ID = $(this).parent().parent().attr('select2-id');
        $('.filter-selects select#' + select2ID).next().find('.select2-selection__rendered li[data-select2-id=' + id + '] button').click();
        $(this).parent().remove();
        var item = $('.filter-selects #' + select2ID).next().find('.select2-selection__rendered').html();
        $('#result div[select2-id=' + select2ID + ']').html(item);
    });

    $('.filter-form .input-box .dropdown').click(function (event) {
        $('.filter-form .input-box').toggleClass('open-dropdown');
    });

    $(document).click(function (e) {
        var target = e.target;
        if (!$(target).is('.filter-form .input-box .dropdown') && !$(target).parents().is('.category-dropdown')) {
            $('.filter-form .input-box').removeClass('open-dropdown');
        }
    });

    function productGallery() {

        $('[data-fancybox="images"]').fancybox({
            buttons: [
                "close"
            ],
            idleTime: false,
            infobar: false,
            thumbs: {
                autoStart: true,
                axis: 'x'
            }
        });

    }
    productGallery();
    $("#project-detail").on('shown.bs.modal', function () {
        productGallery();
    });

    $('[data-fancybox="product-image"]').fancybox({
        buttons: [
            "close"
        ],
        idleTime: false,
        infobar: false,
        thumbs: {
            autoStart: true,
            axis: 'x'
        }
    });

    $('.product-detail-section .select2-container--default .select2-search--inline .select2-search__field').click(function (event) {
        event.stopPropagation();
    });


    $('.custom-dropdown .option').click(function (event) {
        event.preventDefault();
        let option = $(this).attr('data-option');
        let optionValue = $(this).attr('data-value');
        let parentSlug = $(this).parents('.custom-dropdown').find('.s-parent-category.active').data('slug');
        let allCategoryFields = [];
        let currentCategoryFields = [];

        $(this).parents('.custom-dropdown').find('input[name="category"]').val(optionValue);
        $(this).parents('.custom-dropdown').find('input[name="category_name"]').val(option);
        $(this).parents('.custom-dropdown').find('.label').text(option);
        $(this).parents('.custom-dropdown').find('input[name="product_category_slug"]').val(parentSlug);
        $('.custom-dropdown').removeClass('show');

        $.each(categoryWiseFields, function (keySlug, categorySlugElement) {
            if (keySlug == parentSlug) {
                currentCategoryFields = categorySlugElement;
            } else {
                allCategoryFields = allCategoryFields.concat(categorySlugElement);
            }
        });

        $.each(allCategoryFields, function (key, categoryFieldName) {
            $('.new-article-field-' + categoryFieldName).hide();
        });

        $.each(currentCategoryFields, function (key, categoryFieldName) {
            $('.new-article-field-' + categoryFieldName).show();
        });

        let keywordsPlaceholder = $('.item-add-edit textarea[name="product_keywords"]').data(parentSlug);
        let productPlaceholder = $('.item-add-edit #artikelbezeichnung').data(parentSlug);

        $('.item-add-edit textarea[name="product_keywords"]').prop('placeholder', keywordsPlaceholder);
        $('.item-add-edit #artikelbezeichnung').prop('placeholder', productPlaceholder);

        $('.item-add-edit .add-edit-price-q').text($('.item-add-edit .add-edit-price-q').data(parentSlug === "grafik" ? parentSlug : 'default'));
        $('.item-add-edit .add-edit-epoche-q').text($('.item-add-edit .add-edit-epoche-q').data(parentSlug === "grafik" ? parentSlug : 'default'));
        $('.item-add-edit .add-edit-custom_price_available-q').text($('.item-add-edit .add-edit-custom_price_available-q').data(parentSlug === "grafik" ? parentSlug : 'default'));

        $('.item-add-edit .add-edit-location-q').text($('.item-add-edit .add-edit-location-q').data(parentSlug === "dienstleistung" ? parentSlug : 'default'));
        $('.item-add-edit .add-edit-location-opt1').text($('.item-add-edit .add-edit-location-opt1').data(parentSlug === "dienstleistung" ? parentSlug : 'default'));
        $('.item-add-edit .add-edit-location-opt2').text($('.item-add-edit .add-edit-location-opt2').data(parentSlug === "dienstleistung" ? parentSlug : 'default'));

        let allPriceDurationSelect = $('.new-article-field-price').find('.article-price select');

        allPriceDurationSelect.each(function () {
            let priceDurationSelect = $('.price-wrapper-clone .price-wrapper select').clone();

            if (parentSlug === 'grafik') {
                priceDurationSelect.find('.duration_option').remove();
            } else {
                priceDurationSelect.find('.duration_graphics_option').remove();
            }

            $(this).find('option').remove();
            $(this).append(priceDurationSelect.find('option'));
            $(this).find('option:first').attr('selected', 'selected');
        });



    });

    $('.custom-dropdown .set-option').click(function (event) {
        event.preventDefault();
        let option = $(this).attr('data-option');
        let optionValue = $(this).attr('data-value');

        $(this).parents('.custom-dropdown').removeClass('field-error');
        $(this).parents('.custom-dropdown').find('input[name="store_order_item"]').val(optionValue);
        $(this).parents('.custom-dropdown').find('.label').text(option);
        $('.custom-dropdown').removeClass('show');

    });

    $('.custom-dropdown .label').click(function (event) {
        $(this).parent('.custom-dropdown').toggleClass('show');
    });
    $('.category-tab1-btn ul li a').click(function (event) {
        event.preventDefault();
        var id = $(this).attr('href');
        $('.category-tab1-btn li a').removeClass('active');
        $(this).addClass('active');
        $('.category-tab2-btn .content').hide().removeClass('show');
        $('.category-tab2-btn ' + id).fadeIn().addClass('active');
        $('.category-tab-content .content').hide().removeClass('show');
    });

    $('.category-tab2-btn ul li a').click(function (event) {
        event.preventDefault();
        var id = $(this).attr('href');
        $('.category-tab2-btn li a').removeClass('active');
        $(this).addClass('active');
        $('.category-tab-content .content').hide().removeClass('show');
        if (id != "#") {
            $('.category-tab-content ' + id).fadeIn().addClass('active');
        }
    });

    $(document).click(function (e) {
        var target = e.target;
        if (!$(target).is('.custom-dropdown .label') && !$(target).parents().is('.custom-dropdown')) {
            $('.custom-dropdown').removeClass('show');
        }
    });

    $('.categories-wrap #nav-icon2').click(function (event) {
        $('.categories-wrap,.categories-wrap .menu-overlay').addClass('open');
        $('body').addClass('menu-open');
    });
    $('.categories-wrap .close-menu #nav-icon2,.categories-wrap .menu-overlay').click(function (event) {
        $('.categories-wrap,.categories-wrap .menu-overlay').removeClass('open');
        $('body').removeClass('menu-open');
    });

    $('.filter-selects-mobile .check_container input[type="checkbox"]').click(function () {
        if ($(this).prop("checked") == true) {
            $(this).parent('.check_container').addClass('active');
        } else if ($(this).prop("checked") == false) {
            $(this).parent('.check_container').removeClass('active');
        }
    });

    $(".collapse").on('show.bs.collapse', function () {
        $(this).parent('.card').addClass('active');
    });
    $(".collapse").on('hide.bs.collapse', function () {
        $(this).parent('.card').removeClass('active');
    });

    function categorySelectHandler() {
        // if ($(window).width() > 992) {
        $('.filter-form .btn').click(function (event) {
            // $(this).toggleClass('open');
            // $('.product-filter .selects-wrapper').slideToggle();
        });

        $(".filter-form.showfilter .search-box #search_text").click(function (e) {
            $('.product-filter .selects-wrapper').slideDown();
            $('.product-filter .selects-wrapper').addClass('open');
        })
        // } 
        // else {
        //     $('.filter-form .btn').click(function (event) {
        //         $('body').addClass('menu-open');
        //         $('.selects-wrapper .filter-selects-mobile,.categories-wrap .menu-overlay').addClass('open');
        //     });
        //     $('.filter-selects-mobile .close-menu,.categories-wrap .menu-overlay').click(function (event) {
        //         $('body').removeClass('menu-open');
        //         $('.selects-wrapper .filter-selects-mobile,.categories-wrap .menu-overlay').removeClass('open');
        //     });
        // }
    }
    categorySelectHandler();

    /*
     * Search bar category/subcategory dropdown menu
     */

    $('.filter-form .category-dropdown ul li a').click(function (event) {
        event.preventDefault();
        let data_id = $(this).attr('href');
        let data_label = $(this).attr('data-label');
        let data_option = $(this).attr('data-option');

        if (data_label && data_label == '1') {

            $('.content-wrap.label1 .tab_content').hide().removeClass('show');
            $('.content-wrap.label1 ' + data_id).fadeIn();
            $('.filter-form .category-dropdown ul li a[data-label="1"]').removeClass('active');
            $(this).addClass('active');
            $('.content-wrap.label2 .tab_content').hide().removeClass('show');

            // $('.content-wrap.label1 '+data_id+' ul li a').removeClass('active');
            // $('.content-wrap.label1 '+data_id+' ul li a').first().addClass('active');
            // let data_id2 = $('.content-wrap.label1 '+data_id+' ul li').first().find('a').attr('href');
            // $('.content-wrap.label2 .tab_content').hide().removeClass('show');
            // $('.content-wrap.label2 '+data_id2).fadeIn();
            $('.filter-form .category-dropdown .btn-second-label').hide();

        } else if (data_label && data_label == '2') {
            $('.content-wrap.label2 .tab_content').hide().removeClass('show');
            $('.content-wrap.label2 ' + data_id).fadeIn();
            $('.filter-form .category-dropdown ul li a[data-label="2"]').removeClass('active');
            $(this).addClass('active');
            if ($(window).width() < 767) {
                $('.filter-form .category-dropdown .btn-second-label').show();
                $('.content-wrap.label1 .tab_content').hide();
            }

        } else if (data_option) {
            $('.filter-form .input-box .dropdown').text(data_option);
            $('.filter-form .input-box #searched_category_name').val(data_option);
            $('.filter-form .input-box #searched_category_id').val($(this).attr('data-value'));
            $('.filter-form .input-box').removeClass('open-dropdown');
            //$(this).addClass('active');
            $('#frm-article-search').submit();
        }

    });

    $('.filter-form .category-dropdown .btn-second-label').click(function (event) {
        var activeID = $('ul.label0 li a.active').attr('href');
        $('.content-wrap.label2 .tab_content').hide();
        $('.content-wrap.label1 .tab_content' + activeID).show();
        $(this).hide();
    });

// -------------- Login Integration ------------------- //

    $('.open-login').click(function (event) {
        $('#registeration-popup').modal('hide');
        $('#fav-popup').modal('hide');

        resetFormErrorMessages('login_form');
        $("#login_form")[0].reset();

        $('#login-modal').modal('show');
        $("#login-modal").on('shown.bs.modal', function () {
            $('body').addClass('modal-open');
        });
    });

    $('#sign_in_button').click(function (e) {
        e.preventDefault();

        progressIndicator('login-modal', 'show');

//        params = {};
//        params.email = $('#l_email').val();
//        params.password = $('#l_password').val();

        params = $("#login_form").serialize();

        $('.errors-email').text('');
        $('.errors-password').text('');
        resetFormErrorMessages('login-modal');

        callApi('/login', params, function (status, response) {
            if (status == 'success') {
                if (response.status == 'success') {
                    window.location = $('#website_url').val() + response.redirectTo;
                } else {
                    $('#login-modal').removeClass('model_disabled_container');
                    $('#login-modal .spinner-border.button_spinner').removeClass('start');
                }
            } else {
                if (response.status === 422) {
                    $.each(response.responseJSON.errors, function (key, value) {
                        $('.errors-' + key).text(value);
                    });
                }
                progressIndicator('login-modal', 'hide');
            }

        });
    });


// -------------- Registration Integration ------------------- //

    $('.open-registration-project').click(function (event) {
        $('.open-registration').trigger('click', ['project']);
    });

    $('.open-registration-fundus').click(function (event) {
        $('.open-registration').trigger('click', ['fundus']);
    });

    $('.open-registration').on('click', function (event, registerOption) {
        $('#login-modal').modal('hide');
        $('#fav-popup').modal('hide');

        resetFormErrorMessages('register_form');

        $("#register_form")[0].reset();
        $('#register-form-progress').hide();
        $('#registeration-popup .body-inner').hide();
        $('#registeration-popup #account-type-selection').show();
        $("#registeration-popup #project-data .firma_checked").show();
        $("#registeration-popup #fundus-data .fundusdaten_firma_checked").show();

        if (registerOption) {
            if (registerOption == 'project') {
                $('#registration_account_type').prop('checked', true);
                //$('#registration_account_type').trigger('click');
            } else if (registerOption == 'fundus') {
                $('#registration_account_type_fundus').prop('checked', true);
                //$('#registration_account_type_fundus').trigger('click');
            }
        }

        $('#registeration-popup').modal({backdrop: 'static',
            keyboard: false,
            show: true});

        $("#registeration-popup").on('shown.bs.modal', function () {
            $('body').addClass('modal-open');
        });
    });

    $('#sign_up_button').click(function (e) {
        e.preventDefault();

        if (!$('#register_form').valid()) {
            return false;
        }

        progressIndicator('registeration-popup', 'show');

        params = $("#register_form").serialize();

        resetFormErrorMessages('register_form');

        callApi('/register', params, function (status, response) {

            if (status == 'success') {
                if (response.status == 'success') {
                    //window.location = $('#website_url').val() + response.redirectTo;
                    var accountType = $('input[name="account_type"]:checked').val();
                    $('.register-success-message-name').text(
                            $('#registeration-popup [name="first_name"]').val());

                    $('.register-success-message-email').text(
                            $('#registeration-popup [name="email"]').val()
                            );

                    $('#registeration-popup').modal('hide');
                    $('#complete-success-message').hide();
                    $('#fundus-success-message').hide();
                    if (accountType == 'complete') {
                        $('#fundus-success-message').show();
                    } else {
                        $('#fundus-success-message').show();
                    }
                    $('#register-success').modal('show');
                    $("#register-success").on('shown.bs.modal', function () {
                        $('body').addClass('modal-open');
                    });
                }
            } else {
                if (response.status == 422) {
                    var isErrorTabDisplayed = false;
                    $.each(response.responseJSON.errors, function (key, value) {
                        $('#register_form [name="' + key + '"]').after('<span class="error errors-' + key + '" style="text-align: left">' + value + '</span>');
                        $.each(registrationFields, function (regKey, regValue) {
                            if (regValue.includes(key) && isErrorTabDisplayed == false) {
                                registerationHandler(regKey, true);
                                isErrorTabDisplayed = true;
                                return false;
                            }
                        });

                    });
                }
            }

            progressIndicator('registeration-popup', 'hide');

        });
    });

    $.extend($.validator.messages, {
        required: "Dieses Feld ist erforderlich.",
        min: "Bitte gib einen Wert größer als oder gleich {0} ein.",
        max: "Bitte gib einen Wert größer oder gleich {0} ein.",
        minlength: "Bitte gib mindestens {0} Zeichen ein.",
        maxlength: "Bitte gib nicht mehr als {0} Zeichen ein.",
        equalTo: "Bitte gib denselben Wert erneut ein.",
        number: "Bitte gib eine gültige Nummer ein.",
        digits: "Bitte gib nur Ziffern ein.",
        url: "Bitte gib eine gültige URL ein.",
        date: "Bitte gib ein korrektes Datum an."
    });

    $("#register_form").validate({
        rules: {
            first_name: {
                required: true,
            },
            email: {
                required: true,
                email: true
            },
            mobile_number: {
                required: true,
            },
            password: {
                minlength: 8
            },
            password_confirmation: {
                minlength: 8,
                equalTo: "#registration_password"
            },
            tnc_approval: {
                required: true
            },
            cancellation_approval: {
                required: '#registration_account_type:checked'
            }
        },
        messages: {
            first_name: {
                required: "Bitte gib Ihren Vornamen ein",
            },
            email: "Bitte gib eine gültige E-Mail Adresse ein",
        }
    });

// -------------- Change Password Integration ------------------- //

    $('.open-change-password').click(function (event) {
        resetFormErrorMessages('change_password_form');

        $("#change_password_form")[0].reset();

        $('#change-password .popup-body').show();
        $('#password-changed').hide();

        $('#change-password').modal('show');
    });

    $('#change-password-button').click(function (e) {
        e.preventDefault();

//        if (!$('#change_password_form').valid()) {
//            return false;
//        }

        progressIndicator('change-password', 'show');

        params = $("#change_password_form").serialize();

        resetFormErrorMessages('change_password_form');

        callApi('/changePassword', params, function (status, response) {
            if (status == 'success') {
                if (response.status == 'success') {
                    popupChangePasswordSuccess('password-changed');
                }
            } else {
                if (response.status == 422) {
                    $.each(response.responseJSON.errors, function (key, value) {
                        $('#change_password_form [name="' + key + '"]').after('<span class="error errors-' + key + '" style="text-align: left">' + value + '</span>');
                    });
                }
            }
            progressIndicator('change-password', 'hide');
        });
    });

    function popupChangePasswordSuccess(id) {
        $('#change-password .popup-body').hide();
        $('#' + id).fadeIn();
    }

    $("#change_password_form").validate({
        rules: {
            current_password: {
                required: true,
                minlength: 8
            },
            new_password: {
                required: true,
                minlength: 8
            },
            confirm_password: {
                minlength: 8,
                equalTo: "#change_new_password"
            }
        }
    });

    $("#pause_fundus_form").validate({
        rules: {
            pause_till: {
                required: true,
            },
            pause_till_date: {
                required: $('input[name="pause_till"]').val() == 'definite',
            }
        }
    });


});

// -------------- Payment Popup Integration ------------------- //
$(function () {
    $('.open-payment-popup').click(function (event) {
        openPaymentPopup();
    });

    $('#payment-popup input[name="subscription_type"]').click(function (event) {
        let subsType = $('#payment-popup input[name="subscription_type"]:checked').val();

        if (subsType == 'recurring') {

            $('#payment-popup .subsBasicTitle').text('monatlicher Betrag');
            $('#payment-popup .subsBasicAmount').text($(this).data('basic_amount') + ' €');

            $('#payment-popup .subsTaxTitle').text('+ ' + $(this).data('tax') + '% MwSt');
            $('#payment-popup .subsTaxAmount').text($(this).data('tax_amount') + ' €');

            $('#payment-popup .subsTotalTitle').text('monatlicher  Gesamtbetrag');
            $('#payment-popup .subsTotalAmount, #payment-bank-transfer-popup .subsTotalAmount').text($(this).data('total_amount') + ' €');

        } else if (subsType == 'onetime') {
            $('#payment-popup select[name="duration"]').change();
        }
    });

    $('#payment-popup select[name="duration"]').change(function (event) {
        $('#payment-popup input[name="subscription_type"]').prop('checked', true);

        $('#payment-popup .subsBasicTitle').text('einmaliger Betrag');
        $('#payment-popup .subsBasicAmount').text($(this).find(':selected').data('basic_amount') + ' €');

        $('#payment-popup .subsTaxTitle').text('+ ' + $(this).find(':selected').data('tax') + '% MwSt');
        $('#payment-popup .subsTaxAmount').text($(this).find(':selected').data('tax_amount') + ' €');

        $('#payment-popup .subsTotalTitle').text('Gesamtbetrag');
        $('#payment-popup .subsTotalAmount,#payment-bank-transfer-popup .subsTotalAmount').text($(this).find(':selected').data('total_amount') + ' €');
    });

    $('.close-open-payment-popup').click(function (event) {
        $('#new-project-option-popup').modal('hide');
        openPaymentPopup();
    });

    $('.open-new-project-option-popup').click(function (event) {
        $('#new-project-option-popup').modal('show');
    });

    $('.open-new-project-popup').click(function (event) {
        event.preventDefault();
        $('#new-project-option-popup').modal('hide');

        resetFormErrorMessages('new_project_form');

        $("#new_project_form")[0].reset();
        //upgradeProjectHandler('new-project-data');
        $('#new-project-popup').modal({backdrop: 'static',
            keyboard: false,
            show: true});
    });

    $('#new-project-popup #new_project_form_button').click(function (e) {
        e.preventDefault();

        if (!$('#new_project_form').valid()) {
            return false;
        }

        progressIndicator('new-project-popup', 'show');

        params = $("#new_project_form").serialize();

        resetFormErrorMessages('new_project_form');

        callApi('/data', params, function (status, response) {

            if (status == 'success') {
                if (response.status == 'success') {
                    //$('#new-project-popup').modal('hide');
                    //openPaymentPopup();
                    location.reload();
                }
            } else {
                if (response.status == 422) {
                    var isErrorTabDisplayed = false;
                    $.each(response.responseJSON.errors, function (key, value) {
                        $('#new_project_form [name="' + key + '"]').after('<span class="error errors-' + key + '" style="text-align: left">' + value + '</span>');
                        $.each(registrationFields, function (regKey, regValue) {
                            if (regValue.includes(key) && isErrorTabDisplayed == false) {
                                upgradeProjectHandler(regKey, true);
                                isErrorTabDisplayed = true;
                                return false;
                            }
                        });

                    });
                }
            }

            progressIndicator('new-project-popup', 'hide');

        });
    });

    $('#new-project-popup #new-project-data .register-checked .check_container input[type="radio"]').click(function () {
        var inputValue = $(this).attr("value");
        var targetBox = $("." + inputValue);
        $("#new-project-popup #new-project-data .rbox").not(targetBox).hide();
        $("#new-project-popup #new-project-data .rbox").not(targetBox).find('input').val('');
        $(targetBox).show();
    });
});

function paymentModalHandler(id, back) {
    if (!back && !$('#payment_form').valid()) {
        return false;
    }

    if (id == 'payment-type') {
        if ($('#payment-popup input[name="subscription_type"]:checked').val() == 'recurring') {
            $('#payment-popup #payment_method_bank').prop('checked', false);
            $('#payment-popup #payment_method_paypal').prop('checked', true);
            $('#payment-popup #payment_method_bank').prop('disabled', true);
            $('#payment-popup #payment_method_bank').closest('.check_container').addClass('disable');
        } else {
            $('#payment-popup #payment_method_bank').prop('disabled', false);
            $('#payment-popup #payment_method_bank').closest('.check_container').removeClass('disable');
        }
    }

    if (id == 'bank-detail' && $('#payment-popup input[name="payment_method"]:checked').val() == 'paypal') {
        $("#payment_form")[0].submit();
        return true;
    }

    if (id == 'bank-detail' && $('#payment-popup input[name="payment_method"]:checked').val() == 'bank_account') {
        subscribeProjectBankTransfer();
        return true;
    }

    if (id == 'bank-detail-final') {
        id = 'bank-detail';
    }

    if (id == 'project-confirm-payment') {
        $('#payment-popup #l_project_payment_method').text($('#payment-popup input[name="payment_method"]:checked').data('name'));
        if ($('#payment-popup input[name="subscription_type"]:checked').val() == 'recurring') {
            $('#payment-popup #l_project_subscription_type').text($('#payment-popup input[name="subscription_type"]:checked').data('name'));
            $('#payment-popup #l_project_subscription_charge').text($('#payment-popup input[name="subscription_type"]:checked').data('subscription-charge'));
        } else {
            $('#payment-popup #l_project_subscription_type').text($('#payment-popup select[name="duration"]').find(':selected').data('name'));
            $('#payment-popup #l_project_subscription_charge').text($('#payment-popup select[name="duration"]').find(':selected').data('subscription-charge'));
        }
    }

    $('#payment-popup .popup-body').hide();
    $('#payment-popup #' + id).fadeIn();
    $('#payment-popup li[data-id=' + id + ']').addClass('active');
    $('#payment-popup li[data-id=' + id + ']').nextAll('li').removeClass('active');
}

function subscribeProjectBankTransfer() {
    let formObject = $("#payment-popup #payment_form");
    let params = formObject.serialize();
    let thisObject = $(this);

    $('#payment-popup').addClass('model_disabled_container');
    $('#payment-popup .spinner-border.button_spinner').addClass('start');

    callApi('/payment/bank', params, function (status, response) {
        if (status == 'success') {
            if (response.status == 'success') {
                $('#payment-bank-transfer-popup .order_number').text(response.data.order_number);
                paymentPopupFlag = false;
                //paymentModalHandler('bank-detail-final');
                $('#payment-popup').modal('hide');
                $('#payment-bank-transfer-popup').modal('show');

            }
        } else {
            if (response.status == 422) {
                $.each(response.responseJSON.errors, function (key, value) {
                    //thisObject.parent().after('<div class="error">' + value + '</div>');
                });
            }
        }

        $('#payment-popup').removeClass('model_disabled_container');
        $('#payment-popup .spinner-border.button_spinner').removeClass('start');
    });

}

function openPaymentPopup() {
    resetFormErrorMessages('payment-popup');

    $("#payment_form")[0].reset();
    //$('#payment-popup,#payment-bank-transfer-popup .subsTotalAmount').text($('#payment-popup select[name="duration"]').find(':selected').data('amount'));

    $('#payment-popup .subsBasicTitle').text('');
    $('#payment-popup .subsBasicAmount').text('');

    $('#payment-popup .subsTaxTitle').text('');
    $('#payment-popup .subsTaxAmount').text('');

    $('#payment-popup .subsTotalTitle').text('');
    $('#payment-popup .subsTotalAmount,#payment-bank-transfer-popup .subsTotalAmount').text('');

    $('#payment-popup input[name="subscription_type"][value=recurring]').attr('checked', true);
    $('#payment-popup input[name="subscription_type"][value=recurring]').click();

    paymentModalHandler('subscription')
    $('#payment-popup').modal('show');
}

let validatorPayment = $("#payment_form").validate({
    rules: {
        subscription_type: {
            required: true,
        },
        duration: {
            required: $('#payment-popup input[name="subscription_type"]:checked').val() === "onetime",
        },
        payment_method: {
            required: $('#payment-popup input[name="subscription_type"]:checked').val() === "onetime",
        }
    }
});

// -------------- Product Detail Popup Integration ------------------- //
$(function () {
    $('#project-detail .add-bookmark-button').click(function (event) {
        event.preventDefault();

        if ($(this).data('target') == '#fav-popup') {
            $('#fav-popup').modal('show');
            return true;
        }

        if (paymentPopupFlag === true) {
            openPaymentPopup();
        } else {
            if (!$("#product_bookmark_form").valid()) {
                return false;
            }
            progressIndicator('project-detail', 'show');
            params = $("#product_bookmark_form").serialize();

            resetFormErrorMessages('product_bookmark_form');

            callApi('/bookmark', params, function (status, response) {
                if (status == 'success') {
                    if (response.status == 'success') {
                        $('#requested_count-success').text(response.message);
                    }
                } else {
                    if (response.status == 422) {
                        $.each(response.responseJSON.errors, function (key, value) {
                            $('#requested_count-error').text(value);
                            $('#requested_count-error').show();
                        });
                    }
                }
                progressIndicator('project-detail', 'hide');
            });
        }
    });

    $("#product_bookmark_form").validate({
        rules: {
            requested_count: {
                required: true,
                min: 1,
                number: true
            },
            favourite: {
                required: true,
            },
        }
    });

    $('.open-product-detail-popup').click(function (event) {
        event.preventDefault();
        let mediaFiles = $(this).data('media');
        let productData = $(this).data('product');
        let productPrices = $(this).data('price');
        let mediaBaseUrl = $('#img_url').val();

        let postalCode = (productData.postal_code != 0 ? productData.postal_code : '');
        let yearString = productData.year != '0' && productData.year != '9999' && productData.year != '' ? '(' + productData.year + ')' : '';

        resetFormErrorMessages('product_bookmark_form');
        $("#product_bookmark_form")[0].reset();

        $("#project-detail-favourite").val("1");
        $("#project-detail-favourite").trigger("change");

        //$("#product_bookmark_form").validate().resetForm();

        $('.product_image_view_row').html('');
        $('p').remove('.article-price-row');


        //***** Hide fields as per category ****//
        let allCategoryFields = [];
        let currentCategoryFields = [];
        let parentSlug = $(this).data('parent-category-slug');

        $.each(categoryWiseFields, function (keySlug, categorySlugElement) {
            if (keySlug == parentSlug) {
                currentCategoryFields = categorySlugElement;
            } else {
                allCategoryFields = allCategoryFields.concat(categorySlugElement);
            }
        });

//        $.each(allCategoryFields, function (key, categoryFieldName) {
//            $('#product-detail-' + categoryFieldName).closest('.article-field').hide();
//        });

        allCategoryFields = allCategoryFields.filter(function (fieldValue) {
            return (currentCategoryFields.indexOf(fieldValue) == -1);
        });

        $.each(currentCategoryFields, function (key, categoryFieldName) {
            $('#product-detail-' + categoryFieldName).closest('.article-field').show();
        });
        //***** Code ends - Hide fields as per category ****//

        $('#product-detail-requested_count').attr('data-available', productData.quantity);

        $('#product-detail-image').attr('src', $(this).data('image'));
        $('#product-detail-fancy-image').attr('href', $(this).data('image'));

        $('#product-detail-slug').val(productData.slug);

        $('#product-detail-name').text(productData.name);
        $('#product-detail-description').text($(this).data('description'));
        $('#product-detail-category').text($(this).data('category'));
        $('#product-detail-code').text(productData.code);
        $('#product-detail-quantity').text(productData.quantity);

        setElementText('style', $(this).data('style'));
        setElementText('graphic_form', $(this).data('graphic_form'));
        setElementText('manufacturer_id', $(this).data('manufacturer_id'));
        setElementText('manufacture_country', $(this).data('manufacture_country'));
        setElementText('file_format', $(this).data('file_format'));
        setElementText('copy_right', $(this).data('copy_right'));
        setElementText('dimensions', $(this).data('dimensions'));

        setElementText('epoche', $(this).data('epoche') + ' ' + yearString);
        setElementText('color-name', $(this).data('color-name'));
        $('#product-detail-color').attr('style', 'background: #' + $(this).data('color'));

        if (productPrices.length > 0 || productData.custom_price_available == 1) {
            $('#product-detail-price').closest('.article-field').show();
        } else {
            $('#product-detail-price').closest('.article-field').hide();
        }

        if (productData.location !== "" || postalCode !== "") {
            $('#product-detail-location_at').text(productData.location + ' ' + postalCode);
            $('#product-detail-location_at').closest('.article-field').show();
        } else {
            $('#product-detail-location_at').text('');
            $('#product-detail-location_at').closest('.article-field').hide();
        }

        $('.product-detail-fundus-name').text($(this).data('fundus-name'));
        $('.product-detail-fundus-email').attr('href', 'mailto:' + $(this).data('fundus-email'));
        $('.product-detail-fundus-email').text($(this).data('fundus-email'));
        $('.product-detail-fundus-phone').text($(this).data('fundus-phone'));
        $('.product-detail-fundus-location').text($(this).data('fundus-location'));
        $('.product-detail-fundus-logo').attr('src', $(this).data('fundus-logo'));
        $('.product-detail-fundus-store').attr('href', $(this).data('fundus-store'));


        $.each(productPrices, function (index, data) {
            $('#price-row-parent').append('<p class="article-price-row">' + data.formatted_price + ' € / ' + data.duration_text + '</p>');
        });
        if (productData.custom_price_available == 1) {
            $('#price-row-parent').append('<p class="article-price-row text-grey">' +
                    ((parentSlug == 'grafik') ? 'Bei Stückpreis, Mengenrabatt möglich' : 'Pauschale möglich') +
                    '</p>');
        }

        $.each(mediaFiles, function (index, mediaFileName) {
            $('.product_image_view_row').append('<div class="col-sm-6"><a href="' + mediaBaseUrl + mediaFileName + '" data-fancybox="images"><img src="' + mediaBaseUrl + mediaFileName + '"></a></div>');
        });

        $.each(allCategoryFields, function (key, categoryFieldName) {
            $('#product-detail-' + categoryFieldName).closest('.article-field').hide();
        });

        $('#project-detail').modal({backdrop: 'static',
            keyboard: false,
            show: true});
    });

    $('#registeration-popup .close.btn,#login-modal .close.btn').on('click', function (e) {
        //$('body').addClass('modal-open2');
    });
    $('#project-detail .close.btn').on('click', function (e) {
        //$('body').removeClass('modal-open2');
    });
});

function setElementText(elementSuffix, elementText) {
    let elementId = '#product-detail-' + elementSuffix;

    if (typeof elementText === "string" && elementText.trim().length !== 0) {
        $(elementId).text(elementText);
        $(elementId).closest('.article-field').show();
    } else if (typeof elementText === "number" && elementText > 0) {
        $(elementId).text(elementText);
        $(elementId).closest('.article-field').show();
    } else {
        $(elementId).text('');
        $(elementId).closest('.article-field').hide();
    }
}

function callApi(url, params, callback, thisObject) {
    params._token = $('meta[name="csrf_token"]').attr('content');
    $.ajax({
        url: $('#website_url').val() + url,
        type: 'post',
        data: params,
        cache: false,
        headers: {
            Accept: "application/json; charset=utf-8",
            //"Content-Type": "text/plain; charset=utf-8"
        },
        success: function (data) {
            callback('success', data, thisObject);
        },
        error: function (data) {
            if (data.status == 419) {
                location.reload();
                return false;
            }
            callback('error', data);
        }
    });
}

function callApiWithFile(url, params, callback, thisObject) {
    params._token = $('meta[name="csrf_token"]').attr('content');
    $.ajax({
        url: $('#website_url').val() + url,
        type: 'post',
        data: params,
        cache: false,
        processData: false,
        contentType: false,
        enctype: 'multipart/form-data',
        headers: {
            Accept: "application/json; charset=utf-8",
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    //"Content-Type": "text/plain; charset=utf-8"
        },
        success: function (data) {
            callback('success', data, thisObject);
        },
        error: function (data) {
            if (data.status == 419) {
                location.reload();
                return false;
            }
            callback('error', data);
        }
    });
}

// -------------- Delete Account Integration ------------------- //
$(function () {
    $('.open-delete-account').click(function (event) {
        event.preventDefault();

        $("#delete_account_form")[0].reset();
        resetFormErrorMessages('delete_account_form');
        $('#delete-account .popup-body').hide();
        $('#delete-account #delete-popup').show();
        $('#delete-account').modal({backdrop: 'static',
            keyboard: false,
            show: true});
    });
});

function popupDelete(id) {
    if (id == 'account-password-verification') {

        $('#delete-account .popup-body').hide();
        $('#' + id).fadeIn();

    } else if (id == 'account-deleted') {

        resetFormErrorMessages('delete_account_form');

        progressIndicator('delete-account', 'show');

        params = {};
        params._method = 'DELETE';
        params.account_password = $('#delete-account input[name="account_password"]').val();

        callApi('/data/0', params, function (status, response) {
            if (status == 'success') {
                if (response.status == 'success') {
                    $('#delete-account .popup-body').hide();
                    $('#delete-account .close.btn').hide();

                    $('#' + id).fadeIn();
                }
            } else {
                if (response.status == 422) {
                    $.each(response.responseJSON.errors, function (key, value) {
                        $('.error-' + key).text(value);
                    });
                }
            }

            progressIndicator('delete-account', 'hide');

        });

    } else if (id == 'account-deleted-conclude') {

        window.location = $('#website_url').val();
    }
}

// -------------- Forgot Password Integration ------------------- //
$(function () {
    $('.open-forgot-password').click(function (event) {
        event.preventDefault();
        $('#login-modal').modal('hide');

        $("#forgot_password_form")[0].reset();
        resetFormErrorMessages('forgot_password_form');
        $('#forgot-password .body-inner').hide();
        $('#forgot-password #forgot-password-email').show();

        $('#forgot-password').modal('show');

    });
});

function forgotPassword(id) {
    if (id == 'forgot-password-confirm') {

        resetFormErrorMessages('forgot_password_form');

        $('#forgot-password').addClass('model_disabled_container');
        $('#forgot-password .spinner-border.button_spinner').addClass('start');

        params = {};
        params.email = $('#forgot-password input[name="email"]').val();

        callApi('/password/email', params, function (status, response) {
            if (status == 'success') {
                //if (response.status == 'success') {
                $('#forgot-password .body-inner').hide();
                $('#' + id).fadeIn();
                //}
            } else {
                if (response.status == 422) {
                    $.each(response.responseJSON.errors, function (key, value) {
                        $('.error-' + key).text(value);
                    });
                }
            }

            $('#forgot-password').removeClass('model_disabled_container');
            $('#forgot-password .spinner-border.button_spinner').removeClass('start');
        });

    }
}

// -------------- Article Add Integration ------------------- //
$(function () {
    $('.close-image-btn').click(function () {
        var selectedProductId;
        selectedProductId = $(this).parent().find('input[name="current_image[]"]').val();
        $('input[name="current_selected_products"]').val($('input[name="current_selected_products"]').val() + ',' + selectedProductId)
        $(this).parent().parent().parent().remove();
    });
});

function readURL(input) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        var image = new Image();
        var showNextUploadPlaceholder = $(input).next().attr('src') == '' ? true : false;
        reader.onload = function (e) {
            $(input).next()
                    .attr('src', e.target.result).css('opacity', 1);
            $(input).parent('label').next('.delete-image-btn').show();
            image.src = e.target.result;
            image.onload = function () {
                $(input).data('height', this.height);
                $(input).data('width', this.width);
                $(input).data('size', ~~((input.files[0].size / 1024) / 1024));
            }
        };

        reader.readAsDataURL(input.files[0]);

        if (showNextUploadPlaceholder) {
            addFileUploadPlaceHolder();
        }
    }
}

$(document).on('click', '.delete-image-btn', function (e) {
    $(this).parents('.col-sm-6').remove();
})

function addFileUploadPlaceHolder() {
    var uploadFileHtml = '<div class="col-sm-6"><div class="img-upload"><label><span><i class="zmdi zmdi-plus"></i></span><input type="file" onchange="readURL(this);" name="product_image[]" accept="image/*" /><img id="preview" src=""/></label><button type="button" class="btn delete-image-btn"><i class="zmdi zmdi-close"></i></button></div></div>';
    $('.product_image_upload_row').append(uploadFileHtml);
}



//$.validator.addMethod('height', function (value, element, param) {
//    if ($(element).data('height')) {
//        return $(element).data('height') == param;
//    }
//    return this.optional(element) || true;
//}, 'Image must have minimum height {0}px');
//
//$.validator.addMethod('width', function (value, element, param) {
//    if ($(element).data('width')) {
//        return $(element).data('width') == param;
//    }
//    return this.optional(element) || true;
//}, 'Image must have minimum width {0}px');
//
//$(function () {
//
//    $("#product_add_form").validate({
//        rules: {
//            product_name: {
//                required: true,
//            },
//            "product_image[]": {
//                required: true,
//                width: 350,
//                height: 350,
//            }
//        }
//    });
//    
//});

$(function () {
    $(document).on('click', '.btn.decrement', function (event) {
        //$('.btn.decrement').click(function (event) {
        var currentCounter = parseInt($(this).next().val());
        if (currentCounter > 0) {
            if ($(this).parent().hasClass('fundus-index-counter')) {
                updateProductCount($(this).parent().data('slug'), 'decrement', $(this).next());
                //} else if ($(this).parent().hasClass('inquiry-product-counter')) {
                //updateOrderProductCount($(this).parent().data('id'), 'decrement', $(this).next());
                //} else if ($(this).parent().hasClass('fav-fundus-product-counter')) {
                //updateFavouriteProductCount($(this).parent().data('id'), 'decrement', $(this).next());
                //} else if ($(this).parent().hasClass('fav-theme-product-counter')) {
                //updateFavouriteProductCount($(this).parent().data('id'), 'decrement', $(this).next());
            } else {
                $(this).next().val(currentCounter - 1).change();
            }
        }
    });

    $(document).on('click', '.btn.increment', function (event) {
        // $('.btn.increment').click(function (event) {
        var currentCounter = parseInt($(this).prev().val());
        if (currentCounter < 1000) {
            if ($(this).parent().hasClass('fundus-index-counter')) {
                updateProductCount($(this).parent().data('slug'), 'increment', $(this).prev());
                //} else if ($(this).parent().hasClass('inquiry-product-counter')) {
                //updateOrderProductCount($(this).parent().data('id'), 'increment', $(this).prev());
                //} else if ($(this).parent().hasClass('fav-fundus-product-counter')) {
                //updateFavouriteProductCount($(this).parent().data('id'), 'increment', $(this).prev());
                //} else if ($(this).parent().hasClass('fav-theme-product-counter')) {
                //updateFavouriteProductCount($(this).parent().data('id'), 'increment', $(this).prev());
            } else {
                //let availableCount = $(this).data('available');
                let availableCount = $(this).attr('data-available');

                if (availableCount !== undefined) {
                    if (availableCount >= currentCounter + 1) {
                        $(this).prev().val(currentCounter + 1).change();
                    }
                } else {
                    $(this).prev().val(currentCounter + 1).change();
                }
            }
        }
    });
});

// -------------- Favourites By Motiv Integration ------------------- //

$(function () {
    let card_id = 0;
    $('.motive-add-btn').click(function (event) {
        event.preventDefault();
        card_id++;
        let currentCardElement = newCardElement.replace(/funds-details-one-new-/g, 'funds-details-one-new-' + card_id);
        currentCardElement = currentCardElement.replace(/favourite-motiv-form-new-/g, 'favourite-motiv-form-new-' + card_id);

        $('.global-accordion .card-add-template').first().after(currentCardElement);

        $('.edit-products-wrap .edit-button.funds-details-one-new-' + card_id + '9 i').click();

        $('.daterange-single2add').daterangepicker({
            singleDatePicker: true,
            autoApply: true,
            showDropdowns: true,
            locale: {
                format: 'DD.MM.YYYY'
            }
        }).on("apply.daterangepicker", function (e, picker) {

            //disable future dates based on pickup date
            let pickup_date = picker.startDate.format('YYYY-MM-DD');
            if ($(this).hasClass('start_date')) {

                $(this).parents('.dates-container').find('.daterange-single2add.end_date').daterangepicker({
                    singleDatePicker: true,
                    autoApply: true,
                    showDropdowns: true,
                    minDate: new Date(pickup_date),
                    locale: {
                        format: 'DD.MM.YYYY'
                    }
                })
            }
            //end

        });
    });
});

// -------------- Star symbol Bookmark Integration ------------------- //

$(function () {
    $('.products-container .item-box .product-img .wishlist').click(function (event) {
        if ($('#login-modal').val() !== undefined) {
            $('.open-login').click();
        } else {
            if (paymentPopupFlag === true) {
                openPaymentPopup();
            } else {
                params = {};
                params.bookmarked = $(this).hasClass('active');
                let bookmarkArticleSlug = $(this).data('slug');

                let thisObject = $(this);
                callApi('/bookmarkProduct/' + bookmarkArticleSlug, params, function (status, response, thisObject) {
                    if (status == 'success') {
                        if (response.status == 'success') {
                            //success message in tooltip
                            thisObject.toggleClass('active');
                        }
                    } else {
                        if (response.status == 422) {
                            $.each(response.responseJSON.errors, function (key, value) {
                                //error message in tooltip
                            });
                        }
                    }
                }, thisObject);
            }
        }
    });
});


// -------------- Product add/edit Integration ------------------- //
$(function () {
    $('.item-add-edit .ort input[name="location_at"]').click(function (event) {
        if ($('.item-add-edit .ort input[name="location_at"]:checked').val() == 'others') {
            $('.item-add-edit #product_ort_add-edit').show();
        } else {
            $('.item-add-edit #product_ort_add-edit').hide();
        }
    });
});


// -------------- Fundus Increase Product Count Integration ------------------- //
function updateProductCount(productSlug, action, thisObject) {
    params = {};
    params.action = action;
    callApi('/updateProductCount/' + productSlug, params, function (status, response, thisObject) {
        if (status == 'success') {
            if (response.status == 'success') {
                //success message in tooltip
                thisObject.val(response.currentValue);
            }
        } else {
            if (response.status == 422) {
                $.each(response.responseJSON.errors, function (key, value) {
                    //error message in tooltip
                });
            }
        }
    }, thisObject);
}

$(function () {
    $('.funds-wrapper .store-checkbox').click(function (event) {
        const storeId = $(this).val();
        const currentStatus = $(this).is(':checked');
        $('.funds-wrapper .favourite-checkbox-' + storeId).each(function (i, item) {
            $(item).prop("checked", currentStatus);
        });
    });

    $('.funds-wrapper .project-request-checkbox').click(function (event) {
        $(this).removeClass('some-checked all-checked');
        const orderId = $(this).val();
        const currentStatus = $(this).is(':checked');
        $('.funds-wrapper .project-request-item-checkbox-' + orderId).each(function (i, item) {
            $(item).prop("checked", currentStatus);
        });
    });

    $('.fundus-enquiry-item .check_container input').click(function (event) {

        let checkboxID = Number($(this).attr('data-checkbox').trim());
        let checkboxLenght = $('.project-request-item-checkbox-' + checkboxID).length;
        let checkedLenght = $('.project-request-item-checkbox-' + checkboxID + ':checked').length;

        if (checkedLenght >= 1) {
            $('.project-request-checkbox.pr-checkbox-' + checkboxID).prop('checked', true);
        } else {
            $('.project-request-checkbox.pr-checkbox-' + checkboxID).prop('checked', false);
        }

        let checkedClass = checkedLenght == checkboxLenght ? 'all-checked' : 'some-checked';
        $('.project-request-checkbox.pr-checkbox-' + checkboxID).removeClass('some-checked all-checked').addClass(checkedClass);

    });
});

// -------------- Fundus Increase Product Count Integration ------------------- //
function updateOrderProductCount(id, action, thisObject) {
    params = {};
    params.action = action;
    callApi('/fundus/inquiries/updateCount/' + id, params, function (status, response, thisObject) {
        if (status == 'success') {
            if (response.status == 'success') {
                //success message in tooltip
                thisObject.val(response.currentValue);
            }
        } else {
            if (response.status == 422) {
                $.each(response.responseJSON.errors, function (key, value) {
                    //error message in tooltip
                });
            }
        }
    }, thisObject);
}

function updateFavouriteProductCount(id, action, thisObject) {
    params = {};
    params.action = action;
    callApi('/favourites/fundus/updateCount/' + id, params, function (status, response, thisObject) {
        if (status == 'success') {
            if (response.status == 'success') {
                //success message in tooltip
                thisObject.val(response.currentValue);
            }
        } else {
            if (response.status == 422) {
                $.each(response.responseJSON.errors, function (key, value) {
                    //error message in tooltip
                });
            }
        }
    }, thisObject);
}

$(function () {



    $('#favourite_funds select[name="selected_favourite_id[]"], input[name="requested_item_count"]').on('change', function () {
        let productObject = $(this).closest('.item-box');
        let itemKey = productObject.find('input[name="current_state_checksum"]').val();
        let currentKey = productObject.find('select[name="selected_favourite_id[]"]').val() + '-' + productObject.find('input[name="requested_item_count"]').val();

        if (itemKey !== currentKey) {
            productObject.find('.motiv-apply-button').show();
        } else {
            productObject.find('.motiv-apply-button').hide();
        }
    });

    $('#favourite_funds .motiv-apply-button').click(function (event) {
        let productObject = $(this).closest('.item-box');
        let params = {}; //formObject.serialize();
        params.favourite_item_id = productObject.find('input[name="favourite_item_id"]').val();
        params.selected_favourite_id = productObject.find('.select2-theme').val();
        params.requested_item_count = productObject.find('input[name="requested_item_count"]').val();
        params.current_edit_mode = 'fundus';

        let thisObject = $(this);
        productObject.find('.spinner-border.button_spinner').addClass('start');

        callApi('/favourites/fundus/changeFavourite', params, function (status, response) {
            if (status == 'success') {
                if (response.status == 'success') {
                    location.reload()
                }
            } else {
                if (response.status == 422) {
                    $.each(response.responseJSON.errors, function (key, value) {
                        if (thisObject.parent().next().attr('class') == 'error') {
                            thisObject.parent().next().text(value);
                        } else {
                            thisObject.parent().after('<div class="error">' + value + '</div>');
                        }
                    });
                }
            }
            productObject.find('.spinner-border.button_spinner').removeClass('start');
        });

    })

    $('.prop-store .funds-detail-ac.fundus-enquiry .fundus-enquiry-item .inquiry-apply-button').click(function (event) {
        let productObject = $(this).closest('.fundus-enquiry-item');
        let params = {}; //formObject.serialize();
        params.product_item_id = productObject.find('input[name="product_item_id[]"]').map(function () {
            return $(this).val();
        }).get();
        params.unit_price = productObject.find('input[name="unit_price[]"]').map(function () {
            return $(this).val();
        }).get();
        params.requested_item_count = productObject.find('input[name="requested_item_count[]"]').map(function () {
            return $(this).val();
        }).get();

        resetFormErrorMessages('fundus_inquiry_form');

        let thisObject = productObject;

        callApi('/fundus/inquiries/update', params, function (status, response) {
            if (status == 'success') {
                if (response.status == 'success') {
                    location.reload();
                }
            } else {
                if (response.status == 422) {
                    $.each(response.responseJSON.errors, function (key, value) {
                        let elementObject;
                        if (key.indexOf('.') > -1) {
                            inputName = key.split('.');
                            keyIndex = inputName[1];
                            key = inputName[0] + '[]';

                            elementObject = thisObject.find('[name="' + key + '"]').eq(keyIndex);
                        } else {
                            elementObject = thisObject.find('[name="' + key + '"]');
                        }

                        if (key == 'requested_item_count[]') {
                            elementObject = elementObject.closest('.counter.inquiry-product-counter');
                        }

                        if (elementObject.next().attr('class') == 'error') {
                            elementObject.next().text(value);
                        } else {
                            elementObject.after('<span class="error" style="text-align: left">' + value + '</span>');
                        }
                        //thisObject.find(key).after('<div class="error">' + value + '</div>');
                    });
                }
            }
        });

    });

    $(document).on('click', '.fundus_inquiry_add_product_form .addon-product-create', function (event) {
        event.preventDefault();

        let formObject = $(this).closest('.fundus_inquiry_add_product_form');
        let params = new FormData(document.getElementById("fundus_inquiry_add_product_form")); //formObject.serialize();

        resetFormErrorMessages('fundus_inquiry_add_product_form');

        let thisObject = $(this);
        if (formObject.find('input[name="add_product"]:checked').val()) {
            let orderItemId = formObject.find('input[name="product_order_item_id"]').val();
            let productName = formObject.find('input[name="product_name"]').val();
            let productDesc = formObject.find('textarea[name="product_description"]').val();
            let unitPrice = formObject.find('input[name="product_unit_price"]').val();
            let requestedCount = formObject.find('input[name="requested_count"]').val();
            let replacementValue = formObject.find('input[name="replacement_value"]').val();
            let priceValue = formObject.find('input[name="price_per_day"]').val();

            child_window_handle = window.open($('#website_url').val() + '/fundus/create?' +
                    'orditemid=' + orderItemId +
                    '&unit_price=' + unitPrice +
                    '&requested_count=' + requestedCount +
                    '&product_name=' + productName +
                    '&replacement_value=' + replacementValue +
                    //'&price[]=' + priceValue +
                    '&product_description=' + productDesc,
                    '_blank');
            return false;
        }


        $(this).find('.spinner-border.button_spinner').addClass('start');

        callApiWithFile('/fundus/inquiries/product/create', params, function (status, response) {
            if (status == 'success') {
                if (response.status == 'success') {
                    location.reload()
                }
            } else {
                if (response.status == 422) {
                    $.each(response.responseJSON.errors, function (key, value) {
                        let elementObject;
                        if (key.indexOf('.') > -1) {
                            inputName = key.split('.');
                            keyIndex = inputName[1];
                            key = inputName[0] + '[]';

                            elementObject = formObject.find('[name="' + key + '"]').eq(keyIndex);
                        } else {
                            elementObject = formObject.find('[name="' + key + '"]');
                        }

                        if (key == 'requested_count') {
                            elementObject = elementObject.closest('.counter.inquiry-product-counter');
                        } else if (key == 'product_image') {
                            elementObject = elementObject.closest('.product-img');
                        }

                        if (elementObject.next().attr('class') == 'error') {
                            elementObject.next().text(value);
                        } else {
                            elementObject.after('<span class="error">' + value + '</span>');
                        }
                    });
                }
            }
            thisObject.find('.spinner-border.button_spinner').removeClass('start');
        });

    });

    $('input[name="unit_price[]"], input[name="requested_item_count[]"]').on('change keypress', function () {
        let productObject = $(this).closest('.fundus-enquiry-item');
        productObject.find('.inquiry-apply-button').show();
        productObject.find('.enquire-apply-btn-wrap').show();
    });

    $('#favourite-theme select[name="selected_favourite_id[]"], input[name="requested_item_count"]').on('change', function () {
        let formObject = $(this).closest('.favourite-theme-product-container');
        let itemKey = formObject.find('input[name="current_state_checksum"]').val();
        let currentKey = formObject.find('select[name="selected_favourite_id[]"]').val() + '-' + formObject.find('input[name="requested_item_count"]').val();

        if (itemKey !== currentKey) {
            formObject.find('.motiv-apply-button').show();
        } else {
            formObject.find('.motiv-apply-button').hide();
        }
    });

    $('#favourite-theme .motiv-apply-button').click(function (event) {
        let formObject = $(this).closest('.favourite-theme-product-container');
        let params = formObject.serialize();
        let thisObject = $(this);

        formObject.find('.spinner-border.button_spinner').addClass('start');
        callApi('/favourites/fundus/changeFavourite', params, function (status, response) {
            if (status == 'success') {
                if (response.status == 'success') {
                    location.reload()
                }
            } else {
                if (response.status == 422) {
                    $.each(response.responseJSON.errors, function (key, value) {

                        if (thisObject.parent().next().attr('class') == 'error') {
                            thisObject.parent().next().text(value);
                        } else {
                            thisObject.parent().after('<div class="error">' + value + '</div>');
                        }

                    });
                }
            }
            formObject.find('.spinner-border.button_spinner').removeClass('start');
        });

    })

    $("#project-detail-favourite").change(function (event) {
        let selectedOptions = $(this).val();
        if (selectedOptions.length > 1) {
            let index = selectedOptions.indexOf('1');
            if (index > -1) {
                let filteredOptions = selectedOptions.filter(item => item !== '1');
                $(this).val(filteredOptions);
                $("#project-detail-favourite").trigger("change");
            }
        }
    });

    $('#registeration-popup #project-data .register-checked .check_container input[type="radio"]').click(function () {
        var inputValue = $(this).attr("value");
        var targetBox = $("." + inputValue);
        $("#registeration-popup #project-data .rbox").not(targetBox).hide();
        $("#registeration-popup #project-data .rbox").not(targetBox).find('input').val('');
        $(targetBox).show();
    });

    $('#registeration-popup #fundus-data .register-checked .check_container input[type="radio"]').click(function () {
        var inputValue = $(this).attr("value");
        var targetBox = $("." + inputValue);
        $("#registeration-popup #fundus-data .rbox").not(targetBox).hide();
        $("#registeration-popup #fundus-data .rbox").not(targetBox).find('input').val('');
        $(targetBox).show();
    });
});

$(function () {

//    $("#favourite-theme .collapse").on('hidden.bs.collapse', function () {
//        $(this).find('.update-btn button').hide();
//    });
//
//    $("#favourite-theme .collapse").on('shown.bs.collapse', function () {
//        $(this).find('.update-btn button').show();
//    });

//    $("#favourite_funds .collapse").on('hidden.bs.collapse', function () {
//        $(this).find('.update-btn button').hide();
//    });
//
//    $("#favourite_funds .collapse").on('shown.bs.collapse', function () {
//        $(this).find('.update-btn button').show();
//    });

    $('#proinfinit-popup input[name="fundus_subscription_type"]').click(function (event) {

        let amountPrefix = $(this).data('prefix');
        $('#proinfinit-popup .subsBasicTitle').text(amountPrefix + ' Betrag');
        $('#proinfinit-popup .subsBasicAmount').text($(this).data('basic_amount') + ' €');

        $('#proinfinit-popup .subsTaxTitle').text('+ ' + $(this).data('tax') + '% MwSt');
        $('#proinfinit-popup .subsTaxAmount').text($(this).data('tax_amount') + ' €');

        $('#proinfinit-popup .subsTotalTitle').text(amountPrefix + ' Gesamtbetrag');
        $('#proinfinit-popup .subsTotalAmount').text($(this).data('total_amount') + ' €');

    });

    $('.open-upgrade-fundus-popup').click(function (event) {

        resetFormErrorMessages('upgrade_fundus_form');

        $("#upgrade_fundus_form")[0].reset();
        $('#proinfinit-popup .progress-bar-pro-complete').text('Abschließen');
        $('#proinfinit-popup li[data-id=fundus-choose-package]').addClass('active');
        $('#proinfinit-popup li[data-id=fundus-choose-package]').nextAll('li').removeClass('active');
        $('#proinfinit-popup li[data-id=article-count-selection]').hide();

        $('#proinfinit-popup input[name="fundus_subscription_type"][value=monthly]').attr('checked', true);
        $('#proinfinit-popup input[name="fundus_subscription_type"][value=monthly]').click();

        $('#proinfinit-popup .body-inner').hide();
        $('#proinfinit-popup #fundus-choose-package').show();


        $('#proinfinit-popup').modal({backdrop: 'static',
            keyboard: false,
            show: true});

    });

    $('#proinfinit-popup input[name="fundus_package"]').click(function (event) {

        if ($(this).val() == 'infinite') {
            $('#proinfinit-popup li[data-id=article-count-selection]').show();
            $('#proinfinit-popup .progress-bar-pro-complete').text('Anfragen');
        } else {
            $('#proinfinit-popup li[data-id=article-count-selection]').hide();
            $('#proinfinit-popup .progress-bar-pro-complete').text('Abschließen');
        }
    });

    $('#proinfinit-popup input[name="infinite_subscription_type"]').click(function (event) {
        if ($(this).val() == 'yearly') {
            //$('#proinfinit-popup li[data-id=fundus-payment-method]').show();
            $('#proinfinit-popup input[name="infinite_payment_method"]').attr('checked', false);
        } else {
            //$('#proinfinit-popup li[data-id=fundus-payment-method]').hide();
            $('#proinfinit-popup input[name="infinite_payment_method"]:nth(0)').attr('checked', true);
        }
    });

    $('.pro-plan-change-confirmation-button').click(function (event) {
        $('#pro-plan-change-confirmation-popup').modal('hide');
        ugradeFundusHandler('fundus-payment-interval', true);
    });


});

function ugradeFundusHandler(id, back) {
    if (!back && !$('#upgrade_fundus_form').valid()) {
        return;
    }

    let fundusPackage = $('#proinfinit-popup input[name="fundus_package"]:checked').val();
    let fundusSubscriptionType = $('#proinfinit-popup input[name="fundus_subscription_type"]:checked').val();
    let fundusPaymentMethod = $('#proinfinit-popup input[name="fundus_payment_method"]:checked').val();
    let fundusProgressBarId = id.replace('infinite-', '');
    let currentPackage = $('#proinfinit-popup input[name="fundus_current_package"]').val();

    if (id == 'fundus-payment-interval') {
        if ((currentPackage == 'pro' || currentPackage == 'infinite') && fundusPackage == 'basic') {
            $('#basic-plan-change-confirmation-popup input[name="new_fundus_package"]').val(fundusPackage);
            $('#proinfinit-popup').modal('hide');
            $('#basic-plan-change-confirmation-popup').modal('show');
            return true;
        } else if ((currentPackage == 'infinite') && fundusPackage == 'pro' && !back) {
            //$('#proinfinit-popup').modal('hide');
            $('#pro-plan-change-confirmation-popup').modal('show');
            return true;
        }

        if (fundusPackage == 'infinite') {
            id = 'infinite-article-count-selection';
            fundusProgressBarId = 'article-count-selection';
            $('#proinfinit-popup .progress-bar-pro-complete').text('Anfragen');
        } else {
            $('#proinfinit-popup .progress-bar-pro-complete').text('Abschließen');
        }
    }

    if (id == 'fundus-payment-method') {
        if (fundusSubscriptionType == 'monthly') {
            $('#proinfinit-popup input[name="fundus_payment_method"]').prop('checked', false);
            $('#proinfinit-popup #fundus_payment_method_paypal').prop('checked', true);
            $('#proinfinit-popup #fundus_payment_method_bank').prop('disabled', 'disabled');
            $('#proinfinit-popup #fundus_payment_method_bank').closest('.check_container').addClass('disable');
        } else {
            $('#proinfinit-popup #fundus_payment_method_bank').prop('disabled', false);
            $('#proinfinit-popup #fundus_payment_method_bank').closest('.check_container').removeClass('disable');
        }

    }

    if (id == 'infinite-fundus-payment-method') {
        let infiniteSubsType = $('#proinfinit-popup input[name="infinite_subscription_type"]:checked').val();
        if (infiniteSubsType == 'monthly') {
            $('#proinfinit-popup input[name="infinite_payment_method"]').prop('checked', false);
            $('#proinfinit-popup #infinite_payment_method_paypal').prop('checked', true);
            $('#proinfinit-popup #infinite_payment_method_bank').prop('disabled', 'disabled');
            $('#proinfinit-popup #infinite_payment_method_bank').closest('.check_container').addClass('disable');
        } else {
            $('#proinfinit-popup #infinite_payment_method_bank').prop('disabled', false);
            $('#proinfinit-popup #infinite_payment_method_bank').closest('.check_container').removeClass('disable');
        }
    }

    if (id == 'fundus-confirm-payment') {
        fundusProgressBarId = 'fundus-upgrade-complete';
        $('#proinfinit-popup #l_fundus_subscription_type').text($('#proinfinit-popup input[name="fundus_subscription_type"]:checked').data('name'));
        $('#proinfinit-popup #l_fundus_payment_method').text($('#proinfinit-popup input[name="fundus_payment_method"]:checked').data('name'));
        $('#proinfinit-popup #l_fundus_subscription_charge').text($('#proinfinit-popup input[name="fundus_subscription_type"]:checked').data('subscription-charge'));

        if ((currentPackage == 'infinite') && fundusPackage == 'pro') {
            if (fundusPaymentMethod == 'paypal') {
                $('#proinfinit-popup #fundus-downgrade-pro-paypal-payment-message').show();
                $('#proinfinit-popup #fundus-downgrade-pro-banktransfer-payment-message').hide();
            } else if (fundusPaymentMethod == 'bank_account') {
                $('#proinfinit-popup #fundus-downgrade-pro-paypal-payment-message').hide();
                $('#proinfinit-popup #fundus-downgrade-pro-banktransfer-payment-message').show();
            }
        }

    }

    if (id == 'fundus-upgrade-complete') {
        if (fundusPaymentMethod == 'paypal') {
            $("#upgrade_fundus_form")[0].submit();
            return true;
        } else if (fundusPaymentMethod == 'bank_account') {
            changeFundusProPlan();
            return true;
        }
    }

    if (id == 'infinite-fundus-upgrade-complete') {
        $('#proinfinit-popup .infinite_items_count').text($('#proinfinit-popup input[name="infinite_required_article_count"]').val());
        $('#proinfinit-popup .infinite_payment_interval').text($('#proinfinit-popup input[name="infinite_subscription_type"]:checked').data('name'));
        $('#proinfinit-popup .infinite_payment_method').text($('#proinfinit-popup input[name="infinite_payment_method"]:checked').data('name'));
    }

    if (id == 'fundus-upgrade-complete-final') {
        id = fundusProgressBarId = 'fundus-upgrade-complete';
        $('#proinfinit-popup #uprgade-fundus-form-progress').hide();
        $('#proinfinit-popup .close.btn').hide();
    } else {
        $('#proinfinit-popup #uprgade-fundus-form-progress').show();
    }

    $('#proinfinit-popup .body-inner').hide();
    $('#proinfinit-popup #' + id).fadeIn();
    //$('#proinfinit-popup #uprgade-fundus-form-progress').show();
    $('#proinfinit-popup li[data-id=' + fundusProgressBarId + ']').addClass('active');
    $('#proinfinit-popup li[data-id=' + fundusProgressBarId + ']').nextAll('li').removeClass('active');
}

function changeFundusProPlan() {
    let formObject = $("#proinfinit-popup #upgrade_fundus_form");
    let params = formObject.serialize();
    let thisObject = $(this);

    $('#proinfinit-popup').addClass('model_disabled_container');
    $('#proinfinit-popup .spinner-border.button_spinner').addClass('start');

    callApi('/fundus/payment/bank', params, function (status, response) {
        if (status == 'success') {
            if (response.status == 'success') {
                if (response.data.banktransfer) {
                    location.reload();
                } else {
                    $('#proinfinit-popup input[name="fundus_current_package"]').val(response.data.package_name);
                    $('#proinfinit-popup .order_number').text(response.data.order_number);
                    ugradeFundusHandler('fundus-upgrade-complete-final');
                }
            }
        } else {
            if (response.status == 422) {
                $.each(response.responseJSON.errors, function (key, value) {
                    //thisObject.parent().after('<div class="error">' + value + '</div>');
                });
            }
        }

        $('#proinfinit-popup').removeClass('model_disabled_container');
        $('#proinfinit-popup .spinner-border.button_spinner').removeClass('start');
    });

}

function changeFundusPlan() {
    let formObject = $("#proinfinit-popup #upgrade_fundus_form");
    let params = formObject.serialize();
    let thisObject = $(this);

    $('#proinfinit-popup').addClass('model_disabled_container');
    $('#proinfinit-popup .spinner-border.button_spinner').addClass('start');

    callApi('/fundus/plans/infinite', params, function (status, response) {
        if (status == 'success') {
            if (response.status == 'success') {
                $('#proinfinit-popup').modal('hide');
                $('#infinite-plan-change-confirmation-popup').modal('show');
            }
        } else {
            if (response.status == 422) {
                $.each(response.responseJSON.errors, function (key, value) {
                    //thisObject.parent().after('<div class="error">' + value + '</div>');
                });
            }
        }

        $('#proinfinit-popup').removeClass('model_disabled_container');
        $('#proinfinit-popup .spinner-border.button_spinner').removeClass('start');
    });

}

function upgradeStoreHandler(id, back) {
    if (!back && !$('#upgrade_store_form').valid()) {
        return;
    }

    if (id == 'fundus-data') {
        id = 'store-data';
    }
    $('#upgrade-store-popup .body-inner').hide();
    $('#' + id).fadeIn();
    $('#upgrade-store-form-progress').show();
    $('#upgrade-store-popup li[data-id=' + id + ']').addClass('active');
    $('#upgrade-store-popup li[data-id=' + id + ']').nextAll('li').removeClass('active');

    $.each(registrationFields, function (stepIndex, stepData) {
        $.each(stepData, function (key, value) {
            $('.cls_' + value).html($('#upgrade-store-popup [name="' + value + '"]').val());
            if (['phone_number', 'fundus_phone', 'fundus_website', 'company_name', 'ust_id', 'fundus_company_name', 'fundus_ust_id'].indexOf(value) > -1) {

                if ($('#upgrade-store-popup [name="' + value + '"]').val() != '') {
                    $('.cls_' + value).closest('.registration-disp-field').show();
                } else {
                    $('.cls_' + value).closest('.registration-disp-field').hide();
                }
            }
        });
    });
}

function upgradeProjectHandler(id, back) {
    if (!back && !$('#upgrade_project_form').valid()) {
        return;
    }

    $('#upgrade-project-popup .body-inner').hide();
    $('#' + id).fadeIn();
    $('#upgrade-project-form-progress').show();
    $('#upgrade-project-popup li[data-id=' + id + ']').addClass('active');
    $('#upgrade-project-popup li[data-id=' + id + ']').nextAll('li').removeClass('active');

    $.each(registrationFields, function (stepIndex, stepData) {
        $.each(stepData, function (key, value) {
            $('.cls_' + value).html($('#upgrade-project-popup [name="' + value + '"]').val());
            if (['phone_number', 'fundus_phone', 'fundus_website', 'company_name', 'ust_id', 'fundus_company_name', 'fundus_ust_id'].indexOf(value) > -1) {

                if ($('#upgrade-project-popup [name="' + value + '"]').val() != '') {
                    $('.cls_' + value).closest('.registration-disp-field').show();
                } else {
                    $('.cls_' + value).closest('.registration-disp-field').hide();
                }
            }
        });
    });
}

$(function () {

    $('.open-project-upgrade-popup').click(function (event) {
        event.preventDefault();
        resetFormErrorMessages('upgrade_project_form');

        $("#upgrade_project_form")[0].reset();
        upgradeProjectHandler('project-data');

        $('#upgrade-project-popup').modal({backdrop: 'static',
            keyboard: false,
            show: true});
    });

    $('#upgrade-project-popup #upgrade_project_form_button').click(function (e) {
        e.preventDefault();

        if (!$('#upgrade_project_form').valid()) {
            return false;
        }

        progressIndicator('upgrade-project-popup', 'show');

        params = $("#upgrade_project_form").serialize();

        resetFormErrorMessages('upgrade_project_form');

        callApi('/data', params, function (status, response) {

            if (status == 'success') {
                if (response.status == 'success') {
                    //$('#upgrade-project-popup').modal('hide');
                    //openPaymentPopup();
                    location.reload();
                }
            } else {
                if (response.status == 422) {
                    var isErrorTabDisplayed = false;
                    $.each(response.responseJSON.errors, function (key, value) {
                        $('#upgrade_project_form [name="' + key + '"]').after('<span class="error errors-' + key + '" style="text-align: left">' + value + '</span>');
                        $.each(registrationFields, function (regKey, regValue) {
                            if (regValue.includes(key) && isErrorTabDisplayed == false) {
                                upgradeProjectHandler(regKey, true);
                                isErrorTabDisplayed = true;
                                return false;
                            }
                        });

                    });
                }
            }

            progressIndicator('upgrade-project-popup', 'hide');

        });
    });

    $('#upgrade-project-popup #project-data .register-checked .check_container input[type="radio"]').click(function () {
        var inputValue = $(this).attr("value");
        var targetBox = $("." + inputValue);
        $("#upgrade-project-popup #project-data .rbox").not(targetBox).hide();
        $("#upgrade-project-popup #project-data .rbox").not(targetBox).find('input').val('');
        $(targetBox).show();
    });

    $('.open-store-upgrade-popup').click(function (event) {

        resetFormErrorMessages('upgrade_store_form');

        $("#upgrade_store_form")[0].reset();
        upgradeStoreHandler('store-data');

        $('#upgrade-store-popup').modal({backdrop: 'static',
            keyboard: false,
            show: true});
    });

    $('#upgrade-store-popup #upgrade_store_form_button').click(function (e) {
        e.preventDefault();

        if (!$('#upgrade_store_form').valid()) {
            return false;
        }

        progressIndicator('upgrade-store-popup', 'show');

        params = $("#upgrade_store_form").serialize();

        resetFormErrorMessages('upgrade_store_form');

        callApi('/data', params, function (status, response) {

            if (status == 'success') {
                if (response.status == 'success') {
                    //$('#upgrade-project-popup').modal('hide');
                    //openPaymentPopup();
                    location.reload();
                }
            } else {
                if (response.status == 422) {
                    var isErrorTabDisplayed = false;
                    $.each(response.responseJSON.errors, function (key, value) {
                        $('#upgrade_store_form [name="' + key + '"]').after('<span class="error errors-' + key + '" style="text-align: left">' + value + '</span>');
                        $.each(registrationFields, function (regKey, regValue) {
                            if (regValue.includes(key) && isErrorTabDisplayed == false) {
                                upgradeStoreHandler(regKey, true);
                                isErrorTabDisplayed = true;
                                return false;
                            }
                        });

                    });
                }
            }

            progressIndicator('upgrade-store-popup', 'hide');

        });
    });

    $('#upgrade-store-popup #store-data .register-checked .check_container input[type="radio"]').click(function () {
        var inputValue = $(this).attr("value");
        var targetBox = $("." + inputValue);
        $("#upgrade-store-popup #store-data .rbox").not(targetBox).hide();
        $("#upgrade-store-popup #store-data .rbox").not(targetBox).find('input').val('');
        $(targetBox).show();
    });

    $('.fundus-enquiry .add-fundus-enquiry').click(function (event) {
        let orderItemId = $(this).closest('.fundus-enquiry-item').find('input[name="order_item_id[]"]').data('id');
        $(this).parents('.card-body').find('.add-fundus-wrap').append(add_fundus_form.replace('PRODUCT_ORDER_ITEM_ID', orderItemId));

    });

    $(document).on('click', '.fundus-enquiry .save-fundus-wrap .cancel-submit', function (event) {
        $(this).parents('.wrapper-form').remove();
    });

    $('#favourite-theme .motiv-delete-item').click(function (e) {
        e.preventDefault();
        $('#motiv-delete-popup #motiv_delete_form').attr('action', $(this).data('action'));
        $('#motiv-delete-popup').modal('show');
    });

    $('.logout-button').click(function (event) {
        event.preventDefault();
        event.stopPropagation();
        $('#logout-form').submit();
        return false;
    });

    function countDescChars() {
        var str = $('.funds-details .funds-info .desciption .short-description p').text();
        const result = str.length;
        if (result < 380) {
            $('.funds-details .funds-info .desciption button').hide();
        }
    }
    countDescChars();

    $('.prop-store .funds-list .funds-wrapper .funds-name .name').click(function (event) {
        $(this).parent('.funds-name').toggleClass('open');
        $(this).parent('.funds-name').next('.funds-detail-ac').slideToggle();
    });

    $("#product_store_request_form").validate({
        rules: {
            requested_count: {
                required: true,
                min: 1,
                number: true
            },
            store_order_item: {
                required: true,
            }
        }
    });

    $('.product-detail-section .add-product-store-request').click(function (event) {
        event.preventDefault();

        let formObject = $("#product_store_request_form");
        if (!formObject.valid()) {
            return false;
        }

        let storeOrderItem = formObject.find('input[name="store_order_item"]').val();
        if (storeOrderItem === "") {
            formObject.find('.custom-dropdown').addClass('field-error');
            return false;
        }
        //progressIndicator('project-detail', 'show');
        params = $("#product_store_request_form").serialize();

        resetFormErrorMessages('product_store_request_form');
        $(this).find('.spinner-border.button_spinner').addClass('start');
        let buttonObject = $(this);

        callApi('/fundus/inquiries/addProduct', params, function (status, response) {
            if (status == 'success') {
                if (response.status == 'success') {
                    $('#requested_count-error').hide();
                    $('#requested_count-success').text(response.message);
                    $('#requested_count-success').show();
                }
            } else {
                if (response.status == 422) {
                    $.each(response.responseJSON.errors, function (key, value) {
                        $('#requested_count-success').hide();
                        $('#requested_count-error').text(value);
                        $('#requested_count-error').show();
                    });
                }
            }
            buttonObject.find('.spinner-border.button_spinner').removeClass('start');
            //progressIndicator('project-detail', 'hide');
        });

    });

    $('.funds-wrapper select[name="inquiry_action"]').change(function (event) {
        $(this).removeClass('field-error');
    });

    $('.funds-wrapper input[name="project_order_id"]').click(function (event) {
        $(this).closest('.funds-wrapper').find('input[name="project_order_id"]').removeClass('field-error');
        $(this).closest('.funds-wrapper').find('input[name="order_item_id[]"]').map(function () {
            return $(this).removeClass('field-error');
        });
    });

    $('.funds-wrapper input[name="order_item_id[]"]').click(function (event) {
        $(this).closest('.funds-wrapper').find('input[name="project_order_id"]').removeClass('field-error');
        $(this).closest('.funds-wrapper').find('input[name="order_item_id[]"]').map(function () {
            return $(this).removeClass('field-error');
        });
    });

    $('.funds-wrapper .arwenden').click(function (event) {
        event.preventDefault();

//        if (!$(".fundus_inquiry_form").valid()) {
//            return false;
//        }
        let formObject = $(this).closest('.fundus_inquiry_form');
        let inquiryAction = formObject.find('select[name="inquiry_action"]').val();
        let orderId = formObject.find('input[name="project_order_id"]:checked').val();
        let orderItems = formObject.find('input[name="order_item_id[]"]:checked').val();
        let validationErrorFlag = false;

        if (inquiryAction === "") {
            formObject.find('select[name="inquiry_action"]').addClass('field-error');
            validationErrorFlag = true;
        }
        if (orderId === undefined && orderItems === undefined) {
            formObject.find('input[name="project_order_id"]').addClass('field-error');
            validationErrorFlag = true;
        }
        if (validationErrorFlag) {
            return false;
        }

        if (inquiryAction === "export") {
            formObject.attr('action', formObject.find('select[name="inquiry_action"]').find(':selected').data('action'));
            formObject.submit();
        } else if (inquiryAction === "export_gallery") {
            formObject.attr('action', formObject.find('select[name="inquiry_action"]').find(':selected').data('action'));
            formObject.submit();
        } else if (inquiryAction === "delete") {
            $('#delete-store-request').modal({
                backdrop: 'static',
                keyboard: false
            }).on('click', '#delete-store-request-button', function (e) {
                formObject.attr('action', formObject.find('select[name="inquiry_action"]').find(':selected').data('action'));
                formObject.submit();
            });
        }
    });

    $('.item-add-edit input[type="checkbox"]').on('change', function () {
        $('.item-add-edit input[name="' + this.name + '"]').not(this).prop('checked', false);
    });

    $('.upload-doc input[type="file"]').change(function (e) {
        var fileName = e.target.files[0].name;
        $(this).parents('.upload-doc').find('.name').text(fileName);
    });

    $(document).on('keypress', '.only-numeric-data', function (e) {
        if (window.event) // IE
        {
            if ((e.keyCode < 48 || e.keyCode > 57) & e.keyCode != 8 && e.keyCode != 44) {
                event.returnValue = false;
                return false;
            }
        } else { // Fire Foxc
            if ((e.which < 48 || e.which > 57) & e.which != 8 && e.which != 44) {
                e.preventDefault();
                return false;
            }
        }

    });

    $('.open-bulk-upload-popup').click(function () {
        event.preventDefault();
        let formObject = $("#bulk-upload-form");
        formObject[0].reset();
        formObject.find('.upload-bulk-form.csv .upload-doc label').removeClass('field-error');
        formObject.find('.upload-bulk-form.zip .upload-doc label').removeClass('field-error');
        formObject.find('.upload-doc').find('.name').text('');
        $('#bulk-upload .download-categ .select2-selection--single').removeClass('field-error');

        resetFormErrorMessages('bulk-upload-form');
        $('#bulk-upload').modal('show');
    });

    $('#bulk-upload #bulk-upload-sample-csv').change(function (event) {
        let actionLink = $(this).val();
        $('#bulk-upload .download-csv-file').attr('href', actionLink);
        $('#bulk-upload .download-categ .select2-selection--single').removeClass('field-error');
    });

    $('#bulk-upload .download-csv-file').click(function (event) {
        if ($(this).attr('href') == '' || $(this).attr('href') == '#') {
            $('#bulk-upload .download-categ .select2-selection--single').addClass('field-error');
        } else {
            $('#bulk-upload .download-categ .select2-selection--single').removeClass('field-error');
        }
    });

    $('#bulk-upload .upload-csv-file').click(function (event) {
        event.preventDefault();

        resetFormErrorMessages('bulk-upload-form');

        let formObject = $("#bulk-upload-form");
        formObject.validate({
            ignore: [],
            rules: {
                products_csv_file: {
                    required: true,
                    accept: 'csv'
                },
                images_zip_file: {
                    required: true,
                    accept: 'zip'
                }
            }
        });

//        if (!formObject.valid()) {
//            return false;
//        }

        if (formObject.find('input[name="products_csv_file"]').val() === "") {
            formObject.find('.upload-bulk-form.csv .upload-doc label').addClass('field-error');
        } else {
            formObject.find('.upload-bulk-form.csv .upload-doc label').removeClass('field-error');
        }

        if (formObject.find('input[name="images_zip_file"]').val() === "") {
            formObject.find('.upload-bulk-form.zip .upload-doc label').addClass('field-error');
        } else {
            formObject.find('.upload-bulk-form.zip .upload-doc label').removeClass('field-error');
        }

        if (formObject.find('input[name="products_csv_file"]').val() === "" || formObject.find('input[name="images_zip_file"]').val() === "") {
            return false;
        }

        let params = new FormData(document.getElementById("bulk-upload-form"));
        //params = $("#bulk-upload #bulk-upload-form").serialize();


        $(this).find('.spinner-border.button_spinner').addClass('start');
        let buttonObject = $(this);

        callApiWithFile('/fundus/product/bulk/create', params, function (status, response) {
            if (status == 'success') {
                if (response.status == 'success') {
                    $('#bulk-upload').modal('hide');
                    $('#bulk-upload-thank-you-popup').modal('show');
                }
            } else {
                if (response.status == 422) {
                    $.each(response.responseJSON.errors, function (key, value) {
                        $('#bulk-upload-success').hide();
                        $('#bulk-upload-error').text(value);
                        $('#bulk-upload-error').show();
                    });
                }
            }
            buttonObject.find('.spinner-border.button_spinner').removeClass('start');
        });

    });

    if ($(window).width() < 992) {
        $('.item-add-edit .custom-dropdown .category-tab .category-tab2-btn ul li a:not(.option)').on('click', function (event) {
            $(this).parents('.custom-dropdown').find('.tab-content-level2').hide();
            $(this).parents('.custom-dropdown').find('.tab-content-level3').show();
            $(this).parents('.custom-dropdown').find('.btn-second-label').show();
        })

        $('.item-add-edit .custom-dropdown .category-tab .category-tab1-btn ul li a').on('click', function (event) {
            $('.item-add-edit .custom-dropdown .btn-second-label').hide();
            $(this).parents('.custom-dropdown').find('.tab-content-level2').show();
            $(this).parents('.custom-dropdown').find('.tab-content-level3').hide();
        })

        $('.item-add-edit .custom-dropdown .btn-second-label').click(function (event) {
            $(this).parents('.custom-dropdown').find('.tab-content-level2').show();
            $(this).parents('.custom-dropdown').find('.tab-content-level3').hide();
            $(this).hide();
        });
    }

    if (displayInformationModal !== undefined) {
        $('#' + displayInformationModal).modal('show');
    }

    $('.fundus-upgrade-complete-refresh').click(function (event) {
        event.preventDefault();
        location.reload();
    });

    $('.project-upgrade-complete-refresh').click(function (event) {
        event.preventDefault();
        location.reload();
    });

    $('#fundus_message_text').keyup(function (event) {
        toggleSaveDraftCheckbox($(this).val());

    });

//    $('#fundus_message_text').blur(function (event) {
//        event.preventDefault();
//        saveStoreMessageInDraft();
//    });

    $('#send-message-favoriten-fundus .cancel_send_request_to_store_popup').click(function (event) {
        $('#send-message-favoriten-fundus').modal('hide');
        if (fundusDateRangeRequestUpdated) {
            location.reload();
        }
    });

    $('#send-message-favoriten-fundus .send_message_to_fundus').click(function (event) {
        $('#send-message-favoriten-fundus').modal('hide');
        $('#message-to-fundus').modal({backdrop: 'static', keyboard: false});
    });

    $('#message-to-fundus .cancel_send_message_to_fundus').click(function (event) {
        saveStoreMessageInDraft();
        $('#message-to-fundus').modal('hide');
        if (fundusDateRangeRequestUpdated) {
            location.reload();
        }
    });

    $('#message-to-fundus .send_request_to_store').click(function (event) {
        $('.funds-wrapper .send_request_to_store').trigger('click', ['yes']);
    });

    $('.funds-wrapper .send_request_to_store').on('click', function (event, finalSubmit) {
        event.preventDefault();

        let formObject = $(this).closest('.fundus_favourite_form');
        let checkStoreId = formObject.find('input[name="check_store_id"]:checked').val();
        let storeId = formObject.find('input[name="store_id"]').val();
        let favouriteId = formObject.find('input[name="favourite_id[]"]:checked').val();
        let validationErrorFlag = false;
        let popupButton = $('#message-to-fundus .send_request_to_store');

        if (checkStoreId === undefined && favouriteId === undefined) {
            formObject.find('input[name="check_store_id"]').addClass('field-error');
            validationErrorFlag = true;
        }
        if (validationErrorFlag) {
            return false;
        }

        let params = formObject.serialize();
        if (finalSubmit) {
            params = params + '&final_submit=' + finalSubmit;
        }
        params = params + '&storeMessage=' + encodeURIComponent($('#message-to-fundus #fundus_message_text').val());

        formObject.find('.send_request_to_store .spinner-border.button_spinner').addClass('start');
        popupButton.find('.spinner-border.button_spinner').addClass('start');
        $('#message-to-fundus .modal-content').addClass('message-to-fundus-submit');

        callApi('/favourites/storeOrder', params, function (status, response) {
            if (status == 'success') {
                if (response.status == 'success') {
                    location.reload();
                }
            } else {
                if (response.status == 422) {
                    //Validation errors
                    $.each(response.responseJSON.errors, function (key, value) {
                        //error message
                        if (key == 'validateStatus' && value[0] == false) {
                            let storeMessage = '';
                            createPickupReturnDateRanges(response.responseJSON.errors.dateRanges, storeId);
                            $('#send-message-favoriten-fundus').modal({
                                backdrop: 'static',
                                keyboard: false
                            });
                            if (response.responseJSON.errors.storeMessage !== undefined) {
                                storeMessage = response.responseJSON.errors.storeMessage[0]
                            }
                            $('#fundus_message_text').val(storeMessage);
                            toggleSaveDraftCheckbox(storeMessage);
                        } else if (key == 'validateStatus' && value[0] == true) {
                            let storeMessage = '';
                            createPickupReturnDateRanges(response.responseJSON.errors.dateRanges, storeId);
                            if (response.responseJSON.errors.storeMessage !== undefined) {
                                storeMessage = response.responseJSON.errors.storeMessage[0]
                            }
                            $('#fundus_message_text').val(storeMessage);
                            toggleSaveDraftCheckbox(storeMessage);
                            $('#message-to-fundus').modal({
                                backdrop: 'static',
                                keyboard: false
                            });
                        }

                    });
                }
                formObject.find('.send_request_to_store .spinner-border.button_spinner').removeClass('start');
                popupButton.find('.spinner-border.button_spinner').removeClass('start');
                $('#message-to-fundus .modal-content').removeClass('message-to-fundus-submit');
            }
        });

    });

    $('.funds-wrapper .download_favourites_by_store').on('click', function (event, finalSubmit) {
        event.preventDefault();

        let formObject = $(this).closest('.fundus_favourite_form');
        let checkStoreId = formObject.find('input[name="check_store_id"]:checked').val();
        let storeId = formObject.find('input[name="store_id"]').val();
        let favouriteId = formObject.find('input[name="favourite_id[]"]:checked').val();
        let actionUrl = ($(this).data('action')).replace($('#website_url').val(), '');
        let validationErrorFlag = false;

        if (checkStoreId === undefined && favouriteId === undefined) {
            formObject.find('input[name="check_store_id"]').addClass('field-error');
            validationErrorFlag = true;
        }
        if (validationErrorFlag) {
            return false;
        }

        formObject.attr('action', actionUrl);
        formObject.submit();

    });

    $('#favourite_funds .fundus-dates').on('click', '.toggle_button', function (event) {
        event.preventDefault();
        let params = {};
        let dateRangeObject = $(this).closest('.fundus-dates');
        let buttonObject = $(this).find('.btn-toggle');
        let errorMessageObject = $(this).closest('.favourite-funds-children-dates').find('.fundus-dates-errormsg.error');

        params.favourite_date_id = dateRangeObject.find('input[name="favourite_date_id"]').val();
        params.favourite_store_id = dateRangeObject.find('input[name="favourite_store_id"]').val();
        params.active_status = buttonObject.attr('aria-pressed') == "true" ? 'no' : 'yes';

        errorMessageObject.text('');
        dateRangeObject.find('.spinner-border').show();
        callApi('/favourites/fundus/changeRentDate', params, function (status, response) {
            if (status == 'success') {
                if (response.status == 'success') {
                    if (response.is_active == 1) {
                        buttonObject.attr('aria-pressed', true);
                        buttonObject.addClass('active');
                        dateRangeObject.removeClass('deactivated-dates');
                    } else {
                        buttonObject.attr('aria-pressed', false);
                        buttonObject.removeClass('active');
                        dateRangeObject.addClass('deactivated-dates');
                    }
                }
            } else {
                if (response.status == 422) {
                    //Validation errors
                    $.each(response.responseJSON.errors, function (key, value) {
                        //key is field name
                        //error message
                        errorMessageObject.text(value[0]);
                    });
                }
            }
            dateRangeObject.find('.spinner-border').hide();
        });

    });

});

function createPickupReturnDateRanges(favouriteList, storeId) {

    let popupBody = $('.send-message-favoriten-fundus-body');
    let dateRangeError = $('#send-message-favoriten-fundus-body-clone .fundus-dates-errormsg').clone();
    let motivDateRangeData;
    popupBody.html('');

    favouriteList.forEach(function (dataItemRow, dataItemRowIndex) {
        if (dataItemRow.favouriteDateRanges) {
            let motivData = $('#send-message-favoriten-fundus-body-clone .dategroup').clone();
            motivData.find('.set_name').text(dataItemRow.name);
            motivDateRangeData = motivData.find('.fundus-dates-wrap');
            motivDateRangeData.html('');

            dataItemRow.favouriteDateRanges.forEach(function (dataItemDateRange, dataItemDateRangeIndex) {
                let motivDateRange = $('#send-message-favoriten-fundus-body-clone .fundus-dates-wrap .fundus-dates').clone();
                motivDateRange.find('.pickup_date').val(dataItemDateRange.pickup_date);
                motivDateRange.find('.return_date').val(dataItemDateRange.return_date);
                motivDateRange.find('.period-date').html(dataItemDateRange.shooting_period);
                motivDateRange.find('input[name="favourite_date_id"]').val(dataItemDateRange.dateRangeId);
                motivDateRange.find('input[name="favourite_store_id"]').val(storeId);
                if (dataItemDateRange.favourite_date_change_flag == 1) {
                    motivDateRange.find('.period-date').addClass('period-color');
                }
                if (dataItemDateRange.is_active == 0) {
                    motivDateRange.addClass('deactivated-dates');
                }

                motivDateRangeData.append(motivDateRange);
            });
            let dateRangeDesc = $('#send-message-favoriten-fundus-body-clone .fundus-dates-wrap .fundus-dates-desc').clone();
            let dateRangeError = $('#send-message-favoriten-fundus-body-clone .fundus-dates-errormsg').clone();
            motivDateRangeData.append(dateRangeDesc);
            motivDateRangeData.append(dateRangeError);

            popupBody.append(motivData);

            activateDateRangePicker3('up');
        }

    });

}
let fundusDateRangeRequestUpdated = false;
function activateDateRangePicker3(align) {
    $('.daterange-single3').daterangepicker({
        singleDatePicker: true,
        autoApply: true,
        showDropdowns: true,
        autoUpdateInput: false,
        drops: align,
        locale: {
            format: 'DD.MM.YYYY'
        }
    }).on("apply.daterangepicker", function (e, picker) {


        let saveDatesController = (e, picker) => {

            picker.element.val(picker.startDate.format(picker.locale.format));
            let params = {};
            let dateRangeObject = $(picker.element).closest('.fundus-dates');
            let cardHeaderObject = $(picker.element).closest('.card-header');
            let errorMessageObject = $(picker.element).closest('.favourite-funds-children-dates, .dategroup').find('.fundus-dates-errormsg.error');

            params.favourite_date_id = dateRangeObject.find('input[name="favourite_date_id"]').val();
            params.favourite_store_id = dateRangeObject.find('input[name="favourite_store_id"]').val();

            if ($(picker.element).hasClass('pickup_date')) {
                params.pickup_date = $(picker.element).val();
            } else if ($(picker.element).hasClass('return_date')) {
                params.return_date = $(picker.element).val();
            }

            fundusDateRangeRequestUpdated = true;
            errorMessageObject.text('');
            dateRangeObject.find('.spinner-border').show();
            callApi('/favourites/fundus/changeRentDate', params, function (status, response) {
                if (status == 'success') {
                    if (response.status == 'success') {
                        dateRangeObject.find('.period-date').removeClass('period-color');
                        cardHeaderObject.find('.store_request_sent').hide();
                        cardHeaderObject.find('.store_request_changed').show();
                    }
                } else {
                    if (response.status == 422) {
                        $.each(response.responseJSON.errors, function (key, value) {
                            //error message field name (pickup_date, return_date)
                            //error message
                            errorMessageObject.text(value[0]);
                        });
                    }
                }
                dateRangeObject.find('.spinner-border').hide();
            });

        }

        saveDatesController(e, picker);

        //disable future dates based on pickup date
        let pickup_date = picker.startDate.format('YYYY-MM-DD');
        if ($(this).hasClass('pickup_date')) {
            $(this).parents('.fundus-dates').find('.return_date').daterangepicker({
                singleDatePicker: true,
                autoApply: true,
                showDropdowns: true,
                minDate: new Date(pickup_date),
                autoUpdateInput: false,
                locale: {
                    format: 'DD.MM.YYYY'
                }
            }).on("apply.daterangepicker", function (e, picker) {
                let return_date = picker.startDate.format('DD.MM.YYYY');
                $(this).parents('.fundus-dates').find('.return_date').val(return_date);
                saveDatesController(e, picker);
            });
        }
        //end 



    });
}



$(function () {
    $('.delete-store-request-article-confirm').click(function (event) {
        event.preventDefault();
        let buttonObject = $(this);
        $('#delete-store-request-article').modal({
            backdrop: 'static',
            keyboard: false
        }).on('click', '#delete-store-request-article-button', function (e) {
            window.open(buttonObject.attr('href'), '_self');
        });

    });

    $('.create-product-button').click(function (event) {
        event.preventDefault();

        resetFormErrorMessages('product_add_form');

        let formObject = $(this).closest('#product_add_form');
        let params = new FormData(document.getElementById("product_add_form"));

        formObject.find('.spinner-border.button_spinner').addClass('start');

        callApiWithFile('/fundus', params, function (status, response) {
            if (status == 'success') {
                if (response.status == 'success') {
                    window.open(response.route, '_self');
                } else if (response.status == 'error') {
                    window.open(response.route, '_self');
                } else if (response.status == 'opener') {
                    window.opener.postMessage('success', "*");
                    window.close();
                }
            } else {
                if (response.status == 422) {
                    //Validation errors
                    displayProductErrors(response, formObject);
                }
                formObject.find('.spinner-border.button_spinner').removeClass('start');
            }
        });
    });

    $('.edit-product-button').click(function (event) {
        event.preventDefault();

        resetFormErrorMessages('product_edit_form');

        let formObject = $(this).closest('#product_edit_form');
        let params = new FormData(document.getElementById("product_edit_form"));
        let formActionUrl = (formObject.attr('action')).replace($('#website_url').val(), '');

        formObject.find('.spinner-border.button_spinner').addClass('start');

        callApiWithFile(formActionUrl, params, function (status, response) {
            if (status == 'success') {
                if (response.status == 'success') {
                    window.open(response.route, '_self');
                } else if (response.status == 'error') {
                    window.open(response.route, '_self');
                }
            } else {
                if (response.status == 422) {
                    //Validation errors
                    displayProductErrors(response, formObject);
                }
                formObject.find('.spinner-border.button_spinner').removeClass('start');
            }
        });
    });

    $('.custom-cookies, .reject-cookies, .accept-cookies').click(function (event) {
        event.preventDefault();

        let params = {};
        let buttonObject = $(this);

        if (buttonObject.hasClass('accept-cookies')) {
            params.action = 'accepted';
        } else if (buttonObject.hasClass('reject-cookies')) {
            params.action = 'rejected';
        } else if (buttonObject.hasClass('custom-cookies')) {
            params.action = 'custom';
            params.analyse = $('.analyse-cookie-button').attr('aria-pressed') == "true" ? 'yes' : 'no';
            params.marketing = $('.marketing-cookie-button').attr('aria-pressed') == "true" ? 'yes' : 'no';
        }

        buttonObject.find('.spinner-border.button_spinner').addClass('start');

        callApi('/cookies', params, function (status, response) {
            if (status == 'success') {
                if (response.status == 'success') {
                    $('.footer-cookie').hide();
                    $('#cookie-model').modal('hide');
                    if ($('.footer-section').hasClass('footer-space')) {
                        $('.footer-section').removeClass('footer-space');
                    }
                }
            } else {
                if (response.status == 422) {
                    $.each(response.responseJSON.errors, function (key, value) {
                        //error message

                    });
                }
                buttonObject.find('.spinner-border.button_spinner').removeClass('start');
            }
        });
    });
});


function displayProductErrors(response, formObject) {
    let inputFieldObject = null;
    $.each(response.responseJSON.errors, function (key, value) {

        if (key == 'category') {
            inputFieldObject = $('.custom-dropdown');
        } else if (key == 'price.0') {
            inputFieldObject = $('.price-wrapper');
        } else if (key == 'product_image') {
            inputFieldObject = $('.product_image');
        } else if (key == 'primary_product_image') {
            inputFieldObject = $('.product_image');
        } else if (key.indexOf('.') > -1) { //error message for product images
            inputName = key.split('.');
            keyIndex = inputName[1];
            key = inputName[0] + '[]';
            if (inputName[0] == 'product_image') {
                inputFieldObject = formObject.find('[name="' + key + '"]').eq(keyIndex);
                inputFieldObject = inputFieldObject.closest('.img-upload');
            } else if (inputName[0] == 'duration') {
                inputFieldObject = formObject.find('[name="' + key + '"]').eq(keyIndex);
                inputFieldObject = inputFieldObject.closest('.new-article-field-price-duration');
            }
        } else if (key == 'product_image.0') {
            inputFieldObject = $('.item-img');
        } else if (key == 'quantity') {
            inputFieldObject = $('.product-count .counter');

        } else if (['epoche', 'style', 'manufacturer_id', 'manufacture_country'].includes(key)) {
            inputFieldObject = $('[name="' + key + '"]').closest('.input-field').find('.select2-container');

        } else if (['graphic_form', 'file_format', 'copy_right'].includes(key)) {
            inputFieldObject = $('[name="' + key + '"]').closest('.option').last();

        } else if (['length', 'width', 'height', 'dimension_unit'].includes(key)) {
            inputFieldObject = $('[name="' + key + '"]').closest('.dimension');

        } else {
            inputFieldObject = $('[name="' + key + '"]');
        }
        inputFieldObject.after('<span class="error errors-' + key + '" style="text-align: left">' + value + '</span>');

    });
}

$(function () {

    $('.details-cookie').click(function (e) {
        $(this).toggleClass('active');
        $('.details-cookie-content').slideToggle();
    });
    $('.top-header.header-loggedin .head-right a').click(function (event) {
        event.preventDefault();
    });
    $('.top-header.header-loggedin .head-right a').hover(function () {
        $('.top-header').addClass('hovered');
    }, function () {
        $('.top-header').removeClass('hovered');
    });
    $('.top-header.header-loggedin .logged-in-menu').hover(function () {
        $('.top-header').addClass('hovered');
    }, function () {
        $('.top-header').removeClass('hovered');
    });
    $('.top-header.header-loggedin .menu-wrapper .menu-items').hover(function () {
        $('.top-header.header-loggedin .head-right a[href="#' + $(this).attr('id') + '"]').addClass('active');
    }, function () {
        $('.top-header.header-loggedin .head-right a').removeClass('active');
    });
    $('.top-header.header-loggedin .menu-toggle-btn').click(function (event) {
        $(this).find('.menu-toggler').toggleClass('open');
        $('.top-header.header-loggedin .logged-in-menu').toggleClass('menu-open');
    });

    $(document).on('click', '.user-impression', function (event) {

        let params = {};
        let buttonObject = $(this);

        params.key_name = buttonObject.data('impression-key') ? buttonObject.data('impression-key') : 'no-name';
        params.key_value = buttonObject.data('impression-value') ? buttonObject.data('impression-value') : 'yes';

        callApi('/impressions', params, function (status, response) {
            if (status == 'success') {
                if (response.status == 'success') {
                    buttonObject.closest('.user-impression-block').hide();
                }
            } else {
                if (response.status == 422) {
                    $.each(response.responseJSON.errors, function (key, value) {
                        //error message
                    });
                }
            }
        });
    });

    $(document).on('click', '.search-location-impression', function (event) {
        console.log($(this).attr('aria-pressed'));
        if ($(this).attr('aria-pressed') === 'true') {
            saveSearchLocation();
        } else {
            console.log('delete');
            removeSearchLocation();
        }
    });

});

function updateSearchLocation() {
    if ($('.search-location-impression').attr('aria-pressed') === 'true') {
        saveSearchLocation();
    }
}

function saveSearchLocation() {
    let params = {};

    params.key_name = 'search-location';
    params.key_value = $('#ort-order-plz').val() + '::' + $('#geo_location').val() + '::' + $('#ortorder').find(':selected').val();

    callApi('/impressions', params, function (status, response) {
        if (status == 'success') {
            if (response.status == 'success') {
                //Success message
            }
        }
    });
}

function removeSearchLocation() {
    let params = {};

    params.key_name = 'search-location';
    params.key_value = '';
    params._method = 'DELETE';

    callApi('/impressions/search-location', params, function (status, response) {
        if (status == 'success') {
            if (response.status == 'success') {
                //Success message
            }
        }
    });
}

function toggleSaveDraftCheckbox(draftText) {
    if (draftText !== undefined) {
        let messageLength = draftText.length;
        if (messageLength > 0) {
            $('#fundus_message_save_draft').prop('checked', true);
        } else {
            $('#fundus_message_save_draft').prop('checked', false);
        }
    }
}

function saveStoreMessageInDraft() {
    let storeId = $('#send-message-favoriten-fundus').find('input[name="favourite_store_id"]').val();
    let params = {};
    params.store_id = storeId;
    params.message = $('#fundus_message_save_draft').prop('checked') ?
            $('#message-to-fundus #fundus_message_text').val() : '';

    $('#message-to-fundus .modal-content').addClass('message-to-fundus-submit');

    callApi('/favourites/storeMessage', params, function (status, response) {
        if (status == 'success') {
            //No action required
        } else {
            if (response.status == 422) {
                //Show error
            }
        }
        $('#message-to-fundus .modal-content').removeClass('message-to-fundus-submit');
    });
}