<div id="articulosApp">
    <div class="center_box_920">
        <?php $this->load->view('admin/temas/articulos/add_v') ?>

        <table class="table bg-white" v-show="section=='list'">
            <thead>
                <th>Artículo</th>
                <th>Estado</th>
                <th width="95px" class="text-right text-end">
                    <button class="btn btn-primary btn-sm" v-on:click="section='add'">
                        Nuevo
                    </button>
                </th>
            </thead>
            <tbody>
                <tr v-for="(articulo, key) in articulos" v-show="articulo.show == 1" v-bind:class="{'table-info': articulo.id == currentArticulo.id }">
                    <td>
                        <a v-bind:href="`<?= URL_APP . "posts/leer_articulo_tema/" ?>` + articulo.id + `/` + articulo.slug + `/?preview=1`">
                            {{ articulo.nombre_post }}
                        </a>
                    </td>
                    <td>
                        <i class="fa fa-circle-check text-success" v-show="articulo.status==1"></i>
                        <i class="fa fa-circle text-warning" v-show="articulo.status==2"></i>
                        <i class="fa fa-circle text-muted" v-show="articulo.status==5"></i>
                        {{ statusName(articulo.status) }}
                    </td>
                    <td>
                        <a class="a4" v-bind:href="`<?= URL_ADMIN . "posts/edit/" ?>` + articulo.id + `/` + articulo.slug"><i class="fa fa-pencil"></i></a>
                        <button class="a4" v-on:click="setCurrent(key)" data-toggle="modal" data-target="#delete_modal">
                            <i class="fa-solid fa-trash"></i>
                        </button>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
    <?php $this->load->view('common/bs4/modal_single_delete_v') ?>
</div>

<?php $this->load->view('admin/temas/articulos/vue_v') ?>