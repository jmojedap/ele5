<?php

class Esp extends CI_Model {
    /* Esp hace referencia a Especial,
     * Colección de funciones especiales para utilizarse específicamente
     * con CodeIgniter en la aplicación del sitio en casos especiales
     * 
     * PlataformaEnLinea.com V4
     */

    function __construct() {
        parent::__construct();
    }

//SESIONES DE USUARIO
//---------------------------------------------------------------------------------------------------------

    /**
     * 2018-08-18
     * Devuelve row de usuario según un username escrito en formulario de login
     * 
     * @param type $username
     * @return type
     */
    function z_row_username($username) {
        $row_username = NULL;

        $condicion = "(username='{$username}' OR email='{$username}')";

        $this->db->select('id, nombre, apellidos, rol_id, username, institucion_id, grupo_id, cpw, password, ant_password, activo');
        $this->db->where($condicion);
        $this->db->limit(1);
        $query = $this->db->get('usuario');

        if ($query->num_rows() > 0) {
            $row_username = $query->row();
        }

        return $row_username;
    }

    /**
     * Función de validación utilizada para el login en el sistema
     * 
     * En la lista de reglas de validación de password se pone después de la regla MD5 para que 
     * la cadena ya venga convertida para la comparación con la base de datos
     * 
     * @param type $password
     * @return type 
     */
    function z_login_password($password) {

        $this->load->model('Esp');
        $username = $this->input->post('username');

        $login_password = $this->Esp->verificar_login($username, $password);
        return $login_password;
    }

    function z_estado_usuario($row_usuario) {
        $estado_usuario = 1;    //Valor inicial, 1 => activo

        if (!is_null($row_usuario)) {
            if ($row_usuario->estado == 0) {
                $estado_usuario = 0;    //0 => inactivo
            }
        } else {
            $estado_usuario = 2;    //2 => inexistente
        }

        return $estado_usuario;
    }

    /**
     * Verificar si la combinación de username y password existe en un mismo registro
     * Modificada: 2018-11-14
     * 
     * @param type $row_usuario
     * @param type $password
     * @return boolean
     */
    function z_validar_password($row_usuario, $password) {
        $valida = 0;

        if (!is_null($row_usuario)) {
            $epw = md5($password);
            $pw_comparar = $row_usuario->ant_password;

            if ($row_usuario->cpw) {
                $epw = crypt($password, $row_usuario->password);
                $pw_comparar = $row_usuario->password;
            }

            if ($pw_comparar == $epw) {
                $valida = 1;

                if (!$row_usuario->cpw) {
                    //Se asigna la contraseña encriptada
                    $this->load->model('Usuario_model');
                    $this->Usuario_model->establecer_contrasena($row_usuario->id, $password);
                }
            }
        }

        return $valida;
    }

    /**
     * Crea la sesión de usuario
     * 
     * @param type $row_usuario
     * @param type $registrar_login
     */
    function z_crear_sesion($row_usuario, $registrar_login) {
        $data = $this->session_data($row_usuario);
        $this->session->set_userdata($data);

        //Registrar evento de login en la tabla [evento]
        if ($registrar_login) {
            $this->load->model('Evento_model');
            $this->Evento_model->guardar_ev_login($row_usuario);
        }
    }

    /**
     * DESACTIVADA 2018-11-16
     * Array de datos para la creación de sesión de usuario
     * 
     * @param type $row_usuario
     * @return type
     */
    function z_session_data($row_usuario) {

        //$row_usuario = $this->Pcrn->registro('usuario', "username = '{$username}'");
        //$data general
        $data = array(
            'logged' => TRUE,
            'nombre_usuario' => $row_usuario->username,
            'nombre' => $row_usuario->nombre,
            'nombre_completo' => "{$row_usuario->nombre} {$row_usuario->apellidos}",
            'usuario_id' => $row_usuario->id,
            'rol_id' => $row_usuario->rol_id,
            'row' => $row_usuario,
            'src_img' => URL_IMG . 'usuarios/usuario.png'
        );

        //$data específico
        $data_especifico = $this->session_data_app($row_usuario);

        //Devolver array completo
        return array_merge($data, $data_especifico);
    }

