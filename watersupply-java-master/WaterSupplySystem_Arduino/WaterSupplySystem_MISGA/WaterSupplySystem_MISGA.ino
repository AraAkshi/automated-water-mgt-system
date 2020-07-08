#include <Servo.h>

Servo ax,bx,cx,dx,ex,fx,gx,hx,ix,jx;    
char buffer[3];

void setup() {
  analogWrite(A0,255);
  analogWrite(A1,0);

  ax.attach(2);  //Servo config
  bx.attach(3);
  cx.attach(4);
  dx.attach(5);
  ex.attach(6);
  fx.attach(7);
  gx.attach(8);
  hx.attach(9);
  ix.attach(10);
  jx.attach(11);
  
  ax.write(0);
  bx.write(0);
  cx.write(0);
  dx.write(0);
  ex.write(0);
  fx.write(0);
  gx.write(0);
  hx.write(0);
  ix.write(0);
  jx.write(0);

  for(int n=22; n<53; n=n+2){
      pinMode(n,OUTPUT);
      pinMode(n+1,OUTPUT);
      digitalWrite(n,HIGH);
      digitalWrite(n+1,LOW);
  }
  
  Serial.begin(9600);
}

void loop() {
  //Serial.println(analogRead(A3));
  int xx = map(analogRead(A3),170,400,100,0);    //Get Moisture Level
  if(xx>100){
      xx=100;
  }else if(xx<0){
      xx=0;
  }
  Serial.print("m1_ ");
  Serial.print(xx);
  Serial.write("\n");
  
  if(Serial.available()>0){
    char x= Serial.read();
    if(x == 'a'){
        int ang=getAngle();
        ax.write(ang);
        Serial.println("A passed");
    }else if(x == 'b'){
        int ang=getAngle();;
        bx.write(ang);
        Serial.println("B passed");
    }else if(x == 'c'){
        int ang=getAngle();
        cx.write(ang);
        Serial.println("C passed");
    }else if(x == 'd'){
        int ang=getAngle();
        dx.write(ang);
        Serial.println("D passed");
    }else if(x == 'e'){
        int ang=getAngle();
        ex.write(ang);
        Serial.println("E passed");
    }else if(x == 'f'){
        int ang=getAngle();
        fx.write(ang);
        Serial.println("F passed");
    }else if(x == 'g'){
        int ang=getAngle();
        gx.write(ang);
        Serial.println("G passed");
    }else if(x == 'h'){
        int ang=getAngle();
        hx.write(ang);
        Serial.println("H passed");
    }else if(x == 'i'){
        int ang=getAngle();
        ix.write(ang);
        Serial.println("I passed");
    }else if(x == 'j'){
        int ang=getAngle();
        jx.write(ang);
        Serial.println("J passed");
    }
  }
  delay(500);
}

int getAngle(){
    int tot=0;
    if(Serial.available()>=3){
        for(int i=0;i<3;i++){
          buffer[i]=Serial.read();
        }
         for(int x=0,y=100;x<3;x++){
          int val=(int)buffer[x];
          tot = tot+(val-48)*y;
          y = y/10;
         }
    }
    Serial.flush();
    
    if(tot>180){
      tot = 180;
    }
    if(tot<0){
      tot = 0;
    }
    return tot;
}
