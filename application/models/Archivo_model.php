<?php
class Archivo_model extends CI_Model{
    
    function basico($archivo_id)
    {
        
        $this->db->where('id', $archivo_id);
        $query = $this->db->get('archivo');
        
        $row = $query->row();
        
        $basico['primer_archivo_id'] = $this->archivo_id();
        $basico['row'] = $row;
        $basico['titulo_pagina'] = $row->titulo_archivo;
        $basico['vista_a'] = 'archivos/archivo_v';
        
        return $basico;
    }
    
    function buscar($busqueda, $per_page = NULL, $offset = NULL)
    {

        //Construir búsqueda
        //Crear array con términos de búsqueda
            if ( strlen($busqueda['q']) > 2 )
            {
                $concat_campos = $this->Busqueda_model->concat_campos(array('nombre_archivo', 'titulo_archivo', 'subtitulo', 'descripcion', 'palabras_clave', 'link', 'meta'));
                $palabras = $this->Busqueda_model->palabras($busqueda['q']);

                foreach ($palabras as $palabra) {
                    $this->db->like("CONCAT({$concat_campos})", $palabra);
                }
            }
        
        //Especificaciones de consulta
            $this->db->select('*, CONCAT((id), " :: ", (titulo_archivo)) AS name');
            $this->db->order_by('id', 'DESC');
            
        //Otros filtros
            if ( $busqueda['rol'] != '' ) { $this->db->where('rol_id', $busqueda['rol']); }    //Rol de archivo
            
        //Obtener resultados
        if ( is_null($per_page) ){
            $query = $this->db->get('archivo'); //Resultados totales
        } else {
            $query = $this->db->get('archivo', $per_page, $offset); //Resultados por página
        }
        
        return $query;
        
    }
    
    /**
     * Consulta para la función ajax de autocompletar, en el SELECT tiene el campo
     * "name", para mostrar el resultado.
     * 
     * @param type $busqueda
     * @param type $limit
     * @return type
     */
    function autocompletar($busqueda, $limit = 20)
    {
        //Variables previas
            //$filtro_rol = $this->filtro_buscar();
            $concat_campos = $this->Busqueda_model->concat_campos(array('id', 'nombre_archivo', 'titulo_archivo'));
        
        //Construcción de la consulta
            $this->db->select('id, CONCAT((id), " | ", (titulo_archivo), " | ",(nombre_archivo)) AS name');
            $this->db->like("CONCAT({$concat_campos})", $busqueda['q']);
            //$this->db->where($filtro_rol); //Filtro según el rol de usuario que se tenga
            $this->db->order_by('titulo_archivo', 'ASC');
            
        
            $query = $this->db->get('archivo', $limit); //Resultados totales
        
        return $query;
        
    }
    
    /**
     * Verifica si el archivo se puede editar o no, permiso.
     * @param type $archivo_id
     * @return int
     */
    function editable($archivo_id)
    {
        $editable = 1;
        return $editable;
    }
    
    /**
     * Devuelve el id del primer archivo registrado
     * @return type
     */
    function archivo_id()
    {
        $this->db->order_by('id', 'ASC');
        $archivos = $this->db->get('archivo', 1);
        
        $archivo_id = $archivos->row()->id;
        
        return $archivo_id;
    }
    
    function actualizar($archivo_id, $registro)
    {
        //Datos adicionales
            $registro['editor_id'] = $this->session->userdata('usuario_id');
        
        //Actualizar
            $this->db->where('id', $archivo_id);
            $this->db->update('archivo', $registro);
            
        //Cargar resultado
            $resultado['clase'] = 'alert alert-info';
            $resultado['mensaje'] = 'El archivo fue modificado.';

        return $resultado;
    }
    
