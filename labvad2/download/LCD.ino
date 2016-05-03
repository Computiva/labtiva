//*****************************************************
// GERADOR de CARACTERES - Display 2x16               *
// By S.BrandÃ¯Â¿Â½o                                       *
// 30/08/2012 - 3742 bytes                            *
// Este Sketch ÃƒÂ© para um DISPLAY de CARACTERES 4 X 20 *
//*****************************************************



#include <LiquidCrystal.h>

// LiquidCrystal display with:
// rs on pin 6
// rw on pin 7
// enable on pin 8
// d4, d5, d6, d7 on pins 9, 10, 11, 12
LiquidCrystal lcd(6, 7, 8, 9, 10, 11, 12);
int brightness = 255;


void setup()

 Serial.begin(9600); 

}

void loop()
{
  // Print a message to the LCD.
  lcd.clear();
  delay(500);
  lcd.begin (16, 2); // Define o tamanho do DISPLAY 
  lcd.print("Proj:UCA na CUCA");
  delay(1500);
  lcd.setCursor(0,1); // Escreve na Linha 2)
  lcd.print("CNPQ-N C E-UFRJ");
 brightness = 100;
  delay(1500);
  // Se o DISPLAY for de 2 LINHAS as mensagens abaixo aparecem na LINHA 2
  // ********************************************************************
  lcd.setCursor(0,2); // Escreve na 3Ã¯Â¿Â½ linha (Linha 1)
  lcd.print("*** ***  *** ***");
  delay(1500);
  lcd.setCursor(0,3); // Escreve na 4Ã¯Â¿Â½ linha (Linha 1)
  lcd.print("LABVAD__2014");
  delay(1500);
}//*****************************************************
// GERADOR de CARACTERES - Display 2x16               *
// By S.BrandÃ¯Â¿Â½o                                       *
// 30/08/2012 - 3742 bytes                            *
// Este Sketch ÃƒÂ© para um DISPLAY de CARACTERES 4 X 20 *
//*****************************************************



#include <LiquidCrystal.h>

// LiquidCrystal display with:
// rs on pin 6
// rw on pin 7
// enable on pin 8
// d4, d5, d6, d7 on pins 9, 10, 11, 12
LiquidCrystal lcd(6, 7, 8, 9, 10, 11, 12);
int brightness = 255;


void setup()

 Serial.begin(9600); 

}

void loop()
{
  // Print a message to the LCD.
  lcd.clear();
  delay(500);
  lcd.begin (16, 2); // Define o tamanho do DISPLAY 
  lcd.print("Proj:UCA na CUCA");
  delay(1500);
  lcd.setCursor(0,1); // Escreve na Linha 2)
  lcd.print("CNPQ-N C E-UFRJ");
 brightness = 100;
  delay(1500);
  // Se o DISPLAY for de 2 LINHAS as mensagens abaixo aparecem na LINHA 2
  // ********************************************************************
  lcd.setCursor(0,2); // Escreve na 3Ã¯Â¿Â½ linha (Linha 1)
  lcd.print("*** ***  *** ***");
  delay(1500);
  lcd.setCursor(0,3); // Escreve na 4Ã¯Â¿Â½ linha (Linha 1)
  lcd.print("LABVAD__2014");
  delay(1500);
}