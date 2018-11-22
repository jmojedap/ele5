<table class="tabla-transparente">
    <tbody>
        <tr>
            <td>Clave</td>
            <td><?= $row->clave ?></td>
        </tr>
        <tr>
            <td>Respuesta</td>
            <td id="respuesta_quiz" class="resaltar"></td>
        </tr>
    </tbody>
</table>

<hr/>

<h3 class="resaltar"><?= $row_tipo_quiz->enunciado ?></h3>

<h4>
    <?= $row->texto_enunciado ?>
</h4>

<?= $this->load->view($vista_c) ?>



