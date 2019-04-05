package ru.volgactf.shop;

import org.springframework.boot.SpringApplication;
import org.springframework.boot.autoconfigure.SpringBootApplication;
import org.springframework.boot.web.servlet.support.SpringBootServletInitializer;

@SpringBootApplication
public class ShopApplication extends SpringBootServletInitializer {
    
    public static void main(String[] args) {
        SpringApplication.run(ShopApplication.class, args);
    }
}
