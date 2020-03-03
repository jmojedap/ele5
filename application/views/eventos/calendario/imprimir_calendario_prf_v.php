<?php $this->load->view('assets/fullcalendar4'); ?>

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
        var calendarEl = document.getElementById('calendar');

        var calendar = new FullCalendar.Calendar(calendarEl, {
            plugins: [ 'interaction', 'dayGrid', 'timeGrid', 'bootstrap' ],
            themeSystem: 'bootstrap',
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
                <?php foreach ($eventos[22]->result() as $row_evento) : ?>
                    <?php
                        $url = base_url("cuestionarios/grupos/{$row_evento->referente_id}/{$row_evento->institucion_id}/{$row_evento->grupo_id}");
                        $nombre_cuestionario = $this->Pcrn->campo_id('cuestionario', $row_evento->referente_id, 'nombre_cuestionario');
                        $color = $colores_evento[1];
                    ?>
                    {
                        id: <?php echo $row_evento->referente_id ?>,
                        title: "Cuestionario: <?php echo $nombre_cuestionario ?>",
                        start: '<?= $row_evento->fecha_inicio ?>',
                        end: '<?= $this->Pcrn->suma_fecha($row_evento->fecha_fin) ?>',
                        url: '<?= $url ?>',
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
                        //url: base_url + 'flipbooks/abrir/' + <?= $row_evento->referente_2_id ?> + '/' + <?= $row_evento->entero_1 ?>,
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
            ]
        });

        calendar.setOption('locale', 'Es');
        calendar.render();
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

<div id="calendar"></div>

<div class="card my-2">
    <div class="card-body">
        <p>
            <span class="text-muted">Usuario: </span>
            <strong><?= $this->session->userdata('nombre_completo') ?>    </strong>
            <span class="text-muted"> | </span>

            <span class="text-muted">Institución: </span>
            <strong> <?= $this->App_model->nombre_institucion($this->session->userdata('institucion_id')) ?>     </strong>
            <span class="text-muted"> | </span>
            
            <span class="text-muted">Fecha: </span>
            <strong> <?= date('Y-m-d') ?>     </strong>
            <span class="text-muted"> | </span>
        </p>
    </div>
</div>

    