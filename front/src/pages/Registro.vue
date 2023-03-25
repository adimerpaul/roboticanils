<template>
  <q-layout view="lHh Lpr lFf" class="bg-grey-2">
    <q-header class="bg-grey-2">
      <q-toolbar>
        <q-toolbar-title class="text-bold text-primary text-center">
          Registro de estudiante
        </q-toolbar-title>
      </q-toolbar>
    </q-header>
    <q-page-container>
      <router-view>
        <q-page>
          <div v-if="cupoBool" class="flex flex-center text-bold text-center text-h2 text-red-5">
            Este cupo ya fue registrado
          </div>
          <q-form class="q-pa-lg" v-else @submit.prevent="cupoUpdate">
            <div class="row">
              <div class="col-12 col-sm-6 q-px-xs"  >
                <q-input
                  @keyup="studentSearch(cupo);validar(cupo.ci)"
                  v-model="cupo.ci"
                  label="Carnet de identidad"
                  outlined
                  required
                  class="q-mb-md"
                />
              </div>
              <template v-if="cupo.nombres!=''">
              <div class="col-12 col-sm-6 q-px-xs"  >
                <q-input
                  required
                  v-model="cupo.nombres"
                  label="Nombre completo"
                  style="text-transform: uppercase"
                  hint="comenzando por el apellido paterno"
                  outlined
                  class="q-mb-md"
                  readonly

                />
              </div>
              <div class="col-12 col-sm-6 q-px-xs" >
                <q-select
                  v-model="cupo.carrera"
                  label="Carrera"
                  outlined
                  :options="carreas"
                  class="q-mb-md"
                  readonly

                />
              </div>
              <div class="col-12 col-sm-6 q-px-xs" >
                <q-input
                  v-model="cupo.celular"
                  label="Celular"
                  outlined
                  class="q-mb-md"
                />
              </div>
              <div class="col-12 col-sm-6 q-px-xs" >
                <q-input
                  v-model="cupo.direccion"
                  label="Direccion"
                  outlined
                  class="q-mb-md"
                  readonly
                />
              </div>
              <div class="col-12 col-sm-6 q-px-xs" >
                <q-input
                  v-model="cupo.correo"
                  label="Correo"
                  type="email"
                  outlined
                  required
                  class="q-mb-md"
                />
              </div>
              <div v-if="reg" class="q-pa-xs text-bold text-red">{{mensaje}}</div>
              <div class="col-12 col-sm-12 q-px-xs flex flex-center q-pb-xs">
                <q-uploader
                  accept=".jpg, .png"
                  @added="uploadFile"
                  auto-upload
                  max-files="1"
                  label="Subir foto"
                  flat
                  max-file-size="5000000"
                  @rejected="onRejected"
                  bordered
                />
              </div>
              <div class="col-12 col-sm-12 q-px-xs" >
                <q-btn
                  :loading="loading"
                  color="primary"
                  label="Registrar"
                  no-caps
                  type="submit"
                  icon="add_circle_outline"
                  class="full-width"
                  :disable="reg"
                  />
              </div>
              </template>
            </div>
          </q-form>
<!--          <q-btn color="white" text-color="black" label="Standard" @click="impresion"/>-->

        </q-page>
      </router-view>
    </q-page-container>

  </q-layout>

</template>

