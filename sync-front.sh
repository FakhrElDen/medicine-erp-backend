#!/bin/bash

if ! [ -d "front" ]; then
    mkdir front
fi

rsync -rltpvh root@154.38.171.189:/var/www/medicalstaging/dist/medical/ front --delete

sed -i 's|http://medical-bd.restart-technology.com|http://localhost:8000|g' front/*.js

echo '*' >> front/.gitignore