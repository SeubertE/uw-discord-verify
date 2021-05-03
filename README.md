# uw-discord-verify
 This bot is meant to be deployed on [UW Shared Hosting](https://itconnect.uw.edu/connect/web-publishing/shared-hosting/) to take advantage of the ability to authenticate via UW NetID. Theoretically it could be deployed on any server that has the proper certicifate from UW and Shibboleth configured.
---
## Deploying the bot on ovid.u.washington.edu or vergil.u.washington.edu

1. Pull the repo into the server
```
mkdir ~/public_html/discord
cd ~/public_html/discord
git remote add origin https://github.com/SeubertE/uw-discord-verify.git
git pull orgin main -u
```

2. Configuring .htaccess files
Replace `u_netid_myteam` with the UW group you'd like to filter. E.g. `uw_ee_members`.

```
cp .htaccess.sample .htaccess
nano .htaccess
```

3. Configuring the bot
```
cp bot/config.php.sample bot/config.php
nano bot/config.php
```

4. Running python bot once
This is necessary to allow PHP to send messages.

Edit `runonce.py` with the proper keys and such.

```
cd bot
python3 -m venv
pip update
pip install discord.py
python3 runonce.py
```