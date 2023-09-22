<script>
var sectionId = '<?= $this->uri->segment(2) . '_' . $this->uri->segment(3) ?>'
var nav2RowId = '<?= $row->id ?>'
var sections = [
    {
        id: 'programas_explore',
        text: '< Programas',
        cf: 'programas/explore/',
        roles: [0,1,2,9],
        anchor: true,
    },
    {
        id: 'programas_info',
        text: 'Información',
        cf: 'programas/info/' + nav2RowId,
        roles: [0,1,2,9],
        anchor: true
    },
    {
        id: 'programas_temas',
        text: 'Temas',
        cf: 'programas/temas/' + nav2RowId,
        roles: [0,1,2],
        anchor: true
    },
    {
        id: 'programas_editar',
        text: 'Editar',
        cf: 'programas/editar/edit/' + nav2RowId,
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
//if ( sectionId == 'temas_lecturas_dinamicas' ) nav_2[6].class = 'active'
</script>

<?php
$this->load->view('common/bs4/nav_2_v');