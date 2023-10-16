<footer class="fixed-bottom footer d-flex justify-content-center">

    <a class="btn btn-light me-1" href="<?= base_url() ?>">
        <i class="fas fa-home"></i>
    </a>
    <button class="btn btn-light me-1" data-bs-toggle="offcanvas" data-bs-target="#offcanvasAnotacion" aria-controls="offcanvasAnotacion">
        <i class="fa-regular fa-comment"></i>
        Notas
    </button>
    <div>
        <?php $this->load->view('flipbooks/lectura/6_articulos/audios_button_v') ?>
        <?php $this->load->view('flipbooks/lectura/6_articulos/animaciones_button_v') ?>
        <?php $this->load->view('flipbooks/lectura/6_articulos/quices_button_v') ?>

        <!-- Links asociados al tema -->
        <div class="btn-group dropup">
            <button type="button" class="btn btn-light dropdown-toggle" data-bs-toggle="dropdown"
                aria-expanded="false" v-bind:disabled="filteredLinks.length == 0">
                <span class="badge rounded-pill bg-info">
                    {{ filteredLinks.length }}
                </span>
                Enlaces 
            </button>
            <ul class="dropdown-menu">
                <li v-for="link in filteredLinks">
                    <a class="dropdown-item" v-bind:href="link.url" target="_blank">{{ link.titulo }}</a>
                </li>
            </ul>
        </div>
    </div>

    <?php if ( $es_profesor ) : ?>
        <button class="btn btn-light me-1" data-bs-toggle="modal" data-bs-target="#modalPreguntasAbiertas" title="Escribe">
            <i class="fa-solid fa-pencil me-1"></i>
            <span class="only-lg">Escribe</span>
        </button>
        <a class="btn btn-light" href="<?= base_url("flipbooks/programar_temas/{$row->id}") ?>" target="_blank" title="Programar fechas a los temas de este contenido">
            <i class="fa-regular fa-calendar-check me-1"></i>
        </a>
        <a class="btn btn-light" href="<?= base_url("flipbooks/crear_cuestionario/{$row->id}") ?>" target="_blank" title="Crear un cuestionario con los temas de este contenido">
            <i class="fas fa-question"></i>
        </a>
        <a class="btn btn-light" href="<?= base_url('eventos/calendario') ?>" target="_blank" title="Calendario programador">
            <i class="fa-regular fa-calendar-days me-1"></i>
        </a>
    <?php endif; ?>
</footer>