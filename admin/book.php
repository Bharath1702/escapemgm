<?php
if($_SESSION['loggedin']==true){
    echo"<script>alert('Login Success');</script>";
}
else{
    echo"<script>alert('You cant fool me');window.location.href = './index.html';</script>";
}
?>