    /**
     * Render de Grocery Crud para archivo
     * 
     * @return type
     */
    function crud_editar()
    {
        //Grocery crud
        $this->load->library('grocery_CRUD');
        
        $crud = new grocery_CRUD();
        $crud->set_table('archivo');
        $crud->set_subject('archivo');
        $crud->unset_export();
        $crud->unset_print();
        $crud->unset_back_to_list();
        $crud->unset_delete();
        
        //Títulos
            $crud->display_as('titulo_archivo', 'Título');
            $crud->display_as('subtitulo', 'Subtítulo');
            $crud->display_as('descripcion', 'Descripción');
            $crud->display_as('link', 'Link destino');
            
        //Campos
            $crud->edit_fields(
                    'titulo_archivo',
                    'subtitulo',
                    'descripcion',
                    'palabras_clave',
                    'link'
                );
            
            $crud->add_fields(
                    'titulo_archivo',
                    'subtitulo',
                    'descripcion',
                    'palabras_clave',
                    'link'
                );
            
        //Reglas
            $crud->required_fields('titulo_archivo', 'palabras_clave');
                        
        //Formato
            $crud->unset_texteditor('descripcion');
        
        $output = $crud->render();
        
        return $output;
    }
    
    /**
     * Crea el registro del archivo en la tabla archivo
     * @param type $upload_data
     */
    function crear_registro($upload_data)
    {   
        //Construir registro
            $registro['nombre_archivo'] = $upload_data['file_name'];
            $registro['ext'] = $upload_data['file_ext'];
            $registro['es_imagen'] = $upload_data['is_image'];
            $registro['palabras_clave'] = $this->input->post('palabras_clave');
            $registro['titulo_archivo'] = str_replace($upload_data['file_ext'], '', $upload_data['client_name']);  //Para quitar la extensión y punto
            $registro['carpeta'] = date('Y/m/');
            $registro['editado'] = date('Y-m-d H:i:s');
            $registro['editor_id'] = $this->session->userdata('usuario_id');
            $registro['creado'] = date('Y-m-d H:i:s');
            $registro['creador_id'] = $this->session->userdata('usuario_id');
            $registro['meta'] = json_encode($upload_data);
            
        //Insertar
            $this->db->insert('archivo', $registro);
            
        return $this->db->insert_id();
    }
    
    /**
     * Edita el registro del archivo, tabla archivo
     * @param type $upload_data
     */
    function cambiar($archivo_id, $upload_data)
    {
        //Construir registro
            $registro['nombre_archivo'] = $upload_data['file_name'];
            $registro['carpeta'] = date('Y/m/');
            $registro['ext'] = $upload_data['file_ext'];
            $registro['type'] = $upload_data['file_type'];
            $registro['es_imagen'] = $upload_data['is_image'];    //Definir si es imagen o no
            $registro['meta'] = json_encode($upload_data);
            $registro['editor_id'] = $this->session->userdata('usuario_id');
            $registro['editado'] = date('Y-m-d H:i:s');
            
        //Actualizar
            $this->db->where('id', $archivo_id);
            $this->db->update('archivo', $registro);
            
        return $this->db->affected_rows();
    }
    
// ELIMINACIÓN
//-----------------------------------------------------------------------------
    
    /**
     * Elimina archivo, miniaturas y el el registro en la tabla archivo.
     * @param type $archivo_id
     */
    function eliminar($archivo_id)
    {   
        //Eliminar archivos del servidor
            $row_archivo = $this->Pcrn->registro_id('archivo', $archivo_id);
            if ( ! is_null($row_archivo) ) 
            {
                $this->unlink($row_archivo->carpeta, $row_archivo->nombre_archivo);
            }
        
        //Eliminar registros de la base de datos
            $this->eliminar_registros($archivo_id);
    }
    
    /**
     * Elimina de la BD los registros asociados al archivo
     */
    function eliminar_registros($archivo_id)
    {
        //Desvincular registro de archivos con otros elementos
            $this->desvincular($archivo_id);
        
        //Tabla archivo
            if ( $archivo_id > 0 )
            {
                $this->db->where('id', $archivo_id);
                $this->db->delete('archivo');
            }
    }
    
