/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
package com.helper;

import bigjava.math.BigInteger;
import bwmorg.bouncycastle.util.encoders.Base64;
import java.io.UnsupportedEncodingException;
import java.net.URLEncoder;
import java.net.URLDecoder;
import java.security.KeyFactory;
import java.security.MessageDigest;
import java.security.NoSuchAlgorithmException;
import java.security.PrivateKey;
import java.security.PublicKey;
import java.security.spec.InvalidKeySpecException;
import java.security.spec.PKCS8EncodedKeySpec;
import java.security.spec.X509EncodedKeySpec;

/**
 *
 * @author Abubakar Butt
 */
public class Helper {

    public static final String ALGORITHM = "RSA";

    public static String encodeurl(String data) throws UnsupportedEncodingException {

        data = URLEncoder.encode(data, "UTF-8");

        return data;
    }

    public static String decodeurl(String data) throws UnsupportedEncodingException {

        data = URLDecoder.decode(data, "UTF-8");

        return data;
    }

    public static PrivateKey importPrivateKey(String key) throws InvalidKeySpecException, NoSuchAlgorithmException {

        byte yourPrKey[] = Base64.decode(key);
        return KeyFactory.getInstance(ALGORITHM).generatePrivate(new PKCS8EncodedKeySpec(yourPrKey));
    }

    public static PublicKey importPublicKey(String key) throws Exception {

        byte yourPKey[] = Base64.decode(key);
        return KeyFactory.getInstance(ALGORITHM).generatePublic(new X509EncodedKeySpec(yourPKey));
    }

    public static String hash(String pass) throws Exception {
        MessageDigest md = MessageDigest.getInstance("SHA-256");
        byte[] thedigest = md.digest(pass.getBytes("UTF-8"));
        StringBuffer sb = new StringBuffer();
        for (int i = 0; i < thedigest.length; i++) {
            sb.append(Integer.toString((thedigest[i] & 0xff) + 0x100, 16).substring(1));
        }
        return sb.toString();
    }

    public static String KEY_Hash(String input) throws NoSuchAlgorithmException {
        MessageDigest md = MessageDigest.getInstance("MD5");
        byte[] messageDigest = md.digest(input.getBytes());
        BigInteger number = new BigInteger(1, messageDigest);
        String hashtext = number.toString(16);
        // Now we need to zero pad it if you actually want the full 32 chars.
        while (hashtext.length() < 32) {
            hashtext = "0" + hashtext;
        }
        return hashtext.substring(0, 16);
    }

}
