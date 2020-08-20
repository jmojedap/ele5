<script>
// Variables
//-----------------------------------------------------------------------------
    var form_values = {
        nombre_post: '',
        tipo_id: '0<?= $tipo_id ?>'
    };

// Vue App
//-----------------------------------------------------------------------------
            
    new Vue({
        el: '#add_post',
        data: {
            form_values: form_values,
            row_id: 0
        },
        methods: {
            send_form: function() {
                axios.post(url_api + 'posts/insert/', $('#add_form').serialize())
                .then(response => {
                    console.log('status: ' + response.data.message);
                    if ( response.data.status == 1 )
                    {
                        this.row_id = response.data.saved_id;
                        this.clean_form();
                        $('#modal_created').modal();
                    }
                })
                .catch(function (error) {
                     console.log(error);
                });
            },
            clean_form: function() {
                for ( key in form_values ) {
                    this.form_values[key] = '';
                }
            },
            go_created: function() {
                window.location = url_app + 'posts/info/' + this.row_id;
            }
        }
    });
</script>