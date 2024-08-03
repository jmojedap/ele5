<h1 class="articulo-titulo mt-5" id="articulo-<?= $articulo->id ?>"><?= $articulo->titulo ?></h1>
<p class="subtitulo"><?= $articulo->subtitle ?></p>
<?php if ( strlen($articulo->resumen) > 0 ) : ?>
    <div>
        <p class="epigrafe"><?= $articulo->resumen ?></p>
    </div>
<?php endif; ?>
<div class="contenido"><?= $articulo->contenido ?></div>