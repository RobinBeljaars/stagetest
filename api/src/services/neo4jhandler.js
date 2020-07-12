const config = require('../config/config');

var neo4j = require('neo4j-driver').v1;

let driver;
driver = neo4j.driver(config.boltserver, neo4j.auth.basic(config.boltuser, config.botlpassword))

var session = driver.session();
module.exports = session;
