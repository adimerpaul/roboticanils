<template>
  <q-page>

    <q-card>
      <q-card-section>
        <div class="row">
            <div class="col-6">
              <small class="text-bold text-subtitle1">Fecha actual:</small> {{ fechaActual }}
              <q-input square outlined v-model="fechaprint" label="fecha"  type="date"/>
            </div>
          <div class="col-6">
            <q-toggle
              :label="turno"
              color="primary"
              false-value="MAÑANA"
              true-value="TARDE"
              v-model="turno"
            />
          </div>
          <div class="col-12 q-px-lg">
            <q-form >
              <q-input label="Colocar el lector"   v-model="ci" outlined />
            </q-form>
            <q-btn color="accent" icon="print" label="Re Impresion" @click="refrigerioPrint" />
          </div>
        </div>
      </q-card-section>
    </q-card>
    <div class="row">
      <div class="col-12 col-sm-4 q-pa-md">
        <q-card class="bg-green">
          <q-card-section>
            <div class="text-h6 text-white">Mañana <q-btn flat dense @click="totalreg" icon="refresh" /> </div>
          </q-card-section>
          <q-card-section>
            <div class="text-h2 text-white">{{manana}}</div>
          </q-card-section>
        </q-card>
      </div>
      <div class="col-12 col-sm-4 q-pa-md">
        <q-card class="bg-red">
          <q-card-section>
            <div class="text-h6 text-white">Tarde <q-btn flat dense @click="totalreg" icon="refresh" /> </div>
          </q-card-section>
          <q-card-section>
            <div class="text-h2 text-white">{{tarde}}</div>
          </q-card-section>
        </q-card>
      </div>
      <div class="col-12 col-sm-4 q-pa-md">
        <q-card class="bg-blue">
          <q-card-section>
            <div class="text-h6 text-white">Inscritos <q-btn flat dense @click="totalreg" icon="refresh" /> </div>
          </q-card-section>
          <q-card-section>
            <div class="text-h2 text-white">{{total}}</div>
          </q-card-section>
        </q-card>
      </div>
    </div>
    <div id="myelement" class="hidden"></div>
  </q-page>
</template>

<script>
import { Printd } from 'printd'
import {date} from "quasar";

