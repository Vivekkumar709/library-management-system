// ✅ Allow only digits (0–9), max 10 digits
function validateDigitsOnlyWithMax(el, max = 10) {
  el.value = el.value.replace(/[^0-9]/g, '').slice(0, max);
}

// ✅ Allow only digits (0–9)
function validateDigitsOnly(el) {
    el.value = el.value.replace(/[^0-9]/g, '');
}

// ✅ Validate alphabetic letters only
function validateLettersOnly(el) {
  el.value = el.value.replace(/[^a-zA-Z\s]/g, '');
}

// ✅ Validate email format
function isValidEmail(email) {
  return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email);
}

// ✅ Validate mobile number (10 digits)
function isValidMobile(mobile) {
  return /^[0-9]{10}$/.test(mobile);
}
//===================================

$('.btn_1').on('click', function(e) {         
  var isValid = true; 
  // Clear previous error states
  $('.error-field').removeClass('error-field');
  $('.error-input').removeClass('error-input');
  $('.form-control').removeClass('is-invalid');
  
  // Validate required select elements
  $('select[required]').each(function() {
      if (!$(this).val()) {
          isValid = false;
          $(this).next('.nice-select')
              .addClass('error-field')
              .find('.current')
              .focus();
      }
  });

  $('.select2-multi[required]').each(function() {
    if (!$(this).val() || $(this).val().length === 0) {
        isValid = false;
        var select2Container = $(this).next('.select2-container');
        select2Container.addClass('error-field');
        //$(this).select2('open');
    }
});
  
  
  // Validate required input text fields
  $('input[type="text"][required], input[type="email"][required], input[type="date"][required], input[type="time"][required]').each(function() {
      if (!$(this).val().trim()) {
          isValid = false;
          $(this).addClass('error-input is-invalid').focus();
      } else {
          $(this).removeClass('is-invalid').addClass('is-valid');
      }
  });
  
  // Validate required textarea elements
  $('textarea[required]').each(function() {
      if (!$(this).val().trim()) {
          isValid = false;
          $(this).addClass('error-input is-invalid').focus();
      } else {
          $(this).removeClass('is-invalid').addClass('is-valid');
      }
  });
  
  // Additional input validations
  $('input[type="email"]').each(function() {
      var email = $(this).val().trim();
      if (email && !isValidEmail(email)) {
          isValid = false;
          $(this).addClass('error-input is-invalid').focus();
          showFieldError($(this), 'Please enter a valid email address');
      }
  });
  
  // Mobile number validation (10 digits)
  $('input[name*="mobile"]').each(function() {
      var mobile = $(this).val().trim();
      if (mobile && !isValidMobile(mobile)) {
          isValid = false;
          $(this).addClass('error-input is-invalid').focus();
          showFieldError($(this), 'Please enter a valid 10-digit mobile number');
      }
  });
  
  // Pincode validation (6 digits)
  $('input[name="pincode"]').each(function() {
      var pincode = $(this).val().trim();
      if (pincode && !isValidPincode(pincode)) {
          isValid = false;
          $(this).addClass('error-input is-invalid').focus();
          showFieldError($(this), 'Please enter a valid 6-digit pincode');
      }
  });
  
  // Date validation (not in past for valid_from)
  $('input[name="valid_from"]').each(function() {
      var selectedDate = new Date($(this).val());
      var today = new Date();
      today.setHours(0, 0, 0, 0);
      
      if ($(this).val() && selectedDate < today) {
          isValid = false;
          $(this).addClass('error-input is-invalid').focus();
          showFieldError($(this), 'Valid from date cannot be in the past');
      }
  });
  
  if (!isValid) {
      e.preventDefault();
      // Scroll to first error element
      var firstError = $('.error-field, .error-input').first();
      if (firstError.length) {
          $('html, body').animate({
              scrollTop: firstError.offset().top - 100
          }, 200);
      }
  }
});

// Remove error styling when user starts typing in input fields
$('input[type="text"], input[type="email"], input[type="date"], textarea').on('input', function() {
  $(this).removeClass('error-input is-invalid');
  hideFieldError($(this));
});

// Remove error styling when user selects an option in select fields
// $('select').on('change', function() {
//   $(this).next('.nice-select').removeClass('error-field');
// });
$('select').on('change', function() {
  $(this).next('.nice-select, .nice-select-container, .nice-select-wrap').removeClass('error-field');
});

// Remove error styling when user focuses on fields
$('input, textarea, select').on('focus', function() {
  $(this).removeClass('error-input');
  $(this).next('.nice-select').removeClass('error-field');
});

// Validation helper functions
function isValidEmail(email) {
  var emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
  return emailRegex.test(email);
}

function isValidMobile(mobile) {
  var mobileRegex = /^[0-9]{10}$/;
  return mobileRegex.test(mobile);
}

