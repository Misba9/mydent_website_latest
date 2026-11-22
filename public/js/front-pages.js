/******/ (() => { // webpackBootstrap
/******/ 	var __webpack_modules__ = ({

/***/ "./resources/assets/front/js/front-custom.js":
/*!***************************************************!*\
  !*** ./resources/assets/front/js/front-custom.js ***!
  \***************************************************/
/***/ (() => {

document.addEventListener('turbo:load', loadFrontData);

function loadFrontData() {
  frontAlertInitialize();
  loadFrontSlider();
  loadScroll();
}

function frontAlertInitialize() {
  $('.alert').delay(5000).slideUp(300);
}

function loadFrontSlider() {
  $('.services-carousel').slick({
    dots: false,
    centerPadding: '0',
    slidesToShow: 1,
    slidesToScroll: 1
  });
  $('.testimonial-carousel').slick({
    dots: true,
    centerPadding: '0',
    slidesToShow: 1,
    slidesToScroll: 1,
    responsive: [{
      breakpoint: 992,
      settings: {
        slidesToShow: 1,
        arrows: false
      }
    }]
  });
}

function loadScroll() {
  $(window).scroll(function () {
    var sticky = $('.header'),
        scroll = $(window).scrollTop();
    if (scroll >= 50) sticky.addClass('fixed');else sticky.removeClass('fixed');
  });
}

/***/ }),

/***/ "./resources/assets/front/js/front-language.js":
/*!*****************************************************!*\
  !*** ./resources/assets/front/js/front-language.js ***!
  \*****************************************************/
/***/ (() => {

listenClick('.languageSelection', function () {
  var languageName = $(this).data('prefix-value');
  $.ajax({
    type: 'POST',
    url: route('front.change.language'),
    data: {
      '_token': csrfToken,
      languageName: languageName
    },
    success: function success() {
      location.reload();
    }
  });
});

/***/ }),

/***/ "./resources/assets/js/custom/helper.js":
/*!**********************************************!*\
  !*** ./resources/assets/js/custom/helper.js ***!
  \**********************************************/
/***/ (() => {

window.listen = function (event, selector, callback) {
  $(document).on(event, selector, callback);
};

window.listenClick = function (selector, callback) {
  $(document).on('click', selector, callback);
};

window.listenSubmit = function (selector, callback) {
  $(document).on('submit', selector, callback);
};

window.listenChange = function (selector, callback) {
  $(document).on('change', selector, callback);
};

window.listenKeyup = function (selector, callback) {
  $(document).on('keyup', selector, callback);
};

/***/ }),

/***/ "./resources/assets/js/custom/input_price_format.js":
/*!**********************************************************!*\
  !*** ./resources/assets/js/custom/input_price_format.js ***!
  \**********************************************************/
/***/ (() => {

"use strict";


window.setPrice = function (selector, price) {
  if (price != '' || price > 0) {
    if (typeof price !== 'number') {
      price = price.replace(/,/g, '');
    }

    var formattedPrice = addCommas(price);
    $(selector).val(formattedPrice);
  }
};

window.addCommas = function (nStr) {
  nStr += '';
  var x = nStr.split('.');
  var x1 = x[0];
  var x2 = x.length > 1 ? '.' + x[1] : '';
  var rgx = /(\d+)(\d{3})/;

  while (rgx.test(x1)) {
    x1 = x1.replace(rgx, '$1' + ',' + '$2');
  }

  return x1 + x2;
};

window.getFormattedPrice = function (price) {
  if (price != '' || price > 0) {
    if (typeof price !== 'number') {
      price = price.replace(/,/g, '');
    }

    return addCommas(price);
  }
};

window.priceFormatSelector = function (selector) {
  $(document).on('input keyup keydown keypress', selector, function (event) {
    var price = $(this).val();

    if (price === '') {
      $(this).val('');
    } else {
      if (/[0-9]+(,[0-9]+)*$/.test(price)) {
        $(this).val(getFormattedPrice(price));
        return true;
      } else {
        $(this).val(price.replace(/[^0-9 \,]/, ''));
      }
    }
  });
};

window.removeCommas = function (str) {
  return str.replace(/,/g, '');
};

priceFormatSelector('.price-input');

/***/ }),

/***/ "./resources/assets/js/custom/phone-number-country-code.js":
/*!*****************************************************************!*\
  !*** ./resources/assets/js/custom/phone-number-country-code.js ***!
  \*****************************************************************/
/***/ (() => {

document.addEventListener('turbo:load', loadPhoneNumberCountryCodeData);

function loadPhoneNumberCountryCodeData() {
  loadPhoneNumberCountryCode();
  userCreateForm();
  userEditForm();
  vcardEditForm();
  createSetting();
}

function loadPhoneNumberCountryCode() {
  if (!$('#phoneNumber').length) {
    return false;
  }

  var input = document.querySelector('#phoneNumber'),
      errorMsg = document.querySelector('#error-msg'),
      validMsg = document.querySelector('#valid-msg');
  var errorMap = [Lang.get('messages.invalid_number'), Lang.get('messages.invalid_country_number'), Lang.get('messages.too_short'), Lang.get('messages.too_long'), Lang.get('messages.invalid_number')]; // initialise plugin

  var intl = window.intlTelInput(input, {
    initialCountry: defaultCountryCodeValue,
    separateDialCode: true,
    geoIpLookup: function geoIpLookup(success, failure) {
      $.get('https://ipinfo.io', function () {}, 'jsonp').always(function (resp) {
        var countryCode = resp && resp.country ? resp.country : '';
        success(countryCode);
      });
    },
    utilsScript: '../../public/assets/js/inttel/js/utils.min.js'
  });

  var reset = function reset() {
    input.classList.remove('error');
    errorMsg.innerHTML = '';
    errorMsg.classList.add('d-none');
    validMsg.classList.add('d-none');
  };

  input.addEventListener('blur', function () {
    reset();

    if (input.value.trim()) {
      if (intl.isValidNumber()) {
        validMsg.classList.remove('d-none');
      } else {
        input.classList.add('error');
        var errorCode = intl.getValidationError();
        errorMsg.innerHTML = errorMap[errorCode];
        errorMsg.classList.remove('d-none');
      }
    }
  }); // on keyup / change flag: reset

  input.addEventListener('change', reset);
  input.addEventListener('keyup', reset);

  if (typeof phoneNo != 'undefined' && phoneNo !== '') {
    setTimeout(function () {
      $('#phoneNumber').trigger('change');
    }, 500);
  }

  $('#phoneNumber').on('blur keyup change countrychange', function () {
    if (typeof phoneNo != 'undefined' && phoneNo !== '') {
      intl.setNumber('+' + phoneNo);
      phoneNo = '';
    }

    var getCode = intl.selectedCountryData['dialCode'];
    $('#prefix_code').val(getCode);
  });
  var getCode = intl.selectedCountryData['dialCode'];
  $('#prefix_code').val(getCode);
  var getPhoneNumber = $('#phoneNumber').val();
  var removeDashPhoneNumber = getPhoneNumber.replaceAll('-', ' ');
  var removeSpacePhoneNumber = removeDashPhoneNumber.replace(/\s/g, '');
  $('#phoneNumber').val(removeSpacePhoneNumber);
  $('#phoneNumber').focus();
  $('#phoneNumber').trigger('blur');
}

$(document).on('click', '.iti__country', function () {
  var flagClass = $('.iti__selected-flag>.iti__flag').attr('class');
  flagClass = flagClass.split(/\s+/)[1];
  var dialCodeVal = $('.iti__selected-dial-code').text();
  window.localStorage.setItem('flagClassLocal', flagClass);
  window.localStorage.setItem('dialCodeValLocal', dialCodeVal);
});

function userCreateForm() {
  if (!$('#userCreateForm').length) {
    return false;
  }

  $('#userCreateForm').submit(function () {
    if ($('#error-msg').text() !== '') {
      $('#phoneNumber').focus();
      return false;
    }
  });
}

function vcardEditForm() {
  if (!$('#editForm').length) {
    return false;
  }

  $('#editForm').submit(function () {
    if ($('#error-msg').text() !== '') {
      $('#phoneNumber').focus();
      return false;
    }
  });
}

function createSetting() {
  if (!$('#createSetting').length) {
    return false;
  }

  $('#createSetting').submit(function () {
    if ($('#error-msg').text() !== '') {
      $('#phoneNumber').focus();
      return false;
    }
  });
}

function userEditForm() {
  if (!$('#userEditForm').length) {
    return false;
  }

  $('#userEditForm').submit(function () {
    if ($('#error-msg').text() !== '') {
      $('#phoneNumber').focus();
      return false;
    }
  });
}

/***/ }),

/***/ "./resources/assets/js/fronts/appointments/book_appointment.js":
/*!*********************************************************************!*\
  !*** ./resources/assets/js/fronts/appointments/book_appointment.js ***!
  \*********************************************************************/
/***/ (() => {

document.addEventListener("turbo:load", loadFrontAppointmentData);
var frontTimezoneOffsetMinutes = new Date().getTimezoneOffset();
frontTimezoneOffsetMinutes = frontTimezoneOffsetMinutes === 0 ? 0 : -frontTimezoneOffsetMinutes;
var frontSelectedDate;
var frontCharge = 0;
var frontPayableAmount = 0;
var dateEle = "#templateAppointmentDate";

function loadFrontDateData() {
  if (!$("#templateAppointmentDate").length) {
    return;
  }

  $("#templateAppointmentDate").datepicker({
    language: "es-es",
    format: "yyyy-mm-dd",
    minDate: new Date(),
    startDate: new Date(),
    todayHighlight: true
  });
}

function loadFrontAppointmentData() {
  if (!$("#templateAppointmentDate").length) {
    return;
  }

  loadFrontDateData();
  var frontSelectedDate = $("#templateAppointmentDate").val();

  if (!($("#appointmentDoctorId").val() == "")) {
    $(dateEle).removeAttr("disabled");
    $.ajax({
      url: route("get-service"),
      type: "GET",
      data: {
        appointmentDoctorId: $("#appointmentDoctorId").val()
      },
      success: function success(result) {
        if (result.success) {
          $(dateEle).removeAttr("disabled");
          $("#FrontAppointmentServiceId").empty();
          $("#FrontAppointmentServiceId").append($('<option value=""></option>').text(Lang.get("messages.common.select_service")));
          $.each(result.data, function (i, v) {
            $("#FrontAppointmentServiceId").append($("<option></option>").attr("value", v.id).text(v.name));
          });
        }
      }
    });
  }

  frontPayableAmount = 0;
  frontCharge = 0;

  if (!frontSelectedDate) {
    return false;
  }

  $.ajax({
    url: route("doctor-session-time"),
    type: "GET",
    data: {
      adminAppointmentDoctorId: $("#appointmentDoctorId").val(),
      date: frontSelectedDate,
      timezone_offset_minutes: frontTimezoneOffsetMinutes
    },
    success: function success(result) {
      if (result.success) {
        $(".appointment-slot-data").html("");
        $.each(result.data["slots"], function (index, value) {
          $(".no-time-slot").addClass("d-none");

          if (result.data["bookedSlot"] == null) {
            $(".appointment-slot-data").append('<span class="badge badge-lg slots-item bg-success time-slot" data-id="' + value + '">' + value + "</span>");
          } else {
            if ($.inArray(value, result.data["bookedSlot"]) !== -1) {
              $(".appointment-slot-data").append('<span class="badge badge-lg slots-item bg-success time-slot bookedSlot" data-id="' + value + '">' + value + "</span>");
            } else {
              $(".appointment-slot-data").append('<span class="badge badge-lg slots-item bg-success time-slot" data-id="' + value + '">' + value + "</span>");
            }
          }
        });
      }
    },
    error: function error(result) {
      $(".appointment-slot-data").html("");
      $(".book-appointment-message").css("display", "block");
      var response = '<div class="gen alert alert-danger">' + result.responseJSON.message + "</div>";
      $(".book-appointment-message").html(response).delay(5000).hide("slow");
    }
  });
}

listenChange("#isPatientAccount", function () {
  if (this.checked) {
    $(".name-details").addClass("d-none");
    $(".registered-patient").removeClass("d-none");
    $("#template-medical-email").keyup(function () {
      $("#patientName").val("");
      var email = $("#template-medical-email").val();
      $.ajax({
        url: route("get-patient-name"),
        type: "GET",
        data: {
          email: email
        },
        success: function success(result) {
          if (result.data) {
            $("#patientName").val(result.data);
          }
        }
      });
    });
  } else {
    $(".name-details").removeClass("d-none");
    $(".registered-patient").addClass("d-none");
  }
});
$(".no-time-slot").removeClass("d-none");
listenChange(dateEle, function () {
  frontSelectedDate = $(this).val();
  $.ajax({
    url: route("doctor-session-time"),
    type: "GET",
    data: {
      adminAppointmentDoctorId: $("#appointmentDoctorId").val(),
      date: frontSelectedDate,
      timezone_offset_minutes: frontTimezoneOffsetMinutes
    },
    success: function success(result) {
      if (result.success) {
        $(".appointment-slot-data").html("");
        $.each(result.data["slots"], function (index, value) {
          $(".no-time-slot").addClass("d-none");

          if (result.data["bookedSlot"] == null) {
            $(".appointment-slot-data").append('<span class="badge badge-lg slots-item bg-success time-slot" data-id="' + value + '">' + value + "</span>");
          } else {
            if ($.inArray(value, result.data["bookedSlot"]) !== -1) {
              $(".appointment-slot-data").append('<span class="badge badge-lg slots-item bg-success time-slot bookedSlot" data-id="' + value + '">' + value + "</span>");
            } else {
              $(".appointment-slot-data").append('<span class="badge badge-lg slots-item bg-success time-slot" data-id="' + value + '">' + value + "</span>");
            }
          }
        });
      }
    },
    error: function error(result) {
      $(".appointment-slot-data").html("");
      $(".book-appointment-message").css("display", "block");
      var response = '<div class="gen alert alert-danger">' + result.responseJSON.message + "</div>";
      $(".book-appointment-message").html(response).delay(5000).hide("slow");

      if ($(".no-time-slot").hasClass("d-none")) {
        $(".no-time-slot").removeClass("d-none");
      }
    }
  });
});
listenClick(".time-slot", function () {
  if ($(".time-slot").hasClass("activeSlot")) {
    $(".time-slot").removeClass("activeSlot");
    $(this).addClass("activeSlot");
  } else {
    $(this).addClass("activeSlot");
  }

  var fromToTime = $(this).attr("data-id").split("-");
  var fromTime = fromToTime[0];
  var toTime = fromToTime[1];
  $("#timeSlot").val("");
  $("#toTime").val("");
  $("#timeSlot").val(fromTime);
  $("#toTime").val(toTime);
});
var serviceIdExist = $("#FrontAppointmentServiceId").val();
listenChange("#appointmentDoctorId", function (e) {
  e.preventDefault();
  $("#chargeId").val("");
  $("#templateAppointmentDate").val("");
  $("#addFees").val("");
  frontPayableAmount = 0;
  frontCharge = 0;
  $(".appointment-slot-data").html("");
  $(".no-time-slot").removeClass("d-none");
  $(dateEle).removeAttr("disabled");
  $.ajax({
    url: route("get-service"),
    type: "GET",
    data: {
      appointmentDoctorId: $(this).val()
    },
    success: function success(result) {
      if (result.success) {
        $(dateEle).removeAttr("disabled");
        $("#FrontAppointmentServiceId").empty();
        $("#FrontAppointmentServiceId").append($('<option value=""></option>').text(Lang.get("messages.common.select_service")));
        $.each(result.data, function (i, v) {
          $("#FrontAppointmentServiceId").append($("<option></option>").attr("value", v.id).attr("selected", v.id == serviceIdExist).text(v.name));
        });
      }
    }
  });
});
listenChange("#FrontAppointmentServiceId", function () {
  frontPayableAmount = 0;
  frontCharge = 0;
});
listenSubmit("#frontAppointmentBook", function (e) {
  e.preventDefault();
  var firstName = $("#template-medical-first_name").val().trim();
  var lastName = $("#template-medical-last_name").val().trim();
  var email = $("#template-medical-email").val().trim();
  var doctor = $("#appointmentDoctorId").val().trim();
  var services = $("#FrontAppointmentServiceId").val().trim();
  var appointmentDate = $("#templateAppointmentDate").val().trim();
  $(".book-appointment-message").css("display", "block");

  if (!$("#isPatientAccount").is(":checked")) {
    if (firstName == "") {
      response = '<div class="gen alert alert-danger">' + Lang.get("messages.common.first_name_required") + "</div>";
      $(window).scrollTop($(".appointment-form").offset().top);
      $(".book-appointment-message").html(response).delay(5000).hide("slow");
      return false;
    }

    if (lastName == "") {
      response = '<div class="gen alert alert-danger">' + Lang.get("messages.common.last_name_required") + "</div>";
      $(window).scrollTop($(".appointment-form").offset().top);
      $(".book-appointment-message").html(response).delay(5000).hide("slow");
      return false;
    }
  }

  if (email == "") {
    response = '<div class="gen alert alert-danger">' + Lang.get("messages.common.email_required") + "</div>";
    $(".book-appointment-message").html(response).delay(5000).hide("slow");
    $(window).scrollTop($(".appointment-form").offset().top);
    return false;
  }

  if (doctor == "") {
    response = '<div class="gen alert alert-danger">' + Lang.get("messages.common.doctor_required") + "</div>";
    $(".book-appointment-message").html(response).delay(5000).hide("slow");
    $(window).scrollTop($(".appointment-form").offset().top);
    return false;
  }

  if (services == "") {
    response = '<div class="gen alert alert-danger">' + Lang.get("messages.common.service_required") + "</div>";
    $(".book-appointment-message").html(response).delay(5000).hide("slow");
    $(window).scrollTop($(".appointment-form").offset().top);
    return false;
  }

  if (appointmentDate == "") {
    response = '<div class="gen alert alert-danger">' + Lang.get("messages.common.appointment_date_required") + "</div>";
    $(".book-appointment-message").html(response).delay(5000).hide("slow");
    $(window).scrollTop($(".appointment-form").offset().top);
    return false;
  }

  var btnSaveEle = $(this).find("#saveBtn");
  setFrontBtnLoader(btnSaveEle);
  var frontAppointmentFormData = new FormData($(this)[0]);
  frontAppointmentFormData.set("payable_amount", 0);
  frontAppointmentFormData.set("payment_type", manually);
  var response = '<div class="alert alert-warning alert-dismissable"> ' + Lang.get("messages.common.processing") + "</div>";
  jQuery(this).find(".book-appointment-message").html(response).show("slow");
  $.ajax({
    url: $(this).attr("action"),
    type: "POST",
    data: frontAppointmentFormData,
    processData: false,
    contentType: false,
    success: function success(result) {
      if (result.success) {
        response = '<div class="gen alert alert-success">' + result.message + "</div>";
        $(".book-appointment-message").html(response).delay(5000).hide("slow");
        $(window).scrollTop($(".appointment-form").offset().top);
        $("#frontAppointmentBook")[0].reset();
        setTimeout(function () {
          location.reload();
        }, 1200);
      }
    },
    error: function error(result) {
      $(".book-appointment-message").css("display", "block");
      response = '<div class="gen alert alert-danger">' + result.responseJSON.message + "</div>";
      $(window).scrollTop($(".appointment-form").offset().top);
      $(".book-appointment-message").html(response).delay(5000).hide("slow");
    },
    complete: function complete() {
      setFrontBtnLoader(btnSaveEle);
    }
  });
});
listenClick(".show-more-btn", function () {
  if ($(".question").hasClass("d-none")) {
    $(".question").removeClass("d-none");
    $(".show-more-btn").html("show less");
  } else {
    $(".show-content").addClass("d-none");
    $(".show-more-btn").html("show more");
  }
});

window.setFrontBtnLoader = function (btnLoader) {
  if (btnLoader.attr("data-old-text")) {
    btnLoader.html(btnLoader.attr("data-old-text")).prop("disabled", false);
    btnLoader.removeAttr("data-old-text");
    return;
  }

  btnLoader.attr("data-old-text", btnLoader.text());
  btnLoader.html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>').prop("disabled", true);
};

function storeFailedPayment(response) {
  $.ajax({
    type: "POST",
    url: route("razorpay.failed"),
    data: {
      data: response
    },
    success: function success(result) {
      if (result.success) {
        displaySuccessMessage(result.message);
      }
    },
    error: function error() {}
  });
}

/***/ }),

/***/ "./resources/assets/js/fronts/cms/create.js":
/*!**************************************************!*\
  !*** ./resources/assets/js/fronts/cms/create.js ***!
  \**************************************************/
/***/ (() => {

document.addEventListener('turbo:load', loadFrontCMSData);

function loadFrontCMSData() {
  $('#cmsShortDescription').on('keyup', function () {
    $('#cmsShortDescription').attr('maxlength', 800);
  });
  $('#cmsShortDescription').attr('maxlength', 800);

  if (!$('#cmsTermConditionId').length) {
    return;
  }

  var quill1 = new Quill('#cmsTermConditionId', {
    modules: {
      toolbar: [[{
        header: [1, 2, false]
      }], ['bold', 'italic', 'underline'], ['image', 'code-block']]
    },
    placeholder: Lang.get('messages.cms.terms_conditions'),
    theme: 'snow' // or 'bubble'

  });
  quill1.on('text-change', function (delta, oldDelta, source) {
    if (quill1.getText().trim().length === 0) {
      quill1.setContents([{
        insert: ''
      }]);
    }
  });

  if (!$('#cmsPrivacyPolicyId').length) {
    return;
  }

  var quill2 = new Quill('#cmsPrivacyPolicyId', {
    modules: {
      toolbar: [[{
        header: [1, 2, false]
      }], ['bold', 'italic', 'underline'], ['image', 'code-block']]
    },
    placeholder: Lang.get('messages.cms.privacy_policy'),
    theme: 'snow' // or 'bubble'

  });
  quill2.on('text-change', function (delta, oldDelta, source) {
    if (quill2.getText().trim().length === 0) {
      quill2.setContents([{
        insert: ''
      }]);
    }
  });
  var element = document.createElement('textarea');
  element.innerHTML = $('#cmsTermConditionData').val();
  quill1.root.innerHTML = element.value;
  element.innerHTML = $('#cmsPrivacyPolicyData').val();
  quill2.root.innerHTML = element.value;
  listenSubmit('#addCMSForm', function () {
    var title = $('#aboutTitleId').val();
    var empty = title.trim().replace(/ \r\n\t/g, '') === '';
    var description = $('#cmsShortDescription').val();
    var empty2 = description.trim().replace(/ \r\n\t/g, '') === '';

    if (empty) {
      displayErrorMessage('About Title field is not contain only white space');
      return false;
    }

    if (empty2) {
      displayErrorMessage('About Short Description field is not contain only white space');
      return false;
    }

    if ($('#aboutExperience').val() === '') {
      displayErrorMessage('About Experience field is required.');
      return false;
    }

    var element = document.createElement('textarea');
    var editor_content_1 = quill1.root.innerHTML;
    element.innerHTML = editor_content_1;
    var editor_content_2 = quill2.root.innerHTML;

    if (quill1.getText().trim().length === 0) {
      displayErrorMessage('The Terms & Conditions is required.');
      return false;
    }

    if (quill2.getText().trim().length === 0) {
      displayErrorMessage('The Privacy Policy is required.');
      return false;
    }

    $('#termData').val(JSON.stringify(editor_content_1));
    $('#privacyData').val(JSON.stringify(editor_content_2));
  });
}

/***/ }),

/***/ "./resources/assets/js/fronts/enquiries/enquiry.js":
/*!*********************************************************!*\
  !*** ./resources/assets/js/fronts/enquiries/enquiry.js ***!
  \*********************************************************/
/***/ (() => {

listenClick('#enquiryResetFilter', function () {
  var allEnquiry = $('#allEnquiry').val();
  $('#enquiriesStatus').val(allEnquiry).trigger('change');
  hideDropdownManually($('#enquiryFilterBtn'), $('.dropdown-menu'));
});
listenChange('#enquiriesStatus', function () {
  window.livewire.emit('changeStatusFilter', $(this).val());
});
listenClick('.enquiry-delete-btn', function () {
  var enquiryRecordId = $(this).attr('data-id');
  deleteItem(route('enquiries.destroy', enquiryRecordId), Lang.get('messages.web.enquiry'));
});

/***/ }),

/***/ "./resources/assets/js/fronts/faqs/faqs.js":
/*!*************************************************!*\
  !*** ./resources/assets/js/fronts/faqs/faqs.js ***!
  \*************************************************/
/***/ (() => {

listenClick('.faq-delete-btn', function (event) {
  var faqRecordId = $(event.currentTarget).attr('data-id');
  deleteItem(route('faqs.destroy', faqRecordId), Lang.get('messages.faqs'));
});
listenClick('.accordion-button', function (event) {
  var tohide = $(this).attr("data-bs-target");

  if (!$(this).hasClass('custom-class')) {
    $(this).addClass('custom-class');
    $(tohide).addClass('show');
    $(tohide).removeClass('hide');
    $(this).attr("aria-expanded", "true");
  } else {
    $(this).attr("aria-expanded", "false");
    $(this).addClass('collapsed');
    $(tohide).removeClass('show');
    $(tohide).addClass('hide');
    $(this).removeClass('custom-class');
    $(this).css("box-shadow", "none");
  }
});

/***/ }),

/***/ "./resources/assets/js/fronts/front_home/front-home.js":
/*!*************************************************************!*\
  !*** ./resources/assets/js/fronts/front_home/front-home.js ***!
  \*************************************************************/
/***/ (() => {

document.addEventListener('turbo:load', loadFrontHomeData);

function loadFrontHomeData() {
  var frontAppointmentDate = '#frontAppointmentDate';

  if (!$(frontAppointmentDate).length) {
    return;
  }

  $(frontAppointmentDate).datepicker({
    format: 'yyyy-mm-dd',
    startDate: new Date(),
    todayHighlight: true
  });
}

/***/ }),

/***/ "./resources/assets/js/fronts/front_patient_testimonials/create-edit.js":
/*!******************************************************************************!*\
  !*** ./resources/assets/js/fronts/front_patient_testimonials/create-edit.js ***!
  \******************************************************************************/
/***/ (() => {

document.addEventListener('turbo:load', loadFrontTestimonialData);

function loadFrontTestimonialData() {
  if (!$('#shortDescription').length) {
    return;
  }

  $('#shortDescription').on('keyup', function () {
    $('#shortDescription').attr('maxlength', 111);
  });
}

/***/ }),

/***/ "./resources/assets/js/fronts/front_patient_testimonials/front_patient_testimonials.js":
/*!*********************************************************************************************!*\
  !*** ./resources/assets/js/fronts/front_patient_testimonials/front_patient_testimonials.js ***!
  \*********************************************************************************************/
/***/ (() => {

listenClick('.front-testimonial-delete-btn', function (event) {
  var testimonialRecordId = $(event.currentTarget).attr('data-id');
  deleteItem(route('front-patient-testimonials.destroy', testimonialRecordId), Lang.get('messages.front_patient_testimonials'));
});

/***/ }),

/***/ "./resources/assets/js/fronts/medical-contact/enquiry.js":
/*!***************************************************************!*\
  !*** ./resources/assets/js/fronts/medical-contact/enquiry.js ***!
  \***************************************************************/
/***/ (() => {

// listenSubmit('#enquiryForm', function (e) {
//     e.preventDefault()
//     let btnLoader = $(this).find('button[type="submit"]')
//     // setBtnLoader(btnLoader)
//     $.ajax({
//         url: route('enquiries.store'),
//         type: 'POST',
//         data: $(this).serialize(),
//         success: function (result) {
//             if (result.success) {
//              
//                 $('#enquiryForm')[0].reset()
//                
//             }
//         },
//         error: function (error) {
//             // toastr.error(error.responseJSON.message)
//         },
//     })
// })

/***/ }),

/***/ "./resources/assets/js/fronts/sliders/create-edit-slider.js":
/*!******************************************************************!*\
  !*** ./resources/assets/js/fronts/sliders/create-edit-slider.js ***!
  \******************************************************************/
/***/ (() => {

document.addEventListener('turbo:load', loadSliderData);

function loadSliderData() {
  if (!$('#shortDescription').length) {
    return;
  }

  listenKeyup('#shortDescription', function () {
    $('#sliderShortDescription').attr('maxlength', 55);
  });

  if (!$('#sliderShortDescription').length) {
    return;
  }

  $('#sliderShortDescription').attr('maxlength', 55);
}

/***/ }),

/***/ "./resources/assets/js/fronts/sliders/slider.js":
/*!******************************************************!*\
  !*** ./resources/assets/js/fronts/sliders/slider.js ***!
  \******************************************************/
/***/ (() => {



/***/ }),

/***/ "./resources/assets/js/fronts/subscribers/create.js":
/*!**********************************************************!*\
  !*** ./resources/assets/js/fronts/subscribers/create.js ***!
  \**********************************************************/
/***/ (() => {

listenSubmit('#subscribeForm', function (e) {
  e.preventDefault();
  $.ajax({
    url: route('subscribe.store'),
    type: 'POST',
    data: $(this).serialize(),
    success: function success(result) {
      if (result.success) {
        $('.subscribeForm-message').append('' + '<div class="gen alert alert-success">' + Lang.get("messages.flash.subscriber_creat") + '</div>').delay(5000);
        setTimeout(function () {
          $('.subscribeForm-message').empty();
          $('#subscribeForm')[0].reset();
        }, 3000);
      }
    },
    error: function error(_error) {
      $('.subscribeForm-message').append('' + '<div class="err alert alert-danger">' + Lang.get("messages.common.email_already_exist") + '</div>').delay(5000);
      setTimeout(function () {
        $('.subscribeForm-message').empty();
        $('#subscribeForm')[0].reset();
      }, 3000);
    },
    complete: function complete() {}
  });
});

/***/ }),

/***/ "./resources/assets/js/fronts/subscribers/subscriber.js":
/*!**************************************************************!*\
  !*** ./resources/assets/js/fronts/subscribers/subscriber.js ***!
  \**************************************************************/
/***/ (() => {

listenClick('.subscriber-delete-btn', function () {
  var subscriberId = $(this).attr('data-id');
  deleteItem(route('subscribers.destroy', subscriberId), Lang.get('messages.subscribers'));
});

/***/ }),

/***/ "./resources/assets/js/turbo.js":
/*!**************************************!*\
  !*** ./resources/assets/js/turbo.js ***!
  \**************************************/
/***/ (() => {

throw new Error("Module parse failed: 'import' and 'export' may appear only with 'sourceType: module' (1:0)\nFile was processed with these loaders:\n * ./node_modules/babel-loader/lib/index.js\nYou may need an additional loader to handle the result of these loaders.\n> import * as Turbo from \"@hotwired/turbo\"; // Guard against Turbo Drive re-injecting this bundle (CustomElementRegistry:\n| // \"turbo-frame has already been used\").\n| ");

/***/ })

/******/ 	});
/************************************************************************/
/******/ 	
/******/ 	// startup
/******/ 	// Load entry module and return exports
/******/ 	// This entry module doesn't tell about it's top-level declarations so it can't be inlined
/******/ 	__webpack_modules__["./resources/assets/js/turbo.js"]();
/******/ 	__webpack_modules__["./resources/assets/js/custom/helper.js"]();
/******/ 	__webpack_modules__["./resources/assets/js/custom/input_price_format.js"]();
/******/ 	__webpack_modules__["./resources/assets/js/fronts/sliders/slider.js"]();
/******/ 	__webpack_modules__["./resources/assets/front/js/front-custom.js"]();
/******/ 	__webpack_modules__["./resources/assets/js/fronts/sliders/create-edit-slider.js"]();
/******/ 	__webpack_modules__["./resources/assets/js/custom/phone-number-country-code.js"]();
/******/ 	__webpack_modules__["./resources/assets/js/fronts/medical-contact/enquiry.js"]();
/******/ 	__webpack_modules__["./resources/assets/js/fronts/subscribers/create.js"]();
/******/ 	__webpack_modules__["./resources/assets/js/fronts/faqs/faqs.js"]();
/******/ 	__webpack_modules__["./resources/assets/js/fronts/front_patient_testimonials/front_patient_testimonials.js"]();
/******/ 	__webpack_modules__["./resources/assets/js/fronts/front_patient_testimonials/create-edit.js"]();
/******/ 	__webpack_modules__["./resources/assets/js/fronts/enquiries/enquiry.js"]();
/******/ 	__webpack_modules__["./resources/assets/js/fronts/subscribers/subscriber.js"]();
/******/ 	__webpack_modules__["./resources/assets/js/fronts/cms/create.js"]();
/******/ 	__webpack_modules__["./resources/assets/js/fronts/appointments/book_appointment.js"]();
/******/ 	__webpack_modules__["./resources/assets/js/fronts/front_home/front-home.js"]();
/******/ 	var __webpack_exports__ = {};
/******/ 	__webpack_modules__["./resources/assets/front/js/front-language.js"]();
/******/ 	
/******/ })()
;