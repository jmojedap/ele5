<div id="flipbookApp">
    <div class="contenido-layout">
        <div class="column-left">
            <?php $this->load->view('flipbooks/lectura/6_articulos/sidebar_v') ?>
        </div>
        <div class="column-right">
            <div class="articulo-container" v-show="section == 'pagina'">
                <div class="articulo-tema">
                    <div class="text-center" v-show="loading">
                        <div class="spinner-border text-danger" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                    </div>
                    <div v-show="!loading">
                        <h1 class="articulo-titulo">{{ currentArticulo.titulo }}</h1>
                        <p class="subtitulo">{{ currentArticulo.subtitle }}</p>
                        <div v-show="currentArticulo.resumen.length > 1">
                            <p class="epigrafe">{{ currentArticulo.resumen }}</p>
                        </div>
                        <div class="contenido" v-html="currentArticulo.contenido"></div>
                    </div>
                </div>
            </div>

            <div v-show="section == 'preguntas-abiertas'" class="container">
                <?php $this->load->view('flipbooks/lectura/6_articulos/preguntas_abiertas_v') ?>
            </div>

            <?php $this->load->view('flipbooks/lectura/6_articulos/anotaciones_v') ?>
    
            <?php $this->load->view('flipbooks/lectura/6_articulos/audios_modal_v') ?>
            <?php $this->load->view('flipbooks/lectura/6_articulos/animaciones_modal_v') ?>
        </div>
    </div>
    <?php $this->load->view('flipbooks/lectura/6_articulos/sidebar_mobile_v') ?>
    <?php $this->load->view('flipbooks/lectura/6_articulos/footer_v') ?>
</div>

<?php $this->load->view('flipbooks/lectura/6_articulos/vue_v') ?>