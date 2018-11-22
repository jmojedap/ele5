<div class="row">
    <div class="col col-md-7">
        <div class="casilla">
            <?= $this->Pagina_model->img_pf($row, 3); ?>
        </div>
        <div class="casilla">
            <?= $this->Pagina_model->img_pf($row, 1); ?>
            <br/>
            <div class="sep2">
                <?= anchor("paginas/actualizar_miniatura/{$row->id}", '<i class="fa fa-refresh"></i> Actualizar miniatura', 'class="btn btn-default" title=""') ?>
            </div>
            
        </div>
    </div>
    
    <div class="col col-sm-5">
        
        <div class="alert alert-info">
            Contenidos en los que se incluye la página
        </div>
        
        <table class="table table-default bg-blanco">
            <thead>
                <th>Contenido</th>
                <th>Núm página</th>
            </thead>
            <tbody>
                <?php foreach ($flipbooks->result() as $row_flipbook): ?>                    
                    <tr>
                        <td><?= anchor("flipbooks/paginas/{$row_flipbook->flipbook_id}", $row_flipbook->nombre_flipbook, 'class="a1"') ?></td>
                        <td><?= $row_flipbook->num_pagina ?></td>
                    </tr>

                <?php endforeach ?>
            </tbody>
        </table>
    </div>
</div>

