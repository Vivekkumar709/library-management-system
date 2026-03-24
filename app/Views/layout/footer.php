<footer id="footer" class="footer position-relative light-background">
    <!-- <div class="container footer-top">
      <div class="row gy-4">
        <div class="col-lg-4 col-md-6 footer-about">
          <a href="index.html" class="logo d-flex align-items-center">
            <span class="sitename">QuickStart</span>
          </a>
          <div class="footer-contact pt-3">
            <p>51/35 Shiv Hari Mandir Colony</p>
            <p>Sabun Godam, T.P Nager 250002</p>
            <p class="mt-3"><strong>Phone:</strong> <span>+91 7217 6432 38</span></p>
            <p><strong>Email:</strong> <span>info@smarteducationera.com</span></p>
          </div>
          <div class="social-links d-flex mt-4">
            <a href=""><i class="bi bi-play-btn-fill"></i></a>            
            <a href=""><i class="bi bi-facebook"></i></a>
            <a href=""><i class="bi bi-instagram"></i></a>
            <a href=""><i class="bi bi-linkedin"></i></a>
          </div>
        </div>

        <div class="col-lg-2 col-md-3 footer-links">
          <h4>Useful Links</h4>
          <ul>
            <li><a href="#">Home</a></li>
            <li><a href="#">About us</a></li>
            <li><a href="#">Services</a></li>
            <li><a href="#">Terms of service</a></li>
            <li><a href="#">Privacy policy</a></li>
          </ul>
        </div>

        <div class="col-lg-2 col-md-3 footer-links">
          <h4>Our Services</h4>
          <ul>
            <li><a href="#">Web Design</a></li>
            <li><a href="#">Web Development</a></li>
            <li><a href="#">Product Management</a></li>
            <li><a href="#">Marketing</a></li>
            <li><a href="#">Graphic Design</a></li>
          </ul>
        </div>

        <div class="col-lg-4 col-md-12 footer-newsletter">
          <h4>Our Newsletter</h4>
          <p>Subscribe to our newsletter and receive the latest news about our products and services!</p>
          <form action="forms/newsletter.php" method="post" class="php-email-form">
            <div class="newsletter-form"><input type="email" name="email"><input type="submit" value="Subscribe"></div>
            <div class="loading">Loading</div>
            <div class="error-message"></div>
            <div class="sent-message">Your subscription request has been sent. Thank you!</div>
          </form>
        </div>

      </div>
    </div> -->

    <?php if (! isset($_COOKIE['cookie_consent'])): ?>
<div id="cookie-banner" class="cookie-banner" role="dialog" aria-modal="true" aria-live="polite">
    <button type="button" id="cookie-close" class="cb-icon-btn" aria-label="Close">
        &times;
    </button>

    <div class="cb-content">
        <div class="cb-text-wrap">
            <h1 class="h6 mb-1">We use cookies</h1>
            <p class="cb-text mb-0">
                We use cookies to help this site function, remember your login, understand usage, and improve our services.
                You can change your choice at any time.
            </p>
        </div>

        <div class="cb-actions d-flex flex-wrap justify-content-end gap-2 mt-3 mt-md-0">
            <button type="button" id="cookie-manage" class="btn btn-outline-light btn-sm">
                Manage cookies
            </button>
            <button type="button" id="cookie-reject" class="btn btn-outline-light btn-sm">
                Reject non‑essential
            </button>
            <button type="button" id="cookie-accept" class="btn btn-primary btn-sm">
                Accept all
            </button>
        </div>
    </div>
