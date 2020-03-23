<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class App extends CI_Controller{
    
    function __construct()
    {
        parent::__construct();
        
        $this->load->model('Login_model');
        
        //Para definir hora local
        date_default_timezone_set("America/Bogota");
    }
    
    /**
     * Primera función de acceso al sistema 
     */
    function index()
    {
        
        //Si está logueado se envía a la vista de inicio
        
        //echo 'logged: ' . $this->session->userdata('usuario_id');
        
        if ( $this->session->userdata('logged') )
        {
            $row_usuario = $this->Pcrn->registro_id('usuario', $this->session->userdata('usuario_id'));
            
            $destinos_rol[0] = 'usuarios/explorar';
            $destinos_rol[1] = 'usuarios/explorar';
            $destinos_rol[2] = 'usuarios/explorar';
            $destinos_rol[3] = 'usuarios/biblioteca';
            $destinos_rol[4] = 'usuarios/biblioteca';
            $destinos_rol[5] = 'usuarios/biblioteca';
            $destinos_rol[6] = 'usuarios/biblioteca';
            $destinos_rol[7] = 'app/inicio';
            $destinos_rol[8] = 'app/inicio';
            $destinos_rol[9] = 'app/inicio';
            
            $destino = $destinos_rol[$row_usuario->rol_id];
            
            //Si es estudiante y no ha iniciado sus datos
                if ( $row_usuario->iniciado == 0 && $row_usuario->rol_id == 6 ) { $destino = "usuarios/editarme/edit/{$row_usuario->id}"; }
                
            //Verificar que no tenga contraseña por defecto, o se redirige a formulairo de cambio de contraseña
                if ( $this->input->get('dpw') == 1 ) { $destino = 'usuarios/cambio_dpw'; }
            
            redirect($destino);
            
        } else {
            //$this->login();
            $this->mantenimiento();
        }
    }
    
    /**
     * Formulario de login, iniciar sesión con usuario y contraseña
     */
    function login()
    {
        if ( $this->session->userdata('logged') )
        {
            redirect('app/index');
        } else {
            $data['head_title'] = 'En Línea Editores :: Bienvenidos';
            $data['view_a'] = 'app/login_v';
            $this->load->view('templates/apanel3/start_v', $data);
        }
    }
    
    /**
     * Proveniente de app/login, se valida los datos de usuario
     * verificando nombre de usuario y contraseña
     */
    function validar_login()
    {
        //Validación de login
            $userlogin = $this->input->post('username');
            $password = $this->input->post('password');
            
            $data = $this->Login_model->validar_login($userlogin, $password);
            
            if ( $data['status'] )
            {
                $this->Login_model->crear_sesion($userlogin, TRUE);

                //Verificar si tiene contraseña por defecto
                $default_password = $this->App_model->valor_opcion(10);
                $data['tiene_dpw'] = 0;
                if ( $password == $default_password  )
                {
                    $data['tiene_dpw'] = 1;
                }

                // ** EJECUCIÓN DE CRON JOBS 2019-06-19 **
                $data['cron_id'] = $this->App_model->cron_jobs();
            }
            
        //Respuesta
            $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($data));  
    }

    function inicio()
    {
        $data['vista_a'] = 'app/inicio_v';
        $data['titulo_pagina'] = NOMBRE_APP;
        $this->load->view(PTL_ADMIN, $data);
    }
    
    function logout()
    {
        //Editar, evento de inicio de sesión
            if ( strlen($this->session->userdata('login_id')) > 0 ) 
            {
                $row_evento = $this->Pcrn->registro_id('evento', $this->session->userdata('login_id'));

                $registro['fecha_fin'] = date('Y-m-d');
                $registro['hora_fin'] = date('H:i:s');
                $registro['estado'] = 2;    //Cerrado
                $registro['entero_1'] = $this->Pcrn->segundos_lapso($row_evento->creado, date('Y-m-d H:i:s'));

                if ( ! is_null($row_evento) ) 
                {
                    //Si el evento existe
                    $this->Pcrn->guardar('evento', "id = {$row_evento->id}", $registro);
                }
            }
        
        //Destruir sesión existente y redirigir al login, inicio.
            $this->session->sess_destroy();
            redirect('app/login');
    }
    
    
    
