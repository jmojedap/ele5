<?php
    $app_cf_index = $this->uri->segment(1) . '_' . $this->uri->segment(2);
    
    $cl_nav_2['orders_explore'] = '';
    
    $cl_nav_2[$app_cf_index] = 'active';
    if ( $app_cf_index == 'orders_import_e' ) { $cl_nav_2['orders_import'] = 'active'; }
?>

<script>
    var sections = [];
    var nav_2 = [];
    var sections_rol = [];
    
    sections.explore = {
        'icon': 'fa fa-search',
        'text': 'Explorar',
        'class': '<?php echo $cl_nav_2['orders_explore'] ?>',
        'cf': 'orders/explore'
    };
    
    //Secciones para cada rol
    sections_rol.dvlp = ['explore'];
    sections_rol.admn = ['explore'];
    sections_rol.edtr = ['explore'];
    sections_rol.digt = ['explore'];
    
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