<?php
    $app_cf_index = $this->uri->segment(1) . '_' . $this->uri->segment(2);
    
    $cl_nav_2['cuestionarios_explorar'] = '';
    $cl_nav_2['cuestionarios_nuevo'] = '';
    $cl_nav_2['cuestionarios_asignaciones'] = '';
    $cl_nav_2['cuestionarios_asignar_masivo'] = '';
    $cl_nav_2['cuestionarios_responder_masivo'] = '';
    $cl_nav_2['respuestas_cargar_json'] = '';
    
    $cl_nav_2[$app_cf_index] = 'active';
    if ( $app_cf_index == 'cuestionarios_responder_masivo_e' ) { $cl_nav_2['cuestionarios_responder_masivo'] = 'active'; }
    if ( $app_cf_index == 'cuestionarios_asignar_masivo_e' ) { $cl_nav_2['cuestionarios_responder_masivo'] = 'active'; }
    if ( $app_cf_index == 'respuestas_cargar_json_e' ) { $cl_nav_2['respuestas_cargar_json'] = 'active'; }
?>

<script>
    var sections = [];
    var nav_2 = [];
    var sections_rol = [];
    
    sections.explorar = {
        icon: 'fa fa-search',
        text: 'Explorar',
        class: '<?php echo $cl_nav_2['cuestionarios_explorar'] ?>',
        cf: 'cuestionarios/explorar',
        anchor: true
    };

    sections.asignaciones = {
        icon: 'fa fa-users',
        text: 'Asignaciones',
        class: '<?php echo $cl_nav_2['cuestionarios_asignaciones'] ?>',
        cf: 'cuestionarios/asignaciones',
        anchor: true
    };

    sections.asignar_masivo = {
        icon: 'fa fa-file-excel',
        text: 'Asignar',
        class: '<?php echo $cl_nav_2['cuestionarios_asignar_masivo'] ?>',
        cf: 'cuestionarios/asignar_masivo',
        anchor: true
    };

    sections.responder_masivo = {
        icon: 'fa fa-file-excel',
        text: 'Cargar respuestas',
        class: '<?php echo $cl_nav_2['cuestionarios_responder_masivo'] ?>',
        cf: 'cuestionarios/responder_masivo',
        anchor: true
    };

    sections.cargar_json = {
        icon: 'far fa-file',
        text: 'Respuestas JSON',
        class: '<?php echo $cl_nav_2['respuestas_cargar_json'] ?>',
        cf: 'respuestas/cargar_json',
        anchor: true
    };

    sections.nuevo = {
        icon: 'fa fa-plus',
        text: 'Nuevo',
        class: '<?php echo $cl_nav_2['cuestionarios_nuevo'] ?>',
        cf: 'cuestionarios/nuevo/add',
        anchor: true
    };
    
    //Secciones para cada rol
    sections_rol.dvlp = ['explorar', 'nuevo', 'asignar_masivo', 'responder_masivo', 'cargar_json'];
    sections_rol.admn = ['explorar', 'nuevo', 'asignar_masivo', 'responder_masivo', 'cargar_json'];
    sections_rol.edtr = ['explorar', 'nuevo', 'asignar_masivo'];
    sections_rol.ains = ['explorar', 'nuevo'];
    sections_rol.dirc = ['explorar', 'nuevo'];
    sections_rol.prof = ['explorar', 'nuevo'];
    sections_rol.digt = ['explorar', 'nuevo'];
    sections_rol.comr = ['explorar', 'nuevo'];

    
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