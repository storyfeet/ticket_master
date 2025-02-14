sudo systemctl start docker
sleep 4
./sail up &
sleep 5
./sail artisan reverb:start &
sleep 3
./sail npm run dev &
sleep 3
