<?php
    $app_cf_index = $this->uri->segment(1) . '_' . $this->uri->segment(2);
    
    $cl_nav_2['orders_info'] = '';
    $cl_nav_2['orders_edit'] = '';
    $cl_nav_2['orders_test'] = '';
    $cl_nav_2['orders_details'] = '';
    $cl_nav_2['orders_responses'] = '';
    //$cl_nav_2['orders_import'] = '';
    
    $cl_nav_2[$app_cf_index] = 'active';
    if ( $app_cf_index == 'orders_cropping' ) { $cl_nav_2['orders_test'] = 'active'; }
?>

<script>
    var sections = [];
    var nav_2 = [];
    var sections_rol = [];
    var element_id = '<?php echo $row->id ?>';
    
    sections.explore = {
        icon: 'fa fa-arrow-left',
        text: 'Explorar',
        class: '',
        cf: 'orders/explore/',
        anchor: true

    };

    sections.info = {
        icon: '',
        text: 'Información',
        class: '<?php echo $cl_nav_2['orders_info'] ?>',
        cf: 'orders/info/' + element_id,
        anchor: true
    };

    sections.details = {
        icon: '',
        text: 'Detalles',
        class: '<?php echo $cl_nav_2['orders_details'] ?>',
        cf: 'orders/details/' + element_id,
        anchor: true
    };

    sections.responses = {
        icon: '',
        text: 'Wompi',
        class: '<?php echo $cl_nav_2['orders_responses'] ?>',
        cf: 'orders/responses/' + element_id,
        anchor: true
    };

    sections.edit = {
        icon: '',
        text: 'Editar',
        class: '<?php echo $cl_nav_2['orders_edit'] ?>',
        cf: 'orders/edit/' + element_id,
        anchor: true
    };
    
    sections.test = {
        icon: 'fa fa-gears',
        text: 'Pruebas',
        class: '<?php echo $cl_nav_2['orders_test'] ?>',
        cf: 'orders/test/confirmation/' + element_id,
        anchor: true
    };
    
    //Secciones para cada rol
    sections_rol.dvlp = ['explore', 'info', 'details', 'responses', 'edit', 'test'];
    sections_rol.admn = ['explore', 'info', 'details', 'responses', 'edit'];
    
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