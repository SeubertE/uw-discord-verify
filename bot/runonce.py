import os

import discord
from dotenv import load_dotenv

TOKEN = os.getenv('DISCORD_TOKEN')
GUILD = os.getenv('DISCORD_GUILD')
CHANNEL = os.getenv('DISCORD_CHANNEL')

client = discord.Client()

@client.event
async def on_ready():
    print('Bot is running and connected')

@client.event
async def on_message(message):
    if 'ping' in message.content.lower():
        await message.channel.send('pong')

client.run(TOKEN)