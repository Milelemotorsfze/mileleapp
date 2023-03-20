$(document).ready(function(){
    // Add Class
    $('.edits').change(function(){
     $(this).addClass('editMode');
    });
    // Save data
    $(".edits").change(function(){
     $(this).removeClass("editMode");
     var id = this.id;
     var split_id = id.split("_");
     var field_name = split_id[0];
     var edit_id = split_id[1];
     var value = $(this).val();
     $.ajax({
       url:"<?php echo base_url(); ?>/liveeditqtys",
      type: 'post',
      data: { field:field_name, value:value, id:edit_id },
      success:function(){
        $('.modal').addClass('modalhide');
        $('.modal').removeClass('modalshow');
        // $('.modal').hide();
        console.log('Modal Hidden from close button');
      }
     });
    });
   });
   $(document).ready(function(){
    // Add Class
    $('.editss').change(function(){
     $(this).addClass('editMode');
    });
    // Save data
    $(".editss").change(function(){
     $(this).removeClass("editMode");
     var id = this.id;
     var split_id = id.split("_");
     var field_name = split_id[0];
     var edit_id = split_id[1];
     var value = $(this).val();
     console.log(edit_id);
     $.ajax({
       url:"<?php echo base_url(); ?>/liveeditqtyss",
      type: 'post',
      data: { field:field_name, value:value, id:edit_id },
      success:function(){
      }
     });
    });
   });