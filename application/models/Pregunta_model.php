<?php
class Pregunta_model extends CI_Model{
  
    /**
     * Crea los valores de unas variables para el array $data
     * que serán utilizadas por varias funciones del controlador,
     * son variables básicas sobre un pregunta
     *
     * @param type $pregunta_id
     * @return string
     */
    function basico($pregunta_id)
    {
        
        $row = $this->Pcrn->registro_id('pregunta', $pregunta_id);

        $basico['titulo_pagina'] = 'Pregunta ' . $row->id;
        $basico['row'] = $row;
        $basico['editable'] = $this->editable($row);
        $basico['vista_a'] = 'preguntas/pregunta_v';
        
        return $basico;
    }
    
    /**
     * Búsqueda de preguntas
     * 
     * @param type $busqueda
     * @param type $per_page
     * @param type $offset
     * @return type
     */
    function buscar($busqueda, $per_page = NULL, $offset = NULL)
    {
            
        //Filtro según el rol de usuario que se tenga
            $filtro_rol = $this->filtro_rol();
        
        //Texto búsqueda
            //Crear array con términos de búsqueda
            if ( strlen($busqueda['q']) > 2 )
            {
                $palabras = $this->Busqueda_model->palabras($busqueda['q']);

                foreach ($palabras as $palabra_busqueda)
                {
                    $concat_campos = $this->Busqueda_model->concat_campos(array('cod_pregunta', 'texto_pregunta'));
                    $this->db->like("CONCAT({$concat_campos})", $palabra_busqueda);
                }
            }
            
        //Select
            $this->db->select('id, texto_pregunta, nivel, area_id, editado, editado_usuario_id');
        
        //Filtros de la búsqueda
            if ( $busqueda['a'] != '' ) { $this->db->where('area_id', $busqueda['a']); }    // Área
            if ( $busqueda['n'] != '' ) { $this->db->where('nivel', $busqueda['n']); }      // Nivel
            if ( $busqueda['e'] != '' ) { $this->db->where('editado', $busqueda['e']); }    //Editado
            
        //Otros
            $this->db->where($filtro_rol);  //Filtro por rol
        
        //Especificaciones de consulta
            $this->db->order_by('editado', 'DESC');
            
        //Obtener resultados
        if ( is_null($per_page) ){
            $query = $this->db->get('pregunta'); //Resultados totales
        } else {
            $query = $this->db->get('pregunta', $per_page, $offset); //Resultados por página
        }
        
        return $query;
    }
    
    function filtro_rol($usuario_id = NULL)
    {
        
        if ( is_null($usuario_id) ){ $usuario_id = $this->session->userdata('usuario_id'); }
        
        $row_usuario = $this->Pcrn->registro_id('usuario', $usuario_id);
        $condicion = "id = 0";  //Valor por defecto, ningún usuario, se obtendrían cero resultados.
        
        if ( $row_usuario->rol_id <= 2 )            //Usuarios internos
        {
            $condicion = 'id > 0';
        } elseif ( in_array($row_usuario->rol_id, array(3,4,5)) ) {    //Usuarios institucionales
            $condicion = "creado_usuario_id = {$usuario_id}";
        }
        
        return $condicion;
        
    }
    
    
//GROCERY CRUD DE PÁGINAS
//---------------------------------------------------------------------------------------------------
    
    function editable($row_pregunta)
    {
        $editable = FALSE;
        
        if ( ! is_null($row_pregunta) )
        {
            if ( in_array($this->session->userdata('rol_id'), array(0,1,2,7)) ) { $editable = TRUE; }   //Usuario interno
            if ( $this->session->userdata('usuario_id') == $row_pregunta->creado_usuario_id ) { $editable = TRUE; }   //Usuario que creó la pregunta
        }
        
        
        return $editable;
    }
    
