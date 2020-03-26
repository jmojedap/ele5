<?php
    $app_cf_index = $this->uri->segment(1) . '_' . $this->uri->segment(2);
    
    $cl_nav_2['recursos_links'] = '';
    $cl_nav_2['recursos_links_importar'] = '';
    
    $cl_nav_2[$app_cf_index] = 'active';
    if ( $app_cf_index == 'recursos_import_e' ) { $cl_nav_2['recursos_import'] = 'active'; }
?>

<script>
    var sections = [];
    var nav_2 = [];
    var sections_rol = [];
    
    sections.links = {
        'icon': 'fa fa-search',
        'text': 'Explorar',
        'class': '<?php echo $cl_nav_2['recursos_links'] ?>',
        'cf': 'recursos/links',
        'anchor': true
    };

    sections.links_importar = {
        'icon': 'fa fa-upload',
        'text': 'Importar',
        'class': '<?php echo $cl_nav_2['recursos_links_importar'] ?>',
        'cf': 'recursos/links_importar',
        'anchor': true
    };
    
    //Secciones para cada rol
    sections_rol.dvlp = ['links', 'links_importar'];
    sections_rol.admn = ['links', 'links_importar'];
    sections_rol.edtr = ['links'];
    sections_rol.ains = ['links'];
    sections_rol.dirc = ['links'];
    sections_rol.prof = ['links'];
    
    //Recorrer el sections del rol actual y cargarlos en el menú
    for ( key_section in sections_rol[app_r]) 
    {
        /*console.log(sections_rol[app_r][key_section]);*/
        var key = sections_rol[app_r][key_section];   //Identificar elemento
        nav_2.push(sections[key]);    //Agregar el elemento correspondiente
    }
</script>

<?php
$this->load->view('common/nav_2_v');