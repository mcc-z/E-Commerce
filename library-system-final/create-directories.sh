#!/bin/bash

echo "Creating placeholder files in storage directories..."

# Create .gitkeep files to ensure directories are tracked by git
touch storage/logs/.gitkeep
touch storage/framework/cache/.gitkeep
touch storage/framework/cache/data/.gitkeep
touch storage/framework/sessions/.gitkeep
touch storage/framework/views/.gitkeep
touch bootstrap/cache/.gitkeep
touch database/factories/.gitkeep
touch tests/.gitkeep

echo "Done!"
