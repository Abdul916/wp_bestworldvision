<!-- start Simple Custom CSS and JS -->
<script type="text/javascript">
document.addEventListener("DOMContentLoaded", function() {
    // Select all images on the page
    const images = document.querySelectorAll('img');

    // Loop through each image and remove the alt and title attributes
    images.forEach(function(img) {
        img.removeAttribute('alt');
        img.removeAttribute('title');
    });
});
</script>
<!-- end Simple Custom CSS and JS -->
