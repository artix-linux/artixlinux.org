<?php
$title="Donate";
include('header.html');
?>

<div class="holder">
<center><br><br><br><br><h0>Donate</h0></p>
<br><br><br>
<h1>You can donate to the project through PayPal or Liberapay. All donations go to cover project expenses.</h1>
<br><br>

<form action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_top">
<input type="hidden" name="cmd" value="_s-xclick">
<input type="hidden" name="hosted_button_id" value="U8JA68EYVC2GA">
<input type="image" src="https://www.paypalobjects.com/en_US/i/btn/btn_donateCC_LG.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!">
<img alt="" border="0" src="https://www.paypalobjects.com/en_US/i/scr/pixel.gif" width="1" height="1">
</form>
<br>
<script src="https://liberapay.com/artixlinux/widgets/button.js"></script>
<noscript><a href="https://liberapay.com/artixlinux/donate"><img alt="Donate using Liberapay" src="https://liberapay.com/assets/widgets/donate.svg"></a></noscript>

<br><br>
We thank all our friends who have donated so far.

</div>

<?php
$nexttitle="Artix Linux";
$nextfile="index.php";
include('footer.html');
?>

