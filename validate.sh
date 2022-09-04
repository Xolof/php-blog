#!/usr/bin/env bash

Violet="\e[1;35m"
EndColor="\e[0m"

printf "$Violet\n\nPHP Codesniffer\n$EndColor"
tools/codesniff.sh

printf "$Violet\n\nPHP MD\n\n$EndColor"
tools/phpmd.sh

printf "$Violet\n\nPHPStan\n\n$EndColor"
tools/phpstan.sh

