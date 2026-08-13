#!/bin/sh

if [ "${KEEP_STACK:-}" = "1" ]; then
	echo "KEEP_STACK=1 — leaving the test stack running" >&2
	exit 0
fi

. "$(dirname -- "$0")/helper.sh"

dc down --volumes --remove-orphans
