<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Orders extends CI_Controller{
    
    function __construct() 
    {
        parent::__construct();

        $this->load->model('Order_model');
        $this->load->model('Order_model');
        $this->load->model('Product_model');
        
        //Para definir hora local
        date_default_timezone_set("America/Bogota");
    }

//EXPLORE FUNCTIONS
//---------------------------------------------------------------------------------------------------

    /** Exploración de Posts */
    function explore()
    {        
        //Datos básicos de la exploración
            $data = $this->Order_model->explore_data(1);
        
        //Opciones de filtros de búsqueda
            $data['options_status'] = $this->Item_model->options('categoria_id = 7', 'Todos');
            $data['options_institucion'] = $this->App_model->opciones_institucion('id > 0', 'Todos');
            
        //Arrays con valores para contenido en lista
            $data['arr_niveles'] = $this->App_model->arr_nivel();
            $data['arr_status'] = $this->Item_model->arr_cod('categoria_id = 7');
            
        //Cargar vista
            $this->App_model->view(TPL_ADMIN_NEW, $data);
    }

    /**
     * Listado de Posts, filtrados por búsqueda, JSON
     */
    function get($num_page = 1)
    {
        $data = $this->Order_model->get($num_page);
        $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }
    
    /**
     * AJAX JSON
     * Eliminar un conjunto de orders seleccionados
     */
    function delete_selected()
    {
        $selected = explode(',', $this->input->post('selected'));
        $data['qty_deleted'] = 0;
        
        foreach ( $selected as $row_id ) 
        {
            $data['qty_deleted'] += $this->Order_model->delete($row_id);
        }

        //Establecer resultado
        if ( $data['qty_deleted'] > 0 ) { $data['status'] = 1; }
        $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }

    /**
     * Exporta el resultado de la búsqueda a un archivo de Excel
     * 2020-12-11
     */
    function export()
    {
        //Cargando
            $this->load->model('Search_model');
            $this->load->model('Pcrn_excel');
        
        //Datos de consulta, construyendo array de búsqueda
            $filters = $this->Search_model->filters();
            $results_total = $this->Order_model->export($filters);
        
        //Preparar datos
            $datos['nombre_hoja'] = 'Ventas';
            $datos['query'] = $results_total;
            
        //Preparar archivo
            $objWriter = $this->Pcrn_excel->archivo_query($datos);
        
        $data['objWriter'] = $objWriter;
        $data['nombre_archivo'] = date('Ymd_His'). '_ventas'; //save our workbook as this file name
        
        $this->load->view('comunes/descargar_phpexcel_v', $data);
            
    }

// CRUD
//-----------------------------------------------------------------------------

    /**
     * Información general de la compra
     * 2020-11-19
     */
    function get_info($order_code)
    {
        $data['row'] = $this->Db_model->row('orders', "order_code = '{$order_code}'");

        if ( ! is_null($data['row']) )
        {
            $products = $this->Order_model->products($data['row']->id);
            $data['products'] = $products->result();
        }

        //Salida JSON
        $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }

    function get_products($order_id)
    {
        $products = $this->Order_model->products($order_id);

        //Salida JSON
        $this->output->set_content_type('application/json')->set_output(json_encode($products->result()));
    }

    function info($order_id)
    {
        $data = $this->Order_model->basic($order_id);

        $data['products'] = $this->Order_model->products($order_id);

        $data['view_a'] = 'orders/info_v';
        $data['nav_2'] = 'orders/menu_v';
        $data['subtitle_head'] = 'Información';

        $this->App_model->view(TPL_ADMIN_NEW, $data);
    }

    function details($order_id)
    {
        $data = $this->Order_model->basic($order_id);

        $data['products'] = $this->Order_model->products($order_id);

        $data['view_a'] = 'orders/details_v';
        $data['nav_2'] = 'orders/menu_v';
        $data['subtitle_head'] = 'Información';

        $this->App_model->view(TPL_ADMIN_NEW, $data);
    }

// Edición desde administrador
//-----------------------------------------------------------------------------

    /**
     * Formulario edición de datos de la compra
     * 2020-12-07
     */
    function edit($order_id)
    {
        $data = $this->Order_model->basic($order_id);

        $data['products'] = $this->Order_model->products($order_id);

        $data['view_a'] = 'orders/edit_v';
        $data['nav_2'] = 'orders/menu_v';
        $data['subtitle_head'] = 'Editar';

        $this->App_model->view(TPL_ADMIN_NEW, $data);
    }

    function admin_update($order_id)
    {
        $data = $this->Order_model->update($order_id);
        $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }

    /**
     * AJAX JSON
     * Crear un nuevo pedido, tabla orders. Le agrega un producto inicial con cantidad 1.
     */
    function create($product_id)
    {
        $data = $this->Order_model->create();

        if ( $data['status'] )
        {
            $this->session->set_userdata('order_id', $data['order_id']);
            $this->Order_model->add_product($product_id);
        }

        $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }

    function add_product($product_id, $quantity = 1)
    { 
        if ( is_null($this->session->userdata('order_id')) ) 
        {
            $data_order = $this->Order_model->create();
            $this->session->set_userdata('order_id', $data_order['order_id']);
        }

        $data = $this->Order_model->add_product($product_id, $quantity);

        $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }

    /**
     * Quita un producto de la orden y recalcula totales
     * 2020-11-19
     */
    function remove_product($product_id)
    { 
        $data = $this->Order_model->remove_product($product_id);
        $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }

// PAGOS POR CÓDIGOS INSTITUCIÓN Y USUARIO
//-----------------------------------------------------------------------------

    function pays($institution_cod = '')
    {
        $data['head_title'] = 'Pagos';
        $data['view_a'] = 'orders/pays/pays_v';
        $data['institution_cod'] = $institution_cod;
        $data['arr_niveles'] = $this->App_model->arr_nivel('nombre_nivel');

        //Identificar institución
        $curr_institution = array('id' => 0, 'name' => '');
        if ( $institution_cod != '' )
        {
            $institucion = $this->Db_model->row('institucion', "cod = '{$institution_cod}'");
            if ( ! is_null($institucion) ) {
                $curr_institution['id'] = $institucion->id;
                $curr_institution['name'] = $institucion->nombre_institucion;
            }
        }

        $data['curr_institution'] = $curr_institution;

        $this->App_model->view('templates/monster/public/public_v', $data);
    }

// PROCESO DE PAGO
//-----------------------------------------------------------------------------

    /**
     * Pasos en el proceso de compra:
     * Step 1: formulario para completar datos personales
     * Step 2: Verificación de datos y totales
     */
    function checkout($step = 1)
    {
        $order_id = $this->session->userdata('order_id');
        $data = $this->Order_model->basic($order_id);

        $data['products'] = $this->Order_model->products($order_id);
        $data['form_data'] = $this->Order_model->wompi_form_data($order_id);
        $data['institucion'] = $this->Db_model->row_id('institucion', $data['row']->institution_id);
        $data['step'] = $step;

        $data['head_title'] = 'Completa tus datos';
        $data['view_a'] = "orders/checkout/step_{$step}_v";

        //Opciones para formulario
        $data['options_region'] = $this->App_model->options_place('tipo_id = 3', 'place_name', 'Departamento');
        $data['options_city'] = $this->App_model->options_place("tipo_id = 4 AND region_id = {$data['row']->region_id}", 'place_name', 'Ciudad');
        
        if ( $this->session->userdata('logged') ) {
            $this->App_model->view(TPL_ADMIN_NEW, $data);
        } else {
            $this->App_model->view('templates/monster/public/public_v', $data);
        }
    }

    /**
     * Vista HTML, Página de respuesta, redireccionada desde Wompi para mostrar el resultado
     * de una transacción de pago. Toma los datos de resultado de GET
     * 20520-12-09
     */
    function result()
    {
        //Quitar Compra de las variables de sesión
        $this->session->unset_userdata('order_id');

        $data = $this->Order_model->result_data();

        $data['step'] = 3;  //Tercer y último paso, resultado
        $data['view_a'] = "orders/checkout/result_v";
        $this->App_model->view('templates/monster/public/public_v', $data);
    }

    /**
     * AJAX JSON
     * Actualiza los datos de una compra
     * 2020-06-19
     */
    function update($order_id)
    {
        $data = $this->Order_model->update($order_id);
        
        $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }

    /**
     * Página de confirmación que ejecuta remotamente PagosOnLine (pol) al 
     * terminar una transacción. Recibe datos de POL vía post, actualiza 
     * datos del pago del pedido
     */
    function confirmation_wompi()
    {
        $data['confirmation_id'] = $this->Order_model->confirmation_wompi();

        //Salida JSON
        $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }

    /**
     * Realiza el envío de un email al comprador, sobre el estado más reciente de su compra
     * 2020-12-07
     */
    function send_status_email($order_id)
    {
        $data = $this->Order_model->email_buyer($order_id);

        //Salida JSON
        $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }

    function email_preview($order_id)
    {
        $row_order = $this->Db_model->row_id('orders', $order_id);
        $message = $this->Order_model->message_buyer($row_order);
        echo $message;
    }

    function institution_email()
    {
        $data = $this->Order_model->institution_emails(41);

        //Salida JSON
        $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }

    /**
     * Formulario para probar el resultado de ejecución de la página de confirmación
     * ejecutada por Wompi remotamente
     */
    function test($type, $order_id)
    {
        $data = $this->Order_model->basic($order_id);

        $data['head_title'] = 'Test compras ' . $order_id;
        $data['head_subtitle'] = $type;
        $data['view_a'] = "orders/test/{$type}_v";
        $data['nav_2'] = "orders/menu_v";
        $data['nav_3'] = "orders/test/menu_v";
        $this->App_model->view(TPL_ADMIN_NEW, $data);
    }

// Respuestas de plataforma de pago
//-----------------------------------------------------------------------------

    function responses($order_id)
    {
        $data = $this->Order_model->basic($order_id);

        $data['responses'] = $this->Order_model->responses($order_id);

        $data['view_a'] = 'orders/responses_v';
        $data['nav_2'] = "orders/menu_v";
        $data['subtitle_head'] = 'Respuestas Wompi';
        $this->App_model->view(TPL_ADMIN_NEW, $data);
    }

// Compras y suscripciones de Usuarios
//-----------------------------------------------------------------------------

    /**
     * Compras de un usuario
     * 2020-05-20
     */
    function my_orders()
    {
        $user_id = $this->session->userdata('user_id');
        $this->load->model('Usuario_model');
        $data = $this->Usuario_model->basico($user_id);
        $data['orders'] = $this->Order_model->user_orders($user_id);
        
        //Variables específicas
        $data['head_subtitle'] = 'Mis compras';
        $data['nav_2'] = NULL;
        $data['view_a'] = 'orders/my_orders_v';
        
        $this->App_model->view(TPL_ADMIN_NEW, $data);
    }

    function status($order_code)
    {
        $row = $this->Db_model->row('orders', "order_code = '{$order_code}'");

        if ( ! is_null($row) )
        {
            $data = $this->Order_model->basic($row->id);
            $data['products'] = $this->Order_model->products($row->id);
            $data['view_a'] = 'orders/status_v';

            $this->App_model->view('templates/monster/public/public_v', $data);
        } else {
            redirect('app/no_permitido');
        }
    }
}