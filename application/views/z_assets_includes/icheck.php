<?php $carpeta_icheck = URL_ASSETS . 'icheck/'; ?>

<link href="<?= $carpeta_icheck ?>skins/square/blue.css" rel="stylesheet">
<!--<script src="<?php //echo $carpeta_icheck ?>icheck.js"></script>-->
<script src="https://cdnjs.cloudflare.com/ajax/libs/iCheck/1.0.2/icheck.js"></script>

<script>
$(document).ready(function(){
  $('input').iCheck({
    checkboxClass: 'icheckbox_square-blue',
    radioClass: 'iradio_square-blue',
    increaseArea: '20%' // optional
  });
});
</script>
