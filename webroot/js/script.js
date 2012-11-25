$(document).ready(function(){
     
    $('#MovieTitle').keyup(function(){
        $('#MovieTitle').autocomplete({
            source: '/movies/my-movies.json',
            minLength: 3,
            select: function(event,ui){
                $(this).val(ui.item.value); 
                $('#MovieId').attr('value', ui.item.id);
            }
        });
    });
    
});