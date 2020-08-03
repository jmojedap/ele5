<?php
    $style_form_section = 'display: none;';
    if ( $row->image_id == 0 ) { $style_form_section = '';}
?>

<div id="image_form" style="<?php echo $style_form_section ?>">
    <div class="card center_box_750">
        <div class="card-body">
            <form accept-charset="utf-8" method="POST" id="file_form">
                <div class="form-group row">
                    <label for="file_field" class="col-md-3 col-form-label text-right">Archivo</label>
                    <div class="col-md-9">
                        <input
                            type="file"
                            name="file_field"
                            required
                            class="form-control"
                            placeholder="Archivo"
                            title="Arcivo a cargar"
                            >
                    </div>
                </div>

                <div class="form-group row">
                    <div class="col-md-9 offset-md-3">
                        <button class="btn btn-success w120p" type="submit">
                            Cargar
                        </button>
                    </div>
                </div>
            </form>
            <div id="upload_response"></div>
        </div>
    </div>
</div>