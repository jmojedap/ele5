<?php
    /* Código del head para la página en la que se registran las respuestas de los
     * en lote
     */
     
?>

<script type="text/javascript" language="javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.6.4/jquery.min.js"></script>
<script type="text/javascript" language="javascript" src="<?php echo URL_RESOURCES ?>js/jquery.jkey.js"></script>
<script type="text/javascript" language="javascript">

	$(document).ready(function(){
		
                var numPreguntas = <?= $row->num_preguntas ?>
                
		var preguntaVigente = 0;
	
		updatePreguntaVigente();
		
		// actions for key R
		$('body').jkey('0, r, numKeyPad0',function(){
			selectAnAnswerViaKey(0)
			// alert('NR');
		});
                
                //actions for key A
		$('body').jkey('1, a, numKeyPad1',function(){
			selectAnAnswerViaKey(1)
			// alert('A');
		});
		
		// actions for key B
		$('body').jkey('2, b, numKeyPad2',function(){
			selectAnAnswerViaKey(2)
			// alert('B');
		});
		
		// actions for key C
		$('body').jkey('3, c, numKeyPad3',function(){
			selectAnAnswerViaKey(3)
			// alert('C');
		});
		
		// actions for key D
		$('body').jkey('4, d, numKeyPad4',function(){
			selectAnAnswerViaKey(4)
			// alert('D');
		});
		
		function selectAnAnswerViaClick(x){
			var letra;
			$('.opcion', $(x).parent().parent()).removeClass('respuesta');
			$(x).parent().addClass('respuesta');
			preguntaVigente = $(x).parent().parent().index();
			updatePreguntaVigente(preguntaVigente);
			
			if($(x).val() == 0){
				letra = 'NR';
				$('.control', $(x).parent().parent()).removeClass('status_verde');
				//$('.control', $(x).parent().parent()).html(($(x).parent().parent().index() + 1));
                                $('.control', $(x).parent().parent()).html('NR');
			}
			else{
				if($(x).val() == 1) letra = 'A';
				if($(x).val() == 2) letra = 'B';
				if($(x).val() == 3) letra = 'C';
				if($(x).val() == 4) letra = 'D';
				//$('.control', $(x).parent().parent()).html(($(x).parent().parent().index() + 1) + ' = ' + letra);
                                $('.control', $(x).parent().parent()).html(letra);
				$('.control', $(x).parent().parent()).addClass('status_verde');
			}
		}
		
		function selectAnAnswerViaKey(x){	
			$('input:radio', $('.pregunta:nth-child(' + (preguntaVigente + 1) + ') > .opcion:eq(' + x + ')')).click();
		}
		
		$('.opcion input').click(function(e) {
			selectAnAnswerViaClick(this);
		});
		
		$('body').jkey('down, enter', function(){
			if(preguntaVigente < numPreguntas - 1){
				preguntaVigente++;
			}
			else{
				preguntaVigente = 0;
			}
			updatePreguntaVigente(preguntaVigente);
		});
		
		$('body').jkey('up', function(){
			if(preguntaVigente > 0){
				preguntaVigente--;
			}
			else{
				preguntaVigente = numPreguntas - 1;
			}
			updatePreguntaVigente(preguntaVigente);
		});
		
		function updatePreguntaVigente(pv){
			if(typeof(pv)==='undefined') pv = 0;
			$('.destacada').removeClass('destacada');
			$('.pregunta:nth-child(' + (pv + 1) + ')').addClass('destacada');
                        scrollToAnchor(preguntaVigente);
			$('#p_pv').html(preguntaVigente);
		}
		
		function scrollToAnchor(aid){
    		if(aid < 3){
				$('html, body').animate({scrollTop: 0}, 250);
			}
			else{
				var aTag = $('a[name="p_'+ (aid - 2) +'"]');    //aid - 2
    			$('html, body').animate({scrollTop: aTag.offset().top}, 50);   //100 por 50
			}
		}

	});

</script>
<style>
html, body {
	font-family: Arial, Helvetica, sans-serif;
	font-size: 12px;
	color: #666;
}
.pregunta {
	min-width: 512px;
	border-bottom: 1px solid #CCC;
}

.pregunta:hover {
	background: #FFC;
}

.destacada {
	background: #6CC;
}

.no_pregunta {
	padding: 0px 8px;
        text-align: right;
}

.nombre_area{
    text-align: left;
    width: 15%;
    padding-left: 8px;
    margin-right: 16px;
    display: inline-block;
}

.opcion, .no_pregunta, .control {
	margin-right: 16px;
	display: inline-block;
	width: 8%;
}
.control{
	color: #900;
	/*text-align: right;*/
        text-align: left;
}

.status_verde{
	color: #393;
	font-weight: bold;
}

.respuesta{
	background: #3C6;
	color: white;
	font-weight: bold;
}
.tooltip{ 
	position: absolute; 
	top: 0; 
	left: 0; 
	z-index: 32; 
	display: none;
	background: #FFC;
	color: #333;
	padding: 4px 8px;
	border: 1px solid #FC9;
	border-radius: 4px; 
} 
</style>