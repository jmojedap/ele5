<script>
// VueApp
//-----------------------------------------------------------------------------
var fluidezLectora = createApp({
    data() {
        return {
            row: <?= json_encode($ledin) ?>,
            loading: false,
            seccion: 'presentacion',
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
                new bootstrap.Modal($('#exampleModal')).show();
            }, this.segundosLectura * 1000);
        },
        reiniciarProgressBar: function(){
            const progressBar = document.getElementById('time-progress-bar');
            progressBar.style.transition = 'width 0.05s linear';
            progressBar.style.width = '0%';
        },
        resaltarAvance: function(){
            // Seleccionar los primeros 10 elementos <span> dentro del div
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

document.addEventListener('DOMContentLoaded', function() {
    // Obtén el div
    const divLectura = document.getElementById('lectura-dinamica');

    // Añade un event listener a cada span
    divLectura.querySelectorAll('span').forEach(function(span, index) {
        span.addEventListener('click', function() {
            fluidezLectora.numeroElemento = index + 1;
            fluidezLectora.mostrarResultado = true;
            fluidezLectora.resaltarAvance();
        });
    });
});



</script>