<section id="contact" class="contact section">

      <div class="container section-title" data-aos="fade-up">
        <h2>Contact</h2>
        <p>Necessitatibus eius consequatur ex aliquid fuga eum quidem sint consectetur velit</p>
      </div>

      <div class="container" data-aos="fade-up" data-aos-delay="100">

        <div class="row gy-4">

          <div class="col-lg-6">
            <div class="info-item d-flex flex-column justify-content-center align-items-center" data-aos="fade-up" data-aos-delay="200">
              <i class="bi bi-geo-alt"></i>
              <h3>Address</h3>
              <p>51/35 Shiv Hari Mandir Colony, Sabun Godam, T.P Nager 250002</p>
            </div>
          </div>

          <div class="col-lg-3 col-md-6">
            <div class="info-item d-flex flex-column justify-content-center align-items-center" data-aos="fade-up" data-aos-delay="300">
              <i class="bi bi-telephone"></i>
              <h3>Call Us</h3>
              <p>+91 7217 6432 38</p>
            </div>
          </div>

          <div class="col-lg-3 col-md-6">
            <div class="info-item d-flex flex-column justify-content-center align-items-center" data-aos="fade-up" data-aos-delay="400">
              <i class="bi bi-envelope"></i>
              <h3>Email Us</h3>
              <p>info@smarteducationera.com</p>
            </div>
          </div>
        </div>

        <div class="row gy-4 mt-1">
          <div class="col-lg-6" data-aos="fade-up" data-aos-delay="300">
            <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d55852.20248072328!2d77.59764790534972!3d28.96476344412508!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x390c643dee1a07af%3A0xd6cf5b8ff486eeea!2sRahul%20Paints!5e0!3m2!1sen!2sin!4v1745320793021!5m2!1sen!2sin" frameborder="0" style="border:0; width: 100%; height: 400px;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
            <!-- <iframe src="https://www.google.com/maps/embed?pb=!1m14!1m8!1m3!1d48389.78314118045!2d-74.006138!3d40.710059!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x89c25a22a3bda30d%3A0xb89d1fe6bc499443!2sDowntown%20Conference%20Center!5e0!3m2!1sen!2sus!4v1676961268712!5m2!1sen!2sus" frameborder="0" style="border:0; width: 100%; height: 400px;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe> -->
          </div>
          <div class="col-lg-6">
          <?= form_open('', ['id' => 'contactForm', 'class' => 'php-email-form']) ?>
          <input type="hidden" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>">
            <div class="row gy-4">
                <div class="col-md-6">
                    <input type="text" name="name" class="form-control" placeholder="Your Name" required>
                    <div class="invalid-feedback" id="name-error"></div>
                </div>

                <div class="col-md-6">
                    <input type="email" name="email" class="form-control" placeholder="Your Email" required>
                    <div class="invalid-feedback" id="email-error"></div>
                </div>

                <div class="col-md-12">
                    <input type="text" name="subject" class="form-control" placeholder="Subject" required>
                    <div class="invalid-feedback" id="subject-error"></div>
                </div>

                <div class="col-md-12">
                    <textarea name="message" class="form-control" rows="6" placeholder="Message" required></textarea>
                    <div class="invalid-feedback" id="message-error"></div>
                </div>

                <div class="col-md-12 text-center">
                    <div class="loading">Loading</div>
                    <div class="error-message"></div>
                    <div class="sent-message"></div>
                    <button type="submit" class="btn btn-primary">Send Message</button>
                </div>
            </div>
        <?= form_close() ?>
          </div>
        </div>
      </div>
    </section>