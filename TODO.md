- [ ] Brakuje opcji dodawania menu i dodawania linków do menu
- [ ] Brakuje opcji sidebaru czy widgetów gdzie można byłoby przeciągnąć czy dodać np. kartę "about"
- [x] obsługa tagów
- [ ] dodać pole last_seen w tabeli users. last_seen zapisywałbym przy logowaniu a potem przy przejściu na jakąś stronę czy coś - za każdym razem jak user wykona jakąś akcję. Preferuję last_seen, bo wydaje mi się bardziej czytelne. Kolumny w moich bazach SQL wszystkie mają taki zapis nazwy np. first_name, last_name, last_visit
- [ ] do navigation jeszcze dodałbym "order" żebym mógł wybrać które linki mają być w jakiej kolejności. Potem na frontendzie drag&drop do wyboru kolejności.
- [x] Pole aby można było wygasić jakiś link w nawigacji, ale nie koniecznie
  usuwać? np. isActive albo isVisible default bool = true
- [ ] Dodałbym możliwość łatwiejszego linkowania postów/stron żeby automatycznie ich URL był pobierany.
- [ ] Brakuje opcji żebym mógł usunąć swój komentarz
- [ ] Opcja, której mi brakuje to ułatwienie dodawania wewnętrznych linków (post/page) do nawigacji. Chciałbym móc dodać do nawigacji post "jak zarobić pierwszy milion - historia sukcesu książki 101 technik samogwałtu" i żebym nie musiał wpisywać linku http://domain.tld/jak-zarobic-pierwszy-milion itd tylko żeby skrypt już wiedział jaki jest bezpośredni adres tego posta i sam linkował do niego