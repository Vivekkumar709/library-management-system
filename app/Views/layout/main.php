<!DOCTYPE html>
<html lang="en">
<!-- header -->
<?php echo view('layout/header'); ?>
<body class="index-page">
  <header id="header" class="header d-flex align-items-center fixed-top">
    <div class="container-fluid container-xl position-relative d-flex align-items-center">
      <a href="index.html" class="logo d-flex align-items-center me-auto">
        <img src="<?= base_url('images/smartEduEraLogo.png') ?>" alt="">
        <h1 class="sitename">Smart Education ERA</h1>
      </a>
      <!-- menu -->
      <?php echo view('layout/menu'); ?>
    </div>
  </header>

  <main class="main">   
  <?php
        // Render the actual content view
        if (!empty($content_view)) {
            echo view($content_view, $content_data);
        }
   ?>
  </main>

  <!-- footer -->
  <?php echo view('layout/footer'); ?>
</body>
</html>