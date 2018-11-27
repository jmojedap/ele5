<?php

class Flipbook_model extends CI_Model {

    function basico($flipbook_id) {
        $row = $this->datos_flipbook($flipbook_id);

        $basico['flipbook_id'] = $flipbook_id;
        $basico['row'] = $row;
        $basico['titulo_pagina'] = $row->nombre_flipbook;
        $basico['vista_a'] = 'flipbooks/flipbook_v';

        return $basico;
    }

    /**
     * Búsqueda de flipbooks
     * 
     * @param type $busqueda
     * @param type $per_page
     * @param type $offset
     * @return type
     */
    function buscar($busqueda, $per_page = NULL, $offset = NULL) {

        //Filtro según el rol de usuario que se tenga
        $filtro_rol = $this->filtro_rol($this->session->userdata('usuario_id'));

        //Construir búsqueda
        //Texto búsqueda
        //Crear array con términos de búsqueda
        if (strlen($busqueda['q']) > 2) {
            $concat_campos = $this->Busqueda_model->concat_campos(array('id', 'nombre_flipbook', 'descripcion', 'anio_generacion'));
            $palabras = $this->Busqueda_model->palabras($busqueda['q']);

            foreach ($palabras as $palabra_busqueda) {
                $this->db->like("CONCAT({$concat_campos})", $palabra_busqueda);
            }
        }

        //Otros filtros
        if ($busqueda['a'] != '') {
            $this->db->where('area_id', $busqueda['a']);
        }            //Área
        if ($busqueda['n'] != '') {
            $this->db->where('nivel', $busqueda['n']);
        }              //Nivel
        if ($busqueda['tp'] != '') {
            $this->db->where('tipo_flipbook_id', $busqueda['tp']);
        } //Tipo
        if ($busqueda['condicion'] != '') {
            $this->db->where($busqueda['condicion']);
        }       //Condición especial
        //Otros
        $this->db->where($filtro_rol);  //Filtro por rol
        $this->db->order_by('nombre_flipbook', 'ASC');

        //Obtener resultados
        if (is_null($per_page)) {
            $query = $this->db->get('flipbook'); //Resultados totales
        } else {
            $query = $this->db->get('flipbook', $per_page, $offset); //Resultados por página
        }

        return $query;
    }

    function filtro_rol($usuario_id = NULL) {

        if (is_null($usuario_id)) {
            //Si es nulo, es el usuario de la sesión actual
            $usuario_id = $this->session->userdata('usuario_id');
        }

        $row_usuario = $this->Pcrn->registro_id('usuario', $usuario_id);
        $condicion = "id = 0";  //Valor por defecto, ningún flipbook, se obtendrían cero flipbooks.

        if (in_array($row_usuario->rol_id, array(0, 1, 2))) {
            //Todos los flipbooks
            $condicion = 'id > 0';
        } elseif ($row_usuario->rol_id == 5) {
            $this->load->model('Usuario_model');
            //Profesor
            $condicion = $this->Usuario_model->condicion_fb_profesor($usuario_id);
        } else {
            $condicion = 'id = 0';  //Pendiente, ningún resultado
        }

        return $condicion;
    }

    /**
     * Devuelve objeto registro con los datos básicos de un flipbook
     * 
     * @param type $flipbook_id
     * @return type 
     */
    function datos_flipbook($flipbook_id) {

        //Devuelve un objeto de registro con los datos del flipbook

        $this->db->where('id', $flipbook_id);
        $query = $this->db->get('flipbook', 1);

        $datos_flipbook = FALSE;

        if ($query->num_rows() > 0) {
            $row = $query->row();

            //Calculando el número de páginas
            $this->db->select('id');
            $query = $this->db->get_where('flipbook_contenido', "flipbook_id = {$flipbook_id}");
            $row->num_paginas = $query->num_rows();

            //
            $datos_flipbook = $row;
        }

        //Calculando

        return $datos_flipbook;
    }

    function editable() {
        return TRUE;
    }

    function eliminar($flipbook_id) {
        //Tabla tema
        $this->db->where('id', $flipbook_id);
        $this->db->delete('flipbook');

        //Tablas relacionadas
        $tablas = array('flipbook_contenido', 'usuario_flipbook');

        foreach ($tablas as $tabla) {
            $this->db->where('flipbook_id', $flipbook_id);
            $this->db->delete($tabla);
        }

        //Tabla meta
        $this->db->where('tabla_id', 4300); //Tabla flipbook
        $this->db->where('elemento_id', $flipbook_id);
        $this->db->delete('meta');
    }

    /**
     * Crea masivamente la asignación de talleres
     * tabla flipbook
     * 
     * @param type $array_hoja    Array con los datos de los contenidos y talleres
     */
    function asignar_taller($array_hoja) {
        $this->load->model('Esp');

        $no_importados = array();
        $fila = 2;  //Inicia en la fila 2 de la hoja de cálculo
        //Predeterminados registro modificado
        $registro['editado'] = date('Y-m-d H:i:s');
        $registro['editor_id'] = $this->session->userdata('usuario_id');

        foreach ($array_hoja as $array_fila) {
            //Complementar registro
            $registro['taller_id'] = $array_fila[1];

            //Validar
            $condiciones = 0;
            if (strlen($array_fila[0]) > 0) {
                $condiciones++;
            }   //Debe tener valor escrito
            if (strlen($array_fila[1]) > 0) {
                $condiciones++;
            }   //Debe tener valor escrito
            //Si cumple las condiciones
            if ($condiciones == 2) {
                $this->db->where('id', $array_fila[0]);
                $this->db->update('flipbook', $registro);
            } else {
                $no_importados[] = $fila;
            }

            $fila++;    //Para siguiente fila
        }

        return $no_importados;
    }

//GROCERY CRD PARA FLIPBOOKS
//---------------------------------------------------------------------------------------------------

