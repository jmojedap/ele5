<?php
    $colores_evento = $this->App_model->arr_item(13, 'color');
?>

<script>
// VARIABLES
//---------------------------------------------------------------------------------------------------------
    var base_url = '<?php echo base_url(); ?>';
    var controlador = 'eventos';
    var evento_id = 0;
    var url = '';
    var grupo_id = 0;
    var fecha_inicio = '<?php echo date('Y-m-d') ?>';
    var mes_actual = '<?php echo date('Y-m') ?>';
    var get_print = '<?php echo $get_print ?>';

// DOCUMENT READY
//---------------------------------------------------------------------------------------------------------

$(document).ready(function(){
    var calendarEl = document.getElementById('calendar');

    var calendar = new FullCalendar.Calendar(calendarEl, {
        plugins: [ 'interaction', 'dayGrid', 'timeGrid', 'bootstrap' ],
        themeSystem: 'bootstrap',
        defaultView: 'dayGridMonth',
        //defaultDate: '2019-01-05',
        defaultDate: '<?php echo date('Y-m-d') ?>',
        header: {
            left: 'prev,next today',
            center: 'title',
            right: 'agregar_link'
        },
        firstDay: 0,
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
                    url: '<?php echo $row_evento->url ?>',
                    color : '<?= $color ?>',
                    tipo: 'link_externo',
                    fecha_inicio: '<?php echo $row_evento->fecha_inicio ?>',
                    grupo_id: '0<?php echo $row_evento->grupo_id ?>'
                },
            <?php endforeach ?>
        ],
        eventClick: function(info) {
            info.jsEvent.preventDefault(); // don't let the browser navigate
            console.log('evento_id: ' + info.event.id);
            if ( info.event.extendedProps.tipo === 'link_externo' )
            {
                evento_id = info.event.id;
                cargar();
                $('#evento_modal').modal('toggle');
                return false;
            } else {
                window.open(info.event.url, "_blank");
                return false;
            }
        },
        dateClick: function(info) {
            $('#evento_modal').modal('toggle');
            console.log(info.dateStr);
            $('.fc-day').css('background-color', '#ffffff');
            $(this).css('background-color', '#fcf8e3');
            $('#field-url').focus();
            $('#field-url').val('');
            $('#field-grupo_id').val('');
            $('#field-fecha_inicio').val(info.dateStr);
            $('#link_evento_actual').hide();
            $('#eliminar_link').hide();
        },
        customButtons: {
            agregar_link: {
                text: '+ Enlace',
                click: function(){
                    $('#evento_modal').modal('toggle');
                }
            }
        },
    });

    calendar.setOption('locale', 'Es');
    calendar.render();

    /** Guardar evento */
    $('#evento_form').submit(function(){
        
        $.ajax({        
            type: 'POST',
            url: base_url + 'eventos/guardar_ev_link/' + evento_id,
            data: $('#evento_form').serialize(),
            success: function(response){
                toastr['success']('Evento guardado');
                var event = {
                    id: response.saved_id,
                    title: '>> ' + $('#field-url').val(),
                    start: $('#field-fecha_inicio').val(),
                    end: $('#field-fecha_inicio').val(),
                    url: $('#field-url').val(),
                    color: '<?php echo $colores_evento[4] ?>',
                    grupo_id: '0' + $('#field-grupo_id').val(),
                    fecha_inicio: $('#field-fecha_inicio').val(),
                    tipo: 'link_externo'
                };
                console.log(event);
                $('#evento_modal').modal('toggle');
                calendar.addEvent(event);
            }
        });
        return false;
    });

    /** Eliminar Evento Link */
    $('#eliminar_link').click(function(){
        
        $.ajax({
           type: 'POST',
           url: base_url + controlador + '/eliminar/' + evento_id,
            success: function(cant_eliminados){
                if ( cant_eliminados > 0 )
                {
                    var event = calendar.getEventById(evento_id);
                    event.remove();

                    $('#field-url').val('');
                    $('#field-fecha_inicio').val('');
                    $('#field-grupo_id').val('');
                    
                    $('#evento_modal').modal('toggle');
                    toastr['info']('Evento eliminado');
                }
            }
        });
    });

    /**
    * Cargar valores de evento enlace en formulario
    */
    function cargar()
    {
        var event = calendar.getEventById(evento_id);

        //Formulario
        $('#field-url').val(event.url);
        $('#field-fecha_inicio').val(event.extendedProps.fecha_inicio);
        $('#field-grupo_id').val(event.extendedProps.grupo_id);
        $('#link_evento_actual').attr('href', event.url);            
        $('#link_evento_actual').show();
        $('#eliminar_link').show();
    }

    $('.fc-next-button').click(function() { act_mes_actual(); });
    $('.fc-prev-button').click(function() { act_mes_actual(); });
    $('.fc-today-button').click(function() { act_mes_actual(); });

    /** Actualizar HREF para link de impresión del calendario */
    function act_mes_actual()
    {
        var date = calendar.getDate();
        var mes_str = date.getMonth();
        mes_str = mes_str + 1;
        mes_str = '0' + mes_str;
        mes_str = mes_str.substring(mes_str.length - 2, mes_str.length);
        var mes_actual = date.getFullYear() + '-' + mes_str;
        var href = base_url + 'eventos/imprimir_calendario/' + mes_actual + '/?' + get_print;
        $('#boton_print').attr('href', href);
    }
});
</script>