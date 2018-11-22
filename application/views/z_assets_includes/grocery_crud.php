<?php foreach($css_files as $file): ?>
        <link type="text/css" rel="stylesheet" href="<?php echo $file; ?>" />
<?php endforeach; ?>

<?php foreach($js_files as $file): ?>
        <script src="<?php echo $file; ?>"></script>
<?php endforeach; ?>

<script>
    $(document).ready(function(){
        //Ajustes para tema bootstrap de grocery crud
            $('textarea').addClass('form-control');
        
        //Ajuste chosen downdrop
            $('.chzn-container').css('width', '300px');
            $('.chzn-drop').css('width', '300px');
            $('.chzn-drop').css('width', '300px');
            $('.chzn-search input').css('width', '280px');
    });
</script>