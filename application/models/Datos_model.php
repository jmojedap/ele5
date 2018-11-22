<?php

class Datos_model extends CI_Model{
    
    function __construct(){
        parent::__construct();
        
    }
    
//ENUNCIADOS
//---------------------------------------------------------------------------------------------------

    /**
     * Búsqueda de enunciados
     * 
     * @param type $busqueda
     * @param type $per_page
     * @param type $offset
     * @return type
     */
    function buscar_enunciados($busqueda, $per_page = NULL, $offset = NULL)
    {

        //Construir búsqueda
        //Crear array con términos de búsqueda
            if ( strlen($busqueda['q']) > 2 ){
                
                $campos_posts = array('nombre_post', 'contenido');
                
                $concat_campos = $this->Busqueda_model->concat_campos($campos_posts);
                $palabras = $this->Busqueda_model->palabras($busqueda['q']);

                foreach ($palabras as $palabra) {
                    $this->db->like("CONCAT({$concat_campos})", $palabra);
                }
            }
        
        //Especificaciones de consulta
            $this->db->select('post.*');
            $this->db->where('tipo_id', 4401);  //Tipo enunciado
            $this->db->order_by('id', 'DESC');
            
        //Otros filtros
            if ( $busqueda['e'] != '' ) { $this->db->where('editado', $busqueda['e']); }    //Editado
            if ( $busqueda['condicion'] != '' ) { $this->db->where($busqueda['condicion']); }    //Condición especial
            
        //Obtener resultados
        if ( is_null($per_page) ){
            $query = $this->db->get('post'); //Resultados totales
        } else {
            $query = $this->db->get('post', $per_page, $offset); //Resultados por página
        }
        
        return $query;
    }
    
    function enunciado_basico($enunciado_id)
    {
        $row = $this->Pcrn->registro_id('post', $enunciado_id);
        
        $basico['enunciado_id'] = $enunciado_id;
        $basico['row'] = $row;
        $basico['titulo_pagina'] = $row->nombre_post;
        $basico['vista_a'] = 'datos/enunciados/enunciado_v';
        
        return $basico;
    }
    
    function crud_competencias()
    {
        // competencias, tabla item categoría
            $categoria_id = 4;

        $crud = new grocery_CRUD();
        $crud->set_table('item');
        $crud->set_subject('competencia');
        $crud->columns('id', 'item_grupo', 'orden', 'item', 'descripcion');
        $crud->unset_add();
        $crud->unset_print();

        //Filtro
            $crud->where('item.categoria_id', $categoria_id);
            $crud->order_by('item_grupo, orden', 'ASC');
            //$crud->order_by('orden', 'ASC');
            $crud->where('item.item_grupo IS NOT NULL');

        //Títulos de campo
            $crud->display_as('item','Competencia');
            $crud->display_as('descripcion','Descripción');
            $crud->display_as('item_grupo', 'Área');

        //Vistas columnas

        //Relaciones
            $crud->set_relation('item_grupo', 'item', 'item', 'categoria_id = 1');

        //Reglas de validación
            $crud->set_rules('item', 'Competencia','required');

        //Formularios edición
            $crud->edit_fields('item', 'orden', 'item_grupo', 'descripcion', 'categoria_id');
            $crud->add_fields('item', 'orden', 'item_grupo', 'descripcion', 'categoria_id');

        //Valores por defecto
            $crud->field_type('categoria_id', 'hidden', $categoria_id);

        $output = $crud->render();
        
        return $output;
    }
    
