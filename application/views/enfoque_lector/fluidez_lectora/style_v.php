<style>
@import url('https://fonts.googleapis.com/css2?family=Signika+Negative:wght@400;700&display=swap');

body {
    background-color: #FFF;
}

.seccion {
    min-height: calc(100vh - 100px);
    display: flex;
    flex-direction: column;
    align-items: center; /* Centra los elementos en el eje transversal (vertical en este caso) */
    justify-content: center; 
    
}

#time-progress-bar {
    transition: width <?= $segundosLectura ?>s linear;
}

#lectura-dinamica {
    text-align: justify;
}

#lectura-dinamica span {
    cursor: pointer
}

#lectura-dinamica span:hover {
    color: #513a00;
    background-color: #ffe5a0;
}

#lectura-dinamica-resultado {
    text-align: justify;
}

#lectura-dinamica-resultado span.active {
    color: #513a00;
    background-color: #ffe5a0;
}

.contenido-lectura-dinamica{
    font-family: 'Signika Negative', serif;
    font-size: 1.2em;
}

.titulo-lectura {
    color: #b71c1c;
    text-align: center;
}
</style>