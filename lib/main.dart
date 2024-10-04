import 'package:flutter/material.dart';
import 'table_selection_screen.dart';

void main() {
  runApp(MyApp());
}

class MyApp extends StatelessWidget {
  @override
  Widget build(BuildContext context) {
    return MaterialApp(
      title: 'Scan2Eat',
      theme: ThemeData(
        primarySwatch: Colors.blue,
      ),
      home: TableSelectionScreen(),
      debugShowCheckedModeBanner: false,
    );
  }
}
