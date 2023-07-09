<!DOCTYPE html>
<html lang="es">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <title>Estadísticas - Login diario</title>
    <link rel="shortcut icon" href="http://localhost/ele2/media/images/app/icono.png" type="image/ico" />
        <link rel="stylesheet" href="http://localhost/ele2/css/style.css" type="text/css" media="screen" />
    
    <script type="text/javascript" language="javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script>
    
        <link rel="stylesheet" href="http://localhost/ele2/css/admin_layout.css" type="text/css" media="screen" />
        
        
        
<!--        End admin scripts-->
    <!-- HighCharts scripts-->

<script src="http://code.highcharts.com/highcharts.js"></script>
<script src="http://code.highcharts.com/modules/exporting.js"></script>
<script>
    $(function () {
        $('#container').highcharts({
            chart: {
                zoomType: 'x',
                spacingRight: 20
            },
            title: {
                text: 'Login de usuarios por día'
            },
            subtitle: {
                text: document.ontouchstart === undefined ?
                    'Click and drag in the plot area to zoom in' :
                    'Pinch the chart to zoom in'
            },
            xAxis: {
                type: 'datetime',
                maxZoom: 14 * 24 * 3600000, // fourteen days
                title: {
                    text: null
                }
            },
            yAxis: {
                title: {
                    text: 'Usuarios'
                }
            },
            tooltip: {
                shared: true
            },
            legend: {
                enabled: false
            },
            plotOptions: {
                area: {
                    lineWidth: 1,
                    marker: {
                        enabled: false
                    },
                    shadow: false,
                    states: {
                        hover: {
                            lineWidth: 1
                        }
                    },
                    threshold: null
                }
            },
    
            series: [{
                type: 'area',
                name: 'Usuarios',
                pointInterval: 30 * 24 * 3600 * 1000,
                pointStart: Date.UTC(2010, 9, 1),
                data: [
                    1,2,5,8,7,9,6,5,4,7,8,8.5,9.0,9.2,9.5
                ]
            }]
        });
    });
</script>    

</head>
<body>

	<header id="header">
		<hgroup>
			<h1 class="site_title"><a href="http://localhost/ele2/"><img src="http://localhost/ele2/media/images/admin/logo_enlace_transparent.png" class="logo_enlace" /></a></h1>
			<h2 class="section_title">Estadísticas - Login diario***</h2>
		</hgroup>
	</header> <!-- end of header bar -->
        
        <section id="secondary_bar">
            <div class="user">
                    <p> Administrador</p>
                    <!-- <a class="logout_user" href="#" title="Logout">Logout</a> -->
            </div>
            <div class="breadcrumbs_container">
                    
            </div>
	</section><!-- end of secondary bar -->
        
