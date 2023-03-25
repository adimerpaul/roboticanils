<template>
<q-page>
  <q-table
    virtual-scroll
    :virtual-scroll-item-size="48"
    :selected-rows-label="getSelectedString" v-model:selected="selected" selection="multiple" dense :rows-per-page-options="[15,100,150,500]" :rows="cupos" :columns="cupoColumns" :filter="cupoSearch">
    <template v-slot:top-right>
      <q-btn flat round dense icon="o_print" @click="cupoPrint" />
      <q-btn flat round dense icon="refresh" @click="cupoGet();cupoSearch=''" />
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
      <q-td :props="props" >
        <template v-if="store.user.role=='ADMINISTRADOR'">
          <q-btn flat round dense icon="qr_code" @click="qrPrint(props.row)" />
          <q-btn flat round dense icon="public" @click="cupoRegister(props.row)" />
          <!--<q-btn flat round dense icon="recycling" @click="cupoReset(props.row)" />-->
          <q-btn flat round dense icon="published_with_changes" @click="cupoChange(props.row)" />
        </template>
      </q-td>
    </template>
    <template v-slot:body-cell-foto="props">
      <q-td :props="props">
        <q-btn flat round dense icon="o_rotate_90_degrees_cw" @click="rotateFoto(props.row)" />
        <q-img @click="fotoShow(props.row)" :src="url+'../imagenes/'+props.row.foto" width="30px" height="30px" />
      </q-td>
    </template>

  </q-table>
  <q-dialog v-model="cupoUpdateShow">
    <q-card >
      <q-card-section class="q-pb-none">
        <div class="text-h6 ">Actualizar Tipo</div>
      </q-card-section>
      <q-card-section class="q-pt-none">
        <q-form @submit.prevent="CambioTipo">
          <div class="row">
            <div class="col-12">
              <q-select outlined v-model="cupo.tipo" label="Tipo" :options="['PARTICIPANTE','EXPOSITOR','ORGANIZADOR','DOCENTE']" />
            </div>
            <div class="col-12">
              <q-btn type="submit" color="primary" label="Actualizar"  class="full-width"/>
            </div>
          </div>
        </q-form>
      </q-card-section>
    </q-card>
  </q-dialog>
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
        <q-input dense outlined v-model="cupo.ci" label="Cedula de Identidad" autofocus @update:model-value="validar(cupo.ci)" required/>
        <q-input dense outlined v-model="cupo.nombres" label="Nombre Completo" required/>
        <div v-if="reg">{{mensaje}}</div>
        <div>
            <q-btn label="Registrar" type="submit" color="green" :disable="reg"/>
            <q-btn label="Cancelar"  color="red"  class="q-ml-sm" v-close-popup />
          </div>
        </q-form>
      </q-card-section>
    </q-card>
  </q-dialog>
