# Overview
<a href="https://poggit.pmmp.io/p/AutoSellPE"><img src="https://poggit.pmmp.io/shield.state/AutoSellPE"></a>
<a href="https://poggit.pmmp.io/p/AutoSellPE"><img src="https://poggit.pmmp.io/shield.api/AutoSellPE"></a>
<a href="https://poggit.pmmp.io/p/AutoSellPE"><img src="https://poggit.pmmp.io/shield.dl.total/AutoSellPE"></a>
<a href="https://poggit.pmmp.io/p/AutoSellPE"><img src="https://poggit.pmmp.io/shield.dl/AutoSellPE"></a>

<b>Github version only supports PM5 / API 5</b>

A PocketMine-MP plugin which automatically sells the blocks that you mine. The blocks and their prices can be changed in the config.yml file. 
You can even set the worlds on which autosell can be used.
 - This plugin is compatible with AutoInv
 - This plugin supports BedrockEconomy, Capital & EconomyAPI

# Commands: 
  Usage: `/autosell < on | off | add | remove | view >`
   
   | Command | Sub-commands | Description |
   | --------|-------------|-------------|
   | autosell | on | Turns on autosell |
   | | off | Turns off autosell |
   | | add | Adds the item the user is holding to the autosell price list. Proper usage: `/autosell add {price}` {price} should be a integer or float. For updating prices repeat with different price
   | | remove | Removes the item the user is holding from the autosell price list
   | | view | Lists the prices of all blocks/items added to the autosell price list
   
 
 Alias: `/as`
 
# Config.yml
```
#     _              _             ____           _   _   ____    _____
#    / \     _   _  | |_    ___   / ___|    ___  | | | | |  _ \  | ____|
#   / _ \   | | | | | __|  / _ \  \___ \   / _ \ | | | | | |_) | |  _|
#  / ___ \  | |_| | | |_  | (_) |  ___) | |  __/ | | | | |  __/  | |___
# /_/   \_\  \__,_|  \__|  \___/  |____/   \___| |_| |_| |_|     |_____|
#
# Author: MCA7
# Website: https://github.com/MCPEAbdu77/AutoSellPE
# Apache-2.0 (C) 2021

# Don't change this shit
ver: 1.2

# Prefix: (use § for color coding)
prefix: "§6AutoSell§aPE§7:"

# Name of the world autosell works on.
# Example:
# worlds:
#  - "world1"
#  - "world2"

worlds:
  - "world"
  - "world2"
  - "world3"

# Economy provider
# the possible values for this currently are
# 1. BedrockEconomy
# 2. Capital
# 3. EconomyAPI
economy-provider: BedrockEconomy

# DO NOT TOUCH IF YOU DONT KNOW WHAT YOU ARE DOING
# BY DEFAULT SELECTOR ENTRY IS EMPTY
capital-settings:
  selector:

# New system for adding/removing blocks/items for AutoSell
# has been changed to in-game configeration.
# Hold the block/item and use the command,
# /autosell add <price>
# Example:
# Hold cobblestone block and then use
# /autosell add 10
# This will autosell cobblestone block for $10
# when stone is mined.
# Likewise for removing, hold the block and
# use /autosell remove
```
 
 # Permissions
 
 | Permission | Description |
 |------------|-------------|
 | `autosell.command` | Grants the user/group the permission to toggle autosell ON or OFF - Restricted to OP's by default |
 | `autosell.command.add` | Grants the user/group the permission to add or update block prices - Restricted to OP's by default |
 | `autosell.command.remove` | Grants the user/group the permission to remove blocks from autosell - Restricted to OP's by default |
 | `autosell.command.view` | Grants the user/group the permission to view/list all the prices for the autoselling blocks - Granted to everyone by default |

 # Contact

 - Discord: MCA7#1245
