<?php $this->load->view('assets/fullcalendar'); ?>
<?php $this->load->view('assets/bootstrap_datepicker'); ?>

<?php
    $att_evento_id = array(
        'id'     => 'campo_evento_id',
        'name'   => 'evento_id',
        'required'   => TRUE,
        'class'  => 'd-none',
        'value'  => 0
    );

    $att_url = array(
        'id'     => 'campo_url',
        'name'   => 'url',
        'class'  => 'form-control',
        'type'  => 'url',
        'value'  => '',
        'placeholder'   => 'Escriba la url',
        'title'   => 'Escriba la URL del evento',
        'required' => TRUE
    );
    
    $att_fecha_inicio = array(
        'id'     => 'campo_fecha_inicio',
        'name'   => 'fecha_inicio',
        'class'  => 'form-control bs_datepicker',
        'value'  => '',
        'placeholder'  => 'AAAA-MM-DD',
        'required' => TRUE
    );
    
    //Selección de grupos
        $str_grupos = '0';
        $arr_grupos = $this->session->userdata('arr_grupos');
        if ( count($arr_grupos) > 0 ) { $str_grupos = implode(',', $arr_grupos); }
        $condicion_grupos = 'grupo.id IN (' . $str_grupos . ')';
        $opciones_grupo = $this->App_model->opciones_grupo($condicion_grupos);
        
    //
        $colores_evento = $this->App_model->arr_item(13, 'color');
        
    //Get para link print
        $get_print = $this->Pcrn->get_str();
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
    var mes_actual = '<?= date('Y-m') ?>';
    var get_print = '<?= $get_print ?>';
    

// DOCUMENT
//---------------------------------------------------------------------------------------------------------

    $(document).ready(function()
    {

        $('#eliminar_link').click(function(){
            eliminar_evento();
        });

        $('#calendar').fullCalendar({
            themeSystem: 'bootstrap4',
            header: {
                left: 'prev,next today',
                center: 'title',
                right: 'month,agendaWeek'
            },
            defaultDate: '<?= date('Y-m-d') ?>',
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
                        title: "Tema: <?= $row_evento->nombre_evento ?>",
                        start: '<?= $row_evento->fecha_inicio ?>',
                        end: '<?= $row_evento->fecha_inicio ?>',
                        url: base_url + 'flipbooks/leer/' + <?= $row_evento->referente_2_id ?> + '/' + <?= $row_evento->entero_1 ?>,
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
                        url: 'link_externo',
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
                $('#eliminar_link').addClass('d-none');
                $('.evento_link').addClass('d-none');
                
            }
        });
        
        $('.fc-next-button').click(function() { act_mes_actual(); });
        $('.fc-prev-button').click(function() { act_mes_actual(); });
        $('.fc-today-button').click(function() { act_mes_actual(); });
        
    });

// FUNCIONES
//---------------------------------------------------------------------------------------------------------

    /**
    * Cargar valores de evento en formulario
     */
    function cargar()
    {
        //Variables
            url = $('#link_' + evento_id).data('url');
            fecha_inicio = $('#link_' + evento_id).data('fecha_inicio');
            grupo_id = $('#link_' + evento_id).data('grupo_id');
        
        //Formato
            $('.evento_link').addClass('hidden');
            $('#link_' + evento_id).removeClass('hidden');
            $('#eliminar_link').removeClass('hidden');
            $('#eliminar_link').removeClass('hidden');
        
        //Formulario
            $('#campo_evento_id').val(evento_id);
            $('#campo_url').val(url);
            $('#campo_fecha_inicio').val(fecha_inicio);
            $('#campo_grupo_id').val(grupo_id);
    }
    
    /**
    * AJAX
     */
    function eliminar_evento()
    {
        $.ajax({        
            type: 'POST',
            url: base_url + controlador + '/eliminar/' + evento_id,
            success: function(cant_eliminados){
                if ( cant_eliminados > 0 ) {
                    $('#calendar').fullCalendar('removeEvents', evento_id);
                    $('#campo_url').val('');
                    $('#campo_fecha_inicio').val('');
                    $('#campo_grupo_id').val('');
                }
                
            }
        });
    }
    
    function act_mes_actual()
    {
        var moment = $('#calendar').fullCalendar('getDate');
        mes_actual = moment.format('YYYY-M');
        var href = base_url + 'eventos/imprimir_calendario/' + mes_actual + '/?' + get_print;
        $('#boton_print').attr('href', href);
    }

</script>

<div class="row">
    <div class="col-md-3">
        <div class="mb-2">
            <?php $this->load->view('eventos/filtro_grupos_v'); ?>
        </div>
        <div class="mb-2">
            <?php $this->load->view('eventos/filtro_areas_v'); ?>
        </div>
        <div class="mb-2">
            <?php $this->load->view('eventos/filtro_tipos_v'); ?>
        </div>
        
        <div class="mb-2">
            <a href="<?php echo base_url("eventos/imprimir_calendario/?{$get_print}") ?>" class="btn btn-info btn-block" id="boton_print" target="_blank">
                <i class="fa fa-print"></i> Imprimir
            </a>
        </div>
        
        <?= form_open("eventos/crear_ev_link", $att_form) ?>
            <?= form_input($att_evento_id) ?>
            
            <div class="card card-default">
                <div class="card-header">
                    Asignar link a una fecha
                </div>
                <div class="card-body">
                    <p>Haga clic en la fecha donde programará el link</p>
                    <div class="form-group">
                        <label for="url">Fecha</label>
                        <?= form_input($att_fecha_inicio) ?>
                    </div>
                    <div class="form-group">
                        <label for="url">URL</label>
                        <?= form_input($att_url) ?>
                    </div>
                    <div class="form-group">
                        <label for="grupo_id">Grupo</label>
                        <?= form_dropdown('grupo_id', $opciones_grupo, '', 'id="campo_grupo_id" class="form-control" required title="Elija el grupo al cual le asigna el link"') ?>
                    </div>
                    
                    <button class="btn btn-primary" type="submit">
                        Guardar
                    </button>
                    
                    <div id="eliminar_link" class="btn btn-warning hidden">
                        <i class="fa fa-trash"></i>
                    </div>
                    
                    <?php foreach ($eventos[4]->result() as $row_evento) : ?>
                        <a id="link_<?= $row_evento->id ?>" href="<?= $row_evento->url ?>" target="_blank" class="evento_link hidden btn btn-info" data-url="<?= $row_evento->url ?>" data-fecha_inicio="<?= $row_evento->fecha_inicio ?>" data-grupo_id="0<?= $row_evento->grupo_id ?>" title="Abrir el link">
                            <i class="fa fa-external-link-alt"></i>
                        </a>
                    <?php endforeach; ?>
                    
                </div>
            </div>
        <?= form_close('') ?>

    </div>
    <div class="col-md-9">
        <div class="card card-default">
            <div class="card-body">
                <div id='calendar'></div>
            </div>
        </div>
    </div>
</div>