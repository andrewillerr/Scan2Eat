import 'package:flutter/foundation.dart';
import 'package:flutter/services.dart';

import 'flutter_appfood_platform_interface.dart';

/// An implementation of [FlutterAppfoodPlatform] that uses method channels.
class MethodChannelFlutterAppfood extends FlutterAppfoodPlatform {
  /// The method channel used to interact with the native platform.
  @visibleForTesting
  final methodChannel = const MethodChannel('flutter_appfood');

  @override
  Future<String?> getPlatformVersion() async {
    final version = await methodChannel.invokeMethod<String>('getPlatformVersion');
    return version;
  }
}
