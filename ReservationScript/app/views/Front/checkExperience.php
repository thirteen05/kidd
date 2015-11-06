<?php 
    if($tpl['exp'] == "ok")
    {
        include("loadCheckout.php");
    }
    else 
    {
        // include("needMoreExperience.php");
        echo "<p>Based on your experience level, you may need some boat training to continue your reservation.</p>";
        echo "<p>Please contact: 727-442-8601 for more info;</p>";
    }
?>