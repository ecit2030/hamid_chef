#!/bin/bash

# Script to run all API tests for User Profile, Chef Profile, and Chef Service Creation

echo "=========================================="
echo "Running API Tests"
echo "=========================================="
echo ""

echo "1. Running User Profile Update Tests..."
php artisan test tests/Feature/Api/UserProfileUpdateTest.php --testdox
echo ""

echo "2. Running Chef Profile Update Tests..."
php artisan test tests/Feature/Api/ChefProfileUpdateTest.php --testdox
echo ""

echo "3. Running Chef Service Creation Tests..."
php artisan test tests/Feature/Api/ChefServiceCreationTest.php --testdox
echo ""

echo "=========================================="
echo "All Tests Completed!"
echo "=========================================="
