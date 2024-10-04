import 'package:plugin_platform_interface/plugin_platform_interface.dart';

import 'flutter_appfood_method_channel.dart';

abstract class FlutterAppfoodPlatform extends PlatformInterface {
  /// Constructs a FlutterAppfoodPlatform.
  FlutterAppfoodPlatform() : super(token: _token);

  static final Object _token = Object();

  static FlutterAppfoodPlatform _instance = MethodChannelFlutterAppfood();

  /// The default instance of [FlutterAppfoodPlatform] to use.
  ///
  /// Defaults to [MethodChannelFlutterAppfood].
  static FlutterAppfoodPlatform get instance => _instance;

  /// Platform-specific implementations should set this with their own
  /// platform-specific class that extends [FlutterAppfoodPlatform] when
  /// they register themselves.
  static set instance(FlutterAppfoodPlatform instance) {
    PlatformInterface.verifyToken(instance, _token);
    _instance = instance;
  }

  Future<String?> getPlatformVersion() {
    throw UnimplementedError('platformVersion() has not been implemented.');
  }
}
