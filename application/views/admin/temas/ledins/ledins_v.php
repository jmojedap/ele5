<script>
    var base_url = '<?php echo base_url() ?>';
    var ledin_id = '<?php echo $ledin->id ?>';

    $(document).ready(function(){
        $('#btn_delete_element').click(function(){
            console.log('ELIMINANDO');
            delete_ledin();
        });
    });

    function delete_ledin(){
        $.ajax({        
            type: 'POST',
            url: base_url + 'posts/delete/' + ledin_id,
            success: function(response){
                if ( response.status == 1) {
                    $('#ledin_' + ledin_id).hide();
                    $('#ledin_container').hide('');
                    toastr['info']('Lectura eliminada');
                }
            }
        });
    }
</script>

<div class="row">
    <div class="col-md-4">
        <div class="list-group">
            <?php foreach ( $ledins->result() as $row_ledin ) { ?>
                <?php
                    $cl_item = $this->Pcrn->clase_activa($row_ledin->id, $ledin_id, 'active');
                ?>
                <a
                    id="ledin_<?php echo $row_ledin->id ?>"
                    href="<?php echo base_url("admin/temas/lecturas_dinamicas/{$row->id}/{$row_ledin->id}") ?>"
                    class="list-group-item list-group-item-action <?php echo $cl_item ?>">
                    <?php echo $row_ledin->nombre_post ?>
                </a>
            <?php } ?>
        </div>
    </div>
    <div class="col-md-8">
        <?php if ( ! is_null($ledin_id) ) { ?>
            <div class="card" style="max-width: 650px;" id="ledin_container">
                <div class="card-body">
                    <button class="btn btn-warning float-right" title="Eliminar lectura" data-toggle="modal" data-target="#delete_modal">
                        <i class="fa fa-trash"></i>
                    </button>
                    <?php $this->load->view('admin/temas/ledins/ledin_v') ?>
                </div>
            </div>
        <?php } ?>
    </div>
    <?php $this->load->view('comunes/bs4/modal_simple_delete_v') ?>
</div>