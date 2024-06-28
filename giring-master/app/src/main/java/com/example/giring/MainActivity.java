package com.example.giring;

import androidx.appcompat.app.AppCompatActivity;


import android.app.AlertDialog;
import android.content.DialogInterface;
import android.content.Intent;
import android.media.Image;
import android.os.Bundle;
import android.view.View;
import android.view.Window;
import android.widget.ImageView;
import android.widget.TextView;
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


public class MainActivity extends AppCompatActivity implements ipAddress {

    ImageView imgButton, imgButton1, imgLogout, imgButton2, imgButton4;
    TextView TVemployeeName;

    String responsed, Boss, Username, empFirstName, empLastName, empIncomeTax, empTotalTax, empTotalSalary, SSS, empSSS, PagIbig, empPagIbig, Philhealth, empPhilhealth, empPayroll;

    Intent i;
    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_main);
        Intent intent = getIntent();
        Bundle bundle = intent.getExtras();

         empLastName = bundle.getString("empLastName");
         empFirstName = bundle.getString("empFirstName");
         Username = bundle.getString("employeeID");
         Boss = bundle.getString("Owner");

        TVemployeeName = findViewById(R.id.textView13);
        imgLogout = (ImageView) findViewById(R.id.imageViewLogout);
        TVemployeeName.setText(empFirstName + " " +empLastName);

        imgButton = (ImageView) findViewById(R.id.imageButton);
        imgButton1 = (ImageView) findViewById(R.id.imageButton1);
        imgButton2 = (ImageView) findViewById(R.id.imageButton2);
        imgButton4 = (ImageView) findViewById(R.id.imageButton4);

        imgButton.setOnClickListener(new View.OnClickListener() { // stockControl
            @Override
            public void onClick(View view) {
                i = new Intent(MainActivity.this, Revenue.class);
                i.putExtra("Owner", Boss);
                i.putExtra("empID", Username);
                i.putExtra("empfirstname", empFirstName);
                i.putExtra("emplastname", empLastName);
                startActivity(i);
            }
        });

        imgButton1.setOnClickListener(new View.OnClickListener() { // expense
            @Override
            public void onClick(View view) {
                i = new Intent(MainActivity.this, Expense.class);
                i.putExtra("Owner", Boss);
                i.putExtra("empID", Username);
                i.putExtra("empfirstname", empFirstName);
                i.putExtra("emplastname", empLastName);
                startActivity(i);
            }
        });

        imgButton2.setOnClickListener(new View.OnClickListener() { // Sales
            @Override
            public void onClick(View view) {
                i = new Intent(MainActivity.this, Sales.class);
                i.putExtra("Owner", Boss);
                i.putExtra("empID", Username);
                i.putExtra("empfirstname", empFirstName);
                i.putExtra("emplastname", empLastName);
                startActivity(i);
            }
        });

        String url = "http://"+ipAddress+"/GiringCapstoneProject/mobile/getEmployeeSalaryInfo.php";

        RequestQueue queue = Volley.newRequestQueue(getApplicationContext());

        StringRequest stringRequest = new StringRequest(Request.Method.POST, url,
                new Response.Listener<String>() {
                    @Override
                    public void onResponse(String response) {


                        try {

                            JSONArray jsonArray = new JSONArray(response);

                            for (int i = 0; i < jsonArray.length(); i++) {
                                JSONObject jsonObject = jsonArray.getJSONObject(i);

                                empIncomeTax = jsonObject.getString("income_tax");
                                empTotalTax = jsonObject.getString("total_tax");
                                empTotalSalary = jsonObject.getString("total_salary");
                                SSS = jsonObject.getString("SSS");
                                empSSS = jsonObject.getString("EmployeeSSS");
                                PagIbig = jsonObject.getString("pagIbig");
                                empPagIbig = jsonObject.getString("EmployeePagIbig");
                                Philhealth = jsonObject.getString("Philhealth");
                                empPhilhealth = jsonObject.getString("EmployeePhilhealth");
                                empPayroll = jsonObject.getString("Pay_date");
                            }



                        }catch (JSONException e) {
                            throw new RuntimeException(e);
                        }
                    }
                }, new Response.ErrorListener() {

            @Override
            public void onErrorResponse(VolleyError error) {
                Toast.makeText(MainActivity.this, error.getMessage(), Toast.LENGTH_LONG).show();
            }
        }){

            protected Map<String, String> getParams(){
                Map<String, String> paramV = new HashMap<>();
                paramV.put("boss", Boss);
                paramV.put("empID", Username);
                return paramV;
            }
        };
        queue.add(stringRequest);

        imgButton4.setOnClickListener(new View.OnClickListener() { // payroll
            @Override
            public void onClick(View view) {



                // Create an AlertDialog.Builder instance
                AlertDialog.Builder builder = new AlertDialog.Builder(MainActivity.this);

                // Set the dialog title and message
                builder.setTitle("Do you want to add this to the Expense?")
                        .setMessage("Employee Name: " + empFirstName +" "+ empLastName +
                                "\nEmployee ID: " + Username +
                                "\nPay Date: " + empPayroll +
                                "\nIncome Tax: " + empIncomeTax +
                                "\nTotal Tax: " + empTotalTax +
                                "\nTotal Salary: " + empTotalSalary +
                                "\nSSS: " + SSS +
                                "\nMy SSS Tax: " + empSSS +
                                "\nPag-Ibig: " + PagIbig +
                                "\nMy Pag-Ibig Tax: " + empPagIbig +
                                "\nPhilhealth: " + Philhealth +
                                "\nMy Philhealth Tax: " + empPhilhealth );




                builder.setPositiveButton("Exit", new DialogInterface.OnClickListener() {
                    public void onClick(DialogInterface dialog, int id) {
                        // Perform action when OK is clicked


                    }
                });



                // Create and display the dialog
                AlertDialog dialog = builder.create();
                dialog.show();


            }
        });


        imgLogout.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View view) {
                RequestQueue queue = Volley.newRequestQueue(getApplicationContext());
                String url ="http://"+ipAddress+"/GiringCapstoneProject/mobile/logout.php";

                StringRequest stringRequest = new StringRequest(Request.Method.POST, url,
                        new Response.Listener<String>() {
                            @Override
                            public void onResponse(String response) {

                                i = new Intent(MainActivity.this, LoginActivity.class);
                                startActivity(i);
                            }
                        }, new Response.ErrorListener() {

                    @Override
                    public void onErrorResponse(VolleyError error) {
                        Toast.makeText(MainActivity.this, error.getMessage(), Toast.LENGTH_LONG).show();
                    }
                }){

                    protected Map<String, String> getParams(){
                        Map<String, String> paramV = new HashMap<>();
                        paramV.put("EMPID", Username);
                        return paramV;
                    }
                };
                queue.add(stringRequest);
            }
        });
    }
}