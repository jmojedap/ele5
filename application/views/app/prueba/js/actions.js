
/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

$(document).ready(function () {
	
	var quizResuelto = false;

	jsPlumb.importDefaults({
		DragOptions : { cursor: 'pointer', zIndex: 2000 },
		PaintStyle: {
			lineWidth: 4,
			strokeStyle: '#678'
		},
		Connector: [ 'Bezier', { curviness: 4 } ],
		Endpoint: [ 'Dot', { radius: 10 } ],
		Container: 'col-container'
	});
	
	jsPlumb.bind("connection", function(info) {
		console.log('connection!');
		if($(info.target).data('origen') == $(info.source).index()){
			console.log('correcto!');
			$(info.source).data('respuesta', 1);
		}
		else{
			console.log(':(');
			$(info.source).data('respuesta', 0);
		}
	});
	
	jsPlumb.bind("connectionMoved", function(info) {
		console.log('moved!');
		if($(info.target).data('origen') == $(info.source).index()){
			console.log('correcto!');
			$(info.source).data('respuesta', 1);
		}
		else{
			console.log(':(');
			$(info.source).data('respuesta', 0);
		}
	});
	
	jsPlumb.bind("connectionDetached", function(info) {
		console.log('detached!');
		$(info.source).data('respuesta', 0);
	});

	jsPlumb.ready(function () {
		var origenEPOptions = {isSource: true, isTarget: false};
		var destinoEPOptions = {isSource: false, isTarget: true};
		var origen0EP = jsPlumb.addEndpoint('origen-0', {anchor: 'Right'}, origenEPOptions);
		var origen1EP = jsPlumb.addEndpoint('origen-1', {anchor: 'Right'}, origenEPOptions);
		var origen2EP = jsPlumb.addEndpoint('origen-2', {anchor: 'Right'}, origenEPOptions);
		var origen3EP = jsPlumb.addEndpoint('origen-3', {anchor: 'Right'}, origenEPOptions);
		var destino0EP = jsPlumb.addEndpoint('destino-0', {anchor: 'Left'}, destinoEPOptions);
		var destino1EP = jsPlumb.addEndpoint('destino-1', {anchor: 'Left'}, destinoEPOptions);
		var destino2EP = jsPlumb.addEndpoint('destino-2', {anchor: 'Left'}, destinoEPOptions);
		var destino3EP = jsPlumb.addEndpoint('destino-3', {anchor: 'Left'}, destinoEPOptions);
	});
	
	$('#check').click(function(){
		$('.col-1 li').each(function(i, e){
			if($(e).data('respuesta') == 0){
				quizResuelto = false;
				$('#note').html('no resuelto');
				return false;
			}
			quizResuelto = true;
			$('#note').html('muy bien!');
		});
	});
});