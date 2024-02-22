<?php
    $app_cf_index = $this->uri->segment(1) . '_' . $this->uri->segment(2);
    
    $cl_nav_2['quices_explorar'] = '';
    
    $cl_nav_2[$app_cf_index] = 'active';
    
    /*if ( $app_cf_index == 'quices_importar_estudiantes_e' ) { $cl_nav_2['quices_importar_estudiantes'] = 'active'; }*/
?>

<script>
    var sections = [];
    var nav_2 = [];
    var sections_rol = [];
    
    sections.explorar = {
        icon: '',
        text: 'Explorar',
        class: '<?= $cl_nav_2['quices_explorar'] ?>',
        cf: 'quices/explorar',
        anchor: true
    };
    
    //Secciones para cada rol
    sections_rol.dvlp = ['explorar'];
    sections_rol.admn = ['explorar'];
    sections_rol.edtr = ['explorar'];
    sections_rol.comr = ['explorar'];
    sections_rol.digt = ['explorar'];
    sections_rol.dig2 = ['explorar'];
    
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