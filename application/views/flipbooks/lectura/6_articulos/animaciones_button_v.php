<a 
    v-for="animacion in bookData.animaciones"
    data-bs-toggle="modal"
    v-bind:data-bs-target="'#modal_' + animacion.archivo_id"
    title="Recurso de animacion"
    v-show="animacion.tema_id == currentArticulo.tema_id"
    class="btn btn-light" type="button"
    >
    <img v-bind:src="'<?= URL_IMG . 'flipbook/' ?>' + animacion.icono">
</a>