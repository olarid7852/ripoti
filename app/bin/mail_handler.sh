#!/bin/sh

MESSAGE=$(cat)

cd "$(dirname "$0")/../.."
./yii email/pipe "$MESSAGE"

exit 0