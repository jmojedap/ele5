<?php
    $arrGrupos = [];
    $arrEstudiantes = [];
    foreach ($grupos->result() as $row_grupo) {
        $grupo['id'] = $row_grupo->id;
        $grupo['nombre'] = $row_grupo->nombre_grupo;
        $grupo['selected'] = false;

        //Identificar estudiantes
        $estudiantes = $this->Grupo_model->estudiantes($row_grupo->id, 'nombre NOT LIKE "*" AND apellidos NOT LIKE "*"');
        foreach ($estudiantes->result() as $row_estudiante) {
            $condicion = "usuario_id = {$row_estudiante->id} AND cuestionario_id = {$row->id}";
            $row_uc = $this->Db_model->row('usuario_cuestionario', $condicion);

            $estudiante['id'] = $row_estudiante->id;
            $estudiante['grupo_id'] = $row_grupo->id;
            $estudiante['nombre_grupo'] = $row_grupo->nombre_grupo;
            $estudiante['display_name'] = $this->App_model->nombre_usuario($row_estudiante->id, 3);
            $estudiante['selected'] = false;
            $estudiante['assigned'] = (is_null($row_uc)) ? false : true ;

            $arrEstudiantes[] = $estudiante;
        }

        $arrGrupos[] = $grupo;
    }
?>

<script>
// Variables
//-----------------------------------------------------------------------------
    var startGrupo = {id:0,name:''} //Valor por defecto
    var grupos = <?= json_encode($arrGrupos) ?>;    //Info de grupos
    var grupoId = <?= $grupo_id ?>; //ID grupo inicial

    //Si hay grupo definido
    if ( grupoId > 0 ) {
        //Buscar grupo y asignar valor
        startGrupo = grupos.find(grupo => grupo.id == grupoId);
        if ( startGrupo == undefined ) {
            startGrupo = {id:0,name:''}
        }
    } else {
        //Si no hay ID de grupo, seleccionar el primero
        if ( grupos.length > 0 ) {
            startGrupo = grupos[0]
        }
    }


// VueApp
//-----------------------------------------------------------------------------
var asignarCuestionarioApp = new Vue({
    el: '#asignarCuestionarioApp',
    created: function(){
        //this.get_list()
    },
    data: {
        url_base: '<?= base_url() ?>',
        cuestionario_id: <?= $row->id ?>,
        loading: false,
        instituciones: <?= json_encode($instituciones) ?>,
        institucionId: <?= $institucion_id ?>,
        grupos: grupos,
        currGrupo: startGrupo,
        estudiantes: <?= json_encode($arrEstudiantes) ?>,
        allSelected: false,
        fields: {
            tiempo_minutos: '<?= $row->tiempo_minutos ?>',
            fecha_inicio: '<?= date('Y-m-d') ?>',
            fecha_fin: '<?= date('Y-m-d', strtotime('+7 days')) ?>',
        },

    },
    methods: {
        setStartGrupo: function(){
            var startGrupo
        },
        handleSubmit: function(){
            this.loading = true
            var formValues = new FormData(document.getElementById('asignarForm'))
            axios.post(this.url_base + 'cuestionarios/asignar_e/' + this.cuestionario_id, formValues)
            .then(response => {
                if ( parseInt(response.data.qty_inserted) > 0 ) {
                    toastr['success'](response.data.qty_inserted + ' asignaciones realizadas')
                    toastr['info']('Actualizando datos...')
                    setTimeout(() => {
                        window.location = this.url_base + 'cuestionarios/asignar/' + this.cuestionario_id + '/' + this.currGrupo.id
                    }, 2000);
                } else {
                    toastr['warning']('No se realizaron asignaciones de cuestionario')
                }
                this.loading = false
            })
            .catch( function(error) {console.log(error)} )
        },
        setCurrGrupo: function(key){
            this.currGrupo = this.grupos[key];
            history.pushState(null, null, this.url_base + 'cuestionarios/asignar/' + this.cuestionario_id + '/' + this.institucionId +  '/' + this.currGrupo.id);
            console.log(this.currGrupo)
        },
        selectGrupo: function(keyGrupo){
            var grupo = this.grupos[keyGrupo]
            this.estudiantes.estudiantes.forEach(estudiante => {
                if ( estudiante.grupo_id == grupo.id ) {
                    estudiante.selected = grupo.selected
                }
            });
        },
        selectAll: function(key) {
            var grupo = this.grupos[key]
            console.log(grupo.id)
            this.estudiantes.forEach((estudiante,index) => {
                if ( estudiante.grupo_id == grupo.id && estudiante.assigned == false ) {
                    this.estudiantes[index].selected = grupo.selected
                }
            });
        },
        setInstitucion: function(){
            window.location = this.url_base + 'cuestionarios/asignar/' + this.cuestionario_id + '/' + this.institucionId
        },
    },
    computed:{
        gruposSeleccionados: function(){
            var gruposSeleccionados = []
            this.grupos.forEach(grupo => {
                if ( grupo.selected == true ) {
                    gruposSeleccionados.push(grupo.id)
                }
            });
            return gruposSeleccionados
        },
        estudiantesSeleccionados: function(){
            var estudiantesSeleccionados = []
            this.estudiantes.forEach(estudiante => {
                if ( estudiante.selected == true ) {
                    estudiantesSeleccionados.push(estudiante.id)
                }
            });
            return estudiantesSeleccionados
        }
        
    }
})
</script>