<script>
var sectionId = '<?= $this->uri->segment(2) . '_' . $this->uri->segment(3) ?>'
var sections = [
    {
        id: 'files_explore',
        text: 'Explorar',
        cf: 'admin/files/explore',
        roles: [0,1,2]
    },
    {
        id: 'files_add',
        text: 'Cargar',
        cf: 'admin/files/add',
        roles: [0,1,2]
    }
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