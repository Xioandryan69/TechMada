#!/bin/bash

# Définition du dossier source
SOURCE_DIR="$HOME/Documents/S4/Mr Rojo/Regime-alimentaire/src/app/Views"
# Fichier de destination final
OUTPUT_FILE="export_php_content.txt"

# On vide le fichier de destination s'il existe déjà
> "$OUTPUT_FILE"

echo "Début de la copie des fichiers .php..."

# Vérification si le dossier existe
if [ -d "$SOURCE_DIR" ]; then
    # Boucle sur tous les fichiers .php
    for file in "$SOURCE_DIR"/*.php; do
        # Vérifier si des fichiers existent pour éviter l'erreur de la boucle vide
        [ -e "$file" ] || continue
        
        # Extraire le nom du fichier uniquement
        filename=$(basename "$file")
        
        echo "Traitement de : $filename"
        
        # Ajouter le titre au fichier de destination
        echo "========================================" >> "$OUTPUT_FILE"
        echo "NOM DU FICHIER : $filename" >> "$OUTPUT_FILE"
        echo "========================================" >> "$OUTPUT_FILE"
        echo "" >> "$OUTPUT_FILE"
        
        # Ajouter le contenu du fichier .php
        cat "$file" >> "$OUTPUT_FILE"
        
        # Ajouter des sauts de ligne pour la clarté
        echo -e "\n\n" >> "$OUTPUT_FILE"
    done
    
    echo "Opération terminée. Tout a été copié dans : $OUTPUT_FILE"
else
    echo "Erreur : Le dossier $SOURCE_DIR n'existe pas."
fi