<!--  <pre>{{store.cupos}}</pre>-->
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
      url: process.env.API,
      cupoSearch: '',
      store:useCounterStore(),
      cupos: [],
      cupo:{},
      reg:false,
      dialogReg:false,
      cupoUpdateShow: false,
      valida:true,
      val:false,
      cupoColumns:[
        {name:'action', label:'Acción', field:'action', align:'left', sortable:true},
        {name:'ci', label:'C.I.', field:'ci', align:'left', sortable:true},
        {name:'tipo', label:'Usuario', field:'tipo', align:'left', sortable:true},
        {name:'estado', label:'Estado', field:'estado', align:'left', sortable:true},
        {name:'id', label:'ID', field:'id', align:'left', sortable:true},
        {name:'nombre', label:'Nombre', field:'nombre', align:'left', sortable:true},
        // {name:'carrera', label:'Carrera', field:'carrera', align:'left', sortable:true},
       // {name:'celular', label:'Celular', field:'celular', align:'left', sortable:true},
        //{name:'correo', label:'Correo', field:'correo', align:'left', sortable:true},
      //  {name:'foto', label:'Foto', field:'foto', align:'left', sortable:true},
      ]
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
    cupoRegister(cc){
      this.cupo=cc
      this.dialogReg=true
    },
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
    qrPrint(row){
      // console.log(row)
      QRCode.toDataURL(process.env.API_FRONT+'registro/'+row.codigo)
        .then(url => {
          this.$q.dialog({
            title: 'Código QR',
            message: `<a href="${url}" target="_blank"><div style="text-align: center"><img src="${url}" /></div></a>`,
            html: true,
            ok: false,
            // persistent: true,
            cancel: {
              label: 'Cerrar',
              color: 'primary',
              flat: true,
              noDismiss: true
            }
          })
          // console.log(url)
        })
        .catch(err => {
          console.error(err)
        })
    },
    regStudent(){
      //window.open(process.env.API_FRONT+'registro/'+cupo.codigo,'_blank')
        if(this.reg){
          return false
        }
        this.$q.loading.show()
        //this.cupo.foto=this.foto
        this.cupo.nombres= this.cupo.nombres.toUpperCase()
        this.$api.put(`updateRegistro/${this.cupo.id}`, this.cupo).then((response) => {
          this.dialogReg=false
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
  
    },
    rotateFoto(row){
      this.$q.dialog({
        title: 'Rotar Foto',
        message: '¿Desea rotar la foto?',
        ok: {
          label: 'Rotar',
          color: 'primary',
          flat: true,
          noDismiss: true,
        },
        cancel: {
          label: 'Cancelar',
          color: 'primary',
          flat: true,
          noDismiss: true
        }
      }).onOk(() => {
        this.$q.loading.show()
        this.$api.post(this.url+'rotateFoto',row)
          .then(response => {
            row.foto = response.data.foto
            // console.log(response.data)
            this.$q.loading.hide()
            // this.cupoGet()
          })
          .catch(error => {
            this.$q.loading.hide()
            console.log(error)
          })
      })


    },
    cupoChange(cupo){
      this.cupoUpdateShow=true
      console.log(cupo)
      this.cupo=cupo

    },
    cupoReset(cupo){
      this.valido=true
      cupo.materials.forEach(r => {
          if(r.estado==1){
            this.valido=false
          }
      })
      if(!this.valido){
        this.$q.notify({
            message: 'Existe registro material',
            color: 'red-4',
            textColor: 'white',
            icon: 'info',
            position: 'top'
          })
        return false;
      }
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

      // const doc = new jsPDF('landscape', null, 'letter');
      // let img = new Image()
      // img.src = "folleto.jpg"
      // this.selected.forEach((item, index) => {
      //   if (index % 2 === 0) {
      //     doc.addImage(img, 'JPEG', 4, 5, 135, 205)
      //     doc.setFont("helvetica", "bold");
      //     doc.setFontSize(10);
      //     doc.setTextColor(125, 125, 125);
      //     doc.text(item.id + '', 135, 200, 'center')
      //     doc.addImage(item.qr, 'JPEG', 10, 10, 10, 10)
      //   } else if (index % 2 === 1) {
      //     doc.addImage(img, 'JPEG', 140, 5, 135, 205)
      //     doc.setFont("helvetica", "bold");
      //     doc.setFontSize(10);
      //     doc.setTextColor(125, 125, 125);
      //     doc.text(item.id + '', 270, 200, 'center')
      //     doc.addPage();
      //   }
      // });
      //
      //
      // // $( '#docpdf' ).attr('src', doc.output('datauristring'));
      // // doc.save("Certificado "+date.formatDate(Date.now(),'DD-MM-YYYY')+".pdf");
      // window.open(doc.output('bloburl'), '_blank');

    },
    getSelectedString () {
      return this.selected.length === 0 ? '' : `${this.selected.length} registro${this.selected.length > 1 ? 's' : ''} seleccionados de ${this.store.cupos.length}`
    },
    fotoShow(row) {
      this.$q.dialog({
        title: 'Foto',
        message: '<img src="'+this.url+'../imagenes/'+row.foto+'" width="100%" />',
        html: true,
        persistent: true,
        ok: {
          label: 'Cerrar',
          color: 'primary'
        }
      })
    },
    CambioTipo() {
      this.$q.loading.show();
      this.$api.post(`updateTipo`,this.cupo).then((res) => {
      }).catch((error) => {
          console.log(error);
      }).finally(() => {
        this.cupoUpdateShow=false
        this.$q.loading.hide();
      });
  },
    cupoGet() {
      this.$q.loading.show();
      this.store.cupos=[];
      this.cupos=[]
      this.$api.get(`cupo`).then((res) => {
        res.data.forEach((item) => {
          item.estado=item.nombres==null?'Libre':'Ocupado';
          item.nombre=(item.nombres==null?'':item.nombres)+' '+(item.apellidos==null?'':item.apellidos)
          // QRCode.toDataURL(process.env.API_FRONT+'registro/'+item.codigo)
          //   .then(url => {
          //     item.qr = url;
          //   })
          //   .catch(err => {
          //     console.error(err)
          //   })
          this.cupos.push(item);
        });
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
