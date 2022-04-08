<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
|--------------------------------------------------------------------------
| File and Directory Modes
|--------------------------------------------------------------------------
|
| These prefs are used when checking and setting modes when working
| with the file system.  The defaults are fine on servers with proper
| security, but you may wish (or even need) to change the values in
| certain environments (Apache running a separate process for each
| user, PHP under CGI with Apache suEXEC, etc.).  Octal values should
| always be used to set the mode correctly.
|
*/
define('FILE_READ_MODE', 0644);
define('FILE_WRITE_MODE', 0666);
define('DIR_READ_MODE', 0755);
define('DIR_WRITE_MODE', 0755);

/*
|--------------------------------------------------------------------------
| File Stream Modes
|--------------------------------------------------------------------------
|
| These modes are used when working with fopen()/popen()
|
*/

define('FOPEN_READ', 'rb');
define('FOPEN_READ_WRITE', 'r+b');
define('FOPEN_WRITE_CREATE_DESTRUCTIVE', 'wb'); // truncates existing file data, use with care
define('FOPEN_READ_WRITE_CREATE_DESTRUCTIVE', 'w+b'); // truncates existing file data, use with care
define('FOPEN_WRITE_CREATE', 'ab');
define('FOPEN_READ_WRITE_CREATE', 'a+b');
define('FOPEN_WRITE_CREATE_STRICT', 'xb');
define('FOPEN_READ_WRITE_CREATE_STRICT', 'x+b');

/*
|--------------------------------------------------------------------------
| Display Debug backtrace
|--------------------------------------------------------------------------
|
| If set to TRUE, a backtrace will be displayed along with php errors. If
| error_reporting is disabled, the backtrace will not display, regardless
| of this setting
|
*/
define('SHOW_DEBUG_BACKTRACE', TRUE);

/*
|--------------------------------------------------------------------------
| Exit Status Codes
|--------------------------------------------------------------------------
|
| Used to indicate the conditions under which the script is exit()ing.
| While there is no universal standard for error codes, there are some
| broad conventions.  Three such conventions are mentioned below, for
| those who wish to make use of them.  The CodeIgniter defaults were
| chosen for the least overlap with these conventions, while still
| leaving room for others to be defined in future versions and user
| applications.
|
| The three main conventions used for determining exit status codes
| are as follows:
|
|    Standard C/C++ Library (stdlibc):
|       http://www.gnu.org/software/libc/manual/html_node/Exit-Status.html
|       (This link also contains other GNU-specific conventions)
|    BSD sysexits.h:
|       http://www.gsp.com/cgi-bin/man.cgi?section=3&topic=sysexits
|    Bash scripting:
|       http://tldp.org/LDP/abs/html/exitcodes.html
|
*/
define('EXIT_SUCCESS', 0); // no errors
define('EXIT_ERROR', 1); // generic error
define('EXIT_CONFIG', 3); // configuration error
define('EXIT_UNKNOWN_FILE', 4); // file not found
define('EXIT_UNKNOWN_CLASS', 5); // unknown class
define('EXIT_UNKNOWN_METHOD', 6); // unknown class member
define('EXIT_USER_INPUT', 7); // invalid user input
define('EXIT_DATABASE', 8); // database error
define('EXIT__AUTO_MIN', 9); // lowest automatically-assigned error code
define('EXIT__AUTO_MAX', 125); // highest automatically-assigned error code

/*
|--------------------------------------------------------------------------
|Definidas por Pacarina
*/


define('RUTA_IMG', 'resources/images/');
define('RUTA_CONTENT', 'content/');
define('RUTA_UPLOADS', 'content/uploads/');
define('URL_ASSETS', 'http://localhost/editores/assets/');
//define('URL_CONTENT', 'http://localhost/editores/content/');   //URL carpeta contenido
define('URL_CONTENT', 'https://www.plataformaenlinea.com/2017/content/');   //URL carpeta contenido
define('URL_UPLOADS', 'http://localhost/editores/content/uploads/');
//define('URL_UPLOADS', 'https://www.plataformaenlinea.com/v3/assets/uploads/');
define('URL_RECURSOS', 'http://localhost/editores/resources/');   //URL carpeta contenido
define('URL_IMG', 'http://localhost/editores/resources/images/');
define('PTL_ADMIN', 'templates/apanel2/plantilla_v');   //Vista plantilla de administración
define('VER_LOCAL', TRUE);   //Es una versión local
define('MAX_REG_EXPORT', 5000);                //Número máximo de registros para exportar en archivo MS-Excel
define('NOMBRE_APP', 'Plataforma en Línea');    //Nombre de la aplicación
define('LAT_CHAT', 5000);                       //Latencia del chat, milisegudos actualización de mensajes chat

define('APP_NAME', 'Plataforma en Línea');    //Nombre de la aplicación
define('URL_RESOURCES', 'http://localhost/editores/resources/');   //URL carpeta contenido
define('TPL_ADMIN', 'templates/apanel3/main_v');   //Vista plantilla de administración
define('TPL_ADMIN_NEW', 'templates/monster/main_v');   //Vista plantilla de administración
define('TPL_ADMIN_FOLDER', 'templates/monster/');   //Vista plantilla de administración