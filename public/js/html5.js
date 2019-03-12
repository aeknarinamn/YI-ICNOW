$(function() {
	$('[data-dataTable]').DataTable();

    $('.alert')
    .delay(5000)
    .fadeOut(function() {
      $(this).remove(); 
    });
});