    function crud_editar($flipbook_id) {
        //Grocery crud
        $this->load->library('grocery_CRUD');

        $crud = new grocery_CRUD();
        $crud->set_table('flipbook');
        $crud->set_subject('flipbook');
        $crud->unset_export();
        $crud->unset_print();
        $crud->unset_back_to_list();
        $crud->unset_delete();
        $crud->unset_read();
        $crud->columns('nombre_flipbook', 'area_id', 'nivel', 'descripcion');

        //Filtro
        $crud->where('flipbook.id', 0);

        //Callback, vista
        $crud->callback_column('nombre_flipbook', array($this, 'gc_link_flipbook'));

        //Títulos de los campos
        $crud->display_as('area_id', 'Área');
        $crud->display_as('taller_id', 'Taller asociado');
        $crud->display_as('descripcion', 'Descripción');
        $crud->display_as('anio_generacion', 'Año generación');
        $crud->display_as('tipo_flipbook_id', 'Tipo de Contenido');

        //Filtro para opciones de temas
        $row_flipbook = $this->Pcrn->registro_id('flipbook', $flipbook_id);
        $condicion = "tipo_flipbook_id = 1 AND area_id = {$row_flipbook->area_id} AND nivel = {$row_flipbook->nivel}";

        //Relaciones
        $crud->set_relation('area_id', 'item', 'item', 'categoria_id = 1');
        $crud->set_relation('taller_id', 'flipbook', '{anio_generacion} | {nombre_flipbook}', $condicion, 'anio_generacion DESC');

        //Opciones nivel
        $opciones_nivel = $this->App_model->opciones_nivel('item_largo');
        $crud->field_type('nivel', 'dropdown', $opciones_nivel);


        //Formulario Edit
        $crud->edit_fields(
                'nombre_flipbook', 'anio_generacion', 'area_id', 'nivel', 'taller_id', 'tipo_flipbook_id', 'descripcion', 'editado', 'editor_id'
        );

        //Formulario Add
        $crud->add_fields(
                'nombre_flipbook', 'anio_generacion', 'area_id', 'nivel', 'descripcion', 'editado', 'editor_id', 'creado', 'creador_id'
        );

        //Funciones
        //$crud->callback_after_update(array($this, 'gc_after_insert'));
        //Tipo de campos
        $opciones_anio = $this->Pcrn->array_rango(date('Y') - 10, date('Y') + 10);
        $opciones_tipo = $this->Item_model->arr_item(11);

        //Reglas de validación
        $crud->required_fields('nombre_flipbook', 'area_id', 'nivel');

        //Valores por defecto
        $crud->field_type('anio_generacion', 'enum', $opciones_anio);
        $crud->field_type('tipo_flipbook_id', 'dropdown', $opciones_tipo);
        $crud->field_type('editado', 'hidden', date('Y-m-d H:i:s'));
        $crud->field_type('editor_id', 'hidden', $this->session->userdata('usuario_id'));
        $crud->field_type('creado', 'hidden', date('Y-m-d H:i:s'));
        $crud->field_type('creador_id', 'hidden', $this->session->userdata('usuario_id'));

        //Formato
        $crud->unset_texteditor('descripcion');

        $output = $crud->render();

        return $output;
    }

    function crud_nuevo() {
        //Grocery crud
        $this->load->library('grocery_CRUD');

        $crud = new grocery_CRUD();
        $crud->set_table('flipbook');
        $crud->set_subject('flipbook');
        $crud->unset_export();
        $crud->unset_print();
        $crud->unset_back_to_list();
        $crud->unset_delete();
        $crud->unset_read();

        //Filtro
        $crud->where('flipbook.id', 0);

        //Títulos de los campos
        $crud->display_as('area_id', 'Área');
        $crud->display_as('taller_id', 'Taller asociado');
        $crud->display_as('descripcion', 'Descripción');
        $crud->display_as('anio_generacion', 'Año generación');

        //Relaciones
        $crud->set_relation('area_id', 'item', 'item', 'categoria_id = 1');
        $crud->set_relation('taller_id', 'flipbook', '{anio_generacion} | {nombre_flipbook}', 'tipo_flipbook_id = 1', 'anio_generacion DESC');

        //Formulario Add
        $crud->add_fields(
                'nombre_flipbook', 'anio_generacion', 'area_id', 'nivel', 'descripcion', 'editado', 'editor_id', 'creado', 'creador_id'
        );

        //Opciones nivel
        $opciones_nivel = $this->App_model->opciones_nivel('item_largo');
        $crud->field_type('nivel', 'dropdown', $opciones_nivel);

        //Tipo de campos
        $opciones_anio = $this->Pcrn->array_rango(date('Y'), date('Y') + 4);
        $crud->field_type('anio_generacion', 'enum', $opciones_anio);

        //Reglas de validación
        $crud->required_fields('nombre_flipbook', 'area_id', 'nivel');
        $crud->set_rules('nivel', 'Nivel', 'greater_than[0]|less_than[12]');

        //Valores por defecto
        $crud->change_field_type('editado', 'hidden', date('Y-m-d H:i:s'));
        $crud->change_field_type('editor_id', 'hidden', $this->session->userdata('usuario_id'));
        $crud->change_field_type('creado', 'hidden', date('Y-m-d H:i:s'));
        $crud->change_field_type('creador_id', 'hidden', $this->session->userdata('usuario_id'));

        //Formato
        $crud->unset_texteditor('descripcion');

        $output = $crud->render();

        return $output;
    }

    function gc_link_flipbook($value, $row) {

        $texto = $row->nombre_flipbook;
        $att = 'title="Ir al perfil de ' . $value . '"';
        return anchor("flipbooks/temas/{$row->id}", $texto, $att);
    }

    function gc_link_taller($value, $row) {

        $texto = '-';
        if ($row->taller_id > 0) {
            $texto = anchor("flipbooks/temas/{$row->taller_id}", $this->App_model->nombre_flipbook($row->taller_id));
        }

        return $texto;
    }

//General
//---------------------------------------------------------------------------------------------------

    /**
     * Verifica si un usuario tiene permiso de ver o leer un flipbook
     * @param type $flipbook_id
     */
    function visible($flipbook_id) {
        $condiciones = 0;
        $visible = TRUE;

        $row_flipbook = $this->Pcrn->registro_id('flipbook', $flipbook_id);

        if ($row_flipbook->tipo_flipbook_id == 1) {
            $condiciones += 1;
        }
        if ($this->session->userdata('rol_id') == 6) {
            $condiciones += 1;
        }

        if ($condiciones >= 2) {
            $visible = FALSE;
        }

        return $visible;
    }

//FUNCIONES PARA flipbook/leer
//---------------------------------------------------------------------------------------------------

    /**
     * String con la ruta y nombre del archivo JSON con el contenido del flipboook
     * utilizado para crear la vista de lectura.
     * 
     * @param type $flipbook_id
     * @return string
     */
    function ruta_json($flipbook_id) {
        $nombre_archivo = str_pad($flipbook_id, 5, '0', STR_PAD_LEFT) . '.json';
        $ruta_archivo = RUTA_CONTENT . 'flipbooks_json/' . $nombre_archivo;

        return $ruta_archivo;
    }

