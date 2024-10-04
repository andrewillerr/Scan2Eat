import 'package:flutter/material.dart';
import 'api_service.dart';

class PaymentScreen extends StatelessWidget {
  final int tableNumber;
  final double totalPrice;

  PaymentScreen({
    required this.tableNumber,
    required this.totalPrice,
  });

  Future<void> processPayment(BuildContext context) async {
    try {
      final paymentSuccess = await ApiService.payAndUpdateStatus(tableNumber);

      if (paymentSuccess) {
        ScaffoldMessenger.of(context).showSnackBar(SnackBar(
          content: Text('Payment completed! Table is now available.'),
          backgroundColor: Colors.green,
        ));

        // Navigate back to the main dashboard or a success screen
        Navigator.pop(context); 
      } else {
        ScaffoldMessenger.of(context).showSnackBar(SnackBar(
          content: Text('Payment failed. Please try again.'),
          backgroundColor: Colors.red,
        ));
      }
    } catch (error) {
      ScaffoldMessenger.of(context).showSnackBar(SnackBar(
        content: Text('Error processing payment: $error'),
        backgroundColor: Colors.red,
      ));
    }
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        title: Text('Payment'),
      ),
      body: Column(
        mainAxisAlignment: MainAxisAlignment.center,
        children: [
          // Text('Total: ฿$totalPrice', style: TextStyle(fontSize: 24)),
          SizedBox(height: 20),
          ElevatedButton(
            onPressed: () {
              processPayment(context);
            },
            child: Text('Pay Now'),
          ),
        ],
      ),
    );
  }
}
