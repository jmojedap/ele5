<script>
var sectionId = '<?= $this->uri->segment(2) . '_' . $this->uri->segment(3) ?>'
var nav2RowId = '<?= $row->id ?>'
var sections = [
    {
        id: 'temas_explore',
        text: '< Temas',
        cf: 'temas/explore/',
        roles: [0,1,2],
        anchor: true,
    },
    {
        id: 'temas_info',
        text: 'Información',
        cf: 'temas/info/' + nav2RowId,
        roles: [0,1,2],
        anchor: true
    },
    {
        id: 'temas_paginas',
        text: 'Páginas',
        cf: 'temas/paginas/' + nav2RowId,
        roles: [0,1,2],
        anchor: true
    },
    {
        id: 'temas_preguntas',
        text: 'Preguntas',
        cf: 'temas/preguntas/' + nav2RowId,
        roles: [0,1,2],
        anchor: true
    },
    {
        id: 'temas_quices',
        text: 'Evidencias',
        cf: 'temas/quices/' + nav2RowId,
        roles: [0,1,2],
        anchor: true
    },
    {
        id: 'temas_relacionados',
        text: 'Relacionados',
        cf: 'temas/relacionados/' + nav2RowId,
        roles: [0,1,2],
        anchor: true
    },
    {
        id: 'temas_preguntas_abiertas',
        text: 'Recursos',
        cf: 'temas/preguntas_abiertas/' + nav2RowId,
        roles: [0,1,2],
        anchor: true
    },
    {
        id: 'temas_editar',
        text: 'Editar',
        cf: 'temas/editar/edit/' + nav2RowId,
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
if ( sectionId == 'temas_lecturas_dinamicas' ) nav_2[6].class = 'active'
if ( sectionId == 'temas_archivos' ) nav_2[6].class = 'active'
if ( sectionId == 'temas_links' ) nav_2[6].class = 'active'
if ( sectionId == 'temas_copiar' ) nav_2[1].class = 'active'
</script>

<?php
$this->load->view('common/bs4/nav_2_v');