/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
package com.helper;

/**
 *
 * @author Abubakar Butt
 */
public class Global {

    public static String SERVER_PUBLIC_KEY;
    public static String PUBLIC_KEY;
    public static String PRIVATE_KEY;
    public static String PASS;
    public static String SESSION_KEY;

     public static String getSESSION_KEY() {
        return SESSION_KEY;
    }
    public static String getSERVER_PUBLIC_KEY() {
        return SERVER_PUBLIC_KEY;
    }

    public static void setSERVER_PUBLIC_KEY(String sERVER_PUBLIC_KEY) {
        SERVER_PUBLIC_KEY = sERVER_PUBLIC_KEY;
    }

    public static String getPUBLIC_KEY() {
        return PUBLIC_KEY;
    }

    public static void setSESSION_KEY(String sESSION_KEY) {
        SESSION_KEY = sESSION_KEY;
    }
    
    public static void setPUBLIC_KEY(String pUBLIC_KEY) {
        PUBLIC_KEY = pUBLIC_KEY;
    }

    public static String getPRIVATE_KEY() {
        return PRIVATE_KEY;
    }

    public static void setPRIVATE_KEY(String pRIVATE_KEY) {
        PRIVATE_KEY = pRIVATE_KEY;
    }

    public static String getPASS() {
        return PASS;
    }

    public static void setPASS(String pASS) {
        PASS = pASS;
    }

}
