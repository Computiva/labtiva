//*****************************************************
// S E Q U E N C I A L  de LEDs c/ Parada por bot�o   *
// By S.Brand�o                                       *
// 09/01/2012 - 1398 bytes                            *
// Esta experiencia usa o SEQUENCIALde LEDs.          *
// * Projeto 5 do Livro ARDUINO                       *
//*****************************************************

byte ledPin[] = {  
  6,7,8,9,10,11,12,13}; // Array dos Leds

int  ledDelay(200);          // Intervalo enter as altera��es (mseg)
int  sentido = 1;    
int  LED_atual = 0;

unsigned long changeTime;

void setup()
{
    for (int x=0; x<8; x++)  //Define todos os pinos com sa�da
  {
    pinMode(ledPin[x], OUTPUT);
  }
  changeTime = millis();
  
}
void loop() 
{   
  if ((millis() - changeTime) > ledDelay) 
  { 
    changeLED();
    changeTime = millis();
  }  
}

void changeLED()
{
  for (int x=0; x<8; x++) // Apaga todos os LEDs
  { 
    digitalWrite(ledPin[x], LOW);
  }
  digitalWrite(ledPin[LED_atual], HIGH); // Acende o Led atual
  LED_atual += sentido; // incrementa de acordo com a dura��o
  // ALTERA a dire��o qundo chega ao final.
  if (LED_atual == 7) {
    sentido = -1;
  }
  if (LED_atual == 0) {
    sentido = 1;
  }
}      
// THE END*****************************************************