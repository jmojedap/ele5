<title><?php echo $head_title ?></title>
        <link rel="shortcut icon" href="<?php echo URL_IMG ?>admin/icono.png" type="image/ico" />
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width">
        
        <!-- Bootstrap-->
        <?php $this->load->view('head_includes/bootstrap4') ?>

        <script type="text/javascript" src="<?php echo URL_RESOURCES ?>templates/apanel3/actions.js"></script>
        
        <link rel="stylesheet" href='https://fonts.googleapis.com/css?family=Ubuntu:500,300'>
        
        <!-- Font Awesome CSS -->
        <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.1.0/css/all.css" integrity="sha384-lKuwvrZot6UHsBSfcMvOkWwlCMgc0TaWr+30HWe3a4ltaBwTZhyTEggF5tJv8tbt" crossorigin="anonymous">

        <link type="text/css" rel="stylesheet" href="<?php echo URL_RESOURCES ?>templates/apanel3/style.css">
        <link type="text/css" rel="stylesheet" href="<?php echo URL_RESOURCES ?>templates/apanel3/style_add_01.css">
        <link type="text/css" rel="stylesheet" href="<?php echo URL_RESOURCES ?>css/pel.css">
        <link type="text/css" rel="stylesheet" href="<?php echo URL_RESOURCES . 'css/abc_checkbox.css' ?>">

        <!-- Animate CSS -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/3.7.2/animate.css" integrity="sha256-a2tobsqlbgLsWs7ZVUGgP5IvWZsx8bTNQpzsqCSm5mk=" crossorigin="anonymous" />

        <!-- Vue.js -->
        <?php $this->load->view('assets/vue') ?>

        <!-- Alerts Toastr -->
        <?php $this->load->view('assets/toastr') ?>

        <!-- Google Analytics -->
        <?php //$this->load->view('head_includes/google_analytics'); ?>