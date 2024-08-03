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
                        <div v-html="currentUnidad.html"></div>
                        
                    </div>
                </div>
            </div>

            <div v-show="section == 'portada-unidad'" class="container">
                <?php $this->load->view('flipbooks/lectura/6_articulos_demo/portada_unidad_v') ?>
            </div>

            <div v-show="section == 'preguntas-abiertas'" class="container">
                <?php $this->load->view('flipbooks/lectura/6_articulos_demo/preguntas_abiertas_v') ?>
            </div>

            <div v-show="section == 'chat-ia'" class="container">
                <iframe src="<?= URL_APP ?>enfoque_lector/chat_ele" frameborder="0" style="width: 100%; height: calc(100vh - 60px);"></iframe>
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