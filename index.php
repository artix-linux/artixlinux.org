<?php
$title="Artix";
include('header.html');
?>

<div class="holder">
<center><br><br><br><br><h0>Artix</h0></p>
<br>
<h0>Your Linux, your init.</h0>
</center>
<br><br>


<h1 id="170912"><span itemprop="name">2017-09-12 -- New primary mirror</h1>
We are moving our package repos away from Sourceforge hosting, main reason being the restriction of colon characters (':') in filenames, which breaks the package database with epoch-versioned packages.
<br>
Edit your /etc/pacman.d/mirrorlist with your favourite editor and make sure the following mirrors are on top:
<code>Server = http://mirror1.artixlinux.org/repos/$repo/os/$arch
Server = http://artix.wheaton.edu/repos/$repo/os/$arch/
Server = http://mirror.strits.dk/artix-linux/repos/$repo/os/$arch
Server = https://mirrors.dotsrc.org/artix-linux/repos/$repo/os/$arch
Server = https://www.uex.dk/repos/artix/repos/$repo/os/$arch
</code>
Then run:
<code>pacman -Syu
</code>
to get the latest package databases and update.
<br><br>

<h1 id="170904"><span itemprop="name">2017-09-04 -- Our <a href="forum/index.php">forum</a> is up!</h1>
<br><br>

<h1 id="170727"><span itemprop="name">2017-07-27 -- Return of the Jedi</h1>
After more than two years of maintaining repositories with OpenRC packages, the maintainers of separate but closely or loosely related projects (<a href="https://sourceforge.net/projects/archopenrc/">Arch-OpenRC</a>, <a href="https://sourceforge.net/projects/manjaro-openrc/">Manjaro-OpenRC</a>) decided to join forces and create a project that would be systemd-free and unaffected from upstream changes and updates. That required what might technically be considered as a mini-fork. Three new repositories, <a href="https://github.com/artix-linux">[system], [world] and [galaxy]</a> have been created which must be placed <u><i>before</i></u> the official Arch or Manjaro ones. This ensures that upstream (if this term can be used anymore) will never break the new setup.
<br><br>The [arch-openrc] and [arch-nosystemd] repos will stop being updated. Manjaro-OpenRC will also stop being supported in a couple of months.
<br><br>
Existing OpenRC systems (whether arch-openrc or manjaro-openrc) can be converted to the new scheme with minimal effort. Older eudev-openrc ones might need some more tweaking, especially with concern to the desktop: consolekit2 is replaced by elogind. Those with vanilla systemd Arch or Manjaro must first <a href="migrate.php">migrate as described</a>. Or, you can <a href="download.php">install a fresh Artix system</a>.
<br><br>


Our sources are hosted on <a href="https://github.com/artix-linux">github/artix-linux</a>
</div>

<?php
$nexttitle="FAQ";
$nextfile="faq.php";
include('footer.html');
?>
