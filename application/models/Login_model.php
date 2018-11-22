<?php
class Login_model extends CI_Model{
    
    /**
     * Realiza la validación de login, usuario y contraseña. Valida coincidencia
     * de contraseña, y estado del usuario.
     * 
     * @param type $userlogin
     * @param type $password
     * @return int
     */
    function validar_login($userlogin, $password)
    {
        $resultado['ejecutado'] = 0;
        $resultado['mensajes'] = array();
        
        $condiciones = 0;   //Valor inicial
        
        //Validación de password (Condición 1)
            $validar_password = $this->validar_password($userlogin, $password);
            $resultado['mensajes'][] = $validar_password['mensaje'];

            if ( $validar_password['ejecutado'] ) { $condiciones++; }
            
        //Verificar que el usuario esté activo (Condición 2)
            $estado_usuario = $this->estado_usuario($userlogin);
            if ( $estado_usuario['estado'] != 1 ) { $resultado['mensajes'][] = $estado_usuario['mensaje']; }
            
            
            if ( $estado_usuario['estado'] == 1 ) { $condiciones++; }   //Usuario activo
            
        //Se valida el login si se cumplen las condiciones
        if ( $condiciones == 2 ) 
        {
            $resultado['ejecutado'] = 1;
        }
            
        return $resultado;
    }
    
    //Verificar si tiene cookie para ser recordado en el equipo
    function login_cookie()
    {
        $this->load->helper('cookie');
        get_cookie('dksesionrc');
        $recordarme = $this->input->cookie('dksesionrc');

        $condicion = "cod_activacion = '{$recordarme}'";
        $row_usuario = $this->Pcrn->registro('usuario', $condicion);

        if ( ! is_null($row_usuario) && strlen($recordarme) > 0)
        {
            $this->crear_sesion($row_usuario->username, TRUE);
        }    
    }
    
    /**
     * Array con: valor del campo usuario.estado, y un mensaje explicando 
     * el estado
     * 
     * @param type $userlogin
     * @return string
     */
    function estado_usuario($userlogin)
    {
        $est_usuario['estado'] = 2;     //Valor inicial, 2 => inexistente
        $est_usuario['mensaje'] = 'No existe un usuario identificado con "'. $userlogin .'"';
        
        $this->db->where("username = '{$userlogin}' OR email = '{$userlogin}' OR no_documento = '{$userlogin}'");
        $query = $this->db->get('usuario');
        
        if ( $query->num_rows() > 0 )
        {
            $est_usuario['estado'] = $query->row()->estado;
            $est_usuario['mensaje'] = 'Usuario activo';
            
            if ( $est_usuario['estado'] == 0 ) { $est_usuario['mensaje'] = 'El usuario está inactivo, consulte al administrador'; }
        }
        
        return $est_usuario;
        
    }
    
    /**
     * Verificar la contraseña de de un usuario. Verifica que la combinación de
     * usuario y contraseña existan en un mismo registro en la tabla usuario.
     * 
     * FORMATO TEMPORAL, actualización en método de encriptación 2018-11-18
     * 
     * @param type $userlogin
     * @param type $password
     * @return boolean
     */
    function validar_password($userlogin, $password)
    {
        //Valor por defecto
            $resultado['ejecutado'] = 0;
            $resultado['mensaje'] = 'Contraseña no válida para el usuario "'. $userlogin .'"' ;
         
        //Buscar usuario con username o correo electrónico
            $condicion = "username = '{$userlogin}' OR email = '{$userlogin}' OR no_documento = '{$userlogin}'";
            $row_usuario = $this->Pcrn->registro('usuario', $condicion);
        
        if ( ! is_null($row_usuario) )
        {
            //ANTERIOR VERSIÓN DE CONTRASEÑA
            $epw = md5($password);
            $pw_comparar = $row_usuario->ant_password;
            
            if ( $row_usuario->cpw )
            {
                $epw = crypt($password, $row_usuario->password);
                $pw_comparar = $row_usuario->password;
            }
            
            if ( $pw_comparar == $epw )
            {
                $resultado['ejecutado'] = 1;    //Contraseña válida
                $resultado['mensaje'] = 'Contraseña válida';
                
                //ACTUALIZAR A CONTRASEÑA FORMATO NUEVO
                if ( ! $row_usuario->cpw )
                {
                    //Se asigna la contraseña encriptada
                    $this->load->model('Usuario_model');
                    $this->Usuario_model->establecer_contrasena($row_usuario->id, $password);
                }
                
            }
        }
        
        return $resultado;
    }
    
