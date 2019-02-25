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
        $row_uc = $this->Pcrn->registro_id('usuario_cuestionario', $pagina->asignacion_id);

        $data['cuestionario_id'] = $row_uc->cuestionario_id;

        return $data;
    }
    

}