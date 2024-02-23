//Requerir contenidos HTML vía Ajax
function getSections(menuType)
{
    beforeGetSections(menuType);
    axios.get(URL_APP + app_cf + '/?json=' + menuType)
    .then(response => {
        loadSections(response.data, menuType);
    })
    .catch(function(error) { console.log(error) })
}

//Antes de actualizar, limpiar o blanquear secciones
function beforeGetSections(menuType)
{
    $('#view_a').html('')
    $('#view_b').html('')
    
    if ( menuType === 'nav_1' ) { $('#nav_2').html('') }
    if ( menuType === 'nav_2' ) { $('#nav_3').html('') }

    //Especial, para quitar elementos de herramienta de edición enriquecida, plugin SummerNote
    $('.popover').remove()
    $('#loading_indicator').show()
}

//Al recibir datos con contenidos HTML, cargar las secciones
function loadSections(responseData, menuType)
{
    $('#loading_indicator').hide()
    document.title = responseData.head_title
    history.pushState(null, null, URL_APP + app_cf)
    
    $('#head_title').html(responseData.head_title)
    $('#view_a').html(responseData.view_a)
    
    //Si se requirió desde Nav 1
    if ( menuType === 'nav_1')
    {
        $('#nav_2').html(responseData.nav_2)
        $('#nav_3').html(responseData.nav_3)
    }
    
    //Si se requirió desde Nav 2
    if ( menuType === 'nav_2' )
    {
        $('#nav_3').html(responseData.nav_3)
    }
}