<?php
    $att_item_menu = 'class="menu_lateral"';


?>

<div class="container_12" id="contenido">
    <div class="grid_12 div2 sep1">
        <h2 class="rojo">Bienvenido <?= $this->session->userdata('nombre') ?></h2>
    </div>
    
    <div class="grid_3">
        
        <h4>Institucional</h4>
            <?= anchor('datos/instituciones', 'Instituciones') ?><br/>
            <?= anchor('datos/usuarios', 'Usuarios') ?><br/>
            <?= anchor('datos/grupos', 'Grupos/Cursos') ?><br/>
        
        <h4>Contenidos académicos</h4>
            <?= anchor('datos/cuestionarios', 'Cuestionarios') ?><br/>
            <?= anchor('enunciados/explorar', 'Enunciados') ?><br/>
            <?= anchor('datos/preguntas', 'Preguntas') ?><br/>
            <?= anchor('cuestionarios/agregar_pregunta', 'Adicionar preguntas por búsqueda') ?><br/>
            <?= anchor('datos/recursos', 'Biblioteca de recursos') ?><br/>
        
            
        <h4>Usuario</h4>
            <?= anchor("usuarios/index/{$this->session->userdata('usuario_id')}", 'Ver mi perfil') ?><br/>
            <?= anchor("datos/usuarios/edit/{$this->session->userdata('usuario_id')}", 'Editar mis datos') ?><br/>
            <?= anchor('usuarios/contrasena', 'Cambiar contraseña') ?><br/>
            
            
            
        <h4>Configuración</h4>
            <?= anchor('datos/places', 'Lugares, ciudades y otros') ?><br/>
            
        
    </div>

</div>