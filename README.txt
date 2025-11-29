Incident_Report_Portal - README

Körning:
Starta projektet genom att köra:
php -S localhost:8000

Öppna sedan http://localhost:8000 i webbläsaren.

Filer:
- index.php: Visar inloggningssidan och hanterar inloggningsförsök.
- register.php: Sida där en ny användare kan registrera sig.
- main.php: Huvudsida som endast är tillgänglig för inloggade användare.
- logout.php: Loggar ut användaren och avslutar sessionen.

includes:
- Här läggs filer med funktionalitet som kan nyttjas globalt i projektet. 
	EX: require_once "includes/session.php".

js: 
- Här läggs javascript funktionalitet.

sql:
- (init.sql) Initierar alla tables i databasen.
- (insert.sql) Populerar databasen med data.
- (views.sql) Vyer där vi kan testa så att våran databas är normaliserad och kan formattera datan korrekt.
	      Använd ej i era php queries. Men dra gärna inspiration.

git_tutorial:
- Enkel introduction till hur man använder git och GitHub.

auth:
- Här finns autentiserings funktionalitet samlad.

css:
- Här ska all css finnas sammlad.