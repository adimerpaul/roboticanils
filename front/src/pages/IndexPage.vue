<template>
  <q-page class="">
    <div class="row">
      <div class="col-12 col-sm-6 q-pa-md">
        <q-card class="bg-green">
          <q-card-section>
            <div class="text-h6 text-white">Libres <q-btn flat dense @click="actualizar" icon="refresh" /> </div>
          </q-card-section>
          <q-card-section>
            <div class="text-h2 text-white">{{libres}}</div>
          </q-card-section>
        </q-card>
      </div>
      <div class="col-12 col-sm-6 q-pa-md">
        <q-card class="bg-red">
          <q-card-section>
            <div class="text-h6 text-white">Ocupados <q-btn flat dense @click="actualizar" icon="refresh" /> </div>
          </q-card-section>
          <q-card-section>
            <div class="text-h2 text-white">{{ocupados}}</div>
          </q-card-section>
        </q-card>
      </div>
    </div>
  </q-page>
</template>

<script>
export default {
  data () {
    return {
      libres: 0,
      ocupados: 0,
    }
  },
  created() {
    this.actualizar();
  },
  methods: {
    actualizar() {
      this.$q.loading.show()
      this.$api.get('cupo')
        .then(response => {
          this.libres = 0
          this.ocupados = 0
          response.data.forEach(cupo => {
            if (cupo.nombres != null) {
              this.ocupados++
            } else {
              this.libres++
            }
          })
        })
        .finally(() => {
          this.$q.loading.hide()
        })
    }
  }
}
</script>
