<script>
var sectionId = '<?= $this->uri->segment(2) . '_' . $this->uri->segment(3) ?>'
var nav2RowId = '<?= $row->id ?>'
var sections = [
    {
        id: 'posts_explore',
        text: '< Posts',
        cf: 'posts/explore/',
        roles: [0,1],
        anchor: true
    },
    {
        id: 'posts_info',
        text: 'Información',
        cf: 'posts/info/' + nav2RowId,
        roles: [0,1]
    },
    {
        id: 'posts_images',
        text: 'Imágenes',
        cf: 'posts/images/' + nav2RowId,
        roles: [0,1]
    },
    {
        id: 'posts_details',
        text: 'Detalles',
        cf: 'posts/details/' + nav2RowId,
        roles: [0,1]
    },
    {
        id: 'posts_edit',
        text: 'Editar',
        cf: 'posts/edit/' + nav2RowId,
        roles: [0,1],
        anchor: true
    },
]
    
//Filter role sections
var nav_2 = sections.filter(section => section.roles.includes(parseInt(APP_RID)));

//Set active class
nav_2.forEach((section,i) => {
    nav_2[i].class = ''
    if ( section.id == sectionId ) nav_2[i].class = 'active'
})
</script>

<?php
$this->load->view('common/bs4/nav_2_v');