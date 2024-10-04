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
unsigned long pageStartTime = 0; // ตัวแปรสำหรับจับเวลาหน้าปัจจุบัน

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

  pageStartTime = millis(); // เริ่มจับเวลาเมื่อเข้าสู่หน้าแรก
}

void loop() {
  unsigned long currentMillis = millis();

  // แสดงหน้าจอที่แตกต่างกันตาม currentPage
  if (currentPage == 0) {
    showScan2EatPage(); // แสดงหน้า Scan2Eat

    // หากเวลาผ่านไป 3 วินาที (3000 มิลลิวินาที) ให้สลับไปหน้าถัดไป
    if (currentMillis - pageStartTime >= 3000) {
      currentPage = 1; // เปลี่ยนไปหน้าแสดงข้อมูลโต๊ะ
    }
  } else if (currentPage == 1) {
    fetchTableData(); // ดึงข้อมูลโต๊ะและแสดง
    delay(10000); // รอ 10 วินาที ก่อนรีเฟรชข้อมูล
    pageStartTime = millis(); // รีเซ็ตเวลาเมื่อรีเฟรชข้อมูล
  }
}

// ฟังก์ชันสำหรับแสดงหน้า Scan2Eat
void showScan2EatPage() {
  display.clearDisplay();
  display.setTextSize(2); // ขนาดใหญ่สำหรับชื่อร้าน
  int centerX = (SCREEN_WIDTH - 72) / 2; // คำนวณตำแหน่งกึ่งกลางสำหรับ "Scan2Eat"
  display.setCursor(centerX, 25); 
  display.println("Scan2Eat"); // แสดงชื่อร้านที่กลางจอ
  display.display();
}

// ฟังก์ชันสำหรับดึงข้อมูลโต๊ะจาก API และแสดงผล
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

        // แสดงชื่อร้านที่ส่วนบน
        display.setTextSize(1);
        display.setCursor(0, 0); 
        display.println("Scan2Eat");

        // นับจำนวนโต๊ะที่ว่าง
        for (JsonObject table : doc.as<JsonArray>()) {
          String status = table["status"].as<String>();
          Serial.print("Table ");
          Serial.print(table["table_number"].as<int>());
          Serial.print(" status: ");
          Serial.println(status);

          if (status == "available") {
            availableCount++;
          }
        }

        // แสดงจำนวนโต๊ะว่างและ /10 ที่กลางจอ
        display.setTextSize(1);
        display.setCursor(0, 15);
        display.println("Available tables:");

        display.setTextSize(2); // ขนาดใหญ่สำหรับการแสดงตัวเลขโต๊ะ
        int centerX = (SCREEN_WIDTH - 36) / 2; // คำนวณตำแหน่งกึ่งกลางสำหรับตัวเลข "5/10" ที่มีขนาดใหญ่กว่า
        display.setCursor(centerX, 35); 
        display.print(availableCount); 
        display.print("/10"); 
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

// ฟังก์ชันแสดงข้อความทั่วไปบนจอ
void displayText(String text) {
  display.clearDisplay();
  display.setCursor(0, 0);
  display.setTextSize(1);
  display.setTextColor(SSD1306_WHITE);
  display.println(text);
  display.display();
}