    /**
     * Variables de sesión de usuario, específicas para plataformaenlinea.com
     * @param type $row_usuario
     * @return type
     */
    function session_data_app($row_usuario) {
        $data['institucion_id'] = $this->Pcrn->si_nulo($row_usuario->institucion_id, 0);
        $data['grupo_id'] = $this->Pcrn->si_nulo($row_usuario->grupo_id, 0);

        //Flipbooks
        $this->load->model('Usuario_model');
        $flipbooks = $this->Usuario_model->flipbooks($row_usuario, '0,1,3');
        $data['arr_flipbooks'] = $flipbooks->result_array();

        //Cuestionarios
        $data['arr_cuestionarios'] = array();
        if ($row_usuario->rol_id == 6) {
            $fecha_hoy = date('Y-m-d H:i:s');
            $condicion = "tipo_id IN (3,4) AND estado < 3 AND ('" . $fecha_hoy . "') > fecha_inicio AND ('" . $fecha_hoy . "') < fecha_fin";
            $cuestionarios = $this->Usuario_model->cuestionarios($row_usuario->id, $condicion);

            $data['arr_cuestionarios'] = $cuestionarios->result_array();
        }

        //Seguridad, utilizada en hooks/acceso
        $funciones_bloqueadas = $this->funciones_bloqueadas($row_usuario->rol_id);
        $data['funciones_bloqueadas'] = $funciones_bloqueadas;

        //Calculando cantidad de mensajes sin leer
        $this->load->model('Mensaje_model');
        $this->Mensaje_model->depurar($row_usuario->id);
        $no_leidos = $this->Mensaje_model->no_leidos(NULL, $row_usuario->id);
        $data['no_leidos'] = $no_leidos;

        //Año generación del usuario

        $data['anio_usuario'] = $this->Usuario_model->anio_usuario($row_usuario);

        //Grupos asociados
        $grupos = $this->Usuario_model->grupos_usuario($row_usuario->id);
        $data['arr_grupos'] = $this->Pcrn->query_to_array($grupos, 'id');

        //Super rol, grupo de roles de usuario
        $data['srol'] = $this->superrol($row_usuario->rol_id);

        return $data;
    }

    /**
     * Funciones de la plataforma que están bloqueadas según el rol de usuario
     * 
     * @param type $rol_id
     * @return type
     */
    function funciones_bloqueadas($rol_id) {
        $this->db->select('id');
        $this->db->where("roles LIKE  '%-{$rol_id}-%'");
        $query = $this->db->get('sis_acl_recurso');

        $array = $this->Pcrn->query_to_array($query, 'id');

        $funciones_bloqueadas = $array;
        return $funciones_bloqueadas;
    }

    /**
     * Verificar permiso para enviar info al centro de recursos
     */
    function permiso_cgr($usuario_id, $username) {

        $condicion = "id = {$usuario_id} AND username = '{$username}' AND rol_id <= 2";
        $num_registros = $this->Pcrn->num_registros('usuario', $condicion);

        $condiciones = 0;
        if ($num_registros > 0) {
            $condiciones += 1;
        }

        //Se cumplen las 1 condiciones
        if ($condiciones == 1) {
            return 1;
        } else {
            return 0;
        }

        //return $condiciones;
    }
    
    //Contraseña por defecto
    function pw_default()
    {
        $contrasena = $this->Pcrn->campo_id('sis_opcion', 10, 'valor');
        $this->load->model('Login_model');
        return $this->Login_model->encriptar_pw($contrasena);
    }

    function superrol($rol_id) {
        $arr_superroles = array(
            0 => 'interno',
            1 => 'interno',
            2 => 'interno',
            3 => 'institucional',
            4 => 'institucional',
            5 => 'institucional',
            6 => 'estudiante',
            7 => 'interno',
            8 => 'interno',
            9 => 'interno'
        );

        return $arr_superroles[$rol_id];
    }

//---------------------------------------------------------------------------------------------------------
//ARRAYS

