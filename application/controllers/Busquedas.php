<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Busquedas extends CI_Controller{
    
    function __construct()
    {
        parent::__construct();
        
        //Específicos
        $this->load->model('Busqueda_model');
    }
    
    /**
     * Controla y redirecciona las búsquedas de exploración
     * para cada elemento (explorador), evita el problema de reenvío del
     * formulario al presionar el botón "atrás" del browser
     * 
     * @param type $controlador
     */
    function explorar_redirect($controlador)
    {
        //$this->output->enable_profiler(TRUE);
        $this->load->model('Busqueda_model');
        $busqueda_str = $this->Busqueda_model->busqueda_str();
        redirect("{$controlador}/explorar/?{$busqueda_str}");
    }
    
    /**
     * POST REDIRECT
     * 2017-07-07
     * Toma los datos de POST, los establece en formato GET para url y redirecciona
     * a una controlador y función definidos.
     * 
     * @param type $controlador
     * @param type $funcion
     */
    function redirect($controlador, $funcion)
    {
        $busqueda_str = $this->Busqueda_model->busqueda_str();
        redirect("{$controlador}/{$funcion}/?{$busqueda_str}");
    }
}