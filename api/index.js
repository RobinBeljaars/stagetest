const express = require('express');
const app = express();
var bodyParser = require('body-parser');

app.use(function (req, res, next) { //allow cross origin requests
    res.setHeader("Access-Control-Allow-Origin", "*");
    res.header("Access-Control-Allow-Methods", "POST, PUT, OPTIONS, DELETE, GET");
    res.header("Access-Control-Max-Age", "3600");
    res.header("Access-Control-Allow-Headers", "Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
    next();
});

//Routes
const adminroutes= require('./src/routes/adminroutes.js');
const userroutes= require('./src/routes/userroutes.js');
app.use(bodyParser.urlencoded({'extended': 'true'}));
app.use(bodyParser.json());
app.use(bodyParser.json({type: 'application/vnd.api+json'}));
app.use('/api/', userroutes);
app.use('/api/admin/', adminroutes);


app.all("*", function (req, res, next) {
    const errorObject = {
        message: "Endpoint does not exist!",
        code: 404,
        date: new Date()
    };
    next(errorObject);
});

app.use((error, req, res, next) => {
    res.status(error.code).json(error);
});

app.listen(process.env.PORT || 3000);