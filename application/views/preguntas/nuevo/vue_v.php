<script>
    new Vue({
        el: '#pregunta_nueva',
        data: {
            app_url: '<?php echo base_url() ?>',
            form_destination: '<?php echo $form_destination ?>',
            success_destination: '<?php echo $success_destination ?>',
            pregunta_id: <?php echo $row->id ?>,
            form_values: [],
            loading: false,
            arrHabilidades: <?= json_encode($arrHabilidades) ?>,
            arrProcesos: <?= json_encode($arrProcesos) ?>,
        },
        methods: {
            send_form: function(){
                this.loading = true
                axios.post(URL_API + this.form_destination, $('#pregunta_form').serialize())
                .then(response => {
                    toastr["success"](response.data.message);
                    window.location = this.app_url + this.success_destination;
                    this.loading = false
                })
                .catch(function (error) {
                    console.log(error);
                });
            },   
        }
    });
</script>