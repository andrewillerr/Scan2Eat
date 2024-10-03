import 'package:flutter/material.dart';
import 'order_confirmation_screen.dart';
import 'api_service.dart';

class MenuScreen extends StatefulWidget {
  final int tableNumber;

  MenuScreen({required this.tableNumber});

  @override
  _MenuScreenState createState() => _MenuScreenState();
}

class _MenuScreenState extends State<MenuScreen> {
  Map<int, int> orderItems = {}; // itemId -> quantity
  List<Map<String, dynamic>> menuItems = [];

  Future<void> fetchMenu() async {
    menuItems = await ApiService.getMenu();
    setState(() {});
  }

  @override
  void initState() {
    super.initState();
    fetchMenu();
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        title: Text('Select Menu'),
      ),
      body: menuItems.isEmpty
          ? Center(child: CircularProgressIndicator())
          : ListView.builder(
              itemCount: menuItems.length,
              itemBuilder: (context, index) {
                var item = menuItems[index];
                int itemId = int.tryParse(item['item_id'].toString()) ?? 0; // แปลง item_id เป็น int

                return ListTile(
                  title: Text(item['item_name']),
                  subtitle: Text('฿${item['item_price']}'),
                  trailing: Row(
                    mainAxisSize: MainAxisSize.min,
                    children: [
                      IconButton(
                        icon: Icon(Icons.remove),
                        onPressed: () {
                          setState(() {
                            if (orderItems[itemId] != null && orderItems[itemId]! > 1) {
                              orderItems[itemId] = orderItems[itemId]! - 1;
                            } else {
                              orderItems.remove(itemId);
                            }
                          });
                        },
                      ),
                      Text(orderItems[itemId]?.toString() ?? '0'), // แสดงจำนวนที่เลือก
                      IconButton(
                        icon: Icon(Icons.add),
                        onPressed: () {
                          setState(() {
                            orderItems[itemId] = (orderItems[itemId] ?? 0) + 1;
                          });
                        },
                      ),
                    ],
                  ),
                );
              },
            ),
      floatingActionButton: FloatingActionButton(
        onPressed: () {
          Navigator.push(
            context,
            MaterialPageRoute(
              builder: (context) => OrderConfirmationScreen(
                tableNumber: widget.tableNumber,
                orderItems: orderItems,
                menu: menuItems,
              ),
            ),
          );
        },
        child: Icon(Icons.check),
      ),
    );
  }
}
