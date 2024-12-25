#include <WiFiManager.h>
#include <HTTPClient.h>
#include <Keypad.h>
#include <Wire.h>
#include <LiquidCrystal_PCF8574.h>
#include <ArduinoJson.h>
#include <NTPClient.h>
#include <WiFiUdp.h>
#include <TimeLib.h>
#include <Adafruit_Fingerprint.h>
#include <WebServer.h>

// Fingerprint AS608
#define RX_PIN 16 // Hubungkan ke TX di AS608
#define TX_PIN 17 // Hubungkan ke RX di AS608

// Inisialisasi hardware serial port
HardwareSerial mySerial(1); // UART1
Adafruit_Fingerprint finger = Adafruit_Fingerprint(&mySerial);

// Web server pada port 80
WebServer server(80);

// Konfigurasi Keypad
const byte ROWS = 4; // Jumlah baris
const byte COLS = 4; // Jumlah kolom
char keys[ROWS][COLS] = {
  {'1', '2', '3', 'A'},
  {'4', '5', '6', 'B'},
  {'7', '8', '9', 'C'},
  {'*', '0', '#', 'D'}
};
byte rowPins[ROWS] = {14, 27, 26, 25}; // Pin Baris
byte colPins[COLS] = {33, 32, 13, 12}; // Pin Kolom

Keypad keypad = Keypad(makeKeymap(keys), rowPins, colPins, ROWS, COLS);

// Konfigurasi LCD
LiquidCrystal_PCF8574 lcd(0x27); // Alamat I2C LCD, ukuran 16x2

// Inisialisasi UDP dan NTPClient
WiFiUDP udp;
NTPClient timeClient(udp, "pool.ntp.org", 0, 60000);

void setup() {
  delay(1000);
  Serial.begin(115200);

  // Inisialisasi LCD
  Wire.begin(21, 22); // SDA ke pin 21, SCL ke pin 22
  lcd.begin(16, 2); // LCD 16x2
  lcd.setBacklight(1); // Aktifkan lampu latar
  lcd.setCursor(0, 0);
  lcd.print("Mohon tunggu...");

  // Inisialisasi sensor fingerprint
  mySerial.begin(57600, SERIAL_8N1, RX_PIN, TX_PIN);
  finger.begin(57600);
  if (finger.verifyPassword()) {
    Serial.println("Fingerprint sensor ditemukan!");
  } else {
    Serial.println("Sensor fingerprint tidak ditemukan!");
    while (1) { delay(1); }
  }

  // Konfigurasi WiFiManager
  WiFiManager wm;

  // Menampilkan pesan jika belum terhubung ke Wi-Fi
  lcd.clear();
  lcd.setCursor(0, 0);
  lcd.print("Mohon sambungkan");
  lcd.setCursor(0, 1);
  lcd.print("alat ke WIFI");

  // Memulai WiFiManager dan menunggu konfigurasi Wi-Fi
  if (!wm.autoConnect("ESP32-Absensi by Nasyih")) {
    Serial.println("Failed to connect");
    lcd.clear();
    lcd.setCursor(0, 0);
    lcd.print("WiFi gagal");
    lcd.setCursor(0, 1);
    lcd.print("Coba ulangi");
    delay(5000);
    ESP.restart(); // Restart perangkat jika gagal
  }

  // Wi-Fi berhasil tersambung
  Serial.println("WiFi connected!");
  lcd.clear();
  lcd.setCursor(0, 0);
  lcd.print("Tersambung ke:");
  lcd.setCursor(0, 1);
  lcd.print(WiFi.SSID());
  delay(3000);

  // Inisialisasi NTPClient
  timeClient.begin();
  timeClient.setTimeOffset(25200); // 7 jam dalam detik
  timeClient.update(); // Update waktu pertama kali

  // Rute untuk enroll fingerprint
  server.on("/enroll", handleEnroll);
  server.begin();
  Serial.println("Server dimulai!");
}

void loop() {
  static unsigned long lastHeartbeat = 0;
  static unsigned long lastOtherTask = 0;

  unsigned long currentMillis = millis();

  // Kirim heartbeat setiap 1 detik
  if (currentMillis - lastHeartbeat >= 1000) { // 1 detik
    sendHeartbeat();
    lastHeartbeat = currentMillis;
  }

  // Jalankan tugas lain dengan non-blocking
  if (currentMillis - lastOtherTask >= 50) { // Setiap 50 ms
    lastOtherTask = currentMillis;

    server.handleClient();    // Tangani server
    tampilWaktu();            // Tampilkan waktu
    cekFingerprint();         // Cek fingerprint
    cekPerubahanIP();         // Cek perubahan IP
  }
}


