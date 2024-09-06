<?php $this->load->view('assets/bootstrap_datepicker'); ?>
<?php $this->load->view('assets/fullcalendar4'); ?>
<?php $this->load->view('assets/icheck'); ?>

<?php
    $colores_evento = $this->App_model->arr_item(13, 'color');
?>

<script>
    //Variables
    var base_url = '<?= base_url(); ?>';
</script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        var calendarEl = document.getElementById('calendar');

        var calendar = new FullCalendar.Calendar(calendarEl, {
            plugins: [ 'interaction', 'dayGrid', 'timeGrid', 'bootstrap' ],
            themeSystem: 'bootstrap',
            defaultView: 'dayGridMonth',
            //defaultDate: '2019-01-05',
            defaultDate: '<?= date('Y-m-d') ?>',
            header: {
                left: 'prev,next today',
                center: 'title',
                right: ''
            },
            firstDay: 0,
            events: [
                //Eventos, programación de cuestionarios
                <?php foreach ($eventos[1]->result() as $row_evento) : ?>
                {
                    id: <?= $row_evento->id ?>,
                    title: 'Cuestionario: <?= str_replace("'", "\'", $row_evento->nombre_cuestionario) ?>',
                    start: '<?= $row_evento->fecha_inicio ?>',
                    end: '<?= $this->Pcrn->suma_fecha($row_evento->fecha_fin) ?>',
                    url: base_url + 'cuestionarios/preliminar/' + '<?= $row_evento->referente_id ?>',
                    color : '<?= $colores_evento[1] ?>'
                },
                <?php endforeach ?>
                
                //Eventos, programación de temas
                
                <?php foreach ($eventos[2]->result() as $row_evento) : ?>
                <?php
                    $url = base_url("flipbooks/abrir/{$row_evento->referente_2_id}/{$row_evento->entero_1}/{$row_evento->referente_id}");
                ?>
                {
                    id: <?= $row_evento->id ?>,
                    title: 'Tema: <?= str_replace("'", "\'", $row_evento->nombre_cuestionario) ?>',
                    start: '<?= $row_evento->fecha_inicio ?>',
                    end: '<?= $row_evento->fecha_inicio ?>',
                    url: "<?= $url ?>",
                    color : '<?= $colores_evento[2] ?>'
                },
                <?php endforeach ?>
                
                //Quices, programación de quices
                
                <?php foreach ($eventos[3]->result() as $row_evento) : ?>
                <?php
                    $url = base_url("quices/iniciar/{$row_evento->referente_id}/");
                ?>
                {
                    id: <?= $row_evento->id ?>,
                    title: 'Evidencia: <?= $row_evento->nombre_quiz ?>',
                    start: '<?= $row_evento->fecha_inicio ?>',
                    end: '<?= $row_evento->fecha_inicio ?>',
                    url: "<?= $url ?>",
                    color : '<?= $colores_evento[3] ?>'
                },
                <?php endforeach ?>
                
                //Eventos, programación de links
                <?php foreach ($eventos[4]->result() as $row_evento) : ?>
                    {
                        id: <?= $row_evento->id ?>,
                        title: '>> <?= $this->Pcrn->texto_url($row_evento->url) ?>',
                        start: '<?= $row_evento->fecha_inicio ?>',
                        end: '<?= $row_evento->fecha_inicio ?>',
                        url: "<?= $row_evento->url ?>",
                        color : '<?= $colores_evento[4] ?>'
                    },
                <?php endforeach ?>

                //Eventos, programación de links internos (5)
                <?php foreach ($eventos[5]->result() as $row_evento) : ?>
                    <?php
                        $color = $colores_evento[5];
                    ?>
                    {
                        id: <?= $row_evento->id ?>,
                        title: 'LINK >> <?= $this->Pcrn->texto_url($row_evento->url) ?>',
                        start: '<?= $row_evento->fecha_inicio ?>',
                        end: '<?= $row_evento->fecha_inicio ?>',
                        url: "<?= $row_evento->url ?>",
                        color : '<?= $color ?>',
                        tipo: 'link_interno',
                        fecha_inicio: '<?= $row_evento->fecha_inicio ?>',
                        grupo_id: '0<?= $row_evento->grupo_id ?>'
                    },
                <?php endforeach ?>

                //Eventos, programación de sesiones virtuales (6)
                <?php foreach ($eventos[6]->result() as $row_evento) : ?>
                    {
                        id: <?= $row_evento->id ?>,
                        title: 'Sesión Virtual >> <?= $this->Pcrn->texto_url($row_evento->url) ?>',
                        start: '<?= $row_evento->fecha_inicio ?>',
                        end: '<?= $row_evento->fecha_inicio ?>',
                        url: "<?= $row_evento->url ?>",
                        color : '<?= $colores_evento[6] ?>',
                        tipo: 'sesion_virtual',
                        descripcion: '<?= json_encode($row_evento->descripcion) ?>',
                        fecha_inicio: '<?= $row_evento->fecha_inicio ?>',
                        grupo_id: '0<?= $row_evento->grupo_id ?>'
                    },
                <?php endforeach ?>

                //Eventos, programación de archivos asignados (7)
                <?php foreach ($eventos[7]->result() as $row_evento) : ?>
                    {
                        id: <?= $row_evento->id ?>,
                        title: 'Archivo: > <?= $this->Pcrn->texto_url($row_evento->nombre_evento) ?>',
                        start: '<?= $row_evento->fecha_inicio ?>',
                        end: '<?= $row_evento->fecha_inicio ?>',
                        url: "<?= $row_evento->url ?>",
                        color : '<?= $colores_evento[7] ?>',
                        tipo: 'archivo_asignado',
                        fecha_inicio: '<?= $row_evento->fecha_inicio ?>',
                        grupo_id: '0<?= $row_evento->grupo_id ?>'
                    },
                <?php endforeach ?>
            ],
            eventClick: function(info) {
                info.jsEvent.preventDefault(); // don't let the browser navigate
                console.log('evento_id: ' + info.event.id);
                window.open(info.event.url, "_blank");
                return false;
            },
        });

        calendar.setOption('locale', 'Es');
        calendar.render();
    });
</script>

<div class="row">
    <div class="col-md-3">
        <div class="mb-2">
            <?php $this->load->view('eventos/filtros_bs4/filtro_areas_v'); ?>
        </div>
        
        <div class="mb-2">
            <?php $this->load->view('eventos/filtros_bs4/filtro_tipos_v'); ?>
        </div>
    </div>
    <div class="col-md-9">
        <div id="calendar"></div>
    </div>
</div>


