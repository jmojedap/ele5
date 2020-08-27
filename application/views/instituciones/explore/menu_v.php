<?php
    $app_cf_index = $this->uri->segment(1) . '_' . $this->uri->segment(2);
    
    $cl_nav_2['instituciones_explorar'] = '';
    $cl_nav_2['instituciones_import'] = '';
    $cl_nav_2['instituciones_nuevo'] = '';
    
    $cl_nav_2[$app_cf_index] = 'active';
    if ( $app_cf_index == 'instituciones_import_e' ) { $cl_nav_2['instituciones_import'] = 'active'; }
?>

<script>
    var sections = [];
    var nav_2 = [];
    var sections_rol = [];
    
    sections.explorar = {
        icon: 'fa fa-search',
        text: 'Explorar',
        class: '<?php echo $cl_nav_2['instituciones_explorar'] ?>',
        cf: 'instituciones/explorar'
    };

    sections.import = {
        icon: 'fa fa-upload',
        text: 'Importar',
        class: '<?php echo $cl_nav_2['instituciones_import'] ?>',
        cf: 'instituciones/import'
    };

    sections.nuevo = {
        icon: 'fa fa-plus',
        text: 'Nuevo',
        class: '<?php echo $cl_nav_2['instituciones_nuevo'] ?>',
        cf: 'instituciones/nuevo/add',
        anchor: true
    };
    
    //Secciones para cada rol
    sections_rol.dvlp = ['explorar', 'nuevo'];
    sections_rol.admn = ['explorar', 'nuevo'];
    sections_rol.edtr = ['explorar', 'nuevo'];
    
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