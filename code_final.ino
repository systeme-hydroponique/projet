bool niveau_suffisant = false;

//Définitions pour le réseau
const char* mqtt_server = "mqtt.ci-ciad.utbm.fr";
WiFiClient espClientHYDRO;
PubSubClient clientHYDRO(espClientHYDRO);

int val1 = 1;
int val2 = 2;
int val3 = 3;
int val4 = 4;
int val5 = 5;

//Fonctions pour recevoir les messages des mqtt out
void callback(char* topic, byte* payload, unsigned int length) {
  Serial.print("Message arrived [");
  Serial.print(topic);
  Serial.print("] ");

  String messageTemp;

  for (int i = 0; i < length; i++) {
    Serial.print((char)payload[i]);
    messageTemp += (char)payload[i];
  }

  Serial.println();
  Serial.print("Message reçu : ");
  Serial.println(messageTemp);

  //Allumer ou éteindre les LEDS
  if (strcmp(topic,"esp32/LED") == 0) {

    if (messageTemp == "allumer_leds") {
      for(int i=0; i<nbpixels; i++) {
        ledsallume = true;
        pixels.setPixelColor(i, pixels.Color(255, 255, 255));
        pixels.show();
      }
    }

    if (messageTemp == "eteindre_leds") {
      for(int i=0; i<nbpixels; i++) {
        ledsallume = false;
        pixels.setPixelColor(i, pixels.Color(0, 0, 0));
        pixels.show();
      }
    }
  }

  //Gérer l'intensité des LEDS 
  if (ledsallume){
      if (strcmp(topic, "esp32/intensiteled") == 0) {
        intensiteled = messageTemp.toInt() * 25.5;
        pixels.setBrightness(intensiteled);
      }
  }
  //Gérer la couleur des LEDS
  if (ledsallume){
    if (strcmp(topic, "esp32/couleur") == 0) {

    }
  }

  //Allumer ou éteindre l'écran
  if (strcmp(topic, "esp32/ECRAN") == 0) {
    if (messageTemp == "allumer_ecran") {
      Serial.println("116 OK");
      ecran.ssd1306_command(SSD1306_DISPLAYON);
      ecranallume = true;
      Serial.println("119 OK");

    } else if (messageTemp == "éteindre_ecran") {
      Serial.println("122 OK");
      ecran.ssd1306_command(SSD1306_DISPLAYOFF);
      ecranallume = false;
      Serial.println("125 OK");
    }
  }

  //Choisir les options sur le drop-down
  if (ecranallume) {
    Serial.println("136 OK");
    if (strcmp(topic,"esp32/quoiafficher") == 0) {
      Serial.println("138 OK");
      Serial.println("messageTemp");
      if (messageTemp == "1") {
        Serial.println("134 OK");
        option_selectionnee = "temperature";

      } else if (messageTemp == "2") {
        option_selectionnee = "humiditedusol";

      } else if (messageTemp == "3")  {
        option_selectionnee = "humiditeair";

      } else if (messageTemp == "4")  {
          option_selectionnee = "ensoleillement";

      } else if (messageTemp == "5"){
          option_selectionnee = "niveaueau";
      }
    }
  }
}


void setup(){
  Serial.begin(9600);
  
  Wire.begin(21,22);
  pixels.begin();
  pixels.setBrightness(15);
  if(!ecran.begin(SSD1306_SWITCHCAPVCC, 0x3C)) {
    Serial.println(F("SSD1306 allocation failed"));
    for(;;);
  } 
  //capteur niveau d'eau
  pinMode(WATER_SENSOR_PIN, INPUT);
  //capteur DHT11
  pinMode(PIN_DHT11, INPUT);
  dht.begin();
  setup_wifi();
  clientHYDRO.setServer(mqtt_server, 1883);
  clientHYDRO.setCallback(callback);

  lightMeter.begin(BH1750::CONTINUOUS_HIGH_RES_MODE);
  Serial.println(F("BH1750 Test begin"));
} 
  
void setup_wifi() {
  delay(10);
  Serial.println();
  Serial.print("Connexion à ");
  Serial.println(ssid); 
  WiFi.begin(ssid,password);
  while (WiFi.status() != WL_CONNECTED) {
    delay(500);
    Serial.print(".");
  }
  Serial.println("");
  Serial.println("WiFi connecté");
  Serial.println("addresse IP: ");
  Serial.println(WiFi.localIP());
}

