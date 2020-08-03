<?php
class File_model extends CI_Model{

// INFO FUNCTIONS
//-----------------------------------------------------------------------------

    function basic($file_id)
    {
        $row = $this->Db_model->row_id('archivo', $file_id);

        $data['file_id'] = $file_id;
        $data['row'] = $row;
        $data['url_file'] = URL_UPLOADS . $row->carpeta . $row->nombre_archivo;
        $data['url_image'] = URL_UPLOADS . $row->carpeta . $row->nombre_archivo;
        $data['path_file'] = PATH_UPLOADS . $row->carpeta . $row->nombre_archivo;
        $data['head_title'] = substr($data['row']->title, 0, 50);

        return $data;
    }

// EXPLORE FUNCTIONS - files/explore
//-----------------------------------------------------------------------------
    
    /**
     * Array con los datos para la vista de exploración
     * 
     * @return string
     */
    function explore_data($num_page)
    {
        //Data inicial, de la tabla
            $data = $this->get($num_page);
        
        //Elemento de exploración
            $data['controller'] = 'files';                      //Nombre del controlador
            $data['cf'] = 'files/explore/';                      //Nombre del controlador
            $data['views_folder'] = 'files/explore/';           //Carpeta donde están las vistas de exploración
            
        //Vistas
            $data['head_title'] = 'Archivos';
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
     * String con condición WHERE SQL para filtrar files
     */
    function search_condition($filters)
    {
        $condition = NULL;
        
        //Tipo de post
        if ( $filters['type'] != '' ) { $condition .= "type_id = {$filters['type']} AND "; }
        
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
            $words_condition = $this->Search_model->words_condition($filters['q'], array('nombre_archivo', 'title', 'carpeta', 'description', 'palabras_clave'));
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
            $query = $this->db->get('archivo'); //Resultados totales
        } else {
            $query = $this->db->get('archivo', $per_page, $offset); //Resultados por página
        }
        
        return $query;
        
    }
    
    /**
     * Devuelve la cantidad de registros encontrados en la tabla con los filtros
     * establecidos en la búsqueda
     * 
     * @param type $filters
     * @return type
     */
    function search_num_rows($filters)
    {
        $query = $this->search($filters); //Para calcular el total de resultados
        return $query->num_rows();
    }
    
    /**
     * Devuelve segmento SQL
     * 
     * @param type $post_id
     * @return type 
     */
    function role_filter()
    {
        
        $role = $this->session->userdata('role');
        $condition = 'id = 0';  //Valor por defecto, ningún post, se obtendrían cero post.
        
        if ( $role <= 2 ) 
        {   //Desarrollador, todos los post
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
            'id' => 'ID Post',
            'nombre_archivo' => 'Nombre'
        );
        
        return $order_options;
    }
    
//UPLOADS
//---------------------------------------------------------------------------------------------------
    
    /**
     * Realiza el upload de un file al servidor, crea el registro asociado en
     * la tabla "file".
     * 2020-02-22
     */
    function upload($file_id = NULL)
    {
        $config_upload = $this->config_upload();
        $this->load->library('upload', $config_upload);

        if ( $this->upload->do_upload('file_field') )  //Campo "file_field" del formulario
        {
            //Guardar registro en la tabla "file"
                $row = $this->save($file_id, $this->upload->data());
                
            //Si es imagen, se generan miniaturas y edita imagen original
                if ( $row->es_imagen )
                {
                    $this->create_thumbnails($row->id);     //Crear miniaturas de la imagen
                    $this->modify_image($row->id);          //Modificar imagen original después de crear miniaturas
                }
            
            //Array resultado
                $data = array('status' => 1, 'message' => 'Archivo cargado');
                $data['upload_data'] = $this->upload->data();
                $data['row'] = $row;
        }
        else    //No se cargó
        {
            $data = array('status' => 0, 'message' => 'El archivo no fue cargado');
            $data['html'] = $this->upload->display_errors('<div class="alert alert-danger alert-dismissible fade show" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>', '</div>');
        }
        
        return $data;
    }
    
