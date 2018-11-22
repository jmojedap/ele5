<?php $this->load->view('assets/icheck'); ?>

<?php

    //Variables para construcción del formulario
        $att_form = array(
            'class' =>  'form-inline'
        );

        $att_submit = array(
            'id' =>  'boton-submit',
            'value' =>  'Crear',
            'class' => 'btn btn-primary'
        );

        $att_check_todos = array(
            'name' => 'check_todos',
            'id'    => 'check_todos',
            'checked' => FALSE
        );
        
        $att_check = array(
            'class' =>  'check_registro cb_tema',
            'checked' => FALSE,
            'value' => 1
        );
        
        $att_nombre_cuestionario = array(
            'name' =>   'nombre_cuestionario',
            'id'    =>  'nombre_cuestionario',
            'class' =>  'form-control',
            'placeholder'  =>  'Nombre cuestionario',
            'value'  =>  'Cuest. ' . date('Ymd'),
            'required' =>   TRUE,
            'autofocus' =>   TRUE
        );

?>

<script>
    
// VARIABLES
//-----------------------------------------------------------------------------

    var cantcb = 0; //Cantidad de temas activos, CheckBox

// DOCUMENT READY
//-----------------------------------------------------------------------------
    
    $(document).ready(function(){
        
        $('#boton-submit').hide();
        
        $('#check_todos').on('ifChanged', function(){
            
            if($(this).is(":checked")) { 
                //Activado
                $('.check_registro').iCheck('check');
            } else {
                //Desactivado
                $('.check_registro').iCheck('uncheck');
            }
        });
        
        /**
         * Al cambiar una casilla, se recalcula el número de temas elegidos
         */
        $('.cb_tema').on('ifChanged', function(){
            if($(this).is(":checked")) { 
                //Activado
                cantcb++;
            } else {
                //Desactivado
                cantcb--;
            }
            
            alt_submit();
        });
    });
    
// Funciones
//-----------------------------------------------------------------------------

    /**
     * Ocultar el botón submit si no hay temas seleccionados
     * Mostrar 
     * 
     * @returns {undefined}
     */
    function alt_submit()
    {
        if ( cantcb > 0 ) {
            $('#boton-submit').show();
        } else {
            $('#boton-submit').hide();
        }
    }
</script>

<?= form_open($destino_form, $att_form) ?>

    <div class="sep1">
        <?= form_input($att_nombre_cuestionario) ?>
        <?= form_submit($att_submit) ?>
    </div>

    <div class="bs-caja-no-padding">
        <table class="table table-default" cellspacing="0">
            <thead>
                <tr class="tr1">
                    <th width="10px"><?= form_checkbox($att_check_todos) ?></th>
                    <th width="10px">Evaluado</th>
                    <th>Tema</th>
                    <th>Preguntas</th>

                    <th width="100px">Cód. tema</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($temas->result() as $row_tema){ ?>
                    <?php
                        $preguntas = $this->Tema_model->preguntas($row_tema->id);
                        $clase = 'neutro';
                        if ( $preguntas->num_rows() ) {  $clase = 'informacion'; }

                        //Evaluado
                            $tema_evaluado = $this->Cuestionario_model->tema_evaluado($row_tema->id);
                            $clase_evaluado = $this->Pcrn->si_cero($tema_evaluado, 'danger', 'info');
                            $texto_evaluado = $this->Pcrn->si_cero($tema_evaluado, 'No', 'Sí');
                            $icono_evaluado = $this->Pcrn->si_cero($tema_evaluado, 'fa-times', 'fa-check');

                        //Checkbox
                            $att_check['name'] = $row_tema->id;
                    ?>
                    <tr>
                        <td><?= form_checkbox($att_check) ?></td>
                        <td class="text-center <?= $clase_evaluado ?>"><i class="fa <?= $icono_evaluado ?>"></i></td>
                        <td><?= $row_tema->nombre_tema ?></td>
                        <td><span class="w1 etiqueta <?= $clase ?>"><?= $preguntas->num_rows(); ?></span></td>

                        <td><?= $row_tema->cod_tema ?></td>
                    </tr>

                <?php } //foreach ?>
            </tbody>
        </table>      
    </div>

<?= form_close() ?>