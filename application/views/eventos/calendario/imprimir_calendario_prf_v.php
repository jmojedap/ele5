<?php $this->load->view('assets/fullcalendar'); ?>

<?php
    
    //Selección de grupos
        $str_grupos = '0';
        $arr_grupos = $this->session->userdata('arr_grupos');
        if ( count($arr_grupos) > 0 ) { $str_grupos = implode(',', $arr_grupos); }
        $condicion_grupos = 'grupo.id IN (' . $str_grupos . ')';
        $opciones_grupo = $this->App_model->opciones_grupo($condicion_grupos);
        
    //
        $colores_evento = $this->App_model->arr_item(13, 'color');
        
    //Nombres filtros
        $texto_grupo = 'Todos';
        if ( strlen($busqueda['g']) > 0 ) { $texto_grupo = $this->App_model->nombre_grupo($busqueda['g']); } 
        
        $texto_area = 'Todas';
        if ( strlen($busqueda['a']) > 0 ) { $texto_area = $this->App_model->nombre_item($busqueda['a']); } 
        
        $texto_actividad = 'Todas';
        if ( strlen($busqueda['tp']) > 0 ) { $texto_actividad = $this->Item_model->nombre(13, $busqueda['tp']); } 
        
?>

<script>
    
// VARIABLES
//---------------------------------------------------------------------------------------------------------
    var base_url = '<?= base_url(); ?>';
    var controlador = 'eventos';
    var evento_id = 0;
    var url = '';
    var grupo_id = 0;
    var fecha_inicio = '<?= date('Y-m-d') ?>';

// DOCUMENT
//---------------------------------------------------------------------------------------------------------

    $(document).ready(function()
    {
        $('#calendar').fullCalendar({
            header: {
                left: 'prev,next today',
                center: 'title',
                right: 'month'
            },
            defaultDate: '<?= $mes . '-01' ?>',
            firstDay: 0,
            editable: false,
            eventLimit: true, // allow "more" link when too many events
            events: [
                //Eventos, programación de cuestionarios
                <?php foreach ($eventos[1]->result() as $row_evento) : ?>
                    <?php
                        $url = base_url() . "cuestionarios/grupos/{$row_evento->referente_2_id}/{$row_evento->institucion_id}/{$row_evento->grupo_id}";
                        $nombre_cuestionario = $this->App_model->nombre_cuestionario($row_evento->referente_2_id);
                        $color = $colores_evento[1];
                    ?>
                    {
                        id: <?= $row_evento->referente_2_id ?>,
                        title: 'Cuestionario: <?= $nombre_cuestionario ?>',
                        start: '<?= $row_evento->fecha_inicio ?>',
                        end: '<?= $this->Pcrn->suma_fecha($row_evento->fecha_fin) ?>',
                        //url: '<?= $url ?>',
                        color : '<?= $color ?>'
                    },
                <?php endforeach ?>
                
                //Eventos, programación de temas
                <?php foreach ($eventos[2]->result() as $row_evento) : ?>
                    <?php
                        $color = $colores_evento[2];
                    ?>
                    {
                        id: <?= $row_evento->id ?>,
                        title: 'T: <?= $row_evento->nombre_evento ?>',
                        start: '<?= $row_evento->fecha_inicio ?>',
                        end: '<?= $row_evento->fecha_inicio ?>',
                        //url: base_url + 'flipbooks/leer/' + <?= $row_evento->referente_2_id ?> + '/' + <?= $row_evento->entero_1 ?>,
                        color : '<?= $color ?>'
                    },
                <?php endforeach ?>
                
                //Eventos, programación de links
                <?php foreach ($eventos[4]->result() as $row_evento) : ?>
                    <?php
                        $color = $colores_evento[4];
                    ?>
                    {
                        id: <?= $row_evento->id ?>,
                        title: '>> <?= $this->Pcrn->texto_url($row_evento->url) ?>',
                        start: '<?= $row_evento->fecha_inicio ?>',
                        end: '<?= $row_evento->fecha_inicio ?>',
                        //url: 'link_externo',
                        color : '<?= $color ?>'
                    },
                <?php endforeach ?>
            ],
            eventClick: function(event) {
                if (event.url === 'link_externo') {
                    evento_id = event.id;
                    cargar();
                    return false;
                } else {
                    window.open(event.url, "_blank");
                    return false;
                }
            },
            dayClick: function(date, jsEvent, view) {
                
                $('.fc-day').css('background-color', '#ffffff');
                $(this).css('background-color', '#fcf8e3');
                $('#campo_url').focus();
                $('#campo_url').val('');
                $('#campo_grupo_id').val('');
                $('#campo_fecha_inicio').val(date.format());
                $('#eliminar_link').addClass('hidden');
                $('.evento_link').addClass('hidden');
                
            }
        });
    });

</script>

<table class="table table-default bg-blanco">
    <tbody>
        <tr>
            <td width="33%">
                Grupo:
                <strong>
                    <?= $texto_grupo ?>    
                </strong>
            </td>
            <td width="33%">
                Área:
                <strong>
                    <?= $texto_area ?>    
                </strong>
            </td>
            <td>
                Actividad:
                <strong>
                    <?= $texto_actividad ?>    
                </strong>
            </td>
        </tr>
    </tbody>
</table>

<div class="row">
    <div class="col-md-12">
        <div class="panel panel-default">
            <div class="panel-body">
                <div id='calendar'></div>
            </div>
        </div>
    </div>
</div>

<div class="panel panel-default">
    <div class="panel-body">
        <p>
            <span class="suave">Usuario: </span>
            <strong><?= $this->session->userdata('nombre_completo') ?>    </strong>
            <span class="suave"> | </span>

            <span class="suave">Institución: </span>
            <strong> <?= $this->App_model->nombre_institucion($this->session->userdata('institucion_id')) ?>     </strong>
            <span class="suave"> | </span>
            
            <span class="suave">Fecha: </span>
            <strong> <?= date('Y-m-d') ?>     </strong>
            <span class="suave"> | </span>
        </p>
    </div>
</div>

    