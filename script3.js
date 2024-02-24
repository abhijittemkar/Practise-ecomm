// side panel in small screen


const bar = document.getElementById('bar');
const close = document.getElementById('close');
const nav = document.getElementById('navbar');

if(bar){
    bar.addEventListener('click', () => {
    nav.classList.add('active');
    })
}

if(close){
    close.addEventListener('click', () => {
    nav.classList.remove('active');
    })
}
//////////

// image switching

const mainimg=document.getElementById('main-img');
const smallimg=document.getElementsByClassName('small-img');

smallimg[0].onclick =function (){
    mainimg.src = smallimg[0].src;
}
smallimg[1].onclick =function (){
    mainimg.src = smallimg[1].src;
}
smallimg[2].onclick =function (){
    mainimg.src = smallimg[2].src;
}
smallimg[3].onclick =function (){
    mainimg.src = smallimg[3].src;
}

//////////


<script>
  function addToCart(productId) {
    // Make an AJAX request to add the product to the user's cart
    var xhr = new XMLHttpRequest();
    xhr.onreadystatechange = function () {
      if (xhr.readyState == 4 && xhr.status == 200) {
        // Update the color of the cart icon after successful addition
        document.getElementById('cartIcon').style.color = 'red'; // Change the color as needed
      }
    };
    xhr.open("GET", "addToCart.php?productId=" + productId, true);
    xhr.send();
  }
</script>
