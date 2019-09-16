<?php
class Institucion_model extends CI_Model{
    
    /**
     * Crea los valores de unas variables para el array $data
     * que serán utilizadas por varias funciones del controlador,
     * son variables básicas sobre un institucion
     *
     * @param type $institucion_id
     * @return string
     */
    function basico($institucion_id)
    {   
        $row = $this->Institucion_model->datos_institucion($institucion_id);
        
        $basico['grupos'] = $this->Institucion_model->grupos($institucion_id);
        $basico['row'] = $row;
        $basico['titulo_pagina'] = $row->nombre_institucion;
        //$basico['titulo_pagina'] = $institucion_id;
        $basico['vista_a'] = 'instituciones/institucion_v';
        
        return $basico;
    }
    
    function buscar($busqueda, $per_page = NULL, $offset = NULL)
    {   
        
        $filtro_rol = $this->Busqueda_model->filtro_instituciones();
        
        //Texto búsqueda
            //Crear array con términos de búsqueda
            if ( strlen($busqueda['q']) > 2 ){
                $palabras = $this->Busqueda_model->palabras($busqueda['q']);

                foreach ($palabras as $palabra_busqueda) {
                    $this->db->like('CONCAT(nombre_institucion)', $palabra_busqueda);
                }
            }

        //Otros
            if ( $busqueda['o'] != '' ) { $this->db->order_by($busqueda['O'], 'ASC'); }    //ORDEN
            
        //Complemento función
            $this->db->where($filtro_rol); //Filtro según el rol de usuario que se tenga
            $this->db->order_by('nombre_institucion', 'ASC');
            
        //Obtener resultados
        if ( is_null($per_page) )
        {
            $query = $this->db->get('institucion'); //Resultados totales
        } else {
            $query = $this->db->get('institucion', $per_page, $offset); //Resultados por página
        }
        
        return $query;
    }
    
    
//GROCERY CRUD DE INSTITUCIONES
//---------------------------------------------------------------------------------------------------
    
    function crud_editar()
    {
        //Grocery crud
        $this->load->library('grocery_CRUD');
        
        $crud = new grocery_CRUD();
        $crud->set_table('institucion');
        $crud->set_subject('institucion');
        $crud->unset_export();
        $crud->unset_print();
        $crud->unset_back_to_list();
        $crud->unset_delete();
        $crud->unset_read();
        
        //Filtro
            $crud->where('institucion.id', 0);
        
        //Títulos de los campos
            $crud->display_as('direccion','Dirección');
            $crud->display_as('telefono','Teléfono');
            $crud->display_as('pagina_web','Página web');
            $crud->display_as('nombre_institucion','Nombre');
            $crud->display_as('lugar_id', 'Ciudad');
            $crud->display_as('ejecutivo_id', 'Ejecutivo asignado');
            $crud->display_as('cat_1', 'Activar Impresión Evaluator');
            $crud->display_as('vencimiento_cartera', 'Vencimiento de cartera');
        
        //Relaciones
            $crud->set_relation('ejecutivo_id', 'usuario', '{apellidos} {nombre}', 'rol_id IN (0, 1, 2, 8)');
            $crud->set_relation('lugar_id', 'lugar', '{nombre_lugar}, {region}', 'pais_id = 51 AND tipo_id = 4');
            $crud->set_relation('lugar_id', 'lugar', '{nombre_lugar}, {region}', 'pais_id = 51 AND tipo_id = 4');

        //Formulario Edit
            $crud->edit_fields('nombre_institucion', 'lugar_id', 'direccion', 'telefono', 'pagina_web', 'ejecutivo_id', 'cat_1', 'vencimiento_cartera', 'acumulador', 'email', 'notas');

        //Formulario Add
            $crud->add_fields('nombre_institucion', 'lugar_id', 'direccion', 'telefono', 'pagina_web', 'ejecutivo_id', 'cat_1', 'vencimiento_cartera', 'acumulador', 'email', 'notas');
            
        //Funciones
            /*$crud->callback_after_update(array($this, 'gc_after_save'));
            $crud->callback_after_insert(array($this, 'gc_after_save'));*/

            $crud->field_type('cat_1','dropdown',array('1' => 'Sí', '2' => 'No'));

        //Reglas de validación
            $crud->required_fields('nombre_institucion', 'area_id', 'nivel', 'acumulador');
            
        //Valores por defecto
            $crud->field_type('editado', 'hidden', date('Y-m-d H:i:s'));
        
        //Formato
            $crud->unset_texteditor('descripcion');
        
        $output = $crud->render();
        
        return $output;
        
    }
    
//---------------------------------------------------------------------------------------------------

    
    function datos_institucion($institucion_id){
        
        //Devuelve un objeto de registro con los datos del institucion
        
        $this->db->where('id', $institucion_id);
        $query = $this->db->get('institucion');
        
        if( $query->num_rows() > 0 ){
            $row = $query->row();
            
            //Calcular estudiantes registrados
            $this->db->where('grupo.institucion_id', $institucion_id);
            $this->db->join('grupo', 'usuario.grupo_id = grupo.id');
            $query_uc = $this->db->get('usuario');
            $row->num_estudiantes = $query_uc->num_rows();
            
            //Nombre lugar de ubicación
            $row->lugar_nombre = $this->App_model->nombre_lugar($row->lugar_id, 1);
            
            $datos_institucion = $row;
        }
        
        return $datos_institucion;
    }
    
