<h3 class="title-light">Selecciona el archivo para ver o descargar</h3>
<table class="table bg-white mt-3">
    <tbody>
        <tr v-for="(file, key) in currentUnidad.files">
            <td width="10px">{{ key + 1 }}</td>
            <td width="10px">
                <!-- <i v-bind:class="file.icono"></i> -->
            </td>
            <td class="text-primary">
                <a v-bind:href="file.url" class="link-titulo" target="_blank">{{ file.title }}</a>
            </td>
            <td width="90px">
                <a class="btn btn-sm btn-light me-1" title="Abrir archivo" v-bind:href="file.url" target="_blank">
                    <i class="fas fa-external-link-alt"></i>
                </a>
                <a class="btn btn-sm btn-light" title="Descargar archivo" v-bind:href="file.url" download>
                    <i class="fas fa-download"></i>
                </a>
            </td>
        </tr>
    </tbody>
</table>