    function crear_json($flipbook_id) {
        //El archivo JSON del flipbook no existe, se crea.
        //Datos básicos
        $data = $this->basico($flipbook_id);
        $ruta_archivo = $this->ruta_json($flipbook_id);

        //Variables 
        $data['paginas'] = $this->paginas($flipbook_id)->result();
        $data['archivos'] = $this->archivos($flipbook_id)->result();
        $data['audios'] = $this->archivos($flipbook_id, 621)->result();
        $data['animaciones'] = $this->archivos($flipbook_id, 619)->result();
        $data['planes_aula'] = $this->planes_aula($flipbook_id)->result();
        $data['quices'] = $this->quices($flipbook_id)->result();
        $data['links'] = $this->links($flipbook_id)->result();
        $data['relacionados'] = $this->n_arr_relacionados($flipbook_id);

        $data_str = json_encode($data);
        file_put_contents($ruta_archivo, $data_str);

        return $data_str;
    }

    function paginas($flipbook_id) {
        $this->db->select('pagina_id, num_pagina, tema_id, archivo_imagen, nombre_tema, titulo_tema');
        $this->db->select('archivo_imagen');
        $this->db->join('pagina_flipbook', 'flipbook_contenido.pagina_id = pagina_flipbook.id');
        $this->db->join('tema', 'pagina_flipbook.tema_id = tema.id', 'LEFT');
        $this->db->where('flipbook_id', $flipbook_id);
        $this->db->order_by('num_pagina', 'ASC');
        $paginas = $this->db->get('flipbook_contenido');

        return $paginas;
    }

    /**
     * Query con archivos asociados a un flipbook, filtratos por tipo
     * @param type $flipbook_id
     * @param type $tipo_archivo_id (Agregado 2018-10-09)
     * @return type
     */
    function archivos($flipbook_id, $tipo_archivo_id = NULL) {
        $this->db->select('recurso.id AS archivo_id, nombre_archivo, slug AS tipo_archivo, CONCAT( (slug), (".png")) AS icono, CONCAT( (slug), ("/"),(nombre_archivo)) AS ubicacion, num_pagina');
        $this->db->join('pagina_flipbook', 'recurso.tema_id = pagina_flipbook.tema_id');
        $this->db->join('flipbook_contenido', 'pagina_flipbook.id = flipbook_contenido.pagina_id');
        $this->db->join('item', 'recurso.tipo_archivo_id = item.id');
        $this->db->order_by('tipo_archivo_id', 'ASC');
        $this->db->where('flipbook_id', $flipbook_id);
        $this->db->where('tipo_recurso_id', 1);
        //$this->db->where('tipo_archivo_id NOT IN (638,639,854)');   //Ver item.id, categoria_id = 20

        if (!is_null($tipo_archivo_id)) {
            $this->db->where('tipo_archivo_id', $tipo_archivo_id);
        } else {
            $this->db->where('tipo_archivo_id IN (620, 622, 623)');
        }

        $this->db->where('disponible', 1);
        $archivos = $this->db->get('recurso');

        return $archivos;
    }

    function links($flipbook_id) {
        $this->db->select('url, num_pagina');
        $this->db->join('pagina_flipbook', 'recurso.tema_id = pagina_flipbook.tema_id');
        $this->db->join('flipbook_contenido', 'pagina_flipbook.id = flipbook_contenido.pagina_id');
        $this->db->join('item', 'recurso.tipo_archivo_id = item.id');
        $this->db->where('flipbook_id', $flipbook_id);
        $this->db->where('tipo_recurso_id', 2);
        $archivos = $this->db->get('recurso');

        return $archivos;
    }

    /**
     * Array con los ID de los quices asociados a los temas y subtemas (temas
     * relacionados) de un flipbook.
     * 
     * @param type $flipbook_id
     * @return type
     */
    function quices_total($flipbook_id) {
        $arr_quices = array();
        $temas = $this->temas($flipbook_id);
        $str_temas = $this->Pcrn->query_to_str($temas, 'tema_id', ',');

        $this->db->select('quiz.*, tema.nombre_tema');
        $this->db->join('quiz', 'recurso.referente_id = quiz.id');
        $this->db->join('tema', 'recurso.tema_id = tema.id');
        $this->db->where("recurso.tema_id IN ({$str_temas})");
        $this->db->where('tipo_recurso_id', 3); //Tipo quiz
        $quices = $this->db->get('recurso');

        foreach ($quices->result() as $row_quiz) {
            $quiz['tema_id'] = $row_quiz->tema_id;
            $quiz['id'] = $row_quiz->id;
            $quiz['nombre_quiz'] = $row_quiz->nombre_quiz;
            $quiz['nombre_tema'] = $row_quiz->nombre_tema;

            $arr_quices[] = $quiz;
        }

        return $arr_quices;
    }

    /**
     * Array con los quices de los temas y subtemas (para UT) de un flipbook, 
     * clasificados para cada página del flipbook
     * 2017-01-20
     * 
     * @param type $flipbook_id
     * @return type
     */
    function quices_total_pag($flipbook_id) {
        $this->load->model('Tema_model');
        $paginas = $this->paginas($flipbook_id);
        $arr_quices = array();
        $quiz = array();

        foreach ($paginas->result() as $row_pagina) {
            $arr_temas = $this->Tema_model->arr_relacionados($row_pagina->tema_id);
            $arr_temas[] = $row_pagina->tema_id;    //Se agrega el tema "padre"

            foreach ($arr_temas as $tema_id) {
                $quices = $this->Tema_model->quices($tema_id);
                foreach ($quices->result() as $row_quiz) {
                    $quiz['num_pagina'] = $row_pagina->num_pagina;
                    $quiz['tema_id'] = $tema_id;
                    $quiz['id'] = $row_quiz->id;
                    $quiz['nombre_quiz'] = $row_quiz->nombre_quiz;

                    $arr_quices[] = $quiz;
                }
            }
        }

        return $arr_quices;
    }

    function quices($flipbook_id) {
        $this->db->select('recurso.referente_id AS quiz_id, num_pagina, recurso.tema_id');
        $this->db->join('pagina_flipbook', 'recurso.tema_id = pagina_flipbook.tema_id');
        $this->db->join('flipbook_contenido', 'pagina_flipbook.id = flipbook_contenido.pagina_id');
        $this->db->where('flipbook_id', $flipbook_id);
        $this->db->where('tipo_recurso_id', 3);
        $this->db->order_by('num_pagina', 'ASC');
        $quices = $this->db->get('recurso');

        return $quices;
    }

