<html>
    <head>
        <?php $this->load->view('plantilla_apanel/head'); ?>
    </head>



    <body>
        <div class="login">
            <div style="text-align: center;">
                <img src="<?= URL_IMG ?>/admin/logo_med.png" />
                <h4 role="alert" class="alert alert-warning"><i class="fa fa-info-circle"></i> Acceso no permitido</h4>
                <?= anchor('app', '<i class="fa fa-caret-left"></i> Volver', 'class="btn btn-info"') ?>
                <?= anchor('app/login', '<i class="fa fa-user"></i> Login de usuario', 'class="btn btn-default"') ?>
            </div>
            
        </div>
        
    </body>

</html>