export default {
  name: `Refrigerio`,
  data() {
    return {
      ci: '',
      turno: 'MAÑANA',
      fechaActual: date.formatDate(new Date(), 'DD/MM/YYYY'),
      total:0,
      manana:0,
      tarde:0,
      fechaprint: date.formatDate(new Date(), 'YYYY-MM-DD'),
      entrega:[
        {fecha:'2022-11-13',turno:'MAÑANA',refrig:"Mini Pizzitas (cada racion de 2 piezas) + refresco de 300ml"},
        {fecha:'2022-11-13',turno:'TARDE',refrig:"Postre de Oreo (cada racion de 2 piezas, tamaño mediano) + Vaso de Café"},
        {fecha:'2022-11-14',turno:'MAÑANA',refrig:"Mini Pizzitas (cada racion de 2 piezas) + refresco de 300ml"},
        {fecha:'2022-11-14',turno:'TARDE',refrig:"Postre de Oreo (cada racion de 2 piezas, tamaño mediano) + Vaso de Café"},
        {fecha:'2022-11-15',turno:'MAÑANA',refrig:"Mini Pizzitas (cada racion de 2 piezas) + refresco de 300ml"},
        {fecha:'2022-11-15',turno:'TARDE',refrig:"Postre de Oreo (cada racion de 2 piezas, tamaño mediano) + Vaso de Café"},
        {fecha:'2022-11-16',turno:'MAÑANA',refrig:"Mini Pizzitas (cada racion de 2 piezas) + refresco de 300ml"},
        {fecha:'2022-11-16',turno:'TARDE',refrig:"Postre de Oreo (cada racion de 2 piezas, tamaño mediano) + Vaso de Café"},
        {fecha:'2022-11-17',turno:'MAÑANA',refrig:"Mini Hamburguezas (cada racion de 2 piezas, Pan, Carne especial, queso, Lechuga, tomate y cebolla caramelizada + refresco de 300ml"},
        {fecha:'2022-11-17',turno:'TARDE',refrig:"Cupcakes (cada racion de 2 piezas, tamaño nornal, con relleno y decorado) + Vaso de Café"},
        {fecha:'2022-11-18',turno:'MAÑANA',refrig:"Mini Sandwiich (cada racion de 2 piezas, Pan tostado, Lechuga, tomate, Filete de pollo, Jamons y Queso) + refresco de 300ml  "},
        {fecha:'2022-11-18',turno:'TARDE',refrig:"Postre tres Leches (cada racion de 2 piezas, tamaño mediano) + Vaso de Café"},
    ]
    }
  },
  created() {
  this.totalreg()
  },
  methods: {
    totalreg(){
      this.$api.post('totalreg',{
        fecha: date.formatDate(new Date(), 'YYYY-MM-DD'),
      })
        .then(response => {
          console.log(response.data)
          this.total = response.data[0].total
          this.manana = response.data[0].manana
          this.tarde = response.data[0].tarde
        })
        .catch(error => {
          console.log(error)
        })
    },
    refrigerioInsert() {
      this.$q.loading.show()
      this.$api.post('refrigerio/',{
        ci: this.ci,
        turno: this.turno,
        fecha: date.formatDate(new Date(), 'YYYY-MM-DD'),
        hora: date.formatDate(new Date(), 'HH:mm:ss')
      })
        .then((response) => {
          let idRefrigerio = response.data.id
          this.totalreg()
          let student = response.data.cupo
          let user = response.data.user
          this.$q.loading.hide()
          this.$q.notify({
            message: 'Se ha entregado el refrigerio',
            color: 'green-4',
            textColor: 'white',
            icon: 'done',
            position: 'top',
          })
          const d = new Printd()
          let fecha = date.formatDate(new Date(), 'DD/MM/YYYY HH:mm:ss')
          let re=''
          this.entrega.forEach(r => {
            if(r.fecha==date.formatDate(new Date(), 'YYYY-MM-DD') && r.turno==this.turno){
              re=r.refrig
            }
          });

          document.getElementById('myelement').innerHTML = `
<style>
.center {
  text-align: center;
}
.left {
  text-align: left;
}
.right {
  text-align: right;
}
.bold {
  text-weight: bold;
}
</style>
          <div class="right bold"> <b>${idRefrigerio}</b></div>
          <div class="center bold"> <b>Universidad Técnica de Oruro</b></div>
          <div class="center bold"> <b>Facultad Nacional de Ingeniería</b></div>
          <div class="center bold"> <b>TICKET REFRIGERIO</b></div>
          <div class="left "> <b>Nombre: </b> ${student.nombres}</div>
          <div class="left "> <b>Carrera: </b> ${student.carrera}</div>
          <div class="left "> <b>Turno: </b> ${this.turno}</div>
          <div class="left "> <b>Fecha hora: </b> ${fecha}</div>
          <div class="left "> <b>Refrigerio: </b> ${re}</div>
          <div class="left "> <b>User: </b> ${user.name}</div>

          <div style="border-top: 2px dotted #1a202c;margin-top: 50px" class="center">FIRMA</div>
          `
          d.print( document.getElementById('myelement') )

          const e = new Printd()
          document.getElementById('myelement').innerHTML = `
<style>
.center {
  text-align: center;
}
.left {
  text-align: left;
}
.right {
  text-align: right;
}
.bold {
  text-weight: bold;
}
</style>
          <div class="right bold"> <b>${idRefrigerio}</b></div>
          <div class="center bold"> <b>Universidad Técnica de Oruro</b></div>
          <div class="center bold"> <b>Facultad Nacional de Ingeniería</b></div>
          <div class="center bold"> <b>TICKET REFRIGERIO</b></div>
          <div class="left "> <b>Nombre: </b> ${student.nombres}</div>
          <div class="left "> <b>Carrera: </b> ${student.carrera}</div>
          <div class="left "> <b>Turno: </b> ${this.turno}</div>
          <div class="left "> <b>Fecha hora: </b> ${fecha}</div>
          <div class="left "> <b>User: </b> ${user.name}</div>
`
          e.print( document.getElementById('myelement') )
          // this.credencial = false
          // this.folder = false
          // this.barbijo = false
          // this.certificado = false
          this.ci = ''
        }).finally(() => {
        this.$q.loading.hide()

      })
        .catch((error) => {
          this.$q.loading.hide()
          this.$q.notify({
            message: error.response.data.message,
            color: 'red-4',
            textColor: 'white',
            icon: 'error',
            position: 'top',
          })
        })
    },

    refrigerioPrint() {
      if(this.ci==undefined || this.ci=='')
      return false
      this.$q.loading.show()
      this.$api.post('printRefri/',{
        ci: this.ci,
        turno: this.turno,
        fecha: this.fechaprint,
      })
        .then((response) => {
          console.log(response.data)
          //return false
          let student = response.data.cupo
          let refrigerio=response.data
          let user=response.data.user
          this.$q.loading.hide()
          const d = new Printd()
          let re=''
          this.entrega.forEach(r => {
            if(r.fecha==refrigerio.fecha && r.turno==refrigerio.turno){
              re=r.refrig
            }
          });

          document.getElementById('myelement').innerHTML = `
              <style>
              .center {
                text-align: center;
              }
              .left {
                text-align: left;
              }
              .right {
                text-align: right;
              }
              .bold {
                text-weight: bold;
              }
              </style>
              <div class="right bold"> <b>${refrigerio.id}</b></div>
          <div class="center bold"> <b>Universidad Técnica de Oruro</b></div>
          <div class="center bold"> <b>Facultad Nacional de Ingeniería</b></div>
          <div class="center bold"> <b>TICKET REFRIGERIO </b></div>
          <div class="left "> <b>Nombre: </b> ${student.nombres}</div>
          <div class="left "> <b>Carrera: </b> ${student.carrera}</div>
          <div class="left "> <b>Turno: </b> ${refrigerio.turno}</div>
          <div class="left "> <b>Fecha hora: </b> ${refrigerio.fecha} ${refrigerio.hora}</div>
          <div class="left "> <b>Refrigerio: </b> ${re}</div>
          <div class="left "> <b>User: </b> ${user.name}</div>

          <div style="border-top: 2px dotted #1a202c;margin-top: 50px" class="center">FIRMA</div>
          `
          d.print( document.getElementById('myelement') )

          const e = new Printd()
          document.getElementById('myelement').innerHTML = `
          <style>
          .center {
            text-align: center;
          }
          .left {
            text-align: left;
          }
          .right {
            text-align: right;
          }
          .bold {
            text-weight: bold;
          }
          </style>
          <div class="right bold"> <b>${refrigerio.id}</b></div>
          <div class="center bold"> <b>Universidad Técnica de Oruro</b></div>
          <div class="center bold"> <b>Facultad Nacional de Ingeniería</b></div>
          <div class="center bold"> <b>CONTROL REFRIGERIO </b></div>
          <div class="left "> <b>Nombre: </b> ${student.nombres}</div>
          <div class="left "> <b>Carrera: </b> ${student.carrera}</div>
          <div class="left "> <b>Turno: </b> ${refrigerio.turno}</div>
          <div class="left "> <b>Fecha hora: </b> ${refrigerio.fecha} </div>
          <div class="left "> <b>Refrigerio: </b> ${re}</div>
          <div class="left "> <b>User: </b> ${user.name}</div>
          <div class="left "> <b>Nota: </b> Este boucher le da derecho a recoger su refrigerio</div>
`
          e.print( document.getElementById('myelement') )
          // this.credencial = false
          // this.folder = false
          // this.barbijo = false
          // this.certificado = false
          this.ci = ''
        }).finally(() => {
        this.$q.loading.hide()

      })
        .catch((error) => {
          this.$q.loading.hide()
          this.$q.notify({
            message: error.response.data.message,
            color: 'red-4',
            textColor: 'white',
            icon: 'error',
            position: 'top',
          })
        })
    },

  },
}
</script>

<style scoped>

</style>