//FUNCIONES DE CONTROL DE CONTENIDO
//---------------------------------------------------------------------------------------------------
    
    function no_permitido()
    {
        $data['head_title'] = 'Acceso No Permitido';
        $data['view_a'] = 'app/no_permitido_v';
        $this->load->view('templates/apanel3/start_v', $data);
    }
    
    function en_construccion()
    {
        $data['titulo_pagina'] = 'Contenido en construcción';
        $data['vista_a'] = 'app/en_construccion_v';
        $this->load->view('plantilla_apanel/plantilla', $data);
    }
    
    function mantenimiento()
    {
        $data['head_title'] = NOMBRE_APP;
        $data['view_a'] = 'app/mantenimiento_v';
        $this->load->view('app/mantenimiento_v', $data);
    }
    
// BÚSQUEDAS Y REDIRECCIONAMIENTO
//-----------------------------------------------------------------------------
    
    /**
     * POST REDIRECT
     * 2017-07-07
     * Toma los datos de POST, los establece en formato GET para url y redirecciona
     * a una controlador y función definidos.
     * 
     * @param type $controlador
     * @param type $funcion
     */
    function buscar($controlador, $funcion)
    {
        $this->load->model('Busqueda_model');
        $busqueda_str = $this->Busqueda_model->busqueda_str();
        redirect("{$controlador}/{$funcion}/?{$busqueda_str}");
    }

    
//---------------------------------------------------------------------------------------------------
//FUNCIONES DE PRUEBAS

    function demo($page = 'biblioteca')
    {
        $data['head_title'] = 'Demo: ' . $page;
        $data['view_a'] = 'app/demo/' . $page . '_v';
        $this->load->view(TPL_ADMIN, $data);
    }
    
//AJAX GENERALES
//---------------------------------------------------------------------------------------------------
    
    /**
     * Ajax
     * Devuelve slug único
     */
    function slug_unico()
    {
        $texto = $this->input->post('texto');
        $tabla = $this->input->post('tabla');
        $campo = $this->input->post('campo');
        
        $slug_unico = $this->Pcrn->slug_unico($texto, $tabla, $campo);
        
        echo $slug_unico;
    }
    
    /**
     * AJAX
     * Elimina un registro de la tabla meta
     */
    function eliminar_meta()
    {
        $meta_id = $this->input->post('meta_id');
        $row_meta = $this->App_model->eliminar_meta($meta_id);
        
        $this->output
        ->set_content_type('application/json')
        ->set_output(json_encode($row_meta->id));
        
    }
    
//---------------------------------------------------------------------------------------------------
//AUTOCOMPLETAR
    
    function autocomplete()
    {
        $data['titulo_pagina'] = 'Autocomplete';
        $data['vista_a'] = 'app/autocomplete_v';
        $this->load->view(PTL_ADMIN, $data);
    }
    
    function arr_elementos_ajax($tabla)
    {
        $this->load->model('Busqueda_model');
        $busqueda = $this->Busqueda_model->busqueda_array();
        $busqueda['q'] = $this->input->post('query');
        
        switch ($tabla) 
        {
            case 'usuario':
                $this->load->model('Usuario_model');
                $resultados = $this->Usuario_model->autocompletar($busqueda);
                break;
            case 'tema':
                $this->load->model('Tema_model');
                $resultados = $this->Tema_model->autocompletar($busqueda);
                break;

            default:
                break;
        }
        
        $arr_elementos = $resultados->result_array();
        
        $this->output->set_content_type('application/json')->set_output(json_encode($arr_elementos));
    }

    function calendar()
    {
        $data['view_a'] = 'app/calendar_v';        
        $data['head_title'] = 'Calendar';
        $this->load->view(TPL_ADMIN, $data);
    }
}