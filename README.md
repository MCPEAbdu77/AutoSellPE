# Overview
<a href="https://poggit.pmmp.io/p/AutoSellPE"><img src="https://poggit.pmmp.io/shield.state/AutoSellPE"></a>
<a href="https://poggit.pmmp.io/p/AutoSellPE"><img src="https://poggit.pmmp.io/shield.api/AutoSellPE"></a>
<a href="https://poggit.pmmp.io/p/AutoSellPE"><img src="https://poggit.pmmp.io/shield.dl.total/AutoSellPE"></a>
<a href="https://poggit.pmmp.io/p/AutoSellPE"><img src="https://poggit.pmmp.io/shield.dl/AutoSellPE"></a>

A PocketMine-MP plugin which automatically sells the blocks that you mine. The blocks and their prices can be changed in the config.yml file.
You can even set the worlds on which autosell can be used.
 - This plugin is compatible with AutoInv
 - This plugin depends on BedrockEconomy

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
  You will be able to set a world's in which AutoSell only works on.
  
 - worlds:
   - "world1"
   - "world2"
   - "world3"
  
  To set Block and their prices: 
  Hold the item or block in hand, use `/autosell add {price}`
  Example: 
  Holding a cobblestone block and using `/autosell add 1`
  will sell cobblestone for $1 during mining.
 
 # Permissions
 
 | Permission | Description |
 |------------|-------------|
 | `autosell.command` | Grants the user/group the permission to toggle autosell ON or OFF - Restricted to OP's by default |
 | `autosell.command.add` | Grants the user/group the permission to add or update block prices - Restricted to OP's by default |
 | `autosell.command.remove` | Grants the user/group the permission to remove blocks from autosell - Restricted to OP's by default |
 | `autosell.command.view` | Grants the user/group the permission to view/list all the prices for the autoselling blocks - Granted to everyone by default |

 # Contact

 - Discord: MCA7#1245
