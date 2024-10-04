#include <WiFiManager.h>
#include <HTTPClient.h>
#include <Wire.h>
#include <Adafruit_GFX.h>
#include <Adafruit_SSD1306.h>
#include <ArduinoJson.h>

#define SCREEN_WIDTH 128
#define SCREEN_HEIGHT 64
#define OLED_RESET -1

Adafruit_SSD1306 display(SCREEN_WIDTH, SCREEN_HEIGHT, &Wire, OLED_RESET);
WiFiManager wm;

// กำหนดพินสำหรับปุ่มกด
const int buttonPin = 2; // เปลี่ยนให้ตรงกับพินที่คุณใช้
int currentPage = 0; // ใช้สำหรับติดตามหน้าปัจจุบัน

// URL ของ API สำหรับข้อมูลโต๊ะ
const char* apiURL = "http://192.168.108.205/api/get_available_tables.php"; // เปลี่ยน URL ตามที่คุณใช้

void setup() {
  Serial.begin(115200);
  WiFi.mode(WIFI_STA);

  // เริ่มต้นจอ OLED
  if (!display.begin(SSD1306_SWITCHCAPVCC, 0x3C)) {
    Serial.println(F("SSD1306 allocation failed"));
    for (;;);
  }
  
  display.clearDisplay();
  display.setTextSize(1);
  display.setTextColor(SSD1306_WHITE);
  
  pinMode(buttonPin, INPUT_PULLUP); // ตั้งค่าพินสำหรับปุ่มกด

  display.setCursor(0, 0);
  display.println("Initializing...");
  display.display();
  delay(2000);

  // เชื่อมต่อ WiFi
  bool res = wm.autoConnect("AutoConnectAP1111", "password"); // กำหนดชื่อ SSID และ Password ตามต้องการ
  
  if (!res) {
    Serial.println("Failed to connect or hit timeout");
    ESP.restart();
  } else {
    Serial.println("Connected to WiFi");
    displayText("Connected to WiFi");
    delay(2000);
  }
}

void loop() {
  // ตรวจสอบการกดปุ่ม
  if (digitalRead(buttonPin) == LOW) {
    currentPage = (currentPage + 1) % 1; // เปลี่ยนหน้า (มีหน้าเดียว)
    delay(300); // ป้องกันการกดซ้ำเร็วเกินไป
  }

  // แสดงข้อมูลโต๊ะ
  if (currentPage == 0) {
    fetchTableData(); // ดึงข้อมูลโต๊ะ
  }

  delay(10000); // ปรับเวลาตามต้องการ
}

void fetchTableData() {
  if (WiFi.status() == WL_CONNECTED) {
    HTTPClient http;
    http.begin(apiURL);
    int httpCode = http.GET();

    Serial.print("HTTP Code: ");
    Serial.println(httpCode);

    if (httpCode > 0) {
      String payload = http.getString();
      Serial.println("Received Data: ");
      Serial.println(payload);

      // ใช้ ArduinoJson เพื่อแยกข้อมูล JSON
      StaticJsonDocument<1024> doc;
      DeserializationError error = deserializeJson(doc, payload);
      
      if (!error) {
        int availableCount = 0;
        display.clearDisplay();
        display.setCursor(0, 0);
        display.setTextSize(1);
        display.setTextColor(SSD1306_WHITE);
        display.println("Empty table total:");

        // นับจำนวนโต๊ะที่ว่าง
        for (JsonObject table : doc.as<JsonArray>()) {
          // ตรวจสอบค่า `status` ว่าเป็น "available"
          String status = table["status"].as<String>();
          Serial.print("Table ");
          Serial.print(table["table_number"].as<int>());
          Serial.print(" status: ");
          Serial.println(status);

          if (status == "available") {
            availableCount++;
          }
        }

        display.setTextSize(2); 
        display.setCursor(0, 20); 
        display.print(availableCount); 
        display.print("/10"); // สมมติว่ามี 10 โต๊ะ
        display.display();
      } else {
        Serial.println("JSON parsing failed!");
        displayText("JSON error");
      }
      
    } else {
      Serial.println("Error on HTTP request");
      displayText("HTTP request error");
    }

    http.end();
  } else {
    Serial.println("WiFi not connected");
    displayText("WiFi not connected");
  }
}

void displayText(String text) {
  display.clearDisplay();
  display.setCursor(0, 0);
  display.setTextSize(1);
  display.setTextColor(SSD1306_WHITE);
  display.println(text);
  display.display();
}
