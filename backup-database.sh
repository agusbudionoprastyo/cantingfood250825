#!/bin/bash

echo "🗄️ Starting Database Backup..."

TIMESTAMP=$(date +"%Y%m%d_%H%M%S")
BACKUP_DIR="database-backups"
BACKUP_FILE="canting_food_backup_$TIMESTAMP.sql"

echo "📁 Creating backup directory..."
mkdir -p $BACKUP_DIR

echo "💾 Backing up database..."
mysqldump -u root -p canting_food > "$BACKUP_DIR/$BACKUP_FILE"

if [ $? -eq 0 ]; then
    echo "✅ Database backup completed successfully!"
    echo "📁 Backup saved to: $BACKUP_DIR/$BACKUP_FILE"
    
    echo "🗜️ Compressing backup file..."
    gzip "$BACKUP_DIR/$BACKUP_FILE"
    
    echo "🧹 Cleaning old backups (keeping last 5)..."
    ls -t "$BACKUP_DIR"/*.sql.gz | tail -n +6 | xargs -r rm
    
    echo "📊 Backup summary:"
    ls -lh "$BACKUP_DIR"/
else
    echo "❌ Database backup failed!"
    exit 1
fi
