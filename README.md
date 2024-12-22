# Execute tables creation
```bash
php .\config\migrations\create_tables.php
```

# Create admin user
```bash
php .\config\factories\create_admin_user.php "adm-123" "senha123"
```

# Init server
```bash
cd init
php -S locahost:8000
```