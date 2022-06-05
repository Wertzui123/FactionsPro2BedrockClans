# *FactionsPro* to *BedrockClans*
This is a simple script that automatically converts your old FactionsPro database to a BedrockClans compatible one.
<br>In other words, if you want to keep your data when moving from FactionsPro to BedrockClans, you can use this program.

## How to use?
Just run this script in a terminal:
```
php converter.php
```
It assumes your FactionsPro database is located in the `in` directory and called `FactionsPro.db`.
<br>The BedrockClans files will be put in the `out` directory (which is automatically created if it doesn't exist).

## What is not converted/changed?
Some features of FactionsPro do not exist in BedrockClans and vice versa.
<br>These include (on the FactionsPro side):
* Alliances
* Allies
* Enemies
* MOTDs
* Plots
* Strength

And on the BedrockClans side:
* Bank balance and withdraw cooldowns
* Clan colors

Furthermore, clan invitations are not converted.

Note that the `Officer` rank in FactionsPro is converted to the `VIM` rank in BedrockClans, while leaders and members stay the same.