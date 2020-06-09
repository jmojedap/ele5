<div id="app_example">

    <table class="table bg-white">
        <thead>
            <th>Elemento</th>
        </thead>
        <tbody>
            <tr v-for="(element, key) in list">
                <td>{{ element.name }}</td>
            </tr>
        </tbody>
    </table>
</div>

<script>
    new Vue({
        el: '#app_example',
        created: function(){
            //this.get_list();
        },
        data: {
            list: [
                {
                    id: '1',
                    name: 'Elemento 1'
                },
                {
                    id: '2',
                    name: 'Elemento 2'
                }
            ]
        }
    });
</script>