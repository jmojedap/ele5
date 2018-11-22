<!DOCTYPE html>
<html>
    <title><?= $titulo_pagina ?></title>
    <link rel="shortcut icon" href="<?= base_url() . RUTA_IMG ?>admin/icono.png" type="image/ico" />
    <style>
        body{
            padding-top: 50px;
            background: #89cb4e;
        }
        
        div.contenedor{
            width: 650px;
            margin: 0 auto;
        }
    </style>
</html>
<body>
    <div class="contenedor">
        <video width="640" height="360" controls autoplay>
            <source src="<?= base_url() . RUTA_UPLOADS . 'animaciones/' . $row->nombre_archivo ?>" type="video/mp4">
            Your browser does not support the video tag.
        </video>
    </div>
    
</body>
</html>