<div class="table-responsive">
    <table class="table bg-white">
        <thead>
            <th>Artículo de ayuda</th>
            <th width="200px">Módulo</th>
        </thead>
        <tbody>
            <tr v-for="(element, key) in list" v-bind:id="`row_` + element.id">

                    
                <td>
                    <a v-bind:href="`<?php echo base_url("posts/open/") ?>` + element.id">
                        {{ element.nombre_post }}
                    </a>
                    <p>{{ element.resumen }}</p>
                </td>
                <td>{{ element.texto_1 }} / {{ element.texto_2 }}</td>
            </tr>
        </tbody>
    </table>
</div>