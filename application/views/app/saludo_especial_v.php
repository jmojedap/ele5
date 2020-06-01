<style>
    .saludo_mini{
        border: 1px solid #ec407a;
        border-radius: 3px;
        -webkit-box-shadow: 5px 5px 5px 0px rgba(189,189,189,1);
        -moz-box-shadow: 5px 5px 5px 0px rgba(189,189,189,1);
        box-shadow: 5px 5px 5px 0px rgba(189,189,189,1);
    }
</style>

<script>
    $(document).ready(function(){
        $(function() {
            lightbox.start($('#saludo_especial'));
        });
    });
</script>

<?php $this->load->view('assets/lightbox2') ?>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.0.0/animate.min.css"/>
<div class="text-center mb-2">
    <a href="<?= URL_IMG ?>gallery/20200515_dia_profesor.png" data-lightbox="image-1" id="saludo_especial">
        <img alt="Saludo proferos" src="<?= URL_IMG ?>gallery/20200515_dia_profesor_sm.png" class="saludo_mini">
    </a>
</div>