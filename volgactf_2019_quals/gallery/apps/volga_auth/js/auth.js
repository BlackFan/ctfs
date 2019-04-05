const unless = require('express-unless');

const auth = function () {
  var authm = function (req, res, next) {
    if (!req.session.name) {
    	res.status(403).send();
    } else {
		next();
    }
  }
  authm.unless = unless;
  return authm;
};

module.exports = auth;