    function crud_enunciados(){
        
        $this->load->config('grocery_crud');
        $this->config->set_item('grocery_crud_file_upload_allow_file_types', 'gif|jpeg|jpg|png');
        $this->config->set_item('grocery_crud_file_upload_max_file_size', '5MB');
        
        $crud = new grocery_CRUD();    
        $crud->set_table('post');
        $crud->columns('nombre_post', 'contenido', 'texto_2', 'usuario_id', 'editado', 'creado');
        $crud->set_subject('enunciado');
        $crud->unset_export();
        $crud->unset_print();
        $crud->unset_back_to_list();       
        $crud->where('id = 0');

        //Títulos de columnas
            $crud->display_as('texto_2', 'Imagen enunciado');
            $crud->display_as('usuario_id', 'Creado por');
            $crud->display_as('editado', 'Editado');
            $crud->display_as('nombre_post', 'Título');

        //Fields
            $crud->add_fields(
                    'tipo_id',
                    'nombre_post',
                    'contenido',
                    'texto_2',          //Antes, archivo_imagen
                    'referente_1_id',   //Antes, institucion_id
                    'usuario_id',
                    'creado',
                    'editor_id',
                    'editado'
            );
            
            $crud->edit_fields(
                    'nombre_post',
                    'contenido',
                    'texto_2',          //Antes, archivo_imagen
                    'referente_1_id',   //Antes, institucion_id
                    'editor_id',
                    'editado'
                );
            
        //Validación
            $crud->required_fields('nombre_post', 'contenido');

        //Preparación de campos
            $crud->set_field_upload('texto_2', RUTA_UPLOADS . 'enunciados');

        //Valores por defecto
            $crud->field_type('tipo_id', 'hidden', 4401);   //Post tipo enunciado
            $crud->field_type('editor_id', 'hidden', $this->session->userdata('usuario_id'));
            $crud->field_type('usuario_id', 'hidden', $this->session->userdata('usuario_id'));
            $crud->field_type('creado', 'hidden', date('Y-m-d H:i:s'));
            $crud->field_type('editado', 'hidden', date('Y-m-d H:i:s'));
            $crud->field_type('referente_1_id', 'hidden', $this->session->userdata('institucion_id'));

        $output = $crud->render();

        return $output;
    }
    
    function enunciado_eliminar($enunciado_id)
    {
        //Desvincular de tablas relacionadas
            $registro['enunciado_id'] = NULL;
            
            $this->db->where('post_id', $enunciado_id);
            $this->db->update('pregunta', $registro);
        
        //Eliminar de tabla
            $this->db->where('id', $enunciado_id);
            $this->db->delete('post');
    }
    
//AYUDAS
//---------------------------------------------------------------------------------------------------
    
    function ayuda_basico($ayuda_id)
    {
        $row = $this->Pcrn->registro_id('item', $ayuda_id);
        
        $basico['ayuda_id'] = $ayuda_id;
        $basico['row'] = $row;
        $basico['titulo_pagina'] = $row->item_largo;
        $basico['vista_a'] = 'datos/ayudas/ayuda_v';
        
        return $basico;
    }
    
    function crud_ayudas(){
        
        $this->load->config('grocery_crud');
        
        $crud = new grocery_CRUD();    
        $crud->set_table('item');
        $crud->columns('item');
        $crud->set_subject('ayuda');
        $crud->unset_export();
        $crud->unset_print();         
        $crud->unset_back_to_list();       

        //Títulos de columnas
            $crud->display_as('item', 'Título');
            $crud->display_as('item_largo', 'Título');
            $crud->display_as('item_corto', 'Roles');
            $crud->display_as('abreviatura', 'ID post');    //ID Post en la DB de WordPress
            $crud->display_as('descripcion', 'Descripción');

        //Fields
            $crud->add_fields(
                'item_largo',
                'descripcion',
                'abreviatura',
                'item_corto',                
                'categoria_id'
            );
            
            $crud->edit_fields(
                'item_largo',
                'descripcion',
                'abreviatura',
                'item_corto',                
                'categoria_id'
            );
            
        //Validación
            $crud->required_fields('item_largo', 'item_corto', 'descripcion', 'abreviatura');

        //Valores por defecto
            $crud->field_type('categoria_id', 'hidden', 14);    //Ayudas

        $output = $crud->render();

        return $output;
    }
    
    
    //* Área, tabla item categoría 1
    function crud_areas()
    {

        $this->load->config('grocery_crud');
        
        $crud = new grocery_CRUD();
        $crud->set_table('item');
        $crud->set_subject('área');
        $crud->columns('id', 'item', 'item_corto', 'descripcion', 'slug');
        $crud->unset_export();
        $crud->unset_print();

        //Filtro
        $crud->where('categoria_id', 1);
        $crud->order_by('item', 'ASC');

        //Títulos de campo
        $crud->display_as('id', 'ID área');
        $crud->display_as('item','Nombre área');
        $crud->display_as('descripcion','Descripción');
        $crud->display_as('item_corto', 'Nombre corto');

        //Vistas columnas

        //Relaciones
        //$crud->set_relation('categoria_id', 'categoria', 'nombre_categoria');

        //Reglas de validación
        $crud->set_rules('item', 'Nombre item','required');

        //Formulario edición
        $crud->edit_fields('item', 'item_corto', 'descripcion', 'slug', 'categoria_id');

        //Formularión adición
        $crud->add_fields('item', 'item_corto', 'descripcion', 'slug','categoria_id');

        //Valores por defecto
        $crud->field_type('categoria_id', 'hidden', 1);

        $output = $crud->render();

        return $output;
    }
    
