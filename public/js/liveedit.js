$(document).ready(function(){
    // Add Class
    $('.edit').click(function(){
     $(this).addClass('editMode');
    });
    // Save data
    $(".edit").focusout(function(){
     $(this).removeClass("editMode");
     var id = this.id;
     var split_id = id.split("_");
     var field_name = split_id[0];
     var edit_id = split_id[1];
     var value = $(this).text();
     $.ajax({
       url:"<?php echo base_url(); ?>/realtimeedit",
      type: 'post',
      data: { field:field_name, value:value, id:edit_id },
      success:function(response){
       console.log(response);
        if(response == 1){
           console.log('Save successfully'); 
        }else{
           console.log("Not saved.");
        }
      }
     });
    });
   });
   $(document).ready(function(){
    // Add Class
    $('.edits').click(function(){
     $(this).addClass('editsMode');
	 console.log(id);
    });
    // Save data
    $(".editforecast").focusout(function(){
     $(this).removeClass("editsMode");
     var id = this.id;
     var split_id = id.split("_");
     var field_name = split_id[0];
     var edit_id = split_id[1];
     var value = $(this).text();
	 console.log(id);
     $.ajax({
       url:"<?php echo base_url(); ?>/liveeditqtyssss",
      type: 'post',
      data: { field:field_name, value:value, id:edit_id },
      success:function(response){
       console.log(response);
        if(response == 1){
           console.log('Save successfully'); 
        }else{
           console.log("Not saved.");
        }
      }
     });
    });
   });