
//Fancybox para ventana modal
$('a.target_modal').click(function(){
            open_resource_modal($(this).attr('href'), $(this).data('mwidth'), $(this).data('mheight'));
    });

    function open_resource_modal(theURL, theWidth, theHeight){
            $.fancybox.open([
                    {
                        href: theURL,
                        width: theWidth,
                        height: theHeight
                    }   
            ], {
                    padding : 0   
            });
    }
        
//Fin de fancybox para ventana modal