<?php $this->load->view('assets/biggora_autocomplete'); ?>

<div class="row">
    <div class="col-md-12">
        <input id="demo5" type="text" class="col-md-12 form-control" placeholder="Buscar temas..." autocomplete="off" />
    
        <hr/>
        
        <table class="table table-hover bg-blanco">
            <thead>
                <th width="60px">id</th>
                <th>name</th>
            </thead>
            <tbody id="tabla_usuarios">
                
            </tbody>
        </table>
    </div>
</div>

<script>
    $(document).ready(function(){
        $('#demo5').typeahead({
            ajax: {
                url: '<?= base_url() ?>app/arr_elementos_ajax/tema',
                method: 'post',
                triggerLength: 2
            },
            onSelect: displayResult
        });
    });

    function displayResult(item)
    {
        $('#tabla_usuarios').append('<tr><td>' + item.value + '</td><td>' + item.text + '</td></tr>');
        $('#demo5').val('');
    }
</script>

