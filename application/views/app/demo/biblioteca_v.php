<style>
    .titulo_area{
        color: white;
    }
</style>

<div id="library_app">

    <div class="card-deck">
        <div class="card shadow" v-for="book in books">
            <a href="<?php echo base_url("flipbooks/leer_demo/1605") ?>" target="_blank">
                <img class="card-img-top" v-bind:src="`<?php echo URL_IMG . 'demo/book_' ?>` + book.area_id + `.jpg`" alt="Card image cap">
            </a>
            <div class="card-body">
                <h4 class="card-title titulo_area text-center" v-bind:style="`background-color: `+ book.bg_color +``">{{ book.title }}</h4>
                <h6 class="card-subtitle mt-3">{{ book.subtitle }}</h6>
                <p class="card-text">{{ book.abstract }}</p>
                <div class="progress" style="height: 5px;">
                    <div
                        class="progress-bar progress-bar-striped progress-bar-animated"
                        role="progressbar"
                        aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"
                        v-bind:style="`width: ` + book.pct + `%;`"
                        v-bind:class="book.pct_class"
                        >
                    </div>
                </div>
                <small class="text-muted">Tema 6 de {{ book.quan_themes }}</small>
            </div>
            <ul class="list-group list-group-flush" v-if="book.tests.length">
                <li class="list-group-item">
                    Evaluaciones
                </li>
                <li class="list-group-item" v-for="test in book.tests">
                    <i v-if="test.days_left < 5" class="fa fa-exclamation-circle text-danger"></i>
                    <a href="#">{{ test.title }}</a>
                    <small class="text-muted" v-show="test.days_left > 2">(Quedan {{ test.days_left }} días) </small>
                    <span class="text-danger" v-show="test.days_left <= 2">(Quedan {{ test.days_left }} días) </span>
                    <button class="btn btn-success btn-sm float-right">Iniciar</button>
                </li>
            </ul>
            <div class="card-footer">
                <a href="<?php echo base_url("flipbooks/leer_demo/1605") ?>" target="_blank" class="btn btn-primary"><i class="fa fa-book"></i> Abrir</a>
                <a href="#" class="btn btn-secondary">Mis notas</a>
            </div>
        </div>
    </div>
</div>

<script>
    new Vue({
        el: '#library_app',
        data: {
            books: [
                {
                    area_id: 50,
                    bg_color: '#006BAB',
                    title: 'Matemáticas',
                    subtitle: 'Funciones trigonométricas',
                    abstract: 'Las funciones trigonométricas son las funciones establecidas con el fin de extender...',
                    pct: '10',
                    pct_class: 'bg-danger',
                    quan_themes: '<?php echo rand(35,45) ?>',
                    tests: [
                        {
                            'uc_id': '1',
                            'title': 'Primer periodo',
                            'days_left' : 5
                        },
                        {
                            'uc_id': '3',
                            'title': 'Segundo periodo',
                            'days_left' : 7
                        },
                    ]
                },
                {
                    area_id: 51,
                    bg_color: '#A678C3',
                    title: 'Castellano',
                    subtitle: 'Descripción de escenarios',
                    abstract: 'El Palacio de Bellas Artes es un recinto cultural ubicado en el Centro Histórico de la ...',
                    pct: '25',
                    pct_class: 'bg-warning',
                    quan_themes: '<?php echo rand(35,45) ?>',
                    tests: [
                        {
                            'uc_id': '1',
                            'title': 'Temas iniciales',
                            'days_left' : 2
                        }
                    ]
                },
                {
                    area_id: 52,
                    bg_color: '#04BDBF',
                    title: 'Ciencias Sociales',
                    subtitle: 'Empecemos',
                    abstract: 'Inicia el conjunto de temas que hemos preparado para aprender las ciencias sociales como...',
                    pct: '50',
                    pct_class: 'bg-primary',
                    quan_themes:'<?php echo rand(35,45) ?>',
                    tests: []
                },
                {
                    area_id: 53,
                    bg_color: '#86BC42',
                    title: 'Ciencias Naturales',
                    subtitle: 'Tipos de suelo',
                    abstract: 'Fallou incluye en su estudio la mayoría de los caracteres concernientes al suelo: historia...',
                    pct: '90',
                    pct_class: 'bg-success',
                    quan_themes: '<?php echo rand(35,45) ?>',
                    tests: [
                        {
                            'uc_id': '1',
                            'title': 'Geología básica',
                            'days_left' : 12
                        }
                    ]
                }
            ]
        },
        methods: {}
    });
</script>