    function usuarios($institucion_id)
    {
        $this->db->select('id, id AS usuario_id');
        $this->db->where('institucion_id', $institucion_id);
        $this->db->where('rol_id IN (3, 4, 5)');
        $usuarios = $this->db->get('usuario');
        
        return $usuarios;
    }
    
    /**
     * Inserta masivamente estudiantes
     * tabla usuario
     * 
     * @param type $array_hoja    Array con los datos de los estudiantes
     */
    function importar_estudiantes($array_hoja, $institucion_id)
    {
        $this->load->model('Esp');
        $this->load->model('Usuario_model');
        $this->load->model('Grupo_model');
        
        $no_importados = array();
        $importados = array();
        $fila = 2;  //Inicia en la fila 2 de la hoja de cálculo
        
        $sexos_cod = $this->Esp->arr_sexos_cod();
        $dpw = $this->App_model->valor_opcion(10);  //Default PassWord, Contraseña por defecto
            
        //Predeterminados registro nuevo
            $registro['rol_id'] = 6;    //Estudiante
            $registro['cpw'] = 1;   //Nueva encriptación de contraseña
            $registro['estado'] = 1;    //Mod. 2019-01-14
            $registro['institucion_id'] = $institucion_id;
            $registro['creado'] = date('Y-m-d h:i:s');
            $registro['editado'] = date('Y-m-d h:i:s');
            $registro['creado_usuario_id'] = $this->session->userdata('usuario_id');
            $registro['editado_usuario_id'] = $this->session->userdata('usuario_id');
        
        foreach ( $array_hoja as $array_fila )
        {
            //Identificar valores
                $sexo = NULL;
                if ( array_key_exists(strtoupper($array_fila[4]), $sexos_cod) ) { $sexo = $sexos_cod[$array_fila[4]]; }
                $grupo_id = 0;
                if ( $this->Pcrn->campo_id('grupo', $array_fila[5], 'id') ) { $grupo_id = $array_fila[5]; }
                $username_alt = $this->Usuario_model->generar_username($array_fila[0], $array_fila[1]);
            
            //Complementar registro
                $registro['nombre'] = $array_fila[0];
                $registro['apellidos'] = $array_fila[1];
                $registro['no_documento'] = $array_fila[2];
                $registro['email'] = $this->Esp->validar_email($array_fila[3]);
                $registro['password'] = $this->Usuario_model->encriptar_pw($dpw);
                $registro['sexo'] = $sexo;
                $registro['username'] = $this->Pcrn->si_strlen($array_fila[6], $username_alt);
                $registro['grupo_id'] = $grupo_id;
                
            //Validar
                $condiciones = 0;
                if ( strlen($array_fila[0]) > 0 ) { $condiciones++; }   //Debe tener nombre escrito
                if ( strlen($array_fila[1]) > 0 ) { $condiciones++; }   //Debe tener apellido escrito
                if ( $grupo_id != 0 ) { $condiciones++; }               //ID de grupo existente
                
            //Si cumple las condiciones
            if ( $condiciones == 3 )
            {   
                //Insertar en la tabla usuario
                    $nuevo_usuario_id = $this->Pcrn->guardar('usuario', "username = '{$registro['username']}'", $registro);
                
                //Insertar registro en la tabla 'usuario_grupo'
                    $registro_ug['grupo_id'] = $grupo_id;   //Para tabla usuario_grupo
                    $registro_ug['usuario_id'] = $nuevo_usuario_id;
                    $this->Grupo_model->insertar_ug($registro_ug);
                    
                $importados[] = $nuevo_usuario_id;
            } else {
                $no_importados[] = $fila;
            }
            
            $fila++;    //Para siguiente fila
        }
        
        $res_importacion['no_importados'] = $no_importados;
        $res_importacion['importados'] = $importados;
        
        return $res_importacion;
    }
    
