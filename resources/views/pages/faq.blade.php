@extends('layouts.app')

@section('content')
<section class="faq-content">
    <div class="container">
        <h2>FAQs zum PROJEKTKONTO</h2>
        <h3>Welche Zahlungsart wird empfohlen?</h3>
        <p>Wir empfehlen Dir die Bezahlung per PayPal. Zum Einen hast Du die Möglichkeit ein Abo abzuschließen, welches monatlich kündbar ist. Zum Anderen wird dein Konto sofort freigeschaltet. Du kannst bei uns alternativ per Banküberweisung bezahlen.  Hier können wir Dein Konto jedoch erst freischalten, wenn das Geld auf unserem Konto ist.</p>
        <h3>Ich habe ein Problem mit der Plattform oder einem anderen Nutzer. Was kann ich tun?</h3>
        <p>Wir möchten Dich bitten uns zu kontaktieren, wann immer Du unzufrieden mit der Plattform oder deren Nutzer bist. Wir haben großes Interesse daran, diese Probleme zu lösen. Eingeschränkte Funktionen und Bugs lassen sich gerade in der Anfangsphase, leider nur sehr schwer vermeiden. Wir sind aber laufend dran uns für Euch zu verbessern und freuen uns über jeden Tipp. Kontaktiere uns gerne über <a href="mailto:all@setbakers.de">all@setbakers.de</a> </p>

        <h2>FAQs zum FUNDUSKONTO</h2>
        <h3>Was kann ich auf SetBakers anbieten?</h3>
        <p>Theoretisch gibt es beim Film nichts, was nicht gebraucht wird. Jedoch macht es keinen Sinn, Dinge anzubieten, die es im nächsten Supermarkt für wenig Geld zu kaufen gibt. Leihen lohnt sich für Filmproduktionen dann, wenn der Artikel entweder selten, historisch, speziell für Filmzwecke (um)gebaut oder teuer in der Anschaffung ist. </p>
        <h3>Verdient SetBakers an jedem Artikel den ich verleihe?</h3>
        <p>Nein, außer dem monatlichen Beitrag entstehen keine Kosten. </p>
        <h3>Sind meine Artikel versichert?</h3>
        <p>In der Regel, schließt jede Filmproduktion eine Requisitenversicherung ab. Die Gegenstände sind somit über den Leihnehmer versichert. Wenn Du unsicher bist, lass dir am besten vom Leihnehmer bestätigen, dass eine solche Versicherung besteht. Da SetBakers nicht in die jeweiligen Leihverträge eingebunden ist, können wir hierfür keine Versicherung anbieten.</p>
        <h3>Die Kategorie für meinen Artikel oder meine Dienstleistung existiert nicht. Was soll ich tun?</h3>
        <p>Keine Panik. Schreibe uns einfach eine kurze E-Mail an all@setbakers.de und wir prüfen, ob wir die Kategorie mit aufnehmen.</p>
        <h3>Wie kann ich mich als Dienstleister bei SetBakers listen?</h3>
        <p>Erstelle Dir zuerst ein kostenloses Funduskonto. Wenn Du mehr als eine Dienstleistung anbietest, empfehlen wir diese auch getrennt voneinander anzulegen. So finden Dich Deine Kunden leichter. Falls Du neue Talente in dir entdecks, kannst Du diese jederzeit hinzufügen. Übrigens, kannst Du natürlich zusätzlich Artikel, Fahrzeuge oder Grafiken anbieten.</p>
        <h3>Warum ist die Herstellungszeit/Baujahr meines Artikels ein Pflichtfeld?</h3>
        <p>Ziel von SetBakers ist es durch Filtern, schnelle und präzise Ergebnisse angezeigt zu bekommen. Die Zeitangabe erspart Deinen Kunden zeitintensive Recherche.</p>
        <h3>Gibt es beim Upload von Fotos etwas zu beachten?</h3>
        <p>Bitte habe Verständnis, dass weder Logos, Wasserzeichen noch andere grafische Elemente in Dein Foto eingebettet sein dürfen. Eine Ausnahme bildet das Wasserzeichen von SetBakers. Wenn Du dieses nutzen möchtest, kannst Du es beim Einstellen deiner Artikel ganz einfach über die Auswahl der enstprechenden Checkbox hinzufügen. Bitte achte ebenfalls darauf, dass Deine Bilder scharf, nicht zu dunkel und idealerweise vor einem neutralen Hintergrund aufgenommen wurden.</p>
        <!--<p>Bitte habe Verständnis, dass es nicht gestattet ist Bilder mit zusätzlichen Informationen hochzuladen. Es dürfen weder Logos, Wasserzeichen noch andere grafische Elemente in Dein Foto eingebettet sein. Eine Ausnahme bildet das Wasserzeichen von SetBakers. Wenn Du dieses nutzen möchtest, kannst Du es <a href="{{ asset('assets/images/watermark.png') }}" download>HIER</a> herunterladen. Bitte achte ebenfalls darauf, dass Deine Bilder scharf, nicht zu dunkel und idealerweise vor einem neutralen Hintergrund aufgenommen wurden.</p>-->
        <h3>Ich habe einen SetBakers Fundus. Was mache ich, wenn ich einmal im Urlaub bin?</h3>
        <p>Kein Problem. Du kannst Deinen Fundus pausieren und später wieder online gehen. Deine Artikel werden dann nicht in der Suche angezeigt. Falls jemand Artikel von Dir in der Merkliste hat, werden diese als nicht verfügbar markiert.</p>
        <h3>Ich bin Grafiker und habe Bedenken, dass meine Grafiken bei anderen Projekten wieder genutzt werden.</h3>
        <p>Die Gefahr, dass Grafiken in anderen Produktionen wieder benutzt werden besteht natürlich immer. Sowohl bei SetBakers, als auch außerhalb. Wenn Du Deine Grafiken nicht als digitale Datei verschicken möchtest, hast du zwei Alternativen. Entweder du produzierst vor und versendest deine Grafiken per Post oder du gibts den Druckauftrag direkt an die Druckerei Deines Vertrauens. Dein Kunde kann diese dann dort abholen. Wir arbeiten derzeit an einer Möglichkeit, diesen Vorgang zu optimieren und können Dir hoffentlich bald eine noch einfachere Möglichkeit anbieten. Wir empfehlen Dir in jedem Fall, Deinem Kunden eine Nutzungslizenz zu übertragen, die sich nur auf das genannte Projekt beschränkt. Beispielsweise als Vermerk auf Deiner Rechnung.</p>
        <h3>Muss ich bei der Vermietung rechtliche Vereinbarungen treffen?</h3>
        <p>Es kann immer passieren, dass Schäden oder Verluste entstehen.  Dessen solltest Du Dir vor Verleih bewusst sein. Grundsätzlich empfiehlt es sich daher, einen Mietvertrag zu schließen um spätere Streitigkeiten zu vermeiden. Hierfür haben wir für Dich einen Mustermietvertrag erstellt. Diesen kannst Du nutzen und für deine Zwecke anpassen. Beachte, dass wir hierfür keine Haftung übernehmen und Du Dir im Zweifelsfall rechtlichen Rat einholen solltest.</p>
        <p><a href="#">Mustermietvertrag_Word</a> <br>
<a href="#">Mustermietvertrag_PDF</a></p>
<h3>Muss ich Steuern auf meine Einkünfte durch den Verleih bezahlen?</h3>
<p>Grundsätzlich wird unterschieden zwischen privaten Einkünften (vereinzelt und unregelmäßig), welche steuerfrei sind und gewerblichen  Einkünften (hoch und regelmäßig), welche versteuert werden müssen. Der Übergang ist hier jedoch nicht klar definiert und wird von Fall zu Fall vom Finanzamt unterschiedlich beurteilt. Daher können unsere Hinweise lediglich als Tipps und nicht als steuerliche Beratung gelten.  SetBakers ist vergleichbar mit anderen Verkaufs- und Kleinanzeigenplattformen (z.B. Ebay). Wenn Du beginnst regelmäßige und hohe Einkünfte zu erzielen, solltest Du über die Gründung eines (Klein-) Unternehmens nachdenken.</p>
    </div>
</section>
@endsection