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

* "ccl" er CCL-søket du bruker for å finne frem de riktige bøkene (se [Bibliofil-håndbøkene om CCL](https://dok.bibsyst.no/web/m2/m2-int-sok.html#m2-ccl))

Bytt ut fila "bord.jpg" med logoen til ditt eget bibliotek.

## Bruk

En lenke direkte til denne siden vil vise utlånstoppen for fjoråret. Hvis du vil vise andre år kan du legge på årstallet bak (som om det var en undermappe).

Hvis, for eksempel, denne siden ligger på `https://example.com` blir linken for 2021 `https://example.com/2021`

Første gang du laster et nytt år kan det ta litt tid å laste siden. Innholdet blir cachet i `cache/`

Du kan refreshe cachen (f.eks. hvis du har krydret noe etter at det ble cachet) med å legge på 'reload'. Eks.: `https://example.com/2021/reload`