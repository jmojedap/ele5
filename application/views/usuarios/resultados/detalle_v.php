<div id="resultados_app">
    <div class="center_box_750">
        <?php if ( $this->session->userdata('role') <= 1 ) : ?>
            <div class="mb-2">
                <button class="btn btn-info w120p" v-on:click="recalificar()">
                    <i class="fa fa-sync mr-2"></i>
                    Recalificar
                </button>
            </div>
        <?php endif; ?>
        <div class="progress mb-2">
            <div class="progress-bar" role="progressbar" v-bind:style="`width: ` + porcentaje + `%;`" v-bind:aria-valuenow="porcentaje" aria-valuemin="0" aria-valuemax="100">{{ porcentaje }}% correctas</div>
        </div>
        <div class="card mb-2" v-for="(respuesta, kp) in respuestas"
            v-bind:class="{ 'border-danger': respuesta.respuesta_correcta != respuesta.respuesta }"
            >
            <div class="card-body">
                <p>
                    {{ parseInt(kp) + 1 }}. <span v-html="respuesta.texto_pregunta"></span>
                </p>
                <div class="row">
                    <div class="col-md-6">
                        <span class="text-muted">Respuesta estudiante</>
                        <br>
                        <span v-show="respuesta.respuesta != respuesta.respuesta_correcta"><i class="fa fa-times text-danger"></i></span>
                        <span v-show="respuesta.respuesta == respuesta.respuesta_correcta"><i class="fa fa-check text-success"></i></span>
                        <strong v-bind:class="{'text-success': respuesta.respuesta_correcta == respuesta.respuesta, 'text-danger': respuesta.respuesta_correcta != respuesta.respuesta }">
                            <span v-show="respuesta.respuesta == 1">{{ respuesta.opcion_1 }}</span>  
                            <span v-show="respuesta.respuesta == 2">{{ respuesta.opcion_2 }}</span>  
                            <span v-show="respuesta.respuesta == 3">{{ respuesta.opcion_3 }}</span>  
                            <span v-show="respuesta.respuesta == 4">{{ respuesta.opcion_4 }}</span>  
                        </strong>
                    </div>
                    <div class="col-md-6">
                        <?php if ( $this->session->userdata('role') <= 5 ) : ?>
                            <span class="text-muted">Respuesta correcta</span>
                            <br>
                            <strong class="text-success">
                                <span v-show="respuesta.respuesta_correcta == 1">{{ respuesta.opcion_1 }}</span>  
                                <span v-show="respuesta.respuesta_correcta == 2">{{ respuesta.opcion_2 }}</span>  
                                <span v-show="respuesta.respuesta_correcta == 3">{{ respuesta.opcion_3 }}</span>  
                                <span v-show="respuesta.respuesta_correcta == 4">{{ respuesta.opcion_4 }}</span>  
                            </strong>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
var resultados_app = new Vue({
    el: '#resultados_app',
    data: {
        usuario_id: '<?= $row->id ?>',
        uc_id: '<?= $row_uc->id ?>',
        respuestas: <?= json_encode($respuestas_cuestionario->result()) ?>,
        porcentaje: <?= intval($res_usuario['porcentaje']) ?>
    },
    methods: {
        recalificar: function(){
            axios.get(url_api + 'cuestionarios/calificar/' + this.uc_id)
            .then(response => {
                if ( response.data.affected_rows >= 0 ) {
                    toastr['success']('Se acualizó la calificación. Cargando...')
                    setTimeout(() => {
                        window.location = url_app + 'usuarios/resultados_detalle/' + this.usuario_id + '/' + this.uc_id
                    }, 2000);
                }
            })
            .catch(function(error) { console.log(error) })
        },
    }
})
</script>