<aside id="sidebar" class="columna">
        <form action="http://localhost/ele2/busquedas/estudiantes" class="quick_search" method="post" accept-charset="utf-8">
                <input name="quick_search" type="text" value="Búsqueda rápida" onfocus="if(!this._haschanged){this.value=''};this._haschanged=true;">
        </form>
        <hr/>
        <h3>Institucional</h3>
            <ul class="toggle">
                <li class="icn_instituciones"><a href="http://localhost/ele2/instituciones/explorar">instituciones</a></li>
                <li class="icn_usuarios_i"><a href="http://localhost/ele2/usuarios/institucionales">usuarios institucionales</a></li>
                <li class="icn_usuarios"><a href="http://localhost/ele2/usuarios/estudiantes">estudiantes</a></li>
                <li class="icn_grupos"><a href="http://localhost/ele2/grupos/explorar">grupos</a></li>
                <li class="icn_usuarios_i"><a href="http://localhost/ele2/datos/grupo_profesor" title="Asignación de profesores a grupos">profesor > grupos</a></li>
            </ul>

        <h3>Contenidos Acad&eacute;micos</h3>
            <ul class="toggle">
            <li class="icn_cuestionarios"><a href="http://localhost/ele2/cuestionarios/explorar">cuestionarios</a></li>
            <li class="icn_preguntas"><a href="http://localhost/ele2/enunciados/explorar">enunciados</a></li>
            <li class="icn_preguntas"><a href="http://localhost/ele2/datos/preguntas">preguntas</a></li>
            </ul>

        <h3>Recursos</h3>
            <ul class="toggle">
                <li class="icn_flipbook"><a href="http://localhost/ele2/temas/index">temas</a></li>
                <li class="icn_flipbook"><a href="http://localhost/ele2/datos/flipbooks">flipbooks</a></li>
                <li class="icn_banco_recursos"><a href="http://localhost/ele2/datos/recursos">banco de recursos</a></li>
                <li class="icn_archivos"><a href="http://localhost/ele2/datos/archivos">archivos</a></li>
            </ul>

        <h3>Configuraci&oacute;n</h3>
            <ul class="toggle">
                <li class="icn_usuarios"><a href="http://localhost/ele2/usuarios/internos" title="Usuarios de En Línea Editores">usuarios ELE</a></li>
                <li class="icn_password"><a href="http://localhost/ele2/usuarios/explorar">gestión de usuarios</a></li>
                <li class="icn_parametros"><a href="http://localhost/ele2/datos/contenidos">parámetros del sistema</a></li>
            </ul>

        <h3>Mi cuenta</h3>
            <ul class="toggle">
                <li class="icn_mensajes"><a href="http://localhost/ele2/mensajes/recibidos">mensajes</a></li>
                <li class="icn_password"><a href="http://localhost/ele2/usuarios/contrasena">cambiar contraseña</a></li>
                <li class="icn_ayuda"><a href="http://localhost/ele2/usuarios/videos_ayuda/JyW_eeO0nzs">vídeos de ayuda</a></li>
                <li class="icn_salir"><a href="http://localhost/ele2/app/logout">cerrar sesión</a></li>
            </ul>

        <footer>
                <hr />        
                <p style="text-align: center;">
                    <img src="http://localhost/ele2/media/images/admin/logo-pacarina-media-lab.png" /><br />
                    <strong>Pacarina Media Lab. &copy; 2013</strong><br />
                    <a href="http://www.pacarina.com" target="blank">www.pacarina.com</a>
                </p>
        </footer>

        <hr />

</aside><!-- end of sidebar -->        <section id="main" class="columna">
            


<article class="module width_full">
    <div class="module_content">
        <h1>Estadísticas</h1>
    </div>
        
</article>

<article class="module width_full">
    <header>
        <h3>Login de usuarios por día</h3>
    </header>
    
    
    <table class="tablesorter" cellspacing="0">
        <thead>
            <th>Fecha evento</th>
            <th>Cantidad</th>
        </thead>
        <tbody>
                            <tr>
                    <td>2013-09-23</td>
                    <td>347</td>
                </tr>

                            <tr>
                    <td>2013-09-24</td>
                    <td>794</td>
                </tr>

                            <tr>
                    <td>2013-09-25</td>
                    <td>833</td>
                </tr>

                            <tr>
                    <td>2013-09-26</td>
                    <td>755</td>
                </tr>

                            <tr>
                    <td>2013-09-27</td>
                    <td>602</td>
                </tr>

                            <tr>
                    <td>2013-09-28</td>
                    <td>449</td>
                </tr>

                            <tr>
                    <td>2013-09-29</td>
                    <td>493</td>
                </tr>

                            <tr>
                    <td>2013-09-30</td>
                    <td>809</td>
                </tr>

                            <tr>
                    <td>2013-10-01</td>
                    <td>860</td>
                </tr>

                            <tr>
                    <td>2013-10-02</td>
                    <td>642</td>
                </tr>

                    </tbody>
    </table>
    
    <hr/>
    
    <div id="container" style="min-width: 310px; height: 400px; margin: 0 auto"></div>
        
    
</article>            <div class="spacer"></div>
        </section>
    </body>
</html>