    /**
     * Inserta masivamente usuarios a una institución ()
     * tabla usuario
     * 
     * @param type $array_hoja    Array con los datos de los usuarios
     */
    function importar_usuarios($array_hoja, $institucion_id)
    {   
        $this->load->model('Esp');
        $this->load->model('Usuario_model');
        
        $no_importados = array();
        $importados = array();
        $fila = 2;  //Inicia en la fila 2 de la hoja de cálculo
        
        $sexos_cod = $this->Esp->arr_sexos_cod();
        $roles_cod = $this->Esp->arr_roles_cod();
        $dpw = $this->App_model->valor_opcion(10);  //Default PassWord, Contraseña por defecto
            
        //Predeterminados registro nuevo
            $registro['cpw'] = 1;   //Nueva encriptación de contraseña
            $registro['estado'] = 1;    //Mod. 2019-01-14
            $registro['institucion_id'] = $institucion_id;
            $registro['creado'] = date('Y-m-d h:i:s');
            $registro['editado'] = date('Y-m-d h:i:s');
            $registro['creado_usuario_id'] = $this->session->userdata('usuario_id');
            $registro['editado_usuario_id'] = $this->session->userdata('usuario_id');
        
        foreach ( $array_hoja as $array_fila )
        {
            //Identificar valores
                $sexo = NULL;
                if ( array_key_exists(strtoupper($array_fila[4]), $sexos_cod) ) { $sexo = $sexos_cod[$array_fila[4]]; }
                $rol_id = NULL;
                if ( array_key_exists(strtoupper($array_fila[5]), $roles_cod) ) { $rol_id = $roles_cod[$array_fila[5]]; }
                $username_alt = $this->Usuario_model->generar_username($array_fila[0], $array_fila[1]);
            
            //Complementar registro
                $registro['nombre'] = $array_fila[0];
                $registro['apellidos'] = $array_fila[1];
                $registro['no_documento'] = $array_fila[2];
                $registro['email'] = $this->Esp->validar_email($array_fila[3]);
                $registro['password'] = $this->Usuario_model->encriptar_pw($dpw);
                $registro['sexo'] = $sexo;
                $registro['username'] = $this->Pcrn->si_strlen($array_fila[6], $username_alt);
                $registro['rol_id'] = $rol_id;
                
            //Validar
                $condiciones = 0;
                if ( strlen($array_fila[0]) > 0 ) { $condiciones++; }   //Debe tener nombre escrito
                if ( strlen($array_fila[1]) > 0 ) { $condiciones++; }   //Debe tener apellido escrito
                if ( ! is_null($rol_id) ) { $condiciones++; }           //ID de rol identificado
                
            //Si cumple las condiciones
            if ( $condiciones == 3 )
            {   
                //Insertar en la tabla usuario
                $importados[] = $this->Pcrn->guardar('usuario', "username = '{$registro['username']}'", $registro);
            } else {
                $no_importados[] = $fila;
            }
            
            $fila++;    //Para siguiente fila
        }
        
        $res_importacion['no_importados'] = $no_importados;
        $res_importacion['importados'] = $importados;
        
        return $res_importacion;
    }
    
