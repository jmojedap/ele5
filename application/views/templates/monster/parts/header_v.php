
        <!-- ============================================================== -->
        <!-- Topbar header - style you can find in pages.scss -->
        <!-- ============================================================== -->
        <header class="topbar">
            
            

            <nav class="navbar top-navbar navbar-expand-md navbar-light">

                <!-- ============================================================== -->
                <!-- Logo -->
                <!-- ============================================================== -->
                <div class="navbar-header">
                    <a class="navbar-brand" href="<?php echo base_url() ?>">
                        <!-- Logo icon -->
                        <b>
                            <!--You can put here icon as well // <i class="wi wi-sunset"></i> //-->
                            <!-- Dark Logo icon -->
                            <img src="<?php echo URL_IMG ?>monster/logo-light-icon.png" alt="homepage" class="dark-logo" />
                            <!-- Light Logo icon -->
                            <img src="<?php echo URL_IMG ?>monster/logo-light-icon.png" alt="homepage" class="light-logo" />
                        </b>
                        <!--End Logo icon -->
                        <!-- Logo text -->
                        <span>
                        <!-- dark Logo text -->
                        <img src="<?php echo URL_IMG ?>monster/logo-light-text.png" alt="homepage" class="dark-logo" />
                        <!-- Light Logo text -->    
                        <img src="<?php echo URL_IMG ?>monster/logo-light-text.png" class="light-logo" alt="homepage" /></span>
                    </a>
                </div>
                <!-- ============================================================== -->
                <!-- End Logo -->
                <!-- ============================================================== -->


                <div class="d-none d-lg-block" id="nav_title">
                    <h1 id="head_title" class="head_title">
                        <?php echo $head_title ?>
                    </h1>
                    <h2 id="head_subtitle" class="head_subtitle">
                        &middot;
                        <?php echo $head_subtitle ?>
                    </h2>
                </div>
                
                <div class="navbar-collapse">

                    

                    <!-- ============================================================== -->
                    <!-- toggle and nav items -->
                    <!-- ============================================================== -->
                    <ul class="navbar-nav mr-auto mt-md-0 ">
                        <!-- This is  -->
                        <li class="nav-item">
                            <a class="nav-link nav-toggler hidden-md-up text-muted waves-effect waves-dark" href="javascript:void(0)">
                                <i class="ti-menu"></i>
                            </a>
                        </li>
                        <li class="nav-item"> <a class="nav-link sidebartoggler hidden-sm-down text-muted waves-effect waves-dark" href="javascript:void(0)"><i class="icon-arrow-left-circle"></i></a> </li>
                        
                        
                    </ul>
                    <!-- ============================================================== -->
                    <!-- User profile and search -->
                    <!-- ============================================================== -->
                    <ul class="navbar-nav my-lg-0">
                        <?php if ( $this->session->userdata('srol') == 'no_mostrar' ) { ?>    
                            <li class="nav-item hidden-sm-down">
                                <form class="app-search">
                                    <input type="text" class="form-control" placeholder="Buscar...">
                                        <a class="srh-btn"><i class="ti-search"></i></a>
                                </form>
                            </li>
                        <?php } ?>

                        <!-- Mensajes -->
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle_no text-muted waves-effect waves-dark"
                                href="<?php echo base_url('mensajes/conversacion') ?>"
                                title="Tiene <?php echo $this->session->userdata('no_leidos') ?> mensajes sin leer"
                                >
                                <i class="mdi mdi-email"></i>
                                <?php if ( $this->session->userdata('no_leidos') > 0 ){ ?>
                                    <div class="notify">
                                        <span class="heartbit"></span>
                                        <span class="point"></span>
                                    </div>
                                <?php } ?>
                            </a>
                        </li>

                        <!-- Usuario -->
                        <li class="nav-item dropdown">    
                            <a class="nav-link dropdown-toggle text-muted waves-effect waves-dark" href="" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <img src="<?php echo URL_IMG ?>users/sm_user_3.png" alt="user" class="profile-pic" />
                            </a>
                            <div class="dropdown-menu dropdown-menu-right animated flipInY">
                                <ul class="dropdown-user">
                                    <li class="text-center">
                                        <div class="dw-user-box">
                                            <div class="u-img"><img class="rounded-circle" src="<?php echo URL_IMG ?>users/sm_user_3.png" alt="usuario"></div>
                                            <div class="u-text">
                                                <h4><?php echo $this->session->userdata('nombre_corto'); ?></h4>
                                                <p class="text-muted"><?php echo $this->session->userdata('nombre_usuario'); ?></p>
                                                <a href="<?php echo base_url("usuarios/contrasena") ?>" class="btn btn-rounded btn-danger btn-sm">Ver perfil</a>
                                            </div>
                                        </div>
                                    </li>
                                    <li role="separator" class="divider"></li>
                                    <li><a href="<?php echo base_url('mensajes/conversacion') ?>"><i class="ti-email"></i> Mensajes</a></li>
                                    <li role="separator" class="divider"></li>
                                    <li><a href="<?php echo base_url('app/logout') ?>"><i class="fa fa-sign-out-alt"></i> Cerrar sesión</a></li>
                                </ul>
                            </div>
                        </li>
                        
                    </ul>
                </div>
            </nav>
        </header>
        <!-- ============================================================== -->
        <!-- End Topbar header -->
        <!-- ============================================================== -->