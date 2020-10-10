<script>
// Variables
//-----------------------------------------------------------------------------
    var base_url = '<?php echo base_url() ?>';
    var resultado = 0;
    var quiz_id = '<?php echo $row->id; ?>';
    var usuario_id = '<?php echo $this->session->userdata('usuario_id'); ?>';

// FUNCIONES
//-----------------------------------------------------------------------------
    function guardar_resultado(){
        $.ajax({        
            type: 'POST',
            url: base_url + 'quices/guardar_resultado',
            data: {
                usuario_id : usuario_id,
                quiz_id : quiz_id,
                resultado : resultado
            },
            success: function(response){
                console.log('ua_id:' + response);
            }
        });
    }
</script>

<style>
    h1{
        color: #da4631;
        font-weight: bold;
    }

    .firts_container{
        width: 800px;
        margin: 0 auto;
        padding-bottom: 10px;
    }

    .quiz_header{
        padding: 0 10px;
    }

    #quiz_container{
        position: relative;
        min-height: 450px;
    }

    .img_bg{
        width: 100%;
    }

    .draggable{
        box-sizing: border-box; 
        min-width: 64px; 
        min-height: 24px; 
        position: absolute;
        cursor: move;
        text-align: center;
        font-family: Calibri, Helvetica, Arial, sans-serif;
        font-size: 14px;
        line-height: 20px;
        font-weight: bold;
        overflow: hidden;
    }

    .draggable:hover{
        opacity: 0.9;
        -webkit-box-shadow: 0px 0px 0px 2px rgba(129,212,250,1);
        -moz-box-shadow: 0px 0px 0px 2px rgba(129,212,250,1);
        box-shadow: 0px 0px 0px 2px rgba(129,212,250,1);
    }

    .correct{
        border: 1px solid #c5e1a5;
        -webkit-box-shadow: 0px 0px 5px 3px rgba(197,225,165,1);
        -moz-box-shadow: 0px 0px 5px 3px rgba(197,225,165,1);
        box-shadow: 0px 0px 5px 3px rgba(197,225,165,1);
    }
</style>

<div class="firts_container">
    <div class="quiz_header">
        <a href="<?php echo base_url() ?>">
            <img class="float-right mt-2" width="100px" alt="Logo En Línea Editores" src="<?php echo URL_IMG ?>admin/logo_enlinea.png" />
        </a>
        <h1><?php echo $head_title ?></h1>
        <p style="font-size: 1.2em;">
            <i class="fa fa-info-circle text-success"></i>
            <?php if ( strlen($row->texto_enunciado) > 0 ) { ?>
                <?php echo $row->texto_enunciado ?>
            <?php } else { ?>
                <?php echo $row_tipo_quiz->enunciado ?>
            <?php } ?>
        </p>
    </div>
    <?php $this->load->view($view_a) ?>
    <div class="text-center mt-2">
        <button class="btn btn-lg btn-success" id="btn_check_results">
            <i class="fa fa-check"></i>
            Verificar
        </button>
    </div>
</div>