    /**
     * Array, con los quices de los temas relacionados
     * 
     * @param type $relacionados
     * @return type
     */
    function subquices($relacionados) {
        $this->load->model('Tema_model');
        $subquices = array();

        for ($i = 1; $i <= 3; $i++) {
            foreach ($relacionados[$i]->result() as $row_relacionado) {
                $query_subquices = $this->Tema_model->quices($row_relacionado->relacionado_id);
                foreach ($query_subquices->result() as $row_quiz) {
                    $subquiz['num_pagina'] = $row_relacionado->num_pagina;
                    $subquiz['subquiz_id'] = $row_quiz->id;
                    $subquiz['nombre_quiz'] = $row_quiz->nombre_quiz;
                    $subquices[] = $subquiz;
                }
            }
        }

        return $subquices;
    }

    function planes_aula($flipbook_id) {
        $this->db->select('recurso.id AS archivo_id, nombre_archivo, slug AS tipo_archivo, CONCAT( (slug), (".png")) AS icono, CONCAT( (slug), ("/"),(nombre_archivo)) AS ubicacion, num_pagina');
        $this->db->join('pagina_flipbook', 'recurso.tema_id = pagina_flipbook.tema_id');
        $this->db->join('flipbook_contenido', 'pagina_flipbook.id = flipbook_contenido.pagina_id');
        $this->db->join('item', 'recurso.tipo_archivo_id = item.id');
        $this->db->order_by('tipo_archivo_id', 'ASC');
        $this->db->where('flipbook_id', $flipbook_id);
        $this->db->where('tipo_recurso_id', 1);
        $this->db->where('tipo_archivo_id', 854);   //Plan de aula, ver item.id
        $this->db->where('disponible', 1);
        $planes_aula = $this->db->get('recurso');

        return $planes_aula;
    }

    /**
     * Array con los objetos query, de los quices relacionados, con las tres 
     * tipos de relación de temas UT con temas.
     * 
     * @param type $flipbook_id
     */
    function arr_relacionados($flipbook_id) {
        $relacionados[1] = $this->Flipbook_model->relacionados($flipbook_id, 1);
        $relacionados[2] = $this->Flipbook_model->relacionados($flipbook_id, 2);
        $relacionados[3] = $this->Flipbook_model->relacionados($flipbook_id, 3);

        return $relacionados;
    }

    /**
     * Array con los objetos query->result, de los quices relacionados, con las tres 
     * tipos de relación de temas UT con temas.
     * 
     * @param type $flipbook_id
     */
    function n_arr_relacionados($flipbook_id) {
        $relacionados[1] = $this->Flipbook_model->relacionados($flipbook_id, 1)->result();
        $relacionados[2] = $this->Flipbook_model->relacionados($flipbook_id, 2)->result();
        $relacionados[3] = $this->Flipbook_model->relacionados($flipbook_id, 3)->result();

        return $relacionados;
    }

    /**
     * Query con temas relacionados con el tema de cada página
     * 
     * @param type $flipbook_id
     * @param type $relacion_id
     * @return type
     */
    function relacionados($flipbook_id, $relacion_id) {
        $this->db->select('meta.elemento_id AS tema_id, meta.relacionado_id, num_pagina, tema.nombre_tema AS nombre_tema_relacionado');
        $this->db->join('pagina_flipbook', 'meta.elemento_id = pagina_flipbook.tema_id');
        $this->db->join('flipbook_contenido', 'pagina_flipbook.id = flipbook_contenido.pagina_id');
        $this->db->join('tema', 'tema.id = meta.relacionado_id');
        $this->db->where('flipbook_id', $flipbook_id);
        $this->db->where('tabla_id', 4540); //Tabla tema
        $this->db->where('dato_id', 4541);  //Tema relacionado
        $this->db->where('categoria_1', $relacion_id); //Tipo de relación de tema con Unidad Temática
        $this->db->order_by('num_pagina', 'ASC');
        $quices = $this->db->get('meta');

        return $quices;
    }

    function anotaciones($flipbook_id, $usuario_id = NULL) {
        if (is_null($usuario_id)) {
            $usuario_id = $this->session->userdata('usuario_id');
        }

        $this->db->select('flipbook_contenido.pagina_id, anotacion, num_pagina, editado');
        $this->db->where('tipo_detalle_id', 3);
        $this->db->join('flipbook_contenido', 'pagina_flipbook_detalle.pagina_id = flipbook_contenido.pagina_id');
        $this->db->where('flipbook_contenido.flipbook_id', $flipbook_id);
        $this->db->where('usuario_id', $usuario_id);
        $this->db->order_by('num_pagina', 'ASC');
        $anotaciones = $this->db->get('pagina_flipbook_detalle');

        return $anotaciones;
    }

    /**
     * Array con elementos que deben mostrarse en el menú de la función flipbooks/leer
     * 
     * @param type $row_flipbook
     * @return boolean
     */
    function elementos_fb($row_flipbook) {
        //Valores por defecto
        $elementos = array(
            'recursos' => 1,
            'crear_cuestionario' => 1,
            'programar_temas' => 1,
            'plan_aula' => 1,
            'herramientas_adicionales' => 0,
            'temas_relacionados' => 1
        );

        //Control crear_cuestionario
        if ($this->session->userdata('rol_id') > 5) {
            $elementos['crear_cuestionario'] = 0;
            $elementos['programar_temas'] = 0;
        }
        if (in_array($row_flipbook->tipo_flipbook_id, array(1))) {
            $elementos['crear_cuestionario'] = 0;
            $elementos['programar_temas'] = 0;
        }

        //Control plan_aula
        if ($row_flipbook->tipo_flipbook_id != 3) {
            $elementos['plan_aula'] = 0;
            $elementos['temas_relacionados'] = 0;
        }

        if ($this->session->userdata('rol_id') == 6) {
            $elementos['plan_aula'] = 0;
        }

        //Control herramientas_adicionales
        if ($elementos['crear_cuestionario'] OR $elementos['temas_relacionados']) {
            $elementos['herramientas_adicionales'] = 1;
        }


        return $elementos;
    }

    /**
     * Listado de quices de un flipbook sin definir página en la que aparece
     */
    function quices_no_pag($flipbook_id) {
        $this->db->select('recurso.referente_id AS quiz_id, recurso.tema_id');
        $this->db->join('pagina_flipbook', 'recurso.tema_id = pagina_flipbook.tema_id');
        $this->db->join('flipbook_contenido', 'pagina_flipbook.id = flipbook_contenido.pagina_id');
        $this->db->where('flipbook_id', $flipbook_id);
        $this->db->where('tipo_recurso_id', 3);
        $this->db->order_by('num_pagina', 'ASC');
        $this->db->group_by('recurso.referente_id, recurso.tema_id');
        $quices = $this->db->get('recurso');

        return $quices;
    }

//GESTIÓN DE PÁGINAS    
//---------------------------------------------------------------------------------------------------

