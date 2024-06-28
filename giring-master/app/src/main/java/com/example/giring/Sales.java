package com.example.giring;

import androidx.appcompat.app.AppCompatActivity;

import android.app.AlertDialog;
import android.content.DialogInterface;
import android.content.Intent;
import android.os.Bundle;
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

public class Sales extends AppCompatActivity implements ipAddress{

    String Boss, empID, itemName, empFirstname, empLastName, itemAmount, itemQuantity, itemTotal, itemDescription, responsed;
    EditText etItemName, etItemDescription, etItemAmount, etItemQuantity;
    Button btnAddSales;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_sales);

        Intent intent = getIntent();
        Bundle bundle = intent.getExtras();

        Boss = bundle.getString("Owner");
        empID = bundle.getString("empID");
        empFirstname = bundle.getString("empfirstname");
        empLastName = bundle.getString("emplastname");

        etItemName = (EditText) findViewById(R.id.etItemName);
        etItemDescription = (EditText) findViewById(R.id.etItemDescription);
        etItemAmount = (EditText) findViewById(R.id.etItemAmount);
        etItemQuantity = (EditText) findViewById(R.id.etItemQuantity);
        btnAddSales = (Button) findViewById(R.id.btnSalesAdd);

        btnAddSales.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View view) {
                itemName = etItemName.getText().toString();
                itemDescription = etItemDescription.getText().toString();
                itemAmount = etItemAmount.getText().toString();
                itemQuantity = etItemQuantity.getText().toString();

                int converterAmount = Integer.parseInt(itemAmount);
                int converterQuantity = Integer.parseInt(itemQuantity);

                int total = converterAmount * converterQuantity;
                itemTotal = String.valueOf(total);
                // Create an AlertDialog.Builder instance
                AlertDialog.Builder builder = new AlertDialog.Builder(Sales.this);

                // Set the dialog title and message
                builder.setTitle("Do you want to add this to the Sales?")
                        .setMessage("Employee Name: " + empFirstname +" "+ empLastName +
                                "\nEmployee ID: " + empID +
                                "\nItem Name: " + itemName +
                                "\nItem Description: " + itemDescription +
                                "\nAmount: " + itemAmount +
                                "\nQuantity: " + itemQuantity +
                                "\nTotal: " + itemTotal);

                // Set positive button and its action
                builder.setPositiveButton("OK", new DialogInterface.OnClickListener() {
                    public void onClick(DialogInterface dialog, int id) {
                        // Perform action when OK is clicked
                        String url = "http://"+ipAddress+"/GiringCapstoneProject/mobile/salesAddFunction.php";
                        if(itemName.isEmpty() || itemDescription.isEmpty() || itemAmount.isEmpty() || itemQuantity.isEmpty()) {
                            Toast.makeText(Sales.this, "Please fill the missing inputs! :)", Toast.LENGTH_SHORT).show();
                        }else {
                            RequestQueue queue = Volley.newRequestQueue(getApplicationContext());

                            StringRequest stringRequest = new StringRequest(Request.Method.POST, url,
                                    new Response.Listener<String>() {
                                        @Override
                                        public void onResponse(String response) {

                                            try {
                                                JSONArray jsonArray = new JSONArray(response);

                                                for (int i = 0; i < jsonArray.length(); i++) {
                                                    JSONObject jsonObject = jsonArray.getJSONObject(i);

                                                    responsed = jsonObject.getString("response");

                                                }
                                                if(responsed.contains("Success")) {
                                                    etItemName.setText("");
                                                    etItemDescription.setText("");
                                                    etItemAmount.setText("");
                                                    etItemQuantity.setText("");
                                                }

                                                Toast.makeText(Sales.this, responsed, Toast.LENGTH_SHORT).show();

                                            }catch (JSONException e) {
                                                throw new RuntimeException(e);
                                            }
                                        }
                                    }, new Response.ErrorListener() {

                                @Override
                                public void onErrorResponse(VolleyError error) {
                                    Toast.makeText(Sales.this, error.getMessage(), Toast.LENGTH_LONG).show();
                                }
                            }){

                                protected Map<String, String> getParams(){
                                    Map<String, String> paramV = new HashMap<>();
                                    paramV.put("boss", Boss);
                                    paramV.put("androidEmpFirstName", empFirstname);
                                    paramV.put("androidEmpLastName", empLastName);
                                    paramV.put("empID", empID);
                                    paramV.put("androidItemName", itemName);
                                    paramV.put("androidItemDescription", itemDescription);
                                    paramV.put("androidItemQuantity", itemQuantity);
                                    paramV.put("androidItemAmount", itemAmount);
                                    paramV.put("androidItemTotal", itemTotal);
                                    return paramV;
                                }
                            };
                            queue.add(stringRequest);
                        }
                    }
                });

                // Set negative button and its action
                builder.setNegativeButton("Cancel", new DialogInterface.OnClickListener() {
                    public void onClick(DialogInterface dialog, int id) {
                        // Perform action when Cancel is clicked
                    }
                });


                // Create and display the dialog
                AlertDialog dialog = builder.create();
                dialog.show();

            }
        });
    }
}