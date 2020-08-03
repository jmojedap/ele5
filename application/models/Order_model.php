<?php
class Order_model extends CI_Model{

    function basic($order_id)
    {
        $data['row'] = $this->Db_model->row_id('orders', $order_id);
        $data['head_title'] = $data['row']->order_code;

        return $data;
    }

// EXPLORE FUNCTIONS - orders/explore
//-----------------------------------------------------------------------------
    
    /**
     * Array con los datos para la vista de exploración
     */
    function explore_data($num_page)
    {
        //Data inicial, de la tabla
            $data = $this->get($num_page);
        
        //Elemento de exploración
            $data['controller'] = 'orders';                      //Nombre del controlador
            $data['cf'] = 'orders/explore/';                      //Nombre del controlador
            $data['views_folder'] = 'orders/explore/';           //Carpeta donde están las vistas de exploración
            
        //Vistas
            $data['head_title'] = 'Compras';
            $data['head_subtitle'] = $data['search_num_rows'];
            $data['view_a'] = $data['views_folder'] . 'explore_v';
            $data['nav_2'] = $data['views_folder'] . 'menu_v';
        
        return $data;
    }

    function get($num_page)
    {
        //Referencia
            $per_page = 10;                             //Cantidad de registros por página
            $offset = ($num_page - 1) * $per_page;      //Número de la página de datos que se está consultado

        //Búsqueda y Resultados
            $this->load->model('Search_model');
            $data['filters'] = $this->Search_model->filters();
            $elements = $this->search($data['filters'], $per_page, $offset);    //Resultados para página
        
        //Cargar datos
            $data['list'] = $elements->result();
            $data['str_filters'] = $this->Search_model->str_filters();
            $data['search_num_rows'] = $this->search_num_rows($data['filters']);
            $data['max_page'] = ceil($this->pml->if_zero($data['search_num_rows'],1) / $per_page);   //Cantidad de páginas

        return $data;
    }
    
    /**
     * String con condición WHERE SQL para filtrar post
     */
    function search_condition($filters)
    {
        $condition = NULL;
        
        //Tipo de post
        if ( $filters['status'] != '' ) { $condition .= "status = {$filters['status']} AND "; }
        
        if ( strlen($condition) > 0 )
        {
            $condition = substr($condition, 0, -5);
        }
        
        return $condition;
    }
    
    function search($filters, $per_page = NULL, $offset = NULL)
    {
        
        $role_filter = $this->role_filter($this->session->userdata('post_id'));

        //Construir consulta
            //$this->db->select('id, post_name, except, ');
        
        //Crear array con términos de búsqueda
            $words_condition = $this->Search_model->words_condition($filters['q'], array('order_code', 'buyer_name', 'city', 'email', 'phone_number'));
            if ( $words_condition )
            {
                $this->db->where($words_condition);
            }
            
        //Orden
            if ( $filters['o'] != '' )
            {
                $order_type = $this->pml->if_strlen($filters['ot'], 'ASC');
                $this->db->order_by($filters['o'], $order_type);
            } else {
                $this->db->order_by('updated_at', 'DESC');
            }
            
        //Filtros
            $this->db->where($role_filter); //Filtro según el rol de post en sesión
            $search_condition = $this->search_condition($filters);
            if ( $search_condition ) { $this->db->where($search_condition);}
            
        //Obtener resultados
        if ( is_null($per_page) )
        {
            $query = $this->db->get('orders'); //Resultados totales
        } else {
            $query = $this->db->get('orders', $per_page, $offset); //Resultados por página
        }
        
        return $query;
        
    }
    
    /**
     * Devuelve la cantidad de registros encontrados en la tabla con los filtros
     * establecidos en la búsqueda
     */
    function search_num_rows($filters)
    {
        $query = $this->search($filters); //Para calcular el total de resultados
        return $query->num_rows();
    }
    
    /**
     * Devuelve segmento SQL
     */
    function role_filter()
    {
        $role = $this->session->userdata('role');
        $condition = "user_id = {$this->session->userdata('user_id')}";  //Valor por defecto, ningún post, se obtendrían cero post.
        
        if ( $role <= 2 ) 
        {   //Desarrollador, todos las compras
            $condition = 'id > 0';
        }
        
        return $condition;
    }
    
