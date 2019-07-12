String ratio1;
String concentration1;
String dTemp1;
void setup() {
  // put your setup code here, to run once:
 Serial.begin(9600);
}

void loop() {
    ratio1="";
  concentration1="";
  dTemp1="";
  // put your main code here, to run repeatedly:
 char envoiVal[18]="12.34-78.45-24";
          Serial.println(envoiVal);
for (int i=0; i<5; i++){
 ratio1+=(String)envoiVal[i];
 }
for (int j=6; j<11; j++){
 concentration1+= (String)envoiVal[j];
 }
for (int k=12; k<14; k++){
 dTemp1+= (String)envoiVal[k];
 }
  String url="";
  url+="http://localhost/ajouter.php?dTemp="+dTemp1;
  url+="&ratio=";
  url+=String(ratio1);
  url+="&concentration=";
  url+=String(concentration1); 
  Serial.println("url=");
  Serial.println(url);
  delay(4000);
}
