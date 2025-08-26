#!/usr/bin/env bash
set -euo pipefail

LOG=storage/logs/laravel.log
echo "Tailing $LOG (Ctrl+C to stop)"
touch "$LOG"
tail -f "$LOG"


