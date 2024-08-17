<div class="offcanvas offcanvas-start" tabindex="-1" id="sidebar-mobile" aria-labelledby="sidebar-mobile">
  <div class="offcanvas-header">
    <h5 class="offcanvas-title" id="offcanvasScrollingLabel">Temas</h5>
    <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
  </div>
  <div class="accordion" id="accordionIndex">
    <div class="accordion-item" v-for="unidad in unidades">
      <h2 class="accordion-header" v-bind:id="`heading-unidad` + unidad.id">
        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" 
          v-bind:data-bs-target="`#collapse-unidad-` + unidad.id" aria-expanded="false" v-bind:aria-controls="`#collapse-unidad-` + unidad.id">
          {{ unidad.nombre }}
        </button>
      </h2>
      <div v-bind:id="`collapse-unidad-` + unidad.id" class="accordion-collapse collapse" aria-labelledby="headingTwo" data-bs-parent="#accordionIndex">
        <div v-for="(articulo, ka) in bookData.articulos" class="tema-link" v-show="articulo.unidad == unidad.id"
          v-on:click="getArticulo(articulo.articulo_id, ka)" v-bind:class="{'active': articulo.articulo_id == currentArticulo.id }"
          data-bs-dismiss="offcanvas" aria-label="Close"
          >
          {{ articulo.titulo }}
        </div>
      </div>
    </div>
  </div>
</div>
