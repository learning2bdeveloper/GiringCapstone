<?php
// A Note on HDMF Contributions for 2023
// Last March 2023, Pag-IBIG declared it would postpone its scheduled contribution increase for the year. The hike would raise an employee’s minimum contribution to ₱150.

// Instead, Pag-IBIG’s management moved the increase to January 2024. Watch this space for updates on the new HDMF contribution rate once implemented.

function showCalculatedTaxDatas($pay_frequency, $rate, $daily_work_hours, $workDaysPerWeek) { 
// $empID = $_POST['empID']; 

// $Salary = new Salary;
// $stmt = $Salary->get_employees_salary_data($empID);
// //`pay_frequency`, `pay_per_date`, `work_hrs`, `work_days_per_week`, `#_of_Holidays`;
// $data;
// while($row = $stmt->fetch()) {
//     $data = $row;
// }
// $pay_frequency = $data['pay_frequency'];
// $rate = $data['rate'];
// $daily_work_hours = $data['daily_work_hours'];
// $workDaysPerWeek = $data['work_days_per_week'];

$pay_frequency = $pay_frequency;
$rate = $rate;
$daily_work_hours = $daily_work_hours;
$workDaysPerWeek = $workDaysPerWeek;


$in_an_hour = 0; // Your hourly rate
$in_a_day = 0; // Daily earnings
$in_a_week = 0; // Weekly earnings
$semi_monthly = 0; // Semi-monthly earnings
$in_a_month = 0; // Monthly earnings
$in_a_year = 0; // Yearly earnings

$income_tax = 0; // done

$EC = 0;
$SSS = 0;
$EmployeeSSS = 0;
$EmployeerSSS = 0;

$pagIbig = 0;
$EmployeePagIbig = 0;
$EmployeerPagIbig = 0;

$Philhealth = 0;
$EmployeePhilhealth = 0;
$EmployeerPhilhealth = 0;

$total_tax = 0;
$total_salary = 0;
$total_employeer_payment = 0;

switch($pay_frequency) { // The chosen pay frequency
    case 'Hourly': 
            $in_an_hour = $rate;
            $in_a_day = $in_an_hour * $daily_work_hours;
            $in_a_week = $in_a_day * $workDaysPerWeek;
            $in_a_month = $in_a_week * 4; // Calculate monthly earnings

             // Your monthly rate
            if(20833 <= $in_a_month AND $in_a_month <= 33332) { // category 2
                $income_tax = 0 + ($in_a_month - 20833) * 0.2;// done
            }

            if(33333 <= $in_a_month AND $in_a_month <= 66666) { // category 3
                $income_tax = 2500 + ($in_a_month - 33333) * 0.25;// done
            }

            if(66667 <= $in_a_month AND $in_a_month <= 166666) { // category 4
                $income_tax = 10833.33 + ($in_a_month - 66667) * 0.3;// done
            }

            if(166667 <= $in_a_month AND $in_a_month <= 666666) { // category 5
                $income_tax = 40833.33 + ($in_a_month - 166667) * 0.32;// done
            }

            if($in_a_month >= 666667) { // category 6
                $income_tax = 200833.33 + ($in_a_month - 666667) * 0.35;// done
            }

            //SSS /////////////////////////////////

            if($in_a_month >= 4000 AND $in_a_month <= 14999) {
                $EC = 10;
                $SSS = $EC + $in_a_month * 0.14; // 14%
                $EmployeeSSS = $in_a_month * 0.045; // 4.5%
                $EmployeerSSS = $EC + $in_a_month * 0.095; // 9.5%
            }
            
            if($in_a_month >= 15000 AND $in_a_month <= 30000) {
                $EC = 30;
                $SSS = $EC + $in_a_month * 0.14; // 14%
                $EmployeeSSS = $in_a_month * 0.045; // 4.5%
                $EmployeerSSS = $EC + $in_a_month * 0.095; // 9.5%
            }

            if($in_a_month > 30000) {
                $MAXIMUM_Monthly_Salary_Credit  = 30000; //(MSC)
                $EC = 30;
                $SSS = $EC + $MAXIMUM_Monthly_Salary_Credit * 0.14; // 14%
                $EmployeeSSS = $MAXIMUM_Monthly_Salary_Credit * 0.045; // 4.5%
                $EmployeerSSS = $EC + $MAXIMUM_Monthly_Salary_Credit * 0.095; // 9.5%
            }

            // PAG-IBIG /////////////////////////////

            if($in_a_month <= 1500) {
                $pagIbig = $in_a_month * 0.03; // 3%
                $EmployeePagIbig = $in_a_month * 0.01; // 1%
                $EmployeerPagIbig = $in_a_month * 0.02; // 2%
            }
            
            if($in_a_month > 1500) {
                $pagIbig = $in_a_month * 0.04; // 4%
                $EmployeePagIbig = $in_a_month * 0.02; // 2%
                $EmployeerPagIbig = $in_a_month * 0.02; // 2%
                
                if($EmployeerPagIbig > 5000) { // bawal mag subra balayran ka employer 5k
                    $EmployeerPagIbig = 5000;
                }
            }

            // PHILHEALTH /////////////////////////////

            if($in_a_month >= 10000 AND $in_a_month <= 80000) {
                $Philhealth = $in_a_month * 0.04; // 4%
                $EmployeePhilhealth = $Philhealth / 2; // divided by 2 // tungaon ka employee kag employeer
                $EmployeerPhilhealth = $Philhealth / 2; // divided by 2
                
            }

            if($in_a_month > 80000) { // max lng daw is 80k
                $MAXIMUM_Monthly_Salary_Credit = 80000;
                $Philhealth = $MAXIMUM_Monthly_Salary_Credit * 0.04; // 4%
                $EmployeePhilhealth = $Philhealth / 2; // divided by 2 // tungaon ka employee kag employeer
                $EmployeerPhilhealth = $Philhealth / 2; // divided by 2
            }

            $total_tax = $Philhealth + $SSS + $pagIbig + $income_tax;
            $total_salary = $in_a_month - ($EmployeePhilhealth + $EmployeePagIbig + $EmployeeSSS + $income_tax);
            $total_employeer_payment = $EmployeerPagIbig + $EmployeerPhilhealth + $EmployeerSSS;
            $semi_monthly = $in_a_month / 2; // Calculate semi-monthly earnings (half of monthly earnings) // Calculate quarterly earnings
            $in_a_year = $in_a_month * 12; // Calculate yearly earnings
            break;

    case 'Daily':
            $in_a_day = $rate; // Your daily rate
                if(685 <= $in_a_day AND $in_a_day <= 1095) { // category 2
                    $income_tax = 0 + ($in_a_day - 685) * 0.2;// done
                }

                if(1096 <= $in_a_day AND $in_a_day <= 2191) { // category 3
                   $income_tax = 82.19 + ($in_a_day - 1096) * 0.25;// done
                }

                if(2192 <= $in_a_day AND $in_a_day <= 5478) { // category 4
                    $income_tax = 356.16 + ($in_a_day - 2192) * 0.3;// done
                }

                if(5479 <= $in_a_day AND $in_a_day <= 21917) { // category 5
                    $income_tax = 1342.47 + ($in_a_day - 5479) * 0.32; // done
                }

                if($in_a_day >= 21918) { // category 6
                    $income_tax = 6602.74 + ($in_a_day - 21918) * 0.35; // done
                }

            $in_an_hour = $in_a_day / $daily_work_hours; // Calculate hourly rate based on daily earnings
            $in_a_week = $in_a_day * $workDaysPerWeek; // Calculate weekly earnings
            $in_a_month = $in_a_week * 4;

                //SSS ////////////////////////////////

                if($in_a_month >= 4000 AND $in_a_month <= 14999) {
                    $EC = 10;
                    $SSS = $EC + $in_a_month * 0.14; // 14%
                    $EmployeeSSS = $in_a_month * 0.045; // 4.5%
                    $EmployeerSSS = $EC + $in_a_month * 0.095; // 9.5%
                }
                
                if($in_a_month >= 15000 AND $in_a_month <= 30000) {
                    $EC = 30;
                    $SSS = $EC + $in_a_month * 0.14; // 14%
                    $EmployeeSSS = $in_a_month * 0.045; // 4.5%
                    $EmployeerSSS = $EC + $in_a_month * 0.095; // 9.5%
                }

                if($in_a_month > 30000) {
                    $MAXIMUM_Monthly_Salary_Credit  = 30000; //(MSC)
                    $EC = 30;
                    $SSS = $EC + $MAXIMUM_Monthly_Salary_Credit * 0.14; // 14%
                    $EmployeeSSS = $MAXIMUM_Monthly_Salary_Credit * 0.045; // 4.5%
                    $EmployeerSSS = $EC + $MAXIMUM_Monthly_Salary_Credit * 0.095; // 9.5%
                }

                // PAG-IBIG /////////////////////////////

                if($in_a_month <= 1500) {
                    $pagIbig = $in_a_month * 0.03; // 3%
                    $EmployeePagIbig = $in_a_month * 0.01; // 1%
                    $EmployeerPagIbig = $in_a_month * 0.02; // 2%
                }
                
                if($in_a_month > 1500) {
                    $pagIbig = $in_a_month * 0.04; // 4%
                    $EmployeePagIbig = $in_a_month * 0.02; // 2%
                    $EmployeerPagIbig = $in_a_month * 0.02; // 2%
                    
                    if($EmployeerPagIbig > 5000) { // bawal mag subra balayran ka employer 5k
                        $EmployeerPagIbig = 5000;
                    }
                }

                // PHILHEALTH /////////////////////////////

                if($in_a_month >= 10000 AND $in_a_month <= 80000) {
                    $Philhealth = $in_a_month * 0.04; // 4%
                    $EmployeePhilhealth = $Philhealth / 2; // divided by 2 // tungaon ka employee kag employeer
                    $EmployeerPhilhealth = $Philhealth / 2; // divided by 2
                    
                }

                if($in_a_month > 80000) { // max lng daw is 80k
                    $MAXIMUM_Monthly_Salary_Credit = 80000;
                    $Philhealth = $MAXIMUM_Monthly_Salary_Credit * 0.04; // 4%
                    $EmployeePhilhealth = $Philhealth / 2; // divided by 2 // tungaon ka employee kag employeer
                    $EmployeerPhilhealth = $Philhealth / 2; // divided by 2
                }

                $total_tax = $Philhealth + $SSS + $pagIbig + $income_tax;
                $total_salary = $in_a_month - ($EmployeePhilhealth + $EmployeePagIbig + $EmployeeSSS + $income_tax);
                $total_employeer_payment = $EmployeerPagIbig + $EmployeerPhilhealth + $EmployeerSSS;

            $semi_monthly = $in_a_month / 2;
            $in_a_year = $in_a_month * 12;
            break;
    case 'Weekly':
            $in_a_week = $rate; // Your weekly rate
                if(4808 <= $in_a_week AND $in_a_week <= 7691) { // category 2
                    $income_tax = 0 + ($in_a_week - 4808) * 0.2;// done
                }

                if(7692 <= $in_a_week AND $in_a_week <= 15384) { // category 3
                    $income_tax = 576.92 + ($in_a_week - 7692) * 0.25;// done
                }

                if(15385 <= $in_a_week AND $in_a_week <= 38461) { // category 4
                    $income_tax = 2500 + ($in_a_week - 15385) * 0.3;// done
                }

                if(38462 <= $in_a_week AND $in_a_week <= 153845) { // category 5
                    $income_tax = 9423.08 + ($in_a_week - 38462) * 0.32;// done
                }

                if($in_a_week >= 153846) { // category 6
                    $income_tax = 46346.15 + ($in_a_week - 153846) * 0.35;// done
                }

            $in_a_day = $in_a_week / $workDaysPerWeek; // Calculate daily earnings based on weekly rate
            $in_an_hour = $in_a_day / $daily_work_hours; // Calculate hourly rate based on daily earnings
            $in_a_month = $in_a_week * 4;

                //SSS //////////////////////////////////

                if($in_a_month >= 4000 AND $in_a_month <= 14999) {
                    $EC = 10;
                    $SSS = $EC + $in_a_month * 0.14; // 14%
                    $EmployeeSSS = $in_a_month * 0.045; // 4.5%
                    $EmployeerSSS = $EC + $in_a_month * 0.095; // 9.5%
                }
                
                if($in_a_month >= 15000 AND $in_a_month <= 30000) {
                    $EC = 30;
                    $SSS = $EC + $in_a_month * 0.14; // 14%
                    $EmployeeSSS = $in_a_month * 0.045; // 4.5%
                    $EmployeerSSS = $EC + $in_a_month * 0.095; // 9.5%
                }

                if($in_a_month > 30000) {
                    $MAXIMUM_Monthly_Salary_Credit  = 30000; //(MSC)
                    $EC = 30;
                    $SSS = $EC + $MAXIMUM_Monthly_Salary_Credit * 0.14; // 14%
                    $EmployeeSSS = $MAXIMUM_Monthly_Salary_Credit * 0.045; // 4.5%
                    $EmployeerSSS = $EC + $MAXIMUM_Monthly_Salary_Credit * 0.095; // 9.5%
                }

                // PAG-IBIG /////////////////////////////

                if($in_a_month <= 1500) {
                    $pagIbig = $in_a_month * 0.03; // 3%
                    $EmployeePagIbig = $in_a_month * 0.01; // 1%
                    $EmployeerPagIbig = $in_a_month * 0.02; // 2%
                }
                
                if($in_a_month > 1500) {
                    $pagIbig = $in_a_month * 0.04; // 4%
                    $EmployeePagIbig = $in_a_month * 0.02; // 2%
                    $EmployeerPagIbig = $in_a_month * 0.02; // 2%
                    
                    if($EmployeerPagIbig > 5000) { // bawal mag subra balayran ka employer 5k
                        $EmployeerPagIbig = 5000;
                    }
                }

                // PHILHEALTH /////////////////////////////

                if($in_a_month >= 10000 AND $in_a_month <= 80000) {
                    $Philhealth = $in_a_month * 0.04; // 4%
                    $EmployeePhilhealth = $Philhealth / 2; // divided by 2 // tungaon ka employee kag employeer
                    $EmployeerPhilhealth = $Philhealth / 2; // divided by 2
                    
                }

                if($in_a_month > 80000) { // max lng daw is 80k
                    $MAXIMUM_Monthly_Salary_Credit = 80000;
                    $Philhealth = $MAXIMUM_Monthly_Salary_Credit * 0.04; // 4%
                    $EmployeePhilhealth = $Philhealth / 2; // divided by 2 // tungaon ka employee kag employeer
                    $EmployeerPhilhealth = $Philhealth / 2; // divided by 2
                }

                $total_tax = $Philhealth + $SSS + $pagIbig + $income_tax;
                $total_salary = $in_a_month - ($EmployeePhilhealth + $EmployeePagIbig + $EmployeeSSS + $income_tax);
                $total_employeer_payment = $EmployeerPagIbig + $EmployeerPhilhealth + $EmployeerSSS;

            $semi_monthly = $in_a_month / 2;   
            $in_a_year = $in_a_month * 12;
            break;
    case 'Semi-Monthly':
            $semi_monthly = $rate; // Your bi-weekly rate
                if(10417 <= $semi_monthly AND $semi_monthly <= 16666) { // category 2
                    $income_tax = 0 + ($semi_monthly - 10417) * 0.2;// done
                }

                if(16667 <= $semi_monthly AND $semi_monthly <= 33332) { // category 3
                    $income_tax = 1250 + ($semi_monthly - 16667) * 0.25;// done
                }

                if(33333 <= $semi_monthly AND $semi_monthly <= 83332) { // category 4
                    $income_tax = 5416.67 + ($semi_monthly - 33333) * 0.3;// done
                }

                if(83333 <= $semi_monthly AND $semi_monthly <= 333332) { // category 5
                    $income_tax = 20416.67 + ($semi_monthly - 83333) * 0.32;// done
                }

                if($semi_monthly >= 333333) { // category 6
                    $income_tax = 100416.67 + ($semi_monthly - 333333) * 0.35;// done
                }

            $in_a_week = $semi_monthly / 2; // Calculate weekly earnings based on bi-weekly rate
            $in_a_day = $in_a_week / $workDaysPerWeek; // Calculate daily earnings based on weekly rate
            $in_an_hour = $in_a_day / $daily_work_hours; // Calculate hourly rate based on daily earnings
            $in_a_month = $in_a_week * 4;

                //SSS ///////////////////////////////////

                if($in_a_month >= 4000 AND $in_a_month <= 14999) {
                    $EC = 10;
                    $SSS = $EC + $in_a_month * 0.14; // 14%
                    $EmployeeSSS = $in_a_month * 0.045; // 4.5%
                    $EmployeerSSS = $EC + $in_a_month * 0.095; // 9.5%
                    }
                    
                    if($in_a_month >= 15000 AND $in_a_month <= 30000) {
                        $EC = 30;
                        $SSS = $EC + $in_a_month * 0.14; // 14%
                        $EmployeeSSS = $in_a_month * 0.045; // 4.5%
                        $EmployeerSSS = $EC + $in_a_month * 0.095; // 9.5%
                    }

                    if($in_a_month > 30000) {
                        $MAXIMUM_Monthly_Salary_Credit  = 30000; //(MSC)
                        $EC = 30;
                        $SSS = $EC + $MAXIMUM_Monthly_Salary_Credit * 0.14; // 14%
                        $EmployeeSSS = $MAXIMUM_Monthly_Salary_Credit * 0.045; // 4.5%
                        $EmployeerSSS = $EC + $MAXIMUM_Monthly_Salary_Credit * 0.095; // 9.5%
                    }

                // PAG-IBIG /////////////////////////////

                if($in_a_month <= 1500) {
                    $pagIbig = $in_a_month * 0.03; // 3%
                    $EmployeePagIbig = $in_a_month * 0.01; // 1%
                    $EmployeerPagIbig = $in_a_month * 0.02; // 2%
                }
                
                if($in_a_month > 1500) {
                    $pagIbig = $in_a_month * 0.04; // 4%
                    $EmployeePagIbig = $in_a_month * 0.02; // 2%
                    $EmployeerPagIbig = $in_a_month * 0.02; // 2%
                    
                    if($EmployeerPagIbig > 5000) { // bawal mag subra balayran ka employer 5k
                        $EmployeerPagIbig = 5000;
                    }
                }
            
                // PHILHEALTH /////////////////////////////

                if($in_a_month >= 10000 AND $in_a_month <= 80000) {
                    $Philhealth = $in_a_month * 0.04; // 4%
                    $EmployeePhilhealth = $Philhealth / 2; // divided by 2 // tungaon ka employee kag employeer
                    $EmployeerPhilhealth = $Philhealth / 2; // divided by 2
                    
                }

                if($in_a_month > 80000) { // max lng daw is 80k
                    $MAXIMUM_Monthly_Salary_Credit = 80000;
                    $Philhealth = $MAXIMUM_Monthly_Salary_Credit * 0.04; // 4%
                    $EmployeePhilhealth = $Philhealth / 2; // divided by 2 // tungaon ka employee kag employeer
                    $EmployeerPhilhealth = $Philhealth / 2; // divided by 2
                }

                $total_tax = $Philhealth + $SSS + $pagIbig + $income_tax;
                $total_salary = $in_a_month - ($EmployeePhilhealth + $EmployeePagIbig + $EmployeeSSS + $income_tax);
                $total_employeer_payment = $EmployeerPagIbig + $EmployeerPhilhealth + $EmployeerSSS;

            $in_a_year = $in_a_month * 12;
            break;
    case 'Monthly':
            $in_a_month = $rate; // Your monthly rate
                if(20833 <= $in_a_month AND $in_a_month <= 33332) { // category 2
                    $income_tax = 0 + ($in_a_month - 20833) * 0.2;// done
                }

                if(33333 <= $in_a_month AND $in_a_month <= 66666) { // category 3
                    $income_tax = 2500 + ($in_a_month - 33333) * 0.25;// done
                }

                if(66667 <= $in_a_month AND $in_a_month <= 166666) { // category 4
                    $income_tax = 10833.33 + ($in_a_month - 66667) * 0.3;// done
                }

                if(166667 <= $in_a_month AND $in_a_month <= 666666) { // category 5
                    $income_tax = 40833.33 + ($in_a_month - 166667) * 0.32;// done
                }

                if($in_a_month >= 666667) { // category 6
                    $income_tax = 200833.33 + ($in_a_month - 666667) * 0.35;// done
                }

                //SSS /////////////////////////////////

                if($in_a_month >= 4000 AND $in_a_month <= 14999) {
                    $EC = 10;
                    $SSS = $EC + $in_a_month * 0.14; // 14%
                    $EmployeeSSS = $in_a_month * 0.045; // 4.5%
                    $EmployeerSSS = $EC + $in_a_month * 0.095; // 9.5%
                }
                
                if($in_a_month >= 15000 AND $in_a_month <= 30000) {
                    $EC = 30;
                    $SSS = $EC + $in_a_month * 0.14; // 14%
                    $EmployeeSSS = $in_a_month * 0.045; // 4.5%
                    $EmployeerSSS = $EC + $in_a_month * 0.095; // 9.5%
                }

                if($in_a_month > 30000) {
                    $MAXIMUM_Monthly_Salary_Credit  = 30000; //(MSC)
                    $EC = 30;
                    $SSS = $EC + $MAXIMUM_Monthly_Salary_Credit * 0.14; // 14%
                    $EmployeeSSS = $MAXIMUM_Monthly_Salary_Credit * 0.045; // 4.5%
                    $EmployeerSSS = $EC + $MAXIMUM_Monthly_Salary_Credit * 0.095; // 9.5%
                }

                // PAG-IBIG /////////////////////////////

                if($in_a_month <= 1500) {
                    $pagIbig = $in_a_month * 0.03; // 3%
                    $EmployeePagIbig = $in_a_month * 0.01; // 1%
                    $EmployeerPagIbig = $in_a_month * 0.02; // 2%
                }
                
                if($in_a_month > 1500) {
                    $pagIbig = $in_a_month * 0.04; // 4%
                    $EmployeePagIbig = $in_a_month * 0.02; // 2%
                    $EmployeerPagIbig = $in_a_month * 0.02; // 2%
                    
                    if($EmployeerPagIbig > 5000) { // bawal mag subra balayran ka employer 5k
                        $EmployeerPagIbig = 5000;
                    }
                }

                // PHILHEALTH /////////////////////////////

                if($in_a_month >= 10000 AND $in_a_month <= 80000) {
                    $Philhealth = $in_a_month * 0.04; // 4%
                    $EmployeePhilhealth = $Philhealth / 2; // divided by 2 // tungaon ka employee kag employeer
                    $EmployeerPhilhealth = $Philhealth / 2; // divided by 2
                    
                }

                if($in_a_month > 80000) { // max lng daw is 80k
                    $MAXIMUM_Monthly_Salary_Credit = 80000;
                    $Philhealth = $MAXIMUM_Monthly_Salary_Credit * 0.04; // 4%
                    $EmployeePhilhealth = $Philhealth / 2; // divided by 2 // tungaon ka employee kag employeer
                    $EmployeerPhilhealth = $Philhealth / 2; // divided by 2
                }

                $total_tax = $Philhealth + $SSS + $pagIbig + $income_tax;
                $total_salary = $in_a_month - ($EmployeePhilhealth + $EmployeePagIbig + $EmployeeSSS + $income_tax);
                $total_employeer_payment = $EmployeerPagIbig + $EmployeerPhilhealth + $EmployeerSSS;

            $in_a_week = $in_a_month / 4; // Calculate weekly earnings based on monthly rate
            $in_a_day = $in_a_week / $workDaysPerWeek; // Calculate daily earnings based on weekly rate
            $in_an_hour = $in_a_day / $daily_work_hours; // Calculate hourly rate based on daily earnings
            $semi_monthly = $in_a_month / 2;
            $in_a_year = $in_a_month * 12;
            break;
    default:
        $in_an_hour = 0; // Your hourly rate
        $in_a_day = 0; // Daily earnings
        $in_a_week = 0; // Weekly earnings
        $semi_monthly = 0; // Semi-monthly earnings
        $in_a_month = 0; // Monthly earnings
        $in_a_year = 0; // Yearly earnings

    }
    $datas = array(
        "income_tax" => $income_tax,
        "total_tax" => $total_tax,
        "total_salary" => $total_salary,
        "total_employer" => $total_employeer_payment,
        "in_an_hour" => $in_an_hour,
        "in_a_day" => $in_a_day,
        "in_a_week" => $in_a_week,
        "semi_monthly" => $semi_monthly,
        "in_a_month" => $in_a_month,
        "in_a_year" => $in_a_year,
        "SSS" => $SSS,
        "EmployeeSSS" => $EmployeeSSS,
        "EmployeerSSS" => $EmployeerSSS,
        "pagIbig" => $pagIbig,
        "EmployeePagIbig" => $EmployeePagIbig,
        "EmployeerPagIbig" => $EmployeerPagIbig,
        "Philhealth" => $Philhealth,
        "EmployeePhilhealth" => $EmployeePhilhealth,
        "EmployeerPhilhealth" => $EmployeerPhilhealth


    );


    return $datas;
}
    