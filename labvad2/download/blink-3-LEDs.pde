/*
  Blink
  Aciona de forma alternada 2 LEDs repetidamente.
 
  This example code is in the public domain.
 */
 
int led1 = 13;
int led2 = 12;

// the setup routine runs once when you press reset

void setup()
{
 
  
  // initialize the digital pin as an output.
  pinMode(ledo1, OUTPUT);     
  pinMode(led2, OUTPUT);   
pinMode(11,OUTPUT);  

}
// the loop routine runs over and over again forever:
void loop() {
  digitalWrite(led1, HIGH);   // turn the LED on (HIGH is the voltage level)
  delay(100);               // wait for a second
  digitalWrite(led1, LOW);    // turn the LED off by making the voltage LOW
  delay(100);               // wait for a second

  digitalWrite(led2, HIGH);   // turn the LED on (HIGH is the voltage level)
  delay(100);               // wait for a second
  digitalWrite(led2, LOW);    // turn the LED off by making the voltage LOW
  delay(100); 

digitalWrite(11, HIGH);   // turn the LED on (HIGH is the voltage level)
  delay(100);               // wait for a second
  digitalWrite(11, LOW);    // turn the LED off by making the voltage LOW
  delay(100); 
}/*
  Blink
  Aciona de forma alternada 2 LEDs repetidamente.
 
  This example code is in the public domain.
 */
 
int led1 = 13;
int led2 = 12;

// the setup routine runs once when you press reset

void setup()
{
 
  
  // initialize the digital pin as an output.
  pinMode(ledo1, OUTPUT);     
  pinMode(led2, OUTPUT);   
pinMode(11,OUTPUT);  

}
// the loop routine runs over and over again forever:
void loop() {
  digitalWrite(led1, HIGH);   // turn the LED on (HIGH is the voltage level)
  delay(100);               // wait for a second
  digitalWrite(led1, LOW);    // turn the LED off by making the voltage LOW
  delay(100);               // wait for a second

  digitalWrite(led2, HIGH);   // turn the LED on (HIGH is the voltage level)
  delay(100);               // wait for a second
  digitalWrite(led2, LOW);    // turn the LED off by making the voltage LOW
  delay(100); 

digitalWrite(11, HIGH);   // turn the LED on (HIGH is the voltage level)
  delay(100);               // wait for a second
  digitalWrite(11, LOW);    // turn the LED off by making the voltage LOW
  delay(100); 
}