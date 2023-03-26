<template>
  <q-layout view="lHh Lpr lFf" class="bg-grey-2">
    <q-header class="bg-grey-2">
      <q-toolbar>
        <q-btn
          flat
          dense
          round
          color="primary"
          icon="menu"
          aria-label="Menu"
          @click="toggleLeftDrawer"
        />
        <q-toolbar-title class="text-bold text-primary">
<!--          <q-item v-if="store.userChat.avatar!=undefined">-->
<!--            <q-item-section avatar>-->
<!--              <q-avatar>-->
<!--                <img  :src="store.userChat.avatar">-->
<!--              </q-avatar>-->
<!--            </q-item-section>-->
<!--            <q-item-section>-->
<!--              <q-item-label lines="1">-->
<!--                {{ store.userChat.name }}-->
<!--              </q-item-label>-->
<!--              <q-item-label class="conversation__summary" caption>-->
<!--                En linea {{ store.userChat.fechaConexion }}-->
<!--              </q-item-label>-->
<!--            </q-item-section>-->
<!--          </q-item>-->
        </q-toolbar-title>
        <div class="row q-pt-xs">
          <div class="col-8">
            <q-input dense rounded outlined v-model="search" placeholder="Buscar">
              <template v-slot:prepend>
                <q-icon name="search" />
              </template>
            </q-input>
          </div>
          <div class="col-2 flex flex-center">
            <q-btn
              flat
              dense
              round
              color="primary"
              icon="o_notifications"
              aria-label="Notifications">
              <q-badge color="primary" floating transparent>
                4
              </q-badge>
            </q-btn>
          </div>
          <div class="col-2 flex flex-center">
            <q-btn round>
              <q-avatar size="38px">
                <img v-if="store.user.avatar!=undefined" :src="url+'../imagenes/'+store.user.avatar">
              </q-avatar>
              <q-menu>
                <q-list style="min-width: 100px">
                  <q-item clickable v-ripple>
                    <q-item-section avatar>
                      <q-icon color="primary" name="perm_contact_calendar" />
                    </q-item-section>
                    <q-item-section>{{store.user.name}}</q-item-section>
                  </q-item>
                  <q-separator />
                  <q-item clickable v-ripple @click="logout">
                    <q-item-section avatar>
                      <q-icon color="primary" name="logout" />
                    </q-item-section>
                    <q-item-section>Salir</q-item-section>
                  </q-item>
                </q-list>
              </q-menu>
            </q-btn>
          </div>
        </div>
      </q-toolbar>
    </q-header>

    <q-drawer
      v-model="leftDrawerOpen"
      show-if-above
      bordered
    >
      <q-list>
        <q-item-label class="q-pa-xs">
          <div class="row">
            <div class="col-3 flex flex-center">
              <!--              <q-avatar size="48px">-->
              <q-icon color="primary" size="48px" name="o_schema" />
              <!--              </q-avatar>-->
            </div>
            <div class="col-9">
              <div class="text-h6 text-bold "  > {{ store.user.name }}</div>
              <div class="text-caption"> <b>Rol:</b> <q-badge :label="store.user.role" /></div>
            </div>
          </div>
        </q-item-label>
        <q-separator />
        <q-toolbar class="bg-primary text-white shadow-2">
          <q-toolbar-title>Opciones</q-toolbar-title>
        </q-toolbar>
        <q-list bordered>
          <q-list >
            <q-item clickable exact to="/" active-class="bg-blue-grey-6 text-white">
              <q-item-section avatar>
                <q-icon name="o_home" />
              </q-item-section>
              <q-item-section>
                  Principal
              </q-item-section>
            </q-item>
            <!--<q-item clickable exact to="estudiantes" v-if="store.user.role=='ADMINISTRADOR'" active-class="bg-blue-grey-6 text-white">
              <q-item-section avatar>
                <q-icon name="o_people" />
              </q-item-section>
              <q-item-section>
                Estudiantes
              </q-item-section>
            </q-item>-->
            <q-item clickable exact to="user" v-if="store.user.role=='ADMINISTRADOR'"  active-class="bg-blue-grey-6 text-white">
              <q-item-section avatar>
                <q-icon name="o_account_circle" />
              </q-item-section>
              <q-item-section>
                Usuarios
              </q-item-section>
            </q-item>
            <q-item clickable exact to="cupo" v-if="store.user.role=='ADMINISTRADOR'||store.user.role=='INSCRIPCION'||store.user.role=='ACREDITACION'"  active-class="bg-blue-grey-6 text-white">
              <q-item-section avatar>
                <q-icon name="o_confirmation_number" />
              </q-item-section>
              <q-item-section>
                Cupos
              </q-item-section>
            </q-item>
            <q-item clickable exact to="credencial"  v-if="store.user.role=='ADMINISTRADOR'||store.user.role==''||store.user.role=='ACREDITACION'" active-class="bg-blue-grey-6 text-white">
              <q-item-section avatar>
                <q-icon name="o_credit_card" />
              </q-item-section>
              <q-item-section>
                Credenciales
              </q-item-section>
            </q-item>
            <q-item clickable exact to="certificado"  v-if="store.user.role=='ADMINISTRADOR'||store.user.role==''||store.user.role=='ACREDITACION'" active-class="bg-blue-grey-6 text-white">
              <q-item-section avatar>
                <q-icon name="o_verified_user" />
              </q-item-section>
              <q-item-section>
                Certificados
              </q-item-section>
            </q-item>
           <!-- <q-item clickable exact to="material" v-if="store.user.role=='ADMINISTRADOR'||store.user.role==''||store.user.role=='ACREDITACION'" active-class="bg-blue-grey-6 text-white">
              <q-item-section avatar>
                <q-icon name="o_category" />
              </q-item-section>
              <q-item-section>
                Entrega material
              </q-item-section>
            </q-item>
            <q-item clickable exact to="refrigerio" v-if="store.user.role=='ADMINISTRADOR'||store.user.role=='REFRIGERIO'" active-class="bg-blue-grey-6 text-white">
              <q-item-section avatar>
                <q-icon name="o_restaurant_menu" />
              </q-item-section>
              <q-item-section>
                Refrigerio
              </q-item-section>
            </q-item>-->
          </q-list>
          <q-separator />
        </q-list>
      </q-list>
    </q-drawer>

    <q-page-container>
      <router-view />
    </q-page-container>

  </q-layout>
</template>

<script>
import {useCounterStore} from "stores/example-store";
import {date} from "quasar";
export default {
  name: 'MainLayout',
  data () {
    return {
      loading: false,
      url:process.env.API,
      leftDrawerOpen: false,
      search: '',
      store:useCounterStore(),
    }
  },
  created() {
  },
  methods: {

    logout(){
      this.$q.dialog({
        message:'¿Quieres cerrar sesión?',
        title:"Salir",
        ok:{
          push:true
        },
        cancel:{
          push:true,
          color:'negative'
        }
      }).onOk(()=>{
        this.$q.loading.show()
        this.$api.post('logout').then(res=>{
          this.$api.defaults.headers.common['Authorization']=''
          this.store.user={}
          this.store.negocio={}
          this.store.userChat={}
          localStorage.removeItem('tokenJor')
          this.store.isLoggedIn=false
          this.$router.push('/login')
          this.$q.loading.hide()
        })
      })
    },
    toggleLeftDrawer () {
      this.leftDrawerOpen = !this.leftDrawerOpen
    }
  }
}
</script>
