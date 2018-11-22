<?php
class Busqueda_model extends CI_Model{
    
    function basico()
    {
        $basico['titulo_pagina'] = 'Búsquedas';
        $basico['vista_a'] = 'busquedas/busquedas_v';
        
        return $basico;
    }
    
    /**
     * Array con las palabras del texto de una búsqueda
     * 
     * @param type $q   //Texto buscado
     * @return type
     */
    function palabras($q)
    {
        
        $palabras = array();
        
        if ( strlen($q) > 2 ){
            
            $no_buscar = array(
                'la',
                'el',
                'los',
                'las',
                'del',
                'de',
                'y',
            );

            $palabras = explode(' ', $q);

            foreach ($palabras as $key => $palabra){
                if (in_array($palabra, $no_buscar) ){
                    unset($palabras[$key]);
                }
            }
        }
        
        return $palabras;
    }
    
    /**
     * Nombres de los índices del array de búsqueda
     * @return string
     */
    function arr_indices()
    {
        $arr_indices = array(
            'q',        //Texto búsqueda
            'cat',      //Categoría
            'u',        //Usuario
            'list',     //Lista
            'tp',       //Tipo
            'o',        //Order by
            'ot',       //Order by tipo
            'rol',      //Rol de usuario
            'e',        //Editado, fecha de edición
            'est',      //Estado
            'a',        //Área 
            'n',        //Nivel escolar, grado escolar
            'i',        //Institución
            'g',        //Grupo
            'y',        //Year, año generación
            'fi',       //Fecha inicial
            'ff',       //Fecha final
            'fa',       //Fecha hacia atrás
            'ctn',      //Cuestionario
            'cptc',     //Competencia
            'cpnt',     //Componente
            'f1',       //Filtro 1
            'f2',       //Filtro 2
            'f3',       //Filtro 3
            'condicion' //Condición SQL Where adicional
        );
        
        return $arr_indices;
    }
    
    /**
     * Array de búsqueda con valor NULL para todos los índices
     * Valor inicial antes de evaluar contenido de POST y GET
     * @return null
     */
    function busqueda_array_inicial()
    {
        $arr_indices = $this->arr_indices();
        
        foreach ($arr_indices as $indice) { $busqueda[$indice] = NULL; }

        return $busqueda;
    }
    
    /**
     * Array con los parámetros de una búsqueda, respuesta para los dos métodos
     * de solicitud POST y GET.
     * 
     * @return type
     */
    function busqueda_array()
    {
        $busqueda = $this->busqueda_array_inicial();
        $arr_indices = $this->arr_indices();
        
        if ( $this->input->post() )
        {
            //Búsqueda por formulario
            foreach ($arr_indices as $indice) {
                $busqueda[$indice] = $this->input->post($indice);
            }

            $busqueda['q_uri'] = $this->Pcrn->texto_uri($busqueda['q'], TRUE);
        } elseif ( $this->input->get() ){
            //Se ha hecho una consulta, por get

            //Búsqueda por formulario
            foreach ($arr_indices as $indice) {
                $busqueda[$indice] = $this->input->get($indice);
            }

            $busqueda['q_uri'] = $this->input->get('q');
        }
        
        //Ajuste de filtros según el rol
        if ( $this->session->userdata('srol') != 'interno' ) {
            $busqueda['i'] = $this->session->userdata('institucion_id');
        }
            
        return $busqueda;
    }
    
    /**
     * String con la cadena para URL tipo GET, con los valores de la búsqueda
     * @return type
     */
    function busqueda_str()
    {
        $busqueda = $this->busqueda_array();
        $arr_indices = $this->arr_indices();
        $busqueda_str = '';
        
        foreach ( $arr_indices as $indice ) {
            if ( $busqueda[$indice] != '' ) { $busqueda_str .= "{$indice}={$busqueda[$indice]}&"; }
        }
        
        return $busqueda_str;
    }
    
