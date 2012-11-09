$(document).ready(function(){
    
    $('#MovieSearch').keyup(function(){
        
        var search = $(this).val(); 

        $('#MovieSearch').autocomplete({
            source: '/movies/titles/' + search,
            minLength: 3,
            select: function(event,ui){
                $(this).val(ui.item.value);
                $(this).attr('value', ui.item.id);
            }
        });
    });
});