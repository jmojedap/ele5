<?php
    $arrLecturas = explode(',',$row->texto_1);
?>

<script>
// Variables
//-----------------------------------------------------------------------------
    var arrLecturas = <?= json_encode($lecturas->result()) ?>;
    var arrArchivosDescargables = <?= json_encode($files->result()) ?>;

// VueApp
//-----------------------------------------------------------------------------
var enfoqueLectorApp = createApp({
    data(){
        return{
            post: <?= json_encode($row) ?>,
            flipbook: <?= json_encode($flipbook) ?>,
            loading: false,
            fields: {},
            seccion: 'inicio',
            contenido: 'herramientas_virtuales',
            lecturas: arrLecturas,
            lecturaDinamicaIdActiva: <?= $arrLecturas[0] ?>,
            herramientasVirtuales: [
                {id:1,texto:'Práctica lectora',numero:'1',imagen:'icono-practica-lectora-2.png', destino: 'practicas_lectoras'},
                {id:2,texto:'Ejercicios descargables',numero:'2',imagen:'icono-ejercicios-descargables.png', destino: 'archivos_descargables'},
            ],
            practicasLectoras: [
                {id:1,texto:'Práctica',numero:'1',imagen:'practica_lectora_1.png', seccion: 'practica-lectora-1'},
                {id:2,texto:'Práctica',numero:'2',imagen:'practica_lectora_2.png', seccion: 'practica-lectora-2'},
                {id:3,texto:'Práctica',numero:'3',imagen:'practica_lectora_3.png', seccion: 'practica-lectora-3'},
            ],
            archivosDescargables: arrArchivosDescargables,
            lecturasFluidez: <?= json_encode($lecturas->result()) ?>,
            lecturaFluidezId: <?= $arrLecturas[0] ?>,
            showFrame: true
        }
    },
    methods: {
        setContenido: function(nuevoContenido){
            console.log(nuevoContenido)
            this.contenido = nuevoContenido
        },
        setLecturaDinamica: function(lecturaDinamicaId){
            this.lecturaDinamicaIdActiva = lecturaDinamicaId
            this.seccion = 'lectura-dinamica'
            this.showFrame = true
        },
        setFluidezLectora: function(lecturaId){
            this.lecturaFluidezId = lecturaId
            console.log(this.frameContent)
            this.seccion = 'fluidez-lectora'
            showFrame: true
        },
        setVerLibro: function(){
            this.seccion = 'ver-libro'
            showFrame: true
        }
    },
    mounted(){
        //this.getList()
    },
    computed:{
        frameContent() {
            if ( this.seccion == 'ver-libro' ) {
                return URL_APP + this.getFlipbookSrc
            }
            if ( this.seccion == 'lectura-dinamica' ) {
                return URL_APP + 'temas/lectura_dinamica/' + this.lecturaDinamicaIdActiva + '?bsversion=5'
            }
            if ( this.seccion == 'fluidez-lectora' ) {
                return URL_APP + 'enfoque_lector/fluidez_lectora/' + this.lecturaFluidezId
            }
            if ( this.seccion == 'practica-lectora-1' ) {
                return URL_APP + 'quices/practica_lectora/201'
            }
            if ( this.seccion == 'practica-lectora-2' ) {
                return URL_APP + 'quices/practica_lectora/203'
            }
            if ( this.seccion == 'practica-lectora-3' ) {
                return URL_APP + 'quices/practica_lectora/202'
            }       
        },
        getFlipbookSrc: function(){
            var flipbookSrc = 'flipbooks/leer_v5/' + this.flipbook.id + '/?embed=1'
            if ( this.flipbook.tipo_flipbook_id == '6' ) {
                flipbookSrc = 'flipbooks/leer_v6/' + this.flipbook.id + '/?embed=1'
            }
            return flipbookSrc
        },
    }
}).mount('#enfoqueLectorApp')
</script>