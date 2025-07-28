# freepbx-missed-call-telegram-notifier

A lightweight PHP script that monitors missed calls on a FreePBX system and sends notifications to specific Telegram chats based on call destination. FreePBX sisteminde belirli destinasyonlara gelen cevapsız çağrıları izleyen ve Telegram grubuna bildirim gönderen hafif bir PHP scripti.

# FreePBX Missed Call Telegram Notifier

This script monitors missed calls on a FreePBX system and sends notifications to a designated Telegram chat or group.

## Features

- Destination-based filtering
- One-time notification per missed call
- Works with FreePBX CDR table
- Telegram group or direct chat support
- Optional log rotation

## Requirements

- PHP CLI
- Access to FreePBX's `asteriskcdrdb`
- A Telegram bot and chat ID

## Setup

1. Clone the repo
2. Configure database and Telegram info in `missed_calls_to_telegram.php`
3. Add cron job to run every 5 minutes:
