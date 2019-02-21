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
    

}