    /**
     * Devuelve un query con los grupos que pertenecen a una instituciÓn
     * 
     * @param type $institucion_id
     * @return boolean 
     */
    
    function grupos($institucion_id, $anio_generacion = NULL)
    {
        
        //Filtro si el usuario es un profesor, rol_id = 5
            if ( $this->session->userdata('rol_id') == 5 ){
                $this->load->model('Grupo_model');
                $grupos_profesor = $this->Grupo_model->grupos_profesor($this->session->userdata('usuario_id'), 'string');
                $condicion = "id IN ({$grupos_profesor})";
            }
        
        if ( ! is_null($anio_generacion) ) { $this->db->where('anio_generacion', $anio_generacion); }
        if ( $this->session->userdata('rol_id') == 5 ) { $this->db->where($condicion); }
        
        $this->db->where('institucion_id', $institucion_id);
        $this->db->order_by('anio_generacion', 'DESC');
        $this->db->order_by('nivel', 'ASC');
        $this->db->order_by('grupo', 'ASC');
        
        $grupos = $this->db->get('grupo');
        
        return $grupos;
    }
    
    /**
     * Guarda masivamente grupos, tabla grupo
     * 
     * @param type $array_hoja    Array con los datos de los grupos
     * @return type
     */
    function importar_grupos($array_hoja, $institucion_id)
    {       
        $this->load->model('Esp');
        $this->load->model('Grupo_model');
        
        $no_importados = array();
        $fila = 2;  //Inicia en la fila 2 de la hoja de cálculo
        
        $registro['anio_generacion'] = date('Y');
        $registro['institucion_id'] = $institucion_id;
        
        foreach ( $array_hoja as $array_fila )
        {
            $registro['nivel'] = $array_fila[0]; //Columna A
            $registro['grupo'] = $array_fila[1]; //Columna B
            $registro['nombre_grupo'] = $this->Grupo_model->generar_nombre($registro['nivel'], $registro['grupo']);

            //Condicion de verificación, grupo único
            $condicion = "nivel = {$registro['nivel']} AND ";
            $condicion .= "grupo = '{$registro['grupo']}' AND ";
            $condicion .= "institucion_id = {$registro['institucion_id']} AND ";
            $condicion .= "anio_generacion = {$registro['anio_generacion']}";

            $grupo_id = $this->Pcrn->existe('grupo', $condicion);
                
            //Validar
                $condiciones = 0;
                if ( $grupo_id == 0 ) { $condiciones++; }                   //El grupo no existe
                if ( strlen($array_fila[0]) >= 0 ) { $condiciones++; }      //Debe tener nivel
                if ( strlen($array_fila[1]) >= 0 ) { $condiciones++; }      //Debe tener nivel
                
            //Si cumple las condiciones
            if ( $condiciones == 3 )
            {
                $this->Pcrn->guardar('grupo', 'id = 0', $registro);
            } else {
                $no_importados[] = $fila;
            }
            
            $fila++;    //Para siguiente fila
        }
        
        $res_importacion['no_importados'] = $no_importados;
        
        return $res_importacion;
    }
    