    /**
     * Array con options para ordenar el listado de post en la vista de
     * exploración
     * 
     * @return string
     */
    function order_options()
    {
        $order_options = array(
            '' => '[ Ordenar por ]',
            'id' => 'ID Pedido',
            'order_code' => 'Ref. venta'
        );
        
        return $order_options;
    }
    
    function editable()
    {
        return TRUE;
    }

// CRUD
//-----------------------------------------------------------------------------

    /**
     * Crear un pedido en la tabla orders
     */
    function create()
    {
        $data = array('status' => 0);
        
        //Construir registro
        $arr_row['country_id'] = 51;    //Colombia

        //Construir registro
            if ( $this->input->get('i') ) { $arr_row['institution_id'] = $this->input->get('i'); }
            if ( $this->input->get('n') ) { $arr_row['level'] = $this->input->get('n'); }
        
        //Identificar usuario
            $row_user = $this->Db_model->row_id('usuario', $this->input->get('u'));    

            if ( ! is_null($row_user) )
            {
                $arr_row['email'] = ( ! is_null($row_user->email) ) ? $row_user->email : '';
                $arr_row['user_id'] = $row_user->id;
                $arr_row['institution_id'] = $row_user->institucion_id;
    
                //Identificar institución, para conocer ciudad y agregar los datos
                $row_institution = $this->Db_model->row_id('institucion', $row_user->institucion_id);
                if ( ! is_null($row_institution) )
                {
                    $arr_row['city_id'] = $row_institution->place_id; 
                    $arr_city = $this->arr_city($row_institution->place_id);
                    $arr_row = array_merge($arr_row, $arr_city);    //Agregar datos
                }
            }

        //Crear registro
            $this->db->insert('orders', $arr_row);
            $order_id = $this->db->insert_id();
    
        //Establecer resultado de la creación de orden
        if ( $order_id > 0 )
        {
            $data['status'] = 1;
            $data['order_id'] = $order_id;
            $data['order_code'] = $this->set_order_code($order_id);
        }
    
        return $data;
    }

    /**
     * Actualizar los datos de un pedido.
     */
    function update($order_id)
    {
        $arr_row = $this->input->post();
        $arr_row['updater_id'] = $this->session->userdata('user_id');

        //Establecer ciudad y datos
        if ( isset($arr_row['city_id']) )
        {
            $arr_city = $this->arr_city($arr_row['city_id']);
            $arr_row = array_merge($arr_row, $arr_city);
        }

        $this->db->where('id', $order_id);
        $this->db->update('orders', $arr_row);
        
        $data = array('status' => 1);

        return $data;
    }

    /**
     * Array con datos complementarios de ciudad, para la tabla orders.
     * 2020-06-25
     */
    function arr_city($city_id)
    {
        $arr_city = array();
        $row_city = $this->Db_model->row_id('lugar', $city_id);

        if ( ! is_null($row_city) )
        {
            $arr_city['city'] = $row_city->nombre_lugar . ' - ' . $row_city->region . ' - ' . $row_city->pais;
            $arr_city['country_id'] = $row_city->pais_id;
            $arr_city['region_id'] = $row_city->region_id;
        }

        return $arr_city;
    }

    /**
     * Agrega un producto en una cantidad definida a una orden, guarda el registro
     * en la tabla order_producto (op), devuelve ID del registro guardado.
     * 2019-06-17
     */
    function add_product($product_id, $quantity = 1)
    {
        $order_id = $this->session->userdata('order_id');

        $this->load->model('Product_model');
        $row_product = $this->Db_model->row_id('product', $product_id);

        $arr_row['order_id'] = $order_id;
        $arr_row['product_id'] = $product_id;
        $arr_row['original_price'] = $row_product->price;
        $arr_row['price'] = $row_product->price;
        $arr_row['quantity'] = $quantity;

        $data['op_id'] = $this->Db_model->save('order_product', "order_id = {$arr_row['order_id']} AND product_id = {$arr_row['product_id']}", $arr_row);

        //Actualizar totales del pedido
        $this->update_totals($order_id);
        
        $data['status'] = ($data['op_id'] > 0 ) ? 1 : 0 ;

        return $data;
    }

