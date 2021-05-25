<style>
    .draggable{
        box-sizing: border-box; 
        min-width: 64px; 
        min-height: 24px; 
        position: absolute; 
        opacity: 0.9;
        cursor: move;
        overflow: hidden;
    }

    .draggable:hover{
        opacity: 0.9;
        -webkit-box-shadow: 0px 0px 0px 2px rgba(197,225,165,1);
        -moz-box-shadow: 0px 0px 0px 2px rgba(197,225,165,1);
        box-shadow: 0px 0px 0px 2px rgba(197,225,165,1);
    }

    #quiz_container{
        width: 800px;
        height: 450px;
        border: 1px solid #DDD;
        position: relative;
    }
</style>