<?php $this->load->view('assets/bootstrap_datepicker'); ?>

<?php
    $marca_programado = '<i class="fa fa-check text-success"></i>';    
?>

<script>
//VARIABLES
//---------------------------------------------------------------------------------------------------

    var base_url = '<?= base_url() ?>';
    var flipbook_id = '<?= $row->id ?>';
    var grupo_id = <?= $grupo_id ?>;
    var evento_id = 0;
    var num_pagina = 0;
    //var marca_programado = '<? $marca_programado ?>';
    
//DOCUMENT
//---------------------------------------------------------------------------------------------------
    
    $(document).ready(function()
    {
        $('.casilla_fecha_inicio').change(function()
        {
            tema_id = $(this).data('tema_id');
            num_pagina = $(this).data('num_pagina');
            fecha_inicio = $(this).val();
            programar_tema();
        });
        
        $('.desprogramar_tema').click(function()
        {
            evento_id = $(this).data('evento_id');
            tema_id = $(this).data('tema_id');
            desprogramar();
        });
    });
    
//FUNCIONES
//---------------------------------------------------------------------------------------------------
    
    //Ajax
    function programar_tema()
    {
        $.ajax({        
            type: 'POST',
            url: base_url + 'eventos/programar_tema',
            data: {
                flipbook_id : flipbook_id,
                tema_id : tema_id,
                num_pagina : num_pagina,
                grupo_id : grupo_id,
                fecha_inicio : fecha_inicio
            },
            success: function(){
                //alert(respuesta);
                programado();
            }
        });
    }
    
    //Ajax
    function desprogramar(){
        $.ajax({        
            type: 'POST',
            url: base_url + 'eventos/eliminar_seleccionados',
            data: {
                seleccionados : evento_id
            },
            success: function(){
                desprogramado();
            }
        });
    }
    
    function programado()
    {
        $('#fila_' + tema_id).addClass('table-info');
    }
    
    function desprogramado()
    {
        $('#fila_' + tema_id).removeClass('table-info');
        $('#tema_' + tema_id).val('');
    }
</script>

<?php if ( $grupos->num_rows() > 0 ){ ?>

    <div class="bs-caja-no-padding">
        <table class="table table-hover" cellspacing="0">
            <thead>
                <tr class="">
                    <th width="45px">ID</th>
                    <th width="100px">Cód. tema</th>
                    <th>Nombre tema</th>
                    
                    <th>Fecha</th>
                    <th width="45px"></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($temas->result() as $row_tema){ ?>
                <?php
                    //Verificar si ya fue programado
                        $claves['tipo_id'] = 2;
                        $claves['referente_id'] = $row_tema->id;
                        $claves['grupo_id'] = $grupo_id;
                        $row_evento = $this->Evento_model->row_evento($claves);

                        $fecha_programado = $row_evento->fecha_inicio;

                    //Texto programado
                        $texto_programado = '';
                        $clase_fila = '';
                        if ( ! is_null($row_evento) ) {
                            $clase_fila = 'table-info';
                            $texto_programado = '<i class="fa fa-check text-success"></i>';
                        }

                    //Atributos de la casilla fecha
                        $att_fecha['id'] = "tema_{$row_tema->id}";
                        $att_fecha['name'] = "fecha_inicio_{$row_tema->id}";
                        $att_fecha['value'] = $fecha_programado;
                        $att_fecha['data-tema_id'] = $row_tema->id;
                        $att_fecha['data-num_pagina'] = $row_tema->min_num_pagina;
                ?>
                    <tr>
                        <td><?= $row_tema->id ?></td>
                        <td><?= $row_tema->cod_tema ?></td>
                        <td><?= anchor("temas/archivos/$row_tema->id", $row_tema->nombre_tema) ?></td>
                        
                        <td class="<?= $clase_fila ?>" id="fila_<?= $row_tema->id ?>">
                            <input
                                id="tema_<?php echo $row_tema->id ?>"
                                name="fecha_inicio_<?php echo $row_tema->id ?>"
                                value="<?php echo $fecha_programado ?>"
                                data-tema_id="<?php echo $row_tema->id ?>"
                                data-num_pagina="<?php echo $row_tema->min_num_pagina ?>"
                                type="text"
                                title="Seleccione la fecha para programar el tema"
                                class="form-control casilla_fecha_inicio bs_datepicker"
                                >
                        </td>
                        <td>
                            <button class="btn btn-light desprogramar_tema" data-evento_id="<?= $row_evento->id ?>" data-tema_id="<?= $row_tema->id ?>">
                                <i class="fa fa-times"></i>
                            </button>
                        </td>
                    </tr>

                <?php } //foreach ?>
            </tbody>
        </table>      
    </div>
<?php } else { ?>
    <div class="alert alert-info">
        <i class="fa fa-info-circle"></i>
        No hay grupos para programar los temas de este Contenido.
    </div>
<?php } ?>
