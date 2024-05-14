<?php
    $arr_json = array();
    if ( strlen($row->contenido_json) ) { $arr_json = json_decode($row->contenido_json); }
?>

<div class="card center_box_750">
    <div class="card-header">Fields</div>
    <div class="card-body">
        <?php foreach ( $fields as $field ) { ?>
            <span class="text-muted"><?php echo $field ?>:</span>
            <strong class="text-primary"><?= $row->$field ?></strong>
            <span class="text-muted"> | </span>
            
        <?php } ?>
    </div>
</div>

<h3>JSON CONTENT</h3>
<?php print_r($arr_json) ?>