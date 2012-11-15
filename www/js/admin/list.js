function deleteProject(id) {
	if(window.confirm('Confirmer la suppression ?'))
	{
		window.location.href = '/admin.project/delete/id/'+id;
	}
}