<script>
    new Vue({
        el: '#preguntas',
        created: function(){
            this.get_list();
        },
        data: {
            app_url: '<?php echo base_url() ?>',
            cuestionario_id: '<?php echo $row->id ?>',
            cant_preguntas: 0,
            lista: [],
            pregunta: []
        },
        methods: {
            get_list: function(){
                axios.get(this.app_url + 'cuestionarios/lista_preguntas/' + this.cuestionario_id)
                .then(response => {
                    this.lista = response.data.lista;
                    this.cant_preguntas = response.data.cant_preguntas;
                    this.pregunta = this.lista[0];
                })
                .catch(function (error) {
                     console.log(error);
                });
            },
            set_current: function(key){
                this.pregunta = this.lista[key];
            },
            create_version: function(key){
                this.set_current(key);
                axios.get(this.app_url + 'preguntas/create_version/' + this.pregunta.pregunta_id)
                .then(response => {
                    if ( response.data.status == 1 ) {
                        window.location = this.app_url + 'preguntas/version/' + response.data.saved_id + '/editar'
                    }
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
        }
    });
</script>