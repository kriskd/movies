$(document).ready(function(){
    
    $('#MovieTitle').keyup(function(){
        
        var search = $(this).val(); 

        $('#MovieTitle').autocomplete({
            source: '/movies/titles/' + search,
            minLength: 3,
            select: function(event,ui){
                $(this).val(ui.item.value); 
                $('#MovieId').attr('value', ui.item.id);
            }
        });
    });
});