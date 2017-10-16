<?php
$title="Installation";
include('header.html');
?>
<!-- This guide is writted by "Francisco Dominguez Lerma"
If you want contact with me, you can email me: francisco.dominguez.lerma@gmail.com
-->

<div class="holder">
<br><br><h0>Artix installation guide from base</h0></p>
<br>
<h1>Partition (BIOS)</h1>
<br>
Partition the harddrive (similar to archlinux):
<code>cfdisk /dev/sdX
</code>
Now, format the new partitions (similar to archlinux):
<code>mkfs.ext4 /dev/sda2  <- root partition
mkfs.ext4 /dev/sda3  <- home partition, only if exist
mkswap /dev/sda1  <- swap partition
</code>
Mount partitions:
<code>swapon /dev/sda1
mount /dev/sda2 /mnt
mount /dev/sda3 /mnt/home  <- only if exist
</code>
<br>
<h1>Install base system</h1><br>
The first step is check the network connection. For example:
<code>ping github.com
</code>
Update repositories:
<code>pacman -Syy
</code>
Install pacstrap into installer, for default, pacstrap is not installed, you can install it with:
<code>pacman -S arch-install-scripts
</code>
Use pacstrap to install base system:
<code>pacstrap /mnt base
</code>
Or if you want base-devel group packages:
<code>pacstrap /mnt base base-devel
</code>
Use genfstab to generate fstab:
<code>genfstab -U /mnt>>/mnt/etc/fstab
</code>
Now, you can enter into your new artix system with:
<code>arch-chroot /mnt
</code>
<br>
<h1>Configure the base system</h1>
<br>
First, install grub to new system (similar to archlinux):
<code>pacman -S grub2
grub-install --recheck /dev/sdX
mkconfig -o /boot/grub/grub.cfg
</code>
Create personal user and password:
<code>useradd -m youruser
passwd youruser
</code>
Set root password:
<code>password
</code>
Generate locales:
<code>nano /etc/locale.gen  <- uncomment your locale
locale-gen
</code>
Set default locale, the most easy way is editing the .bashrc for every user.
For example into <i>"~/.bashrc"</i> add to final:
<code>export LANG="en_US.UTF-8"
export LC_COLLATE="C"
</code>
And for root, in the file <i>"/etc/bash/bashrc.d/artix.bashrc"</i>
    <br> <br>
<h1>Post installation configuration</h1>
    <br>
Now, you can reboot and enter into your new installation:
<code>exit  <- exit to arch-chroot environment
umount /mnt/home  <- only if exist home partition
umount /mnt
reboot
</code>
If all is right, you are in the new system, now you need install xorg and GPU driver, install Xorg:
<code>pacman -S xorg</code>
For modern nvidia drivers you can use:
<br>
<code>pacman -S nvidia nvidia-xconfig
nvidia-xconfig
</code>
For other graphic cards you can read the <a href="https://wiki.archlinux.org/index.php/xorg#Driver_installation" target="_blank">Archlinux wiki</a>, is the same in artix.
<br><br>
Install networkmanager:
<code>pacman -S NetworkManager networkmanager-openrc
rc-update add NetworkManager default
</code>
Install a Desktop Environment, for example Mate:
<code>pacman -S mate mate-extra</code>
And optionally a session manager, for example LXDM:
<code>pacman -S lxdm displaymanager-openrc
rc-update add xdm default
</code>
Or you can use .xinitrc to launch DE, edit (or create) "~/.xinitrc" and add:
<code>exec mate-session</code>
Mate desktop and other apps need systemd, for compatibility you can install a compatibility package:
<code>libsystemd-dummy</code>
If everything went well and you should have your Artix system installed, obviously it may require more configuration depending on your needs.

<?php
$nexttitle="Download";
$nextfile="download.php";
include('footer.html');
?>
