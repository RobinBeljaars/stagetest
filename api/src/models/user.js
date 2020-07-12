var session = require('../services/neo4jhandler');
const jwt = require('jsonwebtoken');
const config = require('../config/config.js')
class user {
    constructor(values) {
        this.username = values.username;
        this.password = values.password;
    }

    async loginadmin() {
        let validlogin = "";
        await session.run('MATCH (u:User) WHERE u.status="admin" AND u.username={username} AND u.password={password} RETURN u', { username: "" + this.username, password: "" + this.password })
            .then((result) => {
                if (result.records.length) {
                    let token=result.records[0]._fields[0].properties;
                    validlogin = jwt.sign(token,config.secretkey);
                }
            });
        return validlogin;
    }
}
module.exports = user