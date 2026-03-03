#!/usr/bin/env bash
# Run Laravel queue worker for Redis connection
# Usage: ./scripts/queue-worker.sh
php artisan queue:work redis --sleep=3 --tries=3 --timeout=90 --memory=128
