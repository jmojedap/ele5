<?php if ( $this->session->userdata('rol_id') <= 1 ) : ?>                
    <div class="card center_box_750">
        <div class="card-body">
            <h5 class="card-title text-danger">Eliminar Institución</h5>
            <h6>
                <span class="text-warning"><i class="fa fa-exclamation-triangle"></i></span>
                Sea cuidadoso con este proceso</h6>
            <p>
                Se eliminarán, grupos, usuarios, estudiantes y demás datos relacionados. Esa acción no se podrá deshacer.
            </p>

            <?= $this->Pcrn->anchor_confirm("instituciones/eliminar/{$row->id}", '<i class="fa fa-trash-o"></i> Eliminar institución', 'class="btn btn-danger" title=""', '¿Confirma la eliminación de esta Institución?') ?>
        </div>
    </div>
<?php endif ?>