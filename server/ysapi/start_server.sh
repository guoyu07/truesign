while [ 1 ]
do
   pkill -9 php
   php  -r run.php | grep -Ei "notice|warning|error"
   sleep 1
done
