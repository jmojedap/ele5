<form accept-charset="utf-8" method="POST" id="file_form" @submit.prevent="send_file_form">
    <fieldset v-bind:disabled="loading_file">
        <div class="form-group row">
            <div class="col-md-8">
                <input
                    type="file" id="field-file"
                    ref="file_field" name="file_field"
                    required class="form-control"
                    v-on:change="handle_file_upload()"
                    >
            </div>
            <div class="col-md-4">
                <button class="btn btn-success btn-block" type="submit" v-show="!loading_file">
                    Cargar  
                </button>
                <button class="btn btn-info w100pc" v-show="loading_file">
                    <i class="fa fa-spin fa-spinner"></i> Cargando
                </button>
            </div>
        </div>
    </fieldset>
</form>

<div id="upload_response"></div>