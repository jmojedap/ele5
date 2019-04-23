<?php
class Respuesta_model extends CI_Model
{
    
    function basico($uc_id)
    {
        $row_uc = $this->Pcrn->registro_id('usuario_cuestionario', $uc_id);

        $data['row_uc'] = $row_uc;
        $data['row_usuario'] = $this->Pcrn->registro_id('usuario', $row_uc->usuario_id);
        $data['row_cuestionario'] = $this->Pcrn->registro_id('cuestionario', $row_uc->cuestionario_id);

        return $data;
    }

    function asignaciones($cuestionario_id, $grupo_id)
    {
        $this->db->select('usuario_cuestionario.id AS uc_id, usuario.nombre, usuario.apellidos');
        $this->db->where('cuestionario_id', $cuestionario_id);
        $this->db->where('usuario_cuestionario.grupo_id', $grupo_id);
        $this->db->join('usuario', 'usuario.id = usuario_cuestionario.usuario_id');
        
        $asignaciones = $this->db->get('usuario_cuestionario');

        return $asignaciones;
    }

// RESPUESTA DE CUESTIONARIOS
//-----------------------------------------------------------------------------

    /**
     * Guarda registro de la tabla usuario_cuestionario
     */
    function guardar_uc($uc_id, $arr_row) 
    {
        //Construir registro
            $arr_row['editado'] = date('Y-m-d H:i:s');

        //Actualizar
            $this->db->where('id', $uc_id);
            $this->db->update('usuario_cuestionario', $arr_row);
        
        //Cargar resultado
            $data['status'] = 1;
            $data['message'] = 'Respuestas guardadas para UC ID: ' . $uc_id;
            $data['uc_id'] = $uc_id;
        
        return $data;
    }

// IMPORTAR RESPUESTAS DE ARCHIVO JSON
//-----------------------------------------------------------------------------

    /**
     * Ejecuta la importación de respuestas de cuestionarios mediante un archivo JSON. Recibe el Objeto De respuestas
     * con el conjunto de datos de páginas escaneadas.
     */
    function importar_respuestas_json($obj_respuestas)
    {
        //Valor inicial
        $data = array('status' => 0, 'message' => 'Proceso no ejecutado', 'imported' => array(), 'not_imported' => array());
        
        foreach ( $obj_respuestas as $pagina )
        {
            $data_importar = $this->importar_respuesta($pagina);    //Cargar respuestas de cada página
            if ( $data_importar['status'] == 1 )
            {
                $data['imported'][] = $data_importar['uc_id'];
            } else {
                $data['not_imported'][] = $data_importar;
            }
        }
        
        //Verificar resultados para JSON
        if ( count($obj_respuestas) )
        {
            $data['status'] = 1;
            $data['message'] = 'Resultados importados: ' . count($data['imported']);
        }

        return $data;
    }

    /**
     * Importa las respuestas de una página, crea las respuestas en la BD y calcula resultados
     * totales.
     */
    function importar_respuesta($pagina)
    {
        //Datos inicales
            $uc_id = 0;
            if ( count($pagina) > 0 ) { $uc_id = $pagina[0]; }  //Verificar que el array de página tenga al menos un elemento

        //Resultado por defecto
            $data = array('status' => 0, 'message' => 'El código de página no fue encontrado en la plataforma', 'cod_page' => $uc_id);
            $row_uc = $this->Pcrn->registro_id('usuario_cuestionario', $uc_id);

        //La asignación de cuestionario sí existe
        if ( ! is_null($row_uc) )
        {
            $row_cuestionario = $this->Pcrn->registro_id('cuestionario', $row_uc->cuestionario_id);

            //Calcular string para campo usuario_cuestionario.respuestas
                $arr_respuestas = $this->arr_respuestas($pagina, $row_cuestionario->clave);
                $arr_row['respuestas'] = implode('-', $arr_respuestas);
            
            //String para campo usuario_cuestionario.resultados
                $arr_row['resultados'] = $this->str_resultados($arr_respuestas, $row_cuestionario->clave);

            if ( $row_uc->estado == 1)  //Verificar que el cuestionario no haya sido ya respondido o cargado (1, sin responder)
            {
                //Guardar registro en la tabla usuario_cuestionario
                    $data = $this->guardar_uc($row_uc->id, $arr_row);

                //Generar respuestas y finalizar cuestionario
                    $this->Cuestionario_model->generar_respuestas($row_uc->id);
                    $this->Cuestionario_model->n_finalizar($row_uc->id);
            } else {
                $data['message'] = 'Respondido o cargado anteriormente';
            }
        }

        return $data;
    }

