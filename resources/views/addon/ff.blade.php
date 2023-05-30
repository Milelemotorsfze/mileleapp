<!--Upload Form-->
<meta name="csrf-token" content="{{ csrf_token() }}">
<form>
@csrf
  <table>
    <tr>
      <td colspan="2">File Upload</td>
    </tr>
    <tr>
      <th>Select File </th>
      <td><input id="csv" name="csv" type="file" /></td>
    </tr>
    <tr>
      <td colspan="2">
        <input type="submit" value="submit"/> 
      </td>
    </tr>
  </table>
</form>
<script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
<script type="text/javascript">
   //form Submit
   $(document).ready(function ()
        {
   $("form").submit(function(evt){   

      evt.preventDefault();
      var formData = new FormData($(this)[0]);

      $.ajax({
          url: "{{url('supplierAddonExcelValidation')}}",
          type: 'POST',
          data: 
                    {
                        formData: formData,
                    _token: '{{csrf_token()}}' 
                    },
          async: false,
          cache: false,
          contentType: false,
          enctype: 'multipart/form-data',
          processData: false,
          success: function (response) {
         
             alert(response);
          }
       });

       return false;

    });
});
</script>