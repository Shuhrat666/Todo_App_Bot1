# Clone the project into your <project_folder>:
```bash
git clone https://github.com/Shuhrat666/ToDo-App-Bot .
```

# Install composer to your <project_folder>:
```bash
composer install
```

# Copy .env.example to .env and fill it with the neccessary data those are mentioned in it :
```bash
cp .env.example .env
```

# Run mig31.php placed in migrations folder:
```bash
php mig31.php
```

# Run your local server port :
```bash
php -S localhost:8000
```

# Run ngrok with the same port :
```bash
ngrok http 8000
```

# Run "webhook.php" file and include <your_ngrok_forwarding_link> in the command :
```bash
php webhook.php <your_ngrok_forwarding_link>
```

# Start the bot :
Click or type '/start' to the bot and check ngrok htttp request. The things are going fine if the respond is '200 OK'.
Othervise, check your terminal commands, layouts and files.
The Web can be run by launching browser and typing localhost link in searchbox:
```bash
localhost:8000/Web2
```