    /**
     * Calcular string para campo usuario_cuestionario.resultados a partir
     * de las respuestas del usuario y la clave de respuestas correctas
     */
    function str_resultados($respuestas, $clave)
    {
        //Calcular string para campo usuario_cuestionario.resultados
        $arr_resultados = array();
        $arr_clave = explode('-', $clave);
        foreach ( $arr_clave as $key => $correcta )
        {
            $resultado = 0;
            if ( isset($respuestas[$key]) )
            {
                if ( $respuestas[$key] == $correcta ) { $resultado = 1; }
            }
            $arr_resultados[] = $resultado;
        }

        $resultados = implode('-', $arr_resultados);

        return $resultados;
    }

    /**
     * Devuelve array con las respuestas, a partir de lo obtenido en la importación
     * Si hay respuestas faltantes las completa con ceros (0).
     */
    function arr_respuestas($pagina, $clave)
    {
        $arr_clave = explode('-', $clave);
        $num_preguntas = count($arr_clave);

        $arr_respuestas = array_slice($pagina, 1);  //Quita primer elemento, correspondiente a $row_uc->id

        //Verifica si hay respuestas faltantes, y las completa con ceros (0)
        $num_faltantes = $num_preguntas - count($arr_respuestas);
        if ( $num_faltantes > 0 )
        {
            for ($i=0; $i < $num_faltantes; $i++) { $arr_respuestas[] = 0;}
        }

        return $arr_respuestas;
    }

    /**
     * Query de tabla usuario_cuestionario, de los cuestionarios que fueron cargados al importar el archivo JSON
     */
    function query_importados($imported)
    {
        $condition = 'usuario_cuestionario.id = 0'; //Valor por defecto
        if ( count($imported) > 0 )
        {
            $str_imported = implode($imported, ',');
            $condition = "usuario_cuestionario.id IN ($str_imported)";
        }

        $this->db->select('usuario_cuestionario.id, usuario_id, cuestionario_id, resumen, nombre, apellidos, nombre_cuestionario');
        $this->db->join('usuario', 'usuario.id = usuario_cuestionario.usuario_id');
        $this->db->join('cuestionario', 'cuestionario.id = usuario_cuestionario.cuestionario_id');
        $this->db->where($condition);
        $this->db->where('usuario_cuestionario.estado', 3);
        $this->db->order_by('usuario_cuestionario.cuestionario_id', 'ASC');
        $this->db->order_by('usuario_cuestionario.grupo_id', 'ASC');
        
        $query_importados = $this->db->get('usuario_cuestionario');

        return $query_importados;
    }

    /**
     * String con el HTML del resultado del proceso de cargue de respuestas con el archivo JSON
     */
    function html_resultado($data)
    {
        $data['importados'] = $this->query_importados($data['imported']);

        $data['titulo_pagina'] = 'respuestas/cargue_json/resultado_v';
        $data['vista_a'] = 'respuestas/cargue_json/resultado_v';
        $html_resultado = $this->load->view('templates/bs4_basic/main_v', $data, TRUE);

        return $html_resultado;
    }

    /**
     * Guarda en la tabla evento un registro sobre la ejecución de un  cargue remoto de respuestas
     * con la herramienta Evaluator
     */
    function guardar_ev_importacion($data)
    {
        //Agregando información de IP
            $data['ip_address'] = $this->input->ip_address();

        //Construir registro
            $arr_row['nombre_evento'] = $this->input->ip_address();
            $arr_row['tipo_id'] = 23;
            $arr_row['fecha_inicio'] = date('Y-m-d');
            $arr_row['hora_inicio'] = date('H:i:s');
            $arr_row['creado'] = date('Y-m-d H:i:s');
            $arr_row['editado'] = date('Y-m-d H:i:s');
            $arr_row['descripcion'] = json_encode($data);
            $arr_row['estado'] = $data['status'];
            $arr_row['entero_1'] = count($data['imported']);    //Cantidad de importados

        //Guardar registro
            $this->db->insert('evento', $arr_row);

        return $this->db->insert_id();
    }
}