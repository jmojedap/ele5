<?php $this->load->view('assets/vue'); ?>
<?php $this->load->view('head_includes/countdown'); ?>
<link type="text/css" rel="stylesheet" href="<?php echo URL_RECURSOS ?>plantillas/apanel2/cuestionario.css">
<script src="<?php echo URL_RECURSOS . 'js/pcrn.js' ?>"></script>

<script>
    $(document).ready(function ()
    {
        //Tiempo
        $('#the_final_countdown_v4').countdown({until: +<?php echo $segundos_restantes ?>, format: 'HMS'});
    });
</script>

<div id="resolver_cuestionario">
    <div class="row" v-show="! finalizado">
        <div class="col-md-8">
            <!-- Nav tabs -->
            <ul class="nav nav-tabs sep1" role="tablist">
                <li role="presentation" class="active">
                    <a href="#tab_pregunta" aria-controls="pregunta" role="tab" data-toggle="tab">
                        Pregunta {{ pregunta_key + 1 }}
                    </a>
                </li>
                <li role="presentation" v-show="pregunta.enunciado_id">
                    <a href="#tab_enunciado" aria-controls="enunciado" role="tab" data-toggle="tab">
                        <span class="label label-danger">
                            <i class="fa fa-caret-right"></i> Lectura
                        </span>
                    </a>
                </li>
            </ul>

            <!-- Tab panes -->
            <div class="tab-content">

                <div role="tabpanel" class="tab-pane active" id="tab_pregunta">
                    <div class="row" style="margin-bottom: 10px">
                        <div class="col col-md-6">
                            <button class="btn btn-default" v-on:click="borrar_respuesta">
                                <i class="fa fa-eraser"></i>
                                Borrar respuesta
                            </button>
                        </div>
                        <div class="col col-md-6">
                            <button class="btn btn-default w3 pull-right" v-on:click="siguiente_pregunta">
                                <i class="fa fa-chevron-right"></i>
                            </button>
                            <button class="btn btn-default w3 pull-right" v-on:click="anterior_pregunta" style="margin-right: 3px;">
                                <i class="fa fa-chevron-left"></i>
                            </button>

                        </div>
                    </div>

                    <div class="panel panel-default">
                        <div class="panel-body">
                            <p style="font-size: 1.3em;" v-html="pregunta.texto_pregunta">
                            </p>

                            <div class="sep1">
                                <div 
                                    class="panel panel-default opcion_respuesta"
                                    v-for="(clave, key_clave) in clave_opciones"
                                    v-on:click="responder(clave)"
                                    v-bind:class="{'opcion_seleccionada':clave == pregunta.rta - 1}"
                                    >
                                    <div class="panel-body">
                                        <span class="label label-primary w2" style="margin-right: 15px; display: none;">
                                            {{ letras[key_clave] }}
                                        </span>
                                        <div v-html="opciones[clave]"></div>
                                    </div>
                                </div>
                                <p class="hidden">Milisec: {{ milisec }}</p>
                            </div>

                            <!-- Mostrar imagen si la pregunta tiene respuestas en imagen -->
                            <div class="sep1 text-center" v-show="pregunta.archivo_imagen">
                                <div class="thumbnail">
                                    <img
                                        width="100%" style="max-width: 800px"
                                        onerror="this.src='<?php echo URL_IMG ?>app/img_pregunta_nd.png'"
                                        alt="Imagen pregunta"
                                        v-bind:src="pregunta.url_imagen_pregunta"
                                        >
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div role="tabpanel" class="tab-pane" id="tab_enunciado">
                    <div class="panel panel-default" v-show="pregunta.contenido_enunciado">
                        <div class="panel-heading">
                            {{ pregunta.titulo_enunciado }}
                        </div>
                        <div class="panel-body">
                            <div v-html="pregunta.contenido_enunciado"></div>
                            
                            <div v-if="pregunta.archivo_enunciado">
                                <hr/>
                                <div style="margin: 0 auto; max-width: 600px; max-height: 600px;">
                                    <img
                                        width="100%"
                                        style="max-width: 800px"
                                        onError="this.src='<?php echo URL_IMG ?>app/img_pregunta_nd.png'"
                                        v-bind:src="pregunta.url_imagen_enunciado">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
            
            <?php if ( $this->session->userdata('institucion_id') == 41 ) { ?>
                <table class="table table-default bg-blanco">
                    <tbody>
                        <tr class="warning">
                            <td>Pruebas<td>
                            <td>
                                <i class="fa fa-info-circle"></i>
                                Tabla de control para pruebas (Solo aparece para Colegio En Línea Editores)
                            <td>
                        </tr>
                        <tr>
                            <td>clv<td>
                            <td>
                                <span v-for="pregunta in lista">{{ pregunta.clv }}-</span>
                            <td>
                        </tr>
                        <tr>
                            <td>rta<td>
                            <td>{{ respuestas }}<td>
                        </tr>
                        <tr>
                            <td>res<td>
                            <td>{{ resultados }}<td>
                        </tr>
                    </tbody>
                </table>
            
            <?php } ?>
            
            
        </div>
        <div class="col-md-4">
            <div class="panel panel-default">
                <div class="panel-body">
                    <div id="the_final_countdown_v4"></div>
                    <div class="progress">
                        <div 
                            class="progress-bar progress-bar-info"
                            role="progressbar" aria-valuenow="60"
                            aria-valuemin="0"
                            aria-valuemax="100"
                            v-bind:style="{ width: porcentaje + '%' }">
                            {{ porcentaje }}%
                        </div>
                    </div>
                    
                    <a
                        class="btn btn-sm btn-default"
                        style="width: 35px; margin-bottom: 2px; margin-right: 2px;"
                        v-for="(pregunta, key) in lista"
                        v-on:click="seleccionar_pregunta(key)"
                        v-bind:class="{'btn-warning':key == pregunta_key, 'btn-info':pregunta.rta > 0}"
                        >
                        {{ key + 1 }}
                    </a>
                    
                    <div class="sep2 hidden-xs">
                        <ul class="list-group">
                            <li class="list-group-item">
                                <span class="badge">{{ cant_preguntas }}</span>
                                Preguntas
                            </li>
                            <li class="list-group-item">
                                <span class="badge">{{ cant_respondidas }}</span>
                                Respondidas
                            </li>
                            <li class="list-group-item" v-bind:class="{'list-group-item-success':cant_respondidas == cant_preguntas, 'list-group-item-danger': cant_respondidas < cant_preguntas}">
                                <span class="badge">
                                    {{ cant_preguntas - cant_respondidas }}
                                </span>
                                Sin responder
                            </li>
                        </ul>
                    </div>
                    
                    <button class="btn btn-primary btn-block" data-toggle="modal" data-target="#confirmar_finalizar">
                        Finalizar
                    </button>
                </div>
            </div>
        </div>
    </div>
    
    <div class="jumbotron" v-show="finalizado">
        <h1>
            <i class="fa fa-circle-o-notch fa-spin text-success"></i>
            Finalizando
        </h1>
        <p>
            Finalizando cuestionario, por favor espere.
        </p>
    </div>
    
    <?php //Ventana modal Confirma Finalizar ?>

    <div class="modal fade" id="confirmar_finalizar" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="myModalLabel">Plataforma Enlace</h4>
                </div>
                <div class="modal-body" id="mensaje_confirmacion">
                    Tiene <span class="label label-danger">{{ cant_preguntas - cant_respondidas }}</span> preguntas sin responder
                    <br/>
                    ¿Confirma la terminación del cuestionario?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                    <button class="btn btn-danger" v-on:click="guardar_finalizar" title="Finalizar cuestionario" data-dismiss="modal">
                        Finalizar
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
$this->load->view('cuestionarios/resolver/vue_v');