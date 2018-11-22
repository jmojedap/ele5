<?php if ( $this->session->userdata('rol_id') <= 1 ) : ?>                
    <div class="panel panel-danger">
        <div class="panel-heading">
            Eliminar Institución
        </div>
        <div class="panel-body">
            <h4>
                <span class="resaltar"><i class="fa fa-warning"></i></span>
                Sea cuidadoso con este proceso</h4>
            <p>
                Se eliminarán, grupos, usuarios, estudiantes y demás datos relacionados. Esa acción no se podrá deshacer.
            </p>

            <?= $this->Pcrn->anchor_confirm("instituciones/eliminar/{$row->id}", '<i class="fa fa-trash-o"></i> Eliminar institución', 'class="btn btn-danger" title=""', '¿Confirma la eliminación de esta Institución?') ?>
        </div>
    </div>

    
<?php endif ?>