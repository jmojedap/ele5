$('#the_submit').click(function(e) {
    updateArray();

    $.ajax({        
        type: 'POST',
        url: '<?= base_url() ?>flipbooks/test/',
        data: {numbers : the_numbers},
        success: function(data) {
            $("#receptor").load("<?php echo base_url() ?>flipbooks/data/");
        }
    }); 

});	