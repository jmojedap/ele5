<div id="infoProgramaApp" class="center_box_750">
    <div class="card">
        <div class="card-body">
            <h3>{{ programa.nombre_programa }}</h3>
            <table class="table">
                <tbody>
                    <tr>
                        <td class="text-right text-primary" width="30%">Descripción</td>
                        <td>{{ programa.descripcion }}</td>
                    </tr>
                    <tr>
                        <td class="text-right text-primary">Año generación</td>
                        <td>{{ programa.anio_generacion }}</td>
                    </tr>
                    <tr>
                        <td class="text-right text-primary">Área</td>
                        <td><?= $this->Item_model->nombre_id($row->area_id); ?></td>
                    </tr>
                    <tr>
                        <td class="text-right text-primary">Nivel</td>
                        <td><?= $this->Item_model->name(3,$row->nivel); ?></td>
                    </tr>
                    <tr>
                        <td class="text-right text-primary">Cantidad unidades</td>
                        <td><?= $row->cantidad_unidades; ?></td>
                    </tr>
                    <tr>
                        <td class="text-right text-primary">Cantidad temas</td>
                        <td>{{ temas.length }}</td>
                    </tr>
                    <tr>
                        <td class="text-right text-primary">Actualizado</td>
                        <td>
                            <?= $this->pml->date_format($row->editado, 'Y-M-d'); ?>
                            &middot;
                            <?= $this->pml->ago($row->editado); ?>
                        </td>
                    </tr>
                    <tr>
                        <td class="text-right text-primary">Creado</td>
                        <td>
                            <?= $this->pml->date_format($row->creado, 'Y-M-d'); ?>
                            &middot;
                            <?= $this->pml->ago($row->creado); ?>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
var infoProgramaApp = new Vue({
    el: '#infoProgramaApp',
    created: function(){
        //this.get_list()
    },
    data: {
        programa: <?= json_encode($row) ?>,
        temas: <?= json_encode($temas->result()) ?>,
        loading: false,
    },
    methods: {
        
    }
})
</script>