    function arr_sexos_cod() 
    {
        $sexos_cod = array(
            'F' => 1,
            'M' => 2
        );
        return $sexos_cod;
    }

    function arr_roles_cod($tipo = 'institucional') {
        $arrays['institucional'] = array(
            'A' => 3, //Administrador institucional
            'D' => 4, //Directivo
            'P' => 5, //Profesor
        );

        $roles_cod = $arrays[$tipo];

        return $roles_cod;
    }

    function arr_tipos_recurso() {
        $tipos_recurso = array(
            1 => 'Archivo interno',
            2 => 'Link',
            3 => 'Carpeta',
            4 => 'Archivo CGR'
        );

        return $tipos_recurso;
    }

    function arr_tipos_archivo() {
        $this->db->where('categoria_id', 20);
        $query = $this->db->get('item');

        $arr_tipo_archivo = $this->Pcrn->query_to_array($query, 'id', 'abreviatura');

        return $arr_tipo_archivo;
    }

    function arr_cod_area() {
        $this->db->where('categoria_id', 1);
        $query = $this->db->get('item');

        $arr_cod_area = $this->Pcrn->query_to_array($query, 'id', 'abreviatura');

        return $arr_cod_area;
    }

    function arr_componentes() {
        $this->db->where('categoria_id', 8);
        $query = $this->db->get('item');

        $arr_componentes = $this->Pcrn->query_to_array($query, 'id', 'id');

        return $arr_componentes;
    }

    function z_arr_tipo_quiz($dropdown = FALSE) {
        $arr_tipo_quiz = array(
            1 => 'A',
            2 => 'B',
            3 => 'C',
            4 => 'D',
            5 => 'E',
            6 => 'F',
            7 => 'G',
            9 => 'I',
            10 => 'J',
            12 => 'L',
            13 => 'M'
        );

        $opciones_dropdown = array(
            '00' => '[ Tipo quiz ]',
            '01' => 'A',
            '02' => 'B',
            '03' => 'C',
            '04' => 'D',
            '05' => 'E',
            '06' => 'F',
            '07' => 'G',
            '09' => 'I',
            '010' => 'J',
            '012' => 'L',
            '013' => 'M'
        );

        if ($dropdown == TRUE) {
            return $opciones_dropdown;
        } else {
            return $arr_tipo_quiz;
        }
    }

    function arr_letras() {

        $arr_areas['A'] = 1;
        $arr_areas['B'] = 2;
        $arr_areas['C'] = 3;
        $arr_areas['D'] = 4;

        return $arr_areas;
    }

//---------------------------------------------------------------------------------------------------------
//GENERAL



    function max_query($query, $campo) {
        $max = 0;
        foreach ($query->result() as $row) {
            $pruebas = 0;
            if ($row->$campo > $max) {
                $pruebas += 1;
            }
            if (!is_null($row->$campo)) {
                $pruebas += 1;
            }

            if ($pruebas == 2) {
                $max = $row->$campo;
            }
        }

        return $max;
    }

    /**
     * Cantidad de registros de la tabla evento
     * Según unas condiciones sql (array)
     * 
     * @param type $condiciones
     * @return type
     */
    function cant_eventos($condiciones) 
    {
        foreach ($condiciones as $condicion) {
            $this->db->where($condicion);
        }

        $query = $this->db->get('evento');

        return $query->num_rows();
    }

    /**
     * Comprueba si un email es válido
     * Si no lo es devuelve un valor alternativo elegido
     * 
     * @param type $email
     * @param type $valor_alterno
     * @return type
     */
    function validar_email($email, $valor_alterno = '') {
        $email_valido = $valor_alterno;
        $this->load->helper('email');
        if (valid_email($email)) {
            $email_valido = $email;
        }

        return $email_valido;
    }

}
