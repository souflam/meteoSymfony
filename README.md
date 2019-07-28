# Meteo Symfony 4
Encapsulation de consomation d'un web service yahoo weather dans graphql en utilisant symfony 4 et en servant de docker.
##### Les taches:
1. ~~mplemente the microservice using Symfony 4~~
2. ~~Expose the API endpoint with GraphQL.~~
3. ~~Input: City Output: One day weather prediction~~
4. ~~Use Yahoo Weather API https://developer.yahoo.com/weather/~~
5. ~~Use of Git in your versioning is mandatory.~~
6. ~~Bonus: use docker.~~
7. ~~Bonus: cache the weather API results.~~
8. ~~Bonus: markdown installation guideline.~~
## installation
premierement telecharger le contenu de repository sur votre machine local

`$ git clone https://github.com/souflam/meteoSymfony.git`

Une fois le télechargement est terminé, vous devez installer les dépendances du projet symfony en se rendant sur le dossier symfony et en exécutant la commande ci-dessous:

`composer install`

Ensuite essayer d'executer la commande suivante pour télécharger les images docker et demarrer les containers docker:

`$ docker-compose up`

vous devez aussi mentionner l'url suivant sur votre fichier host:

`symfony.localhost`

pour tester le projet merci d'accéder sur cette page "http://symfony.localhost/graphiql" et y executer la Query ci-dessous:

    query{
      meteo(ville: "rabat"){
        textMeteo,
        minTemperature,
        maxTemperature,
        ville
      }
    }
## How it works?
1. Le client fait appel a des doonées via la query ci-dessus.
2. Derriere cet appel, le serveur attaque l'api de yahoo en utilisant guzzle , l'authentification à ce service se fait via OAuth1
3. Une fois l'info est récupereé "Guzzle cache" stock l'info récuperer durant 60 min pour s'en servir pour les prochaines request et comme ça on peut bénificier de 2 choses:
	-Reduire le nombre des requetes envoyé a yahoo pour ne pas gaspiller le quota dédié à notre compte.
	garantir que notre service marche bien même si le service yahoo aura une coupure pendant un temps donné


