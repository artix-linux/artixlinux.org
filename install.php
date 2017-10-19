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
<h1>Disk partitioning (BIOS)</h1>
<br>
Partition your hard drive (/dev/sda will be used in this guide) with <b>fdisk</b> or <b>cfdisk</b>, the partition numbers and order are at your discretion:
<code>cfdisk /dev/sda
</code>
If you want to install side-by-side with other operating systems, you must make some space on the disk by resizing the existing partitions. You may use <b>gparted</b> for this, however detailed instructions are out of the scope of this guide. Next, format the new partitions, for example as <b>ext4</b>:
<code>mkfs.ext4 -L ROOT /dev/sda2        <- root partition
mkfs.ext4 -L HOME /dev/sda3        <- home partition, optional
mkfs.ext4 -L BOOT /dev/sda4        <- boot partition, optional
mkswap -L SWAP /dev/sda1           <- swap partition
</code>
The <b>-L</b> switch assigns labels to the partitions, which helps referring to them later through <b>/dev/disk/by-label</b> without having to remember their numbers. Now, mount your partitions:
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
Use basestrap to install the <b>base</b> and optionally the <b>base-devel</b> package groups:
<code>basestrap /mnt base base-devel
</code>
Use genfstab to generate fstab, use <b>-U</b> for UUIDs and <b>-L</b> for partition labels:
<code>genfstab -L /mnt >>/mnt/etc/fstab
</code>
Check the resulting fstab for errors before rebooting. Now, you can chroot into your new Artix system with:
<code>artools-chroot /mnt
</code>
<br>
<h1>Configure the base system</h1>
<br>
First, install <b>grub</b> and <b>os-prober</b> (for detecting other installed operating systems):
<code>pacman -S grub2 os-prober
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
To set the locale systemwide, edit <b>/etc/profile.d/locale.sh</b> (which is sourced by <b>/etc/profile</b>) or <b>/etc/bash/bashrc.d/artix.bashrc</b>; user-specific changes may be made to their respective ~/.bashrc, for example:
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
To get a graphical environment you need to install the <b>xorg group</b>:
<code>pacman -S xorg</code>
Choose your packages, or just install all of them. For the closed-source nvidia drivers you can use the <b>nvidia-lts</b> package, as our default kernel is <b>linux-lts</b>:
<br>
<code>pacman -S nvidia-lts
</code>
Older nvidia cards work with the legacy series, <b>nvidia-340xx-lts</b> and <b>nvidia-304xx-lts</b>. If you want to run a custom kernel, you can install the respective nvidia dkms package which ensures all installed kernels get their nvidia modules.<br>
AMD and Intel cards enjoy excellent (or near-excellent) 3D support with open-source drivers. Read the <a href="https://wiki.archlinux.org/index.php/xorg#Driver_installation" target="_blank">Arch wiki</a>, for information on how Xorg chooses the best available video driver and which one is optimal for your hardware.
<br><br>
Install networkmanager:
<code>pacman -S NetworkManager networkmanager-openrc
rc-update add NetworkManager default
</code>
Install a desktop environment, for example MATE, LXQt or XFCE4:
<code>pacman -S mate mate-extra xfce4 xfce4-goodies lxqt</code>
And optionally a display manager, like LXDM or SDDM:
<code>pacman -S lxdm displaymanager-openrc
rc-update add xdm default
nano /etc/conf.d/xdm   <- edit and set DISPLAYMANAGER="lxdm"
</code>
Or you can use .xinitrc to launch your DE manually; edit (or create) "~/.xinitrc" and add <b>exec mate-session</b>. <b>mate-session</b> and some other packages from Arch are compiled against systemd libs even if they don't use them; to satisfy the library link you may install <b>libsystemd-dummy</b> and <b>systemd-dummy</b>

<?php
$nexttitle="Download";
$nextfile="download.php";
include('footer.html');
?>