<script>
import { jsPDF } from "jspdf";
export default {
  name: `Registro`,
  data() {
    return {
      carreas:[
    'INGENIERIA CIVIL (MENCION ESTRUCTURAS)',
    'INGENIERIA CIVIL (MENCION HIDRAULICA)',
    'INGENIERIA CIVIL (MENCION SANITARIA Y AMBIENTAL)',
    'INGENIERIA CIVIL (MENCION VIAS DE COMUNICACION)',
    'INGENIERIA CIVIL (TECNICO ESTRUCTURAS)',
    'INGENIERIA DE SISTEMAS (MENCION DIR. Y GES. EMPRESARIAL)',
    'INGENIERIA DE SISTEMAS (MENCION GESTION DE LA INFORMACION)',
    'INGENIERIA DE SISTEMAS (MENCION MODELAMIENTO Y OPT. DE R-P)',
    'INGENIERIA ELECTRICA (MENCION SIS. ELECTRICOS INDUSTRIALES)',
    'INGENIERIA ELECTRICA (TECNICA)',
    'INGENIERIA ELECTRONICA (MENCION AUTOMATICA)',
    'INGENIERIA ELECTRONICA (MENCION TELECOMUNICACION)',
    'INGENIERIA INDUSTRIAL',
    'INGENIERIA INFORMATICA (MENCION DESARROLLO DE SOFTWARE)',
    'INGENIERIA INFORMATICA (MENCION TELEMATICA)',
    'INGENIERIA MECATRONICA',
        'CISCO',
        'OTROS'
      ],
      cupoBool: true,
      cupo: {
        ci: "",
        nombres: "",
        apellidos: "",
        carrera: "",
        celular: "",
        direccion: "",
        correo: "",
        foto: "",
      },
      foto: '',
      loading:false,
      codigo:this.$route.params.id,
      students: [],
      reg:false,
      mensaje:'',
    }
  },
  created() {
    this.$api.get(`student`).then((response) => {
      this.students = response.data
    })
  },
  mounted() {

    this.$q.loading.show()
    this.$api.get(`cupo/${this.codigo}`).then((response) => {
      this.cupo = response.data
      if (this.cupo.nombres==null) {
        this.cupoBool = false
      }
    }).finally(() => {
      this.$q.loading.hide()
    })
  },
  methods: {
    validar(ci){
      this.$api.post('validaCupon/'+ci).then(response => {
        console.log(response.data)
        this.reg=false
        this.mensaje=''
          if(response.data && response.data.id!=this.cupo.id){
            this.reg=true
            this.mensaje='El ci esta registrado'
          }
      })

    },
    impresion(){
          let cm=this;
          var doc = new jsPDF('P','cm','letter')
          doc.setFont("courier");
          var img = new Image()
          img.src = 'baner.jpg'
          doc.addImage(img, 'jpg', 1, 0.5, 19, 2)
          doc.setFont(undefined,'bold')
          doc.setFontSize(12);
          doc.text(5, 3, 'II JORNADAS DE TECNOLGIAS DE COMUNICACION 2022')
          doc.text(8, 3.5, 'FORMULARIO DE INSCRIPCION')
          doc.text(8, 4.5, 'DATOS DEL PARTICIPANTE')
          doc.text(3, 5, 'CI')
          doc.text(3, 5.5, 'NOMBRE')
          doc.text(3, 6, 'CARRERA')
          doc.text(3, 6.5, 'CELULAR')
          doc.text(3, 7, 'CORREO')
          doc.setFont(undefined,'normal')

          doc.text(6, 5, cm.cupo.ci+'')
          doc.text(6, 5.5, cm.cupo.nombres)
          doc.text(6, 6, cm.cupo.carrera)
          doc.text(6, 6.5, cm.cupo.celular==null?'':cm.cupo.celular)
          doc.text(6, 7, cm.cupo.correo==null?'':cm.cupo.correo)
          doc.save(cm.cupo.ci+".pdf");
          //window.open(doc.output('bloburl'), '_blank');
    },
    onRejected (rejectedEntries) {
      this.$q.notify({
        type: 'negative',
        message: `${rejectedEntries.length} el archivo paso las restricciones de validación`
      })
    },
    uploadFile (file) {
      this.loading=true
      let dialog = this.$q.dialog({
        message: 'Subiendo... 0%',
      })
      let percentage = 0
      const fd = new FormData()
      fd.append('file', file[0])
      return new Promise((resolve, reject) => {
        this.$api.post('upload',
          fd,
          {
            headers: { 'Content-Type': 'multipart/form-data' },
            onUploadProgress: (progressEvent) => {
              percentage = Math.round((progressEvent.loaded / progressEvent.total) * 100)
              dialog.update({
                message: `Subiendo... ${percentage}%`
              })
              if (percentage>=100){
                dialog.hide()
              }
            }
          })
          .then(res => {
            this.foto=res.data
            this.loading=false
            resolve(file)
          })
          .catch(err => reject(err))
      })
    },
    studentSearch(cupo){
      console.log(cupo)
      this.cupo.nombres=""
      this.cupo.apellidos=""
      this.cupo.carrera=""
      this.cupo.celular=""
      this.cupo.direccion=""
      this.cupo.correo=""
      this.students.forEach((student) => {
        if (student.ci==cupo.ci){
          this.cupo.nombres=student.nombres
          this.cupo.apellidos=student.apellidos
          this.cupo.carrera=student.carrera
          this.cupo.celular=student.celular
          this.cupo.direccion=student.direccion
          this.cupo.correo=student.correo
          return false
        }
      })
    },
    cupoUpdate() {
      if(this.reg){
        return false
      }
      if (this.foto=='') {
        this.$q.notify({
          message: 'Tienes que subir una foto',
          type: 'negative',
          icon: 'warning',
          position: 'top'
        })
        return false
      }
      this.$q.dialog({
        title: 'Confirmación',
        message: '¿Estas seguro de registrar este cupo?',
        cancel: true,
        persistent: true
      }).onOk(() => {
        this.$q.loading.show()
        this.cupo.foto=this.foto
        this.cupo.nombres= this.cupo.nombres.toUpperCase()
        this.$api.put(`updateRegistro/${this.cupo.id}`, this.cupo).then((response) => {
          console.log(this.cupo)
          console.log(response.data)
          this.impresion(this.cupo)
          this.$q.notify({
            message: 'Cupo registrado',
            color: 'positive',
            icon: 'check_circle',
            position: 'top'
          })
          this.cupoBool = true
        }).catch(res=>{
          this.$q.notify({
            message: res.response.data.message,
            color: 'negative',
            icon: 'warning',
            position: 'top'
          })
        }).finally(() => {
          this.$q.loading.hide()
        })
      })


    }
  }
}
</script>

<style scoped>

</style>
