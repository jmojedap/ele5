<?php
    $app_cf_index = $this->uri->segment(1) . '_' . $this->uri->segment(2);
    
    $cl_nav_2['grupos_explorar'] = '';
    $cl_nav_2['grupos_importar_editar_anios'] = '';
    
    $cl_nav_2[$app_cf_index] = 'active';
    if ( $app_cf_index == 'grupos_importar_editar_anios_e' ) { $cl_nav_2['grupos_importar_editar_anios'] = 'active'; }
    if ( $app_cf_index == 'grupos_desasignar_profesores' ) { $cl_nav_2['grupos_importar_editar_anios'] = 'active'; }
    if ( $app_cf_index == 'grupos_desasignar_profesores_e' ) { $cl_nav_2['grupos_importar_editar_anios'] = 'active'; }
?>

<script>
    var sections = [];
    var nav_2 = [];
    var sections_rol = [];
    
    sections.explorar = {
        icon: 'fa fa-search',
        text: 'Explorar',
        class: '<?php echo $cl_nav_2['grupos_explorar'] ?>',
        cf: 'grupos/explorar',
        anchor: true
    };

    sections.importar = {
        icon: 'fa fa-upload',
        text: 'Importar',
        class: '<?php echo $cl_nav_2['grupos_importar_editar_anios'] ?>',
        cf: 'grupos/importar_editar_anios/',
        anchor: true
    };
    
    //Secciones para cada rol
    sections_rol.dvlp = ['explorar', 'importar'];
    sections_rol.admn = ['explorar', 'importar'];
    sections_rol.edtr = ['explorar', 'importar'];
    
    //Recorrer el sections del rol actual y cargarlos en el menú
    for ( key_section in sections_rol[app_r]) 
    {
        //console.log(sections_rol[rol][key_section]);
        var key = sections_rol[app_r][key_section];   //Identificar elemento
        nav_2.push(sections[key]);    //Agregar el elemento correspondiente
    }
</script>

<?php
$this->load->view('common/nav_2_v');