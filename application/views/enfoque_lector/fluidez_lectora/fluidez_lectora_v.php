<?php $this->load->view('enfoque_lector/fluidez_lectora/style_v') ?>

<div id="fluidezLectora">
    <div class="center_box_750">
        <div class="progress my-3 w-100" style="height: 2px;">
            <div class="progress-bar bg-primary" id="time-progress-bar" role="progressbar" style="width: 0%;"></div>
        </div>

        <!-- PRESENTACIÓN -->
        <div v-show="seccion == 'presentacion'" class="seccion">
            <h3 class="text-center">¿Cuántas palabras lees en un minuto?</h3>
            <p>
                En este test de lectura rápida podrás determinar tu velocidad de lectura y comprensión mediante una
                corta lectura con la que podrás descubrir las palabras que lees por minuto y determinar si lees de
                manera eficiente.
            </p>
            <h3 class="text-center">¿Cómo saber qué tan rápido lees?</h3>
            <p>Este test de lectura diagnostica tu capacidad para leer a velocidades más altas con buena comprensión y
                permite planear estrategias de mejorarniento para superar las dificultades de pronunciación, entonación
                y velocidad fundamentales para una adecuada comprensión.
            </p>
            <p>
                Así que descubre ahora mismo si es algo en lo que debas trabajar.
            </p>
            <h3 class="text-center">¿Cuánto es lo más rápido que se puede leer?</h3>
            <p>
                En este test vas a saber cuanto es lo más rápido que puedes leer y lo que puedes llegar a leer. Por lo
                general las personas leen en un promedio de 150 a 200 palabras por minuto Lee el texto y cuando escuches
                la señal, detente y marca la última palabra leída. El sistema te informará tu promedio de lectura y te
                permitirá proyectar tu objetivo de mejoramiento
            </p>
            <div class="text-center">
                <button class="btn btn-warning btn-lg" v-on:click="seccion = 'preparacion'">
                    CONTINUAR
                </button>
            </div>
        </div>

        <div v-show="seccion == 'preparacion'" class="seccion">
            <p>Tienes un minuto para leer el texto que aparecerá en pantalla.</p>
            <p>Pasados los 60 segundos haz clic en la palabra hasta la que llegaste</p>
            <div class="text-center">
                <button class="btn btn-warning btn-lg" v-on:click="iniciarLectura">
                    INICIAR LECTURA
                </button>
            </div>
        </div>

        <div v-show="seccion == 'lectura'" class="contenido-lectura-dinamica">
            <h2 class="titulo-lectura">{{ row.nombre_post }}</h2>
            <div id="lectura-dinamica" v-html="elementos.lectura_dinamica" v-show="status == 'leyendo'"></div>

            <div v-show="mostrarResultado == true">
                <div class="alert alert-success text-center">
                    <strong>Resultado: </strong><b>{{ palabrasPorMinuto }}</b> palabras por minuto
                </div>
                <div class="mb-2 text-center">
                    <button class="btn btn-light" v-on:click="reiniciarApp">
                        Reiniciar
                    </button>
                </div>
            </div>

            <div id="lectura-dinamica-resultado" v-html="elementos.lectura_dinamica" v-show="status == 'resultado'"></div>
        </div>

        <div>

        </div>
    </div>

    <!-- Modal final del tiempo -->
    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Se acabó el tiempo</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Haz click en la palabra hasta la cual llegaste en la lectura
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>
</div>

<?php $this->load->view('enfoque_lector/fluidez_lectora/script_v') ?>