    /**
     * $output del grocery crud para preguntaes
     * 
     * @return type
     */
    function crud_editar()
    {
        //Libería GC
            $this->load->library('grocery_CRUD');
        
        //Modificación de la configuración de GroceryCrud para cargue de archivos
            $this->load->config('grocery_crud');
            $this->config->set_item('grocery_crud_file_upload_allow_file_types', 'gif|jpeg|jpg|png');

        //Básico
            $crud = new grocery_CRUD();
            $crud->set_table('pregunta');
            $crud->columns('cod_pregunta', 'texto_pregunta', 'enunciado_id', 'archivo_imagen', 'nivel', 'area_id', 'creado_usuario_id');
            $crud->set_subject('pregunta');
            $crud->unset_add();
            $crud->unset_delete();
            $crud->unset_back_to_list();
            $crud->unset_export();
            $crud->unset_print();
        
        //Opciones de enunciados según el rol
            $condicion_enunciados = 'tipo_id = 4401';
            if ( $this->session->userdata('rol_id') > 2 ){
                //Condición enunciados
                $condicion_enunciados = "tipo_id = 4401 AND post.referente_1_id = {$this->session->userdata('institucion_id')}";
            }

        //Títulos de campos
            $crud->display_as('cod_pregunta', 'Cód');
            $crud->display_as('enunciado_id', 'Enunciado referencia');
            $crud->display_as('opcion_1', 'Opción A');
            $crud->display_as('opcion_2', 'Opción B');
            $crud->display_as('opcion_3', 'Opción C');
            $crud->display_as('opcion_4', 'Opción D');
            $crud->display_as('tipo_pregunta_id', 'Tipo pregunta');
            $crud->display_as('area_id', 'Área');
            $crud->display_as('archivo_imagen', 'Imagen respuestas');
            $crud->display_as('competencia_id', 'Competencia');
            $crud->display_as('componente_id', 'Componente');
            $crud->display_as('tema_id', 'Tema');
            $crud->display_as('enunciado_id', 'Enunciado referencia');
            $crud->display_as('creado_usuario_id', 'Creado por');

        //Relaciones
            $crud->set_relation('enunciado_id', 'post', '{nombre_post} [{id}]', $condicion_enunciados);
            $crud->set_relation('area_id', 'item', 'item', 'categoria_id = 1');
            $crud->set_relation('creado_usuario_id', 'usuario', 'username');
            $crud->set_relation('tema_id', 'tema', '{nivel} - {nombre_tema}');
            $crud->set_relation('competencia_id', 'item', 'item', 'categoria_id = 4');
            $crud->set_relation('componente_id', 'item', 'item', 'categoria_id = 8');
            
        //Opciones respuesta correcta letras
            $opciones_rta = $this->Item_model->opciones('categoria_id = 57 AND id_interno <=4');
            $crud->field_type('respuesta_correcta', 'dropdown', $opciones_rta);
            
        //Opciones nivel
            $opciones_nivel = $this->App_model->opciones_nivel('item_largo');
            $crud->field_type('nivel', 'dropdown', $opciones_nivel);

        //Preparación de campos
            $crud->set_field_upload('archivo_imagen', RUTA_UPLOADS . 'preguntas');

        //Reglas de validación
            $crud->required_fields('texto_pregunta', 'respuesta_correcta', 'area_id', 'opcion_1', 'opcion_2', 'opcion_3', 'opcion_4');

        //Add form
            $crud->edit_fields('cod_pregunta', 'texto_pregunta', 'enunciado_id', 'archivo_imagen', 'opcion_1', 'opcion_2', 'opcion_3', 'opcion_4', 'respuesta_correcta', 'nivel', 'area_id', 'competencia_id', 'componente_id', 'institucion_id', 'editado', 'editado_usuario_id');
            $crud->callback_add_field('respuesta_correcta', array($this, 'gc_dropdown_respuesta_correcta'));
            $crud->callback_add_field('componente_id', array($this, 'gc_dropdown_componente'));
            $crud->callback_add_field('competencia_id', array($this, 'gc_dropdown_competencia'));
            
            //Redirigir después de crear la pregunta
            /*$destino = "cuestionarios/preguntas/{$referente_id}";
            $crud->set_lang_string('insert_success_message',
                                    'Su pregunta ha sido creada<br/>Por favor espere mientras se redirige al cuestionario.
                                    <script type="text/javascript">
                                    window.location = "'.site_url($destino).'";
                                    </script>
                                    <div style="display:none">
                                    '
            );*/
            
        //Funciones
            $crud->callback_after_update(array($this, 'gc_pregunta_after_update'));

        //Valores por defecto
            $crud->field_type('tipo_pregunta_id', 'hidden', 54);
            $crud->field_type('editado_usuario_id', 'hidden', $this->session->userdata('usuario_id'));
            $crud->field_type('editado', 'hidden', date('Y-m-d H:i:s'));
            $crud->field_type('institucion_id', 'hidden', $this->session->userdata('institucion_id'));
            
        //Formato
            $crud->unset_texteditor('texto_pregunta', 'full_text');

        //
            $output = $crud->render();
            
            return $output;
        
    }
    
    /**
     * $output del grocery crud para preguntaes
     * 
     * @return type
     */
    function crud_editar_institucional()
    {
        //Libería GC
            $this->load->library('grocery_CRUD');
        
        //Modificación de la configuración de GroceryCrud para cargue de archivos
            $this->load->config('grocery_crud');
            $this->config->set_item('grocery_crud_file_upload_allow_file_types', 'gif|jpeg|jpg|png');

        //Básico
            $crud = new grocery_CRUD();
            $crud->set_table('pregunta');
            $crud->columns('cod_pregunta');
            $crud->set_subject('pregunta');
            $crud->unset_add();
            $crud->unset_delete();
            $crud->unset_back_to_list();
            $crud->unset_export();
            $crud->unset_print();

        //Títulos de campos
            $crud->display_as('opcion_1', 'Opción A');
            $crud->display_as('opcion_2', 'Opción B');
            $crud->display_as('opcion_3', 'Opción C');
            $crud->display_as('opcion_4', 'Opción D');
            $crud->display_as('tipo_pregunta_id', 'Tipo pregunta');
            $crud->display_as('archivo_imagen', 'Imagen respuestas');
            $crud->display_as('creado_usuario_id', 'Creado por');

        //Relaciones
            $crud->set_relation('area_id', 'item', 'item', 'categoria_id = 1');
            $crud->set_relation('creado_usuario_id', 'usuario', 'username');
            $crud->set_relation('tema_id', 'tema', '{nivel} - {nombre_tema}');
            $crud->set_relation('competencia_id', 'item', 'item', 'categoria_id = 4');
            $crud->set_relation('componente_id', 'item', 'item', 'categoria_id = 8');
            
        //Opciones nivel
            $opciones_nivel = $this->Item_model->opciones('categoria_id = 3');
            $crud->field_type('nivel', 'dropdown', $opciones_nivel);
            
        //Respuesta correcta letras
            $opciones_rta = $this->Item_model->opciones('categoria_id = 57 AND id_interno <=4');
            $crud->field_type('respuesta_correcta', 'dropdown', $opciones_rta);

        //Preparación de campos
            $crud->set_field_upload('archivo_imagen', RUTA_UPLOADS . 'preguntas');

        //Reglas de validación
            $crud->required_fields('texto_pregunta', 'respuesta_correcta', 'area_id', 'opcion_1', 'opcion_2', 'opcion_3', 'opcion_4');

        //Add form
            $crud->edit_fields(
                    'texto_pregunta',
                    'opcion_1', 
                    'opcion_2', 
                    'opcion_3', 
                    'opcion_4', 
                    'respuesta_correcta', 
                    'archivo_imagen', 
                    'editado', 
                    'editado_usuario_id'
                );
            
        //Funciones
            $crud->callback_after_update(array($this, 'gc_pregunta_after_update'));

        //Valores por defecto
            $crud->field_type('editado_usuario_id', 'hidden', $this->session->userdata('usuario_id'));
            $crud->field_type('editado', 'hidden', date('Y-m-d H:i:s'));
            
        //Formato
            $crud->unset_texteditor('texto_pregunta', 'full_text');

        //
            $output = $crud->render();
            
            return $output;
        
    }
    
