package ru.volgactf.task.utils;

import jakarta.xml.bind.DatatypeConverter;
import java.security.MessageDigest;
import java.security.NoSuchAlgorithmException;

public class Utils {

    public static String getMd5(Object... args) {
        try {
            MessageDigest md = MessageDigest.getInstance("MD5");
            for (Object arg : args) {
                if(arg != null)
                    md.update(String.valueOf(arg).getBytes());
            }
            byte[] digest = md.digest();
            String hash = DatatypeConverter.printHexBinary(digest).toLowerCase();
            return hash;
        } catch (NoSuchAlgorithmException ex) {
            return null;
        }
    }

}
