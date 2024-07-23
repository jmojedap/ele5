<div class="center_box_750">
    <div class="mt-5">
        <h3 class="title-white">Selecciona el archivo para ver o descargar</h3>
    </div>
    <div class="card mt-2">
        <div class="card-body">
            <table class="table bg-white">
                <tbody>
                    <tr v-for="(archivo, key) in demoArchivos">
                        <td width="10px">{{ key + 1 }}</td>
                        <td width="10px">
                            <i v-bind:class="archivo.icono"></i>
                        </td>
                        <td class="text-primary">{{ archivo.nombre }}</td>
                        <td width="90px">
                            <button class="btn btn-sm btn-light" title="">
                                <i class="fas fa-external-link-alt"></i>
                            </button>
                            <button class="btn btn-sm btn-light" title="">
                                <i class="fas fa-download"></i>
                            </button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>