    /**
     * Genera y establece un código único para un pedido. Campo order.order_code
     * 2020-06-19
     */
    function set_order_code($order_id)
    {
        $this->load->helper('string');
        
        $order_code = 'E' . strtoupper(random_string('alpha', 2)) . '-' . $order_id;

        $arr_row['order_code'] = $order_code;
        $arr_row['description'] = 'Compra ' . $order_code . ' en En Línea Editores';
        
        $this->db->where('id', $order_id);
        $this->db->update('orders', $arr_row);

        return $arr_row['order_code'];
    }

// CÁLCULO Y ACTUALIZACIÓN DE TOTALES
//-----------------------------------------------------------------------------

    /**
     * Actualiza los valores numéricos totales del pedido, a partir de los datos detallados en la tabla
     * order_product.
     * 2019-06-17
     */
    function update_totals($order_id)
    {
        $this->update_totals_1($order_id);  //Total productos
        $this->update_totals_3($order_id);  //Total valor, order.amount
    }

    function update_totals_1($order_id)
    {
        //Valor inicial por defecto
        $arr_row['updater_id'] = $this->session->userdata('user_id');
        $arr_row['total_products'] = 0;
        $arr_row['total_tax'] = 0;

        //Consulta para calcular totales
        $this->db->select('SUM(order_product.price * quantity) AS total_products, SUM(order_product.tax * quantity) AS total_tax');
        $this->db->where('order_id', $order_id);
        $this->db->where('order_product.type_id', 1);  //Productos
        $query = $this->db->get('order_product');

        if ( $query->num_rows() > 0 ) 
        {
            $arr_row['total_products'] = $query->row()->total_products;
            $arr_row['total_tax'] = $query->row()->total_tax;
        }

        //Actualizar
        $this->db->where('id', $order_id);
        $this->db->update('orders', $arr_row);
    }

    /**
     * Actualiza los totales: order.amount
     * @param type $order_id
     */
    function update_totals_3($order_id)
    {
        $sql = "UPDATE orders SET amount = total_products + total_extras WHERE id = {$order_id}";
        $this->db->query($sql);
    }

// DATOS DEL PEDIDO
//-----------------------------------------------------------------------------

    //Productos incluidos en un pedido
    function products($order_id)
    {
        $this->db->select('product.name, product.description, order_product.*');
        $this->db->join('product', 'product.id = order_product.product_id');
        $this->db->where('order_id', $order_id);
        $products = $this->db->get('order_product');

        return $products;
    }

    /**
     * Devuelve un elemento row, de un pedido dado el código del pedido
     * @param type $order_code
     * @return type
     */
    function row_by_code($order_code) 
    {
        $row = $this->Db_model->row('orders', "order_code = '{$order_code}'");
        return $row;
    }

// CHECKOUT PayU
//-----------------------------------------------------------------------------

    /**
     * Array con todos los datos para construir el formulario que se envía a PayU
     * para iniciar el proceso de pago.
     */
    function payu_form_data($order_id)
    {
        //Registro del pedido
        $row = $this->Db_model->row_id('orders', $order_id);

        //Construir array
            $data['merchantId'] = K_PUMI;
            $data['referenceCode'] = $row->order_code;
            $data['description'] = $row->description;
            $data['amount'] = $row->amount;
            $data['tax'] = $row->total_tax;
            $data['taxReturnBase'] = 0; //No tiene IVA
            $data['signature'] = $this->payu_signature($row);
            $data['accountId'] = K_PUAI;
            $data['currency'] = 'COP';  //Pesos colombianos
            $data['test'] = ( $this->input->get('test') == 1 ) ? 1 : 0;
            $data['buyerFullName'] = $row->buyer_name;
            $data['buyerEmail'] = $row->email;
            $data['shippingAddress'] = $row->address;
            $data['shippingCity'] = $row->city;
            $data['shippingCountry'] = 'CO';
            $data['telephone'] = $row->phone_number;
            $data['responseUrl'] = base_url('orders/result');
            $data['confirmationUrl'] = base_url('orders/confirmation_payu');

        return $data;
    }

