document.addEventListener( 'wpcf7mailsent', function( e ) {
	if (typeof ga != "undefined"){
        var title = '(not set)';
        if ( e && e.detail && e.detail.inputs ){
            var inputs = e.detail.inputs;
            for(var i=0; i< inputs.length; i++){
                if(inputs[i]["name"] == "machete_wpcf7_title"){
                    title = inputs[i]["value"];
                    break;
                }
            }
        }
        if (title == '(not set)'){
            if ( e && e.target && e.target.id ) {
                title = e.target.id;
            }
        }
        ga('send', 'event', 'Contact Form 7', 'submit', title);
	}
}, false );