    function crear_sesion($username, $registrar_login = TRUE)
    {
        $data = $this->session_data($username);
        $this->session->set_userdata($data);

        //Registrar evento de login en la tabla [evento]
        if ( $registrar_login )
        {
            $this->load->model('Evento_model');
            $this->Evento_model->guardar_ev_login();
        }
        
        //Actualizar usuario.ultimo_login
            $this->act_ultimo_login($username);
        
        //Si el usuario solicitó ser recordardo en el equipo
            if ( $this->input->post('recordarme') ) { $this->recordarme(); }
    }
    
    /**
     * Después de iniciar sesión, se edita el campo usuario.ultimo_login para
     * registrar fecha y hora del login más reciente que realizó el usuario.
     * 
     * @param type $username
     */
    function act_ultimo_login($username)
    {
        $registro['ultimo_login'] = date('Y-m-d H:i:s');
        $this->db->where('username', $username);
        $this->db->update('usuario', $registro);
    }
    
    /**
     * Guardar evento final de sesión, eliminar cookie y destruir sesión
     */
    function logout()
    {
        //Editar, evento de inicio de sesión
            if ( strlen($this->session->userdata('login_id')) > 0 ) 
            {
                $row_evento = $this->Pcrn->registro_id('evento', $this->session->userdata('login_id'));

                $registro['fin'] = date('Y-m-d H:i:s');
                $registro['estado'] = 2;    //Cerrado
                $registro['segundos'] = $this->Pcrn->segundos_lapso($row_evento->creado, date('Y-m-d H:i:s'));

                if ( ! is_null($row_evento) ) 
                {
                    //Si el evento existe
                    $this->Pcrn->guardar('evento', "id = {$row_evento->id}", $registro);
                }
            }
        
        //Eliminar cookie
            $this->load->helper('cookie');
            delete_cookie('dksesionrc');
            
        //Destruir sesión existente
            $this->session->sess_destroy();
    }
    
    /**
     * Se ejecuta la función si el usuario activó la casilla "Recordarme" en
     * el formulario de login.
     */
    function recordarme()
    {
        $this->load->helper('string');
        $registro['cod_activacion'] = random_string('alnum', 32);
        
        //Crear cookie por 7 días
            $this->load->helper('cookie');
            set_cookie('dksesionrc', $registro['cod_activacion'], 7*24*60*60);
        
        //Actualizar en la base de datos
            $this->db->where('id', $this->session->userdata('usuario_id'));
            $this->db->update('usuario', $registro);
    }
    
    function session_data($username)
    {
        $this->load->helper('text');
        $row_usuario = $this->Pcrn->registro('usuario', "username = '{$username}' OR email='{$username}' OR no_documento='{$username}'");

        //$data general
            $data = array(
                'logged' =>   TRUE,
                'nombre_usuario'    =>  $row_usuario->username,
                'nombre_completo'    =>  "{$row_usuario->nombre} {$row_usuario->apellidos}",
                'nombre_corto'    =>  $row_usuario->nombre,
                'usuario_id'    =>  $row_usuario->id,
                'rol_id'    => $row_usuario->rol_id,
                'rol_abrv'    => $this->Pcrn->campo('item', "categoria_id = 58 AND id_interno = {$row_usuario->rol_id}", 'abreviatura'),
                'ultimo_login'    => $row_usuario->ultimo_login,
                //'src_img'    => $this->App_model->src_img_usuario($row_usuario, 'sm_'),
                //'acl' => $this->acl($row_usuario)   //Listado de permisos
            );
                
        //Session $data específico para esta aplicación
            $this->load->model('Esp');
            $data_especifico = $this->Esp->session_data_app($row_usuario);
        
        //Devolver array completo
            return array_merge($data, $data_especifico);
    }
    
    function acl($row_usuario)
    {
        $this->db->where("roles LIKE  '%-{$row_usuario->rol_id}-%'");
        $query = $this->db->get('sis_acl');
        
        $array = $this->Pcrn->query_to_array($query, 'id', NULL);
        
        $funciones_permitidas = $array;
        return $funciones_permitidas;
    }
    
    /**
     * Devuelve array $recaptcha, con el resultado de la validación formularios
     * utilizando la herramienta reCaptcha
     * 
     * @return type
     */
    function recaptcha()
    {
        $recaptcha = array(
            'success' => FALSE,
            'challenge_ts' => NULL,
            'hostname' => '',
        );
        
        if ( ! is_null($this->input->post('g-recaptcha-response')) )
        {
            $secret = '6LfC3TQUAAAAAK4qRUzs_AAVwiyIc09eNgFcDvdL';
            $response = $this->input->post('g-recaptcha-response');
            $remoteip = $this->input->ip_address();
            
            $get = "response={$response}&secret={$secret}&remoteip={$remoteip}";
            ini_set("allow_url_fopen", 1);  //2017-07-10, prueba
            $json_recaptcha = file_get_contents("https://www.google.com/recaptcha/api/siteverify?{$get}");
            $recaptcha = json_decode($json_recaptcha, TRUE);
        }
        
        return $recaptcha;
    }
    
