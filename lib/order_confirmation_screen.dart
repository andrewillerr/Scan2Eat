import 'package:flutter/material.dart';
import 'payment_screen.dart';
import 'api_service.dart';

class OrderConfirmationScreen extends StatelessWidget {
  final int tableNumber;
  final Map<int, int> orderItems;
  final List menu;

  OrderConfirmationScreen({
    required this.tableNumber,
    required this.orderItems,
    required this.menu,
  });

  double calculateTotalPrice() {
    double total = 0.0;
    orderItems.forEach((itemId, quantity) {
      final menuItem = menu.firstWhere(
        (item) => item['item_id'].toString() == itemId.toString(),
        orElse: () => Map<String, dynamic>.from({}),
      );

      if (menuItem.isEmpty) {
        print("Item with ID $itemId not found in the menu.");
      } else {
        // Convert 'item_price' to double to prevent type error
        final itemPrice = double.tryParse(menuItem['item_price'].toString()) ?? 0.0;
        total += itemPrice * quantity;
      }
    });
    return total;
  }

  Future<void> updateTableStatus(BuildContext context, String status) async {
    await ApiService.updateTableStatus(tableNumber, status);
  }

  Future<void> submitOrder(BuildContext context) async {
    final totalPrice = calculateTotalPrice();

    // Update the table status to "not available"
    await updateTableStatus(context, 'not available');

    // Send the order details to the server
    try {
      await ApiService.submitOrder(tableNumber, orderItems, totalPrice);
      ScaffoldMessenger.of(context).showSnackBar(SnackBar(
        content: Text('Order submitted successfully!'),
        backgroundColor: Colors.green,
      ));

      // Navigate to the payment screen
      Navigator.push(
        context,
        MaterialPageRoute(
          builder: (context) => PaymentScreen(
            tableNumber: tableNumber,
            totalPrice: totalPrice,
          ),
        ),
      );
    } catch (error) {
      // Show error message
      ScaffoldMessenger.of(context).showSnackBar(SnackBar(
        content: Text('Error: $error'),
        backgroundColor: Colors.red,
      ));
    }
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        title: Text('Confirm Order'),
      ),
      body: Column(
        children: [
          Expanded(
            child: ListView.builder(
              itemCount: orderItems.length,
              itemBuilder: (context, index) {
                final itemId = orderItems.keys.elementAt(index);
                final quantity = orderItems[itemId];

                final menuItem = menu.firstWhere(
                  (item) => item['item_id'].toString() == itemId.toString(),
                  orElse: () => Map<String, dynamic>.from({}),
                );

                if (menuItem.isEmpty) {
                  return ListTile(
                    title: Text('Item not found for ID $itemId'),
                    subtitle: Text('Quantity: $quantity'),
                  );
                }

                return ListTile(
                  title: Text('${menuItem['item_name']}'),
                  subtitle: Text('Quantity: $quantity'),
                );
              },
            ),
          ),
          Padding(
            padding: const EdgeInsets.all(16.0),
            child: Text('Total: ฿${calculateTotalPrice()}'),
          ),
          ElevatedButton(
            onPressed: () {
              submitOrder(context);  // Call submitOrder when the button is pressed
            },
            child: Text('Submit Order'),
          ),
        ],
      ),
    );
  }
}
