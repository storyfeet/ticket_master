sudo systemctl start docker
sleep 4
./sail up &
sleep 6
./sail artisan reverb:start &
sleep 4
./sail npm run dev &
sleep 4
