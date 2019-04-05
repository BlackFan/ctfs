const express    = require('express');
const session = require('express-session');
const store = require('session-file-store')(session);
const proxy = require('http-proxy-middleware');
const parser = require('body-parser');
const fs = require('fs');
const app = express();

config = require('./config');
auth = require('./auth')();
config.session.store = new store();

app.use(parser.urlencoded({ extended: false }));
app.use(`${config.apiPrefix}/*`, session(config.session));
app.use(`${config.apiPrefix}/*`, auth.unless({path: config.whitelistPaths}));

app.post(`${config.apiPrefix}/login`, function (req, res) {
	/* TODO: Implement login*/
	res.redirect('/login');
});

app.get(`${config.apiPrefix}/logout`, function (req, res) {
	/* TODO: Implement logout */
	res.redirect('/login');
});

app.get(`${config.apiPrefix}/flag`, function (req, res) {
	if(req.session.name === 'admin')
		res.end(fs.readFileSync('../../flag', 'utf8'));
	else
		res.status(403).send();
});

app.use(proxy(config.proxy));
app.listen(config.server.port);