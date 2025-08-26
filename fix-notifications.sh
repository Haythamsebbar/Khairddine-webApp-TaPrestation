#!/bin/bash

# Notification Diagnosis and Fix Script
# This script runs various commands to diagnose and fix notification issues

echo "=== Notification Diagnosis and Fix Script ==="
echo ""

# Working directory
cd /Users/haythamsebbar/Desktop/Khairddine

# Step 1: Register the artisan command
echo "Step 1: Clearing cache to ensure new command is registered"
php artisan cache:clear
php artisan config:clear
php artisan route:clear
echo ""

# Step 2: Run the notification fix command in check mode
echo "Step 2: Checking notifications data structure"
php artisan notifications:fix --check
echo ""

# Step 3: Ask if user wants to fix notifications
echo -n "Do you want to fix notifications that are missing title or message? (y/n): "
read fix_response
if [[ "$fix_response" == "y" || "$fix_response" == "Y" ]]; then
    echo "Running notification fix..."
    php artisan notifications:fix
    echo ""
fi

# Step 4: Open the debug view
echo -n "Do you want to open the debug view in your browser? (y/n): "
read debug_response
if [[ "$debug_response" == "y" || "$debug_response" == "Y" ]]; then
    echo "Opening debug view..."
    php artisan route:list | grep debug-notifications
    
    # Get the URL of the application
    APP_URL=$(php artisan tinker --execute="echo config('app.url');" | tail -n 1 | tr -d ' ')
    if [ -z "$APP_URL" ]; then
        APP_URL="http://localhost"
    fi
    
    echo "Visit ${APP_URL}/debug-notifications-view to see detailed notification diagnostics"
    echo ""
fi

echo "Diagnosis and fix completed!"