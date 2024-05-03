var index = 0;
        var images = document.querySelectorAll('.carousel-img');
        setInterval(function() {
            images[index].style.display = 'none';
            index = (index + 1) % images.length;
            images[index].style.display = 'block';
        }, 3000); // Change image every 3 seconds