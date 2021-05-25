<!-- AUDIOS -->
<div class="d-none">
    <audio id="audio_answer" controls class="audio">
        <source type="audio/mpeg" src="<?= URL_RESOURCES ?>audio/answer_1.mp3">
    </audio>
    <audio id="audio_wrong" controls class="audio">
        <source type="audio/mpeg" src="<?= URL_RESOURCES ?>audio/click_1.mp3">
    </audio>
    <audio id="audio_win" controls class="audio">
        <source type="audio/mpeg" src="<?= URL_RESOURCES ?>audio/win_1.mp3">
    </audio>
    <audio id="audio_game_over" controls class="audio">
        <source type="audio/mpeg" src="<?= URL_RESOURCES ?>audio/game_over_1.mp3">
    </audio>
</div>

<div class="firts_container">
    <div class="quiz_header">
        <a href="<?= base_url() ?>">
            <img class="float-right mt-2" width="100px" alt="Logo En Línea Editores" src="<?= URL_IMG ?>admin/logo_enlinea.png" />
        </a>
        <h1><?= $row_tema->nombre_tema ?></h1>
        <p style="font-size: 1.2em;">
            <i class="fa fa-info-circle text-success"></i>
            <?php if ( strlen($row->texto_enunciado) > 0 ) { ?>
                <?= $row->texto_enunciado ?>
            <?php } else { ?>
                <?= $row_tipo_quiz->enunciado ?>
            <?php } ?>
        </p>
    </div>
    <?php $this->load->view($view_a) ?>
    <div class="text-center mt-2">
        <button class="btn btn-primary btn-lg" onclick="resolverQuiz.verificar_resultados()">
            <i class="fa fa-check"></i> Verificar
        </button>
    </div>
</div>