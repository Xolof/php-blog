#/usr/bin/env bash

rm public/css/styles.min.css

for file in public/css/*;
do
    if [ $file != "public/css/styles.css" ]; then
        echo "Minifying $file";
        npx postcss $file >> public/css/styles.min.css;
    fi
done;

