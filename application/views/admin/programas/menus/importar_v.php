<script>
    var sectionId = '<?= $this->uri->segment(2) . '_' . $this->uri->segment(3); ?>'
    var sections = [
        {
            text: 'Programas',
            id: 'programas_import',
            cf: 'programas/import',
            roles: [0,1,2],
            anchor: true
        },
        {
            text: 'Asignar temas',
            id: 'programas_asignar_temas_multi',
            cf: 'programas/asignar_temas_multi',
            roles: [0,1,2],
            anchor: true
        },
        {
            text: 'Generar contenidos',
            id: 'programas_generar_flipbooks_multi',
            cf: 'programas/generar_flipbooks_multi',
            roles: [0,1,2],
            anchor: true
        },
    ]
    
//Filter role sections
var nav_3 = sections.filter(section => section.roles.includes(parseInt(APP_RID)))

//Set active class
nav_3.forEach((section,i) => {
    nav_3[i].class = ''
    if ( section.id == sectionId ) nav_3[i].class = 'active'
})

//Other sections
if ( sectionId == 'programas_run_import' ) nav_3[0].class = 'active'
if ( sectionId == 'programas_asignar_temas_multi_run' ) nav_3[1].class = 'active'
if ( sectionId == 'programas_generar_flipbooks_multi_run' ) nav_3[2].class = 'active'
</script>

<?php
$this->load->view('common/bs4/nav_3_v');