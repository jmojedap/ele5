<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">

<title><?= $head_title ?></title>
<link rel="shortcut icon" href="<?= URL_BRAND ?>favicon.png"> 

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>

<!-- Bootstrap CSS 5.1.3 -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">

<!-- Font Awesome -->
<!-- <script src="https://kit.fontawesome.com/f45fca298e.js" crossorigin="anonymous"></script> -->
<!-- <script src="<?= URL_RESOURCES ?>js/fa_f45fca298e.js"></script> -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/js/all.min.js" integrity="sha512-Tn2m0TIpgVyTzzvmxLNuqbSJH3JP8jm+Cy3hvHrW7ndTDcJ1w5mBiksqDBb8GpE2ksktFvDB/ykZ0mDpsZj20w==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>


<!-- Vue.js -->
<script src="https://unpkg.com/vue@3/dist/vue.global.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/axios/1.0.0-alpha.1/axios.min.js" integrity="sha512-xIPqqrfvUAc/Cspuj7Bq0UtHNo/5qkdyngx6Vwt+tmbvTLDszzXM0G6c91LXmGrRx8KEPulT+AfOOez+TeVylg==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script>const {createApp} = Vue;</script>

<!-- Alertas y notificaciones -->
<link href="<?= URL_RESOURCES ?>templates/easypml/toastr.css" rel="stylesheet" type="text/css" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js" integrity="sha512-VEd+nq25CkR676O+pLBnDW09R7VQX9Mdiij052gVCp5yVH3jGtH70Ho/UUv4mJDsEdTvqRCFZg0NKGiojGnUCw==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script src="<?= URL_RESOURCES ?>config/easypml/toastr-options.js"></script>

<!-- Moment.js -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.24.0/moment.js" integrity="sha256-H9jAz//QLkDOy/nzE9G4aYijQtkLt9FvGmdUTwBk6gs=" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.24.0/locale/es.js" integrity="sha256-bETP3ndSBCorObibq37vsT+l/vwScuAc9LRJIQyb068=" crossorigin="anonymous"></script>

<!-- PML Tools -->
<link type="text/css" rel="stylesheet" href="<?= URL_RESOURCES . 'css/pacarina.css' ?>">
<script src="<?= URL_RESOURCES . 'js/pcrn.js' ?>"></script>
<script>
    const URL_APP = '<?= URL_APP ?>'; const URL_API = '<?= URL_API ?>'; const URL_FRONT= '<?= URL_FRONT ?>';
    var app_cf = '<?= $this->uri->segment(2) . '/' . $this->uri->segment(3); ?>';

    <?php if ( $this->session->userdata('logged') ) : ?>    
        const APP_RID = '<?= $this->session->userdata('role') ?>';
        const APP_UID = '<?= $this->session->userdata('user_id') ?>';
    <?php else: ?>
        const APP_RID = 99;
        const APP_UID = 0;
    <?php endif; ?>
</script>
<script src="<?= URL_RESOURCES ?>js/bs5_routing.js"></script>

<!-- navbar elements -->
<script src="<?= URL_RESOURCES ?>config/easypml/menus/nav_1_elements_<?= $this->session->userdata('role') ?>.js"></script>

<link rel="stylesheet" href="<?= URL_RESOURCES ?>templates/easypml/style.css">
<link rel="stylesheet" href="<?= URL_RESOURCES ?>templates/easypml/theme-editores.css">
<link rel="stylesheet" href="<?= URL_RESOURCES ?>templates/easypml/app.css">
<link rel="stylesheet" href="<?= URL_RESOURCES ?>templates/easypml/mobile.css">