import 'package:flutter/material.dart';
import 'menu_screen.dart'; // ปรับการนำเข้าเพื่อให้ตรงกับหน้าต่างๆ ของคุณ
import 'api_service.dart';

class TableSelectionScreen extends StatefulWidget {
  @override
  _TableSelectionScreenState createState() => _TableSelectionScreenState();
}

class _TableSelectionScreenState extends State<TableSelectionScreen> {
  int? selectedTable;
  List<Map<String, dynamic>> tables = [];

  Future<void> fetchTables() async {
    try {
      List<Map<String, dynamic>> fetchedTables = await ApiService.getAvailableTables();
      setState(() {
        tables = fetchedTables;
      });
    } catch (e) {
      throw Exception('Failed to load available tables: $e');
    }
  }

  @override
  void initState() {
    super.initState();
    fetchTables();  // เรียกใช้เมธอดเพื่อดึงข้อมูลโต๊ะตั้งแต่เริ่มต้น
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        title: Text('เลือกโต๊ะ'),
        backgroundColor: Colors.green,
      ),
      body: tables.isEmpty
          ? Center(child: CircularProgressIndicator())
          : Padding(
              padding: const EdgeInsets.all(16.0),
              child: Column(
                children: [
                  Text('เลือกโต๊ะ', style: TextStyle(fontSize: 20)),
                  SizedBox(height: 20),
                  DropdownButton<int>(
                    value: selectedTable,
                    hint: Text('เลือกโต๊ะ'),
                    onChanged: (newValue) {
                      setState(() {
                        selectedTable = newValue;
                      });
                    },
                    items: tables.map((table) {
                      var tableNumber = table['table_number']; // ตรวจสอบว่าเป็น String หรือ int
                      var status = table['status'];

                      // แปลง table_number เป็น int ถ้ามันเป็น String
                      int? tableNumberInt = int.tryParse(tableNumber.toString());

                      return DropdownMenuItem<int>(
                        value: tableNumberInt,
                        child: Text('โต๊ะ ${tableNumberInt} - สถานะ: $status'),
                      );
                    }).toList(),
                  ),
                  SizedBox(height: 20),
                  ElevatedButton(
                    onPressed: () {
                      if (selectedTable != null) {
                        Navigator.push(
                          context,
                          MaterialPageRoute(
                            builder: (context) => MenuScreen(tableNumber: selectedTable!),
                          ),
                        );
                      } else {
                        ScaffoldMessenger.of(context).showSnackBar(
                          SnackBar(
                            content: Text('กรุณาเลือกโต๊ะก่อน'),
                          ),
                        );
                      }
                    },
                    child: Text('ยืนยัน'),
                    style: ElevatedButton.styleFrom(
                      backgroundColor: Colors.green, // ใช้ backgroundColor แทน primary
                    ),
                  ),
                ],
              ),
            ),
    );
  }
}
