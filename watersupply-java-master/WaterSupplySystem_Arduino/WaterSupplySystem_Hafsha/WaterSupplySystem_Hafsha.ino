#include <Servo.h>
//#include <dht.h>

//dht DHT;


#define SERVO_1_PIN 9
#define SERVO_2_PIN 11
#define MOISTURE_PIN A2
#define RAIN_PIN A5
//#define TEMPR_PIN 4

Servo ax, bx;
char buffer[3];

void setup() {
  analogWrite(A0, 255);
  analogWrite(A1, 0);
  analogWrite(A3, 255);
  analogWrite(A4, 0);

  ax.attach(SERVO_1_PIN);  //Servo config
  bx.attach(SERVO_2_PIN);

  ax.write(0);
  bx.write(0);

  byte servo1_vcc = 8;
  byte servo1_gnd = 7;
  byte servo2_vcc = 12;
  byte servo2_gnd = 13;
  byte dht_vcc = 5;
  byte dht_gnd = 2;

  pinMode(servo1_vcc, OUTPUT);
  pinMode(servo1_gnd, OUTPUT);
  pinMode(servo2_vcc, OUTPUT);
  pinMode(servo2_gnd, OUTPUT);
  pinMode(dht_vcc, OUTPUT);
  pinMode(dht_gnd, OUTPUT);

  digitalWrite(servo1_vcc, HIGH);
  digitalWrite(servo1_gnd, LOW);
  digitalWrite(servo2_vcc, HIGH);
  digitalWrite(servo2_gnd, LOW);
  digitalWrite(dht_vcc, HIGH);
  digitalWrite(dht_gnd, LOW);

  Serial.begin(9600);
}

void loop() {

  sendSensorValues();
  if (Serial.available() > 0) {
    char x = Serial.read();
    if (x == 'a') {
      int ang = getAngle();
      ax.write(ang);
      Serial.println("A passed");
    } else if (x == 'b') {
      int ang = getAngle();
      bx.write(ang);
      Serial.println("B passed");
    }
  }
  delay(500);
}

int getAngle() {
  int tot = 0;
  if (Serial.available() >= 3) {
    for (int i = 0; i < 3; i++) {
      buffer[i] = Serial.read();
    }
    for (int x = 0, y = 100; x < 3; x++) {
      int val = (int)buffer[x];
      tot = tot + (val - 48) * y;
      y = y / 10;
    }
  }
  Serial.flush();

  if (tot > 180) {
    tot = 180;
  }
  if (tot < 0) {
    tot = 0;
  }
  return tot;
}

void sendSensorValues() {
  //Serial.println(analogRead(A3));
  String moisture = getMoistureReading();
  String rain = getRainReading();
  //String tempr = getTemperatureReading();
  String sensorVals = String("svl::"+moisture+"::"+rain);//+"::"+tempr);

  Serial.println(sensorVals);
  //Serial.write("\n");
}

String getMoistureReading() {
  int val = map(analogRead(MOISTURE_PIN), 0, 1023, 100, 0); //Get Moisture Level
  if (val > 100) {
    val = 100;
  } else if (val < 0) {
    val = 0;
  }
  String sValue = String("mois_"+String(val,10));     
  return sValue;
}

String getRainReading() {
  int val = map(analogRead(RAIN_PIN), 0, 1023, 0, 3); //Get Rain Status
  // 0: Sensor getting wet - flood
  // 1: Sensor getting wet - Rain Warning
  // 2: Sensor dry - Not Raining
  
  String sValue = String("rain_"+String(val,10));
  return sValue;
}
//String tmpr = "28.00";
//String hmdt = "72";
//String getTemperatureReading() {
//  int chk = DHT.read11(TEMPR_PIN);
//  if(DHT.temperature >= 0){
//    tmpr = String(DHT.temperature,1); //with 1 decimal places
//  }
//  if(DHT.humidity >= 0){
//    hmdt = String(DHT.humidity,1);
//  }
//  String sValue = String("temp_"+tmpr+"::humi_"+hmdt);
//  return sValue;
//}