    function crud_add($cuestionario_id, $registro)
    {
        //Libería GC
            $this->load->library('grocery_CRUD');
        
        //Modificación de la configuración de GroceryCrud para cargue de archivos
            $this->load->config('grocery_crud');
            $this->config->set_item('grocery_crud_file_upload_allow_file_types', 'gif|jpeg|jpg|png');

        //Básico
            $crud = new grocery_CRUD();
            $crud->set_table('pregunta');
            $crud->set_subject('pregunta');
            $crud->unset_edit();
            $crud->unset_delete();
            $crud->unset_back_to_list();
            $crud->unset_export();
            $crud->unset_print();
        
        //Opciones de enunciados según el rol
            $condicion_enunciados = 'id > 0';
            if ( $this->session->userdata('rol_id') > 2 ){
                //Condición enunciados
                $condicion_enunciados = "post.referente_1_id = {$this->session->userdata('institucion_id')} AND tipo_id = 4401";
            }

        //Títulos de campos
            $crud->display_as('cod_pregunta', 'Cód.');
            $crud->display_as('cod_pregunta', 'Cód. pregunta');
            $crud->display_as('enunciado_id', 'Enunciado referencia');
            $crud->display_as('opcion_1', 'Opción A');
            $crud->display_as('opcion_2', 'Opción B');
            $crud->display_as('opcion_3', 'Opción C');
            $crud->display_as('opcion_4', 'Opción D');
            $crud->display_as('respuesta_correcta', 'Opción correcta');
            $crud->display_as('tipo_pregunta_id', 'Tipo pregunta');
            $crud->display_as('area_id', 'Área');
            $crud->display_as('archivo_imagen', 'Imagen respuestas');
            $crud->display_as('competencia_id', 'Competencia');
            $crud->display_as('componente_id', 'Componente');
            $crud->display_as('tema_id', 'Tema');
            $crud->display_as('creado_usuario_id', 'Creado por');
            
        //Condición competencia
            /*$condicion_competencia = 'id > 0 ';
            if ( $registro['area_id'] > 0 ) { $condicion_competencia = "area_id = {$registro['area_id']}"; }*/

        //Relaciones
            $crud->set_relation('enunciado_id', 'post', 'nombre_post', $condicion_enunciados);
            
        //Opciones respuesta
            $opciones_rta = $this->Item_model->opciones('categoria_id = 57 AND id_interno <=4');
            $crud->field_type('respuesta_correcta', 'dropdown', $opciones_rta);

        //Preparación de campos
            $crud->set_field_upload('archivo_imagen', RUTA_UPLOADS . 'preguntas');

        //Reglas de validación
            $crud->required_fields('texto_pregunta', 'respuesta_correcta', 'area_id');

        //Add form
            $crud->add_fields(
                    'cod_pregunta',
                    'texto_pregunta',
                    'enunciado_id',
                    'archivo_imagen',
                    'opcion_1',
                    'opcion_2',
                    'opcion_3',
                    'opcion_4',
                    'respuesta_correcta',
                    'nivel',
                    'area_id',
                    'institucion_id',
                    'editado',
                    'editado_usuario_id',
                    'creado',
                    'creado_usuario_id'
                );
            
        //Campo nivel
            if ( $registro['nivel'] > -1 ) { 
                $crud->field_type('nivel', 'hidden', $registro['nivel']); 
            } else {
                $opciones_nivel = $this->Item_model->opciones('categoria_id = 3');
                $crud->field_type('nivel', 'dropdown', $opciones_nivel);
            }
            
        //Campo área
            if ( $registro['area_id'] > 0 ) {
                $crud->field_type('area_id', 'hidden', $registro['area_id']);
            } else {
                $crud->set_relation('area_id', 'item', 'item', 'categoria_id = 1');
            }
            
        //Redireccionamiento después de la insersión
            $destino = "cuestionarios/preguntas/{$cuestionario_id}";    
            $crud->callback_after_insert(array($this, 'gc_pregunta_after_insert'));
            
            
        //Redirigir después de crear la pregunta
            $crud->set_lang_string('insert_success_message',
                                    'Su pregunta ha sido creada<br/>Por favor espere mientras se redirige al cuestionario.
                                    <script type="text/javascript">
                                    window.location = "'.site_url($destino).'";
                                    </script>
                                    <div style="display:none">
                                    '
            );
            

        //Valores por defecto
            $crud->field_type('tipo_pregunta_id', 'hidden', 54); //Selección múltiple
            $crud->field_type('editado_usuario_id', 'hidden', $this->session->userdata('usuario_id'));
            $crud->field_type('creado_usuario_id', 'hidden', $this->session->userdata('usuario_id'));
            $crud->field_type('creado', 'hidden', date('Y-m-d H:i:s'));
            $crud->field_type('editado', 'hidden', date('Y-m-d H:i:s'));
            $crud->field_type('institucion_id', 'hidden', $this->session->userdata('institucion_id'));
            
        //Formato
            $crud->unset_texteditor('texto_pregunta', 'full_text');

        //
            $output = $crud->render();
            
            return $output;
        
    }
    
