#!/bin/bash

# get bash colors and styles here:
# http://misc.flogisoft.com/bash/tip_colors_and_formatting
C_RESET='\e[0m'
C_RED='\e[31m'
C_GREEN='\e[32m'
C_YELLOW='\e[33m'

function __run() #(step, name, cmd)
{
    local color output exitcode

    printf "${C_YELLOW}[%s]${C_RESET} %-20s" "$1" "$2"
    output=$(eval "$3" 2>&1)
    exitcode=$?

    if [[ 0 == $exitcode || 130 == $exitcode ]]; then
        echo -e "${C_GREEN}OK!${C_RESET}"
    else
        echo -e "${C_RED}NOK!${C_RESET}\n\n$output"
        exit 1
    fi
}

modified="git diff --diff-filter=M --name-only --cached  | grep \".php$\""
phpfiles="git ls-tree --full-tree --name-only -r HEAD | grep \".php$\""
ignore="resources/lang,resources/views,bootstrap/helpers,database/migrations,bin" # paths to ignore for phpcs
phpcs="vendor/bin/phpcs --report=code --colors --report-width=80 --standard=PSR12 --ignore=${ignore}"

__run "1/3" "php lint" "${phpfiles} | xargs -r php -l"
__run "2/3" "code sniffer" "${phpfiles} | xargs -r ${phpcs}"
__run "3/3" "phpstan" "${phpfiles} | xargs -r vendor/bin/phpstan analyse"

