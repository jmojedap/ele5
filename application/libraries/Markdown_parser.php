<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require 'vendor/autoload.php';

class Markdown_parser {
    
    protected $CI;
    protected $parsedown;
    
    public function __construct()
    {
        $this->CI =& get_instance();
        $this->parsedown = new Parsedown();
    }
    
    public function parse_file($file)
    {
        if ( ! file_exists($file))
        {
            show_error('The file '.$file.' does not exist.');
        }
        
        $markdown = file_get_contents($file);
        return $this->parsedown->text($markdown);
    }

    public function parse_string($markdown_string)
    {
        return $this->parsedown->text($markdown_string);
    }
    
}
