<template>
    <q-page>
      <q-table
        virtual-scroll
        :virtual-scroll-item-size="48" dense :rows-per-page-options="[15,100,150,500]" :rows="estudiantes" :columns="cupoColumns" :filter="cupoSearch">
        <template v-slot:top-right>
          <q-btn color="primary" icon="add_circle_outline" label="Registro" @click="estudiante={};dialogReg=true" />
          <q-input dense outlined placeholder="Buscar..." v-model="cupoSearch">
            <template v-slot:prepend>
              <q-icon name="search" />
            </template>
          </q-input>
        </template>
        <template v-slot:body-cell-estado="props">
          <q-td :props="props">
            <q-chip :color="props.row.estado=='Libre'?'green':'red'" text-color="white" dense>{{props.row.estado}}</q-chip>
          </q-td>
        </template>
        <template v-slot:body-cell-action="props">
          <q-td :props="props">
            <q-btn flat round dense color="yellow" icon="edit" @click="estudiante2=props.row; dialogMod=true " />
            <q-btn flat round dense color="red" icon="delete" @click="delteEst(props.row)" />
          </q-td>
        </template>
      </q-table>
    <!--  <pre>{{store.cupos}}</pre>-->
    <q-dialog v-model="dialogReg" full-width>
      <q-card >
        <q-card-section>
          <div class="text-h6">REGISTRO DE ESTUDIANTE</div>
        </q-card-section>

        <q-card-section class="q-pt-none">
          <q-form
            @submit="regStudent"
            class="q-gutter-md"
          >
          <q-input dense outlined v-model="estudiante.ci" label="Cedula de Identidad" autofocus @update:model-value="validar(estudiante.ci)" required/>
          <q-input dense outlined v-model="estudiante.nombres" label="Nombre Completo" required/>
          <q-select dense outlined v-model="estudiante.carrera" label="Carrera" :options="carreras" required/>
          <q-input dense outlined v-model="estudiante.celular" label="Celular" />
          <q-input dense outlined v-model="estudiante.direccion" label="Direccion" />
          <div v-if="val">{{mensaje}}</div>
          <div>
              <q-btn label="Registrar" type="submit" color="green" :disable="val"/>
              <q-btn label="Cancelar"  color="red"  class="q-ml-sm" v-close-popup />
            </div>
          </q-form>
        </q-card-section>
      </q-card>
    </q-dialog>
    <q-dialog v-model="dialogMod" full-width>
      <q-card >
        <q-card-section>
          <div class="text-h6">MODIFICAR ESTUDIANTE</div>
        </q-card-section>

        <q-card-section class="q-pt-none">
          <q-form
            @submit="ModStudent"
            class="q-gutter-md"
          >
          <q-input dense outlined v-model="estudiante2.ci" label="Cedula de Identidad" required readonly/>
          <q-input dense outlined v-model="estudiante2.nombres" label="Nombre Completo" required/>
          <q-select dense outlined v-model="estudiante2.carrera" label="Carrera" :options="carreras" required/>
          <q-input dense outlined v-model="estudiante2.celular" label="Celular" />
          <q-input dense outlined v-model="estudiante2.direccion" label="Direccion" />
          <div>
              <q-btn label="Modificar" type="submit" color="yellow" />
              <q-btn label="Cancelar"  color="red"  class="q-ml-sm" v-close-popup />
            </div>
          </q-form>
        </q-card-section>
      </q-card>
    </q-dialog>

    </q-page>
    </template>

    <script>
    import {useCounterStore} from "stores/example-store";
    import {jsPDF} from "jspdf";
    import QRCode from 'qrcode'
    import download from 'downloadjs'

    export default {
      name: `Cupo`,
      data() {
        return {
          selected: [],
          val:false,
          mensaje:'',
          estudiante:{},
          estudiante2:{},
          dialogReg:false,
          dialogMod:false,
          url: process.env.API,
          cupoSearch: '',
          store:useCounterStore(),
          estudiantes:[],
          cupoColumns:[
            {name:'action', label:'Acción', field:'action', align:'left', sortable:true},
            {name:'ci', label:'C.I.', field:'ci', align:'left', sortable:true},
            {name:'id', label:'ID', field:'id', align:'left', sortable:true},
            {name:'nombre', label:'Nombre', field:'nombres', align:'left', sortable:true},
            {name:'carrera', label:'Carrera', field:'carrera', align:'left', sortable:true},
            {name:'celular', label:'Celular', field:'celular', align:'left', sortable:true},
            {name:'direccion', label:'direccion', field:'direccion', align:'left', sortable:true},
          ],
          carreras:[
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

        }
      },
      created() {
        // if (this.store.cupos.length === 0) {
          this.cupoGet();
        // }
      },
      computed: {
      },
      methods: {
        delteEst(est){
          this.$q.dialog({
            title: 'Eliminar Estudiante',
            message: 'Esta seguro de eliminar?'
          }).onOk(() => {
            this.$api.delete(`student/`+est.id).then((res) => {
            this.$q.notify({
              message: 'Estudiante Eliminado.',
              color: 'green',
              icon:'info'
            })
            this.cupoGet()
          })
          }).onCancel(() => {
            // console.log('Cancel')
          }).onDismiss(() => {
            // console.log('I am triggered on both OK and Cancel')
          })
        },
        ModStudent(){
          this.$q.loading.show()
          this.$api.put(`student/`+this.estudiante2.id,this.estudiante2).then((res) => {
            this.dialogMod=false
            this.$q.notify({
              message: 'Estudiante Modificado.',
              color: 'green',
              icon:'info'
            })
            this.cupoGet()
          })
        },
        regStudent(){
          if(this.val)
          {return false}
          this.$q.loading.show()
          this.$api.post(`student`,this.estudiante).then((res) => {
            this.dialogReg=false
            this.$q.notify({
              message: 'Estudiante Registrado.',
              color: 'green',
              icon:'info'
            })
            this.cupoGet()
          })

        },
        validar(ci){
          this.$api.post(`buscarStudent/`+ci).then((res) => {
            console.log(res.data)
            this.mensaje=''
            this.val=false
            if(res.data){
              this.val=true
              this.mensaje='El CI esta Registrado'
            }

          }).catch((error) => {
              console.log(error);
          }).finally(() => {
          });
        },
        cupoRegister(cupo){
          window.open(process.env.API_FRONT+'registro/'+cupo.codigo,'_blank')
        },

        cupoReset(cupo){
          this.$q.dialog({
            title: 'Confirmación',
            message: '¿Está seguro de resetear el cupo?',
            ok: {
              label: 'Si',
              color: 'primary',
              flat: true,
              noDismiss: true
            },
            cancel: {
              label: 'No',
              color: 'primary',
              flat: true,
              noDismiss: true
            }
          }).onOk(() => {
            this.$api.put('cupo/'+cupo.id,{
              ci: "",
              nombres: "",
              apellidos: '',
              carrera: '',
              foto: 'avatar.png',
              celular: '',
              direccion: '',
              correo: '',
              credencial: false,
              folder: false,
              barbijo: false,
              certificado: false,
            }).then((response) => {
              // console.log(response.data)
              this.$q.notify({
                message: 'Cupo reseteado',
                color: 'green-4',
                textColor: 'white',
                icon: 'check_circle',
                position: 'top'
              })
              this.cupoGet();
            }).catch((error) => {
              this.$q.notify({
                message: 'Error al resetear el cupo',
                color: 'red-4',
                textColor: 'white',
                icon: 'error',
                position: 'top'
              })
            })
          })
        },
        cupoPrint: function () {
          if (this.selected.length <= 0) {
            this.$q.notify({
              color: 'red-4',
              textColor: 'white',
              icon: 'report_problem',
              position: 'top',
              message: 'No hay registros seleccionados'
            });
            return false;
          }

          this.$q.loading.show()
          this.$api.post('cupoPdf',this.selected).then(response => {
            console.log(response.data);
            window.open(this.url+'cupoFile', '_blank');
              this.$q.notify({
                color: 'green-4',
                textColor: 'white',
                icon: 'check_circle',
                position: 'top',
                message: 'PDF generado'
              });
          })
            .catch(error => {
            this.$q.notify({
              color: 'red-4',
              textColor: 'white',
              icon: 'report_problem',
              position: 'top',
              message: 'Error al generar PDF'
            });
          })
            .finally(() => {
            this.$q.loading.hide()
          })


        },


        cupoGet() {
          this.$q.loading.show();
          this.store.estudiantes=[];
          this.$api.get(`student`).then((res) => {
            this.estudiantes=res.data

          }).catch((error) => {
              console.log(error);
          }).finally(() => {
            this.$q.loading.hide();
          });
        },
      },

    }
    </script>

    <style scoped>

    </style>
