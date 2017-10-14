<?php
$title="FAQ";
include('header.html');
?>

<div class="holder">
<center><br><br><br><br><h0>Frequently Asked Questions</h0></p>
<br></center>
<br><br>

Q. Why?
<br>
A. <a href="http://systemd-free.org/why.php">Because.</a>
<br>
<br>

Q. No, I mean why Artix?
<br>
A. Because running after Arch was a second-grade approach. By forking Arch Linux and cutting out all the systemd stuff we can be sure that upstream changes won't affect the project negatively and that systemd won't creep into the binary packages.
<br>
<br>

Q. Who's behind Artix?
<br>
A. <a href="https://github.com/orgs/artix-linux/people">Them</a> and more.
<br>
<br>

Q. What does Artix mean?
<br>
A. It's complicated.
<br>
<br>

Q. <a href="http://systemd-free.org/news.php#170727">Jedi Knights?</a> Are you serious?
<br>
A. Any more and you'll die.
<br>
<br>

Q. Why are there still systemd components (i.e. logind and udev) in Artix?
<br>
A. They're elogind and eudev, which is different.
<br>
<br>

Q. What about pulseaudio?
<br>
A. Technically, pulseaudio is not part of systemd. Use alsa.
<br>
<br>

Q. Why not consolekit2?
<br>
A. It's not (yet) on par with elogind in features and requires specifically patched packages. Maybe later.
<br>
<br>

Q. How can I help?
<br>
A. <a href="help.php">Thought you'd never ask!</a>
</div>
<br>
<br>

<?php
$nexttitle="Contact";
$nextfile="contact.php";
include('footer.html');
?>
