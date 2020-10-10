<?php
    $cant_elementos = $elementos->num_rows() - 1;
    $key_elemento = 0;
    
    foreach ($elementos->result() as $row_elemento) {
        $respuesta .= '"",';
    }
?>

<div id="quiz_app">

</div>

<script>
    var respuestas = [];
    <?php for ($i = 0; $i <= $cant_elementos; $i++) { ?>
    respuestas[<?= $i ?>] = '';
    <?php } ?>

    var correctas = [
    <?php foreach ($elementos->result() as $row_elemento ) { ?>
        '<?= $row_elemento->detalle ?>',
    <?php } ?>
    ];
</script>

<script>
    new Vue({
        el: '#quiz_app',
        created: function(){
            //this.get_list();
        },
        data: {
            resultado: 0,
            quiz_id: '<?php echo $row->id ?>',
            usuario_id: '<?php echo $this->session->userdata('usuario_id') ?>',
            elementos: <?= json_encode($elementos->result()) ?>
            respuestas: respuestas
        },
        methods: {
            
        }
    });
</script>