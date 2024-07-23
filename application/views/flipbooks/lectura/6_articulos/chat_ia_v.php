<div class="chat-container">
    <div class="chat-messages" id="chat-messages">
        <!-- <p class="chat-mensaje chat-pregunta">
            Lorem ipsum dolor sit amet consectetur adipisicing elit. Magnam nostrum ratione odit sapiente expedita.
            Doloribus perferendis consequuntur ut! Expedita error incidunt commodi ex ducimus, earum quidem nesciunt
            facere quia corrupti?
        </p>
        <p class="chat-mensaje chat-respuesta">
            2 Lorem ipsum dolor sit amet consectetur adipisicing elit. Magnam nostrum ratione odit sapiente expedita.
            Doloribus perferendis consequuntur ut! Expedita error incidunt commodi ex ducimus, earum quidem nesciunt
            facere quia corrupti?
        </p> -->
        <p class="chat-mensaje" v-bind:class="classIAMensaje(mensaje)" v-for="mensaje in iaChatMensajes" v-html="mensaje.texto"></p>
        <div class="text-center" v-show="iaLoading">
            <div class="spinner-border text-secondary" role="status">
            <span class="visually-hidden">Loading...</span>
            </div>
        </div>
        <p class="chat-mensaje chat-respuesta" v-show="iaLoading">
            <span id="typing-respuesta"></span>
            <span class="typing-effect">
                &middot;
            </span>
            
        </p>
    </div>
    <div class="grid-columns-15rem mb-2">
        <div v-for="pregunta in iaPreguntas" class="chat-pregunta-ejemplo" v-on:click="setIAInput(pregunta)">
            {{ pregunta.texto }}
        </div>
    </div>
    <form accept-charset="utf-8" method="POST" id="ia-chat-form" @submit.prevent="sendIAMessage">
        <input type="hidden" name="filename_answer" v-model="iaFilenameAnswer">
        <fieldset v-bind:disabled="iaLoading">
            <div class="chat-input">
                <input type="text" name="question" id="chat-input" v-model="iaChatInput" placeholder="Escribe una pregunta a Chat En Línea Editores">
                <button type="submit"><i class="fas fa-arrow-up"></i></button>
            </div>
        <fieldset>
    </form>
</div>
