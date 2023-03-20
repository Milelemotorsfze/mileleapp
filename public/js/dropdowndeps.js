$(document).ready(function(){
    $('#modelss').change(function(){
                var model = $('#modelss').val();
				console.log(model);
                 var action = 'get_sfx';
                 $.ajax({
                     url:"http://localhost/smt/modeltosfxvar",
                     method:"POST",
                     data:{model:model, action:action},
                     dataType:"JSON",
                     success:function(data)
                     {
                         
                         var html = '';
                         for(var count = 0; count < data.length; count++)
                        {
                             html += '<option value="'+data[count].sfx+'">'+data[count].sfx+'</option>';
                         }
                        $('#sfxs').html(html);
                     }
                 });
       });
             $('#sfxs').click(function(){
             var model = $('#modelss').val();
             var sfx = $('#sfxs').val();
             var action = 'get_vaient';
             if(model != '')
             {
                 $.ajax({
                     url:"http://localhost/smt/modeltosfxvar",
                     method:"POST",
                     data:{model:model, sfx:sfx, action:action},
                     dataType:"JSON",
                     success:function(data)
                     {
                        var html = '';
                        for(var count = 0; count < data.length; count++)
                         {
                             html += '<option value="'+data[count].name+'">'+data[count].name+'</option>';
                         }
                         $('#varients').html(html);
                     }
                });
            }
         });
    });