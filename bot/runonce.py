import os

import discord

TOKEN = {}
GUILD = {}
CHANNEL = {}

client = discord.Client()

@client.event
async def on_ready():
    print('Bot is running and connected')

@client.event
async def on_message(message):
    if 'ping' in message.content.lower():
        await message.channel.send('pong')

client.run(TOKEN)