    /**
     * Inserta masivamente grupos
     * tabla grupo
     * 
     * @param type $grupos    Array con los datos de los grupos
     */
    function z_cargar_grupos($institucion_id, $grupos)
    {   
        //Básico
            $this->load->model('Esp');
        
        $cargados = array();
        $no_cargados = array();
        
        $registro['anio_generacion'] = $this->input->post('anio_generacion');
        $registro['institucion_id'] = $institucion_id;
        
        foreach ( $grupos as $row_grupo ) {
            
            //identificar tema_id
            $cargado = 0;
            if ( ! is_null($row_grupo[0]) ){
                $registro['nivel'] = $row_grupo[0]; //Columna A
                $registro['grupo'] = $row_grupo[1]; //Columna B
                
                //Condicion de verificación, grupo único
                $condicion = "nivel = {$registro['nivel']} AND ";
                $condicion .= "grupo = '{$registro['grupo']}' AND ";
                $condicion .= "institucion_id = {$registro['institucion_id']} AND ";
                $condicion .= "anio_generacion = {$registro['anio_generacion']}";
                
                $grupo_id = $this->Pcrn->guardar('grupo', $condicion, $registro);
            }
            
            if ( $grupo_id > 0 ) { $cargado = 1; }
            
            //Cargar según resultado
            if ( $cargado ) { 
                $cargados[] = $grupo_id; 
            } else {
                $no_cargados[] = $row_grupo[0] .  ' - ' . $row_grupo[1];
            }
            
        }
        
        $resultado['cargados'] = $cargados;
        $resultado['no_cargados'] = $no_cargados;
        
        return $resultado;
    }
    
    function asignar_profesores($array_excel)
    {
        $resultado_cargue = array();
        $cargados = array();
        
        //Referencia
        
        foreach ( $array_excel as $row_profesor ) {
            
            $grupo_id = $this->Pcrn->campo_id('grupo', $row_profesor[0], 'id');
            $profesor_id = $this->Pcrn->campo_id('usuario', $row_profesor[1], 'id');
            $area_id = $this->Pcrn->campo('item', "id = $row_profesor[2] AND categoria_id = 1", 'id');  //Categoria 1, corresponde a áreas
            
            $condiciones = 0;
            if ( ! is_null($grupo_id) ) { $condiciones += 1; }      //Condición 1, El grupo existe
            if ( ! is_null($profesor_id) ) { $condiciones += 1; }   //Condición 2, El profesor existe
            if ( ! is_null($area_id) ) { $condiciones += 1; }       //Condición 3, El área existe
            
            //Se agrega si cumple todas las condiciones
            if ( $condiciones == 3 ){
                
                //Preparación de registro
                    $registro['grupo_id'] = $grupo_id;
                    $registro['profesor_id'] = $profesor_id;
                    $registro['area_id'] = $area_id;

                //Insertar en la tabla grupo_profesor
                    $condicion = "grupo_id = {$registro['grupo_id']} AND profesor_id = {$registro['profesor_id']} AND area_id = {$registro['area_id']}";
                    $cargados[] = $this->Pcrn->guardar('grupo_profesor', $condicion, $registro);
            }
        }
        
        $resultado_cargue['cargados'] = $cargados;
        
        return $resultado_cargue;
    }
    
    /**
     * Cuestionarios asociados a una institución
     * 
     * @param type $institucion_id
     * @return type
     */
    function cuestionarios($institucion_id, $filtros = NULL)
    {
        
        //Construyendo consulta       
        $this->db->select('cuestionario.id, nombre_cuestionario, area_id, nivel');
        $this->db->where('usuario_cuestionario.institucion_id', $institucion_id);
        if ( ! is_null($filtros) ) { $this->db->where($filtros); }
        $this->db->join('usuario_cuestionario', 'cuestionario.id = usuario_cuestionario.cuestionario_id');
        $this->db->group_by('cuestionario.id, nombre_cuestionario, area_id, nivel');
        $query = $this->db->get('cuestionario');
        
        return $query;
    }
    
    /**
     * Devuelve query con los flipbooks asociados a una institución
     * 
     * @param type $institucion_id
     * @return type 
     */
    function flipbooks($institucion_id)
    {
        $this->db->select('flipbook_id, taller_id');
        $this->db->where('institucion_id', $institucion_id);
        $this->db->where('tipo_flipbook_id IN (0,3)');    //Flipbook estudiantes
        $this->db->group_by('flipbook_id, taller_id');
        $this->db->join('usuario', 'usuario.id = usuario_flipbook.usuario_id');
        $this->db->join('flipbook', 'flipbook.id = usuario_flipbook.flipbook_id');
        $this->db->order_by('area_id', 'ASC');
        $this->db->order_by('nivel', 'ASC');
        $query = $this->db->get('usuario_flipbook');
        
        return $query;
    }
    
