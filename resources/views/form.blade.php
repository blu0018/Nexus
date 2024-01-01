<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Date Picker Example</title>

  <!-- Include jQuery -->
  <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>

  <!-- Include jQuery UI -->
  <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
  <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
</head>
<body>

  <label for="startdate">Select a date:</label>
  <input type="text" id="startdate" name="startdate">
  
  <label for="enddate">Select a date:</label>
  <input type="text" id="endate" name="endate">

  <label for="ticket_startdate">Select a date:</label>
  <input type="text" id="ticket_startdate" name="ticket_startdate">
  
  <label for="ticket_endate">Select a date:</label>
  <input type="text" id="ticket_endate" name="ticket_endate">

  <script>
    $(function() {
      var currentDate = new Date();

      $("#startdate").datepicker({
        dateFormat: 'yy-mm-dd',
        changeMonth: true,
        changeYear: true,
        showButtonPanel: true,
        minDate: currentDate,
        beforeShowDay: function(date) {
          var day = date.getDay();
          return [(day != 0 && day != 6), ''];
        }
      });

      
      // Set minDate for ticket_startdate based on selected date from startdate
      $(document).on('change', '#startdate', function(){
        var startDate = $(this).val();
        $("#endate").datepicker({
        dateFormat: 'yy-mm-dd',
        changeMonth: true,
        changeYear: true,
        showButtonPanel: true,
        minDate: startDate,
        beforeShowDay: function(date) {
          var day = date.getDay();
          return [(day != 0 && day != 6), ''];
        }
      });

      $("#ticket_startdate").datepicker({
        dateFormat: 'yy-mm-dd',
        changeMonth: true,
        changeYear: true,
        showButtonPanel: true,
        minDate: startDate,
        maxDate: startDate,
        beforeShowDay: function(date) {
          var day = date.getDay();
          return [(day != 0 && day != 6), ''];
        }
      });
    });
     
      $(document).on('change','#endate',function(){
        var endDate = $(this).val();
        var startDate = $('#startdate').val();

        $("#ticket_endate").datepicker({
        dateFormat: 'yy-mm-dd',
        changeMonth: true,
        changeYear: true,
        showButtonPanel: true,
        minDate: startDate,
        maxDate: endDate,
        beforeShowDay: function(date) {
          var day = date.getDay();
          return [(day != 0 && day != 6), ''];
        }
      });
      
   
      });
    });
  </script>

</body>
</html>






<!-- <!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quantity and Price Calculator</title>
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <style>
       
        body {
            font-family: Arial, sans-serif;
            text-align: center;
            margin: 20px;
        }

        input[type="number"] {
            width: 50px;
            text-align: center;
        }

        button {
            cursor: pointer;
            padding: 5px 10px;
            font-size: 16px;
        }
    </style>
</head>
<body> -->
    <!-- <label for="quantity">Quantity:</label>
    <div>
        <button id="decreaseButton">-</button>
        <input type="number" id="quantity" name="quantity" value="1" min="1">
        <button id="increaseButton">+</button>
    </div>

    <label for="price">Price:</label>
    <input type="text" id="price" name="price" value="10.00" readonly>

    <label for="total">Total:</label>
    <input type="text" id="total" name="total" value="10.00" readonly>

    <script>
        $(document).ready(function () {
            updateTotal(); 

           $(document).on('click','#increaseButton',function(){
            var quantity = $('#quantity');
            qty = parseInt(quantity.val()) + 1
            quantity.val(qty);
            updateTotal();
           });

           $(document).on('click','#decreaseButton',function(){
            var quantity = $('#quantity');
            var current = parseInt(quantity.val());
            quantity.val( current > 1 ?  current - 1 : 1);
            updateTotal();
           });
        });

        function updateTotal() {
            var quantity = parseInt($('#quantity').val());
            var price = parseFloat($('#price').val());
            var total = quantity * price;
            $('#total').val(total.toFixed(2));
        }
    </script>

</body>
</html> -->



<!-- <!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sample Form</title>
</head>
<body>

    <form action="/submit_form" method="post">
        <label for="subtotal">Sub total:</label>
        <input type="text" id="subtotal" name="subtotal" class="numeric" required>

        <br>

        <label for="shipping_price">Shipping Price:</label>
        <input type="text" id="shipping_price" name="shipping_price" class="numeric" required>

        <br>

        <label for="total">Total:</label>
        <input type="text" id="total" name="total" class="numeric" value="">

        <br>

        <input type="submit" value="Submit">
    </form>

    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
  
    <script>
        // Function to allow only numbers in input fields
        $(document).ready(function () {
            $('.numeric').on('input', function () {
                // Allow only digits and '.' in this case
                this.value = this.value.replace(/[^0-9.]/g, '');
                calculateTotal();
            });
        });

        // Function to calculate the total
        function calculateTotal() {
            var subtotal = parseFloat($('#subtotal').val()) || 0;
            var shippingPrice = parseFloat($('#shipping_price').val()) || 0;
            var total = subtotal + shippingPrice;
            
            // Display the calculated total in the total input field
            $('#total').val(total.toFixed(2));
        }
    </script>
</body>
</html> -->
