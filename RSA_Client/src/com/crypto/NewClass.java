/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
package com.crypto;

import bigjava.math.BigInteger;
import java.security.GeneralSecurityException;
import java.security.NoSuchAlgorithmException;
import javax.crypto.KeyGenerator;
import javax.crypto.SecretKey;

/**
 *
 * @author Abubakar Butt
 */
public class NewClass {
    public static void main(String a[]) throws NoSuchAlgorithmException, GeneralSecurityException
    {
        System.err.println(AES_net.decrypt("OTY3MWE0MTg0YjA3ZjczMmFiODZjYjUwN2UzYmFhNTk=","957B0137891B23B008480709112139D3"));
    }
    public static String byteArrayToHexString(byte[] b) {
    StringBuilder sb = new StringBuilder(b.length * 2);
    for (int i = 0; i < b.length; i++) {
        int v = b[i] & 0xff;
        if (v < 16) {
            sb.append('0');
        }
        sb.append(Integer.toHexString(v));
    }
    return sb.toString().toUpperCase();
}
}