    /**
     * Devuleve el registro de una página a partir del flipbook_id y el número de página
     * Formato array
     * 
     * @param type $flipbook_id
     * @param type $num_pagina
     * @return type
     */
    function pagina_num($flipbook_id, $num_pagina) {
        $datos_pagina = array(
            'id' => 0,
            'tema_id' => 0,
            'archivo_imagen' => ''
        );

        $this->db->select('pagina_flipbook.id, tema_id, archivo_imagen');
        $this->db->where('flipbook_id', $flipbook_id);
        $this->db->where('num_pagina', $num_pagina);
        $this->db->join('flipbook_contenido', 'pagina_flipbook.id = flipbook_contenido.pagina_id');
        $paginas = $this->db->get('pagina_flipbook');

        if ($paginas->num_rows() > 0) {
            $datos_pagina = $paginas->row_array();
        }

        return $datos_pagina;
    }

    /**
     * Temas que están incluidos en las páginas que componen un flipbook.
     * Modificado 2018-11-27
     * 
     * @param type $flipbook_id
     * @return type
     */
    function temas($flipbook_id)
    {
        $this->db->select('id, cod_tema, nombre_tema, area_id, nivel');
        $this->db->where("id IN (SELECT tema_id FROM flipbook_tema WHERE flipbook_id = {$flipbook_id})");

        $temas = $this->db->get('tema');

        return $temas;
    }

    /**
     * Array con los ID de los temas que componen un flipbook y también los
     * subtemas (temas relacionados).
     * 
     * @param type $flipbook_id
     * @return type
     */
    function temas_total($flipbook_id) {
        $this->load->model('Tema_model');
        $temas_total = array();

        $temas = $this->temas($flipbook_id);

        foreach ($temas->result() as $row_tema) {
            $temas_total[] = $row_tema->tema_id;
            $arr_relacionados = $this->Tema_model->arr_relacionados($row_tema->tema_id);

            foreach ($arr_relacionados as $relacionado_id) {
                $temas_total[] = $relacionado_id;
            }
        }

        return $temas_total;
    }

    function importar_programacion($flipbook_id, $grupo_id, $array_hoja) {
        $this->load->model('Evento_model');

        $no_importados = array();
        $fila = 2;  //Inicia en la fila 2 de la hoja de cálculo
        //Predeterminados registro nuevo
        $datos['flipbook_id'] = $flipbook_id;
        $datos['grupo_id'] = $grupo_id;

        foreach ($array_hoja as $array_fila) {
            //Identificar valores
            $datos['tema_id'] = $this->Pcrn->campo('tema', "cod_tema = '{$array_fila[0]}'", 'id');
            $datos['num_pagina'] = 0;

            //Fecha
            $mktime = $this->Pcrn->fexcel_unix($array_fila[1]);
            $datos['fecha_inicio'] = date('Y-m-d', $mktime);


            //Validar
            $condiciones = 0;
            if (!is_null($datos['tema_id'])) {
                $condiciones++;
            }           //Tiene tema identificado
            if (strlen($datos['fecha_inicio']) > 0) {
                $condiciones++;
            }   //Tiene fecha establecida
            //Si cumple las condiciones
            if ($condiciones == 2) {
                $this->Evento_model->programar_tema($datos);
            } else {
                $no_importados[] = $fila;
            }

            $fila++;    //Para siguiente fila
        }

        return $no_importados;
    }

    function arr_temas($flipbook_id) {
        $temas = $this->temas($flipbook_id);
        $arr_temas = $this->Pcrn->query_to_array($temas, 'tema_id');

        return $arr_temas;
    }

    /**
     * Insertar un registro en la tabla 'flipbook_contenido'
     * 
     *
     */
    function insertar_flipbook_contenido($registro) 
    {
        //Calculando el número de páginas actual
        $query = $this->db->get_where('flipbook_contenido', "flipbook_id = {$registro['flipbook_id']}");
        $num_paginas = $query->num_rows();

        if ($num_paginas == 0) {
            //No hay páginas, es la primera
            $registro['num_pagina'] = 0;
        } elseif ($registro['num_pagina'] > $num_paginas OR ! is_numeric($registro['num_pagina'])) {
            //Es mayor al número actual de páginas, se cambia, poniéndolo al final
            $registro['num_pagina'] = $num_paginas;
        } else {
            //Se inserta en un punto intermedio, se cambian los números de las páginas siguientes
            $this->db->query("UPDATE flipbook_contenido SET num_pagina = (num_pagina + 1) WHERE num_pagina >= {$registro['num_pagina']} AND flipbook_id = {$registro['flipbook_id']}");
        }

        //Se inserta el registro
        $this->db->insert('flipbook_contenido', $registro);
    }

    /**
     * Crea una copia de un flipbook, incluyendo las páginas que lo componen
     * 
     * El argumento $independiente, si es TRUE, las páginas que lo componen son nuevas
     * Si es FALSE, se reutilizan las mismas páginas del libro original
     * 
     * @param type $datos
     * @param type $independiente
     * @return type 
     */
    function generar_copia($datos, $independiente = TRUE) {

        //Cargar registro de flipbook original
        $row_flipbook = $this->Pcrn->registro('flipbook', "id = {$datos['flipbook_id']}");

        //Crear nuevo registro en la tabla flipbook
        $registro = array(
            'nombre_flipbook' => $datos['nombre_flipbook_nuevo'],
            'nivel' => $row_flipbook->nivel,
            'area_id' => $row_flipbook->area_id,
            'tipo_flipbook_id' => $row_flipbook->tipo_flipbook_id,
            'descripcion' => $datos['descripcion'],
            'creado' => date('Y-m-d H:i:s'),
            'editado' => date('Y-m-d H:i:s'),
            'creador_id' => $this->session->userdata('usuario_id'),
            'editor_id' => $this->session->userdata('usuario_id')
        );

        $this->db->insert('flipbook', $registro);
        $flipbook_id_nuevo = $this->db->insert_id();

        //Crear registros de páginas incluidas. Tabla flipbook_contenido

        $registro = array();    //Reiniciando variable para nueva insersión
        $registro['flipbook_id'] = $flipbook_id_nuevo;

        $this->db->where('flipbook_id', $datos['flipbook_id']);
        $this->db->order_by('num_pagina', 'ASC');
        $paginas = $this->db->get('flipbook_contenido');

        foreach ($paginas->result() as $row_fc) {

            $registro['num_pagina'] = $row_fc->num_pagina;

            if ($independiente) {
                //Independiente crear una nueva página
                $registro['pagina_id'] = $this->clonar_pagina($row_fc->pagina_id);
            } else {
                //Dependiente, utilizar la misma página
                $registro['pagina_id'] = $row_fc->pagina_id;
            }

            $this->db->insert('flipbook_contenido', $registro);
        }

        //Devolver id de nuevo flipbook
        return $flipbook_id_nuevo;
    }

//---------------------------------------------------------------------------------------------------
//FUNCIONES PROCESO AJAX FLIPBOOK