function isValidPincode(pincode) {
  var pincodeRegex = /^[0-9]{6}$/;
  return pincodeRegex.test(pincode);
}

function showFieldError(field, message) {
  hideFieldError(field);
  var errorDiv = $('<div class="invalid-feedback">' + message + '</div>');
  field.after(errorDiv);
}

function hideFieldError(field) {
  field.siblings('.invalid-feedback').remove();
}


//VALIDATION FOR MODEL
function validateForm(formElement) {
  var isValid = true;
  
  // Clear previous error states
  $(formElement).find('.error-field').removeClass('error-field');
  $(formElement).find('.error-input').removeClass('error-input');
  $(formElement).find('.form-control').removeClass('is-invalid').removeClass('is-valid');
  
  // Validate required select elements
  $(formElement).find('select[required]').each(function() {
      if (!$(this).val()) {
          isValid = false;
          $(this).next('.nice-select')
              .addClass('error-field')
              .find('.current')
              .focus();
          $(this).addClass('is-invalid');
      } else {
          $(this).removeClass('is-invalid').addClass('is-valid');
      }
  });

  $(formElement).find('.select2-multi[required]').each(function() {
      if (!$(this).val() || $(this).val().length === 0) {
          isValid = false;
          var select2Container = $(this).next('.select2-container');
          select2Container.addClass('error-field');
          $(this).addClass('is-invalid');
      } else {
          $(this).removeClass('is-invalid').addClass('is-valid');
      }
  });
  
  // Validate required input fields
  $(formElement).find('input[type="text"][required], input[type="email"][required], input[type="date"][required], input[type="password"][required]').each(function() {
      var $input = $(this);
      var value = $input.val().trim();
      
      if (!value) {
          isValid = false;
          $input.addClass('error-input is-invalid').removeClass('is-valid').focus();
      } else {
          $input.removeClass('is-invalid error-input').addClass('is-valid');
          
          // Additional validation for specific field types
          if ($input.attr('type') === 'email' && !isValidEmail(value)) {
              isValid = false;
              $input.addClass('error-input is-invalid').removeClass('is-valid').focus();
              showFieldError($input, 'Please enter a valid email address');
          }
          
          if ($input.attr('type') === 'password' && $input.attr('minlength') && value.length < parseInt($input.attr('minlength'))) {
              isValid = false;
              $input.addClass('error-input is-invalid').removeClass('is-valid').focus();
              showFieldError($input, 'Password must be at least ' + $input.attr('minlength') + ' characters');
          }
      }
  });
  
  // Validate required textarea elements
  $(formElement).find('textarea[required]').each(function() {
      var $textarea = $(this);
      var value = $textarea.val().trim();
      
      if (!value) {
          isValid = false;
          $textarea.addClass('error-input is-invalid').removeClass('is-valid').focus();
      } else {
          $textarea.removeClass('is-invalid error-input').addClass('is-valid');
      }
  });
  
  // Password match validation (if confirm password exists)
  var $password = $(formElement).find('input[type="password"][name="new_password"]');
  var $confirmPassword = $(formElement).find('input[type="password"][name="confirm_password"]');
  
  if ($password.length && $confirmPassword.length) {
      if ($password.val() !== $confirmPassword.val()) {
          isValid = false;
          $confirmPassword.addClass('error-input is-invalid').removeClass('is-valid').focus();
          showFieldError($confirmPassword, 'Passwords do not match');
      }
  }
  
  if (!isValid) {
      // Scroll to first error element
      var firstError = $(formElement).find('.error-field, .error-input').first();
      if (firstError.length) {
          $('html, body').animate({
              scrollTop: firstError.offset().top - 100
          }, 200);
      }
  }
  
  return isValid;
}

// Existing button click handler
$('.btn_popup').on('click', function(e) {
  var form = $(this).closest('form');
  if (!validateForm(form[0])) {
      e.preventDefault();
  }
});

function showFieldError(fieldId, message) {
  const field = document.getElementById(fieldId);
  if (field) {
      field.classList.add('is-invalid');
      const feedbackElement = field.nextElementSibling;
      if (feedbackElement && feedbackElement.classList.contains('invalid-feedback')) {
          feedbackElement.textContent = message;
          feedbackElement.style.display = 'block';
      }
  }
}

// To Get File Preview function 
function setupImagePreview(inputId, previewId) {
const fileInput = document.getElementById(inputId);
const imagePreview = document.getElementById(previewId);

if (fileInput && imagePreview) {
        fileInput.addEventListener('change', function(event) {
            const file = event.target.files[0];
            if (file && file.type.startsWith('image/')) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    imagePreview.src = e.target.result;
                    imagePreview.classList.remove('d-none');
                };
                reader.readAsDataURL(file);
            } else {
                imagePreview.src = '';
                imagePreview.classList.add('d-none');
            }
        });
    }
}

