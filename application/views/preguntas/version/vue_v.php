<script>
    form_values = <?php echo json_encode($row_version) ?>;
    form_values.enunciado_id = '0<?php echo $row_version->enunciado_id ?>';
    form_values.respuesta_correcta = '0<?php echo $row_version->respuesta_correcta ?>';
    form_values.nivel = '0<?php echo $row_version->nivel ?>';
    form_values.area_id = '0<?php echo $row_version->area_id ?>';
    form_values.competencia_id = '0<?php echo $row_version->competencia_id ?>';
    form_values.componente_id = '0<?php echo $row_version->componente_id ?>';

    new Vue({
        el: '#edicion_pregunta',
        data: {
            app_url: '<?php echo base_url() ?>',
            pregunta_id: <?php echo $row->id ?>,
            version_id: <?php echo $row_version->id ?>,
            form_values: form_values,
            pregunta: <?php echo json_encode($row) ?>
        },
        methods: {
            send_form: function(){
                axios.post(this.app_url + 'preguntas/save/' + this.version_id, $('#pregunta_form').serialize())
                .then(response => {
                    toastr["success"](response.data.message);
                })
                .catch(function (error) {
                    console.log(error);
                });
            },   
        }
    });
</script>