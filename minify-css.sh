#/usr/bin/env bash

rm public/css/styles.min.css

for file in public/css/*;
do
    if [ $file != "public/css/styles.css" ] && [ $file != "public/css/colors" ]; then
        echo "Minifying $file";
        npx postcss $file >> public/css/styles.min.css;
    fi
done;