    function crud_add_institucional($cuestionario_id, $registro)
    {
        //Libería GC
            $this->load->library('grocery_CRUD');
        
        //Modificación de la configuración de GroceryCrud para cargue de archivos
            $this->load->config('grocery_crud');
            $this->config->set_item('grocery_crud_file_upload_allow_file_types', 'gif|jpeg|jpg|png');

        //Básico
            $crud = new grocery_CRUD();
            $crud->set_table('pregunta');
            $crud->columns('cod_pregunta');
            $crud->set_subject('pregunta');
            $crud->unset_edit();
            $crud->unset_delete();
            $crud->unset_back_to_list();
            $crud->unset_export();
            $crud->unset_print();
            $crud->where('pregunta.id', 0);

        //Títulos de campos
            $crud->display_as('opcion_1', 'Opción A');
            $crud->display_as('opcion_2', 'Opción B');
            $crud->display_as('opcion_3', 'Opción C');
            $crud->display_as('opcion_4', 'Opción D');
            $crud->display_as('respuesta_correcta', 'Opción correcta');
            $crud->display_as('tipo_pregunta_id', 'Tipo pregunta');
            $crud->display_as('archivo_imagen', 'Imagen');
            $crud->display_as('creado_usuario_id', 'Creado por');
            
        //Condición competencia
            /*$condicion_competencia = 'id > 0 ';
            if ( $registro['area_id'] > 0 ) { $condicion_competencia = "area_id = {$registro['area_id']}"; }*/

        //Opciones respuesta correcta - letras
            $opciones_rta = $this->Item_model->opciones('categoria_id = 57 AND id_interno <=4');
            $crud->field_type('respuesta_correcta', 'dropdown', $opciones_rta);

        //Preparación de campos
            $crud->set_field_upload('archivo_imagen', RUTA_UPLOADS . 'preguntas');

        //Reglas de validación
            $crud->required_fields('texto_pregunta', 'respuesta_correcta', 'area_id');

        //Add form
            $crud->add_fields(
                    'texto_pregunta',
                    'opcion_1',
                    'opcion_2',
                    'opcion_3',
                    'opcion_4',
                    'respuesta_correcta',
                    'archivo_imagen',
                    'nivel',
                    'area_id',
                    'institucion_id',
                    'editado',
                    'editado_usuario_id',
                    'creado',
                    'creado_usuario_id'
                );
            
        //Valores por defecto
            if ( $registro['nivel'] > -1 ) { $crud->field_type('nivel', 'hidden', $registro['nivel']); }
            if ( $registro['area_id'] > 0 ) { $crud->field_type('area_id', 'hidden', $registro['area_id']); }
            
        //Redireccionamiento después de la insersión
            $destino = "cuestionarios/preguntas/{$cuestionario_id}";    
            $crud->callback_after_insert(array($this, 'gc_pregunta_after_insert'));
            
            
        //Redirigir después de crear la pregunta
            $crud->set_lang_string('insert_success_message',
                                    'Su pregunta ha sido creada<br/>Por favor espere mientras se redirige al cuestionario.
                                    <script type="text/javascript">
                                    window.location = "'.site_url($destino).'";
                                    </script>
                                    <div style="display:none">
                                    '
            );
            

        //Valores por defecto
            $crud->field_type('tipo_pregunta_id', 'hidden', 54); //Selección múltiple
            $crud->field_type('editado_usuario_id', 'hidden', $this->session->userdata('usuario_id'));
            $crud->field_type('creado_usuario_id', 'hidden', $this->session->userdata('usuario_id'));
            $crud->field_type('creado', 'hidden', date('Y-m-d H:i:s'));
            $crud->field_type('editado', 'hidden', date('Y-m-d H:i:s'));
            $crud->field_type('institucion_id', 'hidden', $this->session->userdata('institucion_id'));
            
        //Formato
            $crud->unset_texteditor('texto_pregunta', 'full_text');
            
            $output = $crud->render();
            return $output;
        
    }

    
    
    function crud_add_tema($tema_id, $registro, $orden)
    {
        //Libería GC
            $this->load->library('grocery_CRUD');
        
        //Modificación de la configuración de GroceryCrud para cargue de archivos
            $this->load->config('grocery_crud');
            $this->config->set_item('grocery_crud_file_upload_allow_file_types', 'gif|jpeg|jpg|png');

        //Básico
            $crud = new grocery_CRUD();
            $crud->set_table('pregunta');
            $crud->columns('cod_pregunta', 'texto_pregunta', 'enunciado_id', 'archivo_imagen', 'nivel', 'area_id', 'creado_usuario_id');
            $crud->set_subject('pregunta');
            $crud->unset_delete();
            $crud->unset_back_to_list();
            $crud->unset_export();
            $crud->unset_print();
        
        //Opciones de enunciados según el rol
            $condicion_enunciados = 'id > 0';
            if ( $this->session->userdata('rol_id') > 2 )
            {
                //Condición enunciados
                $condicion_enunciados = "tipo_id = 4401 AND post.referente_1_id = {$this->session->userdata('institucion_id')}";
            }

        //Títulos de campos
            $crud->display_as('cod_pregunta', 'Código');
            $crud->display_as('enunciado_id', 'Enunciado referencia');
            $crud->display_as('opcion_1', 'Opción A');
            $crud->display_as('opcion_2', 'Opción B');
            $crud->display_as('opcion_3', 'Opción C');
            $crud->display_as('opcion_4', 'Opción D');
            $crud->display_as('respuesta_correcta', 'Opción correcta');
            $crud->display_as('archivo_imagen', 'Imagen respuestas');
            $crud->display_as('competencia_id', 'Competencia');
            $crud->display_as('componente_id', 'Componente');
            
        //Condición competencia
            $condicion_competencia = 'categoria_id = 4';
            if ( $registro['area_id'] > 0 ) { $condicion_competencia .= " AND item_grupo = {$registro['area_id']}"; }
            
        //Condición componente
            $condicion_componente = 'categoria_id = 8';
            if ( $registro['area_id'] > 0 ) { $condicion_componente .= " AND item_grupo = {$registro['area_id']}"; }

        //Relaciones
            $crud->set_relation('enunciado_id', 'post', '{nombre_post} [{id}]', $condicion_enunciados);
            $crud->set_relation('competencia_id', 'item', 'item', $condicion_competencia);
            $crud->set_relation('componente_id', 'item', 'item', $condicion_componente);
            
        //Opciones respuesta correcta letra
            $opciones_rta = $this->Item_model->opciones('categoria_id = 57 AND id_interno <=4');
            $crud->field_type('respuesta_correcta', 'dropdown', $opciones_rta);

        //Preparación de campos
            $crud->set_field_upload('archivo_imagen', RUTA_UPLOADS . 'preguntas');

        //Reglas de validación
            $crud->required_fields('texto_pregunta', 'respuesta_correcta', 'area_id');

        //Add form
            $crud->add_fields(
                'cod_pregunta',
                'texto_pregunta',
                'enunciado_id',
                'archivo_imagen',
                'opcion_1',
                'opcion_2',
                'opcion_3',
                'opcion_4',
                'respuesta_correcta',
                'nivel', 'area_id',
                'tema_id',
                'orden',
                'competencia_id',
                'componente_id',
                'institucion_id',
                'editado',
                'editado_usuario_id',
                'creado',
                'creado_usuario_id'
            );
            
        //Condiciones, opciones nivel
            if ( $registro['nivel'] < 0 ) 
            {
                $opciones_nivel = $this->App_model->opciones_nivel('item_largo');
                $crud->field_type('nivel', 'dropdown', $opciones_nivel);
            }
            
            if ( is_null($registro['area_id']) ) { 
                $crud->set_relation('area_id', 'item', 'item', 'categoria_id = 1');
            }
            
        //Valores por defecto
            if ( $registro['nivel'] > -1 ) { $crud->field_type('nivel', 'hidden', $registro['nivel']); }
            if ( $registro['area_id'] > 0 ) { $crud->field_type('area_id', 'hidden', $registro['area_id']); }
            
        //Redirigir después de crear la pregunta
            $destino = "temas/preguntas/{$tema_id}";
            $crud->set_lang_string('insert_success_message',
                                    'Su pregunta ha sido creada<br/>Por favor espere mientras se redirige al tema.
                                    <script type="text/javascript">
                                    window.location = "'.site_url($destino).'";
                                    </script>
                                    <div style="display:none">
                                    '
            );
            
        //Procesos
            $crud->callback_after_insert(array($this, 'gc_pregunta_after_insert_tema'));

        //Valores por defecto
            $crud->field_type('tipo_pregunta_id', 'hidden', 54);
            $crud->field_type('editado_usuario_id', 'hidden', $this->session->userdata('usuario_id'));
            $crud->field_type('creado_usuario_id', 'hidden', $this->session->userdata('usuario_id'));
            $crud->field_type('creado', 'hidden', date('Y-m-d H:i:s'));
            $crud->field_type('editado', 'hidden', date('Y-m-d H:i:s'));
            $crud->field_type('institucion_id', 'hidden', $this->session->userdata('institucion_id'));
            $crud->field_type('tema_id', 'hidden', $tema_id);
            $crud->field_type('nivel', 'hidden', $registro['nivel']);
            $crud->field_type('area_id', 'hidden', $registro['area_id']);
            $crud->field_type('orden', 'hidden', '0' . $orden);
            
        //Formato
            $crud->unset_texteditor('texto_pregunta', 'full_text');

        //
            $output = $crud->render();
            
        return $output;
        
    }
    
