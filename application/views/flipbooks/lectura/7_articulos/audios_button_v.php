<a 
    v-for="audio in bookData.audios"
    data-bs-toggle="modal"
    v-bind:data-bs-target="'#modal_' + audio.archivo_id"
    title="Recurso de audio"
    v-show="audio.tema_id == currentArticulo.tema_id"
    class="btn btn-light" type="button"
    >
    <img v-bind:src="'<?= URL_IMG . 'flipbook/' ?>' + audio.icono">
</a>