    /**
     * Configuración para cargue de files, algunas propiedades solo se aplican
     * para files de imagen.
     * @return boolean
     */
    function config_upload()
    {
        $this->load->helper('string');  //Para activar función random_string
        
        $config['upload_path'] = PATH_UPLOADS . date('Y/m');    //Carpeta año y mes
        $config['allowed_types'] = 'zip|gif|jpg|png|jpeg|pdf|json';
        $config['max_size']	= '3000';       //Tamaño máximo en Kilobytes
        $config['max_width']  = '5000';     //Ancho máxima en pixeles
        $config['max_height']  = '5000';    //Altura máxima en pixeles
        $config['nombre_archivo']  = $this->session->userdata('user_id') . '_' . date('YmdHis') . '_' . random_string('numeric', 3);
        
        return $config;
    }
    
// GESTIÓN DE REGISTROS EN LA TABLA file
//-----------------------------------------------------------------------------

    /**
     * Determina si un archivo puede ser editado o no por parte de un usuario en sesión
     * 2019-05-21
     */
    function editable($file_id)
    {
        $row = $this->Db_model->row_id('archivo', $file_id);

        $editable = FALSE;

        //Administradores y editores
        if ( $this->session->userdata('role') <= 2 ) { $editable = TRUE; }   

        //Es el creador, puede editarlo
        if ( $row->creator_id == $this->session->userdata('user_id') )
        {
            $editable = TRUE;
        }

        return $editable;
    }

    /**
     * Guardar registro del archivo en la tabla file
     */
    function save($file_id, $upload_data)
    {
        if ( is_null($file_id) ) {
            $file_id = $this->insert($upload_data);  //Crear nuevo registro
        } else {
            $this->change($file_id, $upload_data);  //Cambiar el archivo y modificar el registro
        }

        $row = $this->Db_model->row_id('archivo', $file_id);

        return $row;
    }
    
    /**
     * Crea el registro del file en la tabla file
     * @param type $upload_data
     */
    function insert($upload_data)
    {
        //Construir registro
            $arr_row['nombre_archivo'] = $upload_data['nombre_archivo'];
            $arr_row['ext'] = $upload_data['file_ext'];
            $arr_row['palabras_clave'] = $this->pml->if_strlen($this->input->post('keywords'), '');
            $arr_row['titulo_archivo'] = str_replace($upload_data['file_ext'], '', $upload_data['client_name']);  //Para quitar la extensión y punto
            $arr_row['carpeta'] = date('Y/m/');
            $arr_row['url'] = URL_UPLOADS . $arr_row['folder'] . $arr_row['nombre_archivo'];
            $arr_row['url_thumbnail'] = URL_UPLOADS . $arr_row['folder'] . 'sm_' .  $arr_row['nombre_archivo'];
            $arr_row['type'] = $upload_data['file_type'];
            $arr_row['es_imagen'] = $upload_data['es_imagen'];    //Definir si es imagen o no
            $arr_row['meta'] = json_encode($upload_data);
            $arr_row['editado'] = date('Y-m-d H:i:s');
            $arr_row['creador_id'] = $this->session->userdata('user_id');
            $arr_row['creado'] = date('Y-m-d H:i:s');
            $arr_row['editor_id'] = $this->session->userdata('user_id');
            
        //Insertar
            $this->db->insert('archivo', $arr_row);

        return $this->db->insert_id();
    }

    /**
     * Actualiza un registro en la tabla file
     * 2020-03-12
     */
    function update($file_id)
    {
        $data = array('status' => 0);

        //Guardar
            $arr_row = $this->Db_model->arr_row($file_id);
            $saved_id = $this->Db_model->save('archivo', "id = {$file_id}", $arr_row);

        //Actualizar resultado
            if ( $saved_id > 0 ){ $data = array('status' => 1); }
        
        return $data;
    }
    