    function crud_componentes()
    {

        $this->load->config('grocery_crud');
        
        // componentes, tabla item categoría
        $categoria_id = 8;

        $crud = new grocery_CRUD();
        $crud->set_table('item');
        $crud->set_subject('componente');
        $crud->columns('id', 'item', 'item_grupo', 'descripcion');
        //$crud->unset_export();
        $crud->unset_print();

        //Filtro
        $crud->where('item.categoria_id', $categoria_id);
        $crud->order_by('item', 'ASC');

        //Títulos de campo
            $crud->display_as('item','Componente');
            $crud->display_as('descripcion','Descripción');
            $crud->display_as('item_grupo', 'Área');

        //Vistas columnas

        //Relaciones
            $crud->set_relation('item_grupo', 'item', 'item', 'categoria_id = 1');

        //Reglas de validación
        $crud->set_rules('item', 'Componente','required');

        //Formulario edición
            $crud->edit_fields('item', 'item_grupo', 'descripcion', 'categoria_id');

        //Formularión adición
            $crud->add_fields('item', 'item_grupo', 'descripcion', 'categoria_id');

        //Valores por defecto
        $crud->field_type('categoria_id', 'hidden', $categoria_id);

        $output = $crud->render();

        return $output;
    }
    
    function crud_tipos_recurso()
    {
       
        //Referencia
        $this->load->model('Esp');
        $opciones_tipo = $this->Esp->arr_tipos_recurso();
    
        $categoria_id = 20;

        $this->load->config('grocery_crud');
        
        $crud = new grocery_CRUD();
        $crud->set_table('item');
        $crud->set_subject('tipo de recurso');
        $crud->columns('item', 'id_interno', 'slug', 'abreviatura', 'item_grupo');
        $crud->unset_export();
        $crud->unset_print();
        $crud->unset_read();

        //Filtro
            $crud->where('categoria_id', $categoria_id);
            $crud->order_by('item', 'ASC');

        //Títulos de campo
            $crud->display_as('item','Tipo de recurso');
            $crud->display_as('slug', 'Nombre carpeta');
            $crud->display_as('id_interno', 'Cód.');
            $crud->display_as('item_grupo', 'Categoría');

        //Reglas de validación
            $crud->set_rules('item', 'Tipo de recurso','required');

        //Formulario edición
            $crud->edit_fields('item', 'categoria_id', 'id_interno', 'slug', 'abreviatura', 'item_grupo');

        //Formularión adición
            $crud->add_fields('item', 'categoria_id', 'id_interno', 'slug', 'abreviatura', 'item_grupo');

        //Valores por defecto
            $crud->field_type('categoria_id', 'hidden', $categoria_id);
            $crud->field_type('item_grupo', 'dropdown', $opciones_tipo);
        

        $output = $crud->render();

        return $output;
    }

//---------------------------------------------------------------------------------------------------
//FIN AYUDAS
    
