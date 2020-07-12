# stagetest
# API:
laten we beginnen in de map van api. Run de command npm i in de command prompt.
er is ook een config.js beschikbaar in de folder config.
de eerste variable in de config die je te zien krijgt is de secretkey dit is de key die gebruikt wordt voor de JWT token.
de tweede variable is een status lock als een user niet de status admin heeft kan de admin functies zoals accept/deny order niet uitgevoerd worden.
Vervolgens krijgen we de variabelen voor de Neo4j server.
het is mogelijk om een eigen Neo4j server te gebruiken door deze variabelen te gebruiken, maar daarbij moet je wel zelf Nodes aanmaken voor User en Room.
User(let op de label is User) moet de volgende attributten hebben:"password, status en username (allemaal string)".
Room(let op de label is Roomr) heeft weer andere attributten namelijk price(float) roomnumber(int)

Als u klaar bent met de opzetten voor de config kan de api opgestart worden met npm start in de command prompt

# Client customer:
in de customer client moet er een paar dingen gedaan worden ten eerste moet de command "composer require laravel/cashier" uitgevoerd worden.
ten tweede moet de config ingesteld worden.
STRIPE_API_KEY is de secretkey van de stripe account
STRIPE_PUBLISHABLE_KEY is de publishable key van de stripe account
$base_api is de locatie waar de api op draait (voorbeeld als de api runt op localhost:3000 is de base_api localhost:3000)
$stripeconnect is de path naar de stripe-php/init.php file (in mijn geval was dat vendor/stripe/stripe-php/init.php)
voor de mail functie moet de values SMTP, smtp_port en sendmail_from ingevuld worden in de php.ini file.

# client reject/denied
voor het de system administrator is er een andere client opgezet.
bij deze client is er maar een config setting de base_api net als bij de customer client is dit de server adress van de api ('localhost:3000')
ook geef ik even een login voor de login page
username:ComplyNow
password:abcdef

# dingen die nog gepland waren.
sinds ik niet zeker ben dat ik zelf aanwezig kan zijn bij de bespreking maak ik deze onderdeel aan zodat de bedrijf ComplyNow weet wat mijn beweeg redenen zijn.

add list bij de administrator client met een overzicht van alle reservations. Technisch gezien heb ik all een lijst waarin de details staan van de reserveringen, maar ik had helaas een denkfout gemaakt tijdens de reserveren.
als een administrator een reservatie accept of deny wordt het uit de list gehaald waardoor het niet meer zichtbaar is. dit wil ik oplossen met een nieuwe lijst die dat dus niet doet.

opbouw website aanpassen. door een gebrek aan tijd wegens persoonlijke redenen had ik de focus gelegd op de php en api functionaliteit. hierdoor is de opbouw van de html pagina's niet aan de oorde gekomen (dus geen css en html voor opmaak).
mijn plan was om deze onderdeel later te maken door met bootstrap en andere onderdelen nog een keer langs de website pages te gaan.
