<link href="https://fonts.googleapis.com/css?family=Merriweather&display=swap" rel="stylesheet">

<style>
    #diccionario {
        background-color: #fffEFA;
        padding: 2em;
    }

    #diccionario_contenido {
        text-align: justify;
        font-family: 'Merriweather', serif;
    }

    #diccionario span.palabra{
        color: #01579b;
        background: #b3e5fc;
        padding: 0 2px 0 2px;
        cursor: pointer;
        -webkit-border-radius: 2px;
        -moz-border-radius: 2px;
        border-radius: 2px;
    }

    #diccionario span.resaltada{
        color: #01579b;
        background: #b3e5fc;
        cursor: pointer;
        -webkit-border-radius: 2px;
        -moz-border-radius: 2px;
        border-radius: 2px;
    }

    #diccionario hr {
        display: block;
        border: 1px solid red;
    }
</style>
<script>
    var text = '<span>Javier</span> <span>Mauricio</span> <span>Ojeda</span> <span>Pepinosa</span>';
    var i = 0;

    $(document).ready(function(){

        var palabras = $("#diccionario_contenido span");

        $('#btn_play').click(function(){
            /*next_word(i);
            i++;*/
            
            //$("#texto_prueba span").addClass('palabra');
            for (let index = 0; index < palabras.length; index++) {
                next_word(index);
            }

        });

        function next_word(i)
        {
            setTimeout(() => {
                var palabra = palabras[i];
                console.log(i);
                $("#diccionario_contenido span").removeClass('resaltada');
                $(palabra).addClass('resaltada', 'slow');
            }, i * 300);
        }
        

        $(function () {
            $('.palabra').popover({
                container: '#diccionario'
            })
        })

        $('.palabra').hover(function(){
            var definicion = $(this).data('content');
            //$('#definicion').html(definicion);
        });

        $('.palabra').click(function(){
            var definicion = $(this).data('content');
            var titulo = $(this).html();
            console.log(titulo);
            $('#definicion').html(definicion);
            $('#titulo_modal').html(titulo);
            $('#modal_definicion').modal('toggle')
        });
    });

    
</script>


<div id="diccionario">
    <div id="diccionario_contenido">
        <?php if ( ! is_null($diccionario) ) { ?>
            <h4 class="card-title"><?php echo $diccionario->nombre_post ?></h4>
            <div id="diccionario_contenido">
                <?php echo $diccionario->contenido ?>
            </div>
        <?php } ?>
        
        <br><br>
            Hola mundo, aquí vamos a probar los popovers de 
            <span class="palabra" data-toggle="popover" data-trigger="hover" data-placement="top" data-content="Vivamus sagittis lacus vel augue laoreet rutrum faucibus.">Bs4</span>
            . 

        <br>
        <div id="texto_prueba">
            <span>Javier</span> <span class="palabra">Mauricio</span> <span>Ojeda</span> <span>Pepinosa</span>
            <span>Javier</span> <span>Mauricio</span> <span>Ojeda</span> <span>Pepinosa</span>
            <span>Javier</span> <span>Mauricio</span> <span>Ojeda</span> <span>Pepinosa</span>
            <span>Javier</span> <span>Mauricio</span> <span>Ojeda</span> <span>Pepinosa</span>
            <span>Javier</span> <span>Mauricio</span> <span>Ojeda</span> <span>Pepinosa</span>
        </div>

    </div>
    <button class="btn btn-success mt-2" id="btn_play">
        Play
    </button>

    <!-- Modal Definición -->
    <div class="modal fade" id="modal_definicion" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="titulo_modal">Palabra</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div id="definicion"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
            </div>
            </div>
        </div>
    </div>
</div>