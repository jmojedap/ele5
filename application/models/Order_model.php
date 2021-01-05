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
            $data['head_title'] = 'Ventas';
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

        $condition .= $this->role_filter() . ' AND ';

        //q words condition
        $words_condition = $this->Search_model->words_condition($filters['q'], array('name', 'code', 'description', 'text_1', 'text_2'));
        if ( $words_condition )
        {
            $condition .= $words_condition . ' AND ';
        }
        
        if ( $filters['status'] != '' ) { $condition .= "orders.status = {$filters['status']} AND "; } //Estado compra
        if ( $filters['i'] != '' ) { $condition .= "orders.institution_id = {$filters['i']} AND "; } //Institución asociada
        if ( $filters['fi'] != '' ) { $condition .= "orders.created_at >= '{$filters['fi']}' AND "; } //Fecha de creación posterior a
        
        if ( strlen($condition) > 0 )
        {
            $condition = substr($condition, 0, -5);
        }
        
        return $condition;
    }

    /**
     * Segmento Select SQL, con diferentes formatos, consulta de orders
     * 2020-12-15
     */
    function select($format = 'general')
    {
        $arr_select['general'] = 'orders.id, status, order_code, buyer_name, orders.email, id_number, address, city, phone_number, amount, updated_at, created_at, institution_id, nombre_institucion AS institution_name, orders.level, notes_admin, bill AS no_factura, shipping_code AS no_guia, wompi_id, wompi_status, wompi_payment_method_type, confirmed_at, user_id, student_name';

        $arr_select['export'] = 'orders.id, orders.status, order_code AS referencia, buyer_name AS comprador, orders.email, id_number AS no_documento, address AS direccion';
        $arr_select['export'] .= ', city AS ciudad, phone_number AS telefono, amount, orders.updated_at, orders.created_at';
        $arr_select['export'] .= ', orders.institution_id, nombre_institucion AS institution_name, orders.level, notes_admin, bill AS no_factura, shipping_code AS no_guia, wompi_id, wompi_status, wompi_payment_method_type, confirmed_at, user_id, student_name AS nombre_estudiante';
        $arr_select['export'] .= ', order_product.product_id AS producto_id, product.name AS nombre_producto, product.code AS referencia_producto, order_product.quantity AS cantidad_productos';

        return $arr_select[$format];
    }
    
    function search($filters, $per_page = NULL, $offset = NULL)
    {
        //Construir consulta
            $this->db->select($this->select());
            $this->db->join('institucion', 'orders.institution_id = institucion.id', 'left');
            
        //Orden
            if ( $filters['o'] != '' )
            {
                $order_type = $this->pml->if_strlen($filters['ot'], 'ASC');
                $this->db->order_by($filters['o'], $order_type);
            } else {
                $this->db->order_by('updated_at', 'DESC');
            }
            
        //Filtros
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
     * Query para exportar
     * 2020-12-15
     */
    function export($filters)
    {
        $this->db->select($this->select('export'));
        $this->db->join('institucion', 'orders.institution_id = institucion.id', 'left');
        $this->db->join('order_product', 'orders.id = order_product.order_id');
        $this->db->join('product', 'order_product.product_id = product.id');

        $search_condition = $this->search_condition($filters);
        if ( $search_condition ) { $this->db->where($search_condition);}

        $query = $this->db->get('orders', 5000);  //Hasta 5000 productos

        return $query;
    }
    
    /**
     * Devuelve segmento SQL
     */
    function role_filter()
    {
        $role = $this->session->userdata('role');
        $condition = "user_id = {$this->session->userdata('user_id')}";  //Valor por defecto, ninguna order, se obtendrían cero orders.
        
        if ( $role <= 2 ) 
        {   //Desarrollador, todos las compras
            $condition = 'orders.id > 0';
        } elseif ( $role == 7 ) {
            $condition = 'orders.id > 0';
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
        $arr_row['region_id'] = 267;    //Bogotá D.C.

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
     * Quita un producto de la orden y recalcula totales, elimina de la tabla order_product
     * y recalcula totales.
     */
    function remove_product($product_id)
    {
        $order_id = $this->session->userdata('order_id');

        $this->db->where('product_id', $product_id);
        $this->db->where('order_id', $order_id);
        $this->db->where('type_id', 1); //Es un producto
        $this->db->delete('order_product');
        
        $data['qty_deleted'] = $this->db->affected_rows();

        //Actualizar totales del pedido
        $this->update_totals($order_id);
        
        $data['status'] = ($data['qty_deleted'] > 0 ) ? 1 : 0 ;

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

// ELIMINACIÓN
//-----------------------------------------------------------------------------

    /**
     * Eliminar registro de la tabla orders, y relacionados
     * 2020-12-11
     */
    function delete($order_id)
    {
        $qty_deleted = 0;

        //Verificar si se puede eliminar
        $deleteable = 1;

        //Tiene respuestas recibidas de wompi?
        $qty_responses = $this->Db_model->num_rows('post', "tipo_id = 54 AND padre_id = {$order_id}");
        if ( $qty_responses > 0 ) { $deleteable = 0; }

        if ( $deleteable )
        {
            //Tabla orders
            $this->db->where('id', $order_id);
            $this->db->delete('orders');
            
            $qty_deleted = $this->db->affected_rows();

            //Tabla order_product
            $this->db->where('order_id', $order_id);
            $this->db->delete('order_product');
        }

        return $qty_deleted;

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

// CHECKOUT Wompi
//-----------------------------------------------------------------------------

    /**
     * Array con todos los datos para construir el formulario que se envía a Wompi
     * para iniciar el proceso de pago.
     */
    function wompi_form_data($order_id)
    {
        //Registro del pedido
        $row = $this->Db_model->row_id('orders', $order_id);

        //Construir array
            $data['public-key'] = K_WPPK;
            $data['reference'] = $row->order_code;
            $data['description'] = $row->description;
            $data['amount-in-cents'] = intval($row->amount * 100);
            $data['currency'] = 'COP';  //Pesos colombianos
            $data['redirect-url'] = base_url('orders/result');

        return $data;
    }

    /**
     * Genera la firma que se envía en el Formulario para ir al pago en Wompi
     */
    function wompi_signature($row_order)
    {
        $signature_pre = K_PUAK;
        $signature_pre .= '~' . K_PUMI;
        $signature_pre .= '~' . $row_order->order_code;
        $signature_pre .= '~' . $row_order->amount;
        $signature_pre .= '~' . 'COP';
        
        return md5($signature_pre);
    }

    /**
     * Tomar y procesar los datos POST que envía Wompi a la url de eventos
     * 2020-12-09
     * url_confirmacion >> 'orders/confirmation_wompi'
     */
    function confirmation_wompi()
    {   
        //Identificar Pedido
        $confirmation_id = 0;
        $wompi_response = $this->wompi_response();
        $row = $this->row_by_code($wompi_response->data->transaction->reference);
        $row_confirmation = $this->save_confirmation($row, $wompi_response);

        if ( ! is_null($row) )
        {
            //Guardar respuesta de wompi en la tabla "post"
                $row_confirmation = $this->save_confirmation($row, $wompi_response);
                $confirmation_id = $row_confirmation->id;

            //Actualizar estado registro en la tabla orders
                $this->update_status($row->id, $wompi_response);

            //Enviar mensaje a administradores de tienda y al cliente
                $this->email_buyer($row->id);
                //if ( $order_status == 1 ) { $this->email_admon($row->id); }
        }

        return $confirmation_id;
    }

    /**
     * Crea un registro en la tabla post, con los datos recibidos tras en la 
     * ejecución de la URL de eventos por parte de Wompi
     */
    function save_confirmation($row, $wompi_response)
    {
        //Datos Wompi en formato JSON
            $json_confirmation_wompi = json_encode($wompi_response);
        
        //Construir registro para tabla Post
            $arr_row['tipo_id'] = 54;  //54: Confirmación de pago, Ver: items.category_id = 33
            $arr_row['contenido'] = 'Se ejecutó un evento desde Wompi';
            $arr_row['nombre_post'] = 'Confirmación ' . $wompi_response->data->transaction->reference;
            $arr_row['contenido_json'] = $json_confirmation_wompi;
            $arr_row['estado_id'] = ( $wompi_response->data->transaction->status == 'APPROVED' ) ? 1 : 0;
            $arr_row['padre_id'] = $row->id;
            $arr_row['fecha'] = date('Y-m-d H:i:s');
            $arr_row['texto_1'] = $wompi_response->data->transaction->status;
            $arr_row['editor_id'] = 1001;     //Wompi internal user
            $arr_row['usuario_id'] = 1001;    //Wompi internal user
            $arr_row['editado'] = date('Y-m-d H:i:s');
            $arr_row['creado'] = date('Y-m-d H:i:s');
        
        //Guardar
            //$condition = "type_id = 54 AND parent_id = {$row->id}";
            $condition = 'id = 0';  //Siempre guarda respuesta wompi
            $confirmation_id =$this->Db_model->save('post', $condition, $arr_row);

        //Row de confirmación
            $row_confirmation = $this->Db_model->row_id('post', $confirmation_id);
        
        return $row_confirmation;
    }

    /**
     * Objeto con respuesta de Wompi enviada a la URL de eventos
     * 2020-12-09
     */
    function wompi_response()
    {
        $input_wompi = file_get_contents('php://input');
        $wompi_response = json_decode($input_wompi);
        $wompi_response->ip_address = $this->input->ip_address();

        return $wompi_response;
    }

    function responses($order_id)
    {
        $this->db->select('id, texto_1 AS wompi_status, creado, contenido_json');
        $this->db->where('tipo_id', 54);    //Respuesta de Wompi
        $this->db->where('padre_id', $order_id);
        $query = $this->db->get('post');

        $responses = array();
        foreach ($query->result() as $row)
        {
            $response = json_decode($row->contenido_json);
            $response->response_id = $row->id;
            $response->response_created_at = $row->creado;

            $responses[] = $response;
        }
    
        return $responses;
    }

    /**
     * Actualiza el estado de una venta, dependiendo de la respuesta wompi
     * 2020-12-09
     */
    function update_status($order_id, $wompi_response)
    {
        $arr_row['status'] = ( $wompi_response->data->transaction->status == 'APPROVED' ) ? 1 : 5;
        $arr_row['wompi_status'] = $wompi_response->data->transaction->status;
        $arr_row['wompi_id'] = $wompi_response->data->transaction->id;
        $arr_row['wompi_payment_method_type'] = $wompi_response->data->transaction->payment_method_type;
        $arr_row['updated_at'] = date('Y-m-d H:i:s', $wompi_response->timestamp);
        $arr_row['updater_id'] = 1001;  //Wompi Automático

        $this->db->where('id', $order_id);   //Parent ID = Order ID
        $this->db->update('orders', $arr_row);
    }

    /**
     * Datos resultado del pago
     * 2020-12-05
     */
    function result_data()
    {
        $wompi_id = $this->input->get('id');
        $data = array('status' => 0, 'message' => 'Compra no identificada', 'success' => 0);
        $result = NULL;

        //Requerir datos de API Wompi
        $url_wompi_transaction = "https://sandbox.wompi.co/v1/transactions/{$wompi_id}";
        $json_wompi = $this->pml->get_url_content($url_wompi_transaction);

        if ( $json_wompi )
        {
            $wompi = json_decode($json_wompi);
            $result = $wompi->data;    
            //Idenficar registro de Order
            $row = $this->row_by_code($result->reference);
    
            $data = array('status' => 0, 'message' => 'Compra no identificada', 'success' => 0);
            $data['success'] = 0;
            $data['order_id'] = 0;
            $data['head_title'] = 'Pago no realizado';
    
            if ( ! is_null($row) )
            {
                $data['status'] = 1;
                $data['message'] = 'Resultado recibido';
                $data['order_id'] = $row->id;
    
                if ( $result->status == 'APPROVED' )
                {
                    $data['success'] = 1;
                    $data['head_title'] = 'Pago exitoso';
                }
            }
            $data['result'] = $result;

            //Actualizar registro tabla orders
            $data['order_updating'] = $this->update_wompi_status($row->id, $result);
        }

        return $data;
    }

    /**
     * Actualiza los campos de la tabla orders, relacionados con la información de wompi
     * 2020-12-05
     */
    function update_wompi_status($order_id, $result)
    {
        $data = array('status' => 0, 'qty_affected' => 0);

        $arr_row['wompi_status'] = $result->status;
        $arr_row['wompi_payment_method_type'] = $result->payment_method->type;
        $arr_row['wompi_id'] = $result->id;
        $arr_row['confirmed_at'] = $result->created_at;
        $arr_row['status'] = 5;

        if ( $result->status == 'APPROVED' ) { $arr_row['status'] = 1; }    //Si es APPROVED, se marca como Pago confirmado

        $this->db->where('id', $order_id);
        $this->db->update('orders', $arr_row);
        
        $data['qty_affected'] = $this->db->affected_rows();

        if ( $data['qty_affected'] > 0 ) { $data['status'] = 1;}

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
     * Tras la confirmación Wompi, se envía un mensaje de estado del pedido
     * al cliente
     * 
     * @param type $order_id
     */
    function email_buyer($order_id)
    {
        $data = array('status' => 0, 'message' => 'No enviado, Versión Local');

        if ( ENV == 'production' )
        {
            $row_order = $this->Db_model->row_id('orders', $order_id);
            $admin_email = $this->Db_model->field_id('sis_opcion', 25, 'valor'); //Opción 25

            //Email ejecutivo institución
            $institution_emails = $this->institution_emails($row_order->institution_id);
            if ( strlen($institution_emails) ) $admin_email .= ',' . $institution_emails;
                
            //Asunto de mensaje
                $subject = "Estado de la compra {$row_order->order_code}: " . $this->Item_model->name(7, $row_order->status);
            
            //Enviar Email
                $this->load->library('email');
                $config['mailtype'] = 'html';
    
                $this->email->initialize($config);
                $this->email->from('info@' . APP_DOMAIN, COMPANY_NAME);
                $this->email->to($row_order->email);
                $this->email->bcc($admin_email);
                $this->email->subject($subject);
                $this->email->message($this->message_buyer($row_order));
                
                $this->email->send();   //Enviar

            $data = array('status' => 1, 'message' => 'E-mail enviado');
        }

        return $data;
    }

    /**
     * String con emails de los ejecutivos de la institucion a la que está asociada la compra
     * 2020-12-07
     */
    function institution_emails($institution_id)
    {
        $institution_emails = '';

        $row_institution = $this->Db_model->row_id('institucion', $institution_id);
        if ( ! is_null($row_institution) ) {
            $row_user = $this->Db_model->row_id('usuario', $row_institution->ejecutivo_id);

            if ( ! is_null($row_user) )
            {
                $institution_emails = $row_user->email;
            }
        }

        return $institution_emails;
    }

    /**
     * String con contenido del mensaje del correo electrónico enviado al comprador
     * después de recibir la confirmación de pago
     */
    function message_buyer($row_order)
    {
        $data['row_order'] = $row_order;
        $data['products'] = $this->products($row_order->id);

        $style_file_path = PATH_RESOURCES . 'css/email.json';
        $style_file = fopen($style_file_path, 'r');
        $str_style = fread($style_file, filesize($style_file_path));
        $data['style'] = json_decode($str_style);
        
        $message = $this->load->view('orders/emails/message_buyer_v', $data, TRUE);
        
        return $message;
    }
}