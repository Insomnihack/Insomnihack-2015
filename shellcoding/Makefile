all:
	gcc -m64 tldr.c -o tldr -lseccomp
	setcap cap_sys_chroot=ep tldr
