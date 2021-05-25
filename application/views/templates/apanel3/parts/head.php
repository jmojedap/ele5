<title><?= $head_title ?></title>
        <link rel="shortcut icon" href="<?= URL_IMG ?>admin/icono.png" type="image/ico" />
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width">
        
        <!-- Bootstrap-->
        <?php $this->load->view('head_includes/bootstrap4') ?>

        <script type="text/javascript" src="<?= URL_RESOURCES ?>templates/apanel3/actions.js"></script>
        
        <link rel="stylesheet" href='https://fonts.googleapis.com/css?family=Ubuntu:500,300'>
        
        <!-- Font Awesome CSS -->
        <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.1.0/css/all.css" integrity="sha384-lKuwvrZot6UHsBSfcMvOkWwlCMgc0TaWr+30HWe3a4ltaBwTZhyTEggF5tJv8tbt" crossorigin="anonymous">

        <link type="text/css" rel="stylesheet" href="<?= URL_RESOURCES ?>templates/apanel3/style.css">
        <link type="text/css" rel="stylesheet" href="<?= URL_RESOURCES ?>templates/apanel3/style_add_01.css">
        <link type="text/css" rel="stylesheet" href="<?= URL_RESOURCES ?>css/ele_20200910.css">
        <link type="text/css" rel="stylesheet" href="<?= URL_RESOURCES ?>css/pel.css">
        <link type="text/css" rel="stylesheet" href="<?= URL_RESOURCES . 'css/abc_checkbox.css' ?>">

        <!-- Animate CSS -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/3.7.2/animate.css" integrity="sha256-a2tobsqlbgLsWs7ZVUGgP5IvWZsx8bTNQpzsqCSm5mk=" crossorigin="anonymous" />

        <!-- Vue.js -->
        <?php $this->load->view('assets/vue') ?>

        <!-- Alerts Toastr -->
        <?php $this->load->view('assets/toastr') ?>

        <!-- Moment.js -->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.24.0/moment.js" integrity="sha256-H9jAz//QLkDOy/nzE9G4aYijQtkLt9FvGmdUTwBk6gs=" crossorigin="anonymous"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.24.0/locale/es.js" integrity="sha256-bETP3ndSBCorObibq37vsT+l/vwScuAc9LRJIQyb068=" crossorigin="anonymous"></script>

        <!-- Google Analytics -->
        <?php $this->load->view('head_includes/google_analytics'); ?>

        <!-- PML Tools -->
        <link type="text/css" rel="stylesheet" href="<?= URL_RESOURCES . 'css/style_pml.css' ?>">
        <script type="text/javascript" src="<?= URL_RESOURCES . 'js/pcrn_en.js' ?>"></script>
        <script>
                const app_url = '<?= base_url() ?>'; const url_app = '<?= URL_API ?>'; const url_api = '<?= URL_API ?>';
        </script>