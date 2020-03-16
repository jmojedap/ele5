<!-- Modal -->
<div class="modal fade" id="detail_modal" tabindex="-1" role="dialog" aria-labelledby="detail_modal_label" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="detail_modal_label">Pregunta {{ element.id }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <table class="table">
                    <tr>
                        <td>ID</td>
                        <td>{{ element.id }}</td>
                    </tr>
                    <tr>
                        <td>Estadísticas</td>
                        <td>
                            <span class="badge badge-success w1">{{ element.qty_right }}</span> correctas de 
                            <span class="badge badge-primary w1">{{ element.qty_answers }}</span> veces respondida
                        </td>
                    </tr>
                    <tr>
                        <td>Pregunta</td>
                        <td>
                            <p v-html="element.texto_pregunta"></p>
                            <p v-html="element.enunciado_2"></p>
                        </td>
                    </tr>
                    <tr>
                        <td>Opciónes</td>
                        <td>
                            <ul style="list-style-type:none;">
                                <li>
                                    <span class="badge badge-secondary" v-bind:class="{'badge-success': element.respuesta_correcta == 1 }">
                                        A
                                    </span>
                                    {{ element.opcion_1 }}
                                    <i class="fa fa-check-circle text-success" v-if="element.respuesta_correcta == 1"></i>
                                </li>
                                <li>
                                    <span class="badge badge-secondary" v-bind:class="{'badge-success': element.respuesta_correcta == 2 }">
                                        B
                                    </span>
                                    {{ element.opcion_2 }}
                                    <i class="fa fa-check-circle text-success" v-if="element.respuesta_correcta == 2"></i>
                                </li>
                                <li>
                                    <span class="badge badge-secondary" v-bind:class="{'badge-success': element.respuesta_correcta == 3 }">
                                        C
                                    </span>
                                    {{ element.opcion_3 }}
                                    <i class="fa fa-check-circle text-success" v-if="element.respuesta_correcta == 3"></i>
                                </li>
                                <li>
                                    <span class="badge badge-secondary" v-bind:class="{'badge-success': element.respuesta_correcta == 4 }">
                                        D
                                    </span>
                                    {{ element.opcion_4 }}
                                    <i class="fa fa-check-circle text-success" v-if="element.respuesta_correcta == 4"></i>
                                </li>
                            </ul>
                        </td>
                    </tr>
                </table>
                <p>
                    {{ element.excerpt }}
                </p>
            </div>
            <div class="modal-footer">
                <a class="btn btn-primary w3" v-bind:href="`<?php echo base_url('preguntas/index/') ?>` + element.id">Abrir</a>
                <button type="button" class="btn btn-secondary w3" data-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>