<script>
var quizImages = new Vue({
    el: '#quizImages',
    created: function(){
        this.getList();
    },
    data: {
        loading: false,
        file: null,
        quizId: '<?= $row->id ?>',
        images: <?= json_encode($images->result()); ?>,
        currentImage: {}
    },
    methods: {
        getList: function(){
            axios.get(URL_API + 'quices/get_images/' + this.quizId)
            .then(response => {
                this.images = response.data.images;
            })
            .catch(function (error) { console.log(error) })
        },
        submitFileForm: function(){
            let formValues = new FormData();
            formValues.append('file_field', this.file)
            formValues.append('table_id', '4370')   //quiz table
            formValues.append('related_1', this.quizId)

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
            axios.get(URL_API + 'quices/set_main_image/' + this.quizId + '/' + file_id)
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