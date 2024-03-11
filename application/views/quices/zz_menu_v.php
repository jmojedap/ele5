<script>
var sectionId = '<?= $this->uri->segment(2) . '_' . $this->uri->segment(3); ?>'
var element_id = '<?= $row->id ?>';
var sections = [
    {
        id: 'quices_explore',
        text: '< Quices',
        cf: 'admin/quices/explore/',
        roles: [0,1,9],
        anchor: true
    },
    {    
        text: 'Temas',
        id: 'quices_temas',
        cf: 'admin/quices/temas/' + element_id,
        roles: [0,1,2,9],
        anchor: true
    },
    {    
        text: 'Construir',
        id: 'quices_construir',
        cf: 'admin/quices/construir/' + element_id,
        roles: [0,1,2,9],
        anchor: true
    },
    {    
        text: 'Elementos',
        id: 'quices_elementos',
        cf: 'admin/quices/elementos/' + element_id,
        roles: [0,1,2,9],
        anchor: true
    },
    {
        text: 'Editar',
        id: 'quices_editar',
        cf: 'admin/quices/editar/' + element_id,
        roles: [0,1,3,9],
        anchor: true
    },
    {
        text: 'Detalles',
        id: 'quices_detalle',
        cf: 'admin/quices/detalle/' + element_id,
        roles: [0],
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
$this->load->view('common/nav_2_v');