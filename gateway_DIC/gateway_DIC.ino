
 /**  Semtech SX1272 module managing with Arduino
 *
 *  Copyright (C) 2014 Libelium Comunicaciones Distribuidas S.L.
 *  http://www.libelium.com
 *
 *  This program is free software: you can redistribute it and/or modify
 *  it under the terms of the GNU Lesser General Public License as published by
 *  the Free Software Foundation, either version 2.1 of the License, or
 *  (at your option) any later version.

 *  This program is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU Lesser General Public License for more details.

 *  You should have received a copy of the GNU Lesser General Public License
 *  along with th…*/
 //Include required lib so Arduino can talk with the Lora Shield
#include <SPI.h>
#include <RH_RF95.h>
#include <HttpClient.h>

//Include required lib so Arduino can communicate with Yun Shield
#include <FileIO.h>
#include <Console.h>

// Singleton instance of the radio driver
RH_RF95 rf95;
int led = 4;
int reset_lora = 9;
String dataString = "";

float frequency = 868.0;

void setup() 
{
  pinMode(led, OUTPUT); 
  pinMode(reset_lora, OUTPUT);     
  Bridge.begin(115200);
  Console.begin();
  FileSystem.begin();

  // reset lora module first. to make sure it will works properly
  digitalWrite(reset_lora, LOW);   
  delay(1000);
  digitalWrite(reset_lora, HIGH); 
  
  //while(!Console);  // wait for Console port to connect.
  //Console.println("Log remote sensor data to USB flash\n");

  if (!rf95.init())
    Console.println("init failed");  
  // Defaults after init are 434.0MHz, 13dBm, Bw = 125 kHz, Cr = 4/5, Sf = 128chips/symbol, CRC on
  // Need to change to 868.0Mhz in RH_RF95.cpp 
  //rf95.setFrequency(frequency);
}


void loop()
{
  dataString="";
  if (rf95.waitAvailableTimeout(3000))
  {
    Console.println("Get new message");
    // Should be a message for us now   
    uint8_t buf[RH_RF95_MAX_MESSAGE_LEN];
    uint8_t len = sizeof(buf);
    if (rf95.recv(buf, &len))
    {
      digitalWrite(led, HIGH);
      //RH_RF95::printBuffer("request: ", buf, len);
      Console.print("got message: ");
      Console.println((char*)buf);
      Console.print("RSSI: ");
      Console.println(rf95.lastRssi(), DEC);

      //make a string that start with a timestamp for assembling the data to log:
//      dataString += getTimeStamp();
     // dataString += "  :  ";
     // dataString += String((char*)buf);

     // Console.print("donnees: ");
    //  Console.print(dataString);

        ///////////////////////Envoi des données via HTTP///////////////////////////////////

   HttpClient client;   

    Serial.println("connected");
  client.println("post /HTTP/1.1");
  client.println("Host: 192.168.43.50:1880/donnees");
  client.println("User-Agent: Arduino/1.0");
  client.println("Connection: close");
  client.println("content-type: application/json");
  client.print("Content-Length: ");
  client.println(dataString.length());
  client.println();
  client.println(dataString);
 
  

   }
 
 /////////////////////////////////////////Fin partie htpp////////////////////////

      

 ///////////////////////////Stockage des données dans un fichier du systeme/////////////////////////////////////

 /////On doit s'assurer que le fichier csv existe////////////
      File dataFile = FileSystem.open("/var/sauvegarde.csv", FILE_APPEND);

      // if the file is available, write to it:
      if (dataFile) {
        dataFile.println(dataString);
        dataFile.close();
        // print to the serial port too:
        Console.println(dataString);
        Console.println("");
      }  
       // if the file isn't open, pop up an error:
      else 
      {
        Console.println("error opening datalog.csv");
      } 
      digitalWrite(led, LOW);      
    }
    else
    {
      Console.println("recv failed");
    }

    delay(1000);
  }


/////////////////////////////////////Recuperation de l'horodatage/////////////////////////////////////////
String getTimeStamp() {
  String result;
  Process time;
  // date is a command line utility to get the date and the time 
  // in different formats depending on the additional parameter 
  time.begin("date");
  time.addParameter("+%D-%T");  // parameters: D for the complete date mm/dd/yy
                                //             T for the time hh:mm:ss    
  time.run();  // run the command

  // read the output of the command
  while(time.available()>0) {
    char c = time.read();
    if(c != '\n')
      result += c;
  }
  return result;
}
