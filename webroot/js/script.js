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
    
    $('#MovieTitle').css('background', 'none')
        .ajaxStart(function(){
            $(this).css('background', 'url("/img/5-1.gif") no-repeat center right');
            $('input[type=submit]').attr('disabled', 'disabled');
        })
        .ajaxStop(function(){
           $(this).css('background', 'none');
           $('input[type=submit]').removeAttr('disabled');
        })
        
    $('.submit input').click(function(){
        $('#MovieTitle').val('');    
    });
    
    $('.confirm').dialog({
        resizable: false,
        modal: true,
        autoOpen: false
    });
    
    $('.icon-trash').click(function(e){
        var title = $(this).parents('td').next().text();
        $('.confirm').text('Remove ' + title + ' from list?');
        var id = $(this).attr('id');
        var user_movie = id.match(/\d+/);
        var that = this;
        $('.confirm').dialog({
            buttons: {
                'Remove movie': function(){
                    $(this).dialog('close');
                    $.ajax({
                        type: 'POST',
                        url: '/movies/delete',
                        data: {id: user_movie},
                        success: function(){
                            $(that).parents('tr').remove();
                        }
                    });
                },
                'Cancel': function(){
                    $(this).dialog('close');
                }
            }
        });
        $('.confirm').dialog('open');
    });
});