    /**
     * Edita el registro del file, tabla file. El file en el servidor
     * es cambiado, y el registro en la tabla registro es actualizado.
     * 
     * @param type $upload_data
     */
    function change($file_id, $upload_data)
    {
        //Construir registro
            $arr_row['nombre_archivo'] = $upload_data['nombre_archivo'];
            $arr_row['carpeta'] = date('Y/m/');
            $arr_row['ext'] = $upload_data['file_ext'];
            $arr_row['type'] = $upload_data['file_type'];
            $arr_row['es_imagen'] = $upload_data['es_imagen'];    //Definir si es imagen o no
            $arr_row['meta'] = json_encode($upload_data);
            $arr_row['updater_id'] = $this->session->userdata('user_id');
            $arr_row['updated_at'] = date('Y-m-d H:i:s');
            
        //Actualizar
            $this->db->where('id', $file_id);
            $this->db->update('archivo', $arr_row);
            
        return $this->db->affected_rows();
    }
    
// ELIMINACIÓN
//-----------------------------------------------------------------------------
    
    /**
     * Elimina file del servidor y sus miniaturas y el el registro en la 
     * tabla file.
     * 
     * @param type $file_id
     */
    function delete($file_id)
    {   
        //Eliminar files del servidor
            $row_file = $this->Db_model->row_id('archivo', $file_id);
            if ( ! is_null($row_file) ) 
            {
                $this->unlink($row_file->carpeta, $row_file->nombre_archivo);
            }
        
        //Eliminar registros de la base de datos
            $this->delete_rows($file_id);
    }
    
    /**
     * Elimina de la BD los registros asociados al file
     */
    function delete_rows($file_id)
    {
        //Desvincular registro de files con otros elementos
            $this->delete_related_rows($file_id);
        
        //Tabla file
            if ( $file_id > 0 )
            {
                $this->db->where('id', $file_id);
                $this->db->delete('archivo');
            }
    }
    
    /**
     * Elimina los registros que relacionan al file con otros elementos de la
     * base de datos. Tambien edita los fields de registros referentes al 
     * file_id
     */
    function delete_related_rows($file_id)
    {
        //Imagen de perfil de usuario
            $arr_row['image_id'] = 0;
            $arr_row['url_image'] = '';
            $arr_row['url_thumbnail'] = '';
            $this->db->where('image_id', $file_id);
            $this->db->update('user', $arr_row);
    }
    
// GESTIÓN DE IMÁGENES
//-----------------------------------------------------------------------------
    
    /**
     * Devuelve un array con los atributos de una imagen, para ser usado con la funcion img();
     * 2019-10-30
     * 
     */
    function att_img($file_id, $prefix = '')
    {
        $att_img = array(
            'src' => URL_IMG . 'app/sm_nd_square.png',
            'alt' => 'Imagen no disponible',
            'onerror' => "this.src='" . URL_IMG . 'app/' . $prefix . 'nd_square.png' . "'"
        );
        
        $row_file = $this->Db_model->row_id('archivo', $file_id);

        if ( ! is_null($row_file) )
        {
            $att_img = array(
                'src' => URL_UPLOADS . $row_file->carpeta . $prefix . $row_file->nombre_archivo,
                'alt' => $row_file->nombre_archivo,
                'style' => 'width: 100%',
                'onerror' => "this.src='" . URL_IMG . 'app/' . $prefix . 'nd_square.png' . "'"
            );
        }
        
        return $att_img;
    }
    
    /**
     * Array con atributos de la miniatura de un archivo imagen
     * 2019-08-01
     */
    function att_thumbnail($file_id)
    {
        $src = URL_IMG . 'app/sm_nd_square.png';

        $row_file = $this->Db_model->row_id('archivo', $file_id);

        if ( ! is_null($row_file))
        {
            $src = URL_UPLOADS . $row_file->carpeta . 'sm_' . $row_file->nombre_archivo;
            if ( ! $row_file->es_imagen ) { $src = URL_IMG . 'app/file.png'; }
        }
        
        $att_img = array(
            'src' => $src,
            'alt' => 'Miniatura',
            'style' => 'width: 100%',
        );
        
        return $att_img;
    }
    
    function row_img($file_id, $prefix = '')
    {
        $row_img = NULL;
        
        $select = '*, CONCAT("' . URL_UPLOADS . '", (folder), "' . $prefix . '", (nombre_archivo)) AS src';
        
        $this->db->select($select);
        $this->db->where('id', $file_id);
        $query = $this->db->get('archivo');
        
        if ( $query->num_rows() > 0 ) { $row_img = $query->row(); }
        
        return $row_img;
        
    }
    
