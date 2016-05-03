void setup() {                

  Serial.begin(9600);//initialize the serial port
}
void(* resetFunc) (void) = 0; //declare reset function @ address 0


// the loop routine runs over and over again forever:
void loop() {

  Serial.println("on");
  delay(1000);               // wait for a second

  Serial.println("off");
  delay(1000);               // wait for a second
  Serial.println("resetting");
  resetFunc();  //call reset
  
  delay(100);
  Serial.println("never happens");
}