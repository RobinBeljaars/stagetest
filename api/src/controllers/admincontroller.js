const user = require("../models/user");
const jwt = require('jsonwebtoken');
const config = require("../config/config.js");
module.exports={
    login:(req,res)=>{
        var userlogin=new user(req.body);
        if(req.body.username==undefined || req.body.username==""){
            res.status(400).send({message:"username required"});
        } else if(req.body.password==undefined || req.body.password==""){
            res.status(400).send({message:"password required"});
        }
        try{
            userlogin.loginadmin().then(result=>{
                
                if(result){
                    res.status(200).send({message:"login succesvol",token:"bearer:"+result});
                }else{
                    res.status(401).send({message:"login not succesvol"});
                }
            });
        }catch(err){
            console.log(err);
            res.status(400).send();
        }
    },
    validateToken:(req,res,next)=>{
        try {
            const rawtoken=req.headers.authorization
        const token=rawtoken.substring(7, rawtoken.length);
        const payload=jwt.verify(token, config.secretkey);
        console.log(payload);
        if(payload.status =="admin"){
            req.userId=payload.status;
        next();
        }else{
            const errorObject = {
                message: 'Invalid authorization in request!',
                code: 401
            }
            return next(errorObject);
        }
        } catch (error) {
            const errorObject = {
                message: 'Invalid authorization in request!',
                code: 401
            }
            return next(errorObject);
        }
        
    }
}