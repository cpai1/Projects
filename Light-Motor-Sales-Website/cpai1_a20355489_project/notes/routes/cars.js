var express = require('express');
var router = express.Router();
var _ = require('underscore');
var fs=require('fs');



var cars = [];
var dataPath='data2.json';
  
try {
	var stats = fs.statSync(dataPath);
	var dataString = fs.readFileSync(dataPath);
	cars = JSON.parse(dataString);
} catch (e) {
	console.log('Data File Does Not Exist... Creating Empty File...');
	fs.writeFileSync(dataPath, JSON.stringify([]));
}
function lookupContact(car_id) {
  return _.find(cars, function(c) {
    return c.id == parseInt(car_id);
  });
}

function findMaxId() {
  return _.max(cars, function(car) {
    return car.id;
  });
}

router.get('/', function(req, res, next) {
  res.render('list2', {cars: cars});
});


router.get('/api', function(req, res, next) {
  //res.render('list', {contacts: contacts});
  res.json(cars);

});



router.post('/', function(req, res, next) {
	console.log(findMaxId());
	var new_car_id = (findMaxId()).id + 1;
	var new_car = {
		id: new_car_id,
		name: req.body.name,
		value: req.body.value
		
	};
	cars.push(new_car);
	console.log(cars);
	fs.writeFileSync(dataPath, JSON.stringify(cars));

	
	res.redirect('/cars/');
});

router.get('/add2', function(req, res, next) {
	res.render('add2', {car:{}});
});

router.route('/:car_id')
	.all(function(req, res, next){
		car_id = req.params.car_id;
		car = lookupContact(car_id);
		next();
	})
	.get(function(req,res,next){
		res.render('edit2', {car: car});
	})
	.post(function(req,res,next){
		res.send('Post for car ' + car_id);
	})
	.put(function(req,res,next){
		car.name = req.body.name;
		car.value = req.body.value;
		
		fs.writeFileSync(dataPath, JSON.stringify(cars));
		
		res.redirect('/cars/');
	})
	.delete(function(req,res,next){
		for(var i=0;i<cars.length;i++){
			if(cars[i].id===parseInt(car_id)){
				cars.splice(i,1);
			}
		}
		fs.writeFileSync(dataPath, JSON.stringify(cars));
		res.send('Delete for cars ' + car_id);
	});

module.exports = router;
