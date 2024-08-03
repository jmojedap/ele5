<script>
var chatEle = createApp({
    data(){
        return{
            loading: false,
            iaChatRespuesta: 'Lorem ipsum dolor sit amet consectetur adipisicing elit. Magnam nostrum ratione odit sapiente expedita. Doloribus perferendis consequuntur ut! Expedita error incidunt commodi ex ducimus, earum quidem nesciunt facere quia corrupti?',
            iaChatMensajes: [],
            iaChatInput: '',
            iaLoading: false,
            iaPreguntas: [
                {'filename_answer':'adicion.txt','texto': '¿Cuál es la diferencia entre adición y sustracción y cuáles son sus términos?'},
                {'filename_answer':'adicion_edad.txt','texto': '¿Cuál es la diferencia entre adición y sustracción y cuáles son sus términos? Crea una respuesta para niños entre 8 y 9 años.'},
                {'filename_answer':'estrategia.txt','texto': 'Crea una estrategia didáctica para explicar la diferencia entre adición y sustracción y cuáles son sus términos.'},
                {'filename_answer':'mercurio.txt','texto': '¿Por que algunos termómetros usan mercurio para su funcionamiento?'},
                {'filename_answer':'pentagrama.txt','texto': '¿Hace cuánto tiempo se inventó el pentagrama?'},
            ],
            iaFilenameAnswer: 'mercurio.txt',
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
            var formValues = new FormData(document.getElementById('ia-chat-form'))
            axios.post(URL_API + 'chat_ele/get_answer/', formValues)
            .then(response => {
                if (response.data.answer.length > 0) {
                    typeText(response.data.answer, 5);
                }
            })
            .catch( function(error) {console.log(error)} )
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
            this.iaChatInput = pregunta.texto
            this.iaFilenameAnswer = pregunta.filename_answer
        }
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