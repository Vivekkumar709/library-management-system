    <!-- Features Section -->
    <section id="features" class="features section py-5" >
      <div class="container section-title pt-5" data-aos="fade-up">
        <h2>Upgrade Your Plan</h2>
        <p>Unlock all premium features with a subscription</p>
      </div>

      <div class="container">
        <div class="row justify-content-center">
          <div class="col-lg-8">
            <div class="card subscription-card shadow-lg border-0">
              <!-- Progress Bar -->
              <div class="progressbar mb-4 px-4 pt-4">
                <div class="progress-step active" data-title="Plan"><i class="fas fa-list-ul"></i></div>
                <div class="progress-step" data-title="Info"><i class="fas fa-user"></i></div>
                <div class="progress-step" data-title="Payment"><i class="fas fa-credit-card"></i></div>
                <div class="progress-step" data-title="Review"><i class="fas fa-clipboard-check"></i></div>
              </div>

              <div class="card-body p-4 p-md-5">
                <form id="subscriptionForm">
                  <!-- Step 1: Plan Selection -->
                  <div class="form-step form-step-active" id="step-plan">
                    <h5 class="mb-4">Select Your Plan</h5>
                    <div class="row g-3">
                      <?php 
                      $uri = service('uri');
                       $getId =  $uri->getSegment(2);                       
                       $getPlanTypeId =  $uri->getSegment(3); 

                       $plans = get_records('plan_packages pp', [
                        'joins' => [                             
                            [
                                'table' => 'plan_tenure ptr',
                                'condition' => 'pp.tenure_id = ptr.id',
                                'type' => 'left'
                            ],
                            [
                                'table' => 'plan_type pt',
                                'condition' => 'pp.plan_type_id = pt.id',
                                'type' => 'left'
                            ]
                        ],                                        
                        'select' => [
                            'pp.id',                                                                
                            'pp.price',                                            
                            'ptr.name as tenure'                                                                                                                                                                                 
                        ],   
                        'filters'=>[
                            'plan_type_id' => $getPlanTypeId 
                            //'pp.status' => 0,                                                    
                        ],         
                        'groupBy' => 'pp.id, ptr.name, pt.name',
                        'orderBy' => 'pp.id'
                    ]);                                                            
                    foreach ($plans as $key => $pdata){ ?>
                      <div class="col-md-4">
                        <div class="form-check card p-3 h-100 plan-card">
                          <input class="form-check-input" type="radio" name="plan" id="<?=$pdata['tenure'] ?>Plan" value="<?=$pdata['tenure'] ?>" data-price="<?= (int)$pdata['price'] ?>" <?php if($pdata['id'] == $getId){?>checked<?php }?>>
                          <label class="form-check-label" for="<?=$pdata['tenure'] ?>Plan">
                            <h6><?=$pdata['tenure'] ?></h6>
                            <div class="d-flex align-items-baseline mt-2">
                              <span class="h4"><sup>&#8377;</sup><?= (int)$pdata['price'] ?></span>
                              <span class="text-muted ms-1">/<?=$pdata['tenure'] ?></span>
                            </div>
                            <small class="text-muted d-block mt-1">Billed <?=$pdata['tenure'] ?></small>
                          </label>
                        </div>
                      </div>                     
                      <!-- <div class="col-md-4">
                        <div class="form-check card p-3 h-100 plan-card">
                          <input class="form-check-input" type="radio" name="plan" id="lifetimePlan" value="lifetime">
                          <label class="form-check-label" for="lifetimePlan">
                            <h6>Lifetime</h6>
                            <div class="d-flex align-items-baseline mt-2">
                              <span class="h4">$299</span>
                            </div>
                            <small class="text-success d-block mt-1">One-time payment</small>
                          </label>
                        </div>
                      </div> -->
                      <?php } ?>
                    </div>
                    <button type="button" class="btn btn-primary btn-next mt-4 w-100 rounded-pill">Next</button>
                  </div>

                  <!-- Step 2: Personal Info -->
                  <div class="form-step" id="step-info">
                    <h5 class="mb-4">Personal Information</h5>
                    <div class="row g-3">
                      <div class="col-md-6">
                        <label for="firstName" class="form-label">First Name</label>
                        <input type="text" class="form-control" id="firstName" required>
                      </div>
                      <div class="col-md-6">
                        <label for="lastName" class="form-label">Last Name</label>
                        <input type="text" class="form-control" id="lastName" required>
                      </div>
                      <div class="col-12">
                        <label for="email" class="form-label">Email Address</label>
                        <input type="email" class="form-control" id="email" required>
                      </div>
                    </div>
                    <div class="d-flex justify-content-between mt-4">
                      <button type="button" class="btn btn-outline-secondary btn-prev rounded-pill">Back</button>
                      <button type="button" class="btn btn-primary btn-next rounded-pill">Next</button>
                    </div>
                  </div>

                  <!-- Step 3: Payment -->
                  <div class="form-step" id="step-payment">
                    <h5 class="mb-4">Payment Method</h5>
                    <div class="row g-3">
                      <div class="col-md-6">
                        <div class="payment-method card p-3 h-100 active" data-method="creditCard">
                          <input type="radio" class="d-none" name="payment" id="creditCard" checked>
                          <div class="d-flex align-items-center">
                            <i class="far fa-credit-card fa-2x text-primary me-3"></i>
                            <div>
                              <h6 class="mb-0">Credit/Debit Card</h6>
                              <small class="text-muted">Visa, Mastercard, Amex</small>
                            </div>
                          </div>
                          <div class="mt-3 payment-fields" id="creditCardFields">
                            <div class="mb-3">
                              <label for="cardNumber" class="form-label">Card Number</label>
                              <input type="text" class="form-control" id="cardNumber" placeholder="1234 5678 9012 3456">
                            </div>
                            <div class="row g-2">
                              <div class="col-md-6">
                                <label for="expiryDate" class="form-label">Expiry Date</label>
                                <input type="text" class="form-control" id="expiryDate" placeholder="MM/YY">
                              </div>
                              <div class="col-md-6">
                                <label for="cvv" class="form-label">CVV</label>
                                <input type="text" class="form-control" id="cvv" placeholder="123">
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                      <div class="col-md-6">
                        <div class="payment-method card p-3 h-100" data-method="paypal">
                          <input type="radio" class="d-none" name="payment" id="paypal">
                          <div class="d-flex align-items-center">
                            <i class="fab fa-paypal fa-2x text-primary me-3"></i>
                            <div>
                              <h6 class="mb-0">PayPal</h6>
                              <small class="text-muted">Safer, easier way to pay</small>
                            </div>
                          </div>
                        </div>
                        <div class="mt-3" id="paypalEmailGroup">
                          <label for="paypalEmail" class="form-label">PayPal Email</label>
                          <input type="email" class="form-control" id="paypalEmail" placeholder="your@email.com">
                        </div>
                      </div>
                    </div>
                    <div class="d-flex justify-content-between mt-4">
                      <button type="button" class="btn btn-outline-secondary btn-prev rounded-pill">Back</button>
                      <button type="button" class="btn btn-primary btn-next rounded-pill">Next</button>
                    </div>
                  </div>

                  <!-- Step 4: Review -->
                  <div class="form-step" id="step-review">
                    <h5 class="mb-4">Review & Confirm</h5>
                    <div class="card mb-3">
                      <div class="card-body">
                        <h6 class="mb-2">Plan: <span id="reviewPlan" class="fw-bold"></span></h6>
                        <h6 class="mb-2">Name: <span id="reviewName" class="fw-bold"></span></h6>
                        <h6 class="mb-2">Email: <span id="reviewEmail" class="fw-bold"></span></h6>
                        <h6 class="mb-2">Payment: <span id="reviewPayment" class="fw-bold"></span></h6>
                      </div>
                    </div>
                    <div class="form-check mb-4">
                      <input class="form-check-input" type="checkbox" id="termsCheck" required>
                      <label class="form-check-label" for="termsCheck">
                        I agree to the <a href="#" data-bs-toggle="modal" data-bs-target="#termsModal">Terms of Service</a> and <a href="#" data-bs-toggle="modal" data-bs-target="#privacyModal">Privacy Policy</a>
                      </label>
                    </div>
                    <div class="d-flex justify-content-between">
                      <button type="button" class="btn btn-outline-secondary btn-prev rounded-pill">Back</button>
                      <button type="submit" class="btn btn-success btn-subscribe rounded-pill">Subscribe Now</button>
                    </div>
                  </div>
                </form>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- Add jQuery first -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <!-- FontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <script>
      // Wait for document to be fully loaded
      $(document).ready(function() {
        // Hide all steps except first one
        $('.form-step:not(:first)').hide();
        
        // Initialize variables
        let currentStep = 0;
        const totalSteps = $('.form-step').length;

        // Show/hide PayPal email field
        function togglePayPalField() {
          if ($('input[name="payment"]:checked').attr('id') === 'paypal') {
            $('#paypalEmailGroup').show();
          } else {
            $('#paypalEmailGroup').hide();
          }
        }

        // Function to update progress bar
        function updateProgress(step) {
          $('.progress-step').removeClass('active completed');
          $('.progress-step').eq(step).addClass('active');
          for (let i = 0; i < step; i++) {
            $('.progress-step').eq(i).addClass('completed');
          }
        }

        // Function to show step
        function showStep(step) {
          $('.form-step').hide();
          $('.form-step').eq(step).show();
          updateProgress(step);

          // Show/hide back button
          if (step === 0) {
            $('.btn-prev').hide();
          } else {
            $('.btn-prev').show();
          }

          // Show/hide next/submit button
          if (step === totalSteps - 1) {
            $('.btn-next').hide();
            $('.btn-subscribe').show();
          } else {
            $('.btn-next').show();
            $('.btn-subscribe').hide();
          }
          if (step === 2) togglePayPalField();
        }

        // Validation helpers
        function isName(name) {
          return /^[A-Za-z]{2,}$/.test(name);
        }
        function isEmail(email) {
          return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email);
        }
        function isCardNumber(num) {
          return /^\d{16}$/.test(num.replace(/\s+/g, ''));
        }
        function isExpiryDate(date) {
          if (!/^\d{2}\/\d{2}$/.test(date)) return false;
          const [mm, yy] = date.split('/').map(Number);
          if (mm < 1 || mm > 12) return false;
          const now = new Date();
          const year = 2000 + yy;
          const expiry = new Date(year, mm);
          return expiry > now;
        }
        function isCVV(cvv) {
          return /^\d{3}$/.test(cvv);
        }

        // Step validation (only validate fields for the current step)
        function validateCurrentStep(step) {
          if (step === 1) {
            // Personal Info
            const first = $('#firstName').val().trim();
            const last = $('#lastName').val().trim();
            const email = $('#email').val().trim();
            if (!isName(first)) {
              alert('Please enter a valid first name (letters only, min 2).');
              $('#firstName').focus();
              return false;
            }
            if (!isName(last)) {
              alert('Please enter a valid last name (letters only, min 2).');
              $('#lastName').focus();
              return false;
            }
            if (!isEmail(email)) {
              alert('Please enter a valid email address.');
              $('#email').focus();
              return false;
            }
          }
          if (step === 2) {
            // Payment
            const payment = $('input[name="payment"]:checked').attr('id');
            if (payment === 'creditCard') {
              const card = $('#cardNumber').val().replace(/\s+/g, '');
              const expiry = $('#expiryDate').val().trim();
              const cvv = $('#cvv').val().trim();
              if (!isCardNumber(card)) {
                alert('Card number must be 16 digits.');
                $('#cardNumber').focus();
                return false;
              }
              if (!isExpiryDate(expiry)) {
                alert('Expiry date must be MM/YY and in the future.');
                $('#expiryDate').focus();
                return false;
              }
              if (!isCVV(cvv)) {
                alert('CVV must be 3 digits.');
                $('#cvv').focus();
                return false;
              }
            } else if (payment === 'paypal') {
              const paypalEmail = $('#paypalEmail').val().trim();
              if (!isEmail(paypalEmail)) {
                alert('Please enter a valid PayPal email address.');
                $('#paypalEmail').focus();
                return false;
              }
            }
          }
          return true;
        }

        // Validate all steps on submit
        function validateAllSteps() {
          // Step 1: Personal Info
          if (!validateCurrentStep(1)) return false;
          // Step 2: Payment
          if (!validateCurrentStep(2)) return false;
          // Step 3: Terms
          if (!$('#termsCheck').prop('checked')) {
            alert('Please agree to the Terms of Service and Privacy Policy.');
            return false;
          }
          return true;
        }

        // Next button click
        $(document).on('click', '.btn-next', function(e) {
          e.preventDefault();
          if (!validateCurrentStep(currentStep)) return;
          if (currentStep < totalSteps - 1) {
            currentStep++;
            showStep(currentStep);
            if (currentStep === totalSteps - 1) {
              fillReview();
            }
          }
        });

        // Previous button click
        $(document).on('click', '.btn-prev', function(e) {
          e.preventDefault();
          if (currentStep > 0) {
            currentStep--;
            showStep(currentStep);
          }
        });

        // Plan card selection
        $(document).on('click', '.plan-card', function(e) {
          e.preventDefault();
          $('.plan-card').removeClass('selected');
          $(this).addClass('selected');
          $(this).find('input[type="radio"]').prop('checked', true);
        });

        // Payment method selection
        $(document).on('click', '.payment-method', function(e) {
          e.preventDefault();
          $('.payment-method').removeClass('active');
          $(this).addClass('active');
          $(this).find('input[type="radio"]').prop('checked', true);
          togglePayPalField();
        });

        // Fill review information
        function fillReview() {
          const $selectedPlan = $('input[name="plan"]:checked');
          const planTenure = $selectedPlan.val();
          const planPrice = $selectedPlan.data('price');
          $('#reviewPlan').text(
            planTenure.charAt(0).toUpperCase() + planTenure.slice(1) + ' (₹' + planPrice + ' / ' + planTenure + ')'
          );
          $('#reviewName').text($('#firstName').val() + ' ' + $('#lastName').val());
          $('#reviewEmail').text($('#email').val());
          const payment = $('input[name="payment"]:checked').attr('id');
          if (payment === 'creditCard') {
            $('#reviewPayment').text('Credit/Debit Card');
          } else {
            $('#reviewPayment').text('PayPal (' + $('#paypalEmail').val() + ')');
          }
        }

        // Form submission
        $('#subscriptionForm').on('submit', function(e) {
          e.preventDefault();
          if (!validateAllSteps()) return;
          const $submitBtn = $('.btn-subscribe');
          $submitBtn
            .prop('disabled', true)
            .html('<i class="fas fa-spinner fa-spin me-2"></i> Processing...');

          // Simulate form submission
          setTimeout(function() {
            alert('Subscription successful!');
            $submitBtn
              .prop('disabled', false)
              .html('<i class="fas fa-check me-2"></i> Subscribed!');
          }, 2000);
        });

        // Initialize
        showStep(currentStep);
        $('.btn-prev').hide();
        $('.btn-subscribe').hide();
        $('#paypalEmailGroup').hide();

        // Debug logging
        console.log('Script initialized');
        console.log('Total steps:', totalSteps);
      });
    </script>

    <style>
      .form-step {
        display: none;
      }
      .form-step:first-child {
        display: block;
      }
      .plan-card {
        cursor: pointer;
        border: 2px solid transparent;
        transition: all 0.3s ease;
      }
      .plan-card.selected {
        border-color: #388da8;
        background: #f8f9fa;
      }
      .payment-method {
        cursor: pointer;
        border: 2px solid transparent;
        transition: all 0.3s ease;
      }
      .payment-method.active {
        border-color: #388da8;
        background: #f8f9fa;
      }
      .progressbar {
        display: flex;
        justify-content: space-between;
        position: relative;
        margin-bottom: 2rem;
      }
      .progress-step {
        width: 40px;
        height: 40px;
        background: #e0f1f6;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.2rem;
        color: #388da8;
        position: relative;
        z-index: 1;
        transition: background 0.3s, color 0.3s;
      }
      .progress-step.active, .progress-step.completed {
        background: #388da8;
        color: #fff;
      }
      .progress-step:not(:last-child)::after {
        content: '';
        position: absolute;
        width: 100%;
        height: 3px;
        background: #f1f1f1;
        left: 100%;
        top: 50%;
        transform: translateY(-50%);
        z-index: 0;
      }
      .progress-step.completed:not(:last-child)::after {
        background: #388da8;
      }
      .progress-step[data-title]::before {
        content: attr(data-title);
        position: absolute;
        top: 48px;
        left: 50%;
        transform: translateX(-50%);
        font-size: 0.85rem;
        color: #888;
        white-space: nowrap;
      }
      .section-title h2, .section-title p {
        color: #222;
      }
      .subscription-card {
        background: #fff;
      }
      .btn-primary, .btn-success, .btn-outline-secondary, .btn-subscribe {
        background-color: #388da8 !important;
        border-color: #388da8 !important;
        color: #fff !important;
      }
      .btn-primary:hover, .btn-success:hover, .btn-outline-secondary:hover, .btn-subscribe:hover {
        background-color: #2c6c80 !important;
        border-color: #2c6c80 !important;
        color: #fff !important;
      }
    </style>

    <!-- Terms of Service Modal -->
    <div class="modal fade" id="termsModal" tabindex="-1" aria-labelledby="termsModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="termsModalLabel">Terms of Service</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body" style="max-height:60vh; overflow-y:auto;">
            <p><strong>Placeholder Terms of Service:</strong></p>
            <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Etiam euismod, urna eu tincidunt consectetur, nisi nisl aliquam enim, nec dictum nisi nisl eget sapien. Suspendisse potenti. Etiam euismod, urna eu tincidunt consectetur, nisi nisl aliquam enim, nec dictum nisi nisl eget sapien.</p>
            <ul>
              <li>Use of this service is subject to these terms.</li>
              <li>You agree not to misuse the service.</li>
              <li>We reserve the right to update these terms at any time.</li>
            </ul>
            <p>For the full terms, please contact support.</p>
          </div>
        </div>
      </div>
    </div>

    <!-- Privacy Policy Modal -->
    <div class="modal fade" id="privacyModal" tabindex="-1" aria-labelledby="privacyModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="privacyModalLabel">Privacy Policy</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body" style="max-height:60vh; overflow-y:auto;">
            <p><strong>Placeholder Privacy Policy:</strong></p>
            <p>Your privacy is important to us. We collect and use personal data only as needed to deliver our services. We do not sell your data to third parties.</p>
            <ul>
              <li>We collect information you provide directly to us.</li>
              <li>We use cookies to improve your experience.</li>
              <li>You can request deletion of your data at any time.</li>
            </ul>
            <p>For the full privacy policy, please contact support.</p>
          </div>
        </div>
      </div>
    </div>

    <!-- Bootstrap JS for modals (if not already included) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
  
