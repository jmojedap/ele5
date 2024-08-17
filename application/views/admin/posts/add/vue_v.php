<script>
// Variables
//-----------------------------------------------------------------------------
var fields = {
    nombre_post: '',
    tipo_id: '<?= $tipoId ?>'
};

// VueApp
//-----------------------------------------------------------------------------   
var addPostApp = new Vue({
    el: '#addPostApp',
    data: {
        loading: false,
        fields: fields,
        postId: 0,
        arrType: <?= json_encode($arrType) ?>,
    },
    methods: {
        handleSubmit: function() {
            this.loading = true
            var formValues = new FormData(document.getElementById('postForm'))
            axios.post(URL_API + 'posts/save/', formValues)
            .then(response => {
                if ( response.data.saved_id > 0 )
                {
                    this.postId = response.data.saved_id
                    this.clearForm()
                    $('#modal_created').modal()
                }
                this.loading = false
            })
            .catch(function (error) { console.log(error) })
        },
        clearForm: function() {
            for ( key in fields ) this.fields[key] = ''
        },
        goToCreated: function() {
            window.location = URL_APP + 'posts/edit/' + this.postId
        },
    }
});
</script>