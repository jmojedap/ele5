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
            $data['ejecutado'] = 1;
            $data['mensaje'] = 'Respuestas guardadas para UC ID: ' . $uc_id;
        
        return $data;
    }

// IMPORTAR RESPUESTAS DE ARCHIVO JSON
//-----------------------------------------------------------------------------

    function importar_respuestas_json($obj_respuestas)
    {
        $data = array('status' => 0, 'message' => 'Proceso no ejecutado', 'imported' => array());

        if ( count($obj_respuestas) )
        {
            $data = array('status' => 1, 'message' => 'Páginas encontradas: ' . count($obj_respuestas));
        }

        foreach ( $obj_respuestas as $pagina )
        {
            $data['imported'][] = $this->importar_respuesta($pagina);
        }

        return $data;
    }

    function importar_respuesta($pagina)
    {
        //Datos inicales
            $row_uc = $this->Pcrn->registro_id('usuario_cuestionario', $pagina->asignacion_id);
            $row_cuestionario = $this->Pcrn->registro_id('cuestionario', $row_uc->cuestionario_id);

        //Calcular string para campo usuario_cuestionario.respuestas
            $arr_respuestas = (array) $pagina->respuestas;
            $arr_row['respuestas'] = implode('-', $arr_respuestas);
        
        //String para campo usuario_cuestionario.resultados
            $arr_row['resultados'] = $this->str_resultados($pagina->respuestas, $row_cuestionario->clave);

        //Guardar registro en la tabla usuario_cuestionario
            $data = $this->guardar_uc($row_uc->id, $arr_row);

        //Generar respuestas y finalizar cuestionario
            $this->Cuestionario_model->generar_respuestas($row_uc->id);
            $this->Cuestionario_model->finalizar($row_uc->id);

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
        foreach ( $respuestas as $key => $respuesta )
        {
            $resultado = 0;
            if ( $arr_clave[$key] == $respuesta ) { $resultado = 1; }
            $arr_resultados[] = $resultado;
        }

        $resultados = implode('-', $arr_resultados);

        return $resultados;
    }
    

}