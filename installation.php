<?php
$title="Install";
include('header.html');
?>
<!-- This guide is writted by "Francisco Dominguez Lerma"
If you want contact with me, you can email me: francisco.dominguez.lerma@gmail.com
-->

<div class="holder">
<center><br><br><br><br><h0>Installation</h0></p>
<br><br><br>                                                                                            
</center>

Artix can either be installed through the console or the GUI installer. The GUI install is quite straightforward, we'll focus on the console installation procedure here.
<br><br>
<h1>Partition (BIOS)</h1>
<br>
Partition your hard drive (/dev/sda will be used in this guide) with <i>fdisk</i> or <i>cfdisk</i>, the partition numbers and order are at your discretion:
<code>cfdisk /dev/sda
</code>
Now, format the new partitions, for example as <i>ext4</i>:
<code>mkfs.ext4 /dev/sda2        <- root partition
mkfs.ext4 /dev/sda3        <- home partition, optional
mkfs.ext4 /dev/sda4        <- boot partition, optional
mkswap /dev/sda1           <- swap partition
</code>
Mount partitions:
<code>swapon /dev/sda1
mount /dev/sda2 /mnt
mount /dev/sda3 /mnt/home  (if created)
mount /dev/sda4 /mnt/boot  (if created)
</code>
<br>
<h1>Install base system</h1><br>
A working internet connection is required and assumed. A wired connection is setup automatically, if found. Wireless ones must be configured by the user. Verify your connection and update the repositories:
<code>ping artixlinux.org
pacman -Syy
</code>
Use basestrap to install the <i>base</i> and optionally the <i>base-devel</i> package groups:
<code>basestrap /mnt base base-devel
</code>
Use genfstab to generate fstab, use <i>-U</i> for UUIDs and <i>-L</i> for disk labels:
<code>genfstab -U /mnt >>/mnt/etc/fstab
</code>
Check the resulting fstab for errors before rebooting. Now, you can chroot into your new Artix system with:
<code>artools-chroot /mnt
</code>
<br>
<h1>Configure the base system</h1>
<br>
First, install grub:
<code>pacman -S grub2
grub-install --recheck /dev/sda
grub-mkconfig -o /boot/grub/grub.cfg
</code>
Create a user and password:
<code>useradd -m user
passwd user
</code>
Set root password:
<code>password
</code>
Generate locales:
<code>nano /etc/locale.gen  <- uncomment your locale
locale-gen
</code>
To set the locale systemwide, edit <i>/etc/profile.d/locale.sh</i> (which is sourced by <i>/etc/profile</i>) or <i>/etc/bash/bashrc.d/artix.bashrc</i>; user-specific changes may be made to their respective ~/.bashrc, for example:
<code>export LANG="en_US.UTF-8"
export LC_COLLATE="C"
</code>
<br>
<h1>Post installation configuration</h1>
<br>
Now, you can reboot and enter into your new installation:
<code>exit   <- exit chroot environment
umount -R /mnt
reboot
</code>
Once shutdown is complete, remove your installation media. If all went well, you should boot into your new system. Log in as your root to complete the post-installation configuration.
<br>
To get a graphical environment you need to install the <i>xorg group</i>:
<code>pacman -S xorg</code>
Choose your packages, or just install all of them. For the closed-source nvidia drivers you can use the <i>nvidia-lts</i> package, as our default kernel is <i>linux-lts</i>:
<br>
<code>pacman -S nvidia-lts
</code>
Older nvidia cards work with the legacy series, <i>nvidia-340xx-lts</i> and <i>nvidia-304xx-lts</i>. If you want to run a custom kernel, you can install the respective nvidia dkms package which ensures all installed kernels get their nvidia modules.<br>
AMD and Intel cards enjoy excellent (or near-excellent) 3D support with open-source drivers. Read the <a href="https://wiki.archlinux.org/index.php/xorg#Driver_installation" target="_blank">Arch wiki</a>, for information on how Xorg chooses the best available video driver and which one is best suited to your hardware.
<br><br>
Install networkmanager:
<code>pacman -S NetworkManager networkmanager-openrc
rc-update add NetworkManager default
</code>
Install a Desktop Environment, for example MATE or LXQt:
<code>pacman -S mate mate-extra lxqt</code>
And optionally a session manager, for example LXDM or SDDM:
<code>pacman -S lxdm displaymanager-openrc
rc-update add xdm default
nano /etc/conf.d/xdm   <- edit and set DISPLAYMANAGER="lxdm"
</code>
Or you can use .xinitrc to launch your DE manually; edit (or create) "~/.xinitrc" and add <i>exec mate-session</i>. <i>mate-session</i> and some other packages from Arch are compiled against systemd libs even if they don't use them; to satisfy the library link you may install <i>libsystemd-dummy</i> and <i>systemd-dummy</i>

<?php
$nexttitle="Download";
$nextfile="download.php";
include('footer.html');
?>