    /**
     * Crea o actualiza una registro en la tabla pagina_flipbook_detalle, correspondiente a una anotación
     * en la página de un flipbook
     * @param type $registro
     * @return type
     */
    function guardar_anotacion($registro) {
        $condicion = "pagina_id = {$registro['pagina_id']} AND usuario_id = {$registro['usuario_id']}";
        $detalle_id = $this->Pcrn->guardar('pagina_flipbook_detalle', $condicion, $registro);

        return $detalle_id;
    }

    /**
     * Limpiar los caracteres no permitidos en las anotaciones, para uso con JSON
     * @param type $detalle_id
     */
    function limpiar_anotacion($detalle_id) {
        $queries[] = 'UPDATE pagina_flipbook_detalle SET anotacion = REPLACE(anotacion, "\n", " ") WHERE id = ' . $detalle_id . ';';
        $queries[] = 'UPDATE pagina_flipbook_detalle SET anotacion = REPLACE(anotacion, "\'", " ") WHERE id = ' . $detalle_id . ';';

        //Ejecutar la lista de queries
        foreach ($queries as $sql) {
            $this->db->query($sql);
        }
    }

    /**
     * Número de página bookmark para un flipbook de un usuario
     * @param type $flipbook_id
     */
    function bookmark($flipbook_id) {
        $bookmark = $this->Pcrn->campo('usuario_flipbook', "flipbook_id = {$flipbook_id} AND usuario_id = {$this->session->userdata('usuario_id')}", 'bookmark');
        if (is_null($bookmark) or empty($bookmark)) {
            $bookmark = 0;
        }

        return $bookmark;
    }

//---------------------------------------------------------------------------------------------------
// EDICIÓN DE FLIPBOOKS

    /**
     * Cambiar el orden de una página, modificando el campo flipbook_contenido.num_pagina
     * 
     * Se alterna el orden de dos páginas. Se aumenta en 1 la elegida, la página siguiente se disminuye en 1.
     * 
     * @param type $flipbook_id
     * @param type $pf_id }
     */
    function aumentar_num_pagina($flipbook_id, $pf_id) {

        //Registro de la página en la tabla flipbook_contenido
        $condicion = "flipbook_id  = {$flipbook_id} AND pagina_id = {$pf_id}";
        $row_pag = $this->Pcrn->registro('flipbook_contenido', $condicion);
        $num_pag_sig = $row_pag->num_pagina + 1;

        //Registro de la página siguiente en la tabla flipbook_contenido
        $condicion = "flipbook_id  = {$flipbook_id} AND num_pagina = {$num_pag_sig}";
        $row_pag_sig = $this->Pcrn->registro('flipbook_contenido', $condicion);

        //Se aumenta si no es la última página, es decir que exista el registro de la página siguiente ($row_pag_sig)
        if (!is_null($row_pag_sig)) {
            //Aumentar en 1 el número de página
            $this->db->where('id', $row_pag->id);
            $this->db->update('flipbook_contenido', array('num_pagina' => $row_pag->num_pagina + 1));

            //Disminuir en 1 el número de página de la que estaba después, página siguiente
            $this->db->where('id', $row_pag_sig->id);
            $this->db->update('flipbook_contenido', array('num_pagina' => $row_pag_sig->num_pagina - 1));
        }
    }

    /**
     * Cambiar el orden de una página, modificando el campo flipbook_contenido.num_pagina
     * 
     * Se disminuye en 1, a la página anterior se aumenta en 1.
     * 
     * @param type $flipbook_id
     * @param type $pf_id }
     */
    function disminuir_num_pagina($flipbook_id, $pf_id) {

        //Registro de la página en la tabla flipbook_contenido
        $condicion = "flipbook_id  = {$flipbook_id} AND pagina_id = {$pf_id}";
        $row_pag = $this->Pcrn->registro('flipbook_contenido', $condicion);
        $num_pag_ant = $row_pag->num_pagina - 1;

        //Registro de la página antuiente en la tabla flipbook_contenido
        $condicion = "flipbook_id  = {$flipbook_id} AND num_pagina = {$num_pag_ant}";
        $row_pag_ant = $this->Pcrn->registro('flipbook_contenido', $condicion);

        //Se disminuye si no es la primera página, es decir que exista el registro de la página anterior ($row_pag_ant)
        if (!is_null($row_pag_ant)) {
            //Disminuir en 1 el número de página
            $this->db->where('id', $row_pag->id);
            $this->db->update('flipbook_contenido', array('num_pagina' => $row_pag->num_pagina - 1));

            //Aumentar en 1 el número de página de la que estaba antes, página anterior
            $this->db->where('id', $row_pag_ant->id);
            $this->db->update('flipbook_contenido', array('num_pagina' => $row_pag_ant->num_pagina + 1));
        }
    }

//---------------------------------------------------------------------------------------------------
// FLIPBOOKS Y USUARIOS

    /* Función que agrega un registro a la tabla usuario_flipbook (uf)
     * 
     */
    function agregar_uf($registro) {

        $resultado = 0;

        //Se verifica que el registro que relaciona usuario y flipbook no exista
        $this->db->where('usuario_id', $registro['usuario_id']);
        $this->db->where('flipbook_id', $registro['flipbook_id']);
        $query = $this->db->get('usuario_flipbook');

        if ($query->num_rows == 0) {
            //El registro no existe, se inserta
            $this->db->insert('usuario_flipbook', $registro);
            $resultado = 1;
        }

        return $resultado;
    }

    /* Elimina un registro de la tabla usuario_flipbook (uf)
     * El parámetro condición es un array con el usuario_id y el cuestionario_id
     * que se desea eliminar
     */

    function eliminar_uf($condicion) {
        //Eliminando asignación de flipbooks
        $this->db->where($condicion);
        $resultado = $this->db->delete('usuario_flipbook');

        $resultado = $this->db->affected_rows();

        //Eliminando anotaciones, VER TABLA DE ANTOTACIONES DE ESTUDIANTES EN FLIPBOOKS
        /* PENDIENTE */

        return $resultado;
    }

//---------------------------------------------------------------------------------------------------
// DETALLES DE LAS PÁGINAS

