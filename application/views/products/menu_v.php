<?php
    $app_cf_index = $this->uri->segment(1) . '_' . $this->uri->segment(2);
    
    $cl_nav_2['products_explore'] = '';
    $cl_nav_2['products_info'] = '';
    $cl_nav_2['products_flipbooks'] = '';
    $cl_nav_2['products_institutions'] = '';
    $cl_nav_2['products_image'] = '';
    $cl_nav_2['products_edit'] = '';
    //$cl_nav_2['products_import'] = '';
    
    $cl_nav_2[$app_cf_index] = 'active';
    //if ( $app_cf == 'products/explore' ) { $cl_nav_2['products_explore'] = 'active'; }
?>

<script>
    var sections = [];
    var nav_2 = [];
    var sections_rol = [];
    var element_id = '<?php echo $row->id ?>';
    
    sections.explore = {
        icon: 'fa fa-arrow-left',
        text: 'Explorar',
        class: '<?php echo $cl_nav_2['products_explore'] ?>',
        cf: 'products/explore/',
        anchor: true
    };

    sections.info = {
        icon: 'fa fa-info-circle',
        text: 'Información',
        class: '<?php echo $cl_nav_2['products_info'] ?>',
        cf: 'products/info/' + element_id,
        anchor: true
    };

    sections.image = {
        icon: 'fa fa-image',
        text: 'Imagen',
        class: '<?php echo $cl_nav_2['products_image'] ?>',
        cf: 'products/image/' + element_id,
        anchor: true
    };

    sections.flipbooks = {
        icon: 'fa fa-book',
        text: 'Contenidos',
        class: '<?php echo $cl_nav_2['products_flipbooks'] ?>',
        cf: 'products/flipbooks/' + element_id,
        anchor: true
    };

    sections.institutions = {
        icon: 'fa fa-university',
        text: 'Instituciones',
        class: '<?php echo $cl_nav_2['products_institutions'] ?>',
        cf: 'products/institutions/' + element_id,
        anchor: true
    };

    sections.edit = {
        icon: 'fa fa-pencil-alt',
        text: 'Editar',
        class: '<?php echo $cl_nav_2['products_edit'] ?>',
        cf: 'products/edit/' + element_id,
        anchor: true
    };
    
    //Secciones para cada rol
    sections_rol.dvlp = ['explore', 'info', 'institutions', 'edit'];
    sections_rol.admn = ['explore', 'info', 'institutions', 'edit'];
    
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