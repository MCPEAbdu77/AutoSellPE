# AutoSell
This is a bedrock pocketmine (PMMP) plugin which automatically sells the blocks while you mine. The blocks and their prices can be changed in the config.yml file.
You can even set the worlds on which autosell can be used.
 - This plugin is compatible with AutoInv
 - This plugin depends on EconomyAPI (Support for CoinsSystem coming soon)

# Commands: 
 /autosell <on/off> (/as for short form)
 Toggles autosell.
 
# Editing config.yml
  You will be able to set a world's in which AutoSell only works on.
  worlds:
   - "world1"
   - "world2"
   - "world3"
  
  To set Block and their prices: 
   BlockID: "price"
   Example:
   - 1: "5"     // Block ID with price
   - 17:1: "30" // Block ID with Meta variant and it's price
 
 # Permissions
 Add `autosell.command` to the groups you want to give. 

 - You can support my work by donating via PayPal: (paypal.me/abdu77)
 - Copyright (C) 2021 MCA7
