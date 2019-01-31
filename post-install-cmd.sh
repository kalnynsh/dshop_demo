#!/bin/sh
if [ -n "$DYNO" ]
then
    php init --env=Heroku --overwrite=All
    ln -s /app/backend/web frontend/web/backend
    ln -s /app/api/web frontend/web/api
    ln -s /app/static frontend/web/static
    ln -s /app/vendor/bower-asset vendor/bower
fi
