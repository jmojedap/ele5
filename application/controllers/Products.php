<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Products extends CI_Controller{
    
    function __construct() 
    {
        parent::__construct();

        $this->load->model('Product_model');
        
        //Para definir hora local
        date_default_timezone_set("America/Bogota");
    }
    
    function index($product_id)
    {
        redirect("products/info/{$product_id}");
    }
    
//EXPLORE
//---------------------------------------------------------------------------------------------------

    /**
     * Vista listado de productos, filtros exploración
     * 2020-12-12
     */
    function explore($num_page = 1)
    {        
        //Identificar filtros de búsqueda
            $this->load->model('Search_model');
            $filters = $this->Search_model->filters();

        //Datos básicos de la exploración
            $data = $this->Product_model->explore_data($filters, $num_page);
        
        //Opciones de filtros de búsqueda
            /*$data['options_level'] = $this->Item_model->options('category_id = 3', 'Nivel escolar');
            $data['options_teacher'] = $this->App_model->options_user("role > 10 AND role < 20 AND institution_id = {$this->session->userdata('institution_id')}");
            $data['options_generation'] = $this->App_model->options_generation();*/
            
        //Arrays con valores para contenido en lista
            //$data['arr_levels'] = $this->Item_model->arr_cod('category_id = 3');
            
        //Cargar vista
            $this->App_model->view(TPL_ADMIN_NEW, $data);
    }

    /**
     * JSON
     * Listado de productos
     */
    function get($num_page, $per_page = 10)
    {
        //Identificar filtros de búsqueda
        $this->load->model('Search_model');
        $filters = $this->Search_model->filters();

        $data = $this->Product_model->get($filters, $num_page, $per_page);

        //Salida JSON
        $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }

    /**
     * AJAX JSON
     * Eliminar un conjunto de posts seleccionados. Limintada para roles 11 y 13.
     */
    function delete_selected()
    {
        $data = array('status' => 0, 'qty_deleted' => 0);
        $selected = explode(',', $this->input->post('selected'));
        
        foreach ( $selected as $row_id ) 
        {
            $data['qty_deleted'] += $this->Product_model->delete($row_id);
        }

        if ( $data['qty_deleted'] > 0 ) { $data['status'] = 1;}
        
        //Salida
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
            $results_total = $this->Product_model->export($filters);
        
        //Preparar datos
            $datos['nombre_hoja'] = 'Productos';
            $datos['query'] = $results_total;
            
        //Preparar archivo
            $objWriter = $this->Pcrn_excel->archivo_query($datos);
        
        $data['objWriter'] = $objWriter;
        $data['nombre_archivo'] = date('Ymd_His'). '_productos'; //save our workbook as this file name
        
        $this->load->view('comunes/descargar_phpexcel_v', $data);
            
    }
    
    
// CRUD
//-----------------------------------------------------------------------------

    /**
     * Formulario para la creación de un nuevo grupo
     * 
     * @param type $tipo_rol
     */
    function add()
    {
        //Variables generales
            $data['head_title'] = 'Productos';
            $data['head_subtitle'] = 'Nuevo';
            $data['nav_2'] = 'products/explore/menu_v';
            $data['view_a'] = 'products/add_v';

        $this->App_model->view(TPL_ADMIN_NEW, $data);
    }

    /**
     * AJAX JSON
     * Toma datos de POST e inserta un registro en la tabla group. 
     * 2019-10-29
     */ 
    function insert()
    {
        $data = $this->Product_model->insert();
        $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }
    
    /**
     * Información general del grupo
     */
    function info($product_id)
    {        
        //Datos básicos
        $data = $this->Product_model->basic($product_id);
        
        //Variables específicas
        $data['head_subtitle'] = 'Información general';
        $data['view_a'] = 'products/info_v';
        
        $this->App_model->view(TPL_ADMIN_NEW, $data);
    }
    
// EDICIÓN Y ACTUALIZACIÓN
//-----------------------------------------------------------------------------

    /**
     * Formulario para la edición de los datos de un grupo.
     * 2016-11-05
     */
    function edit($product_id)
    {
        $this->load->model('Kit_model');

        //Datos básicos
            $data = $this->Product_model->basic($product_id);
        
        //Variables cargue vista
            $data['nav_2'] = 'products/menu_v';
            $data['view_a'] = 'products/edit_v';
        
        $this->App_model->view(TPL_ADMIN_NEW, $data);
    }    

    /**
     * POST JSON
     * 
     * @param type $product_id
     */
    function update($product_id)
    {
        $data = $this->Product_model->update($product_id);
        $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }

// IMAGEN PRINCIPAL DEL PRODUCTO
//-----------------------------------------------------------------------------

    function image($product_id)
    {
        $data = $this->Product_model->basic($product_id);        

        $data['view_a'] = 'products/image/image_v';
        $data['nav_2'] = 'products/menu_v';
        $data['subtitle_head'] = 'Imagen principal';
        $this->App_model->view(TPL_ADMIN_NEW, $data);
    }

    function cropping($product_id)
    {
        $data = $this->Product_model->basic($product_id);        

        $data['image_id'] = $data['row']->image_id;
        $data['url_image'] = $data['att_img']['src'];
        $data['back_destination'] = "products/image/{$product_id}";

        $data['view_a'] = 'files/cropping_v';
        $data['nav_2'] = 'products/menu_v';
        $data['subtitle_head'] = 'Imagen principal del producto';
        $this->App_model->view(TPL_ADMIN_NEW, $data);
    }

    /**
     * AJAX JSON
     * Carga file de image y se la asigna a un post.
     * 2020-02-22
     */
    function set_image($product_id)
    {
        //Cargue
        $this->load->model('File_model');
        $data_upload = $this->File_model->upload();
        
        $data = $data_upload;
        if ( $data_upload['status'] )
        {
            $this->Product_model->remove_image($product_id);                                  //Quitar image actual, si tiene una
            $data = $this->Product_model->set_image($product_id, $data_upload['row']->id);    //Asignar imagen nueva
        }

        $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }

    /**
     * Desasigna y elimina la image asociada a un post, si la tiene.
     */
    function remove_image($product_id)
    {
        $data = $this->Product_model->remove_image($product_id);
        $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }

// IMPORTACIÓN DE PRODUCTOS
//-----------------------------------------------------------------------------

    /**
     * Mostrar formulario de importación de products
     * con archivo Excel. El resultado del formulario se envía a 
     * 'products/import_e'
     */
    function import($type = 'general')
    {
        $data = $this->Product_model->import_config($type);

        $data['url_file'] = URL_ASSETS . 'formatos_cargue/' . $data['template_file_name'];

        $data['head_title'] = 'Productos';
        $data['nav_2'] = 'products/explore/menu_v';
        $data['view_a'] = 'common/import_v';
        
        $this->App_model->view(TPL_ADMIN_NEW, $data);
    }

    //Ejecuta la importación de products con archivo Excel
    function import_e()
    {
        //Proceso
        $this->load->library('excel_new');            
        $imported_data = $this->excel_new->arr_sheet_default($this->input->post('sheet_name'));
        
        if ( $imported_data['status'] == 1 )
        {
            $data = $this->Product_model->import($imported_data['arr_sheet']);
        }

        //Cargue de variables
            $data['status'] = $imported_data['status'];
            $data['message'] = $imported_data['message'];
            $data['arr_sheet'] = $imported_data['arr_sheet'];
            $data['sheet_name'] = $this->input->post('sheet_name');
            $data['back_destination'] = "products/explore/";
        
        //Cargar vista
            $data['head_title'] = 'Productos';
            $data['head_subtitle'] = 'Resultado importación';
            $data['view_a'] = 'common/import_result_v';
            $data['nav_2'] = 'products/explore/menu_v';

        $this->App_model->view(TPL_ADMIN_NEW, $data);
    }

// CATÁLOGO
//-----------------------------------------------------------------------------

    function catalog($num_page = 1)
    {
        //Datos básicos de la exploración
            //$this->load->model('Noticia_model');
        
        //Variables
            $data['head_title'] = 'Productos';
            $data['view_a'] = 'products/catalog_v';
            
        //Cargar vista
            $this->App_model->view(TPL_ADMIN_NEW, $data);
    }

    function get_catalog($product_family, $num_page = 1)
    {
        $data = $this->Product_model->get_catalog($product_family, $num_page);

        //Salida JSON
        $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }

    /**
     * Detalle del producto, información detallada para venta
     */
    function details($product_id)
    {
        $data = $this->Product_model->basic($product_id);

        //Variables
        $data['flipbooks'] = $this->Product_model->assigned_flipbooks($product_id);
        unset($data['nav_2']);  //Quitar menú de administración
        $data['view_a'] = 'products/details_v';
            
        //Cargar vista
            $this->App_model->view(TPL_ADMIN_NEW, $data);
    }

// METADATOS
//-----------------------------------------------------------------------------

    function delete_meta($product_id, $meta_id)
    {
        $data = $this->Product_model->delete_meta($product_id, $meta_id);
        $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }

// CONTENIDOS ASIGNADOS
//-----------------------------------------------------------------------------

    /**
     * Contenidos digitales asignados a un producto
     * 2020-04-18
     */
    function posts($product_id)
    {
        $data = $this->Product_model->basic($product_id);

        $data['posts'] = $this->Product_model->assigned_posts($product_id);
        $data['options_post'] = $this->App_model->options_post('type_id IN (5,8)', 'n', 'Contenido');

        $data['view_a'] = 'products/posts_v';

        $this->App_model->view(TPL_ADMIN_NEW, $data);
    }

    /**
     * Agrega un post a un producto
     * 2020-05-22
     */
    function add_post($product_id, $post_id)
    {
        $data = $this->Product_model->add_post($product_id, $post_id);

        //Salida JSON
        $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }

// FLIPBOOKS ASIGNADOS
//-----------------------------------------------------------------------------

    /**
     * Contenidos digitales asignados a un producto
     * 2020-04-18
     */
    function flipbooks($product_id)
    {
        $data = $this->Product_model->basic($product_id);

        $data['flipbooks'] = $this->Product_model->assigned_flipbooks($product_id);
        //$data['options_post'] = $this->App_model->options_post('type_id IN (5,8)', 'n', 'Contenido');

        $data['view_a'] = 'products/flipbooks_v';

        $this->App_model->view(TPL_ADMIN_NEW, $data);
    }

    /**
     * Agrega un post a un producto
     * 2020-05-22
     */
    function add_flipbook($product_id, $flipbook_id)
    {
        $data = $this->Product_model->add_flipbook($product_id, $flipbook_id);

        //Salida JSON
        $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }

// INSTITUCIONES ASIGNADAS
//-----------------------------------------------------------------------------

    /**
     * Instituciones asignadas a un producto
     * 2020-06-25
     */
    function institutions($product_id)
    {
        $data = $this->Product_model->basic($product_id);

        $data['institutions'] = $this->Product_model->assigned_institutions($product_id);

        $data['view_a'] = 'products/institutions_v';

        $this->App_model->view(TPL_ADMIN_NEW, $data);
    }

    /**
     * Agrega una institución a un producto
     * 2020-05-22
     */
    function add_institution($product_id, $institution_id)
    {
        $data = $this->Product_model->add_institution($product_id, $institution_id);

        //Salida JSON
        $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }

    function get_by_institution($institution_id, $level = NULL)
    {
        $products = $this->Product_model->get_by_institution($institution_id, $level);
        $data['list'] = $products->result();

        //Salida JSON
        $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }
}