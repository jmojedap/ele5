<?php $this->load->view('assets/chosen_jquery'); ?>

<?php
    $att_link_flipbooks = 'class="a2"';
?>

<script>
    $(document).ready(function(){
        $('#check_todos').change(function() {
            if($(this).is(":checked")) {
                $('form input[type=checkbox]').each( function() {			
                    this.checked = true;
                });
            } else {
                $('form input[type=checkbox]').each( function() {			
                    this.checked = false;
                });
            }
        });
    });
</script>

<?php $this->load->view('grupos/submenu_flipbooks_v') ?>

<?= form_open("grupos/eliminar_asignacion_f/{$row->id}") ?>
<?= form_hidden('flipbook_id', $flipbook_id); ?>

<div class="row">
    <div class="col col-md-3">
        
        <div class="panel panel-default">
            <div class="panel-body">
                
                <p>
                    Seleccione el Contenido que quiere quitar de este grupo
                </p>
                
                <ul class="nav nav-pills flex-column mb-2">
                    <?php foreach ($flipbooks->result() as $row_flipbook): ?>
                        <?php
                            $link_flipbook = "grupos/quitar_flipbook/{$grupo_id}/{$row_flipbook->flipbook_id}";
                            $nombre_flipbook_row = $this->App_model->nombre_flipbook($row_flipbook->flipbook_id);

                            $clase = 'nav-link';
                            if ( $flipbook_id == $row_flipbook->flipbook_id )
                            {
                                $clase = 'nav-link active'; 
                            }
                        ?>

                        <li role="presentation" class="nav-item">
                            <a href="<?= base_url($link_flipbook) ?>" class="<?= $clase ?>">
                                <?= $nombre_flipbook_row ?>
                            </a>
                        </li>


                    <?php endforeach ?>
                </ul>
                
                <div class="mb-2 pull-right">
                    <button class="btn btn-warning w120p" type="submit">
                        Quitar
                    </button>
                </div>
                
            </div>
        </div>
        
        

        

        <?php if ( $this->session->flashdata('resultado') != NULL ):?>
            <?php $resultado = $this->session->flashdata('resultado') ?>
            <div class="mb-2">
                <div class="alert alert-success">
                    <i class="fa fa-info-circle"></i>
                    Se eliminaron <?= $resultado['num_eliminados'] ?> asignaciones de contenido
                </div>
            </div>
        <?php endif ?>
    </div>
    <div class="col col-md-9">
        <div class="panel panel-default">
            <div class="panel-body">
                <h4>Quitar contenido a los estudiantes</h4>
                <p class="p1">
                    Los datos de asignación al contenido <span class="resaltar"><?= $nombre_flipbook ?></span> de los estudiantes que se seleccionen en las casillas serán <span class="resaltar">ELIMINADOS</span>.
                    Las anotaciones para este contenido de los estudiantes seleccionados también serán <span class="resaltar">ELIMINADAS</span>. Sea cuidadoso(a) con este proceso.
                </p>
            </div>
        </div>
        
        <table class="table table-default bg-blanco" cellspacing="0">
            <thead>
                <tr>
                    <th width="10px"><input type="checkbox" id="check_todos"></th>
                    <th>Nombre estudiante</th>
                </tr>
            </thead>
            <tbody>

                <?php foreach ($estudiantes->result() as $row_estudiante): ?>
                    <tr>
                        <td>
                            <input type="checkbox" name="<?= $row_estudiante->id ?>" class="check_registro">
                        </td>
                        <td><?= $this->App_model->nombre_usuario($row_estudiante->id, 3) ?></td>

                    </tr>
                <?php endforeach ?>


            </tbody>
        </table>
    </div>
</div>

<?= form_close() ?>