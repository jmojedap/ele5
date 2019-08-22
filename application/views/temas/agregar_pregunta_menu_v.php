<?php
    $seccion = 'existente';
    
    $clases['nueva'] = '';
    $clases['existente'] = '';
    
    if ( $proceso == 'add' ) { $seccion = 'nueva'; }
    
    $clases[$seccion] = 'active';
    
?>

<div class="mb-2">
    <ul class="nav nav-pills">
        <li class="nav-item">
            <a href="<?php echo base_url("temas/preguntas/{$row->id}") ?>" class="nav-link">
                <i class="fa fa-chevron-left"></i>
                Cancelar
            </a>
        </li>
        <li role="presentation" class="nav-item">
            <a href="<?php echo base_url("temas/agregar_pregunta/{$row->id}/{$orden}/add") ?>" class="nav-link <?= $clases['nueva'] ?>" title="Crear una nueva pregunta">
                Nueva pregunta
            </a>
        </li>
        <li class="nav-item">
            <a 
                class="nav-link <?= $clases['existente'] ?>"
                href="<?php echo base_url("temas/agregar_pregunta/{$row->id}/{$orden}") ?>"
                title="Buscar una pregunta existente para asignársela al tema"
                >
                Pregunta existente
            </a>
        </li>
    </ul>
</div>
