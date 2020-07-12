const express = require('express');
const router = express.Router();
const adminController = require('../controllers/admincontroller.js');
router.post('/login',adminController.login);

module.exports=router