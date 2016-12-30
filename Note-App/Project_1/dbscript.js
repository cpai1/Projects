$(document).ready(function(){
	
	console.log('js loaded....');
	var db;

	var openRequest = indexedDB.open("wordlist",1);
	openRequest.onupgradeneeded = function(e) {
		console.log("Upgrading DB...");
		var thisDB = e.target.result;
		if(!thisDB.objectStoreNames.contains("wordliststore")) {
			thisDB.createObjectStore("wordliststore", { autoIncrement : true });
		}
	}
	openRequest.onsuccess = function(e) {
		console.log("Open Success!");
		db = e.target.result;
		$('#button1').click(function(){
        var item=$("#title").val();
        var item1=$("#descript").val();
        var item2=$("#dat").val();
        
        if(!item.trim() && !item1.trim() && !item2.trim){
        	return false;
        }else{
        	addword(item,item1,item2);
        }
        });
        renderList();
      }
        openRequest.onerror = function(e) {
		console.log("Open Error!");
		console.dir(e);
	   }

	function addword(t,s,d){
		console.log("adding"+ t);
		var transaction=db.transaction(["wordliststore"],"readwrite");
		var store=transaction.objectStore("wordliststore");
		var request=store.add({text:t,text2:s,date:d});
		request.onerror=function(e){
			console.log("Error",e.target.error.name);

		}
		
	
	 request.onsuccess=function(e){
		console.log("adding"+ t);
		$("#title").val("");
		$("#descript").val("");
		$("#dat").val("");		
		renderList();      
	}
}

function renderList(){
		//$('#list-wrapper').empty();
		$("#listOfNotes").empty();
		//Count Objects
		var transaction = db.transaction(['wordliststore'], 'readonly');
		var store = transaction.objectStore('wordliststore');
		var countRequest = store.count();
		countRequest.onsuccess = function(){ console.log(countRequest.result) };
		// Get all Objects
		var objectStore = db.transaction("wordliststore").objectStore("wordliststore");
		objectStore.openCursor().onsuccess = function(event) {
		var cursor = event.target.result;
		
		if (cursor) {
			var $link = $('<a href="#" data-key="' + cursor.key + '">' + cursor.value.text + '    ' + cursor.value.text2 +'' + cursor.value.date+'</a>');
			$link.click(function(){
				//alert('Clicked ' + $(this).attr('data-key'));
				loadTextByKey(parseInt($(this).attr('data-key')));
			});
            var listAllListHTML = "";
            listAllListHTML +='<div class="f1_container" id="list-wrapper'+ cursor.key +'">'
			+'<div class="f1_card"  id="note'+ cursor.key +'" >'
	  			+'<div class="front face" >'
                   +'<div class="trans">' 	    			
                      + cursor.value.text +'<br>'+ cursor.value.date+''
                       +'</div>'
	  			    +'</div>'
	  			    +'<div class="back face center">'
                    +'<input type="image" id="delbut'+ cursor.key +'" src="images/delete.gif" width="31" height="30">'
	  		        + cursor.value.text2 +''
	  				+'</div>'
	  			+'</div>'
			+'</div>';

       		$("#listOfNotes").append(listAllListHTML);
       		console.log("key before appending-->"+cursor.key);
       		var idOfTheDiv = cursor.key;
       		$('#delbut'+ idOfTheDiv).on('click',function(){
        		
        		deleteWord(idOfTheDiv);        		
        	});        	
        	
			cursor.continue();
			}
			else {
			    //no more entries
			}
		};
	}

function deleteWord(key) {
		var transaction = db.transaction(['wordliststore'], 'readwrite');
		var store = transaction.objectStore('wordliststore');
		var request = store.delete(key);
		request.onsuccess = function(evt){
			renderList();
		};
	}
});