    /**
     * Elimina las asignaciones de flipbook a todos los usuarios de una institución
     * 
     * @param type $institucion_id
     * @param type $flipbook_id
     * @return type
     */
    function quitar_flipbook($institucion_id, $flipbook_id)
    {
        $this->db->where("usuario_id IN (SELECT id FROM usuario WHERE institucion_id = {$institucion_id})");
        $this->db->where('flipbook_id', $flipbook_id);
        $this->db->delete('usuario_flipbook');
        
        return $this->db->affected_rows();
    }
    
    /**
     * Elimina una institución
     * 
     * @param type $institucion_id
     */
    function eliminar($institucion_id)
    {
        //Verificar que es administrador
        if ( $this->session->userdata('rol_id') <= 1 )
        {
            //Tabla institucion
                $this->db->where('id', $institucion_id);
                $this->db->delete('institucion');

            //Tablas relacionadas
                $tablas = array('grupo', 'usuario', 'usuario_cuestionario', 'dw_usuario_pregunta', 'evento');
                foreach ( $tablas as $tabla ) {
                    $this->db->where('institucion_id', $institucion_id);
                    $this->db->delete($tabla);
                }
        }
    }
    
    function actualizar($institucion_id, $data){
        $this->db->where('id', $institucion_id);
        $this->db->update('institucion', $data);
    }
    
    function verificar_login($username, $password){
        
        //Verificar si la combinación de username y password existe en un mismo registro
        
        $this->db->where('username', $username);
        $this->db->where('password', $password);
        
        $query = $this->db->get('institucion');
        
        if( $query->num_rows() > 0 ){
            return $query->row();
        } else {
            return FALSE;
        }
    }
    
    function cambiar_contrasena($institucion_id, $password){
        $data = array(
            'password'  => $password
        );
        $this->db->where('id', $institucion_id);
        $action = $this->db->update('institucion', $data);
        return $action;
    }
    
    function agregar_cuestionario($institucion_id, $cuestionario_id){
        
        $permiso = TRUE;
        
        //Verificar si los valor de id existen
        
            //Si los registros no existen, las variables serán igual a NULL
            $row_institucion = $this->Pcrn->registro('institucion', "id = {$institucion_id}");
            $row_cuestionario = $this->Pcrn->registro('cuestionario', "id = {$cuestionario_id}");


            if ( is_null($row_institucion) ){$permiso = FALSE;}
            if ( is_null($row_cuestionario) ){$permiso = FALSE;}
            
        //Si el el permiso sigue siendo afirmativo se inserta el registro
            
            if ( $permiso ){
                $data = array(
                    'institucion_id'  => $institucion_id,
                    'cuestionario_id'      => $cuestionario_id,
                );
                $id_accion = $this->db->insert('institucion_cuestionario', $data);
            } else {
                $id_accion = 0;
            }
            
        //Devolver resultado de la acción
            return $id_accion;
            
            
    }
    
    function quitar_cuestionario($uc_id){
        $this->db->where("id = {$uc_id}");
        $this->db->delete('institucion_cuestionario');
    }

//GESTIÓN DE CUESTIONARIOS
//---------------------------------------------------------------------------------------------------

    
    function resultados_grupo($institucion_id, $cuestionario_id){
        
        $this->db->select('institucion_id, cuestionario_id, grupo_id, count(usuario_pregunta.id) AS num_correctas');
        $this->db->join('usuario', 'usuario.id = usuario_pregunta.usuario_id');
        $this->db->group_by('institucion_id, cuestionario_id, grupo_id, resultado');
        $this->db->having("institucion_id = {$institucion_id} AND cuestionario_id = {$cuestionario_id} AND resultado=True");
        
        $query = $this->db->get('usuario_pregunta');
        
        return $query;
    }
    
