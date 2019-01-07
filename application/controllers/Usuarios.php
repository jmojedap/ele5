<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Usuarios extends CI_Controller{
    
    function __construct() {
        parent::__construct();
        
        $this->load->model('Usuario_model');
        
        //Para definir hora local
        date_default_timezone_set("America/Bogota");
    }
    
    function index()
    {
        $this->explorar();
    }
    
//---------------------------------------------------------------------------------------------------
//GROCERY CRUD PARA USUARIOS
    
    function explorar()
    {   
        $this->load->model('Busqueda_model');
        $this->load->helper('text');
        
        //Datos de consulta, construyendo array de búsqueda
            $busqueda = $this->Busqueda_model->busqueda_array();
            $busqueda_str = $this->Busqueda_model->busqueda_str();
            $resultados_total = $this->Usuario_model->buscar($busqueda); //Para calcular el total de resultados
        
        //Paginación
            $this->load->library('pagination');
            $config = $this->App_model->config_paginacion(2);
            $config['base_url'] = base_url() . "usuarios/explorar/?{$busqueda_str}";
            $config['total_rows'] = $resultados_total->num_rows();
            $this->pagination->initialize($config);
            
        //Generar resultados para mostrar
            $offset = $this->input->get('per_page');
            $resultados = $this->Usuario_model->buscar($busqueda, $config['per_page'], $offset);
            
        //Instituciones
            $this->db->order_by('nombre_institucion', 'ASC');
            $instituciones = $this->db->get('institucion');
        
        //Variables para vista
            $data['cant_resultados'] = $config['total_rows'];
            $data['busqueda'] = $busqueda;
            $data['busqueda_str'] = $busqueda_str;
            $data['resultados'] = $resultados;
            $data['instituciones'] = $instituciones;
        
        //Solicitar vista
            $data['titulo_pagina'] = 'Usuarios';
            $data['subtitulo_pagina'] = number_format($data['cant_resultados'],0,',', '.');
            $data['vista_a'] = 'usuarios/explorar_v';
            $data['vista_menu'] = 'usuarios/explorar_menu_v';
            $this->load->view(PTL_ADMIN, $data);
    }
    
    /**
     * Exporta el resultado de la búsqueda a un archivo de Excel
     */
    function exportar()
    {
        set_time_limit(120);    //120 segundos, 2 minutos para el proceso
        //Cargando
            $this->load->model('Busqueda_model');
            $this->load->model('Pcrn_excel');
        
        //Datos de consulta, construyendo array de búsqueda
            $busqueda = $this->Busqueda_model->busqueda_array();
            $busqueda_str = $this->Busqueda_model->busqueda_str();
            $resultados_total = $this->Usuario_model->buscar($busqueda); //Para calcular el total de resultados
        
            if ( $resultados_total->num_rows() <= MAX_REG_EXPORT )
            {
                //Preparar datos
                    $datos['nombre_hoja'] = 'Usuarios';
                    $datos['query'] = $resultados_total;

                //Preparar archivo
                    $objWriter = $this->Pcrn_excel->archivo_query($datos);

                $data['objWriter'] = $objWriter;
                $data['nombre_archivo'] = date('Ymd_His'). '_usuarios'; //save our workbook as this file name

                $this->load->view('app/descargar_phpexcel_v', $data);
            } else {
                $data['titulo_pagina'] = 'Plataforma Enlace';
                $data['mensaje'] = "El número de registros es de {$resultados_total->num_rows()}. El máximo permitido es de " . MAX_REG_EXPORT . " registros. Puede filtrar los datos por algún criterio para poder exportarlos.";
                $data['link_volver'] = "usuarios/explorar/?{$busqueda_str}";
                $data['vista_a'] = 'app/mensaje_v';
                
                $this->load->view(PTL_ADMIN, $data);
            }
    }
    
    /**
     * AJAX
     * Eliminar un grupo de registros seleccionados
     */
    function eliminar_seleccionados()
    {
        $str_seleccionados = $this->input->post('seleccionados');
        
        $seleccionados = explode('-', $str_seleccionados);
        
        foreach ( $seleccionados as $elemento_id ) {
            $this->Usuario_model->eliminar($elemento_id);
        }
        
        echo count($seleccionados);
    }
    
    function nuevo()
    {
        
        $tipo = $this->uri->segment(3);
        
        //Render del grocery crud
            if ( $tipo == 'estudiante' ) {
                $output = $this->Usuario_model->crud_estudiantes();
            } elseif ( $tipo == 'institucional' ) {
                $output = $this->Usuario_model->crud_institucionales();
            } elseif ( $tipo == 'interno' ) {
                $output = $this->Usuario_model->crud_internos();
            }
            
        
        //Head includes específicos para la página
            $head_includes[] = 'grocery_crud';
            $data['head_includes'] = $head_includes;
            
        //Array data espefícicas
            $data['tipo'] = $tipo;
            $data['titulo_pagina'] = 'Usuarios';
            $data['subtitulo_pagina'] = 'Nuevo';
            $data['vista_a'] = 'usuarios/nuevo_v';
            //$data['vista_a'] = 'app/gc_v';
        
        $output = array_merge($data,(array)$output);
        
        $this->load->view(PTL_ADMIN, $output);
    }
    
    function editar()
    {
        //Cargando datos básicos
            $usuario_id = $this->uri->segment(4);
            $data = $this->Usuario_model->basico($usuario_id);
            
        //Render del grocery crud
            $row = $data['row'];
            //Render del grocery crud
            if ( $row->rol_id == 6 ) {
                $gc_output = $this->Usuario_model->crud_estudiantes();
            } elseif ( in_array($row->rol_id, array(3,4,5)) ) {
                $gc_output = $this->Usuario_model->crud_institucionales();
            } else  {
                $gc_output = $this->Usuario_model->crud_internos();
            }
            
        //Definir vista según permiso de edición
            $vista_b = 'comunes/gc_v';
            if ( ! $data['editable'] ) { $vista_b = 'app/no_permitido_v'; }
            
        //Solicitar vista
            $data['subtitulo_pagina'] = 'Editar';
            $data['vista_b'] = $vista_b;
            $output = array_merge($data,(array)$gc_output);
            $this->load->view(PTL_ADMIN, $output);
    }
    
    /**
     * Vista para editar datos personales del usuario en sesión.
     */
    function editarme()
    {
        //Cargando datos básicos
            $usuario_id = $this->uri->segment(4);
            $data = $this->Usuario_model->basico($usuario_id);
            
        //Render del grocery crud
            $gc_output = $this->Usuario_model->crud_editarme();
            
        //Definir vista según permiso de edición
            $vista_b = 'comunes/gc_v';
            if ( $data['editable'] ) 
            {
                //Solicitar vista
                    $data['subtitulo_pagina'] = 'Editar mi perfil';
                    $data['vista_b'] = $vista_b;
                    $output = array_merge($data,(array)$gc_output);
                    $this->load->view(PTL_ADMIN, $output);
            } else {
                //No se puede editar, se redirige
                redirect("usuarios/contrasena/");
            }
    }
    
    function eliminar($usuario_id)
    {
        
        $this->Usuario_model->eliminar($usuario_id);
        
        $this->load->model('Busqueda_model');
        $busqueda_str = $this->Busqueda_model->busqueda_str();
        
        redirect("usuarios/explorar/?{$busqueda_str}");
    }

// IMPORTAR
//-----------------------------------------------------------------------------
    
    /**
     * Mostrar formulario de importación de estudiantes con archivo MS Excel.
     * El resultado del formulario se envía a 'usuarios/importar_estudiantes_e'
     * 
     */
    function importar_estudiantes()
    {
        //Iniciales
            $nombre_archivo = '30_formato_cargue_estudiantes_general.xlsx';
            $parrafos_ayuda = array(
                'Si la casilla "apellidos" (columna B) se encuentra vacía el estudiante no será creado.'
            );
        
        //Instructivo
            $data['titulo_ayuda'] = '¿Cómo importar estudiantes?';
            $data['nota_ayuda'] = 'Se importarán estudiantes';
            $data['parrafos_ayuda'] = $parrafos_ayuda;
        
        //Variables específicas
            $data['destino_form'] = "usuarios/importar_estudiantes_e/";
            $data['nombre_archivo'] = $nombre_archivo;
            $data['nombre_hoja'] = 'usuarios';
            $data['url_archivo'] = base_url("assets/formatos_cargue/{$nombre_archivo}");
            
        //Variables generales
            //$data['ayuda_id'] = 97;
            $data['titulo_pagina'] = 'Usuarios';
            $data['subtitulo_pagina'] = 'Importar estudiantes';
            $data['vista_a'] = 'comunes/importar_v';
            $data['vista_menu'] = 'usuarios/explorar_menu_v';
            $data['vista_submenu'] = 'usuarios/importar_menu_v';
        
        $this->load->view(PTL_ADMIN, $data);
    }
    
    /**
     * Importar estudiantes, (e) ejecutar.
     */
    function importar_estudiantes_e()
    {
        //Proceso
            $this->load->model('Pcrn_excel');
            $letra_columna = 'G';   //Última columna con datos
            
            $resultado = $this->Pcrn_excel->array_hoja_default($letra_columna);

            $res_importacion = array('no_importados' => null, 'importados' => '');
            if ( $resultado['valido'] )
            {
                $this->load->model('Institucion_model');
                $res_importacion = $this->Usuario_model->importar_estudiantes($resultado['array_hoja']);
            }
        
        //Cargue de variables
            $data['valido'] = $resultado['valido'];
            $data['mensaje'] = $resultado['mensaje'];
            $data['array_hoja'] = $resultado['array_hoja'];
            $data['nombre_hoja'] = $this->input->post('nombre_hoja');
            $data['no_importados'] = $res_importacion['no_importados'];
            $data['importados'] = $res_importacion['importados'];
            $data['destino_volver'] = "usuarios/explorar/";
            $data['vista_importados'] = 'instituciones/importar_estudiantes_r_v';
        
        //Cargar vista
            $data['titulo_pagina'] = 'Usuarios';
            $data['subtitulo_pagina'] = 'Resultado importación';
            $data['vista_a'] = 'comunes/resultado_importacion_v';
            $data['vista_menu'] = 'usuarios/explorar_menu_v';
            $data['vista_submenu'] = 'usuarios/importar_menu_v';
            $this->load->view(PTL_ADMIN, $data);
    }
    
    
//GESTIÓN DE CUENTAS
//---------------------------------------------------------------------------------------------------
    
    function registrado()
    {
        $usuario_id = $this->session->userdata('usuario_id');
        $row_usuario = $this->Pcrn->registro_id('usuario', $usuario_id);
        
        $mensaje = 'Su registro de usuario fue existoso. ';
        $mensaje .= 'Revise su correo electrónico, enviamos un mensaje con un Link para activar su cuenta. ';
        $mensaje .= 'Es posible que el mensaje llegue a la carpeta de CORREO NO DESEADO.';
        
        $data['mensaje'] = $mensaje;
        $data['clase_alerta'] = 'alert-info';
        
        //Solicitar vista
            $data['titulo_pagina'] = 'Usuario registrado';
            $data['subtitulo_pagina'] = $row_usuario->nombre . ' ' . $row_usuario->apellidos;
            $data['vista_a'] = 'app/mensaje_v';
            $this->load->view(PTL_ADMIN, $data);
    }
    
    function enviar_email($usuario_id = 2)
    {
        $this->Usuario_model->email_activacion($usuario_id);
    }
    
    function activar($cod_activacion, $tipo_activacion = 'activar')
    {
        $this->load->model('Esp');
        $row_usuario = $this->Pcrn->registro('usuario', "cod_activacion = '{$cod_activacion}'");        
        
        //Variables
            $data['destino_form'] = "usuarios/activar_e/{$this->uri->segment(3)}";
            $data['cod_activacion'] = $cod_activacion;
            $data['tipo_activacion'] = $tipo_activacion;
            $data['row'] = $row_usuario;
            $data['vista_a'] = 'usuarios/activar_v';
            $data['titulo_pagina'] = "Cuenta de {$row_usuario->nombre}";
            
        //Evaluar condiciones
            $condiciones = 0;
            if ( ! is_null($row_usuario) ) { $condiciones++; }
            if ( $this->session->userdata('logged') != TRUE ) { $condiciones++; }
        
        if ( $condiciones == 2 ) {
            $this->load->view('p_apanel2/plantilla_vacia_v', $data);
        } else {
            redirect('app/no_permitido');
        }
    }
    
    /**
     * Activar usuario
     * @param type $cod_activacion
     */
    function activar_ajax($cod_activacion)
    {
        
        $usuario_id = 0;
        $row_usuario = $this->Usuario_model->row_activacion($cod_activacion);
        
        if ( ! is_null($row_usuario) ) 
        {
            $this->Usuario_model->activar($row_usuario->id);

            $this->load->model('Esp');
            $this->Esp->crear_sesion($row_usuario, 1);
            $usuario_id = $row_usuario->id;
        }
        
        echo $usuario_id;
    }
    
    function activar_e($cod_activacion)
    {
        //$this->output->enable_profiler(TRUE);
        $validar_contrasenas = $this->Usuario_model->validar_contrasenas();
        
        if ( $validar_contrasenas ) {
            $row_usuario = $this->Usuario_model->activar($cod_activacion);

            $this->load->model('Esp');
            $this->Esp->crear_sesion($row_usuario, 1);
            redirect("usuarios/editarme/edit/{$row_usuario->id}");
        } else {
            $this->activar($cod_activacion);
        }
    }
    
    function test_email($usuario_id, $tipo_activacion = 'activar')
    {
        $row_usuario = $this->Pcrn->registro_id('usuario', $usuario_id);
        $data['row_usuario'] = $row_usuario ;
        $data['tipo_activacion'] = $tipo_activacion;
        
        $this->load->view('usuarios/email_activacion_v', $data);
    
    }
    
//ACTIVIDAD Y NOTICIAS
//---------------------------------------------------------------------------------------------------
    
    function actividad($usuario_id)
    {
        //Cargue
        //$this->output->enable_profiler(TRUE);
        $this->load->model('Evento_model');
        $this->load->model('Busqueda_model');
        
        $data = $this->Usuario_model->basico($usuario_id);
    
        $busqueda = $this->Busqueda_model->busqueda_array();
        $busqueda_str = $this->Busqueda_model->busqueda_str();
        
        //Filtros de eventos
            $filtros['interno'] = 'g1';
            $filtros['institucional'] = 'g2';
            $filtros['estudiante'] = 'g1';
            $srol = $this->session->userdata('srol');
        
            $condicion_eventos = 'categoria_id = 13 AND filtro LIKE "%-' . $filtros[$srol] . '-%"';
            
        //Cantidad de noticias para mostrar
            $limit = 20;
        
        //Variables
            $data['limit'] = $limit;
            $data['noticias'] = $this->Evento_model->noticias_usuario($usuario_id, $busqueda, $limit);
            $data['busqueda'] = $busqueda;
            $data['busqueda_str'] = $busqueda_str;
            //$data['config_form'] = $this->Evento_model->config_form_publicacion();
            $data['areas'] = $this->App_model->areas('item_grupo = 1');
            $data['tipos'] = $this->db->get_where('item', $condicion_eventos);
            $data['grupos'] = $this->Usuario_model->grupos_usuario($this->session->userdata('usuario_id'));
            $data['destino_form'] = 'eventos/crear_publicacion';
            $data['destino_filtros'] = "usuarios/actividad/{$usuario_id}/";
            $data['url_mas'] = base_url() . 'usuarios/mas_actividad/' . $usuario_id . '/';
        
        //Variables vista
        $data['vista_b'] = 'eventos/noticias/noticias_usuario_v';
        $this->load->view(PTL_ADMIN, $data);
        
    }
    
    /**
     * AJAX, envía un objeto JSON con el html de activida adicionales para mostrarse
     * al final de la vista de actividad cuando el usuario hace clic en el botón [Más]
     * 
     * @param type $limit
     * @param type $offset
     */
    function mas_actividad($usuario_id, $limit, $offset)
    {
        
        $this->load->model('Evento_model');
        $this->load->model('Busqueda_model');
    
        $busqueda = $this->Busqueda_model->busqueda_array();
        
        $noticias = $this->Evento_model->noticias_usuario($usuario_id, $busqueda, $limit, $offset);
        
        $data['noticias'] = $noticias;
        
        $html = $this->load->view('eventos/noticias/listado_noticias_p_v', $data, TRUE);
        
        $respuesta['html'] = $html;
        $respuesta['cant_noticias'] = $noticias->num_rows();
        
        $this->output
        ->set_content_type('application/json')
        ->set_output(json_encode($respuesta));
    }
    
//GESTIÓN DE USUARIOS
//---------------------------------------------------------------------------------------------------
    
    // Cambio de contraseña de cada usuario, el que ha iniciado sesión
    function contrasena()
    {
        $data = $this->Usuario_model->basico($this->session->userdata('usuario_id'));
        
        //Variables
            $data['usuario_id_cambio'] = $this->session->userdata('usuario_id');
            $data['destino_form'] = 'usuarios/contrasena_e';
        
        //Solicitar vista
            $data['subtitulo_pagina'] = 'Cambio de contraseña';
            $data['vista_b'] = 'usuarios/contrasena_v';
            $this->load->view(PTL_ADMIN, $data);
        
    }
    
    /**
     * AJAX JSON
     * Ejecuta el proceso de cambio de contraseña
     */
    function contrasena_e()
    {
        
        $this->load->model('Login_model');
        $condiciones = 0;
        $row_usuario = $this->Pcrn->registro_id('usuario', $this->input->post('id'));
        
        //Valores iniciales para el resultado del proceso
            $resultado = array('ejecutado' => 0, 'mensaje' => '');
        
        //Verificar contraseña actual
            $validar_pw = $this->Login_model->validar_password($row_usuario->username, $this->input->post('password_actual'));
            if ( $validar_pw['ejecutado'] ) {
                $condiciones++;
            } else {
                $resultado['mensaje'] = 'La contraseña actual es incorrecta. ';
            }
        
        //Verificar que contraseña nueva coincida con la confirmación
            if ( $this->input->post('password') == $this->input->post('passconf') ) {
                $condiciones++;
            } else {
                $resultado['mensaje'] .= 'Las contraseña de confirmación no coincide.';
            }
        
        //Verificar condiciones necesarias
            if ( $condiciones == 2 )
            {
                $this->Usuario_model->cambiar_contrasena($row_usuario->id, $this->input->post('password'));
                $resultado['ejecutado'] = 1;
                $resultado['mensaje'] = 'La contraseña se cambió exitosamente.';
            }
        
        $this->output
        ->set_content_type('application/json')
        ->set_output(json_encode($resultado));
        
    }
    
    /**
     * AJAX Cambia el estado de activación de un usuario a un valor específico
     * 
     * @param type $usuario_id
     * @param type $valor
     * @param type $rapido 
     */
    function cambiar_activacion($usuario_id, $valor)
    {
        $this->Usuario_model->cambiar_activacion($usuario_id, $valor);
        
        $this->output
        ->set_content_type('application/json')
        ->set_output(json_encode($valor));
    }
    
    /**
     * AJAX Alterna el estado de pago de un usuario, entre verdadero y falso
     * 
     * @param type $usuario_id
     * @param type $valor
     * @param type $rapido 
     */
    function alternar_activacion($usuario_id)
    {
        $valor_actual = $this->Pcrn->campo_id('usuario', $usuario_id, 'estado');
        $valor_nuevo = $this->Pcrn->alternar($valor_actual);
        $this->Usuario_model->cambiar_activacion($usuario_id, $valor_nuevo);
        
        $this->output
            ->set_content_type('application/json')
            ->set_output($valor_nuevo);
    }
    
    /**
     * AJAX Cambia el estado de pago de un usuario
     * 
     * @param type $usuario_id
     * @param type $valor
     * @param type $rapido 
     */
    function alternar_pago($usuario_id)
    {
        $nuevo_valor = $this->Pcrn->alternar_boleano('usuario', $usuario_id, 'pago');
        
        $estado = 1;
        if ( $nuevo_valor == 0 ) { $estado = 0; } //No ha pagado, se cambia a inactivo
        
        $this->Usuario_model->cambiar_activacion($usuario_id, $estado);
        
        $arr_resultados['pago'] = $nuevo_valor;
        $arr_resultados['estado'] = $estado;
        
        $resultados = json_encode($arr_resultados);
        
        $this->output->set_content_type('application/json');
        $this->output->set_output($resultados);
    }
    
    /**
     * AJAX, devuelve un valor de username sugerido disponible, dados los nombres y apellidos
     */
    function username()
    {
        $nombre = $this->input->post('nombre');
        $apellidos = $this->input->post('apellidos');
        $username = $this->Usuario_model->generar_username($nombre, $apellidos);
        echo $username;
    }
    
    /**
     * AJAX, Cambia el valor de la contraseña de un usuario al valor definido por defecto.
     * 
     * @param type $usuario_id
     * @param type $rapido 
     */
    function restaurar_contrasena($usuario_id)
    {
        $this->Usuario_model->restaurar_contrasena($usuario_id);
        echo 1;
    }
    
    /* Viene del formulario que muestra el controlador usuarios/cambiar_contrasena
    * Solo tienen acceso los administradores (rol_id = 1)
    */
    function guardar_contrasena()
    {
        
        $this->load->library('form_validation');
        
        //Reglas
        $this->form_validation->set_rules('password', 'Nueva contraseña', 'trim|required|alpha_numeric|min_length[8]|md5');
        $this->form_validation->set_rules('passconf', 'Confirmación de la nueva contraseña', 'trim|required|matches[password]|md5');
        
        //Mensajes de validación
        $this->form_validation->set_message('required', "El campo %s es requerido");
        $this->form_validation->set_message('alpha_numeric', "%s: sólo permite letras y números");
        $this->form_validation->set_message('min_length', 'La contraseña debe tener al menos 8 caracteres');
        $this->form_validation->set_message('matches', "El valor de las contraseñas no coincide");
        
        
        if ($this->form_validation->run() == FALSE){
            //La validación falla, retornar al formulario
            $usuario_id = $this->input->post('id');
            $this->cambiar_contrasena($usuario_id);
        } else {
            //La validación es exitosa, se cambia la contraseña
            $password = $this->input->post('password');
            $usuario_id = $this->input->post('id');
            $resultado = $this->Usuario_model->cambiar_contrasena($usuario_id, $password);
            
            $this->session->set_flashdata('resultado', $resultado);
            
            redirect("usuarios/cambiar_contrasena/{$usuario_id}");
        }
        
    }
    
    function validar_contrasena()
    {   
        
        $this->load->library('form_validation');
        
        //Reglas
        $this->form_validation->set_rules('password_actual', 'Contraseña actual', 'trim|md5|required|callback__password_check');
        $this->form_validation->set_rules('password', 'Nueva contraseña', 'trim|required|alpha_numeric|min_length[8]');
        $this->form_validation->set_rules('passconf', 'Confirmación de la nueva contraseña', 'trim|required|matches[password]');
        
        //Mensajes de validación
        $this->form_validation->set_message('required', "El campo %s es requerido");
        $this->form_validation->set_message('alpha_numeric', "%s: sólo permite letras y números");
        $this->form_validation->set_message('matches', "El valor de las contraseñas no coincide");
        $this->form_validation->set_message('min_length', 'El valor escrito en %s es demasiado corto');
        $this->form_validation->set_message('_password_check', "La %s no es correcta");
        
        if ($this->form_validation->run() == FALSE){
            //La validación falla, retornar al formulario
            $this->contrasena();
        } else {
            //La validación es exitosa, se cambia la contraseña
            $password = md5($this->input->post('password'));
            $usuario_id = $this->input->post('id');
            $resultado = $this->Usuario_model->cambiar_contrasena($usuario_id, $password);
            $this->contrasena($resultado);
        }
        
    }
    
//RESTAURACIÓN DE CUENTAS
//---------------------------------------------------------------------------------------------------
    
    /**
     * Formulario para restaurar contraseña o reactivar cuenta
     * se ingresa con nombre de usuario y contraseña
     */
    function restaurar($resultado = NULL)
    {
        
        if ( $this->session->userdata('logged') )
        {
            redirect('app');
        } else {
            
            $data['destino_form'] = 'usuarios/restaurar_e';
            
            $data['titulo_pagina'] = 'Restauración de contraseña';
            $data['vista_a'] = 'usuarios/restaurar_v';
            $data['resultado'] = $resultado;
            $this->load->view('templates/apanel3/start_v', $data);
        }
    }
    
    /**
     * Formulario para restaurar contraseña o reactivar cuenta
     * se ingresa con nombre de usuario y contraseña
     */
    function restaurar_e()
    {
        $email = $this->input->post('email');
        $resultado = $this->Usuario_model->restaurar($email);
        
        if ( $resultado == 1 ){
            //El correo se encontró y se envío email de recuperación de cuenta
            redirect('usuarios/restaurar/enviado');
        } else {
            //El correo no existe en la base de datos
            redirect('usuarios/restaurar/no_encontrado');
        }
    }
    
    function test_envio($usuario_id)
    {

        if ( $usuario_id == 3484 ) {
            $this->load->library('email');
            $config['mailtype'] = 'html';

            //$row_usuario = $this->Pcrn->registro_id('usuario', $usuario_id);

            $this->email->initialize($config);
            $this->email->from('info@plataformaenlinea.com', 'Plataforma en Línea');
            $this->email->to('jmojedap@gmail.com');
            $this->email->message('Este es el contenido del mensaje');
            $this->email->subject('Subject del mensaje');
        
            $this->email->send();   //Enviar
            
            echo 'email enviado';
        } else {
            echo 'email no enviado';
        }
    }
    
    /**
     * Cambiar Default PassWord
     * Función requerida si el usuario tiene como contraseña la contraseña
     * establecida por defecto
     */
    function cambiar_dpw()
    {
        $data = $this->Usuario_model->basico($this->session->userdata('usuario_id'));
        
        //Variables
            $data['usuario_id_cambio'] = $this->session->userdata('usuario_id');
            $data['destino_form'] = 'usuarios/cambiar_dpw_e';
        
        //Solicitar vista
            $data['titulo_pagina'] = 'Cambio de contraseña';
            $data['vista_a'] = 'usuarios/cambiar_dpw_v';
            $this->load->view('p_apanel2/plantilla_vacia_v', $data);
    }
    
    /**
     * Cambiar Default PassWord Ejecutar
     * Ejecuta el proceso de cambio de contraseña por defecto
     */
    function cambiar_dpw_e()
    {
        
        $this->load->model('Esp');
        $condiciones = 0;
        $destino = 'usuarios/cambiar_dpw';
        $row_usuario = $this->Pcrn->registro_id('usuario', $this->session->userdata('usuario_id'));
        
        //Valores iniciales para el resultado del proceso
            $resultado['mensaje'] = '';
            $resultado['clase'] = 'alert-info';
            
        //Verificar que la contraseña nueva no sea la contraseña por defecto
            $dpw = $this->App_model->valor_opcion(10);
            if ( $this->input->post('password') != $dpw ) {
                $condiciones++;
            } else {
                $resultado['mensaje'] .= 'La contraseña nueva no puede ser igual a la contraseña por defecto. ';
                $resultado['clase'] = 'alert-danger';
            }
        
        //Verificar que contraseña nueva coincida con la confirmación
            if ( $this->input->post('password') == $this->input->post('passconf') ) {
                $condiciones++;
            } else {
                $resultado['mensaje'] .= 'La contraseña de confirmación no coincide.';
                $resultado['clase'] = 'alert-danger';
            }
        
        //Verificar condiciones necesarias
            if ( $condiciones == 2 )
            {
                $this->Usuario_model->cambiar_contrasena($row_usuario->id, $this->input->post('password'));
                $this->Usuario_model->marcar_pagado($row_usuario->id);  //2016-11-19, Al cambiar contraseña se considera que ya pagó

                $resultado['clase'] = 'alert-success';
                $resultado['mensaje'] = 'La contraseña se cambió exitosamente.';
                
                $destino = 'app/index';
            }
        
        $this->session->set_flashdata('resultado', $resultado);
        redirect($destino);
        
    }
    
//HERRAMIENTAS DE USUARIOS
//---------------------------------------------------------------------------------------------------

    
    /**
     * Pantalla de bienvenida para los estudiantes
     * Actualizada 2018-08-21
     *  
     */
    function biblioteca()
    {
        if ($this->input->get('profiler'))
        {
            $this->output->enable_profiler(TRUE);
        }
        
        //Flipbooks
            $data['funcion_flipbook'] = 'abrir_flipbook';
            $data['flipbooks'] = $this->session->userdata('arr_flipbooks');

        //Cuestionarios
            $data['cuestionarios'] = $this->session->userdata('arr_cuestionarios');
            
        //Contenidos Acompañamiento Pedagógico
            //$data['contenidos_ap'] = $this->Usuario_model->contenidos_ap();
            
        $vista_a = 'usuarios/biblioteca_v';
        if ( $this->session->userdata('rol_id') != 6 )
        {
            $vista_a = 'usuarios/biblioteca_profesor_v';
        }
        
        //Array data
        $data['titulo_pagina'] = 'Biblioteca virtual';
        $data['vista_a'] = $vista_a;
        
        $this->load->view(PTL_ADMIN, $data);
    }
    
    
//MURO GRUPOS
//---------------------------------------------------------------------------------------------------
    
    function grupos($usuario_id)
    {
        //Cargando datos básicos (_basico)
        $data = $this->Usuario_model->basico($usuario_id);
        
        //Variables
        $data['grupos'] = $this->Usuario_model->grupos_usuario($usuario_id);
        
        //Solicitar vista
        $data['subtitulo_pagina'] = 'Grupos';
        $data['vista_b'] = 'usuarios/grupos_v';
        $this->load->view(PTL_ADMIN, $data);
    }
    
    /**
     * Listado de grupos que tiene asignado un profesor, en la tabla
     * grupo_profesor
     * 
     * @param type $usuario_id
     */
    function grupos_profesor($usuario_id)
    {
        //Cargando datos básicos
            $data = $this->Usuario_model->basico($usuario_id);
        
        //Variables
            $data['grupos'] = $this->Usuario_model->grupos_profesor($usuario_id);
        
        //Solicitar vista
            $data['subtitulo_pagina'] = 'Grupos asignados';
            $data['vista_b'] = 'usuarios/grupos_profesor_v';
            $this->load->view(PTL_ADMIN, $data);
    }
    
//---------------------------------------------------------------------------------------------------
//QUICES
    
    /**
     * Estado de respuesta de los quices por parte de los usuarios
     * 
     * @param type $usuario_id
     * @param type $flipbook_id
     */
    function quices($usuario_id, $flipbook_id = 0)
    {
        if ( $this->input->get('profiler') ) 
        {
            $this->output->enable_profiler(TRUE);
        }
        
        $this->load->model('Flipbook_model');
        $this->load->model('Tema_model');
        
        //Si es estudiante (rol 6), solo puede ver su perfil
        if ( $this->session->userdata('rol_id') == 6 ) { $usuario_id = $this->session->userdata('usuario_id'); }
        
        $data = $this->Usuario_model->basico($usuario_id);
        
        $flipbooks = $this->Usuario_model->flipbooks($data['row']);
        if ( $flipbooks->num_rows() > 0 && $flipbook_id == 0 ) { $flipbook_id = $flipbooks->row()->flipbook_id; }
        
        //Variables $data
            $data['flipbook_id'] = $flipbook_id;
            $data['subseccion'] = 'listado';
            $data['flipbooks'] = $flipbooks;
            $data['arr_estado_quiz'] = $this->Usuario_model->arr_estado_quiz($usuario_id);
            
            $data['quices'] = $this->Flipbook_model->quices_total($flipbook_id);
            
        //Temas relacionados, con temas tipo UT
            //$relacionados = $this->Flipbook_model->arr_relacionados($flipbook_id);
            //$data['subquices'] = $this->Flipbook_model->subquices($relacionados);
        
        //Solicitar vista
            $data['subtitulo_pagina'] = 'Evidencias';
            $data['vista_b'] = 'usuarios/quices_v';
            $this->load->view(PTL_ADMIN, $data);
            //print_r($data['quices']);
    }
    
//---------------------------------------------------------------------------------------------------
//GESTIÓN DE RECURSOS DE USUARIOS    
    
    /**
     * Mostrar los flipbooks que un usuario tiene asignado
     * 
     * @param type $usuario_id 
     */
    function flipbooks($usuario_id)
    {
        $data = $this->Usuario_model->basico($usuario_id);
        
        //Variables
        if ( $data['row']->rol_id == 6 )
        {   //Es estudiante
            $data['flipbooks'] = $this->Usuario_model->flipbooks($data['row']);
        } else {
            $data['flipbooks'] = $this->Usuario_model->flipbooks_profesor($usuario_id);
        }
        
        //Solicitar vista
        $data['subtitulo_pagina'] = 'Contenidos';
        $data['vista_b'] = 'usuarios/flipbooks_v';
        $this->load->view(PTL_ADMIN, $data);
    }
    
    function quitar_flipbook($usuario_id, $flipbook_id)
    {
        //Quitar asignación de flipbook
            $this->db->where('flipbook_id', $flipbook_id);
            $this->db->where('usuario_id', $usuario_id);
            $this->db->delete('usuario_flipbook');
        
        redirect("usuarios/flipbooks/{$usuario_id}");
    }
    
    function anotaciones($usuario_id, $flipbook_id = NULL)
    {
        //Cargando
            $this->load->model('Flipbook_model');
            $this->load->model('Pagina_model');
        
        //Cargando datos básicos
        $data = $this->Usuario_model->basico($usuario_id);
        $flipbooks = $this->Usuario_model->flipbooks($data['row']);
        
        if ( $flipbooks->num_rows() > 0 ){
            $flipbook_id = $this->Pcrn->si_nulo($flipbook_id, $flipbooks->row()->flipbook_id);
        }
       
       //Variables
            $data['flipbook_id'] = $flipbook_id;
            $data['anotaciones'] = $this->Flipbook_model->anotaciones($flipbook_id, $usuario_id);
            $data['flipbooks'] = $flipbooks;
        
        
        //Solicitar vista
        $data['subtitulo_pagina'] = 'Anotaciones';
        $data['vista_b'] = 'usuarios/anotaciones_v';
        $this->load->view(PTL_ADMIN, $data);
    }
    
//CUESTIONARIOS
//---------------------------------------------------------------------------------------------------

    function cuestionarios_new($usuario_id)
    {
        
        //$this->output->enable_profiler(TRUE);
        $this->load->model('Cuestionario_model');
        
        //Cargando datos básicos (_basico)
        if ( $this->session->userdata('rol_id') == 6 ) { $usuario_id = $this->session->userdata('usuario_id'); }
        $data = $this->Usuario_model->basico($usuario_id);
        
        
        
        //Cuestionarios
            /*$fecha_hoy = date('Y-m-d H:i:s');
            $condicion = "('" . $fecha_hoy . "') > fecha_inicio AND ('" . $fecha_hoy . "') < fecha_fin";*/
            $data['cuestionarios'] = $this->Usuario_model->cuestionarios($usuario_id);
            
        
        //Solicitar vista
            $data['vista_b'] = 'usuarios/cuestionarios_n_v';
            $this->load->view(PTL_ADMIN, $data);
    }
    
    function cuestionarios($usuario_id, $pestana = 0)
    {
        
        //$this->output->enable_profiler(TRUE);
        $this->load->model('Cuestionario_model');
        
        //Cargando datos básicos (_basico)
        if ( $this->session->userdata('rol_id') == 6 ) { $usuario_id = $this->session->userdata('usuario_id'); }
        $data = $this->Usuario_model->basico($usuario_id);
            
        //Variables
            $cuestionarios_resp = $this->Cuestionario_model->resumen_usuario($usuario_id);
        
        //Condición
            $fecha_hoy = date('Y-m-d H:i:s');
            $condicion = 'tipo_id IN (2,3,4) AND estado < 3 AND fecha_fin > "' . $fecha_hoy  . '"';  //Sin responder y con fecha límite para responder
        
        //Variables $data
            $data['subseccion'] = 'listado';
            $data['pestana'] = $pestana;
            $data['cuestionarios'] = $this->Usuario_model->cuestionarios($usuario_id, $condicion);
            $data['cuestionarios_resp'] = $cuestionarios_resp;
            $data['externos'] = $this->Usuario_model->cuestionarios($usuario_id, 'estado >= 3 AND tipo_id = 4');
        
        //Solicitar vista
            $data['vista_b'] = 'usuarios/cuestionarios_v';
            $this->load->view(PTL_ADMIN, $data);
    }
    
//---------------------------------------------------------------------------------------------------
//RESULTADOS DE CUESTIONARIOS
    
    
    /**
     * Resultados de un usuario en un cuestionario
     * 
     * @param type $usuario_id
     * @param type $uc_id 
     */
    function resultados($usuario_id, $uc_id)
    {
        $this->load->model('Cuestionario_model');
        
        $data = $this->Usuario_model->basico($usuario_id);
        $row_uc = $this->Pcrn->registro('usuario_cuestionario', "id = {$uc_id}");
        $row_cuestionario = $this->Pcrn->registro('cuestionario', "id = {$row_uc->cuestionario_id}");
            
        //Competencias
            $this->db->where('abreviatura IS NOT NULL');
            if ( ! is_null($row_cuestionario->area_id) ) { $this->db->where('item_grupo', $row_cuestionario->area_id); }
            $this->db->order_by('abreviatura', 'ASC');
            $data['competencias'] = $this->db->get_where('item', 'categoria_id = 4');
        
        //Variables
            $data['row_uc'] = $row_uc;
            $data['row_cuestionario'] = $row_cuestionario;
            
            $data['areas'] = $this->Cuestionario_model->areas($data['row_uc']->cuestionario_id);
            $data['temas'] = $this->Cuestionario_model->temas($data['row_uc']->cuestionario_id);
        
        //Cargando arrays de resultados
            $data['respuestas_cuestionario'] = $this->Usuario_model->respuestas_cuestionario($uc_id);
            $data['res_usuario'] = $this->Cuestionario_model->resultado_usuario($data['row_uc']->id);       //2014-06-02
            $data['res_grupo'] = $this->Cuestionario_model->resultado($data['row_uc']->cuestionario_id, "grupo_id = {$data['row_uc']->grupo_id}");
            //$data['res_institucion'] = $this->Cuestionario_model->resultado($data['row_uc']->cuestionario_id, "institucion_id = {$data['row']->institucion_id}");
            //$data['res_total'] = $this->Cuestionario_model->resultado($data['row_uc']->cuestionario_id, 'institucion_id > 0');
            
        //Rango del estudiante
            $porcentaje = $data['res_usuario']['porcentaje']/100;
            $data['rango_usuario'] = $this->App_model->rango_cuestionarios($porcentaje);
        
        //Solicitar vista
            $data['vista_b'] = 'usuarios/resultados/resultados_v';
            $data['vista_c'] = 'usuarios/resultados/comparativos_v';
            $data['vista_menu'] = 'usuarios/resultados/submenu_v';
            $this->load->view(PTL_ADMIN, $data);
        
    }
    
    /**
     * Resultados de un usuario en un cuestionario
     * 
     * @param type $usuario_id
     * @param type $uc_id 
     */
    function resultados_detalle($usuario_id, $uc_id)
    {
        
        //$this->output->enable_profiler(TRUE);
        
        $this->load->model('Cuestionario_model');
        
        $data = $this->Usuario_model->basico($usuario_id);
        
        //Variables
            $data['row_uc'] = $this->Pcrn->registro('usuario_cuestionario', "id = {$uc_id}");
            $data['row_cuestionario'] = $this->Pcrn->registro('cuestionario', "id = {$data['row_uc']->cuestionario_id}");
            
            //'Areas del cuestionario
            $this->load->model('Cuestionario_model');
            $data['areas'] = $this->Cuestionario_model->areas($data['row_uc']->cuestionario_id);
        
        //Cargando arrays de resultados
            $data['respuestas_cuestionario'] = $this->Usuario_model->respuestas_cuestionario($uc_id);
            $data['res_usuario'] = $this->Cuestionario_model->resultado_usuario($data['row_uc']->id);       //2014-06-02
            $data['res_grupo'] = $this->Cuestionario_model->resultado($data['row_uc']->cuestionario_id, "grupo_id = {$data['row_uc']->grupo_id}");
            $data['res_institucion'] = $this->Cuestionario_model->resultado($data['row_uc']->cuestionario_id, "institucion_id = {$data['row']->institucion_id}");
            $data['res_total'] = $this->Cuestionario_model->resultado($data['row_uc']->cuestionario_id, 'institucion_id > 0');
            
        //Rango del estudiante
            $porcentaje = $data['res_usuario']['porcentaje']/100;
            $data['rango_usuario'] = $this->App_model->rango_cuestionarios($porcentaje);
        
        //Solicitar vista
            $data['vista_b'] = 'usuarios/resultados/resultados_v';
            $data['vista_c'] = 'usuarios/resultados/detalle_v';
            $data['vista_menu'] = 'usuarios/resultados/submenu_v';
            $this->load->view(PTL_ADMIN, $data);
        
    }
    
    /* Muestra los resultados de un usuario en un cuestionario por áreas
    */
    function resultados_area($usuario_id, $uc_id){
        
        //Cargando datos básicos (_basico)
        $data = $this->Usuario_model->basico($usuario_id);
        
        //Head includes específicos para la página
            $head_includes[] = 'highcharts';
            $head_includes[] = 'grafico_area';
            $data['head_includes'] = $head_includes;
        
        
        //Variables
            $data['row_uc'] = $this->Pcrn->registro('usuario_cuestionario', "id = {$uc_id}");
            $data['row_cuestionario'] = $this->Pcrn->registro('cuestionario', "id = {$data['row_uc']->cuestionario_id}");
        
        //Variables para el gráfico
        
            $data['titulo_grafico'] = $data['row']->nombre_apellidos . " - " . $data['row_cuestionario']->nombre_cuestionario;
            
            //Áreas del cuestionario
            $this->load->model('Cuestionario_model');
            $areas = $this->Cuestionario_model->areas($data['row_uc']->cuestionario_id);
            
            //Se carga para cada área, un array de resultados
            foreach ($areas->result() as $row_area) {
                $resultados[$row_area->area_id] = $this->App_model->res_cuestionario($data['row_uc']->cuestionario_id, "usuario_id = {$usuario_id}", "area_id = {$row_area->area_id}");
            }

            //Se carga un array con el valor de las preguntas correctas
            foreach ( $resultados as $value ){
                $correctas[] = $value['correctas'];
            }

            foreach ( $resultados as $value ){
                $num_preguntas_area[] = $value['num_preguntas'];
            }
        
        //Array data
            $data['areas'] = $areas;
            $data['correctas'] = $correctas;
            $data['num_preguntas_area'] = $num_preguntas_area;
            $data['resultados'] = $resultados;    
            
            $data['res_usuario'] = $this->App_model->res_cuestionario($data['row_uc']->cuestionario_id, "usuario_id = {$usuario_id}");
            
        //Rango del estudiante
            $porcentaje = $data['res_usuario']['porcentaje']/100;
            $data['rango_usuario'] = $this->App_model->rango_cuestionarios($porcentaje);
            
            $data['vista_b'] = 'usuarios/resultados_v';
        
        //Solicitar vista
            $data['vista_b'] = 'usuarios/resultados/resultados_v';
            $data['vista_c'] = 'usuarios/resultados/area_v';
            $data['vista_menu'] = 'usuarios/resultados/submenu_v';
            $this->load->view(PTL_ADMIN, $data);
        
    }
    
    function resultados_competencias($usuario_id, $uc_id, $area_id){
        
        //Cargando datos básicos (_basico)
            $data = $this->Usuario_model->basico($usuario_id);
        
        //Head includes específicos para la página
            $head_includes[] = 'highcharts';
            $head_includes[] = 'grafico_competencias';
            $data['head_includes'] = $head_includes;
            
        //Variables
            $data['row_uc'] = $this->Pcrn->registro('usuario_cuestionario', "id = {$uc_id}");
            $data['row_cuestionario'] = $this->Pcrn->registro('cuestionario', "id = {$data['row_uc']->cuestionario_id}");
            
        //Variables para el gráfico
            $data['titulo_grafico'] = $this->App_model->nombre_item($area_id, 1);
            
            //Competencias del cuestionario
            $this->load->model('Cuestionario_model');
            $competencias = $this->Cuestionario_model->competencias($data['row_uc']->cuestionario_id, $area_id);
            
            //Se carga para cada competencia, un array de resultados
            foreach ($competencias->result() as $row_competencia) {
                $resultados[$row_competencia->competencia_id] = $this->App_model->res_cuestionario($data['row_uc']->cuestionario_id, "usuario_id = {$usuario_id}", "competencia_id = {$row_competencia->competencia_id}");
            }

            //Se carga un array con el valor de las preguntas correctas
            foreach ( $resultados as $value ){
                $correctas[] = $value['correctas'];
            }

            foreach ( $resultados as $value ){
                $num_preguntas_competencia[] = $value['num_preguntas'];
            }
            
        //Array data
            $data['area_id'] = $area_id;
            $data['areas'] = $this->Cuestionario_model->areas($data['row_uc']->cuestionario_id);
            $data['competencias'] = $competencias;
            $data['correctas'] = $correctas;
            $data['num_preguntas_competencia'] = $num_preguntas_competencia;
            $data['resultados'] = $resultados;    
            
            $data['res_usuario'] = $this->App_model->res_cuestionario($data['row_uc']->cuestionario_id, "usuario_id = {$usuario_id}");
            
        //Rango del estudiante
            $porcentaje = $data['res_usuario']['porcentaje']/100;
            $data['rango_usuario'] = $this->App_model->rango_cuestionarios($porcentaje);
            
            
        
        //Solicitar vista
            $data['subtitulo_pagina'] = 'Resultados por competencias';
            $data['vista_b'] = 'usuarios/resultados/resultados_v';
            $data['vista_c'] = 'usuarios/resultados/competencias_v';
            $data['vista_menu'] = 'usuarios/resultados/submenu_v';
            $this->load->view(PTL_ADMIN, $data);
        
    }
    
    function resultados_componentes($usuario_id, $uc_id, $area_id){
        
        //Cargando datos básicos (_basico)
            $data = $this->Usuario_model->basico($usuario_id);
            
        //Variables
            $correctas = array();
            //$num_preguntas_componente = array();
        
        //Head includes específicos para la página
            $head_includes[] = 'highcharts';
            $head_includes[] = 'grafico_componentes';
            $data['head_includes'] = $head_includes;
            
        //Variables
            $data['row_uc'] = $this->Pcrn->registro('usuario_cuestionario', "id = {$uc_id}");
            $data['row_cuestionario'] = $this->Pcrn->registro('cuestionario', "id = {$data['row_uc']->cuestionario_id}");
            
        //Variables para el gráfico
            $data['titulo_grafico'] = $this->App_model->nombre_item($area_id, 1);
            
            //componentes del cuestionario
            $this->load->model('Cuestionario_model');
            $componentes = $this->Cuestionario_model->componentes($data['row_uc']->cuestionario_id, $area_id);
            
            //Se carga para cada componente, un array de resultados
            $resultados = array();
            foreach ($componentes->result() as $row_componente) {
                $resultados[$row_componente->componente_id] = $this->App_model->res_cuestionario($data['row_uc']->cuestionario_id, "usuario_id = {$usuario_id}", "componente_id = {$row_componente->componente_id}");
            }

            //Se carga un array con el valor de las preguntas correctas
            foreach ( $resultados as $value ){
                $correctas[] = $value['correctas'];
            }

            foreach ( $resultados as $value ){
                $num_preguntas_componente[] = $value['num_preguntas'];
            }
            
        //Array data
            if ( count($resultados) > 0 ) { $data['num_preguntas_componente'] = $num_preguntas_componente; }
            
            $data['area_id'] = $area_id;
            $data['areas'] = $this->Cuestionario_model->areas($data['row_uc']->cuestionario_id);
            $data['componentes'] = $componentes;
            $data['correctas'] = $correctas;
            $data['resultados'] = $resultados;    
            $data['res_usuario'] = $this->App_model->res_cuestionario($data['row_uc']->cuestionario_id, "usuario_id = {$usuario_id}");
            
        //Rango del estudiante
            $porcentaje = $data['res_usuario']['porcentaje']/100;
            $data['rango_usuario'] = $this->App_model->rango_cuestionarios($porcentaje);
        
        //Solicitar vista
            $data['subtitulo_pagina'] = 'Resultados por componentes';
            $data['vista_b'] = 'usuarios/resultados/resultados_v';
            $data['vista_c'] = 'usuarios/resultados/componentes_v';
            $data['vista_menu'] = 'usuarios/resultados/submenu_v';
            $this->load->view(PTL_ADMIN, $data);
        
    }
    
    function cuestionarios_resumen01($usuario_id, $area_id = 50)
    {
        $this->load->model('Cuestionario_model');
        
        //Cargando datos básicos (_basico)
            $data = $this->Usuario_model->basico($usuario_id);
        
        //Head includes específicos para esta función
            $head_includes[] = 'highcharts';
            
        //Cuestionarios
            $this->db->join('usuario_cuestionario', 'cuestionario.id = usuario_cuestionario.cuestionario_id');
            $this->db->where('usuario_id', $usuario_id);
            $this->db->where('tipo_id IN (1, 2, 3)'); //Solo cuestionarios internos de Enlace
            $this->db->like('areas', $area_id);
            $cuestionarios = $this->db->get('cuestionario');
        
        //$data Específico
            $data['areas'] = $this->db->get_where('item', "id IN (50, 51, 52, 53)");
            $data['usuario_id'] = $usuario_id;
            $data['area_id'] = $area_id;
            $data['subseccion'] = 'resumen01';
            $data['head_includes'] = $head_includes;
            $data['cuestionarios'] = $cuestionarios;
            $data['competencias'] = $this->Cuestionario_model->competencias_area($area_id); //Query competencias
            $data['vista_b'] = 'usuarios/resultados/res01_v';
        
        //Solicitar vista
            $this->load->view(PTL_ADMIN, $data);
        
    }
    
    /**
     * Gráfico de desempeño del usuario por competencias
     * agrupado por acumulador (usuario_pregunta.acumulador)
     */
    function cuestionarios_resumen02($usuario_id, $area_id = 50)
    {
        //$this->output->enable_profiler(TRUE);
        
        $this->load->model('Cuestionario_model');
        
        //Cargando datos básicos (_basico)
            $data = $this->Usuario_model->basico($usuario_id);
        
        //Head includes específicos para esta función
            $head_includes[] = 'highcharts';
            
        //Condición usuario
            $this->db->select('id AS competencia_id, item AS nombre_competencia');
            $this->db->where('item_grupo', $area_id);
            $this->db->where('abreviatura IS NOT NULL');
            $competencias = $this->db->get('item');
            
            $nombres_competencias = array();
            foreach ($competencias->result() AS $row_competencia) {
                $nombres_competencias[$row_competencia->competencia_id] = $row_competencia->nombre_competencia;
            }
            
        //Calcular cantidad de acumuladores
            $filtros['usuario_pregunta.usuario_id'] = $usuario_id;
            $filtros['area_id'] = $area_id;
            $cant_acumuladores = $this->Cuestionario_model->cant_acumuladores($filtros);
            
        
        //$data Específico
            $data['areas'] = $this->db->get_where('item', 'categoria_id = 1 AND item_grupo = 1');
            $data['usuario_id'] = $usuario_id;
            $data['cant_acumuladores'] = $cant_acumuladores;
            $data['area_id'] = $area_id;
            $data['subseccion'] = 'resumen02';
            $data['head_includes'] = $head_includes;
            $data['nombres_competencias'] = $nombres_competencias;
            $data['vista_b'] = 'usuarios/resultados/res02_v';
        
        //Solicitar vista
            $data['subtitulo_pagina'] = 'Desempeño por competencias';
            $this->load->view(PTL_ADMIN, $data);
        
    }
    
    /**
     * Gráfico de desempeño del usuario por competencias
     * agrupado por acumulador mixto (usuario_pregunta.acumulador_2)
     */
    function cuestionarios_resumen03($usuario_id, $area_id = 50)
    {
        
        $this->load->model('Cuestionario_model');
        
        //Cargando datos básicos (_basico)
            $data = $this->Usuario_model->basico($usuario_id);
        
        //Head includes específicos para esta función
            $head_includes[] = 'highcharts';
            
        //Identificar acumuladores de la gráfica
            $filtros['usuario_cuestionario.usuario_id'] = $usuario_id;
            $filtros['area_id'] = $area_id;
            $acumuladores = $this->Cuestionario_model->acumuladores_2($filtros);
        
        //$data Específico
            $data['areas'] = $this->db->get_where('item', "categoria_id = 1 AND item_grupo = 1");
            $data['area_id'] = $area_id;
            $data['acumuladores'] = $acumuladores;
            $data['usuario_id'] = $usuario_id;
            $data['subseccion'] = 'resumen03';
            $data['head_includes'] = $head_includes;
            $data['competencias'] = $this->Cuestionario_model->competencias_area($area_id); //Query competencias
            $data['vista_b'] = 'usuarios/resultados/res03_v';
        
        //Solicitar vista
            $data['subtitulo_pagina'] = 'Desempeño por competencias';
            $this->load->view(PTL_ADMIN, $data);
        
    }
    
// ELIMINAR POR USERNAME CON ARCHIVO EXCEL
//-----------------------------------------------------------------------------
    
    /**
     * Mostrar formulario de cargue de archivo excel con listado de username
     * de usuarios y eliminarlos de la plataforma
     * 
     */
    function eliminar_por_username()
    {
        //Iniciales
            $nombre_archivo = '25_formato_eliminar_por_username.xlsx';
            $parrafos_ayuda = array(
                'La columna A [username] no puede estar vacía.',
                'Con esta herramienta solo se podrán eliminar usuarios con rol de estudiante'
            );
        
        //Instructivo
            $data['titulo_ayuda'] = '¿Cómo eliminar usuarios por username?';
            $data['nota_ayuda'] = 'Se cargará listado de "usernames" de usuarios para eliminar.';
            $data['parrafos_ayuda'] = $parrafos_ayuda;
        
        //Variables específicas
            $data['destino_form'] = "usuarios/eliminar_por_username_e";
            $data['nombre_archivo'] = $nombre_archivo;
            $data['nombre_hoja'] = 'usernames';
            $data['url_archivo'] = base_url("assets/formatos_cargue/{$nombre_archivo}");
            
        //Variables generales
            //$data['ayuda_id'] = 97;
            $data['titulo_pagina'] = 'Usuarios';
            $data['subtitulo_pagina'] = 'Eliminar por username';
            $data['vista_a'] = 'comunes/importar_v';
            $data['vista_menu'] = 'usuarios/explorar_menu_v';
            $data['vista_submenu'] = 'usuarios/importar_menu_v';
        
        $this->load->view(PTL_ADMIN, $data);
    }
    
    /**
     * Importar listado de username para eliminar, (e) ejecutar.
     */
    function eliminar_por_username_e()
    {
        //Proceso
            $this->load->model('Pcrn_excel');
            $this->load->model('Esp');
            $letra_columna = 'A';   //Última columna con datos
            
            $resultado = $this->Pcrn_excel->array_hoja_default($letra_columna);

            if ( $resultado['valido'] )
            {
                $res_importacion = $this->Usuario_model->eliminar_por_username($resultado['array_hoja']);
                $data['no_importados'] = $res_importacion['no_importados'];
            }
        
        //Cargue de variables
            $data['valido'] = $resultado['valido'];
            $data['mensaje'] = $resultado['mensaje'];
            $data['array_hoja'] = $resultado['array_hoja'];
            $data['nombre_hoja'] = $this->input->post('nombre_hoja');
            $data['destino_volver'] = "usuarios/explorar/";
        
        //Cargar vista
            $data['titulo_pagina'] = 'Usuarios';
            $data['subtitulo_pagina'] = 'Resultado eliminación por username';
            $data['vista_a'] = 'comunes/resultado_importacion_v';
            $data['vista_menu'] = 'usuarios/explorar_menu_v';
            $data['vista_submenu'] = 'usuarios/importar_menu_v';
            $this->load->view(PTL_ADMIN, $data);
    }
    
// FUNCIONES MASIVAS
//-----------------------------------------------------------------------------
    
    /**
     * Actualiza a los usuarios ya iniciados (usuario.inicado = 1) 
     * como "usuario.pagado = 1". Si ya fueron iniciados, se considera que ya 
     * pagaron
     * 2017-02-15
     */
    function pagados_login()
    {
        //Construcción de registro
            $registro['pago'] = 1;
        
        //Construir consulta
            //$this->db->where('id IN (SELECT usuario_id FROM evento WHERE tipo_id = 101)');
            $this->db->where('iniciado', 1);    //Ya fueron iniciados
            $this->db->where('pago', 0);        //Pero no están marcados como pagados
            $this->db->update('usuario', $registro);
            
        //Construir array de resultado
            $resultado['mensaje'] = 'Usuarios actualizados: ' . $this->db->affected_rows();
            $resultado['clase'] = 'alert-info';
            $this->session->set_flashdata('resultado', $resultado);
        
        redirect('develop/procesos');
    }
    
    /**
     * 
     */
    function quitar_asteriscos()
    {
        //Consultas
            $consultas_sql[] = "UPDATE usuario SET nombre = REPLACE(nombre, '*', '') WHERE CHAR_LENGTH(nombre) > 1 AND nombre LIKE '%*%';";
            $consultas_sql[] = "UPDATE usuario SET apellidos = REPLACE(apellidos, '*', '') WHERE CHAR_LENGTH(apellidos) > 1 AND apellidos LIKE '%*%';";
            
        //Ejecutar
            $cant_modificados = 0;
            foreach ( $consultas_sql as $sql )
            {
                $this->db->query($sql);
                $cant_modificados += $this->db->affected_rows();
            }
            
        //Construir array de resultado
            $resultado['mensaje'] = 'Campos modificados: ' . $cant_modificados;
            $resultado['clase'] = 'alert-info';
            $this->session->set_flashdata('resultado', $resultado);
        
        redirect('develop/procesos');
    }
    
//---------------------------------------------------------------------------------------------------
//FUNCIONES INTERNAS
    
    function _password_check($password)
    {
        //Comparar con la contraseña que el usuario tiene registrada en la base de datos
        //Función de validación utilizada para el cambio de contraseña
        
        $usuario_id = $this->input->post('id');
        $pw_comparacion = $this->Pcrn->campo('usuario', "id = {$usuario_id}", 'password');
        
        if ( $password == $pw_comparacion ){
            return TRUE;
        } else {
            return FALSE;
        }
    }

}