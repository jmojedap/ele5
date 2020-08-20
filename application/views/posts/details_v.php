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