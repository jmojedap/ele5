<?php $this->load->view('assets/fullcalendar4') ?>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        var calendarEl = document.getElementById('calendar');

        var calendar = new FullCalendar.Calendar(calendarEl, {
            plugins: [ 'interaction', 'dayGrid', 'timeGrid', 'bootstrap' ],
            themeSystem: 'bootstrap',
            defaultView: 'dayGridMonth',
            defaultDate: '2020-01-27',
            header: {
                left: 'prev,next today miBoton',
                center: 'title',
                right: 'dayGridMonth,timeGridWeek,timeGridDay'
            },
            customButtons: {
                miBoton: {
                    text: 'Agregar',
                    click: function(){
                        $('#exampleModal').modal('toggle')
                    }
                }
            },
            dateClick: function(info){
                console.log(info);
                //$('#exampleModal').modal('toggle');
                calendar.addEvent({title: 'Nuevo evento', date: info.dateStr });
            },
            eventClick: function(info){
                console.log(info);
                console.log(info.event.title);
                console.log(info.event.extendedProps.notes);
            },
            events:[
                {
                    title: 'Mi evento 1',
                    start: '2020-01-28 12:35:00',
                    notes: 'La nota al evento 1'
                },
                {
                    title: 'Mi evento 1',
                    start: '2020-01-07 12:35:00',
                    end: '2020-01-12 12:35:00',
                    color: '#FFCCAA',
                    textColor: '#000000',
                    notes: 'La nota al evento 2'
                }
            ]
        });

        calendar.setOption('locale', 'Es');
        calendar.render();
    });
</script>


<div id='calendar'></div>

<!-- Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Modal title</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        ...
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary">Save changes</button>
      </div>
    </div>
  </div>
</div>