    /**
     * Elimina los registros que relacionan al archivo con otros elementos de la
     * base de datos. Tambien edita los campos de registros referentes al 
     * archivo_id
     */
    function desvincular($archivo_id)
    {
        //Imagen de perfil de usuario   INACTIVA
            /*$registro['imagen_id'] = NULL;
            $this->db->where('imagen_id', $archivo_id);
            $this->db->update('usuario', $registro);*/
        
        //Archivo de Post
            $registro['imagen_id'] = NULL;
            $this->db->where('imagen_id', $archivo_id);
            $this->db->update('post', $registro);
            
        //Eliminar metadatos
            $this->db->where('dato_id IN (1, 3)');              //Meta tipo imagen (1) y archivo relacionado (3)
            $this->db->where('relacionado_id', $archivo_id);
            $this->db->delete('meta');
    }

//DATOS
//---------------------------------------------------------------------------------------------------
    
    /**
     * Devuelve un array con los atributos de una imagen, para ser usado con la funcion img();
     * 
     * @param type $archivo_id
     * @param type $prefijo
     * @return string
     */
    function att_img($archivo_id, $prefijo= '')
    {
        $row_archivo = $this->Pcrn->registro_id('archivo', $archivo_id);
        
        $att_img = array(
            'src' => URL_UPLOADS . $row_archivo->carpeta . $prefijo . $row_archivo->nombre_archivo,
            'alt' => $row_archivo->nombre_archivo,
            'style' => 'width: 100%'
        );
        
        return $att_img;
    }
    
    function att_thumbnail($archivo_id)
    {
        $row_archivo = $this->Pcrn->registro_id('archivo', $archivo_id);
        
        $att_img = array(
            'src' => URL_UPLOADS . $row_archivo->carpeta . '125px_' . $row_archivo->nombre_archivo,
            'alt' => $row_archivo->nombre_archivo,
            'style' => 'width: 100%',
        );
        
        return $att_img;
    }
    
    function row_img($archivo_id, $prefijo = '')
    {
        $row_img = NULL;
        
        $select = '*, CONCAT("' . URL_UPLOADS . '", (carpeta), "' . $prefijo . '", (nombre_archivo)) AS src';
        
        $this->db->select($select);
        $this->db->where('id', $archivo_id);
        $query = $this->db->get('archivo');
        
        if ( $query->num_rows() > 0 ) { $row_img = $query->row(); }
        
        return $row_img;
        
    }
    
//UPLOADS
//---------------------------------------------------------------------------------------------------
    
    /**
     * Realiza el upload de un archivo al servidor, crea el registro asociado en
     * la tabla "archivo".
     * 
     * @return type
     */
    function cargar($archivo_id = NULL)
    {
        $config_upload = $this->config_upload();
        $this->load->library('upload', $config_upload);
        
        $row_archivo = $this->Pcrn->registro_id('archivo', $archivo_id);

        if ( $this->upload->do_upload('archivo') )  //El archivo se cargó
        {
            $upload_data = $this->upload->data();
            
            //Identificar registro en la tabla "archivo"
                if ( is_null($archivo_id) ) 
                {
                    $archivo_id = $this->crear_registro($upload_data);
                    $row_archivo = $this->Pcrn->registro_id('archivo', $archivo_id);
                }
                
            //Si es imagen, se generan miniaturas y edita archivo original
                if ( $upload_data['is_image'] )
                {
                    $this->Archivo_model->crear_miniaturas($row_archivo->id);   //Crear miniaturas de la imagen
                    $this->Archivo_model->mod_original_id($row_archivo->id);    //Modificar imagen original después de crear miniaturas
                }
            
            //Array resultado
                $resultado = $this->res_cargue_base(TRUE);
                $resultado['upload_data'] = $this->upload->data();
                $resultado['row_archivo'] = $row_archivo;
        }
        else    //No se cargó
        {
            $resultado = $this->res_cargue_base(FALSE);
            $resultado['html'] = $this->upload->display_errors('<div role="alert" class="alert alert-danger"><i class="fa fa-warning"></i> ', '</div>');
        }
        
        return $resultado;
    }
    
    /**
     * Array base del resultado del proceso de cargue de archivo
     * 
     * @param type $ejecutado
     * @return string
     */
    function res_cargue_base($ejecutado)
    {
        //Valor principal
            $resultado['ejecutado'] = $ejecutado;
            
        //Valores por defecto, no se cargó
            $resultado['mensaje'] = 'El archivo no fue cargado';
            $resultado['clase'] = 'alert alert-danger';
        
        //Si se ejecutó el cargue
            if ( $ejecutado )
            {
                $resultado['mensaje'] = 'Archivo cargado';
                $resultado['clase'] = 'alert alert-success';
            }
        
        return $resultado;
    }
    
