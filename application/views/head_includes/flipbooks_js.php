    <script type="text/javascript" language="javascript" src="<?= base_url() ?>js/turn.min.js"></script>

        <script type="text/javascript">
            $(window).bind('keydown', function(e){

                if (e.keyCode==37)
                        $('#flipbook').turn('previous');
                else if (e.keyCode==39)
                        $('#flipbook').turn('next');

            });
        </script>