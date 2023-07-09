<?php
    $creator = $this->Db_model->row_id('users', $row->creator_id);
    $manzana = $this->Db_model->row_id('posts', $row->related_2);
?>

<div class="center_box_750">
<table class="table bg-white">
            <tbody>
                <tr>
                    <td class="td-title">ID</td>
                    <td>
                        <span class="lead text-primary"><?= $row->id ?></span>
                    </td>
                </tr>
                <tr>
                    <td class="td-title">Tipo</td>
                    <td>Sesión Escuela Hombres al Cuidado</td>
                </tr>
                <tr>
                    <td class="td-title">Nombre</td>
                    <td><?= $row->post_name ?></td>
                </tr>
                <tr>
                    <td class="td-title">Módulo y Sesión</td>
                    <td>M<?= $row->integer_1 ?>:S<?= $row->integer_2 ?></td>
                </tr>
                <tr>
                    <td class="td-title">Fecha</td>
                    <td>
                        <?= $this->pml->date_format($row->date_1, 'Y-m-d H:i'); ?>
                        &middot; <span class="text-muted"><?= $this->pml->ago($row->date_1); ?></span>
                    </td>
                </tr>

                <tr>
                    <td class="td-title">Manzana</td>
                    <td>
                        <a href="<?= URL_ADMIN . "posts/info/{$manzana->id}" ?>">
                            <?= $manzana->post_name ?>
                        </a>
                    </td>
                </tr>
                <tr>
                    <td class="td-title">Dirección</td>
                    <td><?= $manzana->text_1 ?></td>
                </tr>
                <tr>
                    <td class="td-title">Localidad</td>
                    <td><?= $this->Item_model->name(121,$row->related_1); ?></td>
                </tr>
                <tr>
                    <td class="td-title">Observaciones</td>
                    <td><?= $row->excerpt ?></td>
                </tr>
            </tbody>
        </table>

        <table class="table bg-white">
            <tbody>
                <tr>
                    <td class="td-title">Creada por</td>
                    <td>
                        <?= $row->updater_id ?> &middot;
                        <a href="<?= URL_ADMIN . "users/profile/{$row->creator_id}" ?>">
                            <?= $this->App_model->name_user($row->creator_id); ?>
                        </a>
                    </td>
                </tr>
                <tr>
                    <td class="td-title">Creada en</td>
                    <td>
                        <?= $row->created_at ?> <br>
                        <?= $this->pml->ago($row->created_at); ?>
                    </td>
                </tr>
                <tr>
                    <td class="td-title">Actualizada por</td>
                    <td>
                        <?= $row->updater_id ?> &middot;
                        <a href="<?= URL_ADMIN . "users/profile/{$row->updater_id}" ?>">
                            <?= $this->App_model->name_user($row->updater_id); ?>
                        </a>
                    </td>
                </tr>
                <tr>
                    <td class="td-title">Actualizada en</td>
                    <td>
                        <?= $row->updated_at ?> <br>
                        <?= $this->pml->ago($row->updated_at); ?>
                    </td>
                </tr>
            </tbody>
        </table>
</div>