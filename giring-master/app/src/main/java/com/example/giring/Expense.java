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

public class Expense extends AppCompatActivity implements ipAddress {

    TextView category_budget, remark, amount;
    Spinner expense_category;
    ArrayList optionsList;
    Button btnAddExpense;

    String Boss, empLastName, empFirstName, Username, input_category, input_budget, input_remark, input_amount, responsed;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_expense);

        Intent intent = getIntent();
        Bundle bundle = intent.getExtras();

        empLastName = bundle.getString("emplastname");
        empFirstName = bundle.getString("empfirstname");
        Username = bundle.getString("empID");
        Boss = bundle.getString("Owner");

        category_budget = (TextView) findViewById(R.id.categoryBudget);
        expense_category = (Spinner) findViewById(R.id.expenseNameSpinner);
        remark = (TextView) findViewById(R.id.tvRemark);
        amount = (TextView) findViewById(R.id.tvAmount);

        btnAddExpense = (Button) findViewById(R.id.btnExpenseAdd);

        optionsList = new ArrayList<>();
        fetchDataFromMySQL();

        // Inside onCreate after setting adapter to spinner
        expense_category.setOnItemSelectedListener(new AdapterView.OnItemSelectedListener() {
            @Override
            public void onItemSelected(AdapterView<?> parent, View view, int position, long id) {
                String selectedItem = parent.getItemAtPosition(position).toString();
                fetchCategoryBudget(selectedItem);
            }

            @Override
            public void onNothingSelected(AdapterView<?> parent) {
                // Handle when nothing is selected
            }
        });

        btnAddExpense.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View view) {
                addExpense();
            }
        });
    }

    private void fetchDataFromMySQL() {
        String url = "http://"+ipAddress+"/GiringCapstoneProject/mobile/getExpenseCategory.php";

        RequestQueue queue = Volley.newRequestQueue(getApplicationContext());


        StringRequest stringRequest = new StringRequest(Request.Method.POST, url,
                new Response.Listener<String>() {
                    @Override
                    public void onResponse(String response) {

                        try {
                            JSONArray jsonArray = new JSONArray(response);

                            for (int i = 0; i < jsonArray.length(); i++) {
                                JSONObject jsonObject = jsonArray.getJSONObject(i);

                                String category = jsonObject.getString("category");
                                optionsList.add(category);

                            }

                            ArrayAdapter<String> adapter = new ArrayAdapter<>(Expense.this,
                                    android.R.layout.simple_spinner_item, optionsList);

                            // Specify the layout to use when the list of choices appears
                            adapter.setDropDownViewResource(android.R.layout.simple_spinner_dropdown_item);

                            // Apply the adapter to the spinner
                            expense_category.setAdapter(adapter);





                        }catch (JSONException e) {
                            throw new RuntimeException(e);
                        }
                    }
                }, new Response.ErrorListener() {

            @Override
            public void onErrorResponse(VolleyError error) {
                Toast.makeText(Expense.this, error.getMessage(), Toast.LENGTH_LONG).show();
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
    public void addExpense() {

         input_category = expense_category.getSelectedItem().toString(); // amo ni kwaon
         input_budget = category_budget.getText().toString(); // amo ni kwaon
         input_remark = remark.getText().toString(); // need to convert to int
         input_amount = amount.getText().toString(); // need to convert to int


        // Create an AlertDialog.Builder instance
        AlertDialog.Builder builder = new AlertDialog.Builder(Expense.this);

        // Set the dialog title and message
        builder.setTitle("Do you want to add this to the Expense?")
                .setMessage("Employee Name: " + empFirstName +" "+ empLastName +
                        "\nEmployee ID: " + Username +
                        "\nCategory: " + input_category +
                        "\nBudget: " + input_budget +
                        "\nRemark: " + input_remark +
                        "\nAmount: " + input_amount);


        // Set positive button and its action
        builder.setPositiveButton("OK", new DialogInterface.OnClickListener() {
            public void onClick(DialogInterface dialog, int id) {
                // Perform action when OK is clicked
                String url = "http://"+ipAddress+"/GiringCapstoneProject/mobile/expenseAddFunction.php";
                if(input_category.isEmpty() || input_budget.isEmpty() || input_remark.isEmpty() || input_amount.isEmpty()) {
                    Toast.makeText(Expense.this, "Please fill the missing inputs! :)", Toast.LENGTH_SHORT).show();
                }else {
                    RequestQueue queue = Volley.newRequestQueue(getApplicationContext());

                    StringRequest stringRequest = new StringRequest(Request.Method.POST, url,
                            new Response.Listener<String>() {
                                @Override
                                public void onResponse(String response) {

                                    Toast.makeText(Expense.this, response, Toast.LENGTH_SHORT).show();
                                    try {

                                        JSONArray jsonArray = new JSONArray(response);

                                        for (int i = 0; i < jsonArray.length(); i++) {
                                            JSONObject jsonObject = jsonArray.getJSONObject(i);

                                            responsed = jsonObject.getString("response");
                                        }

                                        if(responsed.contains("Success")) {
                                            category_budget.setText("");
                                            remark.setText("");
                                            amount.setText("");
                                        }
                                        Toast.makeText(Expense.this, responsed, Toast.LENGTH_SHORT).show();

                                    }catch (JSONException e) {
                                        throw new RuntimeException(e);
                                    }
                                }
                            }, new Response.ErrorListener() {

                        @Override
                        public void onErrorResponse(VolleyError error) {
                            Toast.makeText(Expense.this, error.getMessage(), Toast.LENGTH_LONG).show();
                        }
                    }){

                        protected Map<String, String> getParams(){
                            Map<String, String> paramV = new HashMap<>();
                            paramV.put("boss", Boss);
                            paramV.put("empID", Username);
                            paramV.put("androidCategory", input_category);
                            paramV.put("androidBudget", input_budget);
                            paramV.put("androidRemark", input_remark);
                            paramV.put("androidAmount", input_amount);
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

    public void fetchCategoryBudget(String selectedItem) {

            String url = "http://" + ipAddress + "/GiringCapstoneProject/mobile/getexpenseCategoryBudget.php";
            RequestQueue queue = Volley.newRequestQueue(getApplicationContext());

            StringRequest stringRequest = new StringRequest(Request.Method.POST, url,
                    new Response.Listener<String>() {
                        @Override
                        public void onResponse(String response) {
                            try {
                                JSONArray jsonArray = new JSONArray(response);
                                JSONObject jsonObject = jsonArray.getJSONObject(0);
                                String selected = jsonObject.getString("selectedItem");
                                category_budget.setText(selected);


                            } catch (JSONException e) {
                                e.printStackTrace();
                            }
                        }
                    }, new Response.ErrorListener() {
                @Override
                public void onErrorResponse(VolleyError error) {
                    Toast.makeText(Expense.this, error.getMessage(), Toast.LENGTH_LONG).show();
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
