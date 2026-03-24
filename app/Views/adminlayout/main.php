<?php echo view('adminlayout/header'); ?>
<?php echo view('adminlayout/sideMenu'); ?>
<section class="main_content dashboard_part large_header_bg">
    <!-- menu  -->
    <?php echo view('adminlayout/topMenu'); ?>
    <!--/ menu  -->
    <div class="main_content_iner overly_inner ">
        <?php if (service('uri')->getSegment(1) != 'dashboard'): ?>   
            
            <div class="row">
    <div class="col-12">
        <div class="page_title_box d-flex flex-wrap align-items-center justify-content-between">
            <div class="page_title_left d-flex align-items-center">
                <!-- <h3 class="f_s_25 f_w_700 dark_text mr_30">Dashboard</h3> -->
                <ol class="breadcrumb page_bradcam mb-0">
                <li class="breadcrumb-item">
                    <a href="<?= site_url('dashboard') ?>">Dashboard</a>
                </li>
                    <?php 
                    if (!empty($thumbnails)): ?>
                        <?php foreach ($thumbnails as $breadcrumb): ?> 
                            <li class="breadcrumb-item <?= isset($breadcrumb['active']) && $breadcrumb['active'] ? 'active' : '' ?>">
                                <?php if (isset($breadcrumb['url']) && $breadcrumb['url'] && !isset($breadcrumb['active'])): ?>
                                    <a href="<?= $breadcrumb['url'] ?>"><?= $breadcrumb['title'] ?></a>
                                <?php else: ?>
                                    <?= $breadcrumb['title'] ?>
                                <?php endif; ?>
                            </li>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </ol>
            </div>
            <!-- <div class="page_title_right">
                <div class="page_date_button d-flex align-items-center"> 
                    <img src="<?= base_url('assets/adminAssets/img/icon/calender_icon.svg') ?>" alt="">
                    August 1, 2020 - August 31, 2020
                     <?=date('F j, Y');?>
                </div>
            </div> -->                    
            <div class="page_title_right">
                <div class="page_date_button d-flex align-items-center"> 
                    <!-- Professional Clock Design -->
                    <div class="live-clock-wrapper me-4">
                        <div class="clock-container" style="
                            background: linear-gradient(135deg, #02436d 0%, #0369a1 100%);
                            padding: 12px 20px;
                            border-radius: 10px;
                            box-shadow: 0 3px 10px rgba(2, 67, 109, 0.2);
                            border: 1px solid rgba(255, 255, 255, 0.1);
                            min-width: 130px;
                            text-align: center;
                            position: relative;
                            overflow: hidden;
                        ">
                            <!-- Animated background element -->
                            <div style="
                                position: absolute;
                                top: -50%;
                                right: -50%;
                                width: 100%;
                                height: 200%;
                                background: linear-gradient(45deg, transparent, rgba(255,255,255,0.1), transparent);
                                transform: rotate(45deg);
                                animation: shine 3s infinite;
                            "></div>
                            
                            <div class="clock-time" id="current-time" style="
                                font-size: 15px;
                                font-weight: 700;
                                color: white;
                                font-family: 'Segoe UI', system-ui, sans-serif;
                                letter-spacing: 1.5px;
                                position: relative;
                                z-index: 2;
                            "></div>
                            <!-- <div class="clock-label" style="
                                font-size: 9px;
                                color: rgba(255, 255, 255, 0.8);
                                font-weight: 600;
                                margin-top: 4px;
                                text-transform: uppercase;
                                letter-spacing: 1px;
                                position: relative;
                                z-index: 2;
                            ">CURRENT TIME</div> -->
                        </div>
                    </div>
                    
                    <!-- Calendar Section -->
                    <div class="calendar-section d-flex align-items-center" style="
                        background: linear-gradient(135deg, #059669 0%,rgba(31, 214, 153, 1) 100%);
                        padding: 12px 18px;
                        border-radius: 10px;
                        box-shadow: 0 3px 10px rgba(5, 150, 105, 0.2);
                        border: 1px solid rgba(255, 255, 255, 0.1);
                        min-width: 150px;
                        position: relative;
                        overflow: hidden;
                    ">
                        <!-- Animated background element -->
                        <div style="
                            position: absolute;
                            top: -50%;
                            left: -50%;
                            width: 100%;
                            height: 200%;
                            background: linear-gradient(45deg, transparent, rgba(255,255,255,0.1), transparent);
                            transform: rotate(45deg);
                            animation: shine 3s infinite;
                            animation-delay: 1.5s;
                        "></div>
                        
                        <img src="http://localhost:8080/assets/adminAssets/img/icon/calender_icon.svg" alt="" 
                            style="filter: brightness(0) invert(1); margin-right: 10px; position: relative; z-index: 2;">
                        <span style="font-size: 13px; font-weight: 600; color: white; position: relative; z-index: 2;">
                            <?=date('M j, Y');?>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
            <?php endif; ?>
            <script src="<?= base_url('assets/adminAssets/js/jquery1-3.4.1.min.js') ?>"></script>
        <?php echo view($content_view); ?>        
    </div>
<!--begin::Footer-->
<?php echo view('adminlayout/footer'); ?>
<!--end::Footer-->    


