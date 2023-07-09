<script>
    var sectionId = '<?= $this->uri->segment(2) . '_' . $this->uri->segment(3); ?>'
    var sections = [
        {
            text: 'Explorar',
            id: 'posts_explore',
            cf: 'posts/explore',
            roles: [0,1,2,]
        },
        {
            text: 'Importar',
            id: 'posts_import',
            cf: 'posts/import',
            roles: [0,1]
        },
        {
            text: 'Nuevo',
            id: 'posts_add',
            cf: 'posts/add',
            roles: [0,1,2]
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
$this->load->view('common/bs4/nav_2_v');