    //Procesos después de insertar a un cuestionario
    function gc_pregunta_after_insert($post_array, $primary_key)
    {
        //Actualizar campo orden
            $this->orden_defecto($primary_key);
        
        //Cargar datos
            $registro = array(
                'cuestionario_id' => $this->session->userdata('cuestionario_id'),
                'pregunta_id' => $primary_key,
                'orden' => $this->session->userdata('orden'),
            );
        
        //Insertar en cuestionario
            $this->load->model('Cuestionario_model');
            $this->Cuestionario_model->insertar_cp($registro);
            $this->Cuestionario_model->act_clave($registro['cuestionario_id']); //Agregada 2019-02-27

        return true;
    }

    //Procesos después de insertar a un cuestionario
    function gc_pregunta_after_update($post_array, $primary_key)
    {
        //Actualizar campo cuestionario.clave, de los cuestionarios donde está la pregunta incluida.
            $this->load->model('Cuestionario_model');

            $cuestionarios = $this->cuestionarios($primary_key);

            foreach ( $cuestionarios->result() as $row_cuestionario ) {
                $this->Cuestionario_model->act_clave($row_cuestionario->cuestionario_id);
            }

        return true;
    }
    
    /**
     * Después de insertar a un tema
     * Establecer el orden a la pregunta, dentro de las preguntas que tienen el mismo tema
     * 
     * @param type $post_array
     * @param type $primary_key
     * @return boolean
     */
    function gc_pregunta_after_insert_tema($post_array, $primary_key)
    {
        //Actualizar campo pregunta.orden
            $this->orden_defecto($primary_key);
        
        //Variables
            $tema_id = $this->session->userdata('tema_id');
            $pos_final = $this->session->userdata('orden');
        
        $this->load->model('Tema_model');
        
        $this->Tema_model->cambiar_pos_pregunta($tema_id, $primary_key, $pos_final);

        return true;
    }
    
    function gc_dropdown_respuesta_correcta($value){
        $opciones = $this->App_model->opciones_item("categoria_id = 57 and id_interno <= 4", TRUE);
        $dropdown = form_dropdown('respuesta_correcta', $opciones, $value, 'class="chosen-select" style="width: 512px"');
        return $dropdown;
    }
    
    function gc_dropdown_componente($value){
        $dropdown = $this->App_model->dropdown_item_clase('componente_id', 8, $value, 'class="chosen-select" style="width: 512px"');
        return $dropdown;
    }
    
    function gc_dropdown_competencia($value){
        $dropdown = $this->App_model->dropdown_item_clase('competencia_id', 4, $value, 'class="chosen-select" style="width: 512px"');
        return $dropdown;
    }
    
    function gc_vinculo_pregunta($value, $row)
    {
        $texto = substr($row->nombre_pregunta, 0, 50);
        $att = 'title="Ir al perfil de ' . $value. '"';
        return anchor("preguntaes/estudiantes/{$row->id}", $texto, $att);
    }
    
    function dropdown_lugar($value)
    {
        $dropdown = $this->App_model->dropdown_lugar($value);
        return $dropdown;
    }
    
    /**
     * Eliminación en cascada de registro relacionados
     * 
     * @param type $primary_key 
     */
    function gc_after_del_pregunta()
    {
        $this->App_model->eliminar_cascada();
        
    }
    
// IMPORTAR
//-----------------------------------------------------------------------------
    
    /**
     * Inserta masivamente preguntas
     * tabla programa
     * 
     * @param type $array_hoja    Array con los datos de las preguntas
     */
    function importar($array_hoja)
    {   
        $this->load->model('Esp');
        
        $no_importados = array();
        $fila = 2;  //Inicia en la fila 2 de la hoja de cálculo
        
        $arr_letras = $this->Esp->arr_letras();
            
        //Predeterminados registro nuevo
            $registro['creado_usuario_id'] = $this->session->userdata('usuario_id');
            $registro['editado_usuario_id'] = $this->session->userdata('usuario_id');
            $registro['editado'] = date('Y-m-d H:i:s');
            $registro['creado'] = date('Y-m-d H:i:s');
        
        foreach ( $array_hoja as $array_fila )
        {
            $respuesta_correcta = NULL;
            if ( strlen($array_fila[9]) > 0 ) {
                $respuesta_correcta = $arr_letras[$array_fila[9]];
            }
            
            //Registro
                $registro['cod_pregunta'] = "{$array_fila[2]}-{$array_fila[10]}";
                $registro['texto_pregunta'] = $array_fila[1];
                $registro['tema_id'] = $this->Pcrn->campo('tema', "cod_tema = '{$array_fila[2]}'", 'id');
                $registro['orden'] = $array_fila[3] - 1;        //Se resta 1, para iniciar en 0
                $registro['enunciado_id'] = $array_fila[4];
                $registro['opcion_1'] = $array_fila[5];
                $registro['opcion_2'] = $array_fila[6];
                $registro['opcion_3'] = $array_fila[7];
                $registro['opcion_4'] = $array_fila[8];
                $registro['respuesta_correcta'] = $respuesta_correcta;
                $registro['nivel'] = $this->Pcrn->campo('tema', "cod_tema = '{$array_fila[2]}'", 'nivel');
                $registro['area_id'] = $this->Pcrn->campo('tema', "cod_tema = '{$array_fila[2]}'", 'area_id');
                $registro['competencia_id'] = $array_fila[10];
                
            //Validar
                $condiciones = 0;
                if ( strlen($registro['tema_id']) > 1 ) { $condiciones++; }     //Debe tener tema definido
                if ( strlen($array_fila[10]) > 0 ) { $condiciones++; }          //Debe tener competencia escrita
                
            //Si cumple las condiciones
            if ( $condiciones == 2 )
            {   
                $this->guardar_pregunta($registro);
            } else {
                $no_importados[] = $fila;
            }
            
            $fila++;    //Para siguiente fila
        }
        
        return $no_importados;
    }
    
//CARGUE MASIVO DE DATOS
//-----------------------------------------------------------------------------
    
