function showModal(heading, text) {
  var modal = 
    $('<div class="modal hide fade">' +    
        '<div class="modal-header">' +
          '<a class="close" data-dismiss="modal" >&times;</a>' +
          '<h3>' + heading +'</h3>' +
        '</div>' +

        '<div class="modal-body">' +
          '<p>' + text + '</p>' +
        '</div>' +

        '<div class="modal-footer">' +
          '<a href="#" id="okButton" class="btn btn-danger">OK</a>' +
        '</div>' +
      '</div>');

	modal.find('#okButton').click(function(event) {
		modal.modal('hide');
	});
  
	modal.modal('show');
	return modal;
};