    /**
     * Devuelve string con segmento SQL de campos con el condicional para concatenar
     * 
     * @param type $campos
     * @return type
     */
    function concat_campos($campos)
    {
        $concat_campos = '';
        
        foreach ( $campos as $campo ) {
            $concat_campos .= "IFNULL({$campo}, ''), ";
        }
        
        return substr($concat_campos, 0, -2);
    }
    
    function opciones_fecha_atras()
    {
        $rangos_dias = array(
            '' => '[ Total ]',
            '-6 day' => 'Últimos 7 días',   //6, para incluír el día de hoy
            '-4 week' => 'Últimos 28 días',
            '-3 month' => 'Último trimestre',
            '-1 year' => 'Último año'
        );
        
        return $rangos_dias;
    }
    
// Búsquedas de elementos
//-----------------------------------------------------------------------------
    
    /**
     * Búsqueda de conversaciones
     * 
     * @param type $busqueda
     * @param type $per_page
     * @param type $offset
     * @return type
     */
    function conversaciones($busqueda, $per_page = NULL, $offset = NULL)
    {

        //Construir búsqueda
            $this->db->select('conversacion.*, usuario.*, conversacion.id AS conversacion_id');
        
        //Texto búsqueda
            //Crear array con términos de búsqueda
            if ( strlen($busqueda['q']) > 2 ){
                $palabras = $this->palabras($busqueda['q']);

                foreach ($palabras as $palabra_busqueda) {
                    $this->db->like('CONCAT(asunto)', $palabra_busqueda);
                }
            }

        //Conversaciones a las que está asignado
            $this->db->where("conversacion.id IN (SELECT referente_id FROM usuario_asignacion WHERE usuario_id = {$this->session->userdata('usuario_id')} AND tipo_asignacion_id = 5)"); //Conversaciones a las que está asignado

        //Otros
            $this->db->where('asunto IS NOT NULL'); //No mostrar mensajes sin asunto
            $this->db->order_by('conversacion.editado', 'DESC');
                
        //Tablas relacionadas
            $this->db->join('usuario', 'conversacion.usuario_id = usuario.id');
            
        //Obtener resultados
        if ( is_null($per_page) ){
            $query = $this->db->get('conversacion'); //Resultados totales
        } else {
            $query = $this->db->get('conversacion', $per_page, $offset); //Resultados por página
        }
        
        return $query;
    }
    
    /**
     * Búsqueda de enunciados
     * 
     * @param type $busqueda
     * @param type $per_page
     * @param type $offset
     * @return type
     */
    function enunciados($busqueda, $per_page = NULL, $offset = NULL)
    {

        //Filtro según el rol de usuario que se tenga
            //$filtro_rol = $this->filtro_enunciados();
        
        //Texto búsqueda
            //Crear array con términos de búsqueda
            if ( strlen($busqueda['q']) > 2 ){
                $palabras = $this->palabras($busqueda['q']);

                foreach ($palabras as $palabra_busqueda) {
                    $this->db->like('CONCAT(titulo, IFNULL(texto_enunciado,""), IFNULL(cod_enunciado,""))', $palabra_busqueda);
                }
            }
            
        //Otros filtros
            //if ( $busqueda['a'] != '' ) { $this->db->where('area_id', $busqueda['a']); }    //Área
                
        //Otros
            //$this->db->where($filtro_rol);  //Filtro por rol
            $this->db->order_by('editado', 'DESC');
                
        //Condición especial
            if ( $busqueda['condicion'] != '' ) { $this->db->where($busqueda['condicion']); }   //Condición especial
            
        //Obtener resultados
        if ( is_null($per_page) ){
            $query = $this->db->get('enunciado'); //Resultados totales
        } else {
            $query = $this->db->get('enunciado', $per_page, $offset); //Resultados por página
        }
        
        return $query;
    }
    
    
    
