

<script>
form_values = <?= json_encode($row) ?>;
form_values.enunciado_id = '0<?= $row->enunciado_id ?>';
form_values.respuesta_correcta = '0<?= $row->respuesta_correcta ?>';
form_values.nivel = '0<?= $row->nivel ?>';
form_values.area_id = '0<?= $row->area_id ?>';
form_values.competencia_id = '0<?= $row->competencia_id ?>';
form_values.componente_id = '0<?= $row->componente_id ?>';

var edicion_preguntas = new Vue({
    el: '#edicion_pregunta',
    data: {
        pregunta_id: <?= $row->id ?>,
        form_values: form_values,
        loading: false,
        arrHabilidades: <?= json_encode($arrHabilidades) ?>,
        arrProcesos: <?= json_encode($arrProcesos) ?>,
    },
    methods: {
        send_form: function(){
            this.loading = true
            axios.post(URL_API + 'preguntas/save/' + this.pregunta_id, $('#pregunta_form').serialize())
            .then(response => {
                toastr['success'](response.data.message)
                this.loading = false
            })
            .catch(function (error) {
                console.log(error);
            });
        },   
    }
});
</script>