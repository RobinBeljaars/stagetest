var session = require('../services/neo4jhandler');
const config = require('../config/config');

class reservations {
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
            rooms
        */
    constructor(values) {
        if (values) {
            console.log(values);
            if(values.hasOwnProperty('name')){
                this.name=""+values.name;
            }else{
                
            this.name = values.firstname + " " + values.lastname;
            }
            this.email = values.email;
            this.birthday = values.birthday;
            this.amount = parseInt(values.amount);
            this.telephone = values.telephone;
            this.startdate = values.startdate;
            this.enddate = values.enddate;
            this.extra = values.extra;
            this.rooms = parseInt(values.roomnr);
            this.price = values.paidAmount;
            this.Currency = values.paidCurrency;
            this.payment_status=values.payment_status;
            console.log(this.rooms);
            
        }
    }

    async createReservation() {
        var msg = "";
        try {

            await session.run('merge(n:Reservation {price:{price},currency:{Currency},paymentstatus:{paymentstat},name:{name},email:{email}, tel:{tel},amount:{amount}, birthday: date({birthday}),startdate:date({datestart}),enddate:date({dateend}),comment:{comments}})', { price:this.price,Currency:this.Currency,paymentstat:this.payment_status,name: this.name, email: this.email, tel: "" + this.telephone, amount: this.amount, birthday: "" + this.birthday, datestart: this.startdate, dateend: this.enddate,comments:""+this.extra }).then((res) => {
                session.run('match(n:Reservation {name:{name},email:{email},startdate:date({datestart}),enddate:date({dateend})}) match (room:Room {roomnumber:{roomnr}}) merge (n)-[:res] -> (room) return n,room', { name: this.name, email: this.email, datestart: this.startdate, dateend: this.enddate, roomnr: this.rooms }).then((result) => {
                   
                    return msg
                });
                msg = "succesfully placed reservation";
            });
        } catch (err) {
            console.log(err);
        }
        return msg
    }

    async checkdate() {
        var msg = "";
        try {
            await session.run('match (room:Room {roomnumber: {roomnr}}) <-[:res]-(r:Reservation) where date(r.enddate)>= date({datestart}) and date(r.enddate)<= date({dateend}) or date(r.startdate)>= date({datestart}) and date(r.startdate)<= date({dateend}) or date(r.enddate)<= date({datestart}) and date(r.enddate)>= date({dateend}) or date(r.startdate)<= date({datestart}) and date(r.startdate)>= date({dateend}) return room,r', { roomnr: this.rooms, datestart: ""+this.startdate, dateend:""+ this.enddate }).then((result) => {
                
                if (result.records.length) {
                    msg = "Someone already placed a reservation on those dates"

                } else {
                    msg = "succesfully placed reservation";
                    console.log("no match");
                }
            })
            console.log(msg);
            return msg
        } catch (err) {
            console.log(err);
        }
    }

    async getReservations(adminlock) {
        console.log('test')
        let reservations = []; 
        if(adminlock == config.lock){
        await session.run('MATCH (r:Reservation) -[:res]-> (s:Room) WHERE r.status IS NULL RETURN r,s.roomnumber').then((res) => {
            
            reservations = res.records;
        });}
        return reservations;
    }
    async getRooms() {
        let rooms = [];
        await session.run('MATCH (r:Room) RETURN r').then((res) => {
            rooms = res.records;
        });
        return rooms;
    }
    async updateState(adminlock,state){
        let succesfully=false;
        if(adminlock==config.lock){
            if(state =="accepted"||state=="denied"){
            await session.run('MATCH (r:Reservation {name:{name},email:{email},startdate:date({datestart}),enddate:date({dateend})}) -[:res]-> (room:Room {roomnumber: {roomnr}}) SET r.status={state} return r',{name: ""+this.name, email: ""+this.email, datestart: ""+this.startdate, dateend: ""+this.enddate, roomnr: this.rooms,state:""+state}).then((result)=>{
                console.log(result.records[0]._fields[0].properties);
                if(result.records[0]._fields[0].properties.status=="accepted" ||result.records[0]._fields[0].properties.status=="denied"){
                    succesfully=true;
                }
            })
        }
    }
        return succesfully;
    }
}
module.exports = reservations