    /**
     * Recorrer array de excel para cargue de preguntas
     * 
     * @param type $array_excel
     * @return type
     */
    function cargar($array_excel)
    {       
        //Cargando
            $this->load->model('Esp');
        
        //Variables
            $cant_cargados = 0;
            $editado = date('Y-m-d H:i:s');
        
        //Predeterminados registro nuevo
            $registro['creado_usuario_id'] = $this->session->userdata('usuario_id');
            $registro['editado_usuario_id'] = $this->session->userdata('usuario_id');
            $registro['editado'] = $editado;
            $registro['creado'] = $editado;
        
        foreach ( $array_excel as $row_elemento ) {
            $cant_cargados += $this->cargar_pregunta($row_elemento, $registro);
        }
        
        $res_cargue['cant_cargados'] = $cant_cargados;
        $res_cargue['e'] = str_replace(array(' ', '-', ':'), '', $editado); //Para mostrar resultados, $busqueda['e']
        
        return $res_cargue;
    }
    
    /**
     * Leer fila de array excel para guardar registro de pregunta
     * 
     * @param type $row_elemento
     * @param type $registro
     * @return int
     */
    function cargar_pregunta($row_elemento, $registro)
    {
        $cargado = 0;
        $condiciones = 0;
        $respuesta_correcta = NULL;
        
        //Variables relacionadas
            $arr_letras = $this->Esp->arr_letras();
        
        //Revisar condiciones
            if ( strlen($row_elemento[2]) > 1 ) { $condiciones++; }         //Debe tener tema definido
            if ( strlen($row_elemento[10]) > 0 ) { $condiciones++; }        //Debe tener competencia identificada
        
        if ( $condiciones == 2 ) {
            
            $respuesta_correcta = NULL;
            if ( strlen($row_elemento[9]) > 0 ) {
                $respuesta_correcta = $arr_letras[$row_elemento[9]];
            }
            
            //Registro
                $registro['cod_pregunta'] = "{$row_elemento[2]}-{$row_elemento[10]}";
                $registro['texto_pregunta'] = $row_elemento[1];
                $registro['tema_id'] = $this->Pcrn->campo('tema', "cod_tema = '{$row_elemento[2]}'", 'id');
                $registro['orden'] = $row_elemento[3] - 1;  //Se resta 1, para iniciar en 0
                $registro['enunciado_id'] = $row_elemento[4];
                $registro['opcion_1'] = $row_elemento[5];
                $registro['opcion_2'] = $row_elemento[6];
                $registro['opcion_3'] = $row_elemento[7];
                $registro['opcion_4'] = $row_elemento[8];
                $registro['respuesta_correcta'] = $respuesta_correcta;
                $registro['nivel'] = $this->Pcrn->campo('tema', "cod_tema = '{$row_elemento[2]}'", 'nivel');
                $registro['area_id'] = $this->Pcrn->campo('tema', "cod_tema = '{$row_elemento[2]}'", 'area_id');
                

            //Guardar
                $this->Pregunta_model->guardar_pregunta($registro);

            $cargado = 1;
        }
        
        return $cargado;
    }
    
//GESTIÓN DE PREGUNTAS
//---------------------------------------------------------------------------------------------------
    
    
    
    /**
     * Insertar el registro en la tabla 'pregunta'
     * 
     * @param type $registro 
     */
    function guardar_pregunta($registro)
    {
        $this->db->insert('pregunta', $registro);
    }
    
    /**
     * Establece el valor por defecto al campo pregunta.orden
     * Por defecto se lo pone como la última pregunta del tema
     * 
     * @param type $pregunta_id
     */
    function orden_defecto($pregunta_id)
    {
        //Averiguar tema asociado
            $tema_id = $this->Pcrn->campo('pregunta', "id = {$pregunta_id}", 'tema_id');
        
        //Contar total de preguntas en el tema
            $this->db->where('tema_id', $tema_id);
            $registro['orden'] = $this->db->count_all_results('pregunta');
            
        //Actualizar campo
            $this->db->where('id', $pregunta_id);
            $this->db->update('pregunta', $registro);
    }
    
    /**
     * Se ejecuta el proceso de cargue del archivo de la imagen
     * La imagen cargada es la más grande, visible cuando se hace zoom a una Pregunta
     * Adicionalmente se crean dos imágenes, el tamaño medio, vista normal del libro
     * y la miniatura.
     * @return type 
     */
    function subir_imagen(){
        
        //Configuración del proceso de cargue
        $config['upload_path'] = RUTA_UPLOADS . "preguntas_flipbook_zoom/";
        $config['allowed_types'] = 'gif|jpg|png';
        $config['encrypt_name'] = TRUE;
        $config['max_size'] = '500';
        $config['max_width'] = '1400';
        $config['max_height'] = '1400';
        $this->load->library('upload', $config);
        
        if ( ! $this->upload->do_upload('archivo_imagen') ){
            //No exitoso, regresar al formulario de cargue
            $resultados['cargado'] = TRUE;
            $resultados['mensaje'] = $this->upload->display_errors('<h4 class="alert_error">', '</h4>');
        } else {
            //Exitoso, se realiza el cargue del archivo
            $upload_data = $this->upload->data();
            
            $resultados['cargado'] = TRUE;
            $resultados['mensaje'] = '<h4 class="alert_success">' . 'El archivo fue cargado correctamente' .  '</h4>';
            $resultados['upload_data'] = $this->upload->data();
            
            //Crear imágenes, copias más pequeñas
            $this->img_pregunta_mini($upload_data['file_name']);
        }
        
        return $resultados;
    }
    