void handleEnroll() {
  if (!server.hasArg("id_fingerprint")) {
    server.send(400, "application/json", "{\"status\":\"error\",\"message\":\"ID Fingerprint tidak ditemukan\"}");
    lcd.clear();
    lcd.print("ID Fingerprint");
    lcd.setCursor(0, 1);
    lcd.print("tidak ditemukan");
    delay(2000);
    return;
  }

  int id = server.arg("id_fingerprint").toInt();
  Serial.print("Mulai enroll untuk ID: ");
  Serial.println(id);

  lcd.clear();
  lcd.print("Mulai enroll ID:");
  lcd.setCursor(0, 1);
  lcd.print(id);

  int p = -1;
  Serial.println("Letakkan jari pada sensor...");
  lcd.clear();
  lcd.print("Letakkan jari");
  lcd.setCursor(0, 1);
  lcd.print("Pada sensor...");
  
  while (p != FINGERPRINT_OK) {
    p = finger.getImage();
    if (p == FINGERPRINT_NOFINGER) continue;
    if (p != FINGERPRINT_OK) {
      Serial.println("Gagal mengambil gambar"); 
      lcd.clear();
      lcd.print("Gagal ambil");
      lcd.setCursor(0, 1);
      lcd.print("gambar");
      server.send(500, "application/json", "{\"status\":\"error\",\"message\":\"Gagal mengambil gambar\"}");
      delay(2000);
      return;
    }
  }

  p = finger.image2Tz(1);
  if (p != FINGERPRINT_OK) {
    Serial.println("Gagal mengonversi gambar"); 
    lcd.clear();
    lcd.print("Gagal konversi");
    lcd.setCursor(0, 1);
    lcd.print("gambar");
    server.send(500, "application/json", "{\"status\":\"error\",\"message\":\"Gagal mengonversi gambar\"}");
    delay(2000);
    return;
  }

  Serial.println("Angkat jari dan letakkan lagi...");
  lcd.clear();
  lcd.print("Angkat jari...");
  delay(1000);

  while (p != FINGERPRINT_NOFINGER) p = finger.getImage();

  Serial.println("Letakkan jari lagi...");
  lcd.clear();
  lcd.print("Letakkan jari");
  lcd.setCursor(0, 1);
  lcd.print("lagi...");

  p = -1;
  while (p != FINGERPRINT_OK) {
    p = finger.getImage();
    if (p == FINGERPRINT_NOFINGER) continue;
    if (p != FINGERPRINT_OK) {
      Serial.println("Gagal mengambil gambar kedua"); 
      lcd.clear();
      lcd.print("Gagal ambil");
      lcd.setCursor(0, 1);
      lcd.print("gambar kedua");
      server.send(500, "application/json", "{\"status\":\"error\",\"message\":\"Gagal mengambil gambar kedua\"}");
      delay(2000);
      return;
    }
  }

  p = finger.image2Tz(2);
  if (p != FINGERPRINT_OK) {
    Serial.println("Gagal mengonversi gambar kedua"); 
    lcd.clear();
    lcd.print("Gagal konversi");
    lcd.setCursor(0, 1);
    lcd.print("gambar kedua");
    server.send(500, "application/json", "{\"status\":\"error\",\"message\":\"Gagal mengonversi gambar kedua\"}");
    delay(2000);
    return;
  }

  p = finger.createModel();
  if (p != FINGERPRINT_OK) {
    Serial.println("Gagal membuat model");
    lcd.clear();
    lcd.print("Gagal membuat");
    lcd.setCursor(0, 1);
    lcd.print("model");
    server.send(500, "application/json", "{\"status\":\"error\",\"message\":\"Gagal membuat model\"}");
    delay(2000);
    return;
  }

  p = finger.storeModel(id);
  if (p == FINGERPRINT_OK) {
    server.sendHeader("Access-Control-Allow-Origin", "*");
    server.sendHeader("Content-Type", "application/json");
    Serial.println("Enroll berhasil!"); 
    lcd.clear();
    lcd.print("Enroll berhasil");
    lcd.setCursor(0, 1);
    lcd.print("ID: ");
    lcd.print(id);
    server.send(200, "application/json", "{\"status\":\"success\",\"message\":\"Enroll berhasil\"}");
  } else {
    server.sendHeader("Access-Control-Allow-Origin", "*");
    server.sendHeader("Content-Type", "application/json");
    Serial.println("Gagal menyimpan template");
    lcd.clear();
    lcd.print("Gagal menyimpan");
    lcd.setCursor(0, 1);
    lcd.print("template");
    server.send(500, "application/json", "{\"status\":\"error\",\"message\":\"Gagal menyimpan template\"}");
    delay(2000);
  }
}


