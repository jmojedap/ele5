<?php
    $nivel = 5;
    if ( ! is_null($this->input->get('n')) ) {
        $nivel = $this->input->get('n');
    }
    $unidad = 1;
    if ( ! is_null($this->input->get('unidad')) ) {
        $unidad = $this->input->get('unidad');
    }
?>

<script>
// Variables
//-----------------------------------------------------------------------------
const nivel = <?= $nivel ?>;
const unidad = <?= $unidad ?>;

// VueApp
//-----------------------------------------------------------------------------
var chatEle = createApp({
    data(){
        return{
            loading: false,
            iaChatRespuesta: 'Lorem ipsum dolor sit amet consectetur adipisicing elit. Magnam nostrum ratione odit sapiente expedita. Doloribus perferendis consequuntur ut! Expedita error incidunt commodi ex ducimus, earum quidem nesciunt facere quia corrupti?',
            iaChatMensajes: [],
            iaChatInput: '',
            iaLoading: false,
            preguntas: <?= json_encode($preguntas) ?>,
            iaFilenameAnswer: 'mercurio.txt',
            respuesta:'',
            unidad: unidad,
            nivel: nivel
        }
    },
    methods: {
        sendIAMessage: function(){
            this.iaLoading = true
            console.log(this.iaChatInput)
            var chatElemento = {
                'user':'Mauricio',
                'texto': this.iaChatInput,
            }
            this.agregarIAMensaje(chatElemento)
            this.iaChatInput = ''
            this.loadIARespuesta()
        },
        loadIARespuesta: function(){
            typeText(this.respuesta, 10);
            /*var formValues = new FormData(document.getElementById('ia-chat-form'))
            axios.post(URL_API + 'chat_ele/get_answer/', formValues)
            .then(response => {
                if (response.data.answer.length > 0) {
                    typeText(response.data.answer, 5);
                }
            })
            .catch( function(error) {console.log(error)} )*/
        },
        agregarIAMensaje(chatElemento){
            this.iaChatMensajes.push(chatElemento)
        },
        classIAMensaje(chatElemento){
            if ( chatElemento.user == 'ele') {
                return 'chat-respuesta'
            }
            return 'chat-pregunta'
        },
        setIAInput: function(pregunta){
            this.iaChatInput = pregunta.enunciado_pregunta
            this.respuesta = pregunta.respuesta
        },
        showPregunta: function(pregunta){
            if ( pregunta.nivel != this.nivel ) return false
            if ( pregunta.numero_unidad != this.unidad ) return false
            return true
        },
    },
    mounted(){
        //this.getList()
    }
}).mount('#chatEle')


// Mostrar texto caracter por caracter
//-----------------------------------------------------------------------------
function typeText(text, interval) {
    const container = document.getElementById('typing-respuesta');
    let index = 0;
    container.textContent = ''
    
    function showText() {
        if (index < text.length) {
            container.textContent += text[index];
            index++;
        } else {
            clearInterval(intervalId);
            var chatElemento = {
                'user':'ele',
                'texto': text
            }
            chatEle.agregarIAMensaje(chatElemento)
            chatEle.iaLoading = false
        }
    }
    
    const intervalId = setInterval(showText, interval);
}
</script>