    function recursos($busqueda, $per_page = NULL, $offset = NULL)
    {
        
        //Valor inicial
        $si_resultados = FALSE;

        //Construir búsqueda
        
            //Texto búsqueda
            if ( $busqueda['q'] != "" ) {
                
                //Crear array con términos de búsqueda
                $palabras = $this->palabras($busqueda['q']);

                if ( count($palabras) ){
                    //Si tiene al menos un elemento
                    $si_resultados = TRUE;
                    
                    foreach ($palabras as $palabra_busqueda) {
                        if ( strlen($palabra_busqueda) > 2 ){
                            $this->db->like('CONCAT(titulo, url )', $palabra_busqueda);
                        }
                    }
                }
            }
            
            //Área
            if ( $busqueda['area_id'] != '' ) {
                $si_resultados = TRUE;
                $this->db->where('area_id', $busqueda['area_id']);    
            }
            
            //Nivel
            if ( $busqueda['nivel'] != '' ) {
                $si_resultados = TRUE;
                $this->db->where('nivel', $busqueda['nivel']);    
            }
            
            //Nivel
            if ( $busqueda['tema_id'] != '' ) {
                $si_resultados = TRUE;
                $this->db->where('tema_id', $busqueda['tema_id']);
            }
        
        //No mostrar resultados
            if ( $si_resultados == FALSE ){
                $this->db->where('recurso.id', 0);
            }
        
        //Especificaciones de consulta
            $this->db->order_by('titulo', 'ASC');
            
        //Obtener resultados
        if ( is_null($per_page) ){
            $query = $this->db->get('recurso'); //Resultados totales
        } else {
            $query = $this->db->get('recurso', $per_page, $offset); //Resultados por página
        }
        
        return $query;
    }
    
    /**
     * Búsqueda de recursos y funciones del sistema
     * 
     * @param type $busqueda
     * @param type $per_page
     * @param type $offset
     * @return type
     */
    function acl_recursos($busqueda, $per_page = NULL, $offset = NULL)
    {
        //Construir búsqueda
        
            //Texto búsqueda
                //Crear array con términos de búsqueda
                if ( strlen($busqueda['q']) > 2 ){
                    $palabras = $this->palabras($busqueda['q']);

                    foreach ($palabras as $palabra_busqueda) {
                        $this->db->like('CONCAT(nombre_recurso, descripcion, recurso, roles)', $palabra_busqueda);
                    }
                }
            
            //Otros filtros
                if ( $busqueda['tp'] != '' ) { $this->db->where('tipo_recurso_id', $busqueda['tp']); }  //Tipo
                if ( $busqueda['condicion'] != '' ) { $this->db->where($busqueda['condicion']); }   //Condición especial
                
            //Otros
                $this->db->order_by('nombre_recurso', 'ASC');
            
        //Obtener resultados
        if ( is_null($per_page) ){
            $query = $this->db->get('sis_acl_recurso'); //Resultados totales
        } else {
            $query = $this->db->get('sis_acl_recurso', $per_page, $offset); //Resultados por página
        }
        
        return $query;
    }
    
    /**
     * Búsqueda de item, links de ayudas
     * 
     * @param type $busqueda
     * @param type $per_page
     * @param type $offset
     * @return type
     */
    function items($busqueda, $per_page = NULL, $offset = NULL)
    {
        //Construir búsqueda
        
            //Texto búsqueda
                //Crear array con términos de búsqueda
                if ( strlen($busqueda['q']) > 2 ){
                    $palabras = $this->palabras($busqueda['q']);

                    foreach ($palabras as $palabra_busqueda) {
                        $this->db->like('CONCAT(item, item_largo, descripcion)', $palabra_busqueda);
                    }
                }
            
            //Otros filtros
                if ( $busqueda['tp'] != '' ) { $this->db->where('categoria_id', $busqueda['tp']); }  //Tipo
                if ( $busqueda['condicion'] != '' ) { $this->db->where($busqueda['condicion']); }   //Condición especial
                
            //Otros
                $this->db->order_by('item', 'ASC');
            
        //Obtener resultados
        if ( is_null($per_page) ){
            $query = $this->db->get('item'); //Resultados totales
        } else {
            $query = $this->db->get('item', $per_page, $offset); //Resultados por página
        }
        
        return $query;
    }
    
