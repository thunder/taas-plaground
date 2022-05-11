#!/usr/bin/env bash

# Check requirements.
check_requirements() {
    local task="${1}"

    # source the task
    # shellcheck source=/dev/null
    source "${SCRIPT_DIR}/../lib/tasks/${task}.sh"
    _task_"${task}"_check_requirements
}
