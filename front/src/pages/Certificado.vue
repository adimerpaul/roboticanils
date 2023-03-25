<template>
  <q-page>
    <q-table
      virtual-scroll
      :virtual-scroll-item-size="48"
      :selected-rows-label="getSelectedString" v-model:selected="selected" selection="multiple" dense :rows-per-page-options="[15,100,150,500]" :rows="cupos" :columns="cupoColumns" :filter="cupoSearch">
      <template v-slot:top-right>
        <q-toggle v-model="fondo" true-value="SI" false-value="NO" :label="`${fondo} Fondo`"/>
        
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
        <q-td :props="props">
<!--          <q-btn flat round dense icon="qr_code" @click="qrPrint(props.row)" />-->
<!--          <q-btn flat round dense icon="public" @click="cupoRegister(props.row)" />-->
<!--          <q-btn flat round dense icon="recycling" @click="cupoReset(props.row)" />-->
        </q-td>
      </template>
      <template v-slot:body-cell-foto="props">
        <q-td :props="props">
          <q-btn flat round dense icon="o_rotate_90_degrees_cw" @click="rotateFoto(props.row)" />
          <q-img @click="fotoShow(props.row)" :src="url+'../imagenes/'+props.row.foto" width="30px" height="30px" />
        </q-td>
      </template>
    </q-table>
    <!--  <pre>{{store.cupos}}</pre>-->
  </q-page>
</template>

<script>
import {useCounterStore} from "stores/example-store";
import QRCode from 'qrcode'

export default {
  name: `Certificado`,
  data() {
    return {
      selected: [],
      fondo:'NO',
      url: process.env.API,
      cupoSearch: '',
      store:useCounterStore(),
      cupos: [],
      cupoColumns:[
        {name:'action', label:'Acción', field:'action', align:'left', sortable:true},
        {name:'tipo', label:'Usuario', field:'tipo', align:'left', sortable:true},
        {name:'ci', label:'C.I.', field:'ci', align:'left', sortable:true},
        {name:'estado', label:'Estado', field:'estado', align:'left', sortable:true},
        {name:'id', label:'ID', field:'id', align:'left', sortable:true},
        {name:'nombre', label:'Nombre', field:'nombre', align:'left', sortable:true},
        {name:'carrera', label:'Carrera', field:'carrera', align:'left', sortable:true},
        {name:'celular', label:'Celular', field:'celular', align:'left', sortable:true},
        {name:'correo', label:'Correo', field:'correo', align:'left', sortable:true},
        {name:'foto', label:'Foto', field:'foto', align:'left', sortable:true},
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
    cupoRegister(cupo){
      window.open(process.env.API_FRONT+'registro/'+cupo.codigo,'_blank')
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
            console.log(response.data)
            this.$q.loading.hide()
            this.cupoGet()
          })
          .catch(error => {
            this.$q.loading.hide()
            console.log(error)
          })
      })


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
      this.$api.post('certificadoPdf',{lista:this.selected,fondo:this.fondo}).then(response => {
        console.log(response.data);
        window.open(this.url+'certificadoFile', '_blank');
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
    cupoGet() {
      this.$q.loading.show();
      this.store.cupos=[];
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
