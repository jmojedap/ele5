<div class="center_box_750">
    <div class="mt-5">
        <h3 class="title-white">Selecciona el cuestionario para resolver o asingar</h3>
    </div>
    <div class="card mt-2">
        <div class="card-body">
            <table class="table bg-white">
                <tbody>
                    <tr v-for="(cuestionario, key) in demoCuestionarios">
                        <td width="10px">{{ key + 1 }}</td>
                        <td width="10px">
                            <i class="fas fa-list-ol text-info"></i>
                        </td>
                        <td class="text-danger">{{ cuestionario.nombre }}</td>
                        <td width="90px">
                            <button class="btn btn-sm btn-light me-1" title="Programar">
                                <i class="fa fa-calendar"></i>
                            </button>
                            <button class="btn btn-sm btn-light" title="">
                                <i class="fas fa-external-link-alt"></i>
                            </button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>