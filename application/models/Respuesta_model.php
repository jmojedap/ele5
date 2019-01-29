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
    

}