    /**
     * Genera la firma que se envía en el Formulario para ir al pago en PayU
     */
    function payu_signature($row_order)
    {
        $signature_pre = K_PUAK;
        $signature_pre .= '~' . K_PUMI;
        $signature_pre .= '~' . $row_order->order_code;
        $signature_pre .= '~' . $row_order->amount;
        $signature_pre .= '~' . 'COP';
        
        return md5($signature_pre);
    }

    /**
     * Tomar y procesar los datos POST que envía PayU a la página 
     * de confirmación.
     * url_confirmacion >> 'orders/confirmation_payu'
     */
    function confirmation_payu()
    {   
        //Identificar Pedido
        $confirmation_id = 0;
        $row = $this->row_by_code($this->input->post('reference_sale'));

        if ( ! is_null($row) )
        {
            //Guardar array completo de confirmación en la tabla "meta"
                $row_confirmation = $this->save_confirmation($row);

            //Actualizar registro de pedido
                if ( ! is_null($row_confirmation) )
                {
                    $confirmation_id = $row_confirmation->id;
                    $this->update_status($row_confirmation);
                }

            //Asignar contenidos digitales
                if ( $row_confirmation->estado_id == 1 )
                {
                    //Asignar contenidos digitales asociados a los productos comprados
                    $this->assign_posts($row->id);
                }

            //Enviar mensaje a administradores de tienda y al cliente
                //$this->email_buyer($row->id);
                //if ( $order_status == 1 ) { $this->email_admon($row->id); }
        }

        return $confirmation_id;
    }

    /**
     * Crea un registro en la tabla post, con los datos recibidos tras en la 
     * ejecución de la página de confirmación por parte de PayU.
     */
    function save_confirmation($row)
    {
        //Datos POL
            $arr_confirmation_payu = $this->input->post();
            $arr_confirmation_payu['ip_address'] = $this->input->ip_address();
            $json_confirmation_payu = json_encode($arr_confirmation_payu);
        
        //Construir registro para tabla Post
            $arr_row['tipo_id'] = 54;  //54: Confirmación de pago, Ver: items.category_id = 33
            $arr_row['nombre_post'] = 'Confirmación ' . $arr_confirmation_payu['reference_sale'];
            $arr_row['contenido_json'] = $json_confirmation_payu;
            $arr_row['estado_id'] = ( $arr_confirmation_payu['response_code_pol'] == 1 ) ? 1 : 0;
            $arr_row['padre_id'] = $row->id;
            $arr_row['referente_1_id'] = $arr_confirmation_payu['response_code_pol'];
            $arr_row['referente_2_id'] = $arr_confirmation_payu['payment_method_id'];
            $arr_row['fecha'] = date('Y-m-d H:i:s');
            $arr_row['texto_1'] = $arr_confirmation_payu['sign'];
            $arr_row['texto_2'] = $arr_confirmation_payu['response_message_pol'];
            $arr_row['editor_id'] = 1001;     //PayU internal user
            $arr_row['usuario_id'] = 1001;    //PayU internal user
        
        //Guardar
            $condition = "tipo_id = 54 AND padre_id = {$row->id}";
            $confirmation_id =$this->Db_model->save('post', $condition, $arr_row);

        //Row de confirmación
            $row_confirmation = $this->Db_model->row_id('post', $confirmation_id);
        
        return $row_confirmation;
    }

    /**
     * Actualiza el estado de un pedido, dependiendo del código de respuesta en la 
     * confirmación
     */
    function update_status($row_confirmation)
    {
        $arr_row['status'] = ( $row_confirmation->referente_1_id == 1 ) ? 1 : 5;
        $arr_row['response_code_pol'] = $row_confirmation->referente_1_id;
        $arr_row['confirmed_at'] = date('Y-m-d H:i:s');
        $arr_row['updated_at'] = date('Y-m-d H:i:s');
        $arr_row['updater_id'] = 1001;  //PayU Automático

        $this->db->where('id', $row_confirmation->padre_id);   //Parent ID = Order ID
        $this->db->update('orders', $arr_row);
    }

