<?php $this->load->view('templates/monster/menus/elements_' . $this->session->userdata('rol_id')) ?>
<?php //$this->load->view('templates/monster/menus/elements_6'); ?>

<!-- ============================================================== -->
<!-- Left Sidebar - style you can find in sidebar.scss  -->
<!-- ============================================================== -->
<aside class="left-sidebar" id="nav_1">
    <!-- Sidebar scroll-->
    <div class="scroll-sidebar">
        <!-- Sidebar navigation-->
        <nav class="sidebar-nav">
            <ul id="sidebarnav">
                <li class="nav-small-cap">PERSONAL</li>
                
                <li v-for="(element, i) in elements" v-bind:class="{ 'active': element.active }">
                    <a v-bind:href="`<?php echo base_url() ?>` + element.cf" aria-expanded="false" v-bind:class="{ 'has-arrow': element.submenu }">
                        <i v-bind:class="element.icon"></i>
                        <span class="hide-menu">{{ element.text }}</span>
                    </a>
                    <ul aria-expanded="false" class="collapse" v-if="element.submenu">
                        <li
                            v-for="(subelement, j) in element.subelements"
                            v-bind:data-parent_id="element.id"
                            v-bind:class="{ 'active': subelement.active }"
                        >
                            <a v-bind:href="`<?php echo base_url() ?>` + subelement.cf">
                                <i class="mr-1" v-bind:class="subelement.icon"></i> {{ subelement.text }}
                            </a>
                        </li>
                    </ul>
                </li>
            </ul>
        </nav>
        <!-- End Sidebar navigation -->
    </div>
    <!-- End Sidebar scroll-->
</aside>
<!-- ============================================================== -->
<!-- End Left Sidebar - style you can find in sidebar.scss  -->
<!-- ============================================================== -->

<script>
    new Vue({
        el: '#nav_1',
        data: {
            elements: nav_1_elements
        }
    });
</script>