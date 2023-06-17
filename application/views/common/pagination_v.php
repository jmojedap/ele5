<div class="input-group float-right" style="width: 80px;">
    <div class="input-group-prepend">
        <button class="btn" v-on:click="sumPage(-1)" title="Página anterior" v-bind:disabled="numPage==1">
            <i class="fa fa-chevron-left"></i>
        </button>
    </div>
    <div class="input-group-append">
        <button class="btn" v-on:click="sumPage(1)" title="Página siguiente" v-bind:disabled="numPage>=maxPage">
            <i class="fa fa-chevron-right"></i>
        </button>
    </div>
</div>