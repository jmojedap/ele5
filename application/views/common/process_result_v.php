<?php if ( $this->session->flashdata('result') != NULL ): ?>
    <?php
        $result = $this->session->flashdata('result');
        $class = 'alert-info';
        $icon = 'fa-info-circle';
        if ( ! is_null($result['class'] ) ) { $class = $result['class']; }
        if ( ! is_null($result['icon'] ) ) { $icon = $result['icon']; }
    ?>
    <br/>
    <div class="alert <?php echo $class ?>" role="alert">
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
        
        <i class="fa <?php echo $icon ?>"></i>
        <?php echo $result['message'] ?>
    </div>

    <?php if ( isset($result['html']) ) { ?>
        <div class="">
            <?php echo $result['html'] ?>
        </div>
    <?php } ?>
<?php endif ?>