    /**
     * Búsqueda de paginas
     * 
     * @param type $busqueda
     * @param type $per_page
     * @param type $offset
     * @return type
     */
    function paginas($busqueda, $per_page = NULL, $offset = NULL)
    {

        //Construir búsqueda
        
            //Texto búsqueda
                //Crear array con términos de búsqueda
                if ( strlen($busqueda['q']) > 2 ){
                    $palabras = $this->palabras($busqueda['q']);

                    foreach ($palabras as $palabra_busqueda) {
                        $this->db->like("CONCAT(titulo_pagina, IFNULL(archivo_imagen, ''), IFNULL(nombre_tema, ''))", $palabra_busqueda);
                    }
                }
            
            //Otros filtros
                if ( $busqueda['a'] != '' ) { $this->db->where('area_id', $busqueda['a']); }    //Área
                if ( $busqueda['n'] != '' ) { $this->db->where('nivel', $busqueda['n']); }      //Nivel
                if ( $busqueda['e'] != '' ) { $this->db->where('pagina_flipbook.editado', $busqueda['e']); }    //Editado
                //if ( $busqueda['original'] != '' ) { $this->db->where('pagina_origen_id IS NULL'); }  //Campo, pagina_origen_id
                
            //Otros
                $this->db->select('*, pagina_flipbook.id as pf_id');
                $this->db->join('tema', 'pagina_flipbook.tema_id = tema.id', 'LEFT');
                $this->db->order_by('titulo_pagina', 'ASC');
            
        //Obtener resultados
        if ( is_null($per_page) ){
            $query = $this->db->get('pagina_flipbook'); //Resultados totales
        } else {
            $query = $this->db->get('pagina_flipbook', $per_page, $offset); //Resultados por página
        }
        
        return $query;
    }
    
//---------------------------------------------------------------------------------------------------------
//FILTROS DE BÚSQUEDA SEGÚN EL ROL
    
    function filtro_instituciones($usuario_id = NULL)
    {
        
        if ( is_null($usuario_id) ){
            //Si es nulo, es el usuario de la sesión actual
            $usuario_id = $this->session->userdata('usuario_id');
        }
        
        $row_usuario = $this->Pcrn->registro_id('usuario', $usuario_id);
        $condicion = 'id = 0';  //Valor por defecto, ninguna institución, se obtendrían cero usuarios.
        
        if ( $row_usuario->rol_id == 0 ) {
            //Desarrollador, todos las instituciones
            $condicion = 'id > 0';
        } elseif ( $row_usuario->rol_id == 1 ) {
            //Administrador, todos las instituciones
            $condicion = 'id > 0';
        } elseif ( $row_usuario->rol_id == 2 ) {
            $condicion = 'id > 0';
        } elseif ( in_array($row_usuario->rol_id, array(3,4,5,6)) ) {
            //Su institución
            $condicion = "id = {$row_usuario->institucion_id} ";
        } elseif ( $this->session->userdata('rol_id') ) {
            //Comercial
            $condicion = "ejecutivo_id = {$this->session->userdata('usuario_id')}";
        }
        
        
        return $condicion;
        
    }
    
    function filtro_tickets($usuario_id = NULL)
    {
        
        if ( is_null($usuario_id) ){
            //Si es nulo, es el usuario de la sesión actual
            $usuario_id = $this->session->userdata('usuario_id');
        }
        
        $row_usuario = $this->Pcrn->registro_id('usuario', $usuario_id);
        $condicion = "id = 0";  //Valor por defecto, ningún usuario, se obtendrían cero usuarios.
        
        if ( $row_usuario->rol_id == 0 ) {
            //Administrador, todos los tickets
            $condicion = 'id > 0';
        } elseif ( $row_usuario->rol_id == 1 ) {
            //Administrador, todos los tickets
            $condicion = 'id > 0';
        } else {
            $condicion = 'id = 0';  //Pendiente, ningún resultado
        }
        
        return $condicion;
        
    }
    
}