    /**
     * Realiza el upload de una imagen al servidor
     * @return type
     */
    function cargar_img()
    {
        $resultado['mensaje'] = '';
        
        $config = $this->config_upload();
        $this->load->library('upload', $config);

        if ( $this->upload->do_upload('archivo') ) 
        {
            $upload_data = $this->upload->data();
            
            $archivo_id = $this->crear_registro($upload_data);
            $this->crear_miniaturas($archivo_id);
            
            //Cargado
            $resultado['ejecutado'] = 1;
            $resultado['upload_data'] = $upload_data;
            $resultado['archivo_id'] = $archivo_id;
        } else {
            //No cargado
            $resultado['ejecutado'] = 0;
            $resultado['mensaje'] = 'El archivo no fue cargado';
            $resultado['clase'] = 'alert alert-danger';
            $resultado['html'] = $this->upload->display_errors('<div role="alert" class="alert alert-danger">', '<div>');
        }
        
        return $resultado;
    }
    
    /**
     * Configuración para cargue de imágenes
     * @return boolean
     */
    function config_upload()
    {
        $config['upload_path'] = RUTA_UPLOADS . date('Y/m');
        $config['allowed_types'] = 'gif|jpg|png|jpeg|pdf|docx';
        $config['max_size']	= '5000';   //2MB
        $config['max_width']  = '5000';     //2000px
        $config['max_height']  = '5000';    //2000px
        $config['encrypt_name']  = TRUE;
        
        return $config;
    }
    
    /**
     * Crea los archivos miniaturas de una imagen
     * 
     * @param type $archivo_id
     */
    function crear_miniaturas($archivo_id)
    {
        $anchos = $this->anchos();
        $row_archivo = $this->Pcrn->registro_id('archivo', $archivo_id);
        
        foreach( $anchos as $ancho) {
            $this->crear_miniatura($row_archivo, $ancho);
        }
    }
    
    function mod_original_id($archivo_id)
    {
        $row_archivo = $this->Pcrn->registro_id('archivo', $archivo_id);
        $modificado = $this->mod_original($row_archivo->carpeta, $row_archivo->nombre_archivo);
        
        return $modificado;
    }
    
    /**
     * Modifica la imagen original con un tamaño específico máximo
     * 
     * @param type $row_archivo
     */
    function mod_original($carpeta, $nombre_archivo)
    {
        $modificado = 0;
        $image_size = getimagesize(RUTA_UPLOADS . $carpeta . $nombre_archivo);
        
        $ancho = 800;   //Tamaño 800px
        
        $cant_condiciones = 0;
        
        if ( $image_size[0] > $ancho ) { $cant_condiciones++; }
        if ( $image_size[1] > $ancho ) { $cant_condiciones++; }
        
        if ( $cant_condiciones > 0 ) {
            
            $modificado = 1;
            
            $this->load->library('image_lib');

            //Config
                $config['image_library'] = 'gd2';
                $config['source_image'] = RUTA_UPLOADS . $carpeta . $nombre_archivo;
                $config['maintain_ratio'] = TRUE;
                $config['width'] = $ancho;
                $config['height'] = $ancho;
                //$config['quality'] = 100;

                $this->image_lib->initialize($config);
                $this->image_lib->resize();
                $this->image_lib->clear();
        }
        
        return $modificado;
        
    }
    
    /**
     * Crea la miniatura de una imagen
     * 
     * @param type $row_archivo
     * @param type $ancho - valor en pixeles de la miniatura
     */
    function crear_miniatura($row_archivo, $ancho)
    {
        $this->load->library('image_lib');
        
        //Config
            $config['image_library'] = 'gd2';
            $config['source_image'] = RUTA_UPLOADS . $row_archivo->carpeta . $row_archivo->nombre_archivo;
            $config['new_image'] = RUTA_UPLOADS . $row_archivo->carpeta . $ancho . 'px_' . $row_archivo->nombre_archivo;;
            $config['maintain_ratio'] = TRUE;
            $config['width'] = $ancho;
            $config['height'] = $ancho;

            $this->image_lib->initialize($config);
            $this->image_lib->resize();
            $this->image_lib->clear();
    }
    
