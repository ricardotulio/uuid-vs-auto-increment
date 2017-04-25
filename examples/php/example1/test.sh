#!/bin/bash

for i in $(ls insert_*.php); do
    filename=$(echo $i | cut -d . -f 1)
    filename+=_result
    filename+=_$1
    result=$(docker-compose run --rm php $i)
    echo $result
done
