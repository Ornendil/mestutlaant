# Utlånstoppen

Viser de ti mest utlånte titlene i forskjellige kategorier

![Screenshot](https://github.com/Ornendil/mestutlaant/blob/master/screenshot.webp)

## Installere

1. Kopier filene til serveren din. Hvis du har gh installert på serveren din kan du gjøre det ved å kjøre denne kommandoen:

        gh repo clone Ornendil/mestutlaant

    Det legger filene i mappen mestutlaant under din nåværende plassering.

2. Gå til den nye mappen

        cd mestutlaant

3. Installer dependencies

        composer install

## Oppsett

I filen config.yaml setter du innstillingene for ditt bibliotek under 'bibliotek' og under 'lister' setter du data for hver inndeling du vil ha med på siden din. For eksempel:

    voksen:
        tittel: De ti mest lånte bøkene for voksne
        ccl: >-
            (ff=l) og (bn=voksen)

* "ccl" er CCL-søker du bruker for å finne frem de riktige bøkene (se [Bibliofil-håndbøkene om CCL](https://dok.bibsyst.no/web/m2/m2-int-sok.html#m2-ccl))

Bytt ut fila "bord.jpg" med logoen til ditt eget bibliotek.