    /**
     * Array con los anchos en pixeles de miniaturas
     * 
     * @return int
     */
    function anchos()
    {
        $anchos = array(125, 250);
        return $anchos;
    }
    
    /**
     * Array con los prefijos de las imágenes
     * @return string
     */
    function prefijos()
    {
        $prefijos = array('');  //Primero, sin prefijo
        $anchos = $this->anchos();
        
        foreach( $anchos as $ancho ){
            $prefijos[] = $ancho . 'px_';
        }
        
        return $prefijos;
    }
    
//GESTIÓN DE ARCHIVOS EN CARPETAS
//---------------------------------------------------------------------------------------------------
    
    /**
     * Listado de archivos en una carpeta
     * 
     * @param type $anio
     * @param type $mes
     * @return type
     */
    function archivos($anio, $mes)
    {
        $this->load->helper('file');
        $archivos = get_filenames(RUTA_UPLOADS . $anio . '/' . $mes);
        
        return $archivos;
    }
    
    /**
     * Elimina los archivos que no están siendo utilizados en la herramienta
     * Se considera no usado si no tiene registro asociado en la tabla "archivo"
     * 
     * @param type $anio
     * @param type $mes
     * @return type
     */
    function unlink_no_usados($anio, $mes)
    {
        $cant_eliminados = 0;
        $this->load->helper('file');
        $archivos = get_filenames(RUTA_UPLOADS . $anio . '/' . $mes);
        
        $carpeta = "{$anio}/{$mes}/";
        
        foreach( $archivos as $nombre_archivo ){
            
            $sin_prefijo = $this->quitar_prefijo($nombre_archivo);
            $tiene_registro = $this->tiene_registro($carpeta, $sin_prefijo);
            
            if ( ! $tiene_registro ) { 
                $cant_eliminados += $this->unlink($carpeta, $sin_prefijo);
            }
        }
        
        return $cant_eliminados;
    }
    
    /**
     * Elimina un archivo y sus miniaturas
     * 
     * @param type $carpeta
     * @param type $nombre_archivo
     */
    function unlink($carpeta, $nombre_archivo)
    {
        $cant_eliminados = 0;
        $prefijos = $this->prefijos();  //Prefijos de miniaturas
        
        foreach( $prefijos as $prefijo )
        {
            $ruta = RUTA_UPLOADS . "{$carpeta}{$prefijo}{$nombre_archivo}";
            if (file_exists($ruta) ) { 
                unlink($ruta);
                $cant_eliminados++;
            }
        }
        
        return $cant_eliminados;
    }
    
    /**
     * Devuelve 1/0, verifica si un archivo tiene registro relacionado
     * en la tabla "archivo"
     * 
     * @param type $carpeta
     * @param type $nombre_archivo
     * @return int
     */
    function tiene_registro($carpeta, $nombre_archivo)
    {
        $tiene_registro = 0;
        
        $this->db->where('carpeta', $carpeta);
        $this->db->where('nombre_archivo', $nombre_archivo);
        $query = $this->db->get('archivo');
        
        if ( $query->num_rows() > 0 ) { $tiene_registro = 1; }
        
        $registro['texto_1'] = $this->db->last_query();
        $registro['entero_1'] = $query->num_rows();
        $this->db->insert('z_procesos', $registro);
        
        return $tiene_registro;
    }
    
    /**
     * Le quita el prefijo a un nombre de archivo
     * 
     * @param type $nombre_archivo
     * @return type
     */
    function quitar_prefijo($nombre_archivo)
    {
        $anchos = $this->anchos();
        $sin_prefijo = $nombre_archivo;
        
        foreach ( $anchos as $ancho ) {
            $prefijo = $ancho . 'px_';
            $sin_prefijo = str_replace($prefijo, '', $sin_prefijo);
        }
        
        return $sin_prefijo;
    }
}