<?php
    //Evitar errores de definición de variables e índices de arrays, 2013-12-07
        ini_set('display_errors', 1);
        ini_set('error_reporting', E_ERROR);
        
    //Margin
        $margin_top_links = '35px';
        if ( $this->uri->segment(2) == 'restaurar'  ) { $margin_top_links = '10px'; }

    //Controller Function
        $function_start = $this->uri->segment(2);
?>
        
<!DOCTYPE html>
<html>
    <head>
        <?php $this->load->view('templates/apanel3/parts/head'); ?>
        <style>
            div.start_content{
                max-width: 280px;
                margin: 0 auto;
            }
        </style>
    </head>
    <body>
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div style="padding-top: 50px" class="hidden-xs-down"></div>
                    <div style="text-align: center; padding-bottom: 20px;">
                        <img src="<?php echo URL_IMG . 'admin/logo_med.png' ?>" alt="Logo En Línea Editores" style="margin: auto">
                    </div>
                </div>
            </div>

            <div class="row mb-3 mt-3">
                <div class="col-md-12 text-center">    
                    <a href="<?php echo base_url('app/login') ?>" class="text-muted">
                        Iniciar sesión
                    </a>
                
                    <span class="text-secondary">|</span>
                
                    <a href="<?php echo base_url('usuarios/restaurar') ?>" class="text-muted">
                        Olvidé mi contraseña
                    </a>      
                </div>
            </div>
            
            <?php $this->load->view($view_a); ?>
            
            <div class="fixed-bottom text-center pb-2">
                <span style="color: #AAA">
                    © 2019 &middot; En Línea Editores &middot; Colombia
                </span>
            </div>


        </div>
        
        
    </body>
</html>


    
