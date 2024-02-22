<?php
    $arrElementos = [];
    foreach ($elementos->result() as $elemento) {
        $elemento->respuesta = '';
        $elemento->respondido = 0;
        $elemento->comprobado = 0;
        $elemento->resultado = 0;
        $arrElementos[] = $elemento;
    }
?>

<script>
// Variables
//-----------------------------------------------------------------------------
var elementosInicial = <?= json_encode($arrElementos) ?>;

// VueApp
//-----------------------------------------------------------------------------
var resolverQuiz = createApp({
    data(){
        return{
            step: 'inicio',
            status: 'leyendo',
            loading: false,
            elementos: elementosInicial,
            currentElemento: {
                detalle:'',
                respuesta:''
            },
            currentKey: 0,
            opcionSeleccionada: '',
            resultadoTotal: 0,
            porcentajeTotal: 0,
            porcentajeAncho: 0,
            tiempoRespuesta: 10000,
        }
    },
    methods: {
        setCurrent(key){
            this.step = 'respuesta'
            this.status = 'leyendo'
            this.porcentajeAncho = 0
            this.currentKey = key
            this.currentElemento = this.elementos[key]
            setTimeout(() => {
                this.status = 'respondiendo'
            }, this.tiempoRespuesta);
            this.actualizarProgressBar()
        },
        actualizarProgressBar() {
            var paso = (100 / this.tiempoRespuesta) * 100;
            if (this.porcentajeAncho < 120 ) {
                this.porcentajeAncho += paso;
                setTimeout(this.actualizarProgressBar, 100);
            }
        },
        seleccionarOpcion: function(opcionSeleccionada){
            this.elementos[this.currentKey].respuesta = opcionSeleccionada
            this.elementos[this.currentKey].respondido = 1
        },
        comprobarRespuesta: function(){
            this.elementos[this.currentKey].resultado = 0
            if ( this.elementos[this.currentKey].clave == this.elementos[this.currentKey].respuesta ) {
                this.elementos[this.currentKey].resultado = 1
                toastr['success']('¡Correcto!')
            } else {
                toastr['error']('Incorrecto')
            }
            this.elementos[this.currentKey].comprobado = 1
            this.calcularResultado()
        },
        calcularResultado: function(){
            this.resultadoTotal = this.elementos.reduce((acumulador, elemento) => acumulador + elemento.resultado, 0);
            this.porcentajeTotal = Pcrn.intPercent(this.resultadoTotal, this.elementos.length)
        },
        handleSubmit: function(){
            this.loading = true
            var formValues = new FormData(document.getElementById('quizForm'))
            axios.post(URL_API + 'quices/guardar_resultado/', formValues)
            .then(response => {
                this.step = 'finalizado'
                if ( response.data.saved_id > 0 ) {
                    toastr['success']('Guardado')
                } else {
                    toastr['warning']('Ocurrió un error. No se guardó el resultado.')
                }
                this.loading = false
            })
            .catch( function(error) {console.log(error)} )
        },
        reiniciar: function(){
            this.elementos = <?= json_encode($arrElementos) ?>;
            this.step = 'inicio'
            this.resultadoTotal = 0
            this.porcentajeTotal = 0
            this.setCurrent(0)
        },
        optionClass: function(opcion){
            var optionClass = 'btn-light'
            if ( this.currentElemento.respondido == 1 ) {
                if ( opcion == this.currentElemento.respuesta ) {
                    optionClass = 'btn-primary'
                }
            }
            if ( this.currentElemento.comprobado == 1 ) {
                if ( opcion == this.currentElemento.respuesta ) {
                    if ( this.currentElemento.resultado == 0 ) optionClass = 'btn-danger'
                    if ( this.currentElemento.resultado == 1 ) optionClass = 'btn-success'
                }
            }
            return optionClass
        },

    },
    computed: {
        opciones: function(){
            return this.currentElemento.detalle.split(',')
        },
        respuestasCompletas: function(){
            var cantidadResponidos = this.elementos.reduce((acumulador, elemento) => acumulador + elemento.respondido, 0);
            if ( cantidadResponidos == this.elementos.length ) return true
            return false
        },
    },
    mounted(){
        //this.setCurrent(0)
    }
}).mount('#resolverQuiz')
</script>