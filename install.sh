#!/usr/bin/env bash

BOLD='\033[1m'
GREEN='\033[0;32m'
RED='\033[0;31m'
RESET='\033[0m'

# Check if CI4 project exists
if [ ! -f "app/Config/Paths.php" ]; then
    echo -e "${RED}Error: This does not appear to be a CodeIgniter 4 project.${RESET}"
    echo -e "${RED}Please run this installer in the root of a CodeIgniter 4 project.${RESET}"
    exit 1
fi

echo -e "${GREEN}${BOLD}Installing CodeIgniter Sail...${RESET}"

# Create directory structure
mkdir -p docker/config/php
mkdir -p docker/config/mysql

# Create CI Sail script
cat > raft << 'EOL'
#!/usr/bin/env bash

# Script content here - this will be filled in by the installer
EOL

# Copy Docker files
cat > docker-compose.yml << 'EOL'
# Docker Compose content here - this will be filled in by the installer
EOL

cat > docker/config/php/Dockerfile << 'EOL'
# Dockerfile content here - this will be filled in by the installer
EOL

cat > docker/config/php/php.ini << 'EOL'
# PHP.ini content here - this will be filled in by the installer
EOL

cat > docker/config/php/start-container.sh << 'EOL'
# Start container script content here - this will be filled in by the installer
EOL

cat > docker/config/mysql/create-testing-database.sh << 'EOL'
# MySQL init script content here - this will be filled in by the installer
EOL

# Make scripts executable
chmod +x raft
chmod +x docker/config/php/start-container.sh
chmod +x docker/config/mysql/create-testing-database.sh

# Create .env file if it doesn't exist
if [ ! -f .env ]; then
    cp env .env
fi

# Add Sail-specific environment variables to .env
if ! grep -q "APP_PORT" .env; then
    echo "" >> .env
    echo "# CI Sail Configuration" >> .env
    echo "APP_PORT=80" >> .env
    echo "DB_PORT=3306" >> .env
    echo "REDIS_PORT=6379" >> .env
    echo "MAILHOG_PORT=1025" >> .env
    echo "MAILHOG_DASHBOARD_PORT=8025" >> .env
    echo "WWWUSER=$(id -u)" >> .env
    echo "WWWGROUP=$(id -g)" >> .env
fi

echo -e "${GREEN}${BOLD}CodeIgniter Sail has been installed successfully!${RESET}"
echo -e "${GREEN}You can now start your development environment by running:${RESET}"
echo -e "${BOLD}./raft up${RESET}"