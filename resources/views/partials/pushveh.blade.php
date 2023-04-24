<script type="text/javascript">
  $(document).ready(function() {
    var userId = {{ auth()->user()->id }};
    function updateVehicleCount() {
      $.ajax({
        url: '/get-vehicle-count/' + userId,
        type: 'GET',
        success: function(data) {
          $('.cart-icon-number').text(data);
        }
      });
    }
    updateVehicleCount();
    setInterval(updateVehicleCount, 500);
  });
</script>
