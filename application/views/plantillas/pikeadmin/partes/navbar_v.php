<div class="headerbar">

    <!-- LOGO -->
    <div class="headerbar-left">
        <a href="index.html" class="logo">
            <img src="<?php echo URL_IMG ?>app/logo_admin.png" /> 
            <span><?php echo NOMBRE_APP ?></span>
        </a>
    </div>

    <nav class="navbar-custom">

        <ul class="list-inline float-right mb-0">      

            <li class="list-inline-item dropdown notif">
                <a class="nav-link dropdown-toggle nav-user" data-toggle="dropdown" href="#" role="button" aria-haspopup="false" aria-expanded="false">
                    <img src="<?php echo $this->session->userdata('src_img') ?>" alt="Profile image" class="avatar-rounded">
                </a>
                <div class="dropdown-menu dropdown-menu-right profile-dropdown " aria-labelledby="Preview">
                    <!-- item-->
                    <div class="dropdown-item noti-title">
                        <h5 class="text-overflow"><small>Hola, admin</small> </h5>
                    </div>

                    <!-- item-->
                    <a href="pro-profile.html" class="dropdown-item notify-item">
                        <i class="fa fa-user"></i> <span>Perfil</span>
                    </a>


                    <!-- item-->
                    <a href="<?php echo base_url('app/logout') ?>" class="dropdown-item notify-item">
                        <i class="fa fa-power-off"></i> <span>Salir</span>
                    </a>

                </div>
            </li>

        </ul>

        <ul class="list-inline menu-left mb-0">
            <li class="float-left">
                <button class="button-menu-mobile open-left">
                    <i class="fa fa-fw fa-bars"></i>
                </button>
            </li>
        </ul>

    </nav>

</div>