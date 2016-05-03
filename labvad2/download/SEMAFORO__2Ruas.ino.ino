//*****************************************************
// S E M A F A R O (2Ruas)  para Deficiente Visual    *
// By S.Brand�o                                       *
// 03/09//2012 - 1798 bytes                            *
// Esta experiencia usa o SEQUENCIALde LEDs. (2 Ruas) *
//*****************************************************

int LED_VD = 8;
int LED_AM = 7;
int LED_VM = 6;
int LED_VD2 = 12;
int LED_AM2 = 11;
int LED_VM2 = 10;
int Botao1 = 3;
int BUZZER = 5;   

int Timer1 = 100;
int Timer5 = 500;


void setup() 
{ 
  //pinMode(LED_BC, OUTPUT);  // LED BC 
  pinMode(LED_VD, OUTPUT);  // LED VD 
  pinMode(LED_AM, OUTPUT);  // LED AM 
  pinMode(LED_VM, OUTPUT);  // LED VM 
  pinMode(LED_VD2, OUTPUT);  // LED VD 
  pinMode(LED_AM2, OUTPUT);  // LED AM 
  pinMode(LED_VM2, OUTPUT);  // LED VM
  pinMode(Botao1, INPUT);   
  pinMode(BUZZER, OUTPUT);
} 

void loop() 
{
  // Funcionamento do Sem�faro

  // Acende a LUZ VERDE por 5 segundos
  digitalWrite(LED_VD,HIGH);
  digitalWrite(LED_VM2,HIGH);
  delay(Timer5);


  // LUZ AMARELA  pisca 10x300 = 3seg  
  for (int x=0; x<10; x++)
  {
    digitalWrite(LED_AM, HIGH);
    delay(150);
    digitalWrite(LED_AM, LOW);
    delay(150);
  }
  digitalWrite(LED_VM2,LOW);
  digitalWrite(LED_VD, LOW);
  // Acende a LUZ VERMELHA e soa o alarme por 10 x 2 x 250 segundos

  int state = digitalRead(Botao1); // Verifica se o bot�o/piso deficiente foi acionado.
  if (state == HIGH && (millis() > 200)) // Existe deficiente para atravessar.
  { 
    digitalWrite(LED_VM, HIGH);
    digitalWrite(LED_VD2,HIGH);
    for (int x=0; x<10; x++)
    {
      digitalWrite(BUZZER, HIGH);
      delay(150);
      digitalWrite(BUZZER, LOW);
      delay(150);
    }  

    digitalWrite(LED_VM, LOW);
    digitalWrite(LED_VD2,LOW);
  }
  else 
  {
    digitalWrite(LED_VM,HIGH);
    digitalWrite(LED_VD2,HIGH);
    delay(Timer5);
    // LUZ AMARELA  pisca 10x300 = 3seg  
    for (int x=0; x<10; x++)
    {
      digitalWrite(LED_AM2, HIGH);
      delay(150);
      digitalWrite(LED_AM2, LOW);
      delay(150);
      digitalWrite(LED_VD,LOW);    
    } 
    // RESETE GERAL

    digitalWrite(LED_VD, LOW);
    digitalWrite(LED_AM, LOW);
    digitalWrite(LED_VM, LOW);
    digitalWrite(LED_VD2, LOW);
    digitalWrite(LED_AM2, LOW);
    digitalWrite(LED_VM2, LOW);
    delay(150);
  }            
  // F I M
}