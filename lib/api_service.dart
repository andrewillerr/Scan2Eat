import 'dart:convert';
import 'package:http/http.dart' as http;

class ApiService {
  static const String baseUrl = 'http://192.168.108.205/api';

  // Fetch available tables
  static Future<List<Map<String, dynamic>>> getAvailableTables() async {
    try {
      final response = await http.get(Uri.parse('$baseUrl/get_available_tables.php')).timeout(Duration(seconds: 10)); // Added timeout
      if (response.statusCode == 200) {
        return List<Map<String, dynamic>>.from(json.decode(response.body));
      } else {
        throw Exception('Failed to load available tables: ${response.body}');
      }
    } catch (error) {
      throw Exception('Error fetching available tables: $error');
    }
  }

  // Fetch menu
  static Future<List<Map<String, dynamic>>> getMenu() async {
    try {
      final response = await http.get(Uri.parse('$baseUrl/fetch_menu.php')).timeout(Duration(seconds: 10)); // Added timeout
      if (response.statusCode == 200) {
        return List<Map<String, dynamic>>.from(json.decode(response.body));
      } else {
        throw Exception('Failed to load menu: ${response.body}');
      }
    } catch (error) {
      throw Exception('Error fetching menu: $error');
    }
  }

  // Submit the order
  static Future<void> submitOrder(int tableNumber, Map<int, int> orderItems, double totalPrice) async {
    try {
      Map<String, dynamic> convertedOrderItems = orderItems.map((key, value) => MapEntry(key.toString(), value));

      final response = await http
          .post(
            Uri.parse('$baseUrl/submit_order.php'),
            body: {
              'table_number': tableNumber.toString(),
              'order_items': json.encode(convertedOrderItems),
              'total_price': totalPrice.toString(),
            },
          )
          .timeout(Duration(seconds: 10)); // Added timeout

      if (response.statusCode != 200) {
        throw Exception('Failed to submit order: ${response.body}');
      }
    } catch (error) {
      throw Exception('Error submitting order: $error');
    }
  }

  // Update table status
  static Future<bool> updateTableStatus(int tableNumber, String status) async {
    try {
      final response = await http.post(
        Uri.parse('$baseUrl/update_table_status.php'),
        body: {
          'table_number': tableNumber.toString(),
          'status': status,
        },
      ).timeout(Duration(seconds: 10)); // Added timeout
      return response.statusCode == 200;
    } catch (error) {
      throw Exception('Error updating table status: $error');
    }
  }

  // Process payment and update table status
  static Future<bool> payAndUpdateStatus(int tableNumber) async {
    try {
      final response = await http
          .post(
            Uri.parse('$baseUrl/payment.php'),
            body: {'table_number': tableNumber.toString()},
          )
          .timeout(Duration(seconds: 10)); // Added timeout

      return response.statusCode == 200;
    } catch (error) {
      throw Exception('Error processing payment: $error');
    }
  }
}
