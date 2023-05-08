<?php

/*
 * The MIT License
 *
 * Copyright 2019 Dilshan  Jayasnka.
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 */

if (!function_exists("user_can")) {

    function user_can($user, $action)
    {
        $permision = get_user_actions($action);
        if (in_array($user->user_type, $permision)) {
            return TRUE;
        }
        return FALSE;
    }
}
if (!function_exists("get_user_actions")) {

    function get_user_actions($action)
    {
        $permisions = array(
            CAN_SEND_SMS_TO_CUSTOMERS => array("superadmin", "admin", "admin_lvl2", "caler_lvl_1"),
            CAN_EDIT_UN_APPROVED_CUSTOMERS => array("superadmin", "admin", "admin_lvl2", "caler_lvl_1", "manager", "makerting_exe"),
            CAN_APPROVE_CUSTOMER => array("superadmin", "admin", "admin_lvl2", "manager", "makerting_exe"),
            CAN_CUSTOMER_INSERT => array("superadmin", "admin", "admin_lvl2", "caler_lvl_1", "manager", "makerting_exe", "sales_aget", "clerk_lvl_2"),
            CAN_CUSTOMER_EDIT => array("superadmin", "admin", "admin_lvl2", "caler_lvl_1"),
            CAN_ADJUST_STOCK => array("superadmin", "admin", "admin_lvl2", "manager", "makerting_exe"),
            CAN_ADD_RETURNS => array("superadmin", "admin", "admin_lvl2", "manager", "makerting_exe"),
            CAN_EDIT_DO => array("superadmin", "admin", "admin_lvl2", "manager", "makerting_exe", "sales_aget"),
            CAN_APPROVE_DO => array("superadmin", "admin", "admin_lvl2", "manager"),
            CAN_CANCEL_DO => array("superadmin", "admin", "admin_lvl2", "manager", "makerting_exe"),
            CAN_CREATE_INVOICE => array("superadmin", "admin", "admin_lvl2", "manager", "makerting_exe"),
            CAN_CANCEL_INVOICE => array("superadmin", "admin", "admin_lvl2", "manager", "makerting_exe"),
            CAN_CANCEL_INVOCE_APPROVE => array("superadmin", "admin"),
            CAN_CANCEL_DO_APPROVE => array("superadmin", "admin", "admin_lvl2", "manager", "makerting_exe"),
            CAN_EDIT_INVOICE_INSTALLMENTS => array("superadmin", "admin"),
            CAN_REMOVE_FINE => array("superadmin", "admin","manager","makerting_exe"),
            CAN_PRINT_RECEIPT => array("superadmin", "admin", "admin_lvl2", "caler_lvl_1"),
            CAN_FINISH_INVOICE => array("superadmin", "admin"),
            CAN_CANCEL_PAYMENT => array("superadmin", "admin"),
        );

        return $permisions[$action];
    }

}
