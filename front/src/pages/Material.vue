<template>
  <q-page>
    <q-card>
      <q-card-section>
        <div class="row">
          <div class="col-12">
            <p class="text-red text-bold text-center">Marque los materiales a entregar </p>
          </div>
          <div class="col-12">
             <div class="text-h6 text-bold">PARTICIPANTE : {{cupo.nombres}}</div>
          </div>
          <div class="col-xs-12 col-sm-2 ">
            <q-checkbox v-model="credencial" label="Credencial - Portacredencial" />
          </div>
          <div class="col-xs-12 col-sm-2 ">
            <q-checkbox v-model="folder" label="Folder" />
          </div>
          <div class="col-xs-12 col-sm-2 ">
            <q-checkbox v-model="boligrafo" label="Boligrafo" />
          </div>
          <div class="col-xs-12 col-sm-2 ">
            <q-checkbox v-model="barbijo" label="Barbijo" />
          </div>
          <div class="col-xs-12 col-sm-2 ">
            <q-checkbox v-model="certificado" label="Certificado" />
          </div>
          <div class="col-xs-12 col-sm-2 ">
            <q-checkbox v-model="cd" label="CD" />
          </div>
          <div class="col-6 q-px-lg">
            <q-form @submit.prevent="BuscarCupo">
              <q-input label="Colocar el lector" ref="ci"  v-model="ci" outlined />
            </q-form>
          </div>
          <div class="col-6 flex flex-center">
            <q-btn color="primary" icon="check" label="Entregar" @click="materialInsert" />
          </div>
          <div class="col-6 flex flex-center">
            <q-btn color="accent" icon="print" label="Impresion" @click="impresion" />
          </div>
        </div>
        <div class="row">
      <div class="col-12 col-sm-4 q-pa-md">
        <q-card class="bg-green">
          <q-card-section>
            <div class="text-h6 text-white">Credencial y Porta Credencial <q-btn flat dense @click="totalreg" icon="refresh" /> </div>
          </q-card-section>
          <q-card-section>
            <div class="text-h2 text-white">{{ncred}}</div>
          </q-card-section>
        </q-card>
      </div>
      <div class="col-12 col-sm-4 q-pa-md">
        <q-card class="bg-red">
          <q-card-section>
            <div class="text-h6 text-white">Folder <q-btn flat dense @click="totalreg" icon="refresh" /> </div>
          </q-card-section>
          <q-card-section>
            <div class="text-h2 text-white">{{nfolder}}</div>
          </q-card-section>
        </q-card>
      </div>
      <div class="col-12 col-sm-4 q-pa-md">
        <q-card class="bg-blue">
          <q-card-section>
            <div class="text-h6 text-white">Barbijo <q-btn flat dense @click="totalreg" icon="refresh" /> </div>
          </q-card-section>
          <q-card-section>
            <div class="text-h2 text-white">{{nbarbijo}}</div>
          </q-card-section>
        </q-card>
      </div>
      <div class="col-12 col-sm-4 q-pa-md">
        <q-card class="bg-info">
          <q-card-section>
            <div class="text-h6 text-white">Boligrafo <q-btn flat dense @click="totalreg" icon="refresh" /> </div>
          </q-card-section>
          <q-card-section>
            <div class="text-h2 text-white">{{nbol}}</div>
          </q-card-section>
        </q-card>
      </div>
      <div class="col-12 col-sm-4 q-pa-md">
        <q-card class="bg-primary">
          <q-card-section>
            <div class="text-h6 text-white">Certificado <q-btn flat dense @click="totalreg" icon="refresh" /> </div>
          </q-card-section>
          <q-card-section>
            <div class="text-h2 text-white">{{ncert}}</div>
          </q-card-section>
        </q-card>
      </div>
      <div class="col-12 col-sm-4 q-pa-md">
        <q-card class="bg-secondary">
          <q-card-section>
            <div class="text-h6 text-white">CD <q-btn flat dense @click="totalreg" icon="refresh" /> </div>
          </q-card-section>
          <q-card-section>
            <div class="text-h2 text-white">{{ncd}}</div>
          </q-card-section>
        </q-card>
      </div>
    </div>
      </q-card-section>
    </q-card>
    <div class="col-12">
      <q-table title="Inscritos" :rows="listcupo" :columns="columns" row-key="name" >
      <template v-slot:top-right>
        <q-btn
          color="primary"
          icon-right="archive"
          label="Export to csv"
          no-caps
          @click="exportTable"
        />
      </template>
    </q-table>
    </div>
