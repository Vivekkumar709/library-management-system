<!DOCTYPE html>
<html lang="zxx">


<!-- Mirrored from demo.dashboardpack.com/user-management-html/ by HTTrack Website Copier/3.x [XR&CO'2014], Tue, 27 May 2025 10:01:51 GMT -->
<head>
    <!-- Required meta tags -->
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <title><?php echo $title;?></title>
    <link rel="icon" href="<?= base_url('images/favicon.png') ?>" type="image/png">
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="<?= base_url('assets/adminAssets/css/bootstrap1.min.css') ?>" />
    <!-- themefy CSS -->
    <link rel="stylesheet" href="<?= base_url('assets/adminAssets/vendors/themefy_icon/themify-icons.css') ?>" />
    <!-- select2 CSS -->
    <link rel="stylesheet" href="<?= base_url('assets/adminAssets/vendors/niceselect/css/nice-select.css') ?>" />
    <!-- owl carousel CSS -->
    <link rel="stylesheet" href="<?= base_url('assets/adminAssets/vendors/owl_carousel/css/owl.carousel.css') ?>" />
    <!-- gijgo css -->
    <link rel="stylesheet" href="<?= base_url('assets/adminAssets/vendors/gijgo/gijgo.min.css') ?>" />
    <!-- font awesome CSS -->
    <link rel="stylesheet" href="<?= base_url('assets/adminAssets/vendors/font_awesome/css/all.min.css') ?>" />
    <link rel="stylesheet" href="<?= base_url('assets/adminAssets/vendors/tagsinput/tagsinput.css') ?>" />

    <!-- date picker -->
<?php if ((!empty($content_data['data']['loadDatePicker']) && $content_data['data']['loadDatePicker'] === true)  || (service('uri')->getSegment(1) === 'dashboard')): ?>
    <link rel="stylesheet" href="<?= base_url('assets/adminAssets/vendors/datepicker/date-picker.css') ?>" />
    <link rel="stylesheet" href="<?= base_url('assets/adminAssets/vendors/vectormap-home/vectormap-2.0.2.css') ?>" />  
<?php endif; ?>    
    <!-- scrollabe  -->
    <link rel="stylesheet" href="<?= base_url('assets/adminAssets/vendors/scroll/scrollable.css') ?>" />
    <!-- datatable CSS -->
<?php if ((!empty($content_data['data']['loadResponsiveTable']) && $content_data['data']['loadResponsiveTable'] === true) || (service('uri')->getSegment(1) === 'dashboard')): ?>
    <link rel="stylesheet" href="<?= base_url('assets/adminAssets/vendors/datatable/css/jquery.dataTables.min.css') ?>" />
    <link rel="stylesheet" href="<?= base_url('assets/adminAssets/vendors/datatable/css/responsive.dataTables.min.css') ?>" />
    <link rel="stylesheet" href="<?= base_url('assets/adminAssets/vendors/datatable/css/buttons.dataTables.min.css') ?>" />
    <link rel="stylesheet" href="<?= base_url('assets/adminAssets/css/CustomDataTable.css') ?>" />
<?php endif; ?> 
    <!-- text editor css -->
    <!-- <link rel="stylesheet" href="<?= base_url('assets/adminAssets/vendors/text_editor/summernote-bs4.css') ?>" /> -->
    <!-- morris css -->
    <link rel="stylesheet" href="<?= base_url('assets/adminAssets/vendors/morris/morris.css') ?>">
    <!-- metarial icon css -->
    <link rel="stylesheet" href="<?= base_url('assets/adminAssets/vendors/material_icon/material-icons.css') ?>" />
    <!-- menu css  -->
    <link rel="stylesheet" href="<?= base_url('assets/adminAssets/css/metisMenu.css') ?>">
    <!-- style CSS -->
    <link rel="stylesheet" href="<?= base_url('assets/adminAssets/css/style1.css') ?>" />
    <link rel="stylesheet" href="<?= base_url('assets/adminAssets/css/colors/default.css') ?>" id="colorSkinCSS">
    <style>
    @keyframes shine {
        0% { transform: rotate(45deg) translateX(-100%); }
        100% { transform: rotate(45deg) translateX(100%); }
    }
    .clock-container:hover, .calendar-section:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
        transition: all 0.3s ease;
    }
    </style>
</head>