import 'package:flutter/material.dart';
import 'package:flutter/services.dart';
import 'package:dio/dio.dart';
import 'package:flutter_facebook_auth/flutter_facebook_auth.dart';

void main() {
  WidgetsFlutterBinding.ensureInitialized();
  runApp(MyApp());
}

class MyApp extends StatefulWidget {
  @override
  _MyAppState createState() => _MyAppState();
}

class _MyAppState extends State<MyApp> {
  @override
  Widget build(BuildContext context) {
    return MaterialApp(
      debugShowCheckedModeBanner: false,
      home: HomeScreen(),
    );
  }
}

class HomeScreen extends StatefulWidget {
  HomeScreen({Key? key}) : super(key: key);

  @override
  _HomeScreenState createState() => _HomeScreenState();
}

class _HomeScreenState extends State<HomeScreen> {
  @override
  Widget build(BuildContext context) {
    return LoginScreen();
  }
}

class LoginScreen extends StatefulWidget {
  LoginScreen({Key? key}) : super(key: key);

  @override
  _LoginScreenState createState() => _LoginScreenState();
}

class _LoginScreenState extends State<LoginScreen> {
  void facebookLogin() async {
    try {
      final facebookLoginResult = await FacebookAuth.instance.login();
      final user = await FacebookAuth.instance.getUserData();
      final accessToken = facebookLoginResult.accessToken;

      print("User: ${user['name']} | Token: ${accessToken?.token} ");

      authenticate(accessToken?.token);
    } catch (e) {}
  }

  void authenticate(String? token) async {
    try {
      var response = await Dio().post(
          'http://192.168.0.179:8888/facebook/authenticate',
          data: {"token": token});

      print(response);
    } catch (e) {
      print(e);
    }
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      backgroundColor: Colors.teal,
      body: SafeArea(
        child: Center(
          child: ListView(
            shrinkWrap: true,
            children: [
              CircleAvatar(
                radius: 40.0,
                child: Image.asset('assets/images/user.png'),
                backgroundColor: Colors.white,
              ),
              Text(
                'Paulo Victor',
                textAlign: TextAlign.center,
                style: TextStyle(
                  color: Colors.white,
                  fontSize: 35.0,
                  fontFamily: 'Pacifico',
                  fontWeight: FontWeight.bold,
                ),
              ),
              Text(
                'Flutter Developer'.toUpperCase(),
                textAlign: TextAlign.center,
                style: TextStyle(
                  color: Colors.teal.shade100,
                  fontSize: 20.0,
                  letterSpacing: 2.5,
                  fontFamily: 'SourceSansPro',
                  fontWeight: FontWeight.bold,
                ),
              ),
              Divider(
                  indent: 150.0,
                  endIndent: 150.0,
                  color: Colors.white,
                  height: 20.0,
                  thickness: 0.3),
              Container(
                margin: EdgeInsets.symmetric(vertical: 10.0, horizontal: 25.0),
                child: TextFormField(
                  initialValue: '+55 (21) 96590-1813',
                  keyboardType: TextInputType.phone,
                  cursorColor: Colors.teal,
                  textAlignVertical: TextAlignVertical.center,
                  style: TextStyle(color: Colors.teal, fontSize: 18.0),
                  decoration: InputDecoration(
                    filled: true,
                    fillColor: Colors.white,
                    border: UnderlineInputBorder(
                      borderSide: BorderSide.none,
                      borderRadius: BorderRadius.all(Radius.circular(5.0)),
                    ),
                    prefixIcon: Icon(
                      Icons.phone,
                      color: Colors.teal,
                      size: 22.0,
                    ),
                  ),
                ),
              ),
              Container(
                margin: EdgeInsets.symmetric(vertical: 10.0, horizontal: 25.0),
                child: TextFormField(
                    initialValue: 'pvictorferreira@hotmail.com',
                    keyboardType: TextInputType.emailAddress,
                    cursorColor: Colors.teal,
                    textAlignVertical: TextAlignVertical.center,
                    style: TextStyle(color: Colors.teal, fontSize: 18.0),
                    decoration: InputDecoration(
                      filled: true,
                      fillColor: Colors.white,
                      border: UnderlineInputBorder(
                        borderSide: BorderSide.none,
                        borderRadius: BorderRadius.all(Radius.circular(5.0)),
                      ),
                      prefixIcon: Icon(
                        Icons.email,
                        color: Colors.teal,
                        size: 22.0,
                      ),
                    )),
              ),
              TextButton(
                child: Text(
                  'Login com Facebook',
                  style: TextStyle(color: Colors.white),
                ),
                onPressed: () {
                  facebookLogin();
                },
                style: ButtonStyle(
                  backgroundColor: MaterialStateProperty.all(Colors.blue[700]),
                ),
              )
            ],
          ),
        ),
      ),
    );
  }
}
