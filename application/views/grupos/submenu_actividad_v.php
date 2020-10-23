<?php
    $app_cf_index = $this->uri->segment(1) . '_' . $this->uri->segment(2);
    
    $cl_nav_3['grupos_anotaciones'] = '';
    $cl_nav_3['grupos_quices'] = '';
    $cl_nav_3['grupos_actividad_links'] = '';
    
    $cl_nav_3[$app_cf_index] = 'active';
    //if ( $app_cf_index == 'grupos_editar_estudiantes' ) { $cl_nav_3['grupos_estudiantes'] = 'active'; }
?>

<script>
    var sections = [];
    var nav_3 = [];
    var sections_rol = [];
    var element_id = '<?php echo $row->id ?>';
    
    sections.anotaciones = {
        icon: '',
        text: 'Anotaciones',
        class: '<?php echo $cl_nav_3['grupos_anotaciones'] ?>',
        cf: 'grupos/anotaciones/' + element_id,
        anchor: true
    };

    sections.quices = {
        icon: '',
        text: 'Evidencias',
        class: '<?php echo $cl_nav_3['grupos_quices'] ?>',
        cf: 'grupos/quices/' + element_id,
        anchor: true
    };

    sections.actividad_links = {
        icon: '',
        text: 'Links abiertos',
        class: '<?php echo $cl_nav_3['grupos_actividad_links'] ?>',
        cf: 'grupos/actividad_links/' + element_id,
        anchor: true
    };
    
    //Secciones para cada rol
    sections_rol.dvlp = ['anotaciones', 'quices', 'actividad_links'];
    sections_rol.admn = ['institucion', 'estudiantes', 'anotaciones', 'quices', 'cuestionarios_flipbooks', 'cuestionarios', 'profesores', 'promover', 'editar'];
    sections_rol.edtr = ['institucion', 'estudiantes', 'anotaciones', 'quices', 'cuestionarios_flipbooks', 'cuestionarios', 'profesores', 'promover'];
    sections_rol.ains = ['institucion', 'estudiantes', 'anotaciones', 'quices', 'cuestionarios_flipbooks', 'cuestionarios', 'profesores', 'promover'];
    sections_rol.dirc = ['estudiantes', 'anotaciones', 'quices', 'cuestionarios_flipbooks', 'cuestionarios', 'profesores'];
    sections_rol.prof = ['estudiantes', 'anotaciones', 'quices', 'cuestionarios_flipbooks', 'cuestionarios'];
    sections_rol.comr = ['estudiantes', 'anotaciones', 'quices', 'cuestionarios_flipbooks', 'cuestionarios', 'profesores'];
    
    //Recorrer el sections del rol actual y cargarlos en el menú
    for ( key_section in sections_rol[app_r]) 
    {
        var key = sections_rol[app_r][key_section];   //Identificar elemento
        nav_3.push(sections[key]);    //Agregar el elemento correspondiente
    }
    
</script>

<?php
$this->load->view('common/nav_3_v');