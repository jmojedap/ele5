<?php
    $app_cf_index = $this->uri->segment(2) . '_' . $this->uri->segment(3);
    
    $cl_nav_3['nuevo_estudiante'] = '';
    $cl_nav_3['nuevo_institucional'] = '';
    $cl_nav_3['nuevo_interno'] = '';
    
    $cl_nav_3[$app_cf_index] = 'active';
    if ( $app_cf_index == 'usuarios_importar_estudiantes_e' ) { $cl_nav_3['usuarios_importar_estudiantes'] = 'active'; }
?>

<script>
    var sections = [];
    var nav_3 = [];
    var sections_rol = [];
    
    sections.estudiante = {
        icon: '',
        text: 'Estudiante',
        class: '<?= $cl_nav_3['nuevo_estudiante'] ?>',
        cf: 'usuarios/nuevo/estudiante',
        anchor: true
    };

    sections.institucional = {
        icon: '',
        text: 'Institucional',
        class: '<?= $cl_nav_3['nuevo_institucional'] ?>',
        cf: 'usuarios/nuevo/institucional',
        anchor: true
    };

    sections.interno = {
        icon: '',
        text: 'Interno',
        class: '<?= $cl_nav_3['nuevo_interno'] ?>',
        cf: 'usuarios/nuevo/interno',
        anchor: true
    };
    
    //Secciones para cada rol
    sections_rol.dvlp = ['estudiante', 'institucional', 'interno'];
    sections_rol.admn = ['estudiante', 'institucional', 'interno'];
    
    //Recorrer el sections del rol actual y cargarlos en el menú
    for ( key_section in sections_rol[app_r]) 
    {
        var key = sections_rol[app_r][key_section];   //Identificar elemento
        nav_3.push(sections[key]);    //Agregar el elemento correspondiente
    }
</script>

<?php
$this->load->view('common/nav_3_v');