<div class="input-group float-right" style="width: 120px;">
    <div class="input-group-prepend">
        <button class="btn btn-default" v-on:click="pagina_anterior">
            <i class="fa fa-caret-left"></i>
        </button>
    </div>
    <input
        id="campo-num_pagina"
        name="num_pagina"
        class="form-control"
        type="number"
        value="1"
        v-model="num_pagina"
        v-on:change="obtener_listado"
        >
    <div class="input-group-append">
        <button class="btn btn-default" v-on:click="pagina_siguiente">
            <i class="fa fa-caret-right"></i>
        </button>
    </div>
</div>