<?php
    $user_href = 'accounts/login';
    if ( $this->session->userdata('logged') ) {
        $user_href = 'accounts/profile';
    }
?>

<footer id="footer_app" class="footer fixed-bottom">
    <a v-bind:href="URL_APP + element.href" v-for="(element, ek) in elements" v-bind:class="{'active': element.sections.includes(app_cf) }">
        <i v-bind:class="element.icon"></i>
    </a>
</footer>

<script>
var footer_app = new Vue({
    el: '#footer_app',
    data: {
        url_app: url_app,
        app_cf: app_cf,
        elements: [
            { id: 'home', href: 'site', icon: 'fa fa-home',
                sections: ['site/', 'app/bs5']
            },
            { id: 'search', href: 'site/explore', icon: 'fa fa-search',
                sections: ['site/explore']
            },
            { id: 'favorites', href: 'site/my_favorites', icon: 'fa fa-heart',
                sections: ['site/my_favorites']
            },
            { id: 'user', href: '<?= $user_href ?>', icon: 'fa fa-user',
                sections: ['accounts/profile', 'accounts/edit', 'accounts/login', 'accounts/signup', ]
            },
        ]
    }
})
</script>