void cekFingerprint() {
  uint8_t p = finger.getImage();
  if (p == FINGERPRINT_NOFINGER) return;

  if (p == FINGERPRINT_OK) {
    Serial.println("Gambar diambil");
    p = finger.image2Tz();
    if (p == FINGERPRINT_OK) {
      Serial.println("Gambar dikonversi");
      p = finger.fingerSearch();
      if (p == FINGERPRINT_OK) {
        int id_fingerprint = finger.fingerID;
        int confidence = finger.confidence;
        Serial.print("ID Ditemukan: "); Serial.println(id_fingerprint);
        Serial.print("Kepercayaan: "); Serial.println(confidence);

        String nama;
        int password_absen;
        String status = getUserData(id_fingerprint, nama, password_absen);

        if (status == "success") {
          lcd.clear();
          lcd.setCursor(0, 0);
          lcd.print("Input Password");
          lcd.setCursor(0, 1);
          lcd.print("User: " + nama);

          Serial.println("Silakan masukkan password untuk: " + nama);

          if (verifyPassword(password_absen)) {
            lcd.clear();
            lcd.setCursor(0, 0);
            lcd.print("Mengirim Data...");
            delay(1000);

            bool success = kirimDataAbsen(id_fingerprint);
            if (!success) {
              lcd.clear();
              lcd.setCursor(0, 0);
              lcd.print("Gagal Mengirim");
              delay(2000);
            }
          } else {
            lcd.clear();
            lcd.setCursor(0, 0);
            lcd.print("Password Salah");
            Serial.println("Password salah. Data tidak terkirim.");
            delay(2000);
          }
        } else {
          lcd.clear();
          lcd.setCursor(0, 0);
          lcd.print("Error: ");
          lcd.setCursor(0, 1);
          lcd.print(status);
          Serial.println("Error: " + status);
          delay(3000);
        }

        tampilWaktu();
        delay(1000);
      } else {
        Serial.println("Fingerprint tidak ditemukan");
        lcd.clear();
        lcd.setCursor(0, 0);
        lcd.print("Tidak ditemukan");
        delay(2000);
      }
    } else {
      Serial.println("Gagal mengkonversi gambar");
    }
  } else {
    Serial.println("Gagal mengambil gambar");
  }
}

void tampilWaktu() {
  unsigned long epochTime = timeClient.getEpochTime();
  setTime(epochTime);

  int dayVal = day();
  int monthVal = month();
  int yearVal = year();
  int hourVal = hour();
  int minuteVal = minute();
  int secondVal = second();

  String dateString = (dayVal < 10 ? "0" : "") + String(dayVal) + "/" +
                      (monthVal < 10 ? "0" : "") + String(monthVal) + "/" +
                      String(yearVal);
  String timeString = (hourVal < 10 ? "0" : "") + String(hourVal) + ":" +
                      (minuteVal < 10 ? "0" : "") + String(minuteVal) + ":" +
                      (secondVal < 10 ? "0" : "") + String(secondVal);

  lcd.clear();
  lcd.setCursor(0, 0);
  lcd.print("Sistem Absensi");
  lcd.setCursor(0, 1);
  lcd.print(dateString + " " + timeString);
}

String getUserData(int id_fingerprint, String &nama, int &password_absen) {
  String url = "http://192.168.1.2/esp32_absensi_ci3/api/ambilDataUser?id_fingerprint=" + String(id_fingerprint);
  HTTPClient http;

  http.begin(url);
  int httpCode = http.GET();

  if (httpCode > 0) {
    String payload = http.getString();
    Serial.println(payload);
    http.end();

    DynamicJsonDocument doc(1024);
    DeserializationError error = deserializeJson(doc, payload);

    if (error) {
      Serial.print("Deserialization failed: ");
      Serial.println(error.c_str());
      return "Error parsing JSON";
    }

    if (doc["status"] == "success") {
      nama = doc["nama"].as<String>();
      password_absen = doc["password_absen"].as<int>();
      return "success";
    } else {
      return doc["message"].as<String>();
    }
  } else {
    Serial.print("HTTP Error: ");
    Serial.println(http.errorToString(httpCode));
    http.end();
    return "HTTP Error";
  }
}

