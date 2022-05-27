const express = require('express')
const mysql = require('mysql-await')
const bodyParser = require('body-parser')
const cookieParser = require('cookie-parser')
const validator = require('express-validator')

const app = express()
const port = 8080
const flag = "VolgaCTF{**REDACTED**}"

const pool = mysql.createPool({
  connectionLimit: 10,
  host     : 'localhost',
  user     : 'task',
  password : '**REDACTED**',
  database : 'task'
})

app.set('strict routing', true)
app.set('view engine', 'ejs')
app.use(cookieParser('**REDACTED**'))
app.use(bodyParser.urlencoded({ extended: false }))

app.get('/', async (req, res) => {
  if(!req.signedCookies.login || !req.signedCookies.password) {
    return res.redirect('/login')
  } else {
    const db = await pool.awaitGetConnection()
    try {
      result = await db.awaitQuery("SELECT `id` FROM `users` WHERE `login` = ? AND `password` = ? LIMIT 1", [req.signedCookies.login, req.signedCookies.password])
      if (result.length === 1) {
        if(result[0].id === 1) {
          message = "Welcome admin. Your flag " + flag
        } else {
          message = "You are not admin. Your id " + result[0].id
        }
      } else {
      	message = "Something is wrong"
      }
    } catch(e) {
      message = "Something is wrong"
    } finally {
      db.release()
      res.render('index', { message })
    }
  }
})

app.get('/login', (req, res) => {
	res.render('login', {error: ''})
})

app.post('/login', [ validator.body('login').isString().isLength({ max: 64 }).trim(), validator.body('password').isString().isLength({ max: 64 }) ], async (req, res) => {
  const db = await pool.awaitGetConnection()
  try {
  	validator.validationResult(req).throw()
    result = await db.awaitQuery("SELECT `id` FROM `users` WHERE `login` = ? AND `password` = ? LIMIT 1", [req.body.login, req.body.password])
    if (result.length === 1) {
    	res.cookie('login', req.body.login, { signed: true })
    	res.cookie('password', req.body.password, { signed: true })
    	return res.redirect('/')
    } else {
    	return res.render('login', {error: 'Incorrect login or password'})
    }
  } catch(e) {
    return res.render('login', {error: 'Something is wrong'})
  } finally {
    db.release()
  }
})

app.get('/signup', (req, res) => {
  res.render('signup', {error: ''})
})

app.post('/signup', [ validator.body('login').isString().isLength({ max: 64 }).trim(), validator.body('password').isString().isLength({ max: 64 }) ], async (req, res) => {
  const db = await pool.awaitGetConnection()
  try {
  	validator.validationResult(req).throw()
    result = await db.awaitQuery("SELECT `id` FROM `users` WHERE `login` = ?", [req.body.login])
    if (result.length != 0) 
      return res.render('signup', {error: 'User already exists'})
    result = await db.awaitQuery("INSERT INTO `users` (`login`, `password`) VALUES (?, ?)", [req.body.login, req.body.password])
    res.cookie('login', req.body.login, { signed: true })
    res.cookie('password', req.body.password, { signed: true })
    return res.redirect('/')
  } catch(e) {
    return res.render('signup', {error: 'Something is wrong'})
  } finally {
    db.release()
  }
})

app.get('/logout', (req, res) => {
  res.clearCookie('login')
  res.clearCookie('password')
  return res.redirect('/signup')
})

app.listen(port, () => {
  console.log(`App listening on port ${port}`)
})