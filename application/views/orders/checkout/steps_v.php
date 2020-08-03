<?php
    $pct = number_format($step * 33.33, 0);
?>
<div class="center_box_750 mb-3">
    <div class="progress">
        <div
            class="progress-bar"
            role="progressbar"
            style="width: <?php echo $pct ?>%;"
            aria-valuenow="<?php echo $pct ?>"
            aria-valuemin="0"
            aria-valuemax="100">
            Paso <?php echo $step ?>/3
        </div>
    </div>
</div>