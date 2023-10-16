<style>
    @import url('https://fonts.googleapis.com/css2?family=Roboto+Slab:wght@400;700&display=swap');
    @import url('https://fonts.googleapis.com/css2?family=Signika+Negative:wght@400;700&display=swap');
    @import url('https://fonts.googleapis.com/css2?family=Rubik&display=swap');

    body {
        background: rgb(190,231,253);
        background: linear-gradient(90deg, rgba(190,231,253,1) 0%, rgba(144,213,249,1) 100%);
        font-family: 'Rubik', sans-serif;
        padding-bottom: 60px;
    }

    .footer{
        color: white;
        background-color: #d5effd;
        width: 100%;
        border-top: 1px solid #CCC;
        padding: 0.5em;
    }

    .footer .btn-light {
        color: #2b4193;
        border-color: #d5effd;
        background-color: #d5effd;
    }

    .footer .btn-light:hover {
        color: #333;
        border-color: #ffba02;
        background-color: #ffba02;
    }

    .accordion-item{
        border-radius:0px !important;
    }
    .accordion-item .accordion-button{
        border-radius:0px !important;
    }

    .contenido-layout {
        display: grid;
        grid-template-columns: 1fr 5fr;
        height: calc(100vh - 60px);
    }

    .column-left {
        grid-column: 1;
        align-items: center;
        justify-content: center;
        background-color: #FCFCFC;
        
    }

    .column-right {
        grid-column: 2;
        overflow-y: scroll;
    }

    .articulo-container {
        margin: 0 auto;
        margin-bottom: 1em;
        max-width: 770px;
        padding-top: 1em;
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
        font-family: 'Roboto Slab', serif;
    }

    .articulo-tema h2 {
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

    .tema-link {
        width: 100%;
        padding: 0.5em;
    }

    .tema-link:hover{
        background-color: #e7f1ff;
        cursor: pointer;
    }

    .tema-link.active{
        background-color: #c53c99;
        color: #FFF;
    }

    video{
        width: 100%;
        min-height: 480px;
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