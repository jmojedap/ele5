<!DOCTYPE html>
<html>
    <head>
        <title>Pacarina Media Lab HTML5 Admin Panel</title>

        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width">

        <link rel="stylesheet" href='http://fonts.googleapis.com/css?family=Ubuntu:500,300'>
        <link rel="stylesheet" href="http://netdna.bootstrapcdn.com/font-awesome/4.0.3/css/font-awesome.css">
        <link rel="stylesheet" href="<?= base_url() ?>css/bootstrap/bootstrap.min.css">
        <link rel="stylesheet" href="<?= base_url() ?>css/responsivegridsystem/col.css" media="all">
        <link rel="stylesheet" href="<?= base_url() ?>css/responsivegridsystem/2cols.css" media="all">
        <link rel="stylesheet" href="<?= base_url() ?>css/responsivegridsystem/3cols.css" media="all">
        <link type="text/css" rel="stylesheet" href="<?= base_url() ?>css/apanel/style.css">

        <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
        <script type="text/javascript" src="<?= base_url() ?>js/bootstrap/js/bootstrap.min.js"></script>
        <script type="text/javascript" src="<?= base_url() ?>js/apanel/actions.js"></script>
    </head>
    <body>
        <aside class="main_nav_col">
            <a class="main_logo" href="#"><img src="<?= base_url() ?>media/images/app/logo_enlace.png" width="86"></a>
            <a href="#" class="menu_switch"><i class="fa fa-bars"></i><span>MENU</span></a>
            <ul class="main_nav">	

                <li class="search_container">
                    <form method="POST" action="#">
                        <i class="fa fa-search fa-flip-horizontal"></i>
                        <input type="search" class="search_box" value="buscar">
                    </form>
                </li>

                <li> <!-- contenedor del item -->
                    <a href="#"> <!-- link del item -->
                        <i class="fa fa-2x fa-bar-chart-o"></i> <!-- icono -->
                        <span>inicio</span> <!-- texto -->
                    </a>
                </li>

                <li class="has_submenu"> <!-- contenedor item con submenu -->
                    <a href="#"><i class="fa fa-2x fa-users"></i><span>usuarios</span></a>

                    <ul class="sub_nav"><!-- SUBMENU -->
                        <li><a href="#"><i class="fa fa-user"></i><span>cordinadores</span></a></li> <!-- subitem -->
                        <li><a href="#"><i class="fa fa-user"></i><span>profesores</span></a></li> <!-- subitem -->
                        <li><a href="#"><i class="fa fa-ambulance"></i><span>alumnos</span></a></li> <!-- subitem -->
                        <li><a href="#"><i class="fa fa-user"></i><span>padres</span></a></li> <!-- subitem -->
                        <li><a href="#"><i class="fa fa-user"></i><span>directivos</span></a></li> <!-- subitem -->
                    </ul>

                </li>

                <li class="has_submenu">
                    <a href="#" class="current has_submenu"><i class="fa fa-2x fa-book"></i><span>contenidos</span></a>
                    <span class="gossip">cuestionarios</span>
                    <ul class="sub_nav">
                        <li><a href="#"><i class="fa fa-file-text"></i><span>flipbooks</span></a></li>
                        <li><a href="#" class="current"><i class="fa fa-question-circle"></i><span>cuestionarios</span></a></li>
                        <li><a href="#"><i class="fa fa-volume-up"></i><span>audios</span></a></li>
                    </ul>

                </li>		

                <li><a href="#"><i class="fa fa-2x fa-flask"></i><span>recursos</span></a></li>
                <li><a href="#"><i class="fa fa-2x fa-cogs"></i><span>configuración</span></a></li>
                <li><a href="#"><i class="fa fa-2x fa-male"></i><span>perfiles</span></a></li>

            </ul>
        </aside>

        <div class="main_container">

            <nav class="breadcrumbs">
                <a href="#">Inicio</a>
                <a href="#">Recursos</a>
                <a href="#">Cuestionarios</a>
                <a href="#" class="current">Editar Cuestionario</a>
            </nav>

            <nav class="mini_nav">
                <a href="#">Hola</a>
                <a href="#" class="current">Hola</a>
                <ul>
                    <li><a href="#">Agregar Pregunta</a></li>
                    <li><a href="#">Borrar Flipbook</a></li>
                    <li><a href="#">Asignar a estudiantes</a></li>
                    <li><a href="#" class="current">Ver estadísticas</a></li>
                </ul>
            </nav>

            <div class="section group">
                <h1>Sistema de Columnas</h1>
                <p>
                    Es posible partir el espacio disponible en 1, 2 o 3  
                    columnas.
                </p>
                <p>
                    Cualquier grupo de columnas debería ser puesto dentro de un
                    un div <strong>contenedor</strong> con las clases "section group", 
                    inclusive si solo es una columna.
                </p>
                <p>
                    Las columnas se crean con divs que llevan la clase "col", pero
                    además se debe especificar su ancho con una clase del tipo 
                    "span_1_of_2", donde dice que se debe usar 1/2 del ancho o 
                    "span_2_of_3", donde dice que se deben usar 2/3 del ancho.
                </p>
                <p>
                    La escritura de la clase para un div usado para crear una 
                    columna es, en general, "col span_X_of_Y" donde X es el 
                    numerador y Y el denominador.
                </p>
            </div>

            <div class="section group">
                <div class="col col_box span_2_of_2">
                    <div class="info_container_body">
                        <h2>Bloque de una columna</h2>
                        <p>
                            Este bloque fue creado para demostrar como se puede 
                            crear una sección de contenido que ocupe todo el 
                            ancho disponible.
                        </p>
                    </div>
                </div>
            </div>

            <div class="section group">
                <h3>Two Columns</h3>
            </div>

            <div class="section group">
                <div class="col col_box span_1_of_2">
                    <div class="info_container_body">
                        <p>
                            How can you frighten a man whose hunger is not only 
                            in his own cramped stomach but in the wretched 
                            bellies of his children? You can't scare him – he 
                            has known a fear beyond every other.
                        </p>
                    </div>
                </div>
                <div class="col col_box span_1_of_2">
                    <div class="info_container_body">

                        <!-- Nav tabs -->
                        <ul class="nav nav-tabs">
                            <li class="active"><a href="#bach" data-toggle="tab">Bach</a></li>
                            <li><a href="#vivaldi" data-toggle="tab">Vivaldi</a></li>
                            <li><a href="#handel" data-toggle="tab">Händel</a></li>
                            <li><a href="#marcello" data-toggle="tab">Marcello</a></li>
                        </ul>

                        <!-- Tab panes -->
                        <div class="tab-content">
                            <div class="tab-pane active" id="bach">
                                <p>
                                    In 1950, a thematic catalogue called Bach 
                                    Werke Verzeichnis (Bach Works Catalogue) 
                                    was compiled by Wolfgang Schmieder.[70] 
                                    Schmieder largely followed the Bach 
                                    Gesellschaft Ausgabe, a comprehensive 
                                    edition of the composer's works that was 
                                    produced between 1850 and 1905: BWV 1–224 
                                    are cantatas; BWV 225–249, large-scale 
                                    choral works including his Passions; BWV 
                                    250–524, chorales and sacred songs; BWV 
                                    525–748, organ works; BWV 772–994, other 
                                    keyboard works; BWV 995–1000, lute music; 
                                    BWV 1001–40, chamber music; BWV 1041–71, 
                                    orchestral music; and BWV 1072–1126, canons 
                                    and fugues.[71]
                                </p>
                            </div>
                            <div class="tab-pane" id="vivaldi">
                                <p>
                                    Though Vivaldi's music was well received 
                                    during his lifetime, it later declined in 
                                    popularity until its vigorous revival in 
                                    the first half of the 20th century. Today, 
                                    Vivaldi ranks among the most popular and 
                                    widely recorded of Baroque composers.
                                </p>
                            </div>
                            <div class="tab-pane" id="handel">
                                <p>
                                    George Frideric Handel (German: Georg 
                                    Friedrich Händel; pronounced [ˈhɛndəl]; 23 
                                    February 1685 – 14 April 1759) was a 
                                    German-born British Baroque composer famous 
                                    for his operas, oratorios, anthems and 
                                    organ concertos. Born in a family 
                                    indifferent to music, Handel received 
                                    critical training in Halle, Hamburg and 
                                    Italy before settling in London (1712) as a 
                                    naturalized British subject in 1727.[1] By 
                                    then he was strongly influenced by the 
                                    great composers of the Italian Baroque and 
                                    the middle-German polyphonic choral 
                                    tradition.
                                </p>
                            </div>
                            <div class="tab-pane" id="marcello">
                                <p>
                                    A contemporary of Tomaso Albinoni, Marcello 
                                    was the son of a senator in Venice. As 
                                    such, he enjoyed a comfortable life that 
                                    gave him the scope to pursue his interest 
                                    in music. He held concerts in his hometown 
                                    and also composed and published several 
                                    sets of concertos, including six concertos 
                                    under the title of La Cetra (The Lyre), as 
                                    well as cantatas, arias, canzonets, and 
                                    violin sonatas. Marcello, being a slightly 
                                    older contemporary of Antonio Vivaldi, 
                                    often composed under the pseudonym Eterio 
                                    Stinfalico, his name as a member of the 
                                    celebrated Arcadian Academy (Pontificia 
                                    Accademia degli Arcadi). He died in Padua 
                                    in 1747.
                                </p>
                            </div>
                        </div>

                    </div>
                </div>
            </div>

            <div class="section group">
                <h3>Three Columns</h3>
            </div>

            <div class="section group">
                <div class="col col_box span_1_of_3">
                    <div class="info_container_body">
                        <h2>Estadísticas</h2>
                        <p>
                            In 1723, Bach was appointed Cantor of the 
                            Thomasschule at Thomaskirche in Leipzig, and 
                            Director of Music in the principal churches in the 
                            town, namely the Nikolaikirche and the 
                            Paulinerkirche, the church of the University of 
                            <a href="#">Leipzig</a>.[39] This was a prestigious post in the 
                            mercantile city in the Electorate of Saxony, which 
                            he held for 27 years until his death. It brought 
                            him into contact with the political machinations 
                            of his employer, Leipzig's city council.
                        </p>
                    </div>
                </div>
                <div class="col span_1_of_3">
                    <h2>Weimar, Arnstadt, and Mühlhausen (1703–08)</h2>
                    <p>
                        In January 1703, shortly after graduating from St. 
                        Michael's and being turned down for the post of 
                        organist at Sangerhausen,[20][21] Bach was appointed 
                        court musician in the chapel of Duke Johann Ernst in 
                        Weimar. His role there is unclear, but likely included 
                        menial, non-musical duties. During his seven-month 
                        tenure at Weimar, his <a href="#">reputation</a> as a keyboardist 
                        spread so much that he was invited to inspect the new 
                        organ, and give the inaugural recital, at St. 
                        Boniface's Church in Arnstadt, located about 40 km 
                        southwest of Weimar.[22] In August 1703, he became the 
                        organist at St Boniface's, with light duties, a 
                        relatively generous salary, and a fine new organ tuned 
                        in the modern tempered system that allowed a wide 
                        range of keys to be used.
                    </p>
                    <h2>Leipzig</h2>
                    <p>
                        In 1747, Bach visited the court of King Frederick 
                        II of Prussia at Potsdam. The king played a theme 
                        for Bach and challenged him to improvise a fugue 
                        based on his theme. Bach improvised a three-part 
                        fugue on one of Frederick's fortepianoss, then a 
                        novelty, and later presented the king with a 
                        Musical Offering which consists of fugues, canons 
                        and a trio based on this theme. Its six-part fugue 
                        includes a slightly altered subject more suitable 
                        for extensive elaboration.
                    </p>
                </div>
                <div class="col col_box span_1_of_3">
                    <h2 class="info_container_title">LEGACY</h2>
                    <div class="info_container_body">
                        <p>
                            Bach's music is frequently bracketed with the 
                            literature of <a href="#">William Shakespeare</a> and the science 
                            of Isaac Newton.[66] In Germany, during the 
                            twentieth century, many streets were named and 
                            statues were erected in honour of Bach. His music 
                            features three times – more than any other composer 
                            – on the Voyager Golden Record, a phonograph record 
                            containing a broad sample of the images, common 
                            sounds, languages, and music of Earth, sent into 
                            outer space with the two Voyager probes.[67]
                        </p>
                    </div>
                </div>
            </div>

            <div class="section group">
                <div class="col col_box span_1_of_3">
                    <div class="info_container_body">
                        <p>
                            una columna de tres posibles
                        </p>
                    </div>
                </div>
                <div class="col col_box span_2_of_3">
                    <div class="info_container_body">
                        <p>
                            Part of its impact stemmed from its passionate 
                            depiction of the plight of the poor, and in fact, 
                            many of Steinbeck's contemporaries attacked his 
                            social and political views. Bryan Cordyack writes, 
                            "Steinbeck was attacked as a propagandist and a 
                            socialist from both the left and the right of the 
                            political spectrum. The most fervent of these 
                            attacks came from the Associated Farmers of 
                            California; they were displeased with the book's 
                            depiction of California farmers' attitudes and 
                            conduct toward the migrants. They denounced the 
                            book as a 'pack of lies' and labeled it 'communist 
                            propaganda'.[9] Some accused Steinbeck of 
                            exaggerating camp conditions to make a political 
                            point. Steinbeck had visited the camps well before 
                            publication of the novel[15] and argued their 
                            inhumane nature destroyed the settlers' spirit.
                        </p>
                    </div>
                </div>
            </div>

        </div><!-- main_container -->
        <footer class="main_footer">Pacarina Media Lab &copy; 2013</footer>
    </body>
</html>