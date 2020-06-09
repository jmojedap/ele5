<div class="page-wrapper">
            <!-- ============================================================== -->
            <!-- Container fluid  -->
            <!-- ============================================================== -->
            <div class="container-fluid">                

                <?php if ( ! is_null($view_description) ) { ?>
                    <div id="view_description">
                        <?php $this->load->view($view_description) ?>
                    </div>
                <?php } ?>

                <?php if ( ! is_null($nav_2) ) { ?>
                    <div id="nav_2">
                        <?php $this->load->view($nav_2) ?>
                    </div>
                <?php } ?>

                <?php if ( ! is_null($nav_3) ) { ?>
                    <div id="nav_3">
                        <?php $this->load->view($nav_3) ?>
                    </div>
                <?php } ?>

                <?php echo $this->load->view($view_a) ?>

                <?php if ( ! is_null($view_b) ) { ?>
                    <div id="view_b">
                        <?php echo $this->load->view($view_b) ?>
                    </div>
                <?php } ?>
                
            </div>
            <!-- ============================================================== -->
            <!-- End Container fluid  -->
            <!-- ============================================================== -->

            
            <footer class="footer">
                © 2020 En Línea Editores &middot; Colombia
            </footer>
            
        </div>
        