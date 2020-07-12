const express = require('express');
const router = express.Router();
const userController = require('../controllers/usercontroller.js');
const adminController = require('../controllers/admincontroller.js');

router.post("/",userController.createReservation);
router.get("/",adminController.validateToken,userController.getReservations);
router.post("/state",adminController.validateToken,userController.updatestate);
router.post("/checkdate",adminController.validateToken,userController.checkdate);
router.get("/rooms/", userController.getRooms);

module.exports=router