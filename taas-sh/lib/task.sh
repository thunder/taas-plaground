#!/usr/bin/env bash

run_task() {
    local task="${1}"

    # source the task
    # shellcheck source=/dev/null
    source "${SCRIPT_DIR}/../lib/tasks/${task}.sh"
    _task_"${task}"
}

task_exists() {
    local task="${1}"

    # shellcheck source=/dev/null
    source "${SCRIPT_DIR}/../lib/tasks/${task}.sh"
    declare -f -F _task_"${task}" >/dev/null

    return ${?}
}
