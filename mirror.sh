#!/bin/bash

set -euf -o pipefail

script_directory="$(dirname "$0")"
mirror_directory="${script_directory}/mirror"
wget_log="${script_directory}/wget.log"
wget_options=(
    --mirror
    --page-requisites
    --html-extension
    --convert-links
    --no-parent
    -X ''
    --limit-rate=500k
    --retry-connrefused
    --random-wait=on
    --no-cache
    --no-cookies
    --ignore-length
    --no-check-certificate
    -e robots=off
    -T 60
    -P "${mirror_directory}"
    -o "${wget_log}"
)
urls=(
    http://sidenote.hu
    http://bash.sidenote.hu
    http://sh.sidenote.hu
    http://cellwars.sidenote.hu
    http://cw.sidenote.hu
    http://sidenote.hu/cellwars
)

if [ -d "${mirror_directory}" ]
then
    rm -rf "${mirror_directory}"
fi

mkdir -p "${mirror_directory}"

wget \
    "${wget_options[@]}" \
    "${urls[@]}"