bool verifyPassword(int expectedPassword) {
  String inputPassword = "";
  char key;

  while (inputPassword.length() < 6) {
    key = keypad.getKey();
    if (key) {
      Serial.print("*");
      lcd.setCursor(inputPassword.length(), 1);
      lcd.print("*");
      inputPassword += key;
    }
  }

  Serial.println();
  return inputPassword.toInt() == expectedPassword;
}

bool kirimDataAbsen(int id_fingerprint) {
  String postData = "id_fingerprint=" + String(id_fingerprint);

  HTTPClient http;
  http.begin("http://192.168.1.2/esp32_absensi_ci3/api/kirimDataAbsen");
  http.addHeader("Content-Type", "application/x-www-form-urlencoded");

  int httpCode = http.POST(postData);
  Serial.print("HTTP Code: ");
  Serial.println(httpCode);

  if (httpCode > 0) {
    String payload = http.getString();
    Serial.println("Payload: " + payload);

    DynamicJsonDocument doc(512);
    DeserializationError error = deserializeJson(doc, payload);

    if (error) {
      Serial.print("Deserialization failed: ");
      Serial.println(error.c_str());
      http.end();
      return false;
    }

    String status = doc["status"].as<String>();
    String message = doc["message"].as<String>();

    lcd.clear();
    lcd.setCursor(0, 0);
    if (status == "success") {
      lcd.print(message);
      lcd.setCursor(0, 1);
      lcd.print("berhasil!");
      Serial.println(message);
      delay(3000);
    } else if (status == "error" && message == "Sudah absen.") {
      lcd.print("Anda sudah absen");
      lcd.setCursor(0, 1);
      lcd.print("Hari ini...");
      Serial.println("Anda sudah melakukan absensi hari ini.");
      delay(3000);
    } else if (status == "error" && message == "Absen keluar belum dibuka.") {
      lcd.print("Absen keluar");
      lcd.setCursor(0, 1);
      lcd.print("belum dibuka...");
      Serial.println(message);
      delay(3000);
    } else if (status == "error" && message == "Tidak ada jadwal absen yang sesuai.") {
      lcd.print("Tidak ada jadwal");
      lcd.setCursor(0, 1);
      lcd.print("matakuliah!");
      Serial.println(message);
      delay(3000);
    } else {
      lcd.print("Error: ");
      lcd.setCursor(0, 1);
      lcd.print(message);
      Serial.println(message);
    }

    http.end();
    return (status == "success");
  } else {
    Serial.print("HTTP Error: ");
    Serial.println(http.errorToString(httpCode));
    http.end();
    return false;
  }
}

void cekPerubahanIP()
{
  static String lastIPAddress = ""; 
  String currentIPAddress = WiFi.localIP().toString(); // Ambil IP saat ini

  if (currentIPAddress != lastIPAddress) { // Jika IP berubah
    Serial.print("Alamat IP berubah: ");
    Serial.println(currentIPAddress);

    // Ambil SSID dari WiFi yang sedang terhubung
    String ssid = WiFi.SSID();

    // Konfigurasi data
    String kodeAlat = "ESP32_ABS_BY_N"; 
    String namaAlat = "ESP32_ABSENSI_BY_NASYIH";

    // Kirim data ke server
    HTTPClient http;
    http.begin("http://192.168.1.2/esp32_absensi_ci3/api/kirimIPAddress");
    http.addHeader("Content-Type", "application/x-www-form-urlencoded");

    // Format data
    String postData = "kodeAlat=" + kodeAlat + 
                      "&namaAlat=" + namaAlat + 
                      "&ipAddress=" + currentIPAddress +
                      "&ssid=" + ssid;

    int httpCode = http.POST(postData);

    if (httpCode > 0) {
      String payload = http.getString();
      Serial.println("Response: " + payload);

      // Update alamat IP terakhir hanya jika POST berhasil
      lastIPAddress = currentIPAddress;
    } else {
      Serial.print("HTTP Error: ");
      Serial.println(http.errorToString(httpCode));
    }

    http.end();
  }
}


void sendHeartbeat() {
  HTTPClient http;
  http.begin("http://192.168.1.2/esp32_absensi_ci3/api/heartbeat"); // Endpoint heartbeat
  http.addHeader("Content-Type", "application/x-www-form-urlencoded");

  String kodeAlat = "ESP32_ABS_BY_N";
  String postData = "kodeAlat=" + kodeAlat;

  int httpCode = http.POST(postData);

  if (httpCode > 0) {
    Serial.println("Heartbeat sent");
  } else {
    Serial.print("Heartbeat Error: ");
    Serial.println(http.errorToString(httpCode));
  }

  http.end();
}

