#include <stdio.h>
#include <stdlib.h>
#include <fcntl.h>
#include <seccomp.h>
#include <unistd.h>
#include <string.h>
#include <sys/mman.h>
#include <sys/stat.h>
#include <sys/types.h>

/* The only way to design a good sandbox is using a blacklist!
 *
 * The flag is NOT flag.txt, flag, key, key.txt etc...
 */

#define BLACKLIST_SYSCALLS \
	X(read)       \
	X(open)       \
	X(close)      \
	X(mmap)       \
	X(mprotect)   \
	X(munmap)     \
	X(ioctl)      \
	X(pread64)    \
	X(preadv)     \
	X(readv)      \
	X(dup)        \
	X(dup2)       \
	X(clone)      \
	X(fork)       \
	X(execve)     \
	X(fcntl)      \
	X(ptrace)     \
	X(prctl)      \
	X(chroot)

#define X(name)   SCMP_SYS(name),

#define MAX_SHELLCODE_SIZE    0x100

void error(char *msg)
{
	perror(msg);
	exit(1);
}

void setup_sandbox(void)
{
	int syscalls[] = { BLACKLIST_SYSCALLS };
	scmp_filter_ctx ctx;
	int rc;
	int syscall;
	unsigned i;

	ctx = seccomp_init(SCMP_ACT_ALLOW);

	if (ctx == NULL) {
		error("seccomp_init\n");
	}

	for (i = 0; i < sizeof(syscalls) / sizeof(int); i++) {
		if (seccomp_rule_add(ctx, SCMP_ACT_KILL, syscall, 0)) {
			error("seccomp_rule_add\n");
		}
	}

	if ((rc = seccomp_load(ctx)) < 0) {
		seccomp_release(ctx);
		error("seccomp_load\n");
	}

	seccomp_release(ctx);
}

int main(void)
{
	char shellcode[MAX_SHELLCODE_SIZE + 1];
	char *map;
	int rc;

	map = mmap(NULL, 0x1000, PROT_WRITE, MAP_PRIVATE | MAP_ANONYMOUS, -1, 0);

	if (map == MAP_FAILED) {
		error("mmap");
	}

	bzero(map, 0x1000);

	rc = read(STDIN_FILENO, shellcode, MAX_SHELLCODE_SIZE);
	strncpy(map, shellcode, rc);

	if (mprotect(map, 0x1000, PROT_READ | PROT_EXEC)) {
		error("mprotect");
	}

	if (chroot(".")) {
		error("chroot");
	}

	setup_sandbox();

	((void (*)())map)();

	return 0;
}
