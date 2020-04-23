<?php
    $cl_open = '';
    if ( $this->session->userdata('role') > 2 ) { $cl_open = 'd-none'; }
?>

<!-- Modal -->
<div class="modal fade" id="detail_modal" tabindex="-1" role="dialog" aria-labelledby="detail_modal_label" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="detail_modal_label">{{ element.titulo }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <table class="table">
                    <tr>
                        <td>ID</td>
                        <td>{{ element.id }}</td>
                    </tr>
                    
                    <tr>
                        <td>Link</td>
                        <td>
                            <p v-html="element.url"></p>
                        </td>
                    </tr>
                    <tr>
                        <td>Descripción</td>
                        <td>
                            <p v-html="element.descripcion"></p>
                        </td>
                    </tr>
                    
                </table>
            </div>
            <div class="modal-footer">
                <a class="btn btn-primary w3 <?php echo $cl_open ?>" v-bind:href="`<?php echo base_url('temas/links/') ?>` + element.tema_id">Abrir</a>
                <button type="button" class="btn btn-secondary w3" data-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>