</div>
<?php endif; ?>

    <div class="container copyright text-center mt-4">
      <p>©2026 <span>Copyright</span> <strong class="px-1 sitename">Smart Education ERA</strong><span>All Rights Reserved</span></p>
      <div class="credits">        
        Developed by <a href="https://www.motileinfotech.com">motileinfotech.com</a>        
      </div>
    </div>
  </footer>
  <a href="#" id="scroll-top" class="scroll-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>
  <div id="preloader"></div>
  <script src="<?= base_url('assets/vendor/bootstrap/js/bootstrap.bundle.min.js') ?>"></script>
  <script src="<?= base_url('assets/vendor/php-email-form/validate.js') ?>"></script>
  <script src="<?= base_url('assets/vendor/aos/aos.js') ?>"></script>
  <script src="<?= base_url('assets/vendor/glightbox/js/glightbox.min.js') ?>"></script>
  <script src="<?= base_url('assets/vendor/swiper/swiper-bundle.min.js') ?>"></script>
  <script src="<?= base_url('assets/js/main.js') ?>"></script>
  <script src="<?= base_url('assets/adminAssets/js/jquery1-3.4.1.min.js') ?>"></script>
  <script>
    $(document).ready(function() {
      // Target the school add/edit form by action or another unique selector if needed
      var schoolForm = $("form[action$='schools/save']");
      if (schoolForm.length) {
        schoolForm.on('submit', function(e) {
          var isValid = true;
          // Remove previous error highlights
          schoolForm.find('.is-invalid').removeClass('is-invalid');
          schoolForm.find('.invalid-feedback').remove();

          // Validate all required fields
          schoolForm.find('[required]').each(function() {
            if (!$(this).val() || $(this).val().trim() === '') {
              isValid = false;
              $(this).addClass('is-invalid');
              $(this).after('<div class="invalid-feedback">This field is required.</div>');
            }
          });

          // Validate all data-required selects (hidden selects for custom dropdowns)
          schoolForm.find('select[data-required="true"]').each(function() {
            if (!$(this).val() || $(this).val() === '' || $(this).val() === null) {
              isValid = false;
              // Try to show error on the custom dropdown if possible, else on the select
              var customDropdown = $(this).next('.nice-select');
              if (customDropdown.length) {
                customDropdown.addClass('is-invalid');
                if (customDropdown.find('.invalid-feedback').length === 0) {
                  customDropdown.after('<div class="invalid-feedback">This field is required.</div>');
                }
              } else {
                $(this).addClass('is-invalid');
                if ($(this).next('.invalid-feedback').length === 0) {
                  $(this).after('<div class="invalid-feedback">This field is required.</div>');
                }
              }
            }
          });

          if (!isValid) {
            e.preventDefault();
            // Scroll to first error
            var firstError = schoolForm.find('.is-invalid').first();
            if (firstError.length) {
              $('html, body').animate({
                scrollTop: firstError.offset().top - 100
              }, 500);
            }
          }
        });
      }
    });

    document.addEventListener('DOMContentLoaded', function () {
        const banner = document.getElementById('cookie-banner');
        if (!banner) return;

        function setConsent(value) {
            // 1 year
            document.cookie = "cookie_consent=" + value + "; path=/; max-age=" + (60 * 60 * 24 * 365);
            banner.remove();
        }

        const closeBtn  = document.getElementById('cookie-close');
        const acceptBtn = document.getElementById('cookie-accept');
        const rejectBtn = document.getElementById('cookie-reject');
        const manageBtn = document.getElementById('cookie-manage');

        if (closeBtn)  closeBtn.onclick  = function () { setConsent('deny'); };
        if (acceptBtn) acceptBtn.onclick = function () { setConsent('allow'); };
        if (rejectBtn) rejectBtn.onclick = function () { setConsent('deny'); };
        if (manageBtn) manageBtn.onclick = function () {
            banner.scrollIntoView({ behavior: 'smooth', block: 'center' });
        };
    });

  </script>

<script>document.addEventListener('DOMContentLoaded', function () {    const banner = document.getElementById('cookie-banner');    if (!banner) return;    document.getElementById('cookie-allow').onclick = function () {        document.cookie = "cookie_consent=allow; path=/; max-age=" + (60*60*24*365);        banner.remove();    };    document.getElementById('cookie-deny').onclick = function () {        document.cookie = "cookie_consent=deny; path=/; max-age=" + (60*60*24*365);        banner.remove();    };});</script>
  