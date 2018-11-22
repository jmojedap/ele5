<script>
    //Variables
    var default_cod_quiz = '<?= $row_tema->cod_tema ?>q';
    var default_nombre_quiz = '<?= $row_tema->nombre_tema ?>';
</script>

<script>
    $(document).ready(function(){
        $('#field-cod_quiz').val(default_cod_quiz);
        $('#field-nombre_quiz').val(default_nombre_quiz);
    });
</script>

<?= $this->load->view('quices/menu_explorar_v') ?>
<?= $output; ?>