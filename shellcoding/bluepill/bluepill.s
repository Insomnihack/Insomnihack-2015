# as -o bluepill.o bluepill.s && ld -o bluepill bluepill.o
.section ".short", "awx"
.short
    .global _start
_start:

    #write(fd, buf, size) (rdi, rsi, rdx)
	mov	$len1,%rdx
	mov	$msg1,%rsi
	mov	$1,%rdi
	mov	%rdi,%rax
    syscall

    #read(fd, buf, size) (rdi, rsi, rdx)
	mov	$4,%rdx
	lea	sc, %rsi
	xor	%rdi,%rdi
	xor	%rax,%rax
    syscall

	mov	$end,%rcx
isnull:
	cmpb	$0,(%rcx)
	je	exit
	cmp	$sc,%rcx
	loopne	isnull

sc:
	.rept   0x100
        .long   0xdeadc0de
        .endr

end:
exit:
	push    $60
	mov	$1,%rdi
	pop     %rax
    syscall

msg1:
	.ascii "This is your last chance. After this, there is no going back.\nYou take the blue pill and the story ends.\nYou wake in your bed and you believe whatever you want to believe.\nYou take the red pill and you stay in Wonderland and I show you how deep the rabbit-hole goes...\n\nJust kidding, we're out of red pill anyway!\n"
	len1 = . - msg1