    /**
     * Modificar la imagen original con un tamaño específico máximo, tomando el 
     * file.id
     * 
     * @param type $file_id
     * @return type
     */
    function modify_image($file_id)
    {
        $row_file = $this->Db_model->row_id('archivo', $file_id);
        $modified = $this->mod_original($row_file->carpeta, $row_file->nombre_archivo);
        
        return $modified;
    }
    
    /**
     * Modifica la imagen original con un tamaño específico máximo
     * 
     * @param type $row_file
     */
    function mod_original($folder, $nombre_archivo)
    {
        $modified = 0;
        $image_size = getimagesize(PATH_UPLOADS . $folder . $nombre_archivo);
        
        $width = 800;   //Tamaño 800px
        
        $quan_condiciones = 0;
        
        if ( $image_size[0] > $width ) { $quan_condiciones++; }
        if ( $image_size[1] > $width ) { $quan_condiciones++; }
        
        if ( $quan_condiciones > 0 )
        {
            
            $modified = 1;
            
            $this->load->library('image_lib');

            //Config
                $config['image_library'] = 'gd2';
                $config['source_image'] = PATH_UPLOADS . $folder . $nombre_archivo;
                $config['maintain_ratio'] = TRUE;
                $config['width'] = $width;
                $config['height'] = $width;
                //$config['quality'] = 100;

                $this->image_lib->initialize($config);
                $this->image_lib->resize();
                $this->image_lib->clear();
        }
        
        return $modified;
        
    }
    
// MINIATURAS
//-----------------------------------------------------------------------------
    
    /**
     * Crea los files miniaturas de una imagen
     */
    function create_thumbnails($file_id)
    {
        $sizes = $this->sizes();
        $row_file = $this->Db_model->row_id('archivo', $file_id);
        
        foreach( $sizes as $prefix => $width)
        {
            $this->create_thumbnail($row_file, $prefix, $width);
        }
    }
    
    /**
     * Crea una miniatura de una imagen
     * 2020-06-03
     */
    function create_thumbnail($row_file, $prefix, $width)
    {
        $this->load->library('image_lib');
        
        //Config
            $config['image_library'] = 'gd2';
            $config['source_image'] = PATH_UPLOADS . $row_file->carpeta . $row_file->nombre_archivo;
            $config['new_image'] = PATH_UPLOADS . $row_file->carpeta . $prefix . '_' . $row_file->nombre_archivo;
            $config['maintain_ratio'] = TRUE;
            
            list($original_width, $original_height) = getimagesize(PATH_UPLOADS . $row_file->carpeta . $row_file->nombre_archivo);
            if ( $original_width > $original_height ) {
                // Landscape
                $config['height'] = $width;
            } else {
                $config['width'] = $width;
                // Portrait or Square
            }

            $this->image_lib->initialize($config);
            $this->image_lib->resize();
            $this->image_lib->clear();
    }
    
    /**
     * Array con los prefixes sizes en pixeles de las diferentes miniaturas que
     * se generan para los files de imagen.
     *
     */
    function sizes()
    {
        $sizes = array('sm' => 300);
        return $sizes;
    }
    
    /**
     * Array con los prefixes de files miniatura de files de imágenes, se
     * incluye el string vacío '', es decir sin prefijo para incluir el file
     * original.
     */
    function prefixes()
    {        
        $prefixs = array('', 'sm_');
        return $prefixs;
    }
    
    /**
     * Le quita el prefijo a un nombre de file
     */
    function remove_prefix($nombre_archivo)
    {
        $prefixs = $this->prefixes();
        $without_prefix = $nombre_archivo;
        
        foreach ( $prefixs as $prefix ) 
        {
            $prefix = $prefix . '_';
            $without_prefix = str_replace($prefix, '', $without_prefix);
        }
        
        return $without_prefix;
    }
    