    function resultados_lista($grupo_id, $cuestionario_id){
        
        $this->db->select('usuario_pregunta.usuario_id, Count(usuario_pregunta.id) AS correctas');
        $this->db->join('usuario_cuestionario', 'usuario_pregunta.usuario_id = usuario_cuestionario.usuario_id');
        $this->db->where("grupo_id = {$grupo_id} AND usuario_pregunta.cuestionario_id = {$cuestionario_id} AND resultado = TRUE AND usuario_pregunta.cuestionario_id = usuario_cuestionario.cuestionario_id");  //Modificado 2013-09-20, evitando duplicados
        $this->db->group_by('usuario_pregunta.usuario_id');
        $this->db->order_by('Count(usuario_pregunta.id)', 'DESC');
        
        return $this->db->get('usuario_pregunta');
        
    }
    
    /*
     * Devuelve un query con los grupos de una institución con 
     * estudiantes que están asignados a un determinado cuestionario
     */
    function grupos_cuestionario($institucion_id, $cuestionario_id)
    {   
        $this->db->select('grupo_id');
        $this->db->where('institucion_id', $institucion_id);
        $this->db->where('cuestionario_id', $cuestionario_id);
        $this->db->group_by('grupo_id');
        $query = $this->db->get('usuario_cuestionario');
        
        return $query;
    }
    
    /* Devuelve un query con los id de cuestionarios e id de grupos que están relacionados con una
     * institución
     */
    function cuestionarios_grupos($institucion_id, $anio_generacion = NULL){
        
        //Filtrar grupos por profesor
        if ( $this->session->userdata('rol_id') == 5 ){
            $grupos_profesor = $this->Grupo_model->grupos_profesor($this->session->userdata('usuario_id'), 'string');
            $this->db->where("grupo.id IN ({$grupos_profesor})");
        }
        
        $this->db->select('cuestionario_id, usuario_cuestionario.grupo_id');
        $this->db->join('cuestionario', 'usuario_cuestionario.cuestionario_id = cuestionario.id');
        $this->db->where('usuario_cuestionario.institucion_id', $institucion_id);
        if ( ! is_null($anio_generacion) ) { $this->db->where('cuestionario.anio_generacion', $anio_generacion); }
        $this->db->group_by('cuestionario_id, grupo_id');
        $this->db->order_by('cuestionario_id');
        
        $query = $this->db->get('usuario_cuestionario');
        
        return $query;
    }
    
//CUESTIONARIOS - ACUMULADOR > usuario_pregunta.acumulador
//---------------------------------------------------------------------------------------------------
    
    function query_acumulador($institucion_id)
    {
        $this->db->select('usuario_pregunta.id, usuario_pregunta.usuario_id, fin_respuesta, competencia_id, consecutivo, acumulador');
        $this->db->join('usuario_cuestionario', 'usuario_pregunta.usuario_id = usuario_cuestionario.usuario_id AND usuario_pregunta.cuestionario_id = usuario_cuestionario.cuestionario_id');
        $this->db->join('pregunta', 'usuario_pregunta.pregunta_id = pregunta.id');
        $this->db->join('cuestionario', 'cuestionario.id = usuario_pregunta.cuestionario_id AND cuestionario.id = usuario_cuestionario.cuestionario_id');
        $this->db->where('usuario_cuestionario.institucion_id', $institucion_id);
        $this->db->where('pregunta.competencia_id IS NOT NULL');
        $this->db->where('cuestionario.tipo_id > 1');   //No es prueba diagnóstica, 2015-09-16
        $this->db->order_by('usuario_pregunta.usuario_id');
        $this->db->order_by('competencia_id', 'ASC');
        $this->db->order_by('consecutivo', 'ASC');
        $this->db->order_by('fin_respuesta', 'ASC');
        $query = $this->db->get('usuario_pregunta');
        
        return $query;
    }
    
    function limpiar_acumuladores($institucion_id)
    {   
        $registro['consecutivo'] = 0;
        $registro['acumulador'] = 0;
        $registro['acumulador_2'] = NULL;
        
        $this->db->where("usuario_id IN (SELECT id FROM usuario WHERE institucion_id = {$institucion_id})");
        $this->db->update('usuario_pregunta', $registro);
    }
    
