<div id="flipbookApp">
    <div class="contenido-layout">
        <div class="column-left">
            <?php $this->load->view('flipbooks/lectura/6_articulos/sidebar_v') ?>
        </div>
        <div class="column-right">
            <div class="articulo-container">
                <div class="articulo-tema">
                    <div class="text-center" v-show="loading">
                        <div class="spinner-border text-danger" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                    </div>
                    <div v-show="!loading">
                        <p class="text-center text-muted">{{ numPage }}</p>
                        <h1 class="articulo-titulo">{{ currentArticulo.titulo }}</h1>
                        <p class="subtitulo">{{ currentArticulo.subtitle }}</p>
                        <p class="epigrafe">{{ currentArticulo.resumen }}</p>
                        <div class="contenido" v-html="currentArticulo.contenido"></div>
                    </div>
                </div>
            </div>
            <div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasAnotacion"
                aria-labelledby="offcanvasRightLabel">
                <div class="offcanvas-header">
                    <h5 class="offcanvas-title" id="offcanvasRightLabel">Notas sobre este tema</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
                </div>
                <div class="offcanvas-body">
                    <form accept-charset="utf-8" id="anotacion-form" @submit.prevent="guardarAnotacion"
                        v-show="anotacion.calificacion == 0">
                        <div class="mb-2">
                            <textarea rows="7" name="anotacion" class="form-control"
                                placeholder="Escribe aquí una anotación sobre este tema" required
                                v-model="anotacion.anotacion">
                            </textarea>
                        </div>
                        <div class="mb-2">
                            <button class="btn btn-primary" type="submit">
                                <i class="fa fa-save"></i>
                                Guardar
                            </button>
                        </div>
                    </form>
                </div>
            </div>
    
            <div class="p-2">
            </div>
    
            <?php $this->load->view('flipbooks/lectura/6_articulos/audios_modal_v') ?>
            <?php $this->load->view('flipbooks/lectura/6_articulos/animaciones_modal_v') ?>
            <?php $this->load->view('flipbooks/lectura/6_articulos/preguntas_abiertas_v') ?>
        </div>
    </div>
    <?php $this->load->view('flipbooks/lectura/6_articulos/footer_v') ?>
</div>

<?php $this->load->view('flipbooks/lectura/6_articulos/vue_v') ?>