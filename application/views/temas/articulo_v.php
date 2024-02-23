<style>
    @import url('https://fonts.googleapis.com/css2?family=Signika+Negative:wght@400;700&display=swap');

    body {
        background: rgb(190,231,253);
        background: linear-gradient(90deg, rgba(190,231,253,1) 0%, rgba(144,213,249,1) 100%);
        padding-top: 1em;
        padding-bottom: 2em;
    }
    
    .articulo-container {
        margin: 0px auto;
        max-width: 770px;
    }

    .articulo-tema{
        font-family: 'Signika Negative', serif;
        padding: 3em 4em;
        background-color: #FFFFFC;
        border-radius: 0.3em;
        font-size: 18px;
        color: #222;
        -webkit-box-shadow: 10px 10px 5px 0px rgba(0,0,0,0.20);
        -moz-box-shadow: 10px 10px 5px 0px rgba(0,0,0,0.20);
        box-shadow: 10px 10px 5px 0px rgba(0,0,0,0.20);
    }

    .articulo-tema p {
        text-align: justify;
    }

    .articulo-tema img {
        /*margin: 0 1em;*/
    }

    .articulo-tema h1.articulo-titulo{
        font-weight: bold;
        font-family: 'Signika Negative', serif;
        /*color: #444;*/
        color: #2b4193;
    }

    .articulo-tema .subtitulo{
        text-align: left;
        color: #555;
        font-weight: bold;
        font-size: 1.7em;
        color: #c53c99;
    }

    .articulo-tema .epigrafe{
        font-size: 1.1em;
        padding: 1em;
        border: 1px solid #bcc6eb;
    }

    .articulo-tema h2,h3,h4,h5{
        font-family: 'Signika Negative', serif;
    }

    .articulo-tema h2 {
        /*font-weight: bold;*/
        padding: 0.2em 0.5em;
        color: white;
        background-color: #2b4193;
        border-radius: 0em 15px 15px 0em;
        font-size: 1.2em;
        border-left: 3px solid #f4a827;
    }

    .articulo-tema b{
        color: #dd900c;
    }

    /* Pantallas pequeñas */
    @media (max-width: 767px) {
        .articulo-tema { 
            padding: 1em 1.5em;
            border-radius: 0px;
        }

        .articulo-tema h1,h2,h3,h4,h5,h6{
            text-align: left;
        }
    }
</style>

<?php
    $preview = $this->input->get('preview');
?>

<div id="readArticleApp">
    <div class="articulo-container">
        <?php if ( $preview == 1 ) : ?>
            <div class="mb-2">
                <a href="<?= URL_ADMIN . "posts/edit/{$row->id}" ?>" class="btn btn-light btn-sm me-1 w100p">Editar</a>
                <a href="<?= URL_ADMIN . "temas/articulos/{$row->referente_1_id}" ?>" class="btn btn-light btn-sm w100p" title="Ir al tema al que pertenece el artículo">Tema</a>
            </div>
        <?php endif; ?>
        <div class="articulo-tema">
            <p class="text-center text-muted">{{ numPage }}</p>
            <h1 class="articulo-titulo"> {{ articulo.nombre_post }}</h1>
            <p class="subtitulo">{{ articulo.subtitle }}</p>
            <p class="epigrafe" v-show="articulo.resumen.length > 1">{{ articulo.resumen }}</p>
            <div class="contenido" v-html="articulo.contenido"></div>
        </div>
    </div>    
</div>

<script>
var readArticleApp = createApp({
    data(){
        return{
            articulo: <?= json_encode($row) ?>,
            loading: false,
            numPage: 1,
        }
    },
    methods: {
        
    },
    mounted(){
        //this.getList()
    }
}).mount('#readArticleApp')
</script>