<script>
    var sectionId = '<?= $this->uri->segment(1) . '_' . $this->uri->segment(2); ?>'
    var sections = [
        {
            text: 'Explorar',
            id: 'temas_explore',
            cf: 'temas/explore',
            roles: [0,1,2]
        },
        {
            text: 'Nuevo',
            id: 'temas_nuevo',
            cf: 'temas/nuevo/add',
            roles: [0,1,2],
            anchor: true
        },
        {
            text: 'Importar',
            id: 'temas_importar',
            cf: 'temas/importar',
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
</script>

<?php
$this->load->view('common/bs4/nav_2_v');