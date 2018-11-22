<?php
    $app_cf_indice = $this->uri->segment(1) . '_' . $this->uri->segment(2);
    
    $cl_menu_a['usuarios_explorar'] = '';
    $cl_menu_a['usuarios_nuevo'] = '';
    $cl_menu_a['usuarios_importar'] = '';
    
    $cl_menu_a[$app_cf_indice] = 'active';
    //if ( $app_cf == 'usuarios/explorar' ) { $cl_menu_a['usuarios_explorar'] = 'active'; }
?>

<script>
    var elementos_menu_a = [
        {
            'icono': 'fa fa-list-alt',
            'texto': 'Explorar',
            'clase': '<?php echo $cl_menu_a['usuarios_explorar'] ?>',
            'cf': 'usuarios/explorar'
        },
        {
            'icono': 'fa fa-plus',
            'texto': 'Nuevo',
            'clase': '<?php echo $cl_menu_a['usuarios_nuevo'] ?>',
            'cf': 'usuarios/nuevo'
        },
        {
            'icono': 'fa fa-upload',
            'texto': 'Importar',
            'clase': '<?php echo $cl_menu_a['usuarios_importar'] ?>',
            'cf': 'usuarios/importar'
        }
    ];
</script>

<?php
$this->load->view('comunes/menu_a_v');