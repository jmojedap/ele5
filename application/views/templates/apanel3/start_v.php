<?php
    //Logo
        $att_logo = array(
            'src' => URL_IMG . 'admin/logo_med.png',
            'id' => 'logo'
        );

    //Evitar errores de definición de variables e índices de arrays, 2013-12-07
        ini_set('display_errors', 1);
        ini_set('error_reporting', E_ERROR);
        
    //Margin
        $margin_top_links = '35px';
        if ( $this->uri->segment(2) == 'restaurar'  ) { $margin_top_links = '10px'; }
?>
        
<!DOCTYPE html>
<html>
    <head>
        <?php $this->load->view('templates/apanel3/parts/head'); ?>
        <style>
            div.login{
                max-width: 304px;
                margin: 0 auto;
            }
            
            @media(max-width:480px){
                .container { background-image: none; }
            }
        </style>
    </head>
    <body>
        <div class="container">
            <div class="row">
                <div class="col-md-4 col-md-offset-4 col-sm-6 col-sm-offset-3">
                    <div style="padding-top: 80px" class="hidden-xs-down"></div>
                    <div style="text-align: center; padding-bottom: 20px;">
                        <?= img($att_logo) ?>
                    </div>
                </div>
            </div>
            
            <?php $this->load->view($vista_a); ?>
            
            <div class="row" style="margin-top: <?php echo $margin_top_links ?>;">
                <div class="col-md-12">
                    <div class="text-center">
                        <a href="<?php echo base_url('app/login') ?>">
                            Ingresar
                        </a>
                        <span class="suave"> | </span>
                        
                        <a href="<?php echo base_url('usuarios/restaurar') ?>">
                            Olvidé mi contraseña
                        </a>
                    </div>
                </div>
            </div>
        </div>
        
        
    </body>
</html>


    
