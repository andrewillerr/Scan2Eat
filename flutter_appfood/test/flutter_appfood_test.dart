import 'package:flutter_test/flutter_test.dart';
import 'package:flutter_appfood/flutter_appfood.dart';
import 'package:flutter_appfood/flutter_appfood_platform_interface.dart';
import 'package:flutter_appfood/flutter_appfood_method_channel.dart';
import 'package:plugin_platform_interface/plugin_platform_interface.dart';

class MockFlutterAppfoodPlatform
    with MockPlatformInterfaceMixin
    implements FlutterAppfoodPlatform {

  @override
  Future<String?> getPlatformVersion() => Future.value('42');
}

void main() {
  final FlutterAppfoodPlatform initialPlatform = FlutterAppfoodPlatform.instance;

  test('$MethodChannelFlutterAppfood is the default instance', () {
    expect(initialPlatform, isInstanceOf<MethodChannelFlutterAppfood>());
  });

  test('getPlatformVersion', () async {
    FlutterAppfood flutterAppfoodPlugin = FlutterAppfood();
    MockFlutterAppfoodPlatform fakePlatform = MockFlutterAppfoodPlatform();
    FlutterAppfoodPlatform.instance = fakePlatform;

    expect(await flutterAppfoodPlugin.getPlatformVersion(), '42');
  });
}