    function crud_report_usuarios_01()
    {
        //Grocery crud
        $this->load->library('grocery_CRUD');
        
        $crud = new grocery_CRUD();
        $crud->set_table('usuario');
        $crud->set_subject('usuario');
        $crud->unset_edit();
        $crud->unset_print();
        $crud->unset_read();
        $crud->unset_delete();
        
        $crud->where('rol_id', 6);  //Es estudiante
        
        //Campos
            $crud->columns(
                    'id',
                    'institucion_id',
                    'ciudad',
                    'grupo_id',
                    'nombre',
                    'apellidos',
                    'username',
                    'estado',
                    'pago',
                    'vencimiento_cartera',
                    'ejecutivo'
                );
            
        //Preparación de campos
            $crud->field_type('pago', 'dropdown', array('No', 'Sí'));
            $crud->callback_column('ciudad', array($this,'gc_ciudad_institucion'));
            $crud->callback_column('vencimiento_cartera', array($this,'gc_vencimiento_cartera'));
            $crud->callback_column('ejecutivo', array($this,'gc_ejecutivo'));
            
        //Relaciones
            $crud->set_relation('institucion_id', 'institucion', 'nombre_institucion');
            $crud->set_relation('grupo_id', 'grupo', "Grupo {nivel} - {grupo}");
        
        //Títulos de los campos
            $crud->display_as('id', 'ID estudiante');
            $crud->display_as('institucion_id', 'Institución');
            $crud->display_as('grupo_id', 'Grupo');
            
        //Otros
            $crud->order_by('institucion_id', 'ASC');
        
        $output = $crud->render();
        
        return $output;
    }
    
    function gc_ciudad_institucion($value, $row)
    {
        $ciudad_id = $this->Pcrn->campo_id('institucion', $row->institucion_id, 'lugar_id');
        $nombre_ciudad = $this->Pcrn->campo_id('lugar', $ciudad_id, 'nombre_lugar');
        return $nombre_ciudad;
    }
    
    function gc_vencimiento_cartera($value, $row)
    {
        $vencimiento_cartera = $this->Pcrn->campo_id('institucion', $row->institucion_id, 'vencimiento_cartera');
        return $vencimiento_cartera;
    }
    
    function gc_ejecutivo($value, $row)
    {
        $ejecutivo_id = $this->Pcrn->campo_id('institucion', $row->institucion_id, 'ejecutivo_id');
        $ejecutivo = $this->App_model->nombre_usuario($ejecutivo_id, 2);
        return $ejecutivo;
    }
    
    function crud_report_instituciones_01()
    {
        //Grocery crud
        $this->load->library('grocery_CRUD');
        
        $crud = new grocery_CRUD();
        $crud->set_table('institucion');
        $crud->set_subject('institucion');
        $crud->unset_edit();
        $crud->unset_print();
        $crud->unset_read();
        $crud->unset_delete();
        
        //Campos
            $crud->columns(
                    'id',
                    'nombre_institucion',
                    'lugar_id',
                    'direccion',
                    'telefono',
                    'email',
                    'pagina_web',
                    'ejecutivo_id',
                    'usuario_id',
                    'acumulador',
                    'vencimiento_cartera',
                    'editado',
                    'notas'
                );
            
        //Preparación de campos
            
        //Relaciones
            $crud->set_relation('lugar_id', 'lugar', 'nombre_lugar');
            $crud->set_relation('usuario_id', 'usuario', 'username');
            $crud->set_relation('ejecutivo_id', 'usuario', '{apellidos} {nombre}');
            //$crud->set_relation('grupo_id', 'grupo', "Grupo {nivel} - {grupo}");
        
        //Títulos de los campos
            $crud->display_as('lugar_id', 'Ciudad');
            $crud->display_as('ejecutivo_id', 'Ejecutivo');
            
        //Otros
            $crud->order_by('lugar_id', 'ASC');
        
        $output = $crud->render();
        
        return $output;
    }
    
    function crud_report_temas_01()
    {
        //Grocery crud
        $this->load->library('grocery_CRUD');
        
        $crud = new grocery_CRUD();
        $crud->set_table('tema');
        $crud->set_subject('tema');
        $crud->unset_edit();
        $crud->unset_print();
        $crud->unset_read();
        $crud->unset_delete();
        
        //Campos
            $crud->columns(
                    'id',
                    'cod_tema',
                    'nombre_tema',
                    'area_id',
                    'nivel',
                    'componente',
                    'editado',
                    'usuario_id',
                    'descripcion',
                    'tipo_id'
                );
            
        //Preparación de campos
            
        //Relaciones
            $crud->set_relation('area_id', 'item', 'item');
            $crud->set_relation('usuario_id', 'usuario', "{apellidos} {nombre}");
            
        //Opciones tipo
            $opciones_tipo = $this->Item_model->opciones('categoria_id = 11');
            $crud->field_type('tipo_id', 'dropdown', $opciones_tipo);
                
        
        //Títulos de los campos
            $crud->display_as('area_id', 'Área');
            $crud->display_as('usuario_id', 'Usuario');
            $crud->display_as('tipo_id', 'Tipo');
            
        //Otros
            //$crud->order_by('cod_tema', 'ASC');
        
        $output = $crud->render();
        
        return $output;
    }
    
