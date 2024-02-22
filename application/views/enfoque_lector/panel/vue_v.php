<?php
    $arrLecturas = explode(',',$row->texto_1);
?>

<script>
var enfoqueLectorApp = createApp({
    data(){
        return{
            post: <?= json_encode($row) ?>,
            loading: false,
            fields: {},
            seccion: 'inicio',
            contenido: 'lecturas',
            lecturas: <?= json_encode($lecturas->result()) ?>,
            lecturaDinamicaIdActiva: 0,
            herramientasVirtuales: [
                {id:1,texto:'Práctica',numero:'1',imagen:'practica_lectora_1.png', seccion: 'practica-lectora-1'},
                {id:2,texto:'Práctica',numero:'2',imagen:'practica_lectora_2.png', seccion: 'practica-lectora-2'},
                {id:3,texto:'Práctica',numero:'3',imagen:'practica_lectora_3.png', seccion: 'practica-lectora-3'},
            ],
            lecturasFluidez: [29712],
            lecturaFluidezId: 29712,
            showFrame: true
        }
    },
    methods: {
        setContenido: function(nuevoContenido){
            this.contenido = nuevoContenido
        },
        setLecturaDinamica: function(lecturaDinamicaId){
            this.lecturaDinamicaIdActiva = lecturaDinamicaId
            this.seccion = 'lectura-dinamica'
            this.showFrame = true
        },
        setFluidezLectora: function(index){
            this.lecturaFluidezId = this.lecturasFluidez[index]
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
                return URL_APP + 'flipbooks/leer_v6/' + this.post.referente_1_id
            }
            if ( this.seccion == 'lectura-dinamica' ) {
                return URL_APP + 'temas/lectura_dinamica/' + this.lecturaDinamicaIdActiva
            }
            if ( this.seccion == 'fluidez-lectora' ) {
                return URL_APP + 'enfoque_lector/fluidez_lectora/' + this.lecturaFluidezId
            }
            if ( this.seccion == 'practica-lectora-2' ) {
                return URL_APP + 'quices/practica_lectora/203'
            }
            if ( this.seccion == 'practica-lectora-3' ) {
                return URL_APP + 'quices/practica_lectora/202'
            }
            
        }
    }
}).mount('#enfoqueLectorApp')
</script>