    /**
     * Crea las imágenes miniatura de la Pregunta que se sube.
     * @param type $nombre_archivo 
     */
    function img_pregunta_mini($nombre_archivo)
    { 
        $this->load->library('image_lib');
        
        //Miniatura
            $config['image_library'] = 'gd2';
            $config['source_image'] = RUTA_UPLOADS . 'preguntas_flipbook_zoom/' . $nombre_archivo;
            $config['new_image'] = RUTA_UPLOADS . 'preguntas_flipbook_mini/';
            $config['maintain_ratio'] = TRUE;
            $config['width'] = 256;
            $config['height'] = 256;

            $this->image_lib->initialize($config);
            $this->image_lib->resize();
        
        //Limpiando librería
            $this->image_lib->clear();
            $config = array();
            
        //Tamaño mediano
            $config['image_library'] = 'gd2';
            $config['source_image'] = RUTA_UPLOADS . 'preguntas_flipbook_zoom/' . $nombre_archivo;
            $config['new_image'] = RUTA_UPLOADS . 'preguntas_flipbook/';
            $config['maintain_ratio'] = TRUE;
            $config['width'] = 512;
            $config['height'] = 512;

            $this->image_lib->initialize($config);
            $this->image_lib->resize();
    }
    
    
    

//TEXTO
//---------------------------------------------------------------------------------------------------

    
    function datos_pregunta($pregunta_id)
    {
        //pregunta, sigla de Pregunta flipbook
        
        //Devuelve un objeto de registro con los datos de una pregunta flipbook
        $this->db->where('id', $pregunta_id);
        $query = $this->db->get('pregunta');
        
        $datos_pregunta = FALSE;
        
        if( $query->num_rows() > 0 ){
            $row = $query->row();
            $datos_pregunta = $row;
        }
        
        //Calculando
        return $datos_pregunta;
    }
    
    /**
     * Eliminar un registro de la tabla 'pregunta'
     * 
     * Se eliminan también los archivos de las imágenes asociadas
     * 
     * @param type $pregunta_id 
     */
    function eliminar($pregunta_id){
        
        //$this->load->model('Cuestionario_model');

        $row = $this->Pcrn->registro_id('pregunta', $pregunta_id);
        
        //Eliminar archivos
            $this->eliminar_img_pregunta($row->archivo_imagen);
            
        
        /* Modificar num_de Pregunta de las Preguntas de los libros en los que aparece
         * Al eliminarse una Pregunta, los números de Pregunta de las Preguntas siguientes deben disminuir
         * en uno.
         */
            $cuestionarios = $this->cuestionarios($pregunta_id);   //Identificar todos los cuestionario en los que aparece la Pregunta
            echo $cuestionarios->num_rows();

            foreach ($cuestionarios->result() as $row_cuestionario)  //Recorrer cada cuestionario y hacer la actualización de números de Pregunta
            {
                
                $sql = "UPDATE cuestionario_pregunta ";
                $sql .= "SET orden = orden - 1 ";
                $sql .= "WHERE ";
                $sql .= "orden > {$row_cuestionario->orden} AND ";
                $sql .= "cuestionario_id = {$row_cuestionario->cuestionario_id}";
                
                $this->db->query($sql);
            }
        
        //Eliminar registro de la Tabla principal
            $this->db->where('id', $pregunta_id);
            $this->db->delete('pregunta');
        
        //Eliminar registros de las Tablas relacionadas
            $tablas = array(
                'cuestionario_pregunta',
                'usuario_pregunta'
            );
            
            foreach( $tablas as $tabla )
            {
                $this->db->where('pregunta_id', $pregunta_id);
                $this->db->delete($tabla);
            }
            
            
        //Reenumerar el campo pregunta.orden de las demás Preguntas del tema_id que la Pregunta eliminada tenía
            $this->Tema_model->numerar_preguntas($row->tema_id);
            
    }
    
    /**
     * Elimina los archivos en el servidor,
     * las imágenes asociadas a las Preguntas de los cuestionario
     * Dos carpetas, tamaño original y miniatura
     * 
     * @param type $nombre_archivo 
     */
    function eliminar_img_pregunta($nombre_archivo)
    {
        //Construir rutas con las constantes definidas
            $ruta_archivo = FCPATH . RUTA_UPLOADS . 'preguntas/' . $nombre_archivo;
            
            if ( file_exists($ruta_archivo) && strlen($nombre_archivo) > 2 )
            { 
                echo 'Archivo: ' . $nombre_archivo;
                echo '</br>';
                unlink($ruta_archivo);
            }
    }
    
    /**
     * Cuestionarios en los que aparece una Pregunta
     * 
     * @param type $pregunta_id
     * @return type
     */
    function cuestionario_pregunta($pregunta_id)
    {
        
        $this->db->where('pregunta_id', $pregunta_id);
        $this->db->join('flipbook', 'flipbook_contenido.flipbook_id = flipbook.id');
        return $this->db->get('flipbook_contenido');
        
    }
    
    function cuestionarios($pregunta_id)
    {
        //Cuestionarios
            $this->db->join('cuestionario_pregunta', 'cuestionario.id = cuestionario_pregunta.cuestionario_id');
            $this->db->where('pregunta_id', $pregunta_id);
            $cuestionarios = $this->db->get('cuestionario');
            
        return $cuestionarios;
    }
    
    /**
     * Devuelve los detalles de una Pregunta específica
     * 
     * Si se requiere se puede filtrar por los detalles privados (campo 'publico' = FALSE) de
     * un usuario_id determinado
     * 
     * @param type $pregunta_id 
     */
    function pregunta_detalles($pregunta_id, $usuario_id = NULL)
    {
        
        $this->db->where('pregunta_id', $pregunta_id);
        //$this->db->where('publico', 1); //Detalles públicos de la Pregunta
        return $this->db->get('pregunta_detalle');
        
        //PENDIENTE Filtrar por detalles privados de usuario
        
    }
    