    function actualizar_consecutivo($institucion_id)
    {
        $this->limpiar_acumuladores($institucion_id);
        $query = $this->query_acumulador($institucion_id);
        
        $contador = 0;
        $usuario_id = 0;
        $competencia_id = 0;
        
        
        foreach ( $query->result() as $row_up ) {
            
            //Verificar para reiniciar contador
            if ( $usuario_id != $row_up->usuario_id ) { $contador = 0; }
            if ( $competencia_id != $row_up->competencia_id ) { $contador = 0; }
            
            $contador++;
            
            $registro['consecutivo'] = $contador;
            $this->db->where('id', $row_up->id);
            $this->db->update('usuario_pregunta', $registro);
            
            //Para siguiente ciclo
            $usuario_id = $row_up->usuario_id;
            $competencia_id = $row_up->competencia_id;
            
        }
        
        return $query->num_rows();
    }
    
    function actualizar_acumulador($institucion_id)
    {
        $factor_acumulador = $this->Pcrn->campo_id('institucion', $institucion_id, 'acumulador');   
        $sql = 'UPDATE usuario_pregunta SET acumulador = CEIL(consecutivo/' . $factor_acumulador . ') WHERE usuario_id IN (SELECT id FROM usuario WHERE institucion_id = ' . $institucion_id . ');';
        $this->db->query($sql);
    }
    
    /**
     * Actualizar el campo usuario_pregunta.acumulador_2
     * @param type $institucion_id
     */
    function actualizar_acumulador_2($institucion_id)
    {
        $sql = 'UPDATE usuario_pregunta ';
        $sql .= 'JOIN cuestionario ON usuario_pregunta.cuestionario_id = cuestionario.id ';
        $sql .= 'SET acumulador_2 = IF(cuestionario.tipo_id < 2, cuestionario.nombre_cuestionario, acumulador) ';
        $sql .= 'WHERE usuario_id IN (SELECT id FROM usuario WHERE institucion_id = ' . $institucion_id . ');';
        
        $this->db->query($sql);
    }
    
    //ACTUALIZADA 2018-11-16
    function desactivar_morosos($institucion_id = NULL)
    {
        
        $cant_reg = 'Cero';
        
        $sql = "UPDATE usuario ";
        $sql .= 'JOIN institucion ON usuario.institucion_id = institucion.id ';
        $sql .= 'SET estado = 0 ';
        $sql .= 'WHERE ';
        $sql .= 'DATE_FORMAT(NOW(),"%Y-%m-%d 00:00:00") > institucion.vencimiento_cartera ';
        $sql .= 'AND rol_id = 6 ';  //Es estudiante
        $sql .= 'AND pago = 0 ';
        if ( ! is_null($institucion_id) ) {$sql .= "AND institucion_id = {$institucion_id}";}
        //
        $sql .= ';';
        
        $this->db->query($sql);
        
        
        if ( $this->db->affected_rows() > 0 ){
            $cant_reg = $this->db->affected_rows();
        }
        
        return $cant_reg;
    }
    
//ESTADÍSTICAS
//---------------------------------------------------------------------------------------------------
    
    /**
     * Cantidad de eventos login
     * 
     * @param type $institucion_id
     * @param type $condicion
     * @return type
     */
    function cant_login($institucion_id, $condicion = NULL)
    {
        
        $this->db->where('evento.institucion_id', $institucion_id);
        $this->db->where('tipo_id', 101);  //Login
        $this->db->where('usuario.rol_id', 6);  //Estudiantes
        $this->db->join('usuario', 'usuario.id = evento.usuario_id');
        if ( ! is_null($condicion) ) { $this->db->where($condicion); }
        $query = $this->db->get('evento');
        
        return $query->num_rows();
    }
    
    function cant_estudiantes($institucion_id, $condicion = NULL)
    {
        
        $this->db->where('institucion_id', $institucion_id);
        $this->db->where('rol_id', 6);  //Estudiantes
        if ( ! is_null($condicion) ) { $this->db->where($condicion); }
        
        $query = $this->db->get('usuario');
        
        return $query->num_rows();
    }
    
}
