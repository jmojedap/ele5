<style>
    .chat-container {
        display: flex;
        flex-direction: column;
        width: 720px;
        margin: 0 auto;
        height: calc(100vh - 65px);
    }
    .chat-messages {
        flex: 1;
        padding: 10px;
        overflow-y: auto;
        border-bottom: 1px solid #FFF;
        margin-bottom: 1em;
    }
    .chat-input {
        display: flex;
        height: 52px;
        border-radius: 26px;
        background-color: #FFF;
    }
    .chat-input input {
        flex: 1;
        padding: 10px;
        padding-left: 20px;
        border: none;
        border-radius: 26px 0px 0px 26px;
    }
    .chat-input button {
        padding: 8px;
        border: none;
        background-color: #c53c99;
        color: white;
        width: 40px;
        height: 40px;
        margin-top: 6px;
        margin-right: 6px;
        border-radius: 20px;
    }

    .chat-input input:focus {
        outline: none;
        box-shadow: none;
        border: none;
    }

    .chat-mensaje {
        padding: 1em;
    }

    .chat-pregunta {
        background-color: #c53c99;
        color: #FFF;
        border-radius: 1em 0em 1em 1em;
        width: calc(100% - 2em);
        margin-left: 2em;
    }

    .chat-respuesta {
        background-color: #e7f1ff;
        padding: 1em;
        border-radius: 0em 1em 1em 1em;
        width: calc(100% - 2em);
        margin-right: 2em;
    }
    
    .typing-effect {
        display: inline-block;
        white-space: pre;
        border-right: 2px solid rgba(0,0,0,0.75);
        animation: blink 0.7s steps(44) infinite;
    }
    @keyframes blink {
        0%, 100% { border-color: transparent; }
        50% { border-color: rgba(0,0,0,0.75); }
    }

    .chat-pregunta-ejemplo {
        background-color: #FFF;
        padding: 0.5em;
        font-size: 0.9em;
        border-radius: 0.3em;
        border: 1px solid #CCC;
        cursor: pointer;
    }

    .chat-pregunta-ejemplo:hover {
        background-color: #e7f1ff;
        color: #0C63E4;
        border-color: #FFF;
    }

/* DEMO CUESTIONARIOS */
/*-----------------------------------------------------------------------------*/
    .title-white{
        color:white;
        text-align: center;
    }


</style>