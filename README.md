# FreePBX Missed Call Telegram Notifier

This script monitors missed calls on a FreePBX system and sends Telegram notifications based on destination number (DID routing or extensions).

Features

- Destination-based missed call filtering
- Separate contact names per destination
- One-time notification per call (log-based)
- Telegram bot integration (group or individual)
- Log rotation ready (sample config provided)

Requirements

- PHP CLI on your FreePBX server
- Access to FreePBX's `asteriskcdrdb`
- A Telegram bot and chat/group ID
- Cron access

Installation

1. Clone this repo:
   ```bash
   git clone https://github.com/ozererik/freepbx-missed-call-telegram-notifier.git
   ```
2. Edit the configuration in missed_calls_to_telegram.php:

- Database credentials
- Telegram bot token and chat ID
- List of monitored destinations and labels

3. Make the script executable:
```bash
chmod +x /usr/local/bin/missed_calls_to_telegram.php
```

4. Add to crontab:
```bash
*/5 * * * * /usr/bin/php /usr/local/bin/missed_calls_to_telegram.php
```
   
5. (Optional) Enable log rotation:
```bash
nano /etc/logrotate.d/missed_calls
```
```bash
/var/log/missed_calls_sent.log {
    weekly
    rotate 4
    missingok
    notifempty
    compress
    delaycompress
    create 640 root adm
}
```
