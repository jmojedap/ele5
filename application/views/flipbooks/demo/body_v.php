<style>
    body{
        background: rgb(39,216,218);
        background: radial-gradient(circle, rgba(39,216,218,1) 0%, rgba(4,189,191,1) 38%, rgba(33,169,170,1) 100%);
    }
</style>

<body>
    <div id="flipbook">
    <div id="flipbook">
        <div class="container">
            <!-- BARRA DE HERRAMIENTAS-->
            <div class="row">

                <div class="col-md-offset-2 col-md-7">

                    <div class="text-center">
                        <div class="w2 pull-left boton_fb btn btn-default" v-on:click="pagina_ant"><i class="fa fa-caret-left"></i></div>

                        <button
                            id="alternar_menu_recursos"
                            class="w2 boton_fb btn btn-default hidden-md hidden-lg"
                            v-on:click="alternar_menu_recursos"
                            >
                            <i class="fa fa-files-o"></i>
                        </button>
                        <button 
                            class="w2 boton_fb btn"
                            id="mostrar_indice"
                            title="Ver índice del Contenido"
                            v-on:click="alternar_indice"
                            v-bind:class="[ver_indice ? 'btn-warning' : '', 'btn-default']"
                            >
                            <i class="fa fa-list"></i>
                        </button>
                        <button
                            class="w2 btn"
                            title="Separador en esta página"
                            v-on:click="establecer_bookmark"
                            v-bind:class="clase_bookmark()"
                            >
                            <i class="fa fa-bookmark"></i>
                            {{ parseInt(num_pagina) + 1 }}
                        </button>

                        <div class="w2 pull-right boton_fb btn btn-default" v-on:click="pagina_sig"><i class="fa fa-caret-right"></i></div>
                    </div>
                    
                    <input
                        type="range"
                        min="0" max="<?php echo $row->num_paginas - 1 ?>"
                        value="<?php echo $bookmark ?>"
                        v-model="num_pagina"
                        v-on:change="cambiar_pagina"
                        style="margin-top: 5px;"
                        >

                </div>

                <div class="col-md-3"></div>
            </div>
            
            <!-- SECCIÓN CONTENIDO -->
            <div class="row seccion_contenido">
                
                <!-- SECCIÓN DE RECURSOS -->
                <div class="col col-md-2">
                    <div id="menu_recursos" class="hidden-xs hidden-sm">
                        <a id="btn_texto_enri" href="#" class="btn btn-default btn-block" style="margin-bottom: 5px;" v-on:click="alt_ver_preguntas">
                            <i class="fa fa-pencil"></i>
                            Texto enriquecido
                        </a>

                        <!-- DETonante DE CONOCIMIENTO -->
                        <a id="btn_lectura" href="#lectura" class="btn btn-default btn-block" style="margin-bottom: 5px;" data-toggle="modal" data-target="#modal_detonante">
                            <i class="fa fa-book"></i>
                            Lectura
                        </a>

                        <!-- Modal -->
                        <div class="modal fade" id="modal_detonante" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                            <div class="modal-dialog modal-lg" role="document">
                                <div class="modal-content">
                                <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                    <h4 class="modal-title" id="myModalLabel">Detonante de conocimiento</h4>
                                </div>
                                <div class="modal-body">
                                    <img src="<?php echo URL_IMG . 'demo/detonante.jpg' ?>" alt="Imagen detonante" width="100%">
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
                                </div>
                                </div>
                            </div>
                        </div>

                        <!-- FIN DETonante DE CONOCIMIENTO -->

                        <a id="btn_ova" href="#ova" data-toggle="collapse" aria-expanded="true" class="btn btn-default btn-block" style="margin-bottom: 5px;">
                            O.V.A.
                        </a>
                        <a id="btn_aprueba" href="#aprueba" data-toggle="collapse" aria-expanded="true" class="btn btn-default btn-block" style="margin-bottom: 5px;">
                            Aprueba
                        </a>

                        <div class="list-group" v-show="ver_preguntas">
                            <a href="#"
                                class="list-group-item"
                                v-for="(pregunta, key_pregunta) in preguntas"
                                v-on:click="seleccionar_pregunta(key_pregunta)"
                                >
                                {{ pregunta }}
                            </a>
                        </div>
                    </div>
                </div>
                
                <!-- IMAGEN PÁGINA E ÍNDICE -->
                <div class="col col-md-7 seccion_pagina">

                    
                    <div class="text-center" v-show="!ver_indice">
                        <img
                            id="img_pagina"
                            class="pf_v4"
                            style="max-height: 800px; max-width: 100%;"
                            onError="this.src='<?php echo URL_IMG . 'app/pf_nd_3.png' ?>'"
                            v-bind:src="carpeta_uploads + 'pf_zoom/' + pagina.archivo_imagen"
                        >
                    </div>

                    <!-- ÍNDICE -->
                    <div id="indice_flipbook" v-show="ver_indice"> 
                        <div id="titulo_indice">
                            <h3 class="text-center"><?php echo $titulo_pagina ?></h3>
                        </div>

                        <div id="elementos_indice">
                            <div class="row">    
                                <div class="col-md-6" v-for="pagina in data.indice">
                                    <a 
                                        class="link_indice pull-left"
                                        v-on:click="ir_a_pagina(pagina.num_pagina)"
                                       >
                                        <span class="badge badge-primary">{{ parseInt(pagina.num_pagina) + 1 }}</span>
                                        <span class="a5_no">{{ pagina.nombre_tema }}</span>
                                    </a>    
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- FORMULARIO DE ANOTACIONES -->
                <div class="col col-md-3">
                    <a id="btn_lectura" href="#lectura" data-toggle="collapse" aria-expanded="true" class="btn btn-default btn-block" style="margin-bottom: 5px;">
                        <i class="fa fa-calendar"></i>
                        Programador
                    </a>
                    <a id="btn_lectura" href="#lectura" class="btn btn-default btn-block" style="margin-bottom: 5px;" >
                        <i class="fa fa-question"></i>
                        Cuestionario
                    </a>
                    <a id="btn_lectura" href="#lectura" data-toggle="collapse" aria-expanded="true" class="btn btn-default btn-block" style="margin-bottom: 5px;">
                        Planeador
                    </a>
                    <form accept-charset="utf-8" @submit.prevent="guardar_anotacion" v-show="ver_preguntas">
                        <div class="sep2">
                            <textarea
                                id="anotacion"
                                rows="10"
                                class="anotacion"
                                placeholder="Escribe una anotación sobre este tema"
                                required
                                v-model="anotacion"
                                >
                            </textarea>
                        </div>
                        <div class="sep2">
                            <button class="btn btn-primary btn-block" type="submit">
                                <i class="fa fa-save"></i>
                                Guardar
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <?php $this->load->view('flipbooks/demo/vue_v'); ?>
    </div>
</body>