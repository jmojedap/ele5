<?php
    $current_title = $head_title;
    if ( strlen($head_title) > 25 ) {
        $current_title = substr($head_title,0,25) . '...';
    }
?>

<nav aria-label="breadcrumb">
  <ol class="breadcrumb">
    <?php foreach ( $breadcrumb as $bc_item ) : ?>
        <li class="breadcrumb-item"><a href="<?= $bc_item['url'] ?>"><?= $bc_item['title'] ?></a></li>
    <?php endforeach ?>
    <li class="breadcrumb-item active" aria-current="page"><?= $current_title ?></li>
  </ol>
</nav>