    /**
     * Devuelve las anotaciones de los estudiantes en un flipbook
     * Los estudiantes son los que pertenecen a los grupos que un profesor tiene asignado.
     * 
     * @param type $flipbook_id
     * @param type $usuario_id
     * @return type
     */
    function anotaciones_profesor($flipbook_id, $tema_id = 0, $usuario_id = NULL) {

        //Identificar grupos del profesor
        if (in_array($this->session->userdata('rol_id'), array(3, 4, 5))) {

            $grupos_profesor_array = $this->App_model->grupos_profesor($usuario_id);
            $grupos_profesor = implode(', ', $grupos_profesor_array);

            $this->db->where("grupo_id IN ({$grupos_profesor})");
        }

        $this->db->select('pagina_flipbook_detalle.*, tema.nombre_tema');
        $this->db->where('tipo_detalle_id', 3);
        $this->db->join('flipbook_contenido', 'pagina_flipbook_detalle.pagina_id = flipbook_contenido.pagina_id');
        $this->db->join('usuario_grupo', 'pagina_flipbook_detalle.usuario_id = usuario_grupo.usuario_id');
        $this->db->join('pagina_flipbook', 'pagina_flipbook_detalle.pagina_id = pagina_flipbook.id', 'left');
        $this->db->join('tema', 'pagina_flipbook.tema_id = tema.id', 'left');
        $this->db->where('flipbook_id', $flipbook_id);
        $this->db->order_by('pagina_flipbook_detalle.editado', 'DESC');

        //Filtro por tema
        if ($tema_id > 0) {
            $this->db->where('tema_id', $tema_id);
        }

        $anotaciones = $this->db->get('pagina_flipbook_detalle');

        return $anotaciones;
    }

    /**
     * Devuelve las anotaciones de los estudiantes en un flipbook
     * Los estudiantes son los que pertenecen a un grupo de estudiantes.
     * 
     * @param type $flipbook_id
     * @param type $usuario_id
     * @return type
     */
    function anotaciones_grupo($flipbook_id, $grupo_id = NULL, $tema_id = 0) {

        $this->db->select('pagina_flipbook_detalle.*, tema.nombre_tema');
        $this->db->where('tipo_detalle_id', 3);
        $this->db->join('flipbook_contenido', 'pagina_flipbook_detalle.pagina_id = flipbook_contenido.pagina_id');
        $this->db->join('usuario_grupo', 'pagina_flipbook_detalle.usuario_id = usuario_grupo.usuario_id');
        $this->db->join('pagina_flipbook', 'pagina_flipbook_detalle.pagina_id = pagina_flipbook.id', 'LEFT');
        $this->db->join('tema', 'pagina_flipbook.tema_id = tema.id', 'left');
        $this->db->where('flipbook_id', $flipbook_id);
        $this->db->where('grupo_id', $grupo_id);
        $this->db->order_by('pagina_flipbook_detalle.editado', 'DESC');

        //Filtro por tema
        if ($tema_id > 0) {
            $this->db->where('tema_id', $tema_id);
        }

        $anotaciones = $this->db->get('pagina_flipbook_detalle');

        return $anotaciones;
    }

    function aperturas($flipbook_id) {

        //Si es usuario institucional, se limita a sus grupos
        if (in_array($this->session->userdata('rol_id'), array(3, 4, 5))) {
            //Identificar grupos del profesor
            $grupos_profesor_array = $this->App_model->grupos_profesor($this->session->userdata('usuario_id'));
            $grupos_profesor = implode(', ', $grupos_profesor_array);
            $this->db->where("usuario_grupo.grupo_id IN ({$grupos_profesor})");
        }

        $this->db->select('evento.id, evento.usuario_id, fecha_inicio, institucion_id');
        $this->db->where('referente_id', $flipbook_id);
        $this->db->order_by('fecha_inicio', 'DESC');

        $aperturas = $this->db->get('evento');

        return $aperturas;
    }

    function asignados($flipbook_id, $institucion_id) {
        $this->db->join('usuario', 'usuario.id = usuario_flipbook.usuario_id');
        $this->db->where('flipbook_id', $flipbook_id);
        $this->db->where('institucion_id', $institucion_id);
        $this->db->order_by('institucion_id', 'ASC');
        $query = $this->db->get('usuario_flipbook');

        return $query;
    }

    /**
     * Query de instituciones con estudiantes que tienen asignado el flipbook
     * @param type $flipbook_id
     * @return type
     */
    function instituciones($flipbook_id) {
        $this->db->select('institucion_id, nombre_institucion');
        $this->db->where('flipbook_id', $flipbook_id);
        $this->db->join('usuario', 'usuario.id = usuario_flipbook.usuario_id');
        $this->db->join('institucion', 'institucion.id = usuario.institucion_id');
        $this->db->order_by('institucion_id', 'ASC');
        $this->db->group_by('institucion_id');
        $query = $this->db->get('usuario_flipbook');

        return $query;
    }

    /**
     * Devuelve un query con los resaltados que tiene un flipbook
     * 
     * Un resaltado registrado en la tabla 'pagina_flipbook_detalle' y tiene el valor
     * de 2 en el campo 'tipo_detalle_id'.
     */
    function resaltados($flipbook_id) {
        $this->db->select('*, pagina_flipbook_detalle.id AS detalle_id');
        $this->db->where('tipo_detalle_id', 2);
        $this->db->join('flipbook_contenido', 'pagina_flipbook_detalle.pagina_id = flipbook_contenido.pagina_id');
        $this->db->where('flipbook_contenido.flipbook_id', $flipbook_id);
        $this->db->order_by('num_pagina', 'ASC');

        return $this->db->get('pagina_flipbook_detalle');
    }