    function reporte_temas_02($limit)
    {
        $this->db->select('programa_id, nombre_programa, tema_id, nombre_tema, cod_tema, (orden + 1) AS orden_tema');
        $this->db->join('programa', 'programa.id = programa_tema.programa_id');
        $this->db->join('tema', 'tema.id = programa_tema.tema_id');
        $this->db->order_by('programa_id', 'ASC');
        $this->db->order_by('orden', 'ASC');
        $query = $this->db->get('programa_tema', $limit);
        
        return $query;
    }
    
    function crud_reporte_programas_01()
    {
        //Grocery crud
        $this->load->library('grocery_CRUD');
        
        $crud = new grocery_CRUD();
        $crud->set_table('flipbook');
        $crud->set_subject('programa');
        $crud->unset_edit();
        $crud->unset_print();
        $crud->unset_read();
        $crud->unset_add();
        $crud->unset_delete();
        $crud->where('programa_id IS NOT NULL');
        
        //Campos
            $crud->columns(
                    'programa_id',
                    'id',
                    'tipo_flipbook_id',
                    'nombre_flipbook',
                    'nivel',
                    'area_id'
                );
            
        //Preparación de campos
            $crud->display_as('area_id', 'Área');
            
        //Relaciones
            $crud->set_relation('area_id', 'item', 'item');
        
        //Títulos de los campos
            $crud->display_as('id', 'Flipbook ID');
            $crud->display_as('programa_id', 'Programa ID');
            $crud->display_as('tipo_flipbook_id', 'Tipo');
            $crud->display_as('nombre_flipbook', 'Nombre contenido');
            
        //Otros
            $crud->order_by('programa_id', 'ASC');
        
        $output = $crud->render();
        
        return $output;
    }
    
    function crud_reporte_quices_01()
    {

        //Grocery crud
        $this->load->library('grocery_CRUD');
        
        $crud = new grocery_CRUD();
        $crud->set_table('quiz');
        $crud->set_subject('quiz');
        $crud->unset_edit();
        $crud->unset_print();
        $crud->unset_read();
        $crud->unset_add();
        $crud->unset_delete();
            
        //Relaciones
            $crud->set_relation('area_id', 'item', 'item');
            $crud->set_relation('tema_id', 'tema', 'cod_tema');
        
        //Títulos de los campos
            $crud->display_as('id', 'Quiz ID');
            $crud->display_as('tema_id', 'Tema');
            $crud->display_as('area_id', 'Área');
        
        $output = $crud->render();
        
        return $output;
    }
    
    function crud_reporte_links_01()
    {

        //Grocery crud
        $this->load->library('grocery_CRUD');
        
        $crud = new grocery_CRUD();
        $crud->set_table('recurso');
        $crud->set_subject('enlace');
        $crud->unset_edit();
        $crud->unset_print();
        $crud->unset_read();
        $crud->unset_add();
        $crud->unset_delete();
        
        $crud->where('tipo_recurso_id', 2);
        
        $crud->columns(
            'id',
            'url',
            'tema_id',
            'fecha_subida',
            'usuario_id'
        );
            
        //Relaciones
            $crud->set_relation('tema_id', 'tema', 'cod_tema');
        
        //Títulos de los campos
            $crud->display_as('id', 'Link ID');
        
        $output = $crud->render();
        
        return $output;
    }
    
//EVENTOS, TABLA SIS_EVENTO
//---------------------------------------------------------------------------------------------------
    
    function tipo_eventos($condicion = NULL)
    {
        if ( ! is_null($condicion) ) { $this->db->where($condicion); }
        
        $this->db->select('id_interno AS tipo_evento_id, item AS tipo_evento');
        $this->db->where('categoria_id', 35);   //Ver categorías
        $this->db->order_by('id_interno', 'ASC');
        $query = $this->db->get('item');
        
        return $query;
    }
    
}