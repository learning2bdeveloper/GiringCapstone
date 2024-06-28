package com.example.giring;

import androidx.appcompat.app.AppCompatActivity;

import android.content.Intent;
import android.os.Bundle;
import android.os.UserManager;
import android.view.View;
import android.widget.Button;
import android.widget.EditText;
import android.widget.Toast;

import com.android.volley.Request;
import com.android.volley.RequestQueue;
import com.android.volley.Response;
import com.android.volley.VolleyError;
import com.android.volley.toolbox.StringRequest;
import com.android.volley.toolbox.Volley;

import org.json.JSONArray;
import org.json.JSONException;
import org.json.JSONObject;

import java.util.HashMap;
import java.util.Map;

public class LoginActivity extends AppCompatActivity implements ipAddress {

    EditText username, password;
    Button btnLogin;
    String Username, responsed, Boss, empFirstName, empLastName;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_login);

        username = (EditText) findViewById(R.id.inputUsername);
        password = (EditText) findViewById(R.id.inputPassword);
        btnLogin = (Button) findViewById(R.id.btnlogin);

        btnLogin.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View view) {
                String empID = username.getText().toString();
                String empPassword = password.getText().toString();

                //Toast.makeText(LoginActivity.this, empID + empPassword, Toast.LENGTH_SHORT).show();
                apiSearch(empID, empPassword);

            }
        });
    }

    public void apiSearch(String empID, String empPassword) {

        RequestQueue queue = Volley.newRequestQueue(getApplicationContext());
        String url ="http://"+ipAddress+"/GiringCapstoneProject/mobile/employeeLogin.php";//hostspot

        StringRequest stringRequest = new StringRequest(Request.Method.POST, url,
                new Response.Listener<String>() {
                    @Override
                    public void onResponse(String response) {

                        try {
                            JSONArray jsonArray = new JSONArray(response);

                            for (int i = 0; i < jsonArray.length(); i++) {
                                JSONObject jsonObject = jsonArray.getJSONObject(i);

                                responsed = jsonObject.getString("response");

                                if(responsed.equals("Login Success!")) {
                                    Username = jsonObject.getString("checkEmployeeID");
                                    Boss = jsonObject.getString("boss");
                                    empFirstName = jsonObject.getString("firstName");
                                    empLastName = jsonObject.getString("lastName");
                                }

                            }
                            if(!responsed.equals("Login Success!") ) {
                                Toast.makeText(LoginActivity.this, responsed, Toast.LENGTH_SHORT).show();
                            }else {
                                Intent intent = new Intent(LoginActivity.this, MainActivity.class);
                                intent.putExtra("Owner", Boss);
                                intent.putExtra("empFirstName", empFirstName);
                                intent.putExtra("empLastName", empLastName);
                                intent.putExtra("employeeID", Username);
                                startActivity(intent);

                            }


                        }catch (JSONException e) {
                            throw new RuntimeException(e);
                        }
                    }
                }, new Response.ErrorListener() {

            @Override
            public void onErrorResponse(VolleyError error) {
                Toast.makeText(LoginActivity.this, error.getMessage(), Toast.LENGTH_LONG).show();
            }
        }){

            protected Map<String, String> getParams(){
                Map<String, String> paramV = new HashMap<>();
                paramV.put("EMPID", empID);
                paramV.put("PASSWORD", empPassword);
                paramV.put("LOGIN", "");
                return paramV;
            }
        };
        queue.add(stringRequest);
    }


}