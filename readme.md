# IFX Payments Task 
## Wymagania
Konto bankowe:

- [x]  konto ma swoją walutę
-  [x]  na konto można przyjmować pieniądzie (credit) oraz wysyłać z niego pieniądze (debit) tylko w takiej samej walucie jaką ma konto - przy dodawaniu pieniędzy typ transakcji to credit a przy wysyłaniu to debit
- [x]  Konto ma swój balans wynikający z wykonanych na nim operacji credit i debit
- [x] Każda płatność wychodząca (debit) musi być powiększona o koszty transakcji 0,5%
- [x]  z konta bankowego można wysłać pieniądze tylko jeżeli kwota płatności (powiększona o koszt transakcji) mieści się w dostępnym balansie
- [x]  Konto bankowe zezwala na zrobienie maksymalnie 3 płatności wychodzących 1 dnia

Płatność:
- [x]  Zawiera kwotę oraz walutę

## Wygląd i działanie
Dodanie pieniędzy
![](https://i.imgur.com/4qWXzmm.jpg) 

Dodanie pieniędzy, ale w złej walucie niż w koncie
![](https://i.imgur.com/5wYNGGk.jpg)

Wysłanie pieniędzy, ale kwota transferu (amount+transaction_amount) jest większa niż balans na koncie
![](https://i.imgur.com/nSeAW5i.jpg)

Poprawne dodanie pieniędzy na konto
![](https://i.imgur.com/09JFQdN.jpg)

Poprawne wykonanie płatności
![](https://i.imgur.com/bMBeohm.jpg)


## Testowanie
![](https://i.imgur.com/KiJCjq7.jpg)
- [x]  Stworzony Mockowy obiekt.

Testuję jednostkowo czy metody obiektu otrzymują poprawne parametry. Przy zwiększeniu complexity projektu byłoby to bardzo przydatne do walidacji arrays lub obiektów (wtedy użyłbym Dependency Injection w testach), natomiast dla celów tego prostego zadania do metod obiektu podawane są zmienne (np. info konta podawane do sendMoney() to INT z numerem konta a nie Obiekt Account).

Jako, że na potrzeby uproszczenia mojego projektu powstała tylko jedna klasa (`Bank`) to mockuję tylko jedną klasę w testach. Przy rozwoju aplikacji na pewno stworzyłbym instację w metodzie setUp() testu (patrz "Dalsze kroki").

## Dalsze kroki
Dodałbym testowanie exceptions wyrzucanych przy sendMoney() [tak jak tutaj](https://phpunit.readthedocs.io/en/10.0/writing-tests-for-phpunit.html?highlight=exception#expecting-exceptions)

Stworzyłbym kopię bazy danych (np. `bank_clone`). Testy jednostkowe wysyłałyby zapytania do kopii bazy danych w celu walidacji warunków metod.

Obiekty takie jak Account(), User() itd. przy testowaniu tworzyłbym w setUp() które wywołane zostaje przed każdym testem:
```php
private $bankMock;

protected function setUp(): void
{
     $this->bank = new Bank();
}
```
