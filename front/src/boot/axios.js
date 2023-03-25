import { boot } from 'quasar/wrappers'
import axios from 'axios'
import {useCounterStore} from "stores/example-store";

// Be careful when using SSR for cross-request state pollution
// due to creating a Singleton instance here;
// If any client changes this (global) instance, it might be a
// good idea to move this instance creation inside of the
// "export default () => {}" function below (which runs individually
// for each client)
const api = axios.create({ baseURL: process.env.API })

export default boot(({ app ,router}) => {
  // for use inside Vue files (Options API) through this.$axios and this.$api

  app.config.globalProperties.$axios = axios
  // app.config.globalProperties.$socket = socketInstance
  // ^ ^ ^ this will allow you to use this.$axios (for Vue Options API form)
  //       so you won't necessarily have to import axios in each vue file
  // app.config.globalProperties.$Oauth = Oauth
  app.config.globalProperties.$api = api
  const token = localStorage.getItem('tokenJor')

  if (token) {
    app.config.globalProperties.$api.defaults.headers.common['Authorization'] = 'Bearer '+token
    app.config.globalProperties.$api.post('me').then(res=>{
      useCounterStore().user=res.data
      useCounterStore().isLoggedIn=true
    }).catch(err=>{
      app.config.globalProperties.$api.defaults.headers.common['Authorization']=''
      useCounterStore().user={}
      localStorage.removeItem('tokenJor')
      useCounterStore().isLoggedIn=false
      router.push('/login')
    })
  }else{
    router.push('/login')
    useCounterStore().user={}
    localStorage.removeItem('tokenJor')
    useCounterStore().isLoggedIn=false

  }
  // ^ ^ ^ this will allow you to use this.$api (for Vue Options API form)
  //       so you can easily perform requests against your app's API
})


export { api }
