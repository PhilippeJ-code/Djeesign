# Plugin Djeesign pour Jeedom

    Ce plugin permet de définir un design pour Jeedom et d'en changer facilement
    Le design s'applique au menu et aux cadres 

## 1. Configuration du plugin

    C'est dans la configuration du plugin et donc globalement que l'on choisit le type de design qui 
    sera appliqué aux équipements, les différents designs correspondent aux fichiers css qui sont placés
    dans Plugins\Djeesign\core\template\dashboard\

    Il suffit de placer un nouveau fichier css dans ce répertoire pour qu'il soit pris en compte dans 
    la configuration du plugin

## 2. Configuration des équipements

    Le premier choix à effectuer sera de choisir le type d'équipement que l'on désire créer. 
    
### 2.a. Cadre

        Cet équipement permet la création d'un cadre qui pourra être placé sur un design, on peut définir
        le titre du cadre ainsi que le nom d'une icone

### 2.b. Menu

        Cet équipement permet la création d'un menu qui pourra être placé sur un design, on peut définir 
        la liste des Ids de design qui seront automatiquement placés dans le menu ainsi qu'une liste de noms 
        d'icones correspondant à ces points de menu