    /**
     * Array para enviarse a la vista flipbooks/ver_flipbook_full
     * 
     * Para flipbook array, recursos asociados a un flipbook
     */
    function resultados_quices($flipbook_id) {

        //Cargar model
        $this->load->model('Usuario_model');
        $estudiantes = $this->Usuario_model->estudiantes_profesor($this->session->userdata('usuario_id'), 'string');

        //Identificar las páginas que contiene el flipbook
        $this->db->where('flipbook_id', $flipbook_id);
        $this->db->join('pagina_flipbook', 'flipbook_contenido.pagina_id = pagina_flipbook.id');
        $this->db->order_by('num_pagina', 'ASC');
        $paginas = $this->db->get('flipbook_contenido');

        //Valor inicial del array
        $resultados_array = array();

        //Recorrer las páginas
        foreach ($paginas->result() as $row_pagina) {
            //Estudiantes del profesor

            $this->db->select('usuario_asignacion.*, tema.nombre_tema');
            $this->db->join('tema', 'usuario_asignacion.referente_id = tema.id');
            $this->db->where("usuario_id IN ({$estudiantes})");
            $this->db->where('referente_id', $row_pagina->tema_id);
            $this->db->where('tipo_asignacion_id', 1);  //tipo_detalle_id = 1, corresponde a resultados de quiz

            $resultados = $this->db->get('usuario_asignacion');

            $resultados_pagina = array();
            //Recorrer los resultados de la página actual
            foreach ($resultados->result() as $row_resultado) {

                //Registro del resultado, tabla resultado, para complementar datos
                //$datos_resultado = $this->Pcrn->registro('usuario_asignacion', "id = $row_resultado->id");

                $row_resultado_array = array(
                    'num_pagina' => $row_pagina->num_pagina,
                    'usuario_id' => $row_resultado->usuario_id,
                    'tema_id' => $row_resultado->referente_id,
                    'nombre_tema' => $row_resultado->nombre_tema,
                    'nombre_usuario' => $this->App_model->nombre_usuario($row_resultado->usuario_id, 2),
                    'resultado' => $row_resultado->estado_int,
                    'editado' => $this->Pcrn->fecha_formato($row_resultado->editado, 'Y-M-d'),
                    'tiempo_hace' => $this->Pcrn->tiempo_hace($row_resultado->editado),
                );
                $resultados_pagina[] = $row_resultado_array;
            }

            $resultados_array[] = $resultados_pagina;
        }

        return $resultados_array;
    }

//---------------------------------------------------------------------------------------------------
//GESTIÓN DE PÁGINAS

    /**
     * Devuelve los detalles de una página específica
     * 
     * Si se requiere se puede filtrar por los detalles privados (campo 'publico' = FALSE) de
     * un usuario_id determinado
     * 
     * @param type $pf_id 
     */
    function pf_detalles($pf_id, $usuario_id = NULL) {

        $this->db->where('pagina_id', $pf_id);
        //$this->db->where('publico', 1); //Detalles públicos de la página
        return $this->db->get('pagina_flipbook_detalle');

        //PENDIENTE Filtrar por detalles privados de usuario
    }

    /**
     * Enumerar ordenadamente las página de un flipbook
     * 
     * Se actualiza el campo num_pagina de la tabla 'flipbook_contenido'
     * 
     * @param type $flipbook_id
     * @return int 
     */
    function reenumerar_flipbook($flipbook_id) 
    {

        $this->db->where('flipbook_id', $flipbook_id);
        $this->db->order_by('num_pagina', 'ASC');
        $paginas = $this->db->get('flipbook_contenido');
        $i = 0;

        foreach ($paginas->result() as $row_pagina) {

            $datos = array('num_pagina' => $i);
            $this->db->where('id', $row_pagina->id);
            $this->db->update('flipbook_contenido', $datos);

            $i += 1;
        }

        return $i;
    }

    /* Devuelve un array con las opciones de la tabla flipbook, limitadas por una condición definida
     * en un formato ($formato) definido
     */

    function opciones_flipbook($filtros, $formato = 1) {
        $this->db->select("CONCAT('0', flipbook.id) as flipbook_id, nombre_flipbook, CONCAT(anio_generacion, ' - ', nombre_flipbook) AS anio_nombre", FALSE);
        $this->db->where($filtros);
        $this->db->order_by('anio_generacion', 'DESC');
        $this->db->order_by('nombre_flipbook', 'ASC');
        $query = $this->db->get('flipbook');

        $campo_indice = "flipbook_id";

        if ($formato == 1) {
            $campo_valor = "nombre_flipbook";
        } elseif ($formato == 2) {
            $campo_valor = "anio_nombre";
        }

        $opciones_defecto = array(
            "" => "(Vacío)"
        );

        $opciones = array_merge($opciones_defecto, $this->Pcrn->query_to_array($query, $campo_valor, $campo_indice));

        return $opciones;
    }

    /**
     * Genera los clones de una página tantas veces
     * como aparezca en diferentes flipbooks, para independizar las páginas
     * independizar los recursos en los flipbooks clonados
     * 
     * 
     * @param type $pagina_id
     * @return type
     */
    function independizar_pag($pagina_id) {
        $contador = 0;

        $this->db->select('id, pagina_id');
        $this->db->where('pagina_id', $pagina_id);
        $this->db->order_by('id', 'ASC');
        $query = $this->db->get('flipbook_contenido');

        foreach ($query->result() as $row_contenido) {

            $contador += 1;

            //No se duplica la primera aparición de la página
            if ($contador > 1) {
                //Crear copia de página
                $pagina_nueva_id = $this->clonar_pagina($row_contenido->pagina_id);

                //Cambiar el valor en flipbook_contenido
                $registro = array();
                $registro['pagina_id'] = $pagina_nueva_id;
                $this->db->where('id', $row_contenido->id);
                $this->db->update('flipbook_contenido', $registro);
            }
        }

        //Se devuelve el número de copias que se generaron, se descuenta la primera a la cual no se le genera copia
        return $contador - 1;
    }

    /**
     * Crea un duplicado de una página de la tabla
     * Devuelve el id de la nueva página
     * 
     * @param type $pagina_id
     */
    function clonar_pagina($pagina_id) {
        //Registro de la página
        $row_pagina = $this->Pcrn->registro('pagina_flipbook', "id = {$pagina_id}");

        //Nuevo registro
        $registro['titulo_pagina'] = $row_pagina->titulo_pagina;
        $registro['archivo_imagen'] = $row_pagina->archivo_imagen;
        $registro['tema_id'] = $row_pagina->tema_id;
        $registro['pagina_origen_id'] = $pagina_id;

        $this->db->insert('pagina_flipbook', $registro);
        $nueva_pagina_id = $this->db->insert_id();

        //pagina_flipbook_detalle
        $this->db->where('pagina_id', $pagina_id);
        $this->db->where('tipo_detalle_id', 1); //Solo links de recursos 2013-08-26
        $query = $this->db->get('pagina_flipbook_detalle');

        foreach ($query->result_array() as $row_detalle) {
            $registro = $row_detalle;
            unset($registro['id']);
            $registro['pagina_id'] = $nueva_pagina_id;
            $registro['id_alfanumerico'] = strtoupper($this->Pcrn->alfanumerico_random(16));
            $registro['usuario_id'] = $this->session->userdata('usuario_id');
            $registro['editado'] = date('Y-m-d H:i:s');

            $this->db->insert('pagina_flipbook_detalle', $registro);
        }

        return $nueva_pagina_id;
    }

}
