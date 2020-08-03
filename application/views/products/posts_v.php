<?php
    $arr_posts = array();

    foreach ($posts->result() as $post)
    {
        //$att_img = $this->File_model->att_img($post->imagen_id, '500px_');
        $post->img_src = URL_CONTENT . 'books/covers/' . $post->slug . '.jpg';    ;
        $post->disponible = $this->pml->ago($post->published_at);
        $post->published_at_nice = $this->pml->date_format($post->published_at, 'M-d');
        $arr_posts[] = $post;
    }
?>

<style>
    .cover_post{
        border: 1px solid #DDD;
        max-width: 120px;
        -webkit-border-radius: 2px;
        -moz-border-radius: 2px;
        border-radius: 2px;
        -webkit-box-shadow: 5px 5px 5px 0px rgba(227,227,227,1);
        -moz-box-shadow: 5px 5px 5px 0px rgba(227,227,227,1);
        box-shadow: 5px 5px 5px 0px rgba(227,227,227,1);
    }

    .cover_post:hover{
        border: 1px solid #AAA;
    }
</style>

<div id="product_posts" class="center_box_750">
    <?php if ( $this->session->userdata('role') <= 10 ) { ?>
        <div class="card mb-2">
            <div class="card-body">
                <form accept-charset="utf-8" method="POST" id="posts_form" @submit.prevent="add_post" clas="form-horizontal">
                    <div class="form-group row">
                        <label for="post_id" class="col-md-2 col-form-label text-right">Contenido</label>
                        <div class="col-md-8">
                            <?php echo form_dropdown('post_id', $options_post, '', 'required class="form-control" v-model="post_id"') ?>
                        </div>
                        <div class="col-md-2">
                            <button class="btn btn-primary btn-block" type="submit">
                                Agregar
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    <?php } ?>

    <div class="card mb-3" v-for="(post, key) in posts">
        <div class="d-flex flex-row">
            <div>
                <a v-bind:href="`<?php echo base_url("books/read/") ?>` + `/` + post.code + `/` + post.meta_id + `/` + post.slug">
                    <img v-bind:src="post.img_src" class="card-img" alt="Post cover" style="max-width: 120px;">
                </a>
            </div>
            <div>
                <div class="card-body">
                    <h5 class="card-title">{{ post.title }}</h5>
                    <p>
                        <a class="btn btn-success w75p" v-bind:href="`<?php echo base_url("posts/info/") ?>` + `/` + post.id">
                            Abrir
                        </a>
                        <?php if ( $this->session->userdata('role') <= 10 ) { ?>
                            <button class="btn btn-warning w75p" v-on:click="remove_post(post.id, post.meta_id)">
                                Quitar
                            </button>
                        <?php } ?>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    new Vue({
        el: '#product_posts',
        created: function(){
            //this.get_list();
        },
        data: {
            product_id: <?php echo $row->id ?>,
            posts: <?php echo json_encode($arr_posts) ?>,
            post_id: 0
        },
        methods: {
            add_post: function(){
                axios.get(app_url + 'products/add_post/' + this.product_id + '/' + this.post_id)
                .then(response => {
                    console.log(response.data)
                    window.location = app_url + 'products/posts/' + this.product_id;
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            remove_post: function(post_id, meta_id){
                axios.get(app_url + 'products/remove_post/' + this.product_id + '/' + meta_id)
                .then(response => {
                    console.log(response.data)
                    window.location = app_url + 'products/posts/' + this.product_id;
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
        }
    });
</script>