    function result_data()
    {
        $order_code = $this->input->get('referenceCode');
        $row = $this->row_by_code($order_code);

        $data = array('status' => 0, 'message' => 'Compra no identificada', 'success' => 0);
        $data['success'] = 0;
        $data['order_id'] = 0;
        $data['head_title'] = 'Pago no realizado';

        if ( ! is_null($row) )
        {
            $data['status'] = 1;
            $data['message'] = 'Resultado recibido';
            $data['order_id'] = $row->id;

            if ( $this->input->get('polResponseCode') == 1 )
            {
                $data['success'] = 1;
                $data['head_title'] = 'Pago exitoso';
            }
        }

        return $data;
    }

// ASIGNACIÓN DE PRODUCTOS DIGITALES
//-----------------------------------------------------------------------------

    /**
     * Verifica qué productos de los comprados incluyen contenidos digitales y se los asigna
     * al usuario que realizó la compra
     * 2020-04-16
     * 
     */
    function assign_posts($order_id)
    {
        //Cargue inicial
        $this->load->model('Product_model');
        $this->load->model('Post_model');
        $row = $this->Db_model->row_id('orders', $order_id);

        $products = $this->digital_products($order_id);   //Productos con contenidos digitales

        $arr_posts = array();
        foreach( $products->result() as $row_product )
        {
            $posts = $this->Product_model->assigned_posts($row_product->id);
            foreach ( $posts->result() as $row_post )
            {
                $arr_posts[] = array('id' => $row_post->id, 'title' => $row_post->title);
                $this->Post_model->add_to_user($row_post->id, $row->user_id);
            }
        }

        $data['products'] = $products->result();
        $data['posts'] = $arr_posts;
        $data['qty_posts'] = count($arr_posts);

        return $data;
    }

    /**
     * Listado de productos con contenidos digitales que están incluidos en un pedido
     * 2020-04-16
     */
    function digital_products($order_id)
    {
        $this->db->select('id, name');
        $this->db->where("id IN (SELECT product_id FROM order_product WHERE order_id = {$order_id} AND type_id = 1)");
        $this->db->where('cat_1', 2115);  //Contenidos digitales
        $products = $this->db->get('product');

        return $products;
    }

    /**
     * Pedidos realizados por el usuario, tabla orders.
     */
    function user_orders($user_id)
    {
        $this->db->select('*');
        $this->db->where('user_id', $user_id);
        $this->db->order_by('id', 'DESC');
        $orders = $this->db->get('orders');

        return $orders;
    }

// MENSAJES DE CORREO ELECTRÓNICO
//-----------------------------------------------------------------------------

    /**
     * Tras la confirmación PayU, se envía un mensaje de estado del pedido
     * al cliente
     * 
     * @param type $order_id
     */
    function email_buyer($order_id)
    {
        $row_order = $this->Db_model->row_id('orders', $order_id);
        $admin_email = $this->Db_model->field_id('sis_option', 25); //Opción 25
            
        //Asunto de mensaje
            $subject = "Estado de la compra {$row_order->order_code}: " . $this->Item_model->name(10, $row_order->response_code_pol);
        
        //Enviar Email
            $this->load->library('email');
            $config['mailtype'] = 'html';

            $this->email->initialize($config);
            $this->email->from('info@' . APP_DOMAIN, APP_NAME);
            $this->email->to($row_order->email);
            $this->email->bcc($admin_email);
            $this->email->subject($subject);
            $this->email->message($this->message_buyer($row_order));
            
            $this->email->send();   //Enviar
            
    }

    /**
     * String con contenido del mensaje del correo electrónico enviado al comprador
     * después de recibir la confirmación de pago
     */
    function message_buyer($row_order)
    {
        $data['row_order'] = $row_order;
        $data['product'] = $this->products($row_order->id);

        $str_style = file_get_contents(URL_RESOURCES . 'css/email.json');
        $data['style'] = json_decode($str_style);
        
        $message = $this->load->view('orders/emails/message_buyer_v', $data, TRUE);
        
        return $message;
    }
}