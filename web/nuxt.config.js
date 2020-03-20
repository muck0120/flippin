require('dotenv').config()

export default {
  mode: 'universal',
  srcDir: 'src/',
  router: {
    middleware: ['user-agent']
  },
  /*
  ** Headers of the page
  */
  head: {
    title: 'Flippin',
    titleTemplate: '%s | Flippin',
    meta: [
      { charset: 'utf-8' },
      { name: 'viewport', content: 'width=device-width, user-scalable=no' },
      { hid: 'description', name: 'description', content: process.env.npm_package_description || '' }
    ],
    link: [
      { rel: 'icon', type: 'image/x-icon', href: '/favicon.ico' },
      { rel: 'stylesheet', href: 'https://fonts.googleapis.com/css?family=M+PLUS+Rounded+1c:500,800&display=swap' },
      { rel: 'stylesheet', href: 'https://fonts.googleapis.com/css?family=Baloo+Bhai&display=swap' }
    ]
  },
  /*
  ** Customize the progress-bar color
  */
  loading: '@/components/Loading.vue',
  /*
  ** Global CSS
  */
  css: [
    '@/assets/scss/reset.scss',
    '@/assets/scss/base.scss'
  ],
  /*
  ** Plugins to load before mounting the App
  */
  plugins: [
    '@/plugins/axios',
    '@/plugins/vee-validate',
    '@/plugins/exam-gateway',
    { src: '@/plugins/infinite-loading', mode: 'client' }
  ],
  /*
  ** Nuxt.js dev-modules
  */
  buildModules: [
    // Doc: https://github.com/nuxt-community/eslint-module
    '@nuxtjs/eslint-module',
    '@nuxtjs/stylelint-module'
  ],
  /*
  ** Nuxt.js modules
  */
  modules: [
    // Doc: https://axios.nuxtjs.org/usage
    '@nuxtjs/axios',
    // Doc: https://github.com/nuxt-community/dotenv-module
    '@nuxtjs/dotenv',
    // Doc: https://www.npmjs.com/package/@nuxtjs/style-resources
    '@nuxtjs/style-resources',
    // Doc: https://www.npmjs.com/package/nuxt-fontawesome
    'nuxt-fontawesome'
  ],
  /*
  ** Axios module configuration
  ** See https://axios.nuxtjs.org/options
  */
  axios: {
    baseURL: process.browser ? process.env.BASE_URL_API : `${process.env.BASE_URL}/api`
  },
  /*
  ** Dotenv
  */
  dotenv: {
    path: process.cwd()
  },
  /*
  ** style-resources
  */
  styleResources: {
    scss: [
      '@/assets/scss/vars.scss'
    ]
  },
  /*
  ** FontAwesome
  */
  fontawesome: {
    component: 'fa'
  },
  /*
  ** Build configuration
  */
  build: {
    /*
    ** You can extend webpack config here
    */
    extend (config, ctx) {
      // Run ESLint on save
      if (ctx.isDev && ctx.isClient) {
        if (config.module) {
          config.module.rules.push({
            enforce: 'pre',
            test: /\.(js|vue)$/,
            loader: 'eslint-loader',
            exclude: /(node_modules)/
          })
        }
      }
    },
    transpile: [
      'vee-validate/dist/rules'
    ]
  }
}
