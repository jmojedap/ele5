<script>
// VueApp
//-----------------------------------------------------------------------------
var fluidezLectora = createApp({
    data() {
        return {
            row: <?= json_encode($ledin) ?>,
            loading: false,
            seccion: 'preparacion',
            status: 'leyendo',
            elementos: <?= $ledin->contenido_json ?>,
            numeroElemento: 0,
            segundosLectura: <?= $segundosLectura ?>,
            mostrarResultado: false,
        }
    },
    methods: {
        iniciarLectura() {
            this.seccion = 'lectura'
            this.status = 'leyendo'
            console.log('actualizando')
            const progressBar = document.getElementById('time-progress-bar');
            progressBar.style.width = '100%';
            
            setTimeout(function() {
                new bootstrap.Modal($('#timeOutModal')).show();
            }, this.segundosLectura * 1000);
        },
        reiniciarProgressBar: function(){
            const progressBar = document.getElementById('time-progress-bar');
            progressBar.style.transition = 'width 0.05s linear';
            progressBar.style.width = '0%';
        },
        resaltarAvance: function(){
            this.status = 'resultado'
            var spanElements = document.querySelectorAll('#lectura-dinamica-resultado span');

            // Iterar sobre los elementos y asignar la clase "active" a los primeros 10
            for (var i = 0; i < this.numeroElemento && i < spanElements.length; i++) {
                spanElements[i].classList.add('active');
            }
        },
        reiniciarApp: function(){
            location.reload()
        },
    },
    computed:{
        palabrasPorMinuto: function(){
            var palabrasPorMinuto = this.numeroElemento;
            return parseInt(palabrasPorMinuto)
        },
    },
    mounted() {
        //this.getList()
        //this.actualizarProgressBar()
    }
}).mount('#fluidezLectora')

// Functions
//-----------------------------------------------------------------------------

//Evento click sobre un span de la lectura dinámica
document.addEventListener('DOMContentLoaded', function() {
    // Obtener el DIV
    const divLectura = document.getElementById('lectura-dinamica');

    // Añadir un event listener a cada span
    divLectura.querySelectorAll('span').forEach(function(span, index) {
        span.addEventListener('click', function() {
            fluidezLectora.numeroElemento = index + 1;
            fluidezLectora.mostrarResultado = true;
            fluidezLectora.resaltarAvance();
        });
    });
});

</script>