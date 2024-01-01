<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FancyBox Example</title>
    <!-- Include jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <!-- Include FancyBox -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fancybox/3.5.7/jquery.fancybox.min.css" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/fancybox/3.5.7/jquery.fancybox.min.js"></script>
</head>
<body>

<!-- Hidden container with dynamic content -->
<div id="dynamic-content">
<?php foreach ($category as $cat) { ?>
    <a class="fancybox" data-fancybox="gallery" href="{{ asset('/Category/'.$cat->image) }}">
        <img src="{{ asset('/Category/'.$cat->image) }}" width="150px" height="150px" alt="Category Image">
    </a>
<?php } ?>
</div>


<script>
    $(document).ready(function() {
        // Initialize FancyBox
        $("[data-fancybox]").fancybox({
            // Animation effect
            animationEffect: "fade", // You can change to "zoom", "fade", or other effects

            // Transition duration
            transitionDuration: 500, // In milliseconds

            // Enable or disable infinite gallery navigation
            loop: true,

            // Enable keyboard navigation
            keyboard: true,

            // Enable or disable zooming
            zoom: true,
            infobar: true,
            buttons: ["slideShow", "fullScreen", "thumbs", "close"],

            // Set the idle time after which UI elements will be hidden
            idleTime: 3000, // In milliseconds

            // Callback functions
            afterLoad: function(instance, current) {
                console.log("FancyBox opened!");
            },
            beforeClose: function(instance, current) {
                console.log("FancyBox is about to close!");
            }
            // Add more settings based on your requirements
        });
    });
</script>


</body>
</html>