    /**
     * Validación del formulario de registro de un usuario en el sitio
     * 
     * @return type
     */
    function validar_registro()
    {
        //Valores iniciales, resultado por defecto
            $resultado['ejecutado'] = 0;
            $resultado['mensajes'] = array(
                'recaptcha' => 'Para registrarse debe activar la casilla de verificación "No soy un robot"',
                'cant_emails' => 'La dirección de correo electrónico ya está registrada. Si ya se registró, recupere su contraseña >> <a href="' . base_url('usuarios/recuperar') . '">aquí</a> <<'
            );
            $cant_condiciones = 0;
        
        //Condición 1. Aprobar el reCaptcha
            $recaptcha = $this->recaptcha();   //Validar formulario con la herramienta reCaptcha
            if ( $recaptcha['success'] ) 
            {
                $cant_condiciones++;
                unset($resultado['mensajes']['recaptcha']);
            }
        
        //Condición 2. E-mail único
            $cant_emails = $this->Pcrn->num_registros('usuario', "email = '{$this->input->post('email')}'");
            if ( $cant_emails == 0 ) 
            {
                $cant_condiciones++;
                unset($resultado['mensajes']['cant_emails']);
            }
            
        //Verificación de condiciones cumplidas
            if ( $cant_condiciones == 2 ) { $resultado['ejecutado'] = 1; }
            
        return $resultado;
    }
    
//GESTIÓN DE CONTRASEÑAS
//---------------------------------------------------------------------------------------------------
    
    /**
     * Devuelve password encriptado
     * 
     * @param type $input
     * @param type $rounds
     * @return type
     */
    function encriptar_pw($input, $rounds = 7)
    {
        $salt = '';
        $salt_chars = array_merge(range('A','Z'), range('a','z'), range(0,9));
        for($i=0; $i < 22; $i++) {
          $salt .= $salt_chars[array_rand($salt_chars)];
        }
        
        return crypt($input, sprintf('$2a$%02d$', $rounds) . $salt);
    }
    
    function establecer_contrasena($usuario_id, $password)
    {
        $registro['password'] = $this->encriptar_pw($password);
        $this->db->where('id', $usuario_id);
        $action = $this->db->update('usuario', $registro);
        return $action;
    }
    
    function activar($cod_activacion)
    {
        $row_activacion = $this->Pcrn->registro('usuario', "cod_activacion = '{$cod_activacion}'");
        
        //Registro
            $registro['estado'] = 1;
            $registro['password'] = $this->encriptar_pw($this->input->post('password'));

        //Actualizar
            $this->db->where('id', $row_activacion->id);
            $this->db->update('usuario', $registro);
            
        return $row_activacion;
    }
    
// Vinculación de cuentas de usuario con Google
//-----------------------------------------------------------------------------
    /**
     * Prepara un objeto Google_Client, para solicitar la autorización  de una
     * autenticación de un usuario de google y obtener información de su cuenta
     * 
     * Credenciales de Cliente para la aplicación DeKinder, creadas con 
     * la cuenta google pacarinamedialab@gmail.com
     * 
     * @return \Google_Client
     */
    function g_client()
    {
        $g_client = new Google_Client();
        $g_client->setClientId('');
        $g_client->setClientSecret('');
        $g_client->setApplicationName('');
        $g_client->setRedirectUri('http://localhost/sid/app/g_callback');
        $g_client->setScopes('https://www.googleapis.com/auth/userinfo.profile https://www.googleapis.com/auth/userinfo.email');
        
        return $g_client;
    }
    
    /**
     * Teniendo como entrada el objeto Google_Cliente autorizado, se solicita
     * y se obtiene la información de la cuenta de un usuario mediante 
     * Google_Service_Oauth2 y se carga en el array g_userinfo
     * 
     * @param type $g_client
     * @return type
     */
    function g_userinfo($g_client)
    {
        $oAuth = new Google_Service_Oauth2($g_client);
        $g_userinfo = $oAuth->userinfo_v2_me->get();
        
        return $g_userinfo;
    }
    
    /**
     * Guarda datos de cuenta google de un usuario en la tabla meta, 
     * dato_id = 100021
     */
    function g_guardar_cuenta($usuario_id)
    {
        $g_userinfo = $this->session->userdata('g_userinfo');
        
        $registro['tabla_id'] = 1000;
        $registro['elemento_id'] = $usuario_id;
        $registro['dato_id'] = '100021';    //Cuenta vinculada de Google
        $registro['valor'] = json_encode($g_userinfo);
        $registro['texto_1'] = $g_userinfo['id'];
        $registro['texto_2'] = $g_userinfo['picture'];
        
        $this->load->model('Meta_model');
        $meta_id = $this->Meta_model->guardar($registro, 'texto_1');   //La clave única está en texto_1
        
        return $meta_id;
    }
}