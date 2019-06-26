<?php $this->load->view('assets/bootstrap_datepicker'); ?>
<?php $this->load->view('assets/fullcalendar'); ?>
<?php $this->load->view('assets/icheck'); ?>

<?php
    $colores_evento = $this->App_model->arr_item(13, 'color');
?>

<script>
    //Variables
    var base_url = '<?= base_url(); ?>';
</script>

<script>
    $(document).ready(function() 
    {

        $('#calendar').fullCalendar({
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
                <?php foreach ($eventos[1]->result() as $row_evento) : ?>
                {
                    id: <?php echo $row_evento->id ?>,
                    title: 'Cuestionario: <?= str_replace("'", "\'", $row_evento->nombre_cuestionario) ?>',
                    start: '<?= $row_evento->fecha_inicio ?>',
                    end: '<?= $this->Pcrn->suma_fecha($row_evento->fecha_fin) ?>',
                    url: base_url + 'cuestionarios/preliminar/' + '<?php echo $row_evento->referente_id ?>',
                    color : '<?= $colores_evento[1] ?>'
                },
                <?php endforeach ?>
                
                //Eventos, programación de temas
                
                <?php foreach ($eventos[2]->result() as $row_evento) : ?>
                <?php
                    $url = base_url("flipbooks/abrir_flipbook/{$row_evento->referente_2_id}/{$row_evento->entero_1}/{$row_evento->referente_id}");
                ?>
                {
                    id: <?= $row_evento->id ?>,
                    title: 'Tema: <?= $row_evento->nombre_evento ?>',
                    start: '<?= $row_evento->fecha_inicio ?>',
                    end: '<?= $row_evento->fecha_inicio ?>',
                    url: '<?= $url ?>',
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
                    url: '<?= $url ?>',
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
                        url: '<?= $row_evento->url ?>',
                        color : '<?= $colores_evento[4] ?>'
                    },
                <?php endforeach ?>
            ],
            eventClick: function(event) {
                if (event.url) {
                    window.open(event.url, "_blank");
                    return false;
                }
            }
        });

    });

</script>

<div class="row">
    <div class="col-md-3">
        <div class="mb-2">
            <?php $this->load->view('eventos/filtro_areas_v'); ?>
        </div>
        
        <div class="mb-2">
            <?php $this->load->view('eventos/filtro_tipos_v'); ?>
        </div>
    </div>
    <div class="col-md-9">
        <div class="card">
            <div class="card-body">
                <div id='calendar'></div>
            </div>
        </div>
    </div>
</div>


