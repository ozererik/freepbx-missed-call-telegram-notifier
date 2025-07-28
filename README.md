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
