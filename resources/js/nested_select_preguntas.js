$(function() {
	$('#field-area_id').change(function(){

		$("#field-contenido_id > option").css('display','block');
		$("#field-competencia_id > option").css('display','block');
		$("#field-componente_id > option").css('display','block');
		
		if($(this).val() == '50'){
			$("#field-contenido_id > option").not('.matematicas').css('display','none');
			$("#field-competencia_id > option").not('.matematicas').css('display','none');
			$("#field-componente_id > option").not('.matematicas').css('display','none');
		}
		if($(this).val() == '51'){
			$("#field-contenido_id > option").not('.castellano').css('display','none');
			$("#field-competencia_id > option").not('.castellano').css('display','none');
			$("#field-componente_id > option").not('.castellano').css('display','none');
		}
		if($(this).val() == '52'){
			$("#field-contenido_id > option").not('.sociales').css('display','none');
			$("#field-competencia_id > option").not('.sociales').css('display','none');
			$("#field-componente_id > option").not('.sociales').css('display','none');
		}
		if($(this).val() == '53'){
			$("#field-contenido_id > option").not('.naturales').css('display','none');
			$("#field-competencia_id > option").not('.naturales').css('display','none');
			$("#field-componente_id > option").not('.naturales').css('display','none');
		}

		$(".general").css('display','block');
		$("#field-contenido_id").trigger("liszt:updated");
		$("#field-competencia_id").trigger("liszt:updated");
		$("#field-componente_id").trigger("liszt:updated");

	});

});