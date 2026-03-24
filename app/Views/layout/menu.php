<nav id="navmenu" class="navmenu">
        <ul>
          <li><a href="<?= base_url('/#hero') ?>" class="active">Home</a></li>
          <li><a href="<?= base_url('/#about') ?>">About</a></li>
          <li><a href="<?= base_url('/#features') ?>">Features</a></li>
          <li><a href="<?= base_url('/#services') ?>">Services</a></li>
          <li><a href="<?= base_url('/#pricing') ?>">Pricing</a></li>
          <li class="dropdown"><a href="<?= base_url('/') ?>"><span>Dropdown</span> <i class="bi bi-chevron-down toggle-dropdown"></i></a>
            <ul>
              <li><a href="<?= base_url('/') ?>">Dropdown 1</a></li>
              <li class="dropdown"><a href="<?= base_url('/') ?>"><span>Deep Dropdown</span> <i class="bi bi-chevron-down toggle-dropdown"></i></a>
                <ul>
                  <li><a href="#">Deep Dropdown 1</a></li>
                  <li><a href="#">Deep Dropdown 2</a></li>
                  <li><a href="#">Deep Dropdown 3</a></li>
                  <li><a href="#">Deep Dropdown 4</a></li>
                  <li><a href="#">Deep Dropdown 5</a></li>
                </ul>
              </li>
              <li><a href="<?= base_url('/') ?>">Dropdown 2</a></li>
              <li><a href="<?= base_url('/') ?>">Dropdown 3</a></li>
              <li><a href="<?= base_url('/') ?>">Dropdown 4</a></li>
            </ul>
          </li>
          <li><a href="<?= base_url('/#contact') ?>">Contact</a></li>
        </ul>
        <i class="mobile-nav-toggle d-xl-none bi bi-list"></i>
      </nav>
      <a class="btn-getstarted" href="/login" target="_blank">Login</a>