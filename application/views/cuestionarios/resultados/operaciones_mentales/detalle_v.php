<script src="<?= URL_CONTENT ?>operaciones_mentales/logros.js"></script>

<style>
    .habilidad-interpretar { background-color: #DCF2C4;}
    .habilidad-argumentar{ background-color: #E4E0FC;}
    .habilidad-proponer { background-color: #F8E3B8;}
</style>

<div class="center_box_920">
    <p>
        <a href="<?= base_url("usuarios/cuestionarios/{$row->id}/") ?>" class="btn btn-outline-secondary">
            <i class="fa fa-arrow-left"></i> Respondidos
        </a>
        
        <?php if ( $this->session->userdata('rol_id') <= 5 ) : ?>
            <a href="<?= base_url("cuestionarios/grupos/{$row_cuestionario->id}/{$row->institucion_id}/{$row->grupo_id}") ?>" class="btn btn-outline-secondary">
                <i class="fa fa-users"></i> Estudiantes Grupo
            </a>
        <?php endif ?>
    </p>
    <div class="card">
        <div class="card-body">
            <h5 class="card-title">
                Cuestionario: <?= $row_cuestionario->nombre_cuestionario ?>
            </h5>
            <p>
                Fecha de respuesta: <span class="text-primary"><?= $fecha_editado ?></span> &middot;
                Hace: <span class="text-primary"><?= $tiempo_hace ?></span> &middot;
                <?php if ( $this->session->userdata('rol_id') <= 2 ) { ?>
    
                    Puntaje: <span class="text-primary"><?= $res_usuario['porcentaje'] ?>%</span> &middot; 
                    Rango: <span class="text-primary"><?= $texto_rango[$rango_usuario] ?></span>
                <?php } ?>
            </p>
        </div>
    </div>
</div>

<div id="resultadosApp">

    <ul class="nav justify-content-center nav-pills mb-2">
        <li class="nav-item">
            <a class="nav-link pointer" v-bind:class="{'active': seccion == 'resumen' }" aria-current="page" v-on:click="seccion = 'resumen'">Resumen</a>
        </li>
        <li class="nav-item">
            <a class="nav-link pointer" v-bind:class="{'active': seccion == 'respuestas' }" v-on:click="seccion = 'respuestas'">Respuestas</a>
        </li>
    </ul>

    <div class="center_box_920">
        <?php if ( $this->session->userdata('role') <= 1 ) : ?>
            <div class="mb-2">
                <button class="btn btn-info w120p" v-on:click="recalificar()">
                    <i class="fa fa-sync mr-2"></i>
                    Recalificar
                </button>
            </div>
        <?php endif; ?>
        <table class="table bg-white" v-show="seccion == 'resumen'">
            <thead>
                <th width="10px">#</th>
                <th>Habilidad</th>
                <th>Proceso</th>
                <th width="12%" class="text-center">Básico</th>
                <th width="12%" class="text-center">Medio</th>
                <th width="12%" class="text-center">Avanzado</th>
            </thead>
            <tbody>
                <tr>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td class="text-center">
                        <span class="badge bg-warning w1">
                            {{ cantidadRespuesta(1) }}
                        </span>
                    </td>
                    <td class="text-center">
                        <span class="badge bg-primary w1 text-white">
                            {{ cantidadRespuesta(2) }}
                        </span>
                    </td>
                    <td class="text-center">
                        <span class="badge bg-success w1 text-white">
                            {{ cantidadRespuesta(3) }}
                        </span>
                    </td>
                </tr>
                <tr v-for="(respuesta, key) in respuestas">
                    <td>{{ key + 1 }}</td>
                    <td v-bind:class="textToClass(respuesta.habilidad, `habilidad`)">
                        {{ respuesta.habilidad }}
                    </td>
                    <td>{{ respuesta.proceso_pensamiento }}</td>
                    <td class="text-center">
                        <span v-if="respuesta.respuesta == '1'"><i class="fas fa-check-circle text-warning"></i></span>
                    </td>
                    <td class="text-center">
                        <span v-if="respuesta.respuesta == '2'"><i class="fas fa-check-circle text-primary"></i></span>
                    </td>
                    <td class="text-center">
                        <span v-if="respuesta.respuesta == '3'"><i class="fas fa-check-circle text-success"></i></span>
                    </td>
                </tr>
            </tbody>
        </table>

        <!-- RESPUESTAS -->
        <div class="card mb-2" v-for="(respuesta, kp) in respuestas"
            v-bind:class="{ 'border-danger': respuesta.respuesta_correcta != respuesta.respuesta }"
            v-show="seccion == 'respuestas'"
            >
            <div class="card-body">
                <p>
                    {{ parseInt(kp) + 1 }}) <span v-html="respuesta.texto_pregunta"></span>
                </p>
                <div class="row">
                    <div class="col-md-6">
                        <span class="text-muted">Respuesta estudiante:</>
                        <br>
                        <span><i class="fa fa-info-circle text-primary"></i></span>
                        
                        <strong class="text-primary">
                            <span v-show="respuesta.respuesta == 1">{{ respuesta.opcion_1 }}</span>  
                            <span v-show="respuesta.respuesta == 2">{{ respuesta.opcion_2 }}</span>  
                            <span v-show="respuesta.respuesta == 3">{{ respuesta.opcion_3 }}</span>  
                        </strong>
                    </div>
                    <div class="col-md-6">
                        <span class="text-muted">Resultado:</span>
                        <br>
                        <span v-if="respuesta.respuesta == '1'"><i class="fas fa-check-circle text-warning"></i> Básico</span>
                        <span v-if="respuesta.respuesta == '2'"><i class="fas fa-check-circle text-primary"></i> Medio</span>
                        <span v-if="respuesta.respuesta == '3'"><i class="fas fa-check-circle text-success"></i> Avanzado</span>
                        <br>
                        <div>
                            <b class="text-primary">Logro</b>: <br> {{ getLogro(respuesta) }}
                            <br>
                            <b class="text-primary">Estrategia</b>: <br> {{ getLogro(respuesta, 'estrategia') }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
var resultadosApp = new Vue({
    el: '#resultadosApp',
    data: {
        seccion: 'resumen',
        usuario_id: '<?= $row->id ?>',
        cuestionario: <?= json_encode($row_cuestionario) ?>,
        uc_id: '<?= $row_uc->id ?>',
        respuestas: <?= json_encode($respuestas_cuestionario->result()) ?>,
        porcentaje: <?= intval($res_usuario['porcentaje']) ?>,
        logros: dataLogros,
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
        textToClass: function(text, prefix = null){
            if ( prefix == null) {
                return Pcrn.textToClass(text)
            }
            return prefix + '-' + Pcrn.textToClass(text)
        },
        cantidadRespuesta: function(opcionRespuesta){
            var respuestasFiltradas = this.respuestas.filter(respuesta => respuesta.respuesta == opcionRespuesta)
            return respuestasFiltradas.length
        },
        getLogro: function(respuesta, campo = 'logro') {
            // Determinar el nivel de respuesta
            const niveles = { 1: 'Básico', 2: 'Medio', 3: 'Avanzado' };
            const nivelRespuesta = niveles[respuesta.respuesta] || 'Básico';

            // Filtrar los logros en una sola operación
            const logros = this.logros.find(logro =>
                logro.nivel_respuesta == nivelRespuesta &&
                logro.area_id == this.cuestionario.area_id &&
                logro.nivel == this.cuestionario.nivel &&
                logro.habilidad == respuesta.habilidad &&
                logro.proceso_pensamiento == respuesta.proceso_pensamiento
            );

            // Devolver el campo deseado si existe un logro coincidente
            return logros ? logros[campo] : '-';
        }


    }
})
</script>