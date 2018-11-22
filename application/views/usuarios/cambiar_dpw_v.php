<?php

    //Logo
        $att_logo = array(
            'src' => URL_IMG . 'admin/logo_med.png',
            'id' => 'logo'
        );
    
    //Elementos formulario
    $att_password = array(
        'name'  =>  'password',
        'id'    =>  'password',
        'class' =>  'form-control',
        'required' => 'required',
        'placeholder' =>   'nueva contraseña',
        'autofocus' =>  TRUE,
        'pattern' => '.{8,}',
        'title' => 'Debe tener al menos 8 caractéres'
    );
    
    $att_passconf = array(
        'name'  =>  'passconf',
        'id'    =>  'passconf',
        'class' =>  'form-control',
        'required' => 'required',
        'placeholder' =>   'confirme su nueva contrase&ntilde;a',
        'minlength' => 8
    );
    
    $submit = array(
        'id' => 'submit_form',
        'value' =>  'Guardar contraseña',
        'class' => 'btn btn-primary btn-block'
        
    );
    
?>

<style>
    div.login{
        max-width: 274px;
        margin: 0 auto;
    }
</style>

<div class="row" style="padding-top: 15px">
    <div class="col-md-12 text-center">
        <img width="120px" class="" src="<?php echo base_url()?>media/images/admin/logo_enlinea.png" />
    </div>
</div>

<div class="row">
    <div class="col-md-4 col-md-offset-4 col-sm-6 col-sm-offset-3">

        <div style="text-align: center; padding-top: 30px; padding-bottom: 30px;">
            <?= img($att_logo) ?>
        </div>

        <div class="login" class="text-center">
            
            <h3 class="text-center"><?= $this->session->userdata('nombre_completo'); ?></h3>
            
            <div class="alert alert-info text-center">
                <i class="fa fa-lock fa-2x"></i>
                <p>  
                    Usted tiene actualmente la contraseña por defecto. Para continuar debe cambiarla.
                </p>
            </div>
            

            <?= form_open($destino_form); ?>

                <div class="form-group">
                    <?= form_password($att_password) ?>
                </div>
                <div class="form-group">
                    <?= form_password($att_passconf) ?>
                </div>

                <div class="">
                    <?= form_submit($submit) ?>    
                </div>
            
                <div class="sep2">
                    <?= anchor('app/logout', 'Cancelar', 'class="btn btn-block btn-warning" title="Cancelar"') ?>
                </div>

            <?= form_close(); ?>

            <div class="clearfix"></div>

            <div class="sep2">
                <?php $this->load->view('comunes/resultado_proceso_v'); ?>
            </div>
            
        </div>
    </div>
</div>

