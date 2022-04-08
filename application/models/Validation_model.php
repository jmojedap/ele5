<?php
class Validation_model extends CI_Model{

// General
//-----------------------------------------------------------------------------

    /**
     * Validación de Google Recaptcha V3, la validación se realiza considerando el valor de
     * $recaptcha->score, que va de 0 a 1.
     * 2019-10-31
     */
    function recaptcha()
    {
        $secret = K_RCSC;   //Ver config/constants.php
        $response = $this->input->post('g-recaptcha-response');
        $json_recaptcha = file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret={$secret}&response={$response}");
        $recaptcha = json_decode($json_recaptcha);
        
        return $recaptcha;
    }

// Usuarios
//-----------------------------------------------------------------------------

    /**
     * Valida que username sea único, si se incluye un ID User existente
     * lo excluye de la comparación cuando se realiza edición
     * 2021-02-18
     */
    function username($user_id = null)
    {
        $validation['username_unique'] = -1;    //Por defecto

        if ( strlen($this->input->post('username')) > 0 ) {
            $validation['username_unique'] = $this->Db_model->is_unique('usuario', 'username', $this->input->post('username'), $user_id);
        }
        return $validation;
    }

    /**
     * Valida que username sea único, si se incluye un ID User existente
     * lo excluye de la comparación cuando se realiza edición
     * 2021-02-18
     */
    function email($user_id = null)
    {
        $validation['email_unique'] = -1;   //Indeterminado
        //$validation['email_unique'] = FALSE;   //Indeterminado

        if ( strlen($this->input->post('email')) > 0 ) {
            $validation['email_unique'] = $this->Db_model->is_unique('usuario', 'email', $this->input->post('email'), $user_id);
        }

        return $validation;
    }

    /**
     * Valida que número de identificacion (document_number) sea único, si se 
     * incluye un ID User existentelo excluye de la comparación cuando se 
     * realiza edición
     * 2021-02-18
     */
    function document_number($user_id = null)
    {
        $validation['document_number_unique'] = -1;

        //Si tiene algún valor escrito
        if ( strlen($this->input->post('no_documento')) > 0 ) {
            $validation['document_number_unique'] = $this->Db_model->is_unique('usuario', 'no_documento', $this->input->post('no_documento'), $user_id);
        }
        return $validation;
    }

    /**
     * Valida que el rol del usuario a crearse, sea de inferior jerarquía
     * al del usuario que lo está creando
     * 2022-03-30
     */
    function lower_role($session_user_role)
    {
        $validation['lower_role'] = -1;

        //Si tiene algún valor escrito
        if ( strlen($this->input->post('rol_id')) > 0 ) {
            if (  $this->input->post('rol_id') > $session_user_role) {
                //El valor del rol debe ser mayor (menor jerarquía) para validarse
                $validation['lower_role'] = 1;
            } else {
                //Sie le valor del rol es igual o menor, no se valida
                $validation['lower_role'] = 0;
            }
        }
        return $validation;
    }
}