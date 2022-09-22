<style>
    .comparar{
        display: grid;
        grid-template-columns: 1fr 1fr;
        grid-template-rows: 1fr 5fr 1fr 1fr;
        align-items: center;
        justify-items: center;
        background-color: #000;
        height: 600px;
        grid-auto-rows: minmax(min-content, max-content);
        color: white;
    }

    .comparar h3 {
        color: white;
    }

    .comparado{
        border: 1px solid red;
        display: grid;
        grid-template-rows: 1fr 1fr 1fr;
        width: 100%;
        text-align: center;
    }
</style>

<div id="proporciones_app">
    <div class="container">
        <div class="comparar">
            <h3>{{ elementA.title }}</h3>
            <h3>{{ elementB.title }}</h3>
            <div>
                <img
                    v-bind:src="`<?= URL_IMG ?>proporciones/planetas/` + `la_tierra.png`" v-bind:width="sizes[0] +`px`"
                    alt="planeta"
                    onerror="this.src='<?= URL_IMG ?>app/pf_nd_1.png'"
                >
            </div>
            
            <div>
                <img
                    v-bind:src="`<?= URL_IMG ?>proporciones/planetas/` + `mercurio.png`" v-bind:width="sizes[1] +`px`"
                    alt="planeta"
                    onerror="this.src='<?= URL_IMG ?>app/pf_nd_1.png'"
                >
            </div>

            <div style="width: 95%"><input class="w100pc" type="range" v-model="sizes[0]" min="10" max="300"></div>
            <div style="width: 95%"><input class="w100pc" type="range" v-model="sizes[1]" min="10" max="300"></div>

            <div>
                Diámetro:
                <p>{{ elementA.size }} km</p>
            </div>

            <div>
                Diámetro
                <p>{{ elementB.size }} km</p>
            </div>

        </div>
    </div>
</div>

<script>
var proporciones_app = new Vue({
    el: '#proporciones_app',
    created: function(){
        this.setElementA(2);
        this.setElementB(0);
    },
    data: {
        loading: false,
        planetas:[
            {name:"mercurio",title:"Mercurio",size:4880},
            {name:"venus",title:"Venus",size:12104},
            {name:"la_tierra",title:"La Tierra",size:12756},
            {name:"marte",title:"Marte",size:6794},
            {name:"jupiter",title:"Júpiter",size:142984},
            {name:"saturno",title:"Saturno",size:108728},
            {name:"urano",title:"Urano",size:51118},
            {name:"neptuno",title:"Neptuno",size:49532},
            {name:"pluton",title:"Plutón",size:2320},
        ],
        elementA: {name:'',title:'',size:0},
        elementB: {name:'',title:'',size:0},
        sizes: [150,150]
    },
    methods: {
        setElementA: function(key){
            this.elementA = this.planetas[key]
        },
        setElementB: function(key){
            this.elementB = this.planetas[key]
        },
    }
})
</script>

