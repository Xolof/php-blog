#!/usr/bin/env bash

rm public/css/styles.min.css

for file in public/css/*;
do
    if [ $file != "public/css/styles.css" ] && [ $file != "public/css/colors" ] && [ $file != "public/css/responsive.css" ]; then
        echo "Minifying $file";
        npx postcss $file >> public/css/styles.min.css;
    fi
done;

echo "Minifying $file";
npx postcss public/css/responsive.css >> public/css/styles.min.css;

