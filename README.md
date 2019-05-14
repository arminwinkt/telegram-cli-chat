# Telegram CLI Chat

**A telegram client for the CLI based on PHP.**


![Choose a chat](resources/screenshots/screenshot-1.png?raw=true)
![Chatting](resources/screenshots/screenshot-2.png?raw=true)


## Requirements

- PHP 7.1+
- The application is tested on **Linux** (Ubuntu 19.04) and **Mac OS** (High Sierra)
- It should run also on Windows but was not tested yet

## Installation

1: Clone GitHub Repository
```bash
git clone https://github.com/arminwinkt/telegram-cli-chat && cd telegram-cli-chat
```

2: Run composer install
```bash
$ composer install
```

3: **Add telegram API credentials**
```bash
$ cp config/telegram.php.dist config/telegram.php
```

- Create `api_id` and `api_hash` here: https://my.telegram.org  
  You can read more about it here: https://core.telegram.org/api/obtaining_api_id
- Add the `api_id` and `api_hash` to file `/config/telegram.php`

4: Run the chat
```bash
$ php telegram-cli-chat chat
``` 


## License

This project is released under the permissive [MIT license](LICENSE.md).