<script>
var postImages = new Vue({
    el: '#postImages',
    created: function(){
        this.getList();
    },
    data: {
        loading: false,
        file: null,
        post_id: '<?= $row->id ?>',
        images: <?= json_encode($images->result()); ?>,
        currentImage: {}
    },
    methods: {
        getList: function(){
            axios.get(URL_API + 'posts/get_images/' + this.post_id)
            .then(response => {
                this.images = response.data.images;
            })
            .catch(function (error) { console.log(error) })
        },
        submitFileForm: function(){
            let formValues = new FormData();
            formValues.append('file_field', this.file)
            formValues.append('table_id', '2000')
            formValues.append('related_1', this.post_id)

            axios.post(URL_API + 'files/upload/', formValues, {headers: {'Content-Type': 'multipart/form-data'}})
            .then(response => {
                //Cargar imágenes
                if ( response.data.status == 1 ) {
                    this.getList()
                    //Limpiar formulario
                    document.getElementById('field-file').value = null
                    this.file = null
                }
                //Mostrar respuesta html, si existe
                if ( response.data.html ) { $('#upload_response').html(response.data.html); }
            })
            .catch(function (error) { console.log(error) })
        },
        handleFileUpload(){
            this.file = this.$refs.file_field.files[0]
        },
        setCurrent: function(key){
            this.currentImage = this.images[key]
        },
        delete_element: function(){
            var file_id = this.currentImage.id
            axios.get(URL_API + 'files/delete/' + file_id)
            .then(response => {
                this.getList()
            })
            .catch(function (error) { console.log(error) })
        },
        setMainImage: function(key){
            this.setCurrent(key)
            var file_id = this.currentImage.id
            console.log(this.currentImage)
            axios.get(URL_API + 'posts/set_main_image/' + this.post_id + '/' + file_id)
            .then(response => {
                if ( response.data.status == 1 ) this.getList()
            })
            .catch(function (error) { console.log(error) })
        },
        updatePosition: function(file_id, new_position){
            axios.get(URL_API + 'files/update_position/' + file_id + '/' + new_position)
            .then(response => {
                if ( response.data.status == 1 ) {
                    this.getList()
                } else {
                    toastr['warning']('No se cambió el orden de las imágenes')
                }
            })
            .catch(function(error) { console.log(error) })
        },
    }
});
</script>