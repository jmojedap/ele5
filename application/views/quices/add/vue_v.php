<script>
// Vue App
//-----------------------------------------------------------------------------
            
    new Vue({
        el: '#addQuiz',
        data: {
            fields: {},
            arrTipos: <?= json_encode($arrTipos) ?>,
            arrAreas: <?= json_encode($arrAreas) ?>,
            arrNiveles: <?= json_encode($arrNiveles) ?>,
            rowId: 0
        },
        methods: {
            handleSubmit: function() {
                this.loading = true
                var formValues = new FormData(document.getElementById('quiz_form'))
                axios.post(url_api + 'quices/save/', formValues)
                .then(response => {
                    if ( response.data.saved_id > 0 )
                    {
                        this.rowId = response.data.saved_id
                        this.clearForm()
                        $('#modal_created').modal()
                    }
                })
                .catch(function (error) { console.log(error) })
            },
            clearForm: function() {
                for ( key in this.fields ) {
                    this.fields[key] = '';
                }
            },
            goToCreated: function() {
                window.location = url_app + 'quices/editar/' + this.rowId;
            }
        }
    });
</script>