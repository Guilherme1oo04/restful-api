# Execute tables creation
```bash
php .\config\migrations\create_tables.php
```

# Create admin user
```bash
php .\config\factories\create_admin_user.php "adm-123" "senha123"
```

# Create api token
```bash
# This parameter is the code at user in database
php .\config\factories\create_api_token.php "10"
```

# Init server
```bash
cd init
php -S localhost:8000
```