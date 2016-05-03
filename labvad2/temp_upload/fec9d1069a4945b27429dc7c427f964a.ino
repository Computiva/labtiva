//*****************************************************
// A R C O  I R I S  WEB - Geração de Cores           *
// By S.Brandão                                       *
// 25/10/2012 - 1750 bytes                            *
// Esta experiencia usa o LED RGB - Grupo Miscelanea  *
//*****************************************************

#include "labvad.h"

int timer  = 1000;

void setup()
{   
  pinMode(RGB_VM, OUTPUT); 
  pinMode(RGB_VD, OUTPUT);  
  pinMode(RGB_AZ, OUTPUT); 
}

void loop()
{ 
    
  for (int vm=0; vm<=255; vm+=125) {
    for (int az=0; az<=255; az+=125) {
      for (int vd=0; vd<=255; vd+=125) {
        analogWrite(RGB_VM, vm);  
        analogWrite(RGB_AZ, az);  
        analogWrite(RGB_VD, vd);   
        delay(timer);
      }
    }
  }
  
}  

//_________________________________________________________
// THE END