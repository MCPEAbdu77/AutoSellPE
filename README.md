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
  Usage: `/autosell <on/off>`
   - Toggles autosell.
 
 Alias: `/as <on/off>`
 
# Config.yml
  You will be able to set a world's in which AutoSell only works on.
  
 - worlds:
   - "world1"
   - "world2"
   - "world3"
  
  To set Block and their prices: 
 - BlockID: "price"
  - Example:
   - 1: "5"     // Block ID with price
   - 17:1: "30" // Block ID with Meta variant and it's price
 
 # Permissions
 Add `autosell.command` to the groups you want to give. 

 # Contact

 - Discord: MCA7#1245
