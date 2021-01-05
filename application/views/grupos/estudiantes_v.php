<?php
    $show_payment = 0;
    if ( in_array($this->session->userdata('rol_id'), array(0,1,2,8)) ) $show_payment = 1;
?>

<div id="estudiantes_app">
    <div class="table-responsive">
        <table class="table bg-white">
            <thead>
                <th>Estudiante</th>
                <th>Username</th>
                <th>Estado</th>
                <th>Pago</th>
            </thead>

            <tbody>
                <tr v-for="(element, key) in list">
                    <td>
                        <a v-bind:href="`<?php echo base_url("usuarios/actividad/") ?>` + element.id + `/1`" class="">
                            {{ element.apellidos }} {{ element.nombre }}
                        </a>
                    </td>
                    <td>
                        {{ element.username }}
                    </td>
                    <td>
                        <span v-show="element.estado == 0"><i class="fas fa-circle text-danger"></i> Inactivo</span>
                        <span v-show="element.estado == 1"><i class="fa fa-check-circle text-success"></i> Activo</span>
                        <span v-show="element.estado == 2"><i class="fas fa-minus-circle text-warning"></i> Temporal</span>
                    </td>
                    <td>
                        <div class="dropdown" v-if="show_payment">
                            <button class="btn dropdown-toggle btn-sm btn-danger w50p" v-show="element.pago == 0" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                No
                            </button>
                            <button class="btn dropdown-toggle btn-sm btn-light w50p" v-show="element.pago == 1" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                Sí
                            </button>

                            <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                <a class="dropdown-item" href="#" v-on:click="set_payment(key, 1)">Sí</a>
                                <a class="dropdown-item" href="#" v-on:click="set_payment(key, 0)" v-if="app_rid <= 1">No</a>
                            </div>
                        </div>
                        <div v-else>
                            <span v-if="element.pago == 0">Sí</span>
                            <span v-if="element.pago == 1">No</span>
                        </div>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>

<script>
    new Vue({
        el: '#estudiantes_app',
        created: function(){
            //this.get_list();
        },
        data: {
            list: <?= json_encode($estudiantes->result()) ?>,
            show_payment: <?= $show_payment ?>,
            app_rid: app_rid
        },
        methods: {
            set_payment: function(key, payment){
                axios.get(url_api + 'usuarios/establecer_pago/' + this.list[key].id + '/' + payment)
                .then(response => {
                    if ( response.data.affected_rows > 0 ) {
                        this.list[key].pago = payment
                        this.list[key].estado = response.data.arr_row.estado
                        toastr['success']('Se modificó el estado del pago del usuario')
                    }
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
        }
    });
</script>