    /**
     * Recorta una imagen con unos datos específicos, actualiza las miniaturas
     * según el recorte.
     * 
     * @param type $file_id
     * @return type
     */
    function crop($file_id)
    {
        
        //Valores iniciales
            $row = $this->Db_model->row_id('archivo', $file_id);
            $data = array('status' => 0, 'message' => 'Imagen NO recortada');
        
        //Configuración de recorte
            $this->load->library('image_lib');
            
            $config['image_library'] = 'gd2';
            $config['library_path'] = '/usr/X11R6/bin/';
            $config['source_image'] = PATH_UPLOADS . $row->carpeta . $row->nombre_archivo;
            $config['width'] = $this->input->post('width');
            $config['height'] = $this->input->post('height');
            $config['x_axis'] = $this->input->post('x_axis');
            $config['y_axis'] = $this->input->post('y_axis');
            $config['maintain_ratio'] = FALSE;
        
            $this->image_lib->initialize($config);
            
        //Ejecutar recorte
            if ( $this->image_lib->crop() )
            {
                $this->create_thumbnails($file_id);
                $data = array('status' => 1, 'message' => 'Imagen recortada');
            } else {
                $data['html'] = $this->image_lib->display_errors();
            }
        
        return $data;
    }
    
//GESTIÓN DE ARCHIVOS EN CARPETAS
//---------------------------------------------------------------------------------------------------
    
    /**
     * Listado de files en una folder
     * 
     * @param type $year
     * @param type $month
     * @return type
     */
    function files($year, $month)
    {
        $this->load->helper('file');
        $files = get_filenames(PATH_UPLOADS . $year . '/' . $month);
        
        return $files;
    }
    
    /**
     * Elimina los files que no están siendo utilizados en la herramienta
     * Se considera no usado si no tiene registro asociado en la tabla "file"
     * 
     * @param type $year
     * @param type $month
     * @return type
     */
    function unlink_unused($year, $month)
    {
        $quan_deleted = 0;
        $this->load->helper('file');
        $files = get_filenames(PATH_UPLOADS . $year . '/' . $month);
        
        $folder = "{$year}/{$month}/";
        
        foreach( $files as $nombre_archivo )
        {    
            $without_prefix = $this->remove_prefix($nombre_archivo);
            $has_row = $this->has_row($folder, $without_prefix);
            
            if ( ! $has_row ) { 
                $quan_deleted += $this->unlink($folder, $without_prefix);
            }
        }
        
        return $quan_deleted;
    }
    
    /**
     * Elimina un archivo y sus miniaturas del servidor
     * 
     * @param type $folder
     * @param type $nombre_archivo
     */
    function unlink($folder, $nombre_archivo)
    {
        $quan_deleted = 0;
        $prefixs = $this->prefixes();  //Prefijos de miniaturas
        
        foreach( $prefixs as $prefix )
        {
            $path = PATH_UPLOADS . "{$folder}{$prefix}{$nombre_archivo}";
            if ( file_exists($path) ) 
            {
                unlink($path);
                $quan_deleted++;
            }
        }
        
        return $quan_deleted;
    }
    
    /**
     * Devuelve 1/0, verifica si un file tiene registro relacionado
     * en la tabla "file"
     * 
     * @param type $folder
     * @param type $nombre_archivo
     * @return int
     */
    function has_row($folder, $nombre_archivo)
    {
        $has_row = 0;
        
        $this->db->where('carpeta', $folder);
        $this->db->where('nombre_archivo', $nombre_archivo);
        $query = $this->db->get('archivo');
        
        if ( $query->num_rows() > 0 ) { $has_row = 1; }
        
        return $has_row;
    }

    function massive_thumbnails($folder)
    {
        $this->db->select('id, nombre_archivo, folder');
        $this->db->where('es_imagen', 1);
        $this->db->where("folder LIKE '%{$folder}%'");
        $images = $this->db->get('archivo');

        $thumbnails = array();

        foreach ($images->result() as $row)
        {
            $file_path = PATH_UPLOADS . $row->carpeta . $row->nombre_archivo;
            $thumbnails[] = array('file_path' => $file_path);
            $this->create_thumbnails($row->id);
        }

        return $thumbnails;
    }

// METADATA
//-----------------------------------------------------------------------------

    /**
     * List of file tags, table file_meta
     * 2020-05-07
     */
    function tags($file_id)
    {
        $this->db->select('id AS tag_id, name, slug');
        $this->db->where("id IN (SELECT related_1 FROM file_meta WHERE file_id = {$file_id} AND type_id = 27)");
        $tags = $this->db->get('tag');

        return $tags;
    }
}