    /**
     * Devuelve los links asignados a una Pregunta de cuestionario
     * 
     * Tipo detalle = 1, se asocian a la tabla 'recurso'
     * 
     * @param type $pregunta_id
     * @return type 
     */
    function pregunta_recursos($pregunta_id)
    {
        $this->db->join('recurso', 'pregunta_detalle.recurso_id = recurso.id');
        $this->db->where('pregunta_id', $pregunta_id);
        $this->db->where('tipo_detalle_id', 1);   //Valor del filtro, referenciado a la tabla item,
        $this->db->where('publico', 1); //Detalles públicos de la Pregunta
        
        return $this->db->get('pregunta_detalle');
    }
    
    /**
     * Query con las anotaciones hechas en una Pregunta por un usuario específico
     * 
     * @param type $pregunta_id
     * @param type $usuario_id 
     */
    function pregunta_anotaciones($pregunta_id, $usuario_id = NULL){
        
        
        $this->db->where('pregunta_id', $pregunta_id);
        $this->db->where('tipo_detalle_id', 3);   //Valor del filtro, referenciado a la tabla item, categoria_id = 13
        
        return $this->db->get('pregunta_detalle');
    }
    
    /**
     * Genera los clones de una Pregunta tantas veces
     * como aparezca en diferentes cuestionario, para independizar las Preguntas
     * independizar los recursos en los cuestionario clonados
     * 
     * 
     * @param type $pregunta_id
     * @return type
     */
    function independizar_pag($pregunta_id)
    {
        $contador = 0;
        
        $this->db->select('id, pregunta_id');
        $this->db->where('pregunta_id', $pregunta_id);
        $this->db->order_by('id', 'ASC');
        $query = $this->db->get('flipbook_contenido');
        
        foreach ( $query->result() as $row_contenido ){
            
            $contador += 1;
            
            //No se duplica la primera aparición de la Pregunta
            if ( $contador > 1){
                //Crear copia de Pregunta
                $pregunta_nueva_id = $this->clonar_pregunta($row_contenido->pregunta_id);

                //Cambiar el valor en flipbook_contenido
                $registro = array();
                $registro['pregunta_id'] = $pregunta_nueva_id;
                $this->db->where('id', $row_contenido->id);
                $this->db->update('flipbook_contenido', $registro);
            }
            
        }
        
        //Se devuelve el número de copias que se generaron, se descuenta la primera a la cual no se le genera copia
        return $contador - 1;
        
    }
    
    /**
     * Crea un duplicado de una Pregunta de la tabla
     * Devuelve el id de la nueva Pregunta
     * 
     * @param type $pregunta_id
     */
    function clonar($pregunta_id, $tema_id = NULL)
    {
        //Registro de la Pregunta
            $registro = array();
            $preguntas = $this->db->get_where('pregunta', "id = {$pregunta_id}");
            $nueva_pregunta_id = 0;
            
            if ( $preguntas->num_rows() > 0 ) 
            {   
                //Datos registro base
                    $registro = $preguntas->row_array();
                
                //Preparar nuevo registro
                    unset($registro['id']);
                    $registro['tema_id'] = $tema_id;
                    $registro['institucion_id'] = $this->session->userdata('institucion_id');
                    $registro['creado_usuario_id'] = $this->session->userdata('usuario_id');
                    $registro['editado_usuario_id'] = $this->session->userdata('usuario_id');
                    $registro['creado'] = date('Y-m-d H:i:s');
                    $registro['editado'] = date('Y-m-d H:i:s');
                    
                //Asignar tema
                    unset($registro['tema_id']); //Quitarle tema, para no duplicarla en la asiganación de preguntas
                    if ( ! is_null($tema_id) ) { $registro['tema_id'] = $tema_id; }
                    
                //Crear copia de archivo imagen si tiene nuevo archivo
                    if ( strlen($registro['archivo_imagen']) > 0 )
                    {
                        $nuevo_archivo =  $this->clonar_imagen($registro['archivo_imagen']);
                        $registro['archivo_imagen'] = $nuevo_archivo;
                    }

                //Insertar registro
                    $this->db->insert('pregunta', $registro);
                    $nueva_pregunta_id = $this->db->insert_id();
            }
            
        return $nueva_pregunta_id;
        
    }
    
    /**
     * Crea una copia de una imagen asociada a una pregunta. Carpeta "preguntas"
     * 
     * @param type $nombre_archivo
     * @return string
     */
    function clonar_imagen($nombre_archivo)
    {
        $nuevo_archivo = NULL;
        $ruta_original = RUTA_UPLOADS . 'preguntas/' . $nombre_archivo;
        
        if ( file_exists($ruta_original) )
        {   
            $this->load->helper('string');
            
            $nuevo_archivo = 'zz_copia_' . random_string('alnum', 4) . $nombre_archivo;
            $ruta_nueva = RUTA_UPLOADS . 'preguntas/' . $nuevo_archivo;
            
            copy($ruta_original, $ruta_nueva);  //Crear copia del archivo
        }
        
        return $nuevo_archivo;
    }
    
    /**
     * Le asigna un tema a una pregunta
     *
     */
    function asignar_tema($pregunta_id, $registro)
    {
        //Calculando el número de Preguntas actual
        $query = $this->db->get_where('pregunta', "tema_id = {$registro['tema_id']}");
        $cant_preguntas = $query->num_rows();
        
        if ( $cant_preguntas == 0 ) {
            //No hay Preguntas, es la primera
            $registro['orden'] = 0;
        } elseif ( $registro['orden'] > $cant_preguntas OR !is_numeric($registro['orden']) ) {
            //Es mayor al número actual de Preguntas, se cambia, poniéndolo al final
            $registro['orden'] = $cant_preguntas;
        } else {
            //Se inserta en un punto intermedio, se cambian los números de las Preguntas siguientes
            $this->db->query("UPDATE pregunta SET orden = (orden + 1) WHERE orden >= {$registro['orden']} AND tema_id = {$registro['tema_id']}");
        }
        
        //Se modifica el registro
            $this->db->where('id', $pregunta_id);
            $this->db->update('pregunta', $registro);        
        
    }
    
}