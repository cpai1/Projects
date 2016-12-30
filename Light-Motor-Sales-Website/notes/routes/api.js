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


router.get('/notes', function(req, res, next) {

  res.json(notes);
  });


router.route('/notes/:note_id')
 .all(function(req, res, next){
  note_id = req.params.note_id;
  note = lookupContact(note_id);
  next();
 })
.get(function(req,res,next){
  res.json('read', {note: note});
 })

 module.exports = router;
