var express = require('express');
var router = express.Router();
var _ = require('underscore');
var fs=require('fs');



var notes = [];
var dataPath='data.json';
  
try {
	var stats = fs.statSync(dataPath);
	var dataString = fs.readFileSync(dataPath);
	notes = JSON.parse(dataString);
} catch (e) {
	console.log('Data File Does Not Exist... Creating Empty File...');
	fs.writeFileSync(dataPath, JSON.stringify([]));
}
function lookupContact(note_id) {
  return _.find(notes, function(c) {
    return c.id == parseInt(note_id);
  });
}

function findMaxId() {
  return _.max(notes, function(note) {
    return note.id;
  });
}

router.get('/', function(req, res, next) {
  res.render('list', {notes: notes});
});


router.get('/api', function(req, res, next) {
  //res.render('list', {contacts: contacts});
  res.json(notes);

});



router.post('/', function(req, res, next) {
	console.log(findMaxId());
	var new_note_id = (findMaxId()).id + 1;
	var new_note = {
		id: new_note_id,
		title: req.body.title,
		desc: req.body.desc
		
	};
	notes.push(new_note);
	console.log(notes);
	fs.writeFileSync(dataPath, JSON.stringify(notes));

	
	res.redirect('/notes/');
});

router.get('/add', function(req, res, next) {
	res.render('add', {note:{}});
});

router.route('/:note_id')
	.all(function(req, res, next){
		note_id = req.params.note_id;
		note = lookupContact(note_id);
		next();
	})
	.get(function(req,res,next){
		res.render('edit', {note: note});
	})
	.post(function(req,res,next){
		res.send('Post for note ' + note_id);
	})
	.put(function(req,res,next){
		note.title = req.body.title;
		note.desc = req.body.desc;
		
		fs.writeFileSync(dataPath, JSON.stringify(notes));
		
		res.redirect('/notes/');
	})
	.delete(function(req,res,next){
		for(var i=0;i<notes.length;i++){
			if(notes[i].id===parseInt(note_id)){
				notes.splice(i,1);
			}
		}
		fs.writeFileSync(dataPath, JSON.stringify(notes));
		res.send('Delete for notes ' + note_id);
	});

module.exports = router;
