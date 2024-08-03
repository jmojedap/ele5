<div class="d-flex justify-content-between mb-1">
    <div v-for="unidad in bookData.unidades" :key="unidad.unidad_id" class="numero-unidad" v-bind:title="unidad.titulo"
        v-bind:class="{'active': unidad.numero == currentUnidad.numero }"
        v-on:click="setUnidad(unidad)">
        U{{ unidad.numero }}
    </div>
</div>
<div class="d-flex flex-column">
    <a v-for="(articulo, ka) in bookData.articulos" class="articulo-link w-100" v-show="articulo.unidad == currentUnidad.numero"
        v-on:click="setArticulo(articulo.articulo_id, ka)"
        v-bind:href="`#articulo-` + articulo.articulo_id"
        v-bind:class="{'active': articulo.articulo_id == articuloId }">
        {{ articulo.titulo }}
    </a>
</div>