<div id="myelement" class="hidden"></div>
  </q-page>
</template>

<script>
import { Printd } from 'printd'
import {date} from "quasar";
import { exportFile, useQuasar } from 'quasar'

export default {
  name: `Material`,
  data() {
    return {
      credencial: false,
      folder: false,
      barbijo: false,
      certificado: false,
      cd: false,
      boligrafo: false,
      ncred: 0,
      nfolder: 0,
      nbarbijo: 0,
      ncert: 0,
      ncd: 0,
      nbol: 0,
      ci: '',
      cupo:{},
      listcupo:[],
      columns:[
        {name:"ci",label:"ci",field:"ci"},
        {name:"nombres",label:"nombres",field:"nombres"},
      ]
    }
  },
  created(){
    this.totalreg()
    this.listado()
  },

  methods: {
     wrapCsvValue (val, formatFn, row) {
  let formatted = formatFn !== void 0
    ? formatFn(val, row)
    : val

  formatted = formatted === void 0 || formatted === null
    ? ''
    : String(formatted)

  formatted = formatted.split('"').join('""')
  /**
   * Excel accepts \n and \r in strings, but some other CSV parsers do not
   * Uncomment the next two lines to escape new lines
   */
  // .split('\n').join('\\n')
  // .split('\r').join('\\r')

  return `"${formatted}"`
},
    listado(){
      this.$api.post('listentrega',{fecha:date.formatDate(new Date(), 'YYYY-MM-DD')}).then(res=>{
        console.log(res.data)
        this.listcupo=res.data
      })
    },
    exportTable () {
        // naive encoding to csv format
        const content = [this.columns.map(col => this.wrapCsvValue(col.label))].concat(
          this.listcupo.map(row => this.columns.map(col => this.wrapCsvValue(
            typeof col.field === 'function'
              ? col.field(row)
              : row[ col.field === void 0 ? col.name : col.field ],
            col.format,
            row
          )).join(','))
        ).join('\r\n')

        const status = exportFile(
          'table-export.csv',
          content,
          'text/csv'
        )

        if (status !== true) {
          $q.notify({
            message: 'Browser denied file download...',
            color: 'negative',
            icon: 'warning'
          })
        }
      },
    totalreg(){
      this.$api.post('totalmaterial').then(res=>{
        console.log(res.data)
        this.ncred=res.data[0].cantcred
        this.nfolder=res.data[0].cantfolder
        this.nbarbijo=res.data[0].cantbar
        this.ncert=res.data[0].cantcert
        this.ncd=res.data[0].cantcd
        this.nbol=res.data[0].cantbol
        this.listado()
      })
    },
    BuscarCupo(){
      this.cupo={}
      this.credencial=false
        this.folder=false
        this.barbijo=false
        this.certificado=false
        this.cd=false
        this.boligrafo=false
      this.$api.post('buscarCupo/'+this.ci).then((response) => {
        console.log(response.data)
        this.cupo=response.data
        // this.certificado=false
        // this.folder=false
        // this.barbijo=false
        // this.certificado=false
        // this.cd=false
        this.cupo.materials.forEach(r => {

            if(r.nombre=='CREDENCIAL Y PORTA CREDENCIAL') this.credencial=r.estado==1?true:false
            if(r.nombre=='FOLDER') this.folder=r.estado==1?true:false
            if(r.nombre=='BARBIJO') this.barbijo=r.estado==1?true:false
            if(r.nombre=='CERTIFICADO') this.certificado=r.estado==1?true:false
            if(r.nombre=='CD') this.cd=r.estado==1?true:false
            if(r.nombre=='BOLIGRAFO') this.boligrafo=r.estado==1?true:false
        });
      }).catch(err=>{
        this.$q.notify({
          color: 'negative',
          message: err.response.data.message,
          position: 'top',
          timeout: 2000
        })
      })

    },
    impresion() {
      console.log(this.cupo)
      if (this.cupo.id==undefined) {
            this.$q.notify({
              message: 'Ingrese participante',
              color: 'negative',
              icon: 'warning',
              position: 'top',
            })
        return false
      }
      //if (this.cupo.materials) {
        if (!this.credencial && !this.folder && !this.barbijo && !this.certificado && !this.cd && ! this.boligrafo) {
            this.$q.notify({
              message: 'No se puede entregar material sin seleccionar',
              color: 'negative',
              icon: 'warning',
              position: 'top',
            })
        return false
      }


          let student = this.cupo
          let materiales = ''
          let user=student.materials[0].user
          let fec1=student.materials[0].fecha
          let hora1=student.materials[0].hora
          student.materials.forEach(r => {
            //if (r.estado==1 )
          if (r.estado==1 && r.fecha==fec1)
            {materiales += r.nombre + ' + '
            //fec1=r.fecha
            user=r.user
          }
          //if (r.estado==1 && r.fecha==date.formatDate(new Date(), 'YYYY-MM-DD'))
          })
          const d = new Printd()
          let fecha = fec1+' ' + hora1
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
          <div class="right bold"> <b>${student.ci}-${student.id}</b></div>
          <div class="center bold"> <b>Universidad Técnica de Oruro</b></div>
          <div class="center bold"> <b>Facultad Nacional de Ingeniería</b></div>
          <div class="center bold"> <b>II JORNADAS DE TECNOLOGIAS DE COMUNICACIÓN 2022</b></div>
          <div class="left "> <b>Nombre: </b> ${student.nombres}</div>
          <div class="left "> <b>Carrera: </b> ${student.carrera}</div>
          <div class="left "> <b>Fecha hora: </b> ${fecha}</div>
          <div class="left "> <b>Material entregado: </b> ${materiales}</div>
          <div class="left "> <b>Usuario: </b> ${user.name}</div>

          <div style="border-top: 2px dotted #1a202c;margin-top: 50px" class="center">FIRMA</div>
          `
          if(materiales!='')
            d.print( document.getElementById('myelement') )

      /*const e = new Printd()
      let hora = date.formatDate(new Date(), 'HH:mm:ss')
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
          <div class="right bold"> <b>${student.ci}-${student.id}</b></div>
          <div class="center bold"> <b>Universidad Técnica de Oruro</b></div>
          <div class="center bold"> <b>Facultad Nacional de Ingeniería</b></div>
          <div class="center bold"> <b>II JORNADAS DE TECNOLOGIAS DE COMUNICACIÓN 2022</b></div>
          <div class="left "> <b>Nombre: </b> ${student.nombres}</div>
          <div class="left "> <b>Carrera: </b> ${student.carrera}</div>
          <div class="left "> <b>Fecha hora: </b> 2022/11/21 ${hora}</div>
          <div class="left "> <b>Material: </b> CERTIFICADO Y CD</div>
          <div class="left "> <b>Usuario: </b> ${user.name}</div>

          <div style="border-top: 2px dotted #1a202c;margin-top: 50px" class="center">FIRMA</div>
          `
      if(materiales!='')
        e.print( document.getElementById('myelement') )*/
    },
    materialInsert() {
      if (this.cupo.id==undefined) {
            this.$q.notify({
              message: 'Ingrese participante',
              color: 'negative',
              icon: 'warning',
              position: 'top',
            })
        return false
      }
      if (!this.credencial && !this.folder && !this.barbijo && !this.certificado && !this.cd && ! this.boligrafo) {
            this.$q.notify({
              message: 'No se puede entregar material sin seleccionar',
              color: 'negative',
              icon: 'warning',
              position: 'top',
            })
        return false
      }

      this.$q.loading.show()
      this.$api.post('material', {id:this.cupo.id,
        credencial: this.credencial,
        folder: this.folder,
        barbijo: this.barbijo,
        certificado: this.certificado,
        cd: this.cd,
        boligrafo: this.boligrafo,
        fecha:date.formatDate(new Date(), 'YYYY-MM-DD'),
        hora:date.formatDate(new Date(), "HH:mm:ss")
      })
        .then((response) => {
          this.totalreg()
          // console.log(response.data)
          let student = response.data
          this.$q.loading.hide()
          this.$q.notify({
            message: 'Material entregado',
            color: 'green-4',
            textColor: 'white',
            icon: 'done',
            position: 'top',
          })
          let materiales = ''
          let user=student.materials[0].user

          student.materials.forEach(r => {
            if (r.estado==1 && r.fecha==date.formatDate(new Date(), 'YYYY-MM-DD'))
            {materiales += r.nombre + '  +'
            user=r.user
          }
          })
          const d = new Printd()
          let fecha = date.formatDate(new Date(), 'DD/MM/YYYY HH:mm:ss')
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
          <div class="right bold"> <b>${student.ci}-${student.id}</b></div>
          <div class="center bold"> <b>Universidad Técnica de Oruro</b></div>
          <div class="center bold"> <b>Facultad Nacional de Ingeniería</b></div>
          <div class="center bold"> <b>II JORNADAS DE TECNOLOGIAS DE COMUNICACIÓN 2022</b></div>
          <div class="left "> <b>Nombre: </b> ${student.nombres}</div>
          <div class="left "> <b>Carrera: </b> ${student.carrera}</div>
          <div class="left "> <b>Fecha hora: </b> ${fecha}</div>
          <div class="left "> <b>Material entregado: </b> ${materiales}</div>
          <div class="left "> <b>Usuario: </b> ${user.name}</div>

          <div style="border-top: 2px dotted #1a202c;margin-top: 50px" class="center">FIRMA</div>          `
           if(materiales!='')
          d.print( document.getElementById('myelement') )
          /*const e = new Printd()
          let hora = date.formatDate(new Date(), 'HH:mm:ss')
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
          <div class="right bold"> <b>${student.ci}-${student.id}</b></div>
          <div class="center bold"> <b>Universidad Técnica de Oruro</b></div>
          <div class="center bold"> <b>Facultad Nacional de Ingeniería</b></div>
          <div class="center bold"> <b>II JORNADAS DE TECNOLOGIAS DE COMUNICACIÓN 2022</b></div>
          <div class="left "> <b>Nombre: </b> ${student.nombres}</div>
          <div class="left "> <b>Carrera: </b> ${student.carrera}</div>
          <div class="left "> <b>Fecha hora: </b> 2022/11/21 ${hora}</div>
          <div class="left "> <b>Material: </b> CERTIFICADO Y CD</div>
          <div class="left "> <b>Usuario: </b> ${user.name}</div>

          <div style="border-top: 2px dotted #1a202c;margin-top: 50px" class="center">FIRMA</div>
          `
          if(materiales!='')
            e.print( document.getElementById('myelement') )*/
          // this.credencial = false
          // this.folder = false
          // this.barbijo = false
          // this.certificado = false
          this.cupo = {}
          this.credencial=false
          this.folder=false
          this.barbijo=false
          this.certificado=false
          this.cd=false
          this.boligrafo=false
          this.ci=null
          this.$nextTick(() => {
            this.$refs.ci.focus()
          })

        })
        // .catch((error) => {
        //   this.$q.loading.hide()
        //   this.$q.notify({
        //     message: 'Error al entregar material',
        //     color: 'red-4',
        //     textColor: 'white',
        //     icon: 'error',
        //     position: 'top',
        //   })
        // })
    },
  },
}
</script>

<style scoped>

</style>
