

<div class="d-flex">
    <div class="mr-2 text-right" style="width: 75%;">
        <b class="text-primary">{{ search_num_rows }} </b> resultados |
        Pág <b class="text-primary">{{ num_page }}</b>/{{ max_page }}
    </div>
    <div class="" style="width: 25%;">
        <input
            type="range"
            class="custom-range"
            value="1"
            min="1"
            v-bind:max="max_page"
            v-model="num_page"
            v-on:change="get_list"
            v-bind:title="`${max_page} páginas en total`">
    </div>
</div>
