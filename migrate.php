<?php
$title="Migration";
include('header.html');
?>

<div class="holder">
<br><br><h0>Migration guide from Arch or Manjaro to Artix</h0></p>
<br>
<h1>All commands below must be run as root.</h1>
<br><br>
Put these repos in /etc/pacman.conf *before* the official Arch/Manjaro ones and disable [core] of the latter:
<code># Artix repos
[system]
Include = /etc/pacman.d/mirrorlist
[world]
Include = /etc/pacman.d/mirrorlist
[galaxy]
Include = /etc/pacman.d/mirrorlist

# Arch repos, overriden and [core] disabled
# [core]
# Include = /etc/pacman.d/mirrorlist-arch
[extra]
Include = /etc/pacman.d/mirrorlist-arch
[community]
Include = /etc/pacman.d/mirrorlist-arch
# [multilib]
# Include = /etc/pacman.d/mirrorlist-arch
</code>
For now, the [multilib] repo is only available from Arch or Manjaro, but will eventually be available from Artix too. The [arch-openrc] and [arch-nosystemd] repos (or [openrc-eudev] if you're still on it) <b><i>must</i></b> be disabled.
<br><br>
2. Rename /etc/pacman.d/mirrorlist to /etc/pacman.d/mirrorlist-arch
<code>mv /etc/pacman.d/mirrorlist /etc/pacman.d/mirrorlist-arch
</code>

3. Create a new /etc/pacman.d/mirrorlist, refresh the database and install the new keyring. The new keyring can be installed either by lowering the security levels in pacman.conf or by circumventing pacman, see below.
<code>cat &gt /etc/pacman.d/mirrorlist &lt&ltEOF
# Worldwide mirrors
Server = https://mirrors.dotsrc.org/artix-linux/repos/\$repo/os/\$arch
Server = http://mirror.strits.dk/artix-linux/repos/\$repo/os/\$arch
Server = https://artix.mief.nl/repos/\$repo/os/\$arch
Server = http://mirror1.artixlinux.org/artix-linux/repos/\$repo/os/\$arch
EOF
</code>
Clean all cache, because some of our own packages will have a different signature and pacman will complain. If you have a large cache and want to keep it, don't -Scc but you'll be asked to remove some corrupted packages and re-download. Then, force sync: 
<code>pacman -Scc && pacman -Syy
</code>

To install artix-keyring, you must sign a master key manually:
<code>pacman -S artix-keyring
pacman-key --populate artix
pacman-key --lsign-key 78C9C713EAD7BEC69087447332E21894258C6105
</code>
Save a list of your running systemd daemons, you'll need to install the respective openrc scripts afterwards.
<code>
systemctl list-units --state=running | grep -v systemd | awk '{print $1}' | grep service > daemon.list
</code>
You are now ready for the installation of the Artix components, as well as the replacement init scripts for your systemd services.
<br><br>
You should fetch the basic packages in the pacman cache (not really mandatory, but if you lose your internet connection in the middle of the update, you might be left without init). You must also fetch the linux-lts and linux-lts-headers packages, as Artix uses the latest LTS (long term support) kernel. 
Base and base-devel groups must also be reinstalled in order to be replaced by the ones in the [system] repo. Finally, the openrc-world group provides init scripts for services such as NetworkManager, bluez (for bluetooth) or cups (for printing):
<code>pacman -Sw base base-devel openrc-system grub linux-lts linux-lts-headers systemd-dummy libsystemd-dummy openrc-world
</code>
Now that the packages are cached, you can send systemd and its family to oblivion (answer 'yes' to all pacman questions below).
<code>pacman -Rdd systemd libsystemd
</code>
After you've done this you MUST also complete the next step, otherwise you're left without init system. If you're doing this remotely (e.g. through ssh), please keep another ssh session open, just in case the first one freezes (it <i>has</i> happened, all praise systemd).
Finally, you can install the packages previously downloaded with `pacman -Sw`. Simple enough:
<code>pacman -S base base-devel openrc-system grub linux-lts linux-lts-headers systemd-dummy libsystemd-dummy openrc-world openrc netifrc grub mkinitcpio
</code>
Also, install and enable the initscripts for your running services, an example follows:
<code>pacman -S --needed acpid-openrc alsa-utils-openrc autofs-openrc cronie-openrc cups-openrc displaymanager-openrc fuse-openrc haveged-openrc hdparm-openrc openssh-openrc samba-openrc syslog-ng-openrc
for daemon in acpid alsasound autofs cronie cupsd xdm fuse haveged hdparm smb sshd syslog-ng; do rc-update add $daemon default; done
</code>
You most certainly need to enable udev, dbus and elogind, unless you know what you're doing:
<code>rc-update add udev boot
rc-update add elogind boot
rc-update add dbus default
</code>
Next, copy the new /etc/mkinitcpio.pacnew over to /etc/mkinitcpio, /etc/default/grub.pacnew to /etc/default/grub, recreate the kernel's initramfs with mkinitcpio and reinstall grub. If you had committed any changes to the original files (i.e. resume hook in mkinitcpio.conf or a custom kernel cmdline parameter in grub), you should merge them now.
<br><br>
Edit your network configuration file, it's /etc/conf.d/net. <b><i>This is especially important if you're converting a remote box, or you may very well be locked out of it</i></b>. Depending on whether you use persistent device naming or not, you must symlink /etc/init.d/net.lo to net.enp0s3 or net.eth0. The interface names are used only as examples, make sure you find out what applies to you system. If in doubt (and have only got one ethernet interface), you can disable persistent device naming with a kernel command line (GRUB_CMDLINE_LINUX below) and go with net.eth0, otherwise omit it.
<code>vi /etc/conf.d/net
echo 'GRUB_CMDLINE_LINUX="net.ifnames=0"' >>/etc/default/grub		# disable persistent device naming
ln -s /etc/init.d/net.lo /etc/init.d/net.eth0
rc-update add net.eth0 boot
</code>
If you have a LVM setup, you must install the lvm2-openrc and device-mapper-openrc packages, otherwise the logical volumes will be inactive after reboot. Both of these packages are part of the openrc-system group, so you probably have it installed already. Enable the services at boot:
<code>rc-update add lvm boot
rc-update add device-mapper boot
</code>
You can optionally remove some systemd junk accounts too: 
<code>for user in systemd-journal systemd-journal-gateway systemd-timesync systemd-network systemd-bus-proxy systemd-journal-remote systemd-journal-upload; do userdel $user; done
</code>
Make sure you remove any 'init=/usr/lib/systemd/systemd' or similar directives from your bootloader config, the linux kernel by default launches /sbin/init. Also, remove any 'x-systemd' directives from /etc/fstab.
<br><br>
Recreate your initramfs and grub configuration file.
<code>mkinitcpio -p linux-lts (the default Artix kernel) or mkinitcpio -P (all installed kernels)
update-grub
</code>
Reinstall GRUB: 
<code>-> For UEFI: 
grub-install --target=x86_64-efi --efi-directory=/boot/efi --bootloader-id=grub 
grub-install --target=x86_64-efi --efi-directory=esp_mount --bootloader-id=grub (ditto, a user reported success with this one)

-> For BIOS: 
grub-install /dev/sdX (replace sdX with sda, sdb, or whatever your disk is)
</code>
Edit /etc/rc.conf to your liking. A common tweak is to set rc_parallel="YES", but do this only after you've booted your system at least once. You also need to edit the config files for your hostname, keyboard, keymap and locale.
<code>echo "hostname="`hostname` >| /etc/conf.d/hostname
vi /etc/conf.d/keymaps
vi /etc/conf.d/consolefont
vi /etc/locale.conf
</code>
Install a display manager, sddm is used by Artix in its <a href="download.php">installable ISOs</a> and edit DISPLAYMANAGER in /etc/conf.d/xdm to "sddm".
<code>pacman -S sddm artix-sddm-theme
sed -i 's/DISPLAYMANAGER="mdm"/DISPLAYMANAGER="sddm"/' /etc/conf.d/xdm
</code>
You won't be able to reboot normally, as the bloated PID1 binary is missing now. Do it with the kernel SysRq trigger.
<code>umount -a
mount -f / -o remount,ro
echo s >| /proc/sysrq-trigger
echo u >| /proc/sysrq-trigger
echo b >| /proc/sysrq-trigger
</code>
Some additional configuration may still be needed, especially with regards to desktop functionality. The <a href="http://systemd-free.org/config.php">configuration section at systemd-free.org</a> will give you some ideas.
</div>

<?php
$nexttitle="Installation from base";
$nextfile="installation.php";
include('footer.html');
?>
