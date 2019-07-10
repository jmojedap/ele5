<?php
    //Clases pestanas
    $clase_pestana['asignados'] = '';
    $clase_pestana['respondidos'] = '';
    $clase_pestana['externos'] = '';
    
    $clase_pestana[$seccion] = 'active';
?>

<!-- Nav tabs -->
<ul class="nav nav-tabs mb-2">
    <li class="nav-item">
        <a href="#respondidos" data-toggle="tab" class="nav-link <?= $clase_pestana['respondidos'] ?>">
            Respondidos <span class="badge badge-success">
            <?= $cuestionarios_resp->num_rows() ?></span>
        </a>
    </li>
    <li class="nav-item">
        <a href="#asignados" data-toggle="tab" class="nav-link <?= $clase_pestana['asignados'] ?>">
            Sin responder <span class="badge badge-success"><?= $cuestionarios->num_rows() ?></span>
        </a>
    </li>
    <li class="nav-item">
        <a href="#externos" data-toggle="tab" class="nav-link <?= $clase_pestana['externos'] ?>">
            Otros resultados <span class="badge badge-success"><?= $externos->num_rows() ?></span>
        </a>
    </li>
</ul>

<!-- Tab content -->
<div class="tab-content">
    <div class="tab-pane <?= $clase_pestana['asignados'] ?>" id="asignados">
        <?php $this->load->view('usuarios/cuestionarios/asignados_v') ?>
    </div>
    
    <div class="tab-pane <?= $clase_pestana['respondidos'] ?>" id="respondidos">        
        <?php $this->load->view('usuarios/cuestionarios/respondidos_v') ?>
    </div>
    
    <div class="tab-pane <?= $clase_pestana['externos'] ?>" id="externos">
        <?php $this->load->view('usuarios/cuestionarios/externos_v') ?>
    </div>
</div>