<script>
    var sectionId = '<?= $this->uri->segment(2) . '_' . $this->uri->segment(3); ?>'
    var sections = [
        {
            text: 'Explorar',
            id: 'programas_explore',
            cf: 'programas/explore',
            roles: [0,1,2,9]
        },
        {
            text: 'Crear',
            id: 'programas_nuevo',
            cf: 'programas/nuevo/add',
            roles: [0,1,2],
            anchor: true
        },
        {
            text: 'Importar',
            id: 'programas_import',
            cf: 'programas/import',
            roles: [0,1,2],
            anchor: true
        },
    ]
    
//Filter role sections
var nav_2 = sections.filter(section => section.roles.includes(parseInt(APP_RID)))

//Set active class
nav_2.forEach((section,i) => {
    nav_2[i].class = ''
    if ( section.id == sectionId ) nav_2[i].class = 'active'
})

//Other sections
if ( sectionId == 'programas_run_import' ) nav_2[2].class = 'active'
if ( sectionId == 'programas_asignar_temas_multi' ) nav_2[2].class = 'active'
if ( sectionId == 'programas_asignar_temas_multi_run' ) nav_2[2].class = 'active'
if ( sectionId == 'programas_generar_flipbooks_multi' ) nav_2[2].class = 'active'
if ( sectionId == 'programas_generar_flipbooks_multi_run' ) nav_2[2].class = 'active'
</script>

<?php
$this->load->view('common/bs4/nav_2_v');