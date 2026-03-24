<?php //auth()->user()->user_type_id
$groupUseFor = get_user_group_data(auth()->id(), 'use_for_name');
?>
<div class="container-fluid g-0">
        <div class="row">
            <div class="col-lg-12 p-0 ">
                <div class="header_iner d-flex justify-content-between align-items-center">
                    <div class="sidebar_icon d-lg-none">
                        <i class="ti-menu"></i>
                    </div>
                    <div class="line_icon open_miniSide d-none d-lg-block">
                        <img src="<?= base_url('assets/adminAssets/img/line_img.png') ?>" alt="">
                    </div>
                    <div class="serach_field-area d-flex align-items-center">
                        <div class="search_inner">
                            <form action="#">
                                <div class="search_field">
                                    <input type="text" placeholder="Search">
                                </div>
                                <button type="submit"> <img src="<?= base_url('assets/adminAssets/img/icon/icon_search.svg') ?>" alt=""> </button>
                            </form>
                        </div>
                    </div>
                    <div class="header_right d-flex justify-content-between align-items-center">
                        <div class="header_notification_warp d-flex align-items-center">                            
                            <li style="display: flex; align-items: center; gap: 8px;">
                                <span><h5 style="color:#02436d; margin: 0;">Dark Mode</h5></span>
                                <label class="lms_checkbox_1" for="dark_mode" style="margin-bottom: 0; margin-left: 4px;">
                                    <input type="checkbox" id="dark_mode"
                                        style="width: 18px; height: 18px; accent-color: #02436d; outline: 2px solid #800080; border: 2px solid #800080; margin: 0; vertical-align: middle;" />
                                    <div class="slider-check round" style="border: 1.5px solid #02436d;"></div>
                                </label>
                            </li>
                            <li>
                                <a class="bell_notification_clicker" href="#"> <img src="<?= base_url('assets/adminAssets/img/icon/bell.svg') ?>" alt="">
                                    <span>2</span>
                                </a>
                                <!-- Menu_NOtification_Wrap  -->
                            <div class="Menu_NOtification_Wrap">
                                <div class="notification_Header">
                                    <h4>Notifications</h4>
                                </div>
                                <div class="Notification_body">
                                    <!-- single_notify  -->
                                    <div class="single_notify d-flex align-items-center">
                                        <div class="notify_thumb">
                                            <a href="#"><img src="<?= base_url('assets/adminAssets/img/staf/2.png') ?>" alt=""></a>
                                        </div>
                                        <div class="notify_content">
                                            <a href="#"><h5>Cool Marketing </h5></a>
                                            <p>Lorem ipsum dolor sit amet</p>
                                        </div>
                                    </div>
                                    <!-- single_notify  -->
                                    <div class="single_notify d-flex align-items-center">
                                        <div class="notify_thumb">
                                            <a href="#"><img src="<?= base_url('assets/adminAssets/img/staf/4.png') ?>" alt=""></a>
                                        </div>
                                        <div class="notify_content">
                                            <a href="#"><h5>Awesome packages</h5></a>
                                            <p>Lorem ipsum dolor sit amet</p>
                                        </div>
                                    </div>
                                    <!-- single_notify  -->
                                    <div class="single_notify d-flex align-items-center">
                                        <div class="notify_thumb">
                                            <a href="#"><img src="<?= base_url('assets/adminAssets/img/staf/3.png') ?>" alt=""></a>
                                        </div>
                                        <div class="notify_content">
                                            <a href="#"><h5>what a packages</h5></a>
                                            <p>Lorem ipsum dolor sit amet</p>
                                        </div>
                                    </div>
                                    <!-- single_notify  -->
                                    <div class="single_notify d-flex align-items-center">
                                        <div class="notify_thumb">
                                            <a href="#"><img src="<?= base_url('assets/adminAssets/img/staf/2.png') ?>" alt=""></a>
                                        </div>
                                        <div class="notify_content">
                                            <a href="#"><h5>Cool Marketing </h5></a>
                                            <p>Lorem ipsum dolor sit amet</p>
                                        </div>
                                    </div>
                                    <!-- single_notify  -->
                                    <div class="single_notify d-flex align-items-center">
                                        <div class="notify_thumb">
                                            <a href="#"><img src="<?= base_url('assets/adminAssets/img/staf/4.png') ?>" alt=""></a>
                                        </div>
                                        <div class="notify_content">
                                            <a href="#"><h5>Awesome packages</h5></a>
                                            <p>Lorem ipsum dolor sit amet</p>
                                        </div>
                                    </div>
                                    <!-- single_notify  -->
                                    <div class="single_notify d-flex align-items-center">
                                        <div class="notify_thumb">
                                            <a href="#"><img src="<?= base_url('assets/adminAssets/img/staf/3.png') ?>" alt=""></a>
                                        </div>
                                        <div class="notify_content">
                                            <a href="#"><h5>what a packages</h5></a>
                                            <p>Lorem ipsum dolor sit amet</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="nofity_footer">
                                    <div class="submit_button text-center pt_20">
                                        <a href="#" class="btn_1">See More</a>
                                    </div>
                                </div>
                            </div>
                            <!--/ Menu_NOtification_Wrap  -->
                            </li>
                            <li>
                                <a class="CHATBOX_open" href="#"><img src="<?= base_url('assets/adminAssets/img/icon/msg.svg') ?>" alt=""> <span>2</span>  </a>
                            </li>

                        </div>
                        <div class="profile_info">
                            <img src="<?= esc(base_url(trim(auth()->user()->profile_image)))?>" alt="#">
                            <div class="profile_info_iner">
                                <div class="profile_author_name">
                                    <p><?= esc($LoggedInUserDesignation) ?> </p>
                                    <h5><?= esc(auth()->user()->full_name) ?></h5>
                                    <!-- <p>Username: (<?= esc(auth()->user()->username) ?>)</p> -->
                                    <p>UserType: <?= esc($LoggedInUserGroup) ?></p>
                                    <p>AppFor: <?= esc($groupUseFor);?></p>
                                    
                                </div>
                                <div class="profile_info_details">
                                    <a href="/profile">My Profile (<?= esc(auth()->user()->username) ?>)</a>
                                    <a href="/profile">Settings</a>
                                    <a href="/logout">Log Out </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>