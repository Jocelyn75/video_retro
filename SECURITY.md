# Les mesures de sécurité indispensable

## Le bloc de l'authentification
    - Sécuriser les formulaires d'inscription et de connexion en définissant les contraintes de validation (A faire soi même en fonction du besoin)
    - Failles de type CSRF (Déjà fait par symfony)
    - Failles de type XSS (Déjà fait par symfony)
    - Failles dues aux injections de code SQL (Déjà fait par symfony)
    - Failles dues aux sessions (Déjà fait par symfony)
    - Sécuriser les routes en mettant en place des middlewares (A faire soi même en fonction du besoin)
    - Email de chaque utilisateur doit être unkique pour éviter les doublons en utilisant la commande make:user (Déjà fait par symfony)