package com.example.giring;

import androidx.appcompat.app.AppCompatActivity;


import android.app.AlertDialog;
import android.content.DialogInterface;
import android.content.Intent;
import android.os.Bundle;
import android.view.View;
import android.widget.AdapterView;
import android.widget.ArrayAdapter;
import android.widget.Button;
import android.widget.Spinner;
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

import java.util.ArrayList;
import java.util.HashMap;
import java.util.Map;

public class Revenue extends AppCompatActivity implements ipAddress {

    Spinner spinner;
    ArrayList optionsList;

    String Boss, empID, itemNo, empFirstname, empLastName, itemAmount, itemTax, itemTotal, responsed;

    float amount, tax;


    TextView tvItemDescription, tvItemPrice, tvOrigQuantity, tvItemQuantity, tvItemTaxRate;

    Button btnAddStockControl;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_revenue);
        Intent intent = getIntent();
        Bundle bundle = intent.getExtras();

        Boss = bundle.getString("Owner");
        empID = bundle.getString("empID");
        empFirstname = bundle.getString("empfirstname");
        empLastName = bundle.getString("emplastname");

        tvItemDescription = (TextView) findViewById(R.id.tvRemark); // done
        tvItemPrice = (TextView) findViewById(R.id.tvAmount); // done

        tvItemQuantity = (TextView) findViewById(R.id.tvQuantity); // done
        tvItemTaxRate = (TextView) findViewById(R.id.tvTaxRate); // done




        tvOrigQuantity = (TextView) findViewById(R.id.origQuantity);

        btnAddStockControl = (Button) findViewById(R.id.btnExpenseAdd);

        spinner = (Spinner) findViewById(R.id.expenseNameSpinner); // done

        optionsList = new ArrayList<>();
        fetchDataFromMySQL();

        // Inside onCreate after setting adapter to spinner
        spinner.setOnItemSelectedListener(new AdapterView.OnItemSelectedListener() {
            @Override
            public void onItemSelected(AdapterView<?> parent, View view, int position, long id) {
                String selectedItem = parent.getItemAtPosition(position).toString();
                fetchDescription(selectedItem);
            }

            @Override
            public void onNothingSelected(AdapterView<?> parent) {
                // Handle when nothing is selected
            }
        });

        btnAddStockControl.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View view) {

                String itemName = spinner.getSelectedItem().toString(); // amo ni kwaon
                String itemDescription = tvItemDescription.getText().toString(); // amo ni kwaon
                String itemPrice = tvItemPrice.getText().toString(); // need to convert to int
                String itemQuantity = tvItemQuantity.getText().toString(); // need to convert to int
                String itemTaxRate = tvItemTaxRate.getText().toString(); // need to convert to int

                AddRev(itemName, itemDescription, itemPrice, itemQuantity, itemTaxRate);
            }
        });
    }

    private void AddRev(String itemname, String itemdescription, String itemprice, String itemquantity, String itemtaxrate) {



        if (!itemprice.isEmpty() && !itemquantity.isEmpty()) {
            int price = Integer.parseInt(itemprice);
            int quantity = Integer.parseInt(itemquantity);
            amount = price * quantity;
            itemAmount = String.valueOf(amount);
        }

        if(!itemprice.isEmpty() && !itemtaxrate.isEmpty()) {
            float taxRateConverter = Integer.parseInt(itemtaxrate);
            float priceConverter = Integer.parseInt(itemprice);
            tax = priceConverter * (taxRateConverter / 100);
            float totalConverter = amount + tax;
            itemTotal = String.valueOf(totalConverter);
            itemTax = String.valueOf(tax);
        }

        // Create an AlertDialog.Builder instance
        AlertDialog.Builder builder = new AlertDialog.Builder(Revenue.this);

        // Set the dialog title and message
        builder.setTitle("Do you want to add this to the Stock Control?")
                .setMessage("Employee Name: " + empFirstname +" "+ empLastName +
                            "\nEmployee ID: " + empID +
                            "\nItem No.: " + itemNo +
                            "\nItem Name: " + itemname +
                            "\nItem Description: " + itemdescription +
                            "\nPrice: " + itemprice +
                            "\nQuantity: " + itemquantity +
                            "\nAmount: " + amount +
                            "\nTax Rate: " + itemtaxrate + "%" +
                            "\nTax: " + tax +
                            "\nTotal: " + itemTotal);

        // Set positive button and its action
        builder.setPositiveButton("OK", new DialogInterface.OnClickListener() {
            public void onClick(DialogInterface dialog, int id) {
                // Perform action when OK is clicked
                String url = "http://"+ipAddress+"/GiringCapstoneProject/mobile/revenueAddFunction.php";
            if(itemname.isEmpty() || itemdescription.isEmpty() || itemprice.isEmpty() || itemquantity.isEmpty() || itemtaxrate.isEmpty()) {
                Toast.makeText(Revenue.this, "Please fill the missing inputs! :)", Toast.LENGTH_SHORT).show();
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
                                        tvItemDescription.setText("");
                                        tvItemPrice.setText(""); // done

                                        tvItemQuantity.setText(""); // done
                                        tvItemTaxRate.setText(""); // done

                                    }

                                }catch (JSONException e) {
                                    throw new RuntimeException(e);
                                }
                            }
                        }, new Response.ErrorListener() {

                    @Override
                    public void onErrorResponse(VolleyError error) {
                        Toast.makeText(Revenue.this, error.getMessage(), Toast.LENGTH_LONG).show();
                    }
                }){

                    protected Map<String, String> getParams(){
                        Map<String, String> paramV = new HashMap<>();
                        paramV.put("boss", Boss);
                        paramV.put("empID", empID);
                        paramV.put("androidItemNo", itemNo);
                        paramV.put("androidItemName", itemname);
                        paramV.put("androidItemDescription", itemdescription);
                        paramV.put("androidItemPrice", itemprice);
                        paramV.put("androidItemQuantity", itemquantity);
                        paramV.put("androidItemAmount", itemAmount);
                        paramV.put("androidItemTaxRate", itemtaxrate);
                        paramV.put("androidItemTax", itemTax);
                        paramV.put("androidItemTotal", itemTotal);
                        paramV.put("androidItemOrigQuantity", tvOrigQuantity.getText().toString());
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
    private void fetchDataFromMySQL() {
        String url = "http://"+ipAddress+"/GiringCapstoneProject/mobile/getStockControlitemName.php";

        RequestQueue queue = Volley.newRequestQueue(getApplicationContext());


        StringRequest stringRequest = new StringRequest(Request.Method.POST, url,
                new Response.Listener<String>() {
                    @Override
                    public void onResponse(String response) {

                        try {
                            JSONArray jsonArray = new JSONArray(response);

                            for (int i = 0; i < jsonArray.length(); i++) {
                                JSONObject jsonObject = jsonArray.getJSONObject(i);

                                String itemName = jsonObject.getString("itemName");
                                optionsList.add(itemName);

                            }

                            ArrayAdapter<String> adapter = new ArrayAdapter<>(Revenue.this,
                                    android.R.layout.simple_spinner_item, optionsList);

                            // Specify the layout to use when the list of choices appears
                            adapter.setDropDownViewResource(android.R.layout.simple_spinner_dropdown_item);

                            // Apply the adapter to the spinner
                            spinner.setAdapter(adapter);





                        }catch (JSONException e) {
                            throw new RuntimeException(e);
                        }
                    }
                }, new Response.ErrorListener() {

            @Override
            public void onErrorResponse(VolleyError error) {
                Toast.makeText(Revenue.this, error.getMessage(), Toast.LENGTH_LONG).show();
            }
        }){

            protected Map<String, String> getParams(){
                Map<String, String> paramV = new HashMap<>();
                paramV.put("boss", Boss);
                return paramV;
            }
        };
        queue.add(stringRequest);
    }

    private void fetchDescription(String selectedItem) {
        String url = "http://" + ipAddress + "/GiringCapstoneProject/mobile/getItemDescription.php";
        RequestQueue queue = Volley.newRequestQueue(getApplicationContext());

        StringRequest stringRequest = new StringRequest(Request.Method.POST, url,
                new Response.Listener<String>() {
                    @Override
                    public void onResponse(String response) {
                        try {
                            JSONArray jsonArray = new JSONArray(response);
                            JSONObject jsonObject = jsonArray.getJSONObject(0);
                            String itemDescription = jsonObject.getString("itemDescription");
                            String itemOrigQuantity = jsonObject.getString("itemOrigQuantity");
                            String itemPrice = jsonObject.getString("itemPrice");
                            itemNo = jsonObject.getString("itemNumber");
                            tvItemDescription.setText(itemDescription);
                            tvOrigQuantity.setText(itemOrigQuantity);
                            tvItemPrice.setText(itemPrice);

                        } catch (JSONException e) {
                            e.printStackTrace();
                        }
                    }
                }, new Response.ErrorListener() {
            @Override
            public void onErrorResponse(VolleyError error) {
                Toast.makeText(Revenue.this, error.getMessage(), Toast.LENGTH_LONG).show();
            }
        }) {
            @Override
            protected Map<String, String> getParams() {
                Map<String, String> params = new HashMap<>();
                params.put("boss", Boss);
                params.put("selectedItem", selectedItem);
                return params;
            }
        };
        queue.add(stringRequest);
    }

}

