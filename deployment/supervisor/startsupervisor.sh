echo "DOCKER_ENABLE_SUPERVISOR=$RUN_SUPERVISOR"

if [ "$RUN_SUPERVISOR" = "true" ]; then
    echo "Starting Supervisor..."
    wait 60
    supervisord -n
else
    echo "Supervisor is disabled"
fi
