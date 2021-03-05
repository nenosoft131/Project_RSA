package com.crypto;

import bwmorg.bouncycastle.util.encoders.Base64;
import com.helper.Helper;
import java.security.*;
import javax.crypto.*;
import javax.crypto.spec.*;

public class AES {


    public static String encrypt(String plainText, String password) throws Exception
           {
               
            String s=Helper.KEY_Hash(password);
            Key aesKey = new SecretKeySpec(s.getBytes(), "AES");
            Cipher cipher = Cipher.getInstance("AES");
            // encrypt the text
            cipher.init(Cipher.ENCRYPT_MODE, aesKey);
            byte[] encrypted = cipher.doFinal(plainText.getBytes());
            String ss=new String(Base64.encode(encrypted));
            return ss;
    }

    public static String decrypt(String encryptedText, String password) throws Exception
    {
       
            Key aesKey = new SecretKeySpec(Helper.KEY_Hash(password).getBytes(), "AES");
            Cipher cipher = Cipher.getInstance("AES");
            cipher.init(Cipher.DECRYPT_MODE, aesKey);
            String decrypted = new String(cipher.doFinal(Base64.decode(encryptedText)));
            return  decrypted;
         
        
    }
}
