<?php $this->load->view('flipbooks/lectura/6_articulos_demo/style_v') ?>

<div id="flipbookApp">
    <div class="contenido-layout">
        <div class="column-left">
            <?php $this->load->view('flipbooks/lectura/6_articulos_demo/sidebar_v') ?>
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
                <?php $this->load->view('flipbooks/lectura/6_articulos_demo/preguntas_abiertas_v') ?>
            </div>

            <div v-show="section == 'chat-ia'" class="container">
                <?php $this->load->view('flipbooks/lectura/6_articulos_demo/chat_ia_v') ?>
            </div>

            <div v-show="section == 'demo-cuestionarios'" class="container">
                <?php $this->load->view('flipbooks/lectura/6_articulos_demo/section_cuestionarios_v') ?>
            </div>
            <div v-show="section == 'demo-archivos'" class="container">
                <?php $this->load->view('flipbooks/lectura/6_articulos_demo/section_archivos_v') ?>
            </div>

            <?php $this->load->view('flipbooks/lectura/6_articulos_demo/anotaciones_v') ?>
    
            <?php $this->load->view('flipbooks/lectura/6_articulos_demo/audios_modal_v') ?>
            <?php $this->load->view('flipbooks/lectura/6_articulos_demo/animaciones_modal_v') ?>
        </div>
    </div>
    <?php $this->load->view('flipbooks/lectura/6_articulos_demo/sidebar_mobile_v') ?>
    <?php $this->load->view('flipbooks/lectura/6_articulos_demo/footer_v') ?>
</div>

<?php $this->load->view('flipbooks/lectura/6_articulos_demo/vue_v') ?>