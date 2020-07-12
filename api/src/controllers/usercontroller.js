
const reservationmodel = require('../models/Reservations');



module.exports = {
    createReservation: (req, res) => {
        /* input
            firstname
            lastname
            email
            date of birth
            telephone(nullable)
            number of people
            startdate
            enddate
            extra info(null)
        */
        if (req.body.email == '' || req.body.email === undefined) {
            console.log("error");
            res.status(400).send("email required");
        } else if (req.body.birthday == "" || req.body.birthday == undefined || Number.isNaN(Date.parse(req.body.birthday))) {
            res.status(400).send("birthdate invalid");
        } else if (req.body.startdate == "" || req.body.startdate == undefined || Number.isNaN(Date.parse(req.body.startdate))) {
            res.status(400).send("startdate invalid");
        } else if (req.body.enddate == "" || req.body.enddate == undefined || Number.isNaN(Date.parse(req.body.enddate))) {
            res.status(400).send("enddate invalid");
        } else if (Number.isNaN(parseInt(req.body.amount))) {
            res.status(400).send('invalid amount');
        } else if (Number.isNaN(parseInt(req.body.roomnr))) {
            res.status(400).send('invalid room');
        } else if (Number.isNaN(parseInt(req.body.paidAmount))) {
            res.status(400).send('invalid room');
        } else if (req.body.paidCurrency == "" || req.body.paidCurrency == undefined) {
            res.status(400).send('no currency selected');
        } else if (req.body.payment_status != "succeeded") {
            res.status(400).send('payment not succeeded');
        } else {
            try {
                reservation = new reservationmodel(req.body);
                reservation.checkdate().then((result) => {
                    if (result === "Someone already placed a reservation on those dates") {
                        res.status(412).json(result);
                    } else {
                        reservation.createReservation().then((result) => {
                            console.log(result);
                            res.status(200).send(result);
                        })
                    }
                });
            } catch (err) {
                console.log(err);
                res.status(400).json(err);
            }
        }
    },
    getReservations: (req, res) => {
        let reservationarray = [];
        try {
            reservation = new reservationmodel();
            reservation.getReservations(req.userId).then((result) => {
                result.forEach(element => {
                    let obj = element._fields[0].properties;
                    obj.roomnr = element._fields[1].low;
                    console.log(obj)
                    reservationarray.push(element._fields[0].properties)
                });
                res.json(reservationarray);
            })
        } catch (err) {
            console.log(err);
        }
    },
    getRooms: (req, res) => {
        let roomarray = [];
        try {
            reservation = new reservationmodel();
            reservation.getRooms().then((result) => {
                result.forEach(element => {
                    roomarray.push(element._fields[0].properties)
                });
                res.json(roomarray);
            })
        } catch (err) {
            console.log(err);
        }
    },
    updatestate: (req, res) => {

        if (req.body.name == '' || req.body.name === undefined) {
            res.status(400).send("name required");
        } else if (req.body.startdate == "" || req.body.startdate == undefined || Number.isNaN(Date.parse(req.body.startdate))) {
            res.status(400).send("startdate required");
        } else if (req.body.enddate == "" || req.body.enddate == undefined || Number.isNaN(Date.parse(req.body.enddate))) {
            res.status(400).send("enddate required");
        } else if (Number.isNaN(parseInt(req.body.roomnr))) {
            res.status(400).send("roomnr required");
        } else if (req.body.state == '' || req.body.state === undefined) {
            res.status(400).send("state required");
        } else if (req.body.email == '' || req.body.email === undefined) {
            res.status(400).send("email required");
        } else if (req.userId == "" || req.userId == undefined) {
            res.status(400).send("userId not set from authorisation");
        } else {
            try {
                reservation = new reservationmodel(req.body);
                reservation.updateState(req.userId, req.body.state).then(result => {
                    console.log(result);
                    if (result) {
                        res.status(200).send('state update succesvol');
                    } else {
                        res.status(400).send('invalid state');
                    }
                });
            } catch (error) {
                res.status(400).send(error.message);
            }
        }
    },
    checkdate: (req, res) => {
        if (Number.isNaN(parseInt(req.body.roomnr))) {
            res.status(400).send("roomnr required");
        } else if (req.body.startdate == "" || req.body.startdate == undefined || Number.isNaN(Date.parse(req.body.startdate))) {
            res.status(400).send("startdate required");
        } else if (req.body.enddate == "" || req.body.enddate == undefined || Number.isNaN(Date.parse(req.body.enddate))) {
            res.status(400).send("enddate required");
        } else {
            reservation = new reservationmodel(req.body);
            reservation.checkdate().then(result => {
                if (result == "succesfully placed reservation") {
                    res.status(200).json(result);
                } else {

                    res.status(400).json(result);
                }
            })
        }
    }
}