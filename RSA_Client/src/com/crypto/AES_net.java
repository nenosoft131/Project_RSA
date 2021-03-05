package com.crypto;

import static com.crypto.NewClass.byteArrayToHexString;
import java.security.GeneralSecurityException;
import java.security.NoSuchAlgorithmException;
import javax.crypto.Cipher;
import javax.crypto.KeyGenerator;
import javax.crypto.SecretKey;
import javax.crypto.spec.SecretKeySpec;
/**
Aes encryption
*/
public class AES_net
{

public static String GenrateKey() throws NoSuchAlgorithmException
{
     KeyGenerator gen = KeyGenerator.getInstance("AES");
     gen.init(128); /* 128-bit AES */
     SecretKey secret = gen.generateKey();
     byte[] binary = secret.getEncoded();
     return byteArrayToHexString(binary);
}
   public static String decrypt(String encryptedtext,String KEY) throws GeneralSecurityException {
  try {  SecretKeySpec sks = new SecretKeySpec(hexStringToByteArray(KEY), "AES");
    Cipher cipher = Cipher.getInstance("AES");
    cipher.init(Cipher.DECRYPT_MODE, sks);
    byte[] original = cipher.doFinal(hexStringToByteArray(encryptedtext.trim()));
    return new String(original);
  }catch (Exception e)
  {
      System.out.println(e.toString());
  }
  return "";
}
   
   public static String displayCharValues(String s) {
    StringBuilder sb = new StringBuilder();
    for (char c : s.toCharArray()) {
        sb.append((int) c).append(",");
    }
    return sb.toString();
}
   public static String encrypt(final String plaintext,String KEY) throws GeneralSecurityException {
    SecretKeySpec sks = new SecretKeySpec(hexStringToByteArray(KEY), "AES");
    Cipher cipher = Cipher.getInstance("AES");
    cipher.init(Cipher.ENCRYPT_MODE, sks, cipher.getParameters());
    byte[] encrypted = cipher.doFinal(plaintext.getBytes());
    return byteArrayToHexString(encrypted);
}

public static byte[] hexStringToByteArray(String s) {
    byte[] b = new byte[s.length() / 2];
    for (int i = 0; i < b.length; i++) {
        int index = i * 2;
        int v = Integer.parseInt(s.substring(index, index + 2), 16);
        b[i] = (byte) v;
    }
    return b;
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