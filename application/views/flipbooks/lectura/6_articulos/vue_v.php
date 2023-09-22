<script>
var flipbookApp = createApp({
    data(){
        return{
            flipbook: <?= json_encode($row) ?>,
            loading: false,
            numPage: 1,
            currentArticulo:{
                id:0,
                titulo:'',
                subitle:'',
                resumen:'',
                contenido:'Contenido',
                tema_id:0,
            },
            unidades:[],
            bookData:{},
            anotaciones: {},
            anotacion: {anotacion: '', calificacion: 0},
            grupoId: <?= $this->session->userdata('grupo_id') ?>,
            preguntaAbiertaId: 0,
            preguntasAbiertasAsignadas: [],
            preguntaAbiertaPersonalizada: false,
        }
    },
    methods: {
        getFlipbookData: function(){
            axios.get(URL_API + 'flipbooks/data/' + this.flipbook.id)
            .then(response => {
                this.bookData = response.data;
                this.getFirstArticulo()
                this.setUnidades()
            })
            .catch(function (error) { console.log(error) })
        },
        getFirstArticulo: function(){
            var articuloId = 0
            if (this.bookData.articulos.length > 0) {
                articuloId = this.bookData.articulos[0].articulo_id
            }
            this.getArticulo(articuloId)
            this.getAnotaciones()
        },
        getArticulo(articuloId){
            axios.get(URL_API + 'flipbooks/get_articulo/' + articuloId)
            .then(response => {
                this.currentArticulo = response.data.articulo
                this.setAnotacion()
            })
            .catch(function(error) { console.log(error) })
        },
        setUnidades: function(){
            // Llena el arreglo 'unidades' con los elementos necesarios
            for (let i = 1; i <= this.bookData.row.cantidad_unidades; i++) {
                this.unidades.push({
                    id: i,
                    nombre: `Unidad ${i}`
                });
            }
        },
        // Gestión de preguntas abiertas
        //-----------------------------------------------------------------------------
        cargarPreguntasAbiertasAsignadas: function(){
            axios.get(URL_API + 'grupos/preguntas_abiertas_asignadas/' + this.grupoId + '/' + this.flipbook.area_id, )
            .then(response => {
                this.preguntasAbiertasAsignadas = response.data.pa_asignadas;
            })
            .catch(function (error) { console.log(error) })
        },
        setPreguntaAbierta: function(preguntaId){
            this.preguntaAbiertaId = preguntaId;
        },
        submitPreguntaAbiertaForm: function(){
            var formularioEsValido = false;

            if ( this.preguntaAbiertaPersonalizada ) {
                
                this.preguntaId = 0 
                if ( $('#field-texto_pregunta').val() )
                {
                    formularioEsValido = true
                } else {
                    //No hay texto escrito, se marca como no válido
                    $('#field-texto_pregunta').addClass('is-invalid');
                }
            } else {
                if ( this.preguntaAbiertaId > 0 ){ 
                    formularioEsValido = true; //Hay pregunta seleccionada
                } else {
                    toastr['info']('Debe seleccionar una de las preguntas');
                }
            }

            if ( formularioEsValido ) { this.asignarPreguntaAbierta() }
        },
        asignarPreguntaAbierta: function(){
            axios.post(URL_API + 'grupos/asignar_pregunta_abierta/' + this.grupoId + '/' + this.preguntaAbiertaId, $('#pregunta-abierta-form').serialize())
            .then(response => {
                console.log(response.data.message)
                if ( response.data.status == 1 ) {
                    toastr['success']('La pregunta fue asignada al grupo');
                    //$('#modal_pa').modal('hide');
                    $('#field-texto_pregunta').val('');    //Limpiar campo
                    this.cargarPreguntasAbiertasAsignadas();
                } else {
                    toastr['warning']('No se pudo asignar la pregunta');
                }
            })
            .catch(function (error) { console.log(error) })
        },
        // Gestión de anotaciones
        //-----------------------------------------------------------------------------
        getAnotaciones: function(){
            axios.get(URL_API + 'flipbooks/get_anotaciones/' + this.flipbook.id)
            .then(response => {
                this.anotaciones = response.data.anotaciones
                this.setAnotacion()
            })
            .catch(function(error) { console.log(error) })
        },
        setAnotacion: function(){
            this.anotacion = {anotacion: '', calificacion: 0, tema_id: this.currentArticulo.tema_id}
            var findedAnotacion = this.anotaciones.find(item => item.tema_id == this.currentArticulo.tema_id)
            if ( findedAnotacion != undefined ) this.anotacion = findedAnotacion
        },
        guardarAnotacion: function(){
            this.loading = true
            var formValues = new FormData(document.getElementById('anotacion-form'))
            formValues.append('pagina_id', this.currentArticulo.id)
            formValues.append('tema_id', this.currentArticulo.tema_id)
            formValues.append('tabla_contenido', 2000) //Tabla post
            axios.post(URL_API + 'flipbooks/save_anotacion/', formValues)
            .then(response => {
                toastr["success"]('Anotación guardada')
                this.anotaciones.unshift(this.anotacion)
                this.loading = false
            })
            .catch( function(error) {console.log(error)} )
        },
    },
    mounted(){
        this.getFlipbookData()
    }
}).mount('#flipbookApp')
</script>