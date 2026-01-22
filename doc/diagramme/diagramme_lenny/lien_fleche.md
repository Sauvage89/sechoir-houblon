# Documentaion sur le sens des fleche d'un diagramme d'exigeance

## Sense of
	But : exprimer le **sens ou l’intention générale** d’une exigence par rapport à un besoin métier.  
	Usage : relie l’exigence à un concept ou un objectif global qu’elle soutient.  

	Exemple :  
		Exigence : « Le système doit gérer les utilisateurs »  
		Sense Of :
			« Faciliter la gestion des utilisateurs pour l’administration »  

	Remarque : ce lien permet de clarifier **pourquoi l’exigence existe**.

## Derive
	But : montrer qu’une exigence **découle d’une autre exigence**.  
	Usage : tu as une exigence de haut niveau et tu crées une exigence plus spécifique qui est nécessaire pour la réaliser.

	Exemple :  
		Exigence générale : « Déployer un serveur BDD »  
		Derive : 
			« standardiser la BDD »  

	Remarque : le lien Derive indique une **relation de dépendance obligatoire** pour satisfaire l’exigence initiale.
	Remarque personnel : le lien Derive est un peu comme si qu'on rajouter du travail sur une tache

## Satisfy
	But : relier une exigence à **l’élément du système qui la met en œuvre**.  
	Usage : montre quel composant, module ou fonctionnalité satisfait l’exigence.  
	
	Exemple :
		Exigence : « Le système doit envoyer un e-mail de confirmation »  
		Satisfy : 
			« Module d’envoi d’e-mails »  

	Remarque : Satisfy = **implémentation de l’exigence**.

## Verify
	But : relier une exigence à **la méthode ou le test qui permet de vérifier sa conformité**.  
	Usage : montre comment prouver qu’une exigence est remplie.

	Exemple :  
		Exigence : « Le système doit envoyer un e-mail de confirmation »  
		Verify : 
			« Vérifier qu’un e-mail est bien envoyé après l’inscription »  

	Remarque : Verify = **validation de l’exigence**.

## Refine
	But : préciser ou détailler une exigence.  
	Usage : tu as une exigence générale et tu veux la décomposer en sous-exigences plus détaillées.  

	Exemple :  
		Exigence générale : « Le site doit gérer les utilisateurs »  
		Refine :  
			« Le site doit permettre la création de compte »  
			« Le site doit permettre la suppression de compte »  

	Remarque : le lien refine va **du général vers le détail**.
	Remarque personnel : le lien donne des fonctionnalité a satisfaire en plus.

## Trace
	But : relier une exigence à **une autre exigence ou un élément lié pour le suivi**.  
	Usage : permet de suivre l’origine et l’évolution d’une exigence.  

	Exemple :  
		Exigence : « Le système doit être disponible 24h/24 »
		Trace : 
			« Disponibilité du système »  

	Remarque : Trace = **relation de suivi ou traçabilité**, sans lien de dépendance directe.

## Dependency
	But : montrer qu’une exigence **dépend d’une autre** pour être réalisée.  
	Usage : utile pour gérer l’ordre de mise en œuvre ou les relations entre exigences.  

	Exemple :  
		Exigence : « Implémenter le module de paiement »  
		Dependency : 
			« Avoir un compte utilisateur actif »  

	Remarque : Dependency = **relation de dépendance directe**.

## Composition
	But : montrer qu’une exigence est **composée de plusieurs sous-parties**.  
	Usage : décomposer une exigence complexe en éléments plus simples.

	Exemple :  
		Exigence : « Gérer la session de séchage »  
		Composition :
			« Définir la température », « Définir la durée », « Définir l’humidité »  

	Remarque : Composition = **structure hiérarchique**.

## Containment
	But : indiquer qu’une exigence **contient ou englobe une autre exigence**.  
	Usage : montrer qu’une exigence inclut plusieurs aspects ou sous-exigences.  

	Exemple :
		Exigence : « Assurer la sécurité des données »  
		Containment :
			« Chiffrement des mots de passe », « Connexion HTTPS »  

	Remarque : Containment = **relation de hiérarchie ou inclusion**, proche de Composition mais plus large.