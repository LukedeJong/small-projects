#!/bin/bash

# Ensure script is run as root
if [[ $EUID -ne 0 ]]; then
  echo "This script must be run as root. Use sudo." >&2
  exit 1
fi

# Check if the source directory is provided
if [ $# -lt 1 ]; then
  echo "Usage: $0 <source_directory>"
  exit 1
fi

# Variables
SOURCE_DIR="$1"
BASENAME=$(basename "$SOURCE_DIR" | tr '[:upper:]' '[:lower:]' | tr ' ' '-')  # Convert to lowercase and replace spaces with dashes
TARGET_DIR="/usr/local/share/$BASENAME"
LINK_NAME="/usr/local/bin/$BASENAME"
DB_FILE="$TARGET_DIR/$BASENAME.db"  # SQLite database file path

# Check if source directory exists
if [ ! -d "$SOURCE_DIR" ]; then
  echo "Error: Source directory '$SOURCE_DIR' does not exist." >&2
  exit 1
fi

# Copy the directory to /usr/local/share
rm -rf "$TARGET_DIR"  # Remove old version, if any
cp -r "$SOURCE_DIR" "$TARGET_DIR" || {
  echo "Error: Failed to copy directory to $TARGET_DIR." >&2
  exit 1
}

# Create the SQLite database file if it doesn't exist
if [ ! -f "$DB_FILE" ]; then
  sqlite3 "$DB_FILE" "CREATE TABLE IF NOT EXISTS tasks (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    title TEXT NOT NULL,
    status TEXT NOT NULL DEFAULT 'pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
  );"
  echo "SQLite database initialized at $DB_FILE."
fi

# Set permissions for the target directory
chmod -R 775 "$TARGET_DIR" || {
  echo "Error: Failed to set permissions on $TARGET_DIR." >&2
  exit 1
}

# Set ownership of the target directory to the user running the script
chown -R "$SUDO_USER" "$TARGET_DIR" || {
  echo "Error: Failed to change ownership of $TARGET_DIR to $SUDO_USER." >&2
  exit 1
}

# Find the main script inside the source directory
MAIN_SCRIPT=$(find "$TARGET_DIR" -type f -name "*.php" -maxdepth 1 | head -n 1)
if [ -z "$MAIN_SCRIPT" ]; then
  echo "Error: No PHP script found in $SOURCE_DIR for linking." >&2
  exit 1
fi

# Create a symlink in /usr/local/bin
ln -sf "$MAIN_SCRIPT" "$LINK_NAME" || {
  echo "Error: Failed to create symbolic link in /usr/local/bin." >&2
  exit 1
}

# Make the main script executable
chmod +x "$MAIN_SCRIPT" || {
  echo "Error: Failed to make the main script executable." >&2
  exit 1
}

echo "Installation successful! Run the application using '$BASENAME' command."
