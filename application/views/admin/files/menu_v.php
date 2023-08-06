<script>
var sectionId = '<?= $this->uri->segment(2) . '_' . $this->uri->segment(3) ?>'
var nav2RowId = '<?= $file_id ?>'
var sections = [
    {
        text: '< Archivos',
        id: 'files_explore',
        cf: 'admin/files/explore/',
        roles: [0,1],
        anchor: true
    },
    {
        text: 'Información',
        id: 'files_info',
        cf: 'admin/files/info/' + nav2RowId,
        roles: [0,1]
    },
    {
        text: 'Detalles',
        id: 'files_details',
        cf: 'admin/files/details/' + nav2RowId,
        roles: [0,1]
    },
    {
        text: 'Editar',
        id: 'files_edit',
        cf: 'admin/files/edit/' + nav2RowId,
        roles: [0,1]
    },
    {
        text: 'Recortar',
        id: 'files_cropping',
        cf: 'admin/files/cropping/' + nav2RowId,
        roles: [0,1]
    },
    {
        text: 'Cambiar',
        id: 'files_change',
        cf: 'admin/files/change/' + nav2RowId,
        roles: [0,1]
    },
]

//Filter role sections
var nav_2 = sections.filter(section => section.roles.includes(parseInt(APP_RID)))

//Set active class
nav_2.forEach((section,i) => {
    nav_2[i].class = ''
    if ( section.id == sectionId ) nav_2[i].class = 'active'
})
</script>

<?php
$this->load->view('common/nav_2_v');