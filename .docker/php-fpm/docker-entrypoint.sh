#!/usr/bin/env bash
set -e
if [ -f /init.sh ]; then
    echo "[info] Running /init.sh script" && sh /init.sh
fi

exec "$@"
