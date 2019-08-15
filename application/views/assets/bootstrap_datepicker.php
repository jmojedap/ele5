<link href="<?php echo URL_ASSETS ?>bootstrap_datepicker/css/bootstrap-datepicker.css" rel='stylesheet' />
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.8.0/js/bootstrap-datepicker.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.8.0/locales/bootstrap-datepicker.es.min.js"></script>

<script>
// Document Ready
//-----------------------------------------------------------------------------

    $(document).ready(function()
    {
        $('.bs_datepicker').datepicker({
            format: "yyyy-mm-dd",
            daysOfWeekHighlighted: "0,6",
            language: "es",
            weekStart: 0 // day of the week start. 0 for Sunday - 6 for Saturday
        });
        
    });
</script>