const config = {
  apiPrefix: '/api',
  server: {
    port: 4000
  },
  proxy: {
    target: 'http://localhost:5000',
    autoRewrite: true
  },
  session: {
    name: 'SESSION',
    saveUninitialized: false,
    secret: ';GmU1FSlVETF/vzEaBHP',
    rolling: true,
    resave: false
  },
  whitelistPaths: [
    '/api/login', '/api/logout'
  ]
}

module.exports = config;