void reconnect(){
  while (!clientHYDRO.connected()) {
    Serial.print("Essai de connexion à MQTT");
    if (clientHYDRO.connect("espClientHYDRO")){
      Serial.println("Connecté !");
      clientHYDRO.subscribe("esp32/LED");
      clientHYDRO.subscribe("esp32/intensiteled");
      clientHYDRO.subscribe("esp32/couleur");
      clientHYDRO.subscribe("esp32/ECRAN");
      clientHYDRO.subscribe("esp32/quoiafficher");
    } else {
        Serial.print("échec, rc=");
        Serial.print(clientHYDRO.state());
        Serial.println("réessayer dans 5 secondes");
        delay(5000);
    }
  }
}

long lastMsg = 0;

void loop(){

  //Afficher toutes les données sur le Serial Monitor

    //Luxmètre
    lux = lightMeter.readLightLevel();
    Serial.print("Ensoleillement : ");
    Serial.print(lux);
    Serial.println(" lux");

    //Humidité du sol
    valhumiditesol = analogRead(humiditePin);
    Serial.print("Humidité du sol = " );
    Serial.print(valhumiditesol);
    Serial.println();

    //Humidité du sol et température
    humiditeair = dht.readHumidity();
    temperature = dht.readTemperature();
      if (isnan (valhumiditesol) || isnan (temperature)) {
        Serial.println("Echec reception, d'un ou des capteurs");
          return;
      }
    Serial.print ("Température : ");
    Serial.println(temperature);
    Serial.print ("Humidité de l'air : ");
    Serial.println(humiditeair);
    Serial.println();

    //Niveau d'eau 
    valniveaueau = analogRead(WATER_SENSOR_PIN); 
    Serial.print("Niveau d'eau: ");
    Serial.println(valniveaueau);
    if (valniveaueau >= 3000) {
      niveau_suffisant = true;
    }
    else {
      niveau_suffisant = false;
    }
    delay(1500);

  //Affichage sur l'écran 
  if (ecranallume) {
    ecran.clearDisplay();
    ecran.setTextSize(2);
    ecran.setTextColor(SSD1306_WHITE);
    ecran.setCursor(0, 0);
    Serial.println(option_selectionnee);

    moyennehumidite = total / nombreval;
    pourcentagehum = (moyennehumidite / 4095.0) * 100.0;

    if (strcmp(option_selectionnee,"temperature")==0) {
      ecran.print("Temperature: ");
      ecran.print(temperature);
      ecran.println(" C");

    } else if (strcmp(option_selectionnee,"humiditedusol")==0) {
      ecran.print("Humidite du sol: ");
      ecran.print(pourcentagehum);
      ecran.println(" %");

    } else if (strcmp(option_selectionnee,"humiditeair")==0) {
      ecran.print("Humidite de l'air: ");
      ecran.print(humiditeair);
      ecran.println(" %");

    } else if (strcmp(option_selectionnee,"ensoleillement")==0) {
      ecran.print("Ensoleillement: ");
      ecran.print(lux*100);
      ecran.println(" lux");

    } else if (strcmp(option_selectionnee,"niveaueau")==0) {
      ecran.print("Niveau d'eau: ");
      ecran.println(niveau_suffisant ? "OK" : "Bas");
    }
    ecran.display();
  }
  
  //Envoyer les messages au node-red
    if (!clientHYDRO.connected()) {
      reconnect();
    }
  clientHYDRO.loop(); 
  long now = millis();

  
  if (now - lastMsg > 5000) { 
    lastMsg = now;
    total = total - liste[index_actuel];
    delay(500);
    liste[index_actuel] = analogRead(humiditePin);
    total = total + liste[index_actuel];
    index_actuel = index_actuel + 1;

    if (index_actuel >= nombreval) {
      index_actuel = 0;
    }

    pourcentagehum = (moyennehumidite / 4095.0) * 100.0;

    char strensoleillement[16];
    char strhumiditeair[16];
    char strtemperature[16];
    char strhumiditesol[16];
    char strniveaueau[16];
    dtostrf(lightMeter.readLightLevel()*100, 1, 2, strensoleillement); 
    dtostrf(dht.readTemperature(), 1, 2, strtemperature);  
    dtostrf(dht.readHumidity(), 1, 2, strhumiditeair);  
    dtostrf(pourcentagehum, 1, 2, strhumiditesol);
    dtostrf(analogRead(WATER_SENSOR_PIN), 1, 2, strniveaueau);
    clientHYDRO.publish("esp32/luxmetre", strensoleillement);
    clientHYDRO.publish("esp32/temperature", strtemperature);
    clientHYDRO.publish("esp32/humiditeair", strhumiditeair);
    clientHYDRO.publish("esp32/humiditesol", strhumiditesol